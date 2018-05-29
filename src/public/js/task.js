function taskPageEventListeners() {
	let editButton = document.querySelector("button.edit_task");
	let assignTaskUserButton = document.querySelector("div.coordinator_options a#assign_user");

	if (editButton !== null) {
		editButton.addEventListener('click', showEditForm);
	}

	if (assignTaskUserButton !== null) {
		assignTaskUserButton.addEventListener('click', showAssignUserForm);
	}

	// tasks completion
	let tasksCheckbox = document.querySelector("div#checkbox input[type='checkbox']");
	if(tasksCheckbox !== null)
		tasksCheckbox.addEventListener('click', updateTaskCompletion);

	// assign to task to self
	let assignSelfTaskButton = document.querySelector("div.task_options a.claim");
	if(assignSelfTaskButton !== null)
		assignSelfTaskButton.addEventListener('click', assignSelfTask);

	// assign task to other
	let assignTaskOtherForm = document.querySelector("div.coordinator_options form#assign_user_form");
	if (assignTaskOtherForm !== null) {
		assignTaskOtherForm.addEventListener('submit', assignOtherTask);
	}

	//unassign other from task
	let unassignTaskOtherButton = document.querySelector("div.coordinator_options a#unassign_user");
	if (unassignTaskOtherButton !== null) {
		unassignTaskOtherButton.addEventListener('click', unassignOtherTask);
	}
}

function initTinyMCE() {
	tinymce.init({
		selector: '#mytextarea',
		plugins: 'code,codesample,lists,advlist,image,link,paste,textcolor,textpattern,contextmenu,emoticons,pagebreak,table',
		toolbar: 'code, codesample, bullist, numlist, image, link, paste,forecolor backcolor,emoticons, pagebreak, table',
		menubar: false,
		contextmenu: 'link image inserttable | cell row column deletetable',
		image_caption: true,
		image_advtab: true,
		code_dialog_height: 300,
		code_dialog_width: 350,
		advlist_bullet_styles: 'default,circle,disc,square',
		advlist_number_styles: 'lower-alpha,lower-roman,upper-alpha,upper-roman',
		codesample_languages: [
			{ text: 'HTML/XML', value: 'markup' },
			{ text: 'JavaScript', value: 'javascript' },
			{ text: 'CSS', value: 'css' },
			{ text: 'PHP', value: 'php' },
			{ text: 'Ruby', value: 'ruby' },
			{ text: 'Python', value: 'python' },
			{ text: 'Java', value: 'java' },
			{ text: 'C', value: 'c' },
			{ text: 'C#', value: 'csharp' },
			{ text: 'C++', value: 'cpp' }
		]
	});
}

function showEditForm() {

	let taskInfo = document.querySelector("div#task_info div#task_description");
	let taskForm = document.querySelector("div#task_info form");

	if (taskInfo.style.display !== "none") {
		taskInfo.style.display = "none";

		let textarea = document.querySelector("div#task_info form textarea");
		textarea.innerHTML = taskInfo.innerHTML;
		initTinyMCE();
		taskForm.classList.remove('hidden');
	}
	else {
		taskInfo.style.display = "block";
		taskForm.classList.add('hidden');
	}
}

function showAssignUserForm(event) {

	let assignUserForm = document.querySelector("section#task_page form#assign_user_form");

	if (assignUserForm.classList.contains('hidden')) {
		assignUserForm.classList.remove('hidden');
	}
	else {
		assignUserForm.classList.add('hidden');
	}
}


function updateTaskCompletion() {
	let url = this.getAttribute("data-url");

	let state;
	if (this.checked) {
		state = "Completed";
	} else if (!this.checked) {
		state = "Uncompleted";
	}

	sendAjaxRequest('post', url, { state: state }, updateTaskStatePage);
}

function updateTaskStatePage() {
	let data = JSON.parse(this.responseText);

	if (!data.success) {
		let errorMessage = '<div class="col-12 alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>' + data.error + '</strong></div>';

		let referenceNode = document.querySelector("div#task");
		referenceNode.insertAdjacentHTML('afterbegin', errorMessage);
	}
	else {

		if (data.state === "Completed") {

			let assigned_users = document.querySelector("div.assigned_users");
			while (assigned_users.hasChildNodes()) {
				assigned_users.removeChild(assigned_users.lastChild);
			}


			let task_options = document.querySelector("div.task_options");
			while (task_options.hasChildNodes()) {
				task_options.removeChild(task_options.lastChild);
			}

			let divCompleted = '<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>This task is <strong>completed</strong>.</div>';
			let referenceNode = document.querySelector("section#task_page");
			referenceNode.insertAdjacentHTML('afterbegin', divCompleted);

		} else if (data.state === "Uncompleted") {

			let divCompletedMessage = document.querySelector("div.alert.alert-success");
			if (divCompletedMessage !== null) {
				divCompletedMessage.remove();
			}

			if (data.user_username != null) {
				updateDivAssignUsers(data);
			}

			let referenceNode = document.querySelector("div.task_options");

			if (data.coordinator) {

				referenceNode.innerHTML = data.coordinator_options;
				taskPageEventListeners();

			} else {

				let newButton = document.createElement("a");
				newButton.classList.add("btn", "claim");
				newButton.href = data.claim_url;

				if (data.assigned)
					newButton.innerHTML = "Unclaim Task"
				else
					newButton.innerHTML = "Claim Task";

				referenceNode.appendChild(newButton);
			}
		}

	}

}

