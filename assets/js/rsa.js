import cryptico from "cryptico";

export async function generateRSAKeys(passphrase, bits = 2048) {
  return new Promise(resolve => {
    setTimeout(() => {
      resolve(cryptico.generateRSAKey(passphrase, bits));
    }, 10)
  })
}

export async function publicKeyToString(key) {
  return new Promise(resolve => {
    setTimeout(() => {
      resolve(cryptico.publicKeyString(key));
    }, 0)
  })
}