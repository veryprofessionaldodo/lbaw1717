function addEventListeners() {
	let editProfileButton = document.querySelector(".container-fluid .row aside div a#edit_profile");
	editProfileButton.addEventListener('click', editProfileForm);
}

function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function(k){
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}


function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);
  if(data != null)
  	request.send(encodeForAjax(data));
  else
  	request.send();
}

function editProfileForm(event) {
	event.preventDefault();
	sendAjaxRequest('get', event.target.href, null, showEditProfileForm);
}

function showEditProfileForm() {
	let data = JSON.parse(this.responseText);
	//insert verification of success

	let section = document.querySelector("div.container-fluid .row section");
	section.innerHTML = data.html;
	let submitEdit = document.querySelector("div.edit_profile div#form_options a.btn-success");
	submitEdit.addEventListener('click', sendEditProfile);
}

function sendEditProfile(event) {
	event.preventDefault();

	let new_name = document.querySelector("input[name='user_name']").value;
	let new_username = document.querySelector("input[name='user_username']").value;
	let new_email = document.querySelector("input[name='user_email']").value;
	let new_image = document.querySelector("input[name='user_image']").value; // change this later TODO

	console.log(event.target.href);
	sendAjaxRequest('post', event.target.href, {name: new_name, username: new_username, 
								email: new_email, image: new_image} , showProfileUpdated);
}

function showProfileUpdated() {
	console.log(this.responseText);
	window.location.href = this.responseText;
}

addEventListeners();