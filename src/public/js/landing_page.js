document.addEventListener('DOMContentLoaded', addEventListeners);

function addEventListeners() {

  let sign_in_button = document.querySelector("section #sign #sign_buttons a:first-of-type");
  let sign_up_button = document.querySelector("section #sign #sign_buttons a:last-of-type");

  if (localStorage.form === "sign_in") {
    let form_up = document.querySelector("section #sign form#sign_up");
    let form_in = document.querySelector("section #sign form#sign_in");

    form_in.className = '';
    form_up.className = 'hide';
    sign_in_button.id = 'selected';
    sign_up_button.id = '';
  }


  sign_in_button.addEventListener('click', switch_signin_form);
  sign_up_button.addEventListener('click', switch_signup_form);
}

function switch_signin_form() {
  let form_in = document.querySelector("section #sign form#sign_in");
  let form_up = document.querySelector("section #sign form#sign_up");
  let sign_up_button = document.querySelector("section #sign #sign_buttons a:last-of-type");

  if (form_in.className == 'hide') {
    localStorage.form = "sign_in";
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

  if (form_up.className == 'hide') {
    localStorage.form = "sign_up";
    form_up.className = '';
    form_in.className = 'hide';
    this.id = 'selected';
    sign_in_button.id = '';
  }
}