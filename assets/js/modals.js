import tingle from 'tingle.js';
import 'tingle.js/dist/tingle.css'

export function createAndOpenPasswordModal(data) {
  const modal = new tingle.modal({
    footer: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "Close",
    onClose: function() {
      modal.destroy();
    },
    beforeClose: function() {
      return true; // close the modal
    }
  });


// set content
  modal.setContent(`
    <div>
      <header>
        <h5>${data.name}</h5>
        <p class="right-align">
            <a target="_blank" href="${data.url}" title="${data.url}">
                ${data.url} <i class="tiny material-icons">open_in_new</i>
            </a>           
        </p>
      </header>
      <div>
        <div class="input-field col s6">
            <i class="material-icons prefix">person</i>
            <input type="text" value="${data.username}" readonly />
        </div>
        
        <div class="input-field col s6">
          <i class="material-icons prefix">vpn_key</i>
          <input type="password" value="${data.password}" readonly />
        </div>         
      </ul>
      <div class="center-align">
        <button id="modal_copy_btn" type="button" class="waves-effect waves-light btn btn">Copy</button>
        <button id="modal_copy_open_btn" type="button" class="waves-effect waves-light btn green darken-1">Copy & Open</button>
      </div>
    </div>
`);

  // open modal
  modal.open();

  const modalCopyBtn = document.getElementById('modal_copy_btn');
  const modalCopyAndOpenBtn = document.getElementById('modal_copy_open_btn');
  modalCopyBtn.addEventListener('click', function () {
    copyToClipboard(data.password);
    modal.close();
  })
  modalCopyAndOpenBtn.addEventListener('click', function () {
    copyToClipboard(data.password);
    window.open(data.url);
    modal.close();
  })
}

export function createAndOpenConfirmDeletePasswordModal(password) {
  const modal = new tingle.modal({
    footer: true,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "Close",
    onClose: function() {
      modal.destroy();
    },
    beforeClose: function() {
      return true; // close the modal
    }
  });

  modal.setContent(`
    <div>
      <p>Are you sure you want to delete the password "${password.name}"? This action cannot be undone</p>
    </div>
`);

  modal.open();

  modal.addFooterBtn('Cancel', 'tingle-btn tingle-btn--default tingle-btn--pull-right', function() {
    modal.close();
  });

  modal.addFooterBtn('Delete', 'tingle-btn tingle-btn--danger tingle-btn--pull-right', function() {
    modal.close();
    window.location.href = `/passwords/${password.id}/delete`;
  });

  modal.open();
}

function copyToClipboard(str) {
  const element = document.createElement('textarea');
  element.value = str;
  document.body.appendChild(element);
  element.select();
  document.execCommand('copy');
  document.body.removeChild(element);
}