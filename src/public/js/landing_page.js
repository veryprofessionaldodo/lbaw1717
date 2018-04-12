function addEventListeners() {
  let sign_in_button = document.querySelector("section #sign #sign_buttons a:first-of-type");
  let sign_up_button = document.querySelector("section #sign #sign_buttons a:last-of-type");

  sign_in_button.addEventListener('click',switch_signin_form);
  sign_up_button.addEventListener('click',switch_signup_form);
}

function switch_signin_form() {
  let form_in = document.querySelector("section #sign form#sign_in");
  let form_up = document.querySelector("section #sign form#sign_up");
  let sign_up_button = document.querySelector("section #sign #sign_buttons a:last-of-type");

  if(form_in.className == 'hide') {
    form_in.className = '';
    form_up.className = 'hide';
    this.id = 'selected';
    sign_up_button.id = '';
  }
}

function switch_signup_form() {
  let form_in = document.querySelector("section #sign form#sign_in");
  let form_up = document.querySelector("section #sign form#sign_up");
  let sign_in_button = document.querySelector("section #sign #sign_buttons a:first-of-type");

  if(form_up.className == 'hide') {
    form_up.className = '';
    form_in.className = 'hide';
    this.id = 'selected';
    sign_in_button.id = '';
  }
}

/*
function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);
  request.send(encodeForAjax(data));
}*/

addEventListeners();
