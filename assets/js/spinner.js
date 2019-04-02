function open() {
  const spinner = getSpinnerElement();
  spinner.classList.add('is-open');
}

function close() {
  const spinner = getSpinnerElement();
  spinner.classList.remove('is-open');
}

function getSpinnerElement() {
  return document.getElementById('spinner');
}

const spinner = {open, close};

export default spinner;