import 'materialize-css/dist/js/materialize';
import 'babel-polyfill'
import '../scss/app.scss'
import initializeFlashMessages from './flashMessages'
import {addFormListeners} from "./formListeners";
import openpgp from 'openpgp'

openpgp.initWorker({ path:'/openpgp/compat/openpgp.worker.min.js' });

window.onload = function (e) {
  initializeFlashMessages();
  addFormListeners();

  const prvKeyElement = document.getElementById('prv_key');
  const pubKeyElement = document.getElementById('pub_key');
  const privateKey = atob(prvKeyElement.value);
  const publicKey = atob(pubKeyElement.value);
  console.log(privateKey + '\n' + publicKey);
};