/**
 * Assign or unassign self to task
 * @param {*} event 
 */
function assignSelfTask(event) {
	event.preventDefault();

	sendAjaxRequest('post', event.target.href, null, updateAssignUsers);
}

/**
 * Updates the assigned Users 
 */

function updateAssignUsers() {
	let data = JSON.parse(this.responseText);

	let buttonClaim = document.querySelector("div.task_options a.claim");
	let assignTaskOtherButton = document.querySelector("div.coordinator_options a.claim_other");

	if (data.unclaim_url != null) {

		// user is assigned
		updateDivAssignUsers(data);

		buttonClaim.innerHTML = "Unclaim task";
		buttonClaim.href = data.unclaim_url;

		assignTaskOtherButton.outerHTML = '<a class="btn btn-primary claim_other" id="assign_user">Assign task to user</a>';
		assignTaskOtherButton = document.querySelector("div.coordinator_options a#assign_user");
		assignTaskOtherButton.addEventListener('click', showAssignUserForm);

	}
	else if (data.claim_url != null) {
		// user is unassigned
		let assigned_users = document.querySelector("div.assigned_users");
		while (assigned_users.hasChildNodes()) {
			assigned_users.removeChild(assigned_users.lastChild);
		}

		buttonClaim.innerHTML = "Claim task";
		buttonClaim.href = data.claim_url;
	}
}

function assignOtherTask(event) {
	event.preventDefault();

	let inputUsername = document.querySelector("div.coordinator_options form#assign_user_form input.user_name");

	sendAjaxRequest('POST', event.target.action, { username: inputUsername.value }, updateOtherUserAssigned);
}

function updateOtherUserAssigned() {
	let data = JSON.parse(this.responseText);
	console.log(data);

	if (!data.success) {
		let errorMessage = '<div class="col-12 alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>' + data.error + '</strong></div>';

		let referenceNode = document.querySelector("div#task");
		referenceNode.insertAdjacentHTML('afterbegin', errorMessage);

	}
	else {

		updateDivAssignUsers(data);

		let assignUserButton = document.querySelector("a#assign_user");
		assignUserButton.innerHTML = "Unassign user from task";
		assignUserButton.id = "unassign_user";
		assignUserButton.href = data.unclaim_url;
		assignUserButton.addEventListener('click', unassignOtherTask);

		let form = document.querySelector("div.coordinator_options form#assign_user_form");
		form.classList.add("hidden");

		let buttonClaim = document.querySelector("div.task_options a.claim");
		buttonClaim.innerHTML = "Claim task";
		buttonClaim.href = data.claim_self_url;
	}

	let inputUsername = document.querySelector("div.coordinator_options form#assign_user_form input.user_name");
	inputUsername.value = "";
}

function unassignOtherTask(event) {
	event.preventDefault();

	let form = document.querySelector("div.coordinator_options form#assign_user_form");
	form.classList.add("hidden");

	sendAjaxRequest('POST', event.target.href, null, removeAssignedUser);
}

function removeAssignedUser() {
	let data = JSON.parse(this.responseText);

	if (!data.success) {
		let errorMessage = '<div class="col-12 alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>' + data.error + '</strong></div>';

		let referenceNode = document.querySelector("div#task");
		referenceNode.insertAdjacentHTML('afterbegin', errorMessage);
	}
	else {

		//remove assigned users
		let divAssignUser = document.querySelector("div.assigned_users.col-2");

		while (divAssignUser.hasChildNodes()) {
			divAssignUser.removeChild(divAssignUser.lastChild);
		}

		//update buttons
		let unassignTaskOtherButton = document.querySelector("div.coordinator_options a#unassign_user");

		let assignTaskOtherButton = '<a class="btn btn-primary claim_other" id="assign_user">Assign task to user</a>';
		unassignTaskOtherButton.outerHTML = assignTaskOtherButton;

		assignTaskOtherButton = document.querySelector("div.coordinator_options a#assign_user");
		assignTaskOtherButton.addEventListener('click', showAssignUserForm);

	}
}

/**
 * 
 * Updates the assign_users Div and buttons
 */
function updateDivAssignUsers(data) {

	let divAssignUser = document.querySelector("div.assigned_users.col-2");

	let imgAssignedUser = document.querySelector("div.assigned_users img");
	if (imgAssignedUser !== null) {
		imgAssignedUser.src = data.image;
		imgAssignedUser.title = data.user_username;
	}
	else {

		let paragraph = document.createElement("p");
		paragraph.innerHTML = "User Assigned:";
		divAssignUser.appendChild(paragraph);

		let assigned_user = document.createElement("img");
		assigned_user.src = data.image;
		assigned_user.title = data.user_username;

		divAssignUser.appendChild(assigned_user);
	}

}


taskPageEventListeners();