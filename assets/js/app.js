import 'materialize-css/dist/js/materialize';
import 'babel-polyfill'
import '../scss/app.scss'
import initializeFlashMessages from './flashMessages'
import {addFormListeners} from "./formListeners";
import openpgp from 'openpgp'

initializeFlashMessages();
addFormListeners();
openpgp.initWorker({ path:'/openpgp/compat/openpgp.worker.min.js' })

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
