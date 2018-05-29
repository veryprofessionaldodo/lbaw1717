let sprintButton = document.querySelector("li.nav-item a#sprint_btn");
let taskButton = document.querySelector("li.nav-item a#task_btn");
let memberButton = document.querySelector("li.nav-item a#member_btn");

let newSprintButton = document.querySelector("section.container-fluid div.col-12.new_sprint a");

function addEventListenersProject() {
	if (sprintButton !== null)
		sprintButton.addEventListener('click', switchSprintsView);
	if (taskButton !== null)
		taskButton.addEventListener('click', switchTasksView);
	if (memberButton !== null)
		memberButton.addEventListener('click', switchMembersView);

	if (newSprintButton !== null) {
		newSprintButton.addEventListener('click', getSprintForm);
	}

	let submitComment = document.querySelectorAll("div.comment div.form_comment form#submit");
	for(let i = 0; i < submitComment.length; i++){
		submitComment[i].addEventListener('submit', addComment);
	}

	let editComment = document.querySelectorAll("div.comment div.form_comment.row form#edit");
	for(let i = 0; i < editComment.length; i++){
		editComment[i].addEventListener('submit', editTaskComment);
	}

	// tasks completion
	let tasksCheckboxes = document.querySelectorAll("div.sprint-task input[type='checkbox']");
	for (let i = 0; i < tasksCheckboxes.length; i++) {
		tasksCheckboxes[i].addEventListener('click', updateTaskCompletion);
	}

	// assign to task
	let assignSelfTaskButton = document.querySelectorAll("div.sprint-task a.claim");
	for (let i = 0; i < assignSelfTaskButton.length; i++) {
		assignSelfTaskButton[i].addEventListener('click', assignSelfTask);
	}

	let addTaskButtons = document.querySelectorAll("form.sprint-task.create_task a");
	for (let i = 0; i < addTaskButtons.length; i++) {
		addTaskButtons[i].addEventListener('click', createTask);
	}

	let addTaskProjectButton = document.querySelector("form.create_task_project a");
	if(addTaskProjectButton !== null){
		addTaskProjectButton.addEventListener('click', createTaskProjectButton);
	}

	let deleteSprintButtons = document.querySelectorAll('div.list-group-item a.btn.delete_sprint');
	for (let i = 0; i < deleteSprintButtons.length; i++) {
		deleteSprintButtons[i].addEventListener('click', deleteSprint);
	}

	let deleteProjectButton = document.querySelector("div#project_structure div.row a#delete_project");

	if (deleteProjectButton !== null)
		deleteProjectButton.addEventListener('click', deleteProject);

	let editSprintButtons = document.querySelectorAll("a.edit_sprint");
	for(let i = 0; i < editSprintButtons.length; i++){
		editSprintButtons[i].addEventListener('click', getEditSprintForm);
	}

	let joinProjectBtn = document.querySelector("section.container-fluid div.col-12.edit_project a");
	if(joinProjectBtn !== null)
		joinProjectBtn.addEventListener('click',joinProject);

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
	request.addEventListener('load', handler);
	if (data != null)
		request.send(encodeForAjax(data));
	else
		request.send();
}

function switchSprintsView(event) {
	event.preventDefault();

	sprintButton.classList.add('active');
	taskButton.classList.remove('active');
	memberButton.classList.remove('active');

	sendAjaxRequest('get', event.target.href, null, showSprintsView);
}

function switchTasksView(event) {
	event.preventDefault();

	sprintButton.classList.remove('active');
	taskButton.classList.add('active');
	memberButton.classList.remove('active');

	sendAjaxRequest('get', event.target.href, null, showTasksView);
}

function switchMembersView(event) {
	event.preventDefault();

	sprintButton.classList.remove('active');
	taskButton.classList.remove('active');
	memberButton.classList.add('active');

	sendAjaxRequest('get', event.target.href, null, showMembersView);
}

function showSprintsView() {
	let data = JSON.parse(this.responseText);

	if(data.success){
		let content = document.querySelector("section.container-fluid div.row.content_view");
		content.innerHTML = data.html;
		addEventListenersProject();
	}
}

