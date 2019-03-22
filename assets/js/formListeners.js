import {sha512AndSplit} from "./utils";
import openpgp, {message} from 'openpgp'

export function addFormListeners() {
	addFormSubmitListener('security_form', securityFormListener);
	addFormSubmitListener('recovery_password_form', recoveryPasswordFormListener);
	addFormSubmitListener('update_user_form', updateUserFormListener);
}

function updateUserFormListener(event) {
	event.preventDefault();
	const passwordInput = document.getElementById('update_user_newPassword');

	if(passwordInput) {
		const hashParts = sha512AndSplit(passwordInput.value);
		const publicKeyInput = document.getElementById('update_user_privateKey');
		const privateKeyInput = document.getElementById('update_user_publicKey');
		const emailInput = document.getElementById('update_user_email');
		passwordInput.value = hashParts[0];

		const options = {
			userIds: [{ email:emailInput.value }],
			curve: "ed25519",
			passphrase: hashParts[1]
		};

		openpgp.generateKey(options).then(function(key) {
			const privkey = btoa(key.privateKeyArmored);  //b64 '-----BEGIN PGP PRIVATE KEY BLOCK ... '
			const pubkey = btoa(key.publicKeyArmored);   //b64 '-----BEGIN PGP PUBLIC KEY BLOCK ... '

			console.log(atob(privkey));
			console.log(atob(pubkey));

			const options = {
				message: message.fromText(privkey),
				passwords: [hashParts[1]],
				armor: false
			};

			openpgp.encrypt(options).then(function(ciphertext) {
				const encrypted = ciphertext.message.packets.write(); // get raw encrypted packets as Uint8Array
				console.log(encrypted)
			});

			publicKeyInput.value = privkey;
			privateKeyInput.value = pubkey;
			// event.target.submit();
		});

	}


}

function securityFormListener(event) {
	event.preventDefault();
	const passwordInput = document.querySelector('input[type=password]');

	if(passwordInput) {
		const hashParts = sha512AndSplit(passwordInput.value);
		console.log(hashParts[0]);
		passwordInput.value = hashParts[0];
		sessionStorage.setItem('hash', hashParts[1]);
		event.target.submit();
	}
}

function recoveryPasswordFormListener(event) {
	event.preventDefault();
	const first = document.getElementById('change_password_newPassword_first');
	const second = document.getElementById('change_password_newPassword_second');

	if(first.value && first.value === second.value) {
		const hashParts = sha512AndSplit(first.value);
		first.value = hashParts[0];
		second.value = hashParts[0];
	}
	event.target.submit();
}



function addFormSubmitListener(formId, listener) {
	const form = document.getElementById(formId);
	if(form) {
		form.addEventListener('submit', listener)
	}
}