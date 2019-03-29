import {sha512} from "js-sha512";

export function sha512AndSplit(source) {
	const passwordHashed = sha512(source);
	return splitString(passwordHashed);
}

export function splitString(str) {
	const parts = [];
	let half = str.length / 2;

	parts.push(str.substring(0, half));
	parts.push(str.substring(half, str.length));

	return parts;
}