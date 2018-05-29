function addEventListenersProfile() {

	let editProfileButton = document.querySelector(".container-fluid .row aside div a#edit_profile");
	if (editProfileButton !== null)
		editProfileButton.addEventListener('click', editProfileForm);

	let createProjectButton = document.querySelector(".container-fluid div#options a#new_project");
	if (createProjectButton !== null)
		createProjectButton.addEventListener('click', createProjectForm);


	let searchUserProjectForm = document.querySelector("form.searchbar");
	if (searchUserProjectForm !== null)
		searchUserProjectForm.addEventListener('submit', searchUserProjects);

	let searchRoleUserProjectButtons = document.querySelectorAll("div#role_button a.dropdown-item");
	for (let i = 0; i < searchRoleUserProjectButtons.length; i++) {
		searchRoleUserProjectButtons[i].addEventListener('click', searchByRole);
	}

	let leaveProjectBtn = document.querySelectorAll("div#projects div.project div.project_info button");
	for (let i = 0; i < leaveProjectBtn.length; i++) {
		leaveProjectBtn[i].addEventListener('click', leaveProject);
	}

	let pagination = document.querySelectorAll("div.container-fluid div.row section div#projects div#pagination_section.post ul.pagination li a");
	for (let i = 0; i < pagination.length; i++) {
		pagination[i].addEventListener('click', getProjects);
	}
}

function encodeForAjax(data) {
	if (data == null) return null;
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&');
}


function sendAjaxRequest(method, url, data, handler) {
	let request = new XMLHttpRequest();

	request.open(method, url, true);
	request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	request.addEventListener('load', handler);
	if (data != null)
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
	
	if(data.success){
		let section = document.querySelector("div.container-fluid .row section");
		section.innerHTML = data.html;
	}

}

function showCreateProjectForm() {
	let data = JSON.parse(this.responseText);

	if(data.success){
		let section = document.querySelector("div.container-fluid .row section");
		section.innerHTML = data.html;
		let submitProject = document.querySelector("div.new_project div#form_options a.btn-success");
		submitProject.addEventListener('click', createProjectAction);
	}
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
		{
			name: project_name, description: project_description, public: project_public,
			user_id: user_id, categories: categories
		}, showProfileUpdated);
}

function showProfileUpdated() {
	document.body.innerHTML = this.responseText;
}

function getSelectValues(select) {
	var result = [];
	var options = select.options;
	var opt;

	for (var i = 0, iLen = options.length; i < iLen; i++) {
		opt = options[i];

		if (opt.selected) {
			result.push(opt.value || opt.text);
		}
	}
	return result;
}


function searchUserProjects(event) {
	event.preventDefault();

	let inputValue = event.target.childNodes[1].value;

	sendAjaxRequest("POST", event.target.action, { search: inputValue }, showUserProjects);
}

function searchByRole(event) {
	event.preventDefault();

	let role = event.target.innerHTML;

	sendAjaxRequest("POST", event.target.href, { role: role }, showUserProjects);
}

function showUserProjects() {

	let data = JSON.parse(this.responseText);

	if(data.success){
		let div = document.querySelector("div#projects");
		div.innerHTML = data.html;
	
		addEventListenersProfile();
	}

}


function leaveProject(event) {
	event.preventDefault();

	let href = event.currentTarget.getAttribute('href');

	let index = href.indexOf('projects');
	let index2 = href.indexOf('leave');
	let project_id = href.substring(index + 9, index2 - 1);

	swal({
		title: "Are you sure you want to leave this project?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				sendAjaxRequest('post', href, { project_id: project_id }, leaveProjectHandler);
			}
		});
}

function leaveProjectHandler() {

	let data = JSON.parse(this.responseText);

	if (data.success) {

		swal("You left " + data.project_name + " successfully!", {
			icon: "success",
		});

		let div = document.querySelector("div#projects");
		div.innerHTML = data.html;

	} else {
		swal("Failed to leave the project!", {
			icon: "error",
		});
	}

}

function getProjects(event) {
	event.preventDefault();

	sendAjaxRequest("POST", event.target.href, null, showUserProjects);
}



addEventListenersProfile();