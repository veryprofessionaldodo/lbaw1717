function addEventListeners() {
	let editProfileButton = document.querySelector(".container-fluid .row aside div a#edit_profile");
	editProfileButton.addEventListener('click', editProfileForm);

	let createProjectButton = document.querySelector(".container-fluid div#options a#new_project");
	createProjectButton.addEventListener('click', createProjectForm);
	
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

function createProjectForm(event) {
	event.preventDefault();
	sendAjaxRequest('get', event.target.href, null, showCreateProjectForm);
}

function showEditProfileForm() {
	let data = JSON.parse(this.responseText);
	//insert verification of success

	let section = document.querySelector("div.container-fluid .row section");
	section.innerHTML = data.html;
	let submitEdit = document.querySelector("div.edit_profile div#form_options a.btn-success");
	submitEdit.addEventListener('click', sendEditProfile);
}

function showCreateProjectForm() {
	let data = JSON.parse(this.responseText);
	//insetrt verification of success

	let section = document.querySelector("div.container-fluid .row section");
	section.innerHTML = data.html;
	let submitEdit = document.querySelector("div.new_project div#form_options a.btn-success");
	submitEdit.addEventListener('click', createProjectAction);
}

function sendEditProfile(event) {
	event.preventDefault();

	let new_name = document.querySelector("input[name='user_name']").value;
	let new_username = document.querySelector("input[name='user_username']").value;
	let new_email = document.querySelector("input[name='user_email']").value;
	let new_image = document.querySelector("input[name='user_image']").value; // change this later TODO

	sendAjaxRequest('post', event.target.href, {name: new_name, username: new_username, 
								email: new_email, image: new_image} , showProfileUpdated);
}

function createProjectAction(event) {
	event.preventDefault();

	let project_name = document.querySelector("input[name='project_name']").value;
	let project_description = document.querySelector("input[name='project_description']").value;
	let project_public = document.querySelector("input#public").value;
	
	let select = document.querySelector("select");
	let categories = getSelectValues(select);

	let index = event.target.href.indexOf('?');
	let user_id_text = event.target.href.substring(index + 1, event.target.href.length);
	let index2 = user_id_text.indexOf('=');
	let user_id = user_id_text.substring(index2 + 1, user_id_text.length);
	let href = event.target.href.substring(0, index);
	
	sendAjaxRequest('post', href, 
	{name: project_name, description: project_description, public: project_public, 
		user_id: user_id, categories: categories}, showProfileUpdated);
}

function showProfileUpdated() {
	//window.location.href = this.responseText;
}

function getSelectValues(select) {
	var result = [];
	var options = select.options;
  	var opt;

	for (var i=0, iLen=options.length; i<iLen; i++) {
		opt = options[i];

		if (opt.selected) {
		result.push(opt.value || opt.text);
		}
	}
	return result;
}

addEventListeners();