function getSprintForm(event) {
	event.preventDefault();

	sendAjaxRequest('get', event.target.href, null, showSprintForm);
}

function showSprintForm() {
	let data = JSON.parse(this.responseText);

	if(data.success){
		let content = document.querySelector("section.container-fluid div#project_structure");
		content.innerHTML = data.html;
	}
}

function showTasksView() {
	let data = JSON.parse(this.responseText);

	if(data.success){
		let content = document.querySelector("section.container-fluid div.row.content_view");
		content.innerHTML = data.html;
		addEventListenersProject();
	}
}

function showMembersView() {
	let data = JSON.parse(this.responseText);

	if(data.success){
		let content = document.querySelector("section.container-fluid div.row.content_view");
		content.innerHTML = data.html;
	
		let searchProjectMember = document.querySelector("form#user_search");
		if(searchProjectMember !== null){
			searchProjectMember.addEventListener('submit', submitMemberSearch);
		}
	
		addEventListenersProject();
	}
}

function addComment(event) {
	event.preventDefault();

	let content = event.target.childNodes[3].value;
	sendAjaxRequest('post', event.target.action, { content: content }, updateComments);
}

function updateComments() {
	let data = JSON.parse(this.responseText);

	if(data.success){
		let comments = document.querySelector("div#task-" + data.task_id);
	
		let form = document.querySelector("div#task-" + data.task_id + " div.comment:last-of-type");
	
		form.insertAdjacentHTML('beforebegin', data.comment);

		addEventListenersProject();
	}

	let input = document.querySelector("div#task-" + data.task_id + " div.comment div.form_comment input[name='content']");
	input.value = "";

}

function deleteCommentTask(button) {

	let href = button.getAttribute('href');

	swal({
		title: "Are you sure you want to delete this comment?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				let comment_id = button.id;
				sendAjaxRequest('post', href, { comment_id: comment_id }, updateCommentDeletion);
			}
		});

}

function updateCommentDeletion() {
	let data = JSON.parse(this.responseText);
	if (data.success) {
		let comment = document.querySelector("div.comment[data-id='" + data.comment_id + "']");
		comment.remove();
		swal("The comment has been deleted !", {
			icon: "success",
		});
	}
}

function prepareForEdition(button) {
	
	let commentInfo = document.querySelector("div.comment[data-id='" + button.id + "'] p.content");
	let commentDiv = document.querySelector("div.comment[data-id='" + button.id + "'] div.form_comment.row");
	let commentForm = document.querySelector("div.comment[data-id='" + button.id + "'] div.form_comment.row form#edit");	
	
	let href = commentForm.getAttribute('href');

    if(commentInfo.style.display !== "none"){
		let input = commentForm.querySelector("input[name='content']");
		let content = commentInfo.innerHTML;
        input.value = content;
        commentInfo.style.display = "none"
       	commentDiv.style.display = "block";
    }
    else {
        commentInfo.style.display = "block";
        commentDiv.style.display = "none";

	}
}

function editTaskComment(event){
	event.preventDefault();

	let content = event.target.childNodes[3].value;

	sendAjaxRequest('post', event.target.action, { content: content }, editUpdate);
}

