export default function () {
	// flash_messages
	const flashMessages = document.querySelectorAll('.flash-message');

	flashMessages.forEach(flash => {
		setTimeout(() => {
			flash.remove();
		}, 3500);
	});
}