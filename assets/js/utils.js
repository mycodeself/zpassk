export function splitString(str) {
	const parts = [];
	let half = str.length / 2;

	parts.push(str.substring(0, half));
	parts.push(str.substring(half, str.length));

	return parts;
}
