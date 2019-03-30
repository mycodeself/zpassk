import {sha512} from "js-sha512";
import {splitString} from "./utils";

const openpgp = require('openpgp');

export function sha512AndSplit(source) {
  const passwordHashed = sha512(source);
  return splitString(passwordHashed);
}

export function encryptCredentials(key, username, password) {
  return new Promise(resolve => {

    const keyString = Buffer.from(key).toString('hex')
    const keyPair = getKeyPair();

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
        await privKeyObj.decrypt(sessionStorage.getItem('hash')) // TODO: Change hash key

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
    const keyPair = getKeyPair();

    const prvKeyObj = (await openpgp.key.readArmored(keyPair.privateKey)).keys[0];
    await prvKeyObj.decrypt(sessionStorage.getItem('hash'));

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

function getKeyPair() {
  const prvKeyElement = document.getElementById('prv_key');
  const pubKeyElement = document.getElementById('pub_key');
  const privateKey = atob(prvKeyElement.value);
  const publicKey = atob(pubKeyElement.value);

  return {
    privateKey,
    publicKey
  }
}