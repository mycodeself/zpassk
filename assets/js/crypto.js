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
        const privKeyObj = (await openpgp.key.readArmored(keyPair.privateKey)).keys[0]

        const options = {
          message: openpgp.message.fromBinary(key),
          publicKeys: (await openpgp.key.readArmored(keyPair.publicKey)).keys,
          privateKeys: [privKeyObj]
        };

        openpgp.encrypt(options).then(keyCipher => {
          const keyEncrypted = keyCipher.data;

          resolve({
            username: btoa(usernameEncrypted),
            password: btoa(passwordEncrypted),
            key: btoa(keyEncrypted)
          })
        })
      })
    })

  })
}

export function decryptCredentials(key, username, password) {
  return new Promise(async resolve => {
    const keyPair = await getKeyPair();

    const prvKeyObj = (await openpgp.key.readArmored(keyPair.privateKey)).keys[0];

    const options = {
      message: await openpgp.message.readArmored(key),
      publicKeys: (await openpgp.key.readArmored(keyPair.publicKey)).keys,
      privateKeys: [prvKeyObj],
      format: 'binary'
    };
    // decrypt key
    openpgp.decrypt(options).then(async keyPlain => {
      const keyString = Buffer.from(keyPlain.data).toString('hex');
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
  })
}

export function reencryptKey(keyEncrypted, publicKey) {
  return new Promise(async resolve => {

    const plainKey = await decryptKey(keyEncrypted);
    const keyArmored = await encryptKeyWithPublicKey(plainKey, publicKey);

    resolve(btoa(keyArmored))
  });
}

async function encryptKeyWithPublicKey(key, publicKey) {
  const keyPair = await getKeyPair();
  const privKeyObj = (await openpgp.key.readArmored(keyPair.privateKey)).keys[0];

  const options = {
    message: openpgp.message.fromBinary(key),
    publicKeys: (await openpgp.key.readArmored(publicKey)).keys,
    privateKeys: [privKeyObj]
  };

  const cipher = await openpgp.encrypt(options);

  return cipher.data;
}

async function decryptKey(key) {

  const keyPair = await getKeyPair();
  const prvKeyObj = (await openpgp.key.readArmored(keyPair.privateKey)).keys[0];
  const options = {
    message: await openpgp.message.readArmored(key),
    publicKeys: (await openpgp.key.readArmored(keyPair.publicKey)).keys,
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