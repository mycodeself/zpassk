import {sha512} from "js-sha512";
import {splitString} from "./utils";
import {SESSION_HASH_KEY} from "./constants";

const openpgp = require('openpgp');

export function sha512AndSplit(source) {
  const passwordHashed = sha512(source);
  return splitString(passwordHashed);
}

export function encryptCredentials(key, username, password) {
  return new Promise(async resolve => {

    const keyString = Buffer.from(key).toString('hex');
    const keyPair = await getKeyPair();

    let options = {
      passwords: [keyString],
    };

    // encrypt username
    options.message = openpgp.message.fromText(username);
    openpgp.encrypt(options).then(usernameCipher => {
      const usernameEncrypted = usernameCipher.data;

      //encrypt password
      options.message = openpgp.message.fromText(password);
      openpgp.encrypt(options).then(async passwordCipher => {
        const passwordEncrypted = passwordCipher.data;

        //encrypt aes key
        const keyEncrypted = await encryptKey(key, keyPair.publicKey, keyPair.privateKey);
        resolve({
          username: btoa(usernameEncrypted),
          password: btoa(passwordEncrypted),
          key: btoa(keyEncrypted)
        })
      })
    })

  })
}

export function decryptCredentials(key, username, password, ownerPubKey) {
  return new Promise(async resolve => {
    const keyPair = await getKeyPair();
    const signPubKey = ownerPubKey ? ownerPubKey : keyPair.publicKey;
    const keyPlain = await decryptKey(key, keyPair.privateKey, signPubKey)
    const keyString = Buffer.from(keyPlain).toString('hex');

    let options = {
      passwords: [keyString],
      message: await openpgp.message.readArmored(username)
    };
    // decrypt username
    openpgp.decrypt(options).then(async usernamePlain => {
      // decrypt password
      options.message = await openpgp.message.readArmored(password);
      openpgp.decrypt(options).then(passwordPlain => {

        resolve({
          username: usernamePlain.data,
          password: passwordPlain.data
        })

      })
    });
  });
}

export function reencryptKey(keyEncrypted, publicKey) {
  return new Promise(async resolve => {
    const keyPair = await getKeyPair();
    const plainKey = await decryptKey(keyEncrypted, keyPair.privateKey, keyPair.publicKey);
    const key = await encryptKey(plainKey, publicKey, keyPair.privateKey);

    resolve(btoa(key))
  });
}

async function encryptKey(key, pubKeyArmored, signPrvKeyArmored) {
  const privKeyObj = (await openpgp.key.readArmored(signPrvKeyArmored)).keys[0];
  const pubKeyObj = (await openpgp.key.readArmored(pubKeyArmored)).keys;

  const options = {
    message: openpgp.message.fromBinary(key),
    publicKeys: pubKeyObj,
    privateKeys: [privKeyObj]
  };

  const cipher = await openpgp.encrypt(options);

  return cipher.data;
}

async function decryptKey(key, prvKeyArmored, signPubKeyArmored) {
  const prvKeyObj = (await openpgp.key.readArmored(prvKeyArmored)).keys[0];
  const pubKeyObj = (await openpgp.key.readArmored(signPubKeyArmored)).keys;

  const options = {
    message: await openpgp.message.readArmored(key),
    publicKeys: pubKeyObj,
    privateKeys: [prvKeyObj],
    format: 'binary'
  };

  const plain = await openpgp.decrypt(options);

  return plain.data;
}

async function getKeyPair() {
  const prvKeyElement = document.getElementById('prv_key');
  const pubKeyElement = document.getElementById('pub_key');
  const privateKeyEncrypted = atob(prvKeyElement.value);
  const publicKey = atob(pubKeyElement.value);

  const options = {
    message: await openpgp.message.readArmored(privateKeyEncrypted),
    passwords: [sessionStorage.getItem(SESSION_HASH_KEY)],
  };

  const key = await openpgp.decrypt(options);
  const privateKey = key.data;

  return {
    privateKey,
    publicKey
  }

}