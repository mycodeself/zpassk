import {decryptCredentials, reencryptKey} from "./crypto";
import {createAndOpenPasswordModal} from "./modals";
import spinner from "./spinner";
import $ from 'jquery'

var openpgp = require('openpgp');

export function addButtonListeners() {
  addOnClickListenerByClass('js-view-password-btn', viewPasswordButtonListener);
  addOnClickListenerByClass('js-share-password-btn', sharePasswordButtonListener);
}

function viewPasswordButtonListener(event) {
  event.preventDefault();
  const key = atob(event.target.dataset.k);
  const username = atob(event.target.dataset.u);
  const password = atob(event.target.dataset.p);
  const name = event.target.dataset.name;
  const url = event.target.dataset.url;

  spinner.open();

  decryptCredentials(key, username, password).then(data => {
    data.name = name;
    data.url = url;
    spinner.close();
    //open modal with data
    createAndOpenPasswordModal(data);
  })
}

function sharePasswordButtonListener(event) {
  event.preventDefault();
  const keyArmored = atob(event.target.dataset.k);
  const publicKeyArmored = atob(event.target.dataset.pk);
  const userId = event.target.dataset.u;

  spinner.open();
  reencryptKey(keyArmored, publicKeyArmored).then(key => {
    console.log(key);

    const url = '/ajax' + window.location.pathname;

    $.post(url, {userId, key}, function () {
      console.log('success');
    });
    spinner.close();
  })

}

function addOnClickListenerByClass(className, listener) {
  const buttons = document.getElementsByClassName(className);
  if(buttons) {
    for (let i=0; i < buttons.length; i++) {
      buttons[i].addEventListener('click', listener);
    }
  }
}