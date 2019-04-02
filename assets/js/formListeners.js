import openpgp, {crypto} from 'openpgp'

import spinner from './spinner'
import {encryptCredentials, sha512AndSplit} from "./crypto";

export function addFormListeners() {
	addFormSubmitListener('login_form', loginFormListener);
	addFormSubmitListener('recovery_password_form', recoveryPasswordFormListener);
	addFormSubmitListener('user_form', registerUserFormListener);
	addFormSubmitListener('password_form', addPasswordFormListener)

}

function loginFormListener(event) {
	event.preventDefault();
	const passwordInput = document.querySelector('input[type=password]');

	if(passwordInput) {
		spinner.open();
		const hashParts = sha512AndSplit(passwordInput.value);
		console.log(hashParts[0]);
		passwordInput.value = hashParts[0];
		sessionStorage.setItem('hash', hashParts[1]);
		event.target.submit();
		spinner.close();
	}
}

function registerUserFormListener(event) {
	event.preventDefault();
	const firstPasswordInput = document.getElementById('user_plainPassword_first');
	const secondPasswordInput = document.getElementById('user_plainPassword_second');
	const emailInput = document.getElementById('user_email');
	const usernameInput = document.getElementById('user_username');
	const privateKeyInput = document.getElementById('user_privateKey');
	const publicKeyInput = document.getElementById('user_publicKey');

	if(firstPasswordInput.value !== secondPasswordInput.value || firstPasswordInput.value.length < 6) {
		event.target.submit();
		return;
	}

	spinner.open();
	const hashParts = sha512AndSplit(firstPasswordInput.value);

	const options = {
		userIds: [{ username: usernameInput.value, email:emailInput.value }],
		curve: "ed25519",
		passphrase: hashParts[1]
	};

	openpgp.generateKey(options).then(function(key) {
		publicKeyInput.value = btoa(key.publicKeyArmored);
		privateKeyInput.value = btoa(key.privateKeyArmored);
		firstPasswordInput.value = hashParts[0];
		secondPasswordInput.value = hashParts[0];
		spinner.close();
		event.target.submit();
	});

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

function addPasswordFormListener(event) {
	event.preventDefault();
	const usernameInput = document.getElementById('password_form_username');
	const passwordInput = document.getElementById('password_form_password');
	const keyInput = document.getElementById('password_form_key');

	spinner.open();

	crypto.random.getRandomBytes(32).then(key => {
		encryptCredentials(key, usernameInput.value, passwordInput.value).then(data => {
			usernameInput.value = data.username;
			passwordInput.value = data.password;
			keyInput.value = data.key;
			spinner.close();
			event.target.submit();
		})
	});
}

function addFormSubmitListener(formId, listener) {
	const form = document.getElementById(formId);
	if(form) {
		form.addEventListener('submit', listener)
	}
}