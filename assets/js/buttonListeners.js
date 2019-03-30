import {decryptCredentials} from "./crypto";

var openpgp = require('openpgp');

export function addButtonListeners() {
  addOnClickListenerByClass('js-view-password-btn', viewPasswordButtonListener)
}

function viewPasswordButtonListener(event) {
  event.preventDefault();
  const key = atob(event.target.dataset.k);
  const username = atob(event.target.dataset.u);
  const password = atob(event.target.dataset.p);
  const name = event.target.dataset.name;
  const url = event.target.dataset.url;

  decryptCredentials(key, username, password).then(data => {
    data.name = name;
    data.url = url;

    //open modal with data
    console.log(data);
  })
}

function addOnClickListenerById(buttonId, listener) {
  const button = document.getElementById(buttonId);
  if(button) {
    button.addEventListener('click', listener)
  }
}

function addOnClickListenerByClass(className, listener) {
  const buttons = document.getElementsByClassName(className);
  if(buttons) {
    for (let i=0; i < buttons.length; i++) {
      buttons[i].addEventListener('click', listener);
    }
  }
}