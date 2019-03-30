import 'materialize-css/dist/js/materialize';
import 'babel-polyfill'
import openpgp from 'openpgp'

import '../scss/app.scss'
import initializeFlashMessages from './flashMessages'
import {addFormListeners} from "./formListeners";
import {addButtonListeners} from "./buttonListeners";

openpgp.initWorker({ path:'/openpgp/compat/openpgp.worker.min.js' });

window.onload = function (e) {
  initializeFlashMessages();
  addFormListeners();
  addButtonListeners();
};