function editUpdate(){
	let data = JSON.parse(this.responseText);

	if(data.success){
		let comment = document.querySelector("div.comment[data-id='"+ data.comment_id + "']");

		comment.outerHTML = data.comment;
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

	sendAjaxRequest('post', url, { state: state }, updateTaskState);
}

function updateTaskState() {
	let data = JSON.parse(this.responseText);
	
	if(data.success){
		let task = document.querySelector("div[data-id='" + data.task_id + "'].sprint-task");
	
		if (data.state === "Completed") {
			task.classList.add("task_completed");
	
			let assigned_users = document.querySelector("div[data-id='" + data.task_id + "'].sprint-task div.assigned_users");
			if (assigned_users !== null)
				assigned_users.remove();
	
		} else if (data.state === "Uncompleted") {
	
			task.classList.remove("task_completed");
	
			if (data.user_username != null) {
				createAssignUserDiv(data);
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
 * Updates the assign_users Div and buttons
 */
function updateAssignUsers() {
	let data = JSON.parse(this.responseText);

	if(data.success){
		let assigned_user = document.querySelector("div[data-id='" + data.task_id + "'].sprint-task div.assigned_users");
	
		// the request was to unassign user of the task
		if (data.claim_url != null) {
	
			assigned_user.remove();
	
		}
		else {
			// if the request was to assign user to task
			if (assigned_user !== null) {
	
				//update assigned_user
				assigned_user.firstChild.src = data.image;
				assigned_user.firstChild.title = data.username;
			}
			else {
				createAssignUserDiv(data);
			}
		}
	}
}

/**
 * Creates a assign_users DIV with the current assigned user to the task
 * @param {*} data 
 */
function createAssignUserDiv(data) {
	let referenceNode = document.querySelector("div[data-id='" + data.task_id + "'].sprint-task a.task_name");

	let divUsers = document.createElement("div");
	divUsers.classList.add("assigned_users");

	let assigned_user = document.createElement("img");
	assigned_user.src = data.image;
	assigned_user.title = data.user_username;

	divUsers.appendChild(assigned_user);

	referenceNode.parentNode.insertBefore(divUsers, referenceNode.nextSibling);
}

function createTask(event) {
	event.preventDefault();

	let sprint_project_id = event.target.getAttribute('data-id').split('-');


	let inputTaskName = document.querySelector("div#sprint-" + sprint_project_id[0] + " form.sprint-task.create_task input[type='text']");
	let inputEffort = document.querySelector("div#sprint-" + sprint_project_id[0] + " form.sprint-task.create_task input[type='number']");
	let formHref = document.querySelector("div#sprint-" + sprint_project_id[0] + " form.sprint-task.create_task").getAttribute("action");

	sendAjaxRequest("POST", formHref, {
		sprint_id: sprint_project_id[0], project_id: sprint_project_id[1],
		name: inputTaskName.value, effort: inputEffort.value
	}, addTaskInfo);
}

function addTaskInfo() {
	let data = JSON.parse(this.responseText);
	
	let div = document.querySelector("div#sprint-" + data.sprint_id + " form.sprint-task.create_task");
	
	if (data.success) {
		div.insertAdjacentHTML('beforebegin', data.html);
	}
	else {
		let element = '<div class="alert alert-dismissible alert-danger"> <button type="button" class="close" data-dismiss="alert">&times;</button><strong>' + data.error + '</strong></div>';
		div.insertAdjacentHTML('beforebegin', element);
	}

	let inputTaskName = document.querySelector("div#sprint-" + data.sprint_id + " form.sprint-task.create_task input[type='text']");
	let inputEffort = document.querySelector("div#sprint-" + data.sprint_id + " form.sprint-task.create_task input[type='number']");

	inputEffort.value = "";
	inputTaskName.value = "";

	addEventListenersProject();
}

function createTaskProjectButton(event){
	event.preventDefault();

	let project_id = event.target.getAttribute("data-id");

	let inputTaskName = document.querySelector("form.create_task_project input[type='text']");
	let inputEffort = document.querySelector("form.create_task_project input[type='number']");
	let formHref = document.querySelector("form.create_task_project").getAttribute("action");


	sendAjaxRequest("POST", formHref, { project_id: project_id,
		name: inputTaskName.value, effort: inputEffort.value
	}, addTaskProjectInfo);
}

function addTaskProjectInfo() {
	let data = JSON.parse(this.responseText);

	let div = document.querySelector("form.create_task_project");

	if (data.success) {
		div.insertAdjacentHTML('beforebegin', data.html);
	}
	else {
		let element = '<div class="alert alert-dismissible alert-danger"> <button type="button" class="close" data-dismiss="alert">&times;</button><strong>Max effort exceeded!</strong></div>';
		div.insertAdjacentHTML('beforebegin', element);
	}

	let inputTaskName = document.querySelector("form.create_task_project input[type='text']");
	let inputEffort = document.querySelector("form.create_task_project input[type='number']");

	inputTaskName.value = "";
	inputEffort.value = "";

	addEventListenersProject();
}

function deleteSprint(event) {
	event.preventDefault();

	let href = event.currentTarget.href;
	let index = href.indexOf('projects');
	let index2 = href.indexOf('sprints');
	let project_id = href.substring(index + 9, index2 - 1);
	let sprint_id = href.substring(index2 + 8, href.length);

	swal("Delete Sprint", {
		icon: "warning",
		buttons: {
			move: {
				text: "Delete Sprint and move tasks to the project!",
				value: "move",
			},
			cancel: "Cancel!",
			all: {
				text: "Delete sprint and tasks inside!",
				value: "all",
			}
		},
	})
		.then((value) => {

			switch (value) {

				case "all":
					sendAjaxRequest('post', href, { project_id: project_id, sprint_id: sprint_id, value: value }, deleteSprintHandler);
					break;

				case "move":
					sendAjaxRequest('post',href, { project_id: project_id, sprint_id: sprint_id, value: value }, deleteSprintHandler);
					break;

				case "change":
					break;

				default:
					swal("Operation Canceled!", {
						dangerMode: true,
						icon: "error",
					});
			}
		});


}

function deleteSprintHandler() {

	let data = JSON.parse(this.responseText);
	if (data.success) {

		let sprint = document.querySelector("section.container-fluid div#project_structure div#sprints div.list-group-item[data-id='" + data.sprint_id + "']");
		sprint.remove();

		switch (data.value) {
			case "all":
				swal("Successfully deleted this sprint and the tasks inside !", {
					icon: "success",
				});
				break;
			case "move":
				swal("Successfully deleted this sprint and moved the tasks to the project !", {
					icon: "success",
				});
				break;
			case "change":
				break;
		}
	}
}

function deleteProject(event) {
	event.preventDefault();


	swal({
		title: "Are you sure you want to delete this project?",
		text: "Once deleted, you will not be able to recover this project!",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				let index = event.target.href.indexOf('projects');
				let project_id = event.target.href.substring(index + 9, event.target.length);
				sendAjaxRequest('post', event.target.href, { project_id: project_id }, deleteProjectUpdate);
			} else {
				swal("This project is safe !");
			}
		});
}

function deleteProjectUpdate() {

	let data = JSON.parse(this.responseText);

	if (data.success) {
		window.location.href = data.url;
		swal("Successfully deleted this project !", {
			icon: "success",
		}).then((willDelete) => {
			if (willDelete)
				window.location.href = data.url;
		});

	}
}

function getEditSprintForm(event){
	event.preventDefault();
	if(event.target.tagName === "A"){
		sendAjaxRequest('GET', event.target.href, null, showEditSprintForm);
	}
	else if(event.target.tagName === "svg"){
		sendAjaxRequest('GET', event.target.parentNode.href, null, showEditSprintForm);
	}
	else {
		sendAjaxRequest('GET', event.target.parentNode.parentNode.href, null, showEditSprintForm);
	}
}

function showEditSprintForm() {
	let data = JSON.parse(this.responseText);

	if(data.success){
		let div = document.querySelector("div#project_structure");
		div.innerHTML = data.html;
	}
}

function submitMemberSearch(event){
	event.preventDefault();

	let inputVal = document.querySelector("form#user_search input").value;

	sendAjaxRequest("POST", event.target.action, {search: inputVal}, showTeamMembersSearch);
}

function showTeamMembersSearch(){
	let data = JSON.parse(this.responseText);

	if(data.success){
		let div = document.querySelector("div#show_members");
	
		div.innerHTML = data.html;
	}
}


function joinProject(event) {
    event.preventDefault();

    let index = event.target.href.indexOf('projects');
    let project_id = event.target.href.substring(index + 9, event.target.href.length - 8);

    sendAjaxRequest('post', event.target.href, { project_id: project_id }, responseCreateRequest);
}

function responseCreateRequest() {
    let data = JSON.parse(this.responseText);

    if (data.success) {
        swal('Request send to join project ' + data.project_name, {
            icon: "success",
        });
    } else if (data.reason == "request") {
        swal('You have already rquested to join the project (' + data.project_name + ')', {
            icon: "warning",
        });
    } else {
        swal('You have already received an invite to join this project (' + data.project_name + ')', {
            icon: "warning",
        });
    }
}

addEventListenersProject();