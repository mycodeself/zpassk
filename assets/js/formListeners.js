import {sha512AndSplit} from "./utils";
import openpgp, {message} from 'openpgp'

export function addFormListeners() {
	addFormSubmitListener('login_form', loginFormListener);
	addFormSubmitListener('recovery_password_form', recoveryPasswordFormListener);
	addFormSubmitListener('update_user_form', updateUserFormListener);
	addFormSubmitListener('user_form', registerUserFormListener);

	updateUserEditEmailListener();
	imageInputsListener();
}

function loginFormListener(event) {
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

	const hashParts = sha512AndSplit(firstPasswordInput.value);

	const options = {
		userIds: [{ username: usernameInput.value, email:emailInput.value }],
		curve: "ed25519",
		passphrase: hashParts[1]
	};

	openpgp.generateKey(options).then(function(key) {
		const privkey = btoa(key.privateKeyArmored);  //b64 '-----BEGIN PGP PRIVATE KEY BLOCK ... '
		const pubkey = btoa(key.publicKeyArmored);   //b64 '-----BEGIN PGP PUBLIC KEY BLOCK ... '

		console.log(atob(privkey));
		console.log(atob(pubkey));


		publicKeyInput.value = privkey;
		privateKeyInput.value = pubkey;
		firstPasswordInput.value = hashParts[0];
		secondPasswordInput.value = hashParts[0];
		event.target.submit();
	});

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

function updateUserEditEmailListener() {
	const editEmailBtn = document.getElementById('js-edit-email');
	if(editEmailBtn) {
		const emailField = document.getElementById('update_user_email');
		emailField.disabled = true;
		editEmailBtn.addEventListener('click', function() {
			emailField.disabled = false;
		})
	}
}

function imageInputsListener() {
	const imageInputs = document.getElementsByClassName('image-input');
	if(imageInputs) {
		for(let i = 0; i < imageInputs.length; i++) {
			imageInputs[i].addEventListener('change', function (event)  {
				var reader = new FileReader();
				reader.readAsDataURL(event.target.files[0]);
				reader.onload = function(e) {
					const input = document.getElementById(event.target.id);
					const imagePreview = input.nextElementSibling;
					imagePreview.innerHTML = '';
					const imageNode = document.createElement('IMG');
					imageNode.setAttribute('src', e.target.result);
					imageNode.setAttribute('width', 110);
					imageNode.setAttribute('height', 110);
					imagePreview.appendChild(imageNode);
				}
			})
		}
	}
}

