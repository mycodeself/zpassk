require('materialize-css/dist/js/materialize');
require('../scss/app.scss');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');


// flash_messages
const flashMessages = document.querySelectorAll('.flash-message');

flashMessages.forEach(flash => {
  setTimeout(() => {
    flash.remove();
  }, 3500);
});


// update_user_email
const editEmailBtn = document.getElementById('js-edit-email');
if(editEmailBtn) {
  const emailField = document.getElementById('update_user_email');
  emailField.disabled = true;
  editEmailBtn.addEventListener('click', function() {
    emailField.disabled = false;
  })
}