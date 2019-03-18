import {sha512AndSplit} from "./utils";

export function securityFormListener(event) {
	event.preventDefault();
	const passwordInput = document.querySelector('input[type=password]');

	if(passwordInput) {
		const hashParts = sha512AndSplit(passwordInput.value);
		passwordInput.value = hashParts[0];
		sessionStorage.setItem('hash', hashParts[1]);
		event.target.submit();
	}
}