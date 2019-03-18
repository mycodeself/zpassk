import 'materialize-css/dist/js/materialize';

import '../scss/app.scss'
import initializeFlashMessages from './flashMessages'

import {securityFormListener} from "./formListeners";
import openpgp from 'openpgp/dist/compat/openpgp'



console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

initializeFlashMessages();

const hash = sessionStorage.getItem('hash');
if(hash) {
  console.log(hash);
	openpgp.initWorker({ path: '/openpgp/compat/openpgp.worker.js' })

	 const options = {
		userIds: [{ name:'Jon Smith', email:'jon@example.com' }], // multiple user IDs
		numBits: 4096,
		passphrase: hash
	};

	console.log(options)

	openpgp.generateKey(options).then(function(key) {
		var privkey = key.privateKeyArmored; // '-----BEGIN PGP PRIVATE KEY BLOCK ... '
		var pubkey = key.publicKeyArmored;   // '-----BEGIN PGP PUBLIC KEY BLOCK ... '
		var revocationCertificate = key.revocationCertificate; // '-----BEGIN PGP PUBLIC KEY BLOCK ... '
    console.log(privkey);
    console.log(pubkey);
	}).catch(function(error) {
		console.log(error)
	});

}


const securityForm = document.getElementById('security_form');
if(securityForm) {
	securityForm.addEventListener('submit', securityFormListener)
}



// update_user_email
const editEmailBtn = document.getElementById('js-edit-email');
if(editEmailBtn) {
  const emailField = document.getElementById('update_user_email');
  emailField.disabled = true;
  editEmailBtn.addEventListener('click', function() {
    emailField.disabled = false;
  })
}

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
