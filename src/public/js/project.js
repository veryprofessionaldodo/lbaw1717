let sprintButton = document.querySelector("li.nav-item a#sprint_btn");
let taskButton = document.querySelector("li.nav-item a#task_btn");
let memberButton = document.querySelector("li.nav-item a#member_btn");

let newSprintButton = document.querySelector("section.container-fluid div.col-12.new_sprint a");

function addEventListeners() {

	sprintButton.addEventListener('click', switchSprintsView);
	taskButton.addEventListener('click', switchTasksView);
	memberButton.addEventListener('click', switchMembersView);

	if(newSprintButton !== null){
		newSprintButton.addEventListener('click', getSprintForm);
	}

	submitComment = document.querySelector("div.comment div.form_comment form");
	submitComment.addEventListener('submit', addComment);

	// tasks completion
	let tasksCheckboxes = document.querySelectorAll("div.sprint-task input[type='checkbox']");
	for(let i = 0; i < tasksCheckboxes.length; i++){
		tasksCheckboxes[i].addEventListener('click', updateTaskCompletion);
	}

	// assign to task
	let assignSelfTaskButton = document.querySelectorAll("div.sprint-task a.claim");
	for(let i = 0; i < assignSelfTaskButton.length; i++) {
		assignSelfTaskButton[i].addEventListener('click', assignSelfTask);
	}
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

	let content = document.querySelector("section.container-fluid div.row.content_view");
	content.innerHTML = data.html;
}

function getSprintForm(event) {
	event.preventDefault();

	sendAjaxRequest('get', event.target.href, null, showSprintForm);
}

function showSprintForm() {
	let data = JSON.parse(this.responseText);

	let content = document.querySelector("section.container-fluid div#project_structure");
	content.innerHTML = data.html;
}

function showTasksView() {
	let data = JSON.parse(this.responseText);
	
	let content = document.querySelector("section.container-fluid div.row.content_view");
	content.innerHTML = data.html;
}

function showMembersView() {
	let data = JSON.parse(this.responseText);

	let content = document.querySelector("section.container-fluid div.row.content_view");
	content.innerHTML = data.html;
}

function addComment(event){
	event.preventDefault();
	console.log(event.target.action);

	let content = document.querySelector("div.comment div.form_comment input[name='content']").value;

	sendAjaxRequest('post', event.target.action, {content: content} , updateComments);
}

function updateComments() {
	let data = JSON.parse(this.responseText);

	let comments = document.querySelector("div#task-" + data.task_id);
	
	let form = document.querySelector("div#task-"+ data.task_id + " div.comment:last-of-type");

	form.insertAdjacentHTML('beforebegin', data.comment);

	let input = document.querySelector("div.comment div.form_comment input[name='content']");
	input.value = "";
	
}

function deleteCommentTask(button){
	
	let href = button.getAttribute('href');
	
	let r = confirm("Are you sure you want to delete this comment?\n");
	
	if (r == true) {
		let comment_id = button.id; 
		
		sendAjaxRequest('post', href, {comment_id: comment_id}, updateCommentDeletion);
	} else {
		return;
	}
}

function updateCommentDeletion(){
	let data = JSON.parse(this.responseText);
	if(data.success){
		let comment = document.querySelector("div.comment[data-id='" + data.comment.id + "']");
		comment.remove();
	}
}

function updateTaskCompletion() {
	let url = this.getAttribute("data-url");
	console.log(url);
	
	let state;
	if(this.checked){
		state = "Completed";
	} else if(!this.checked){
		console.log('Ah');
		state = "Uncompleted";
	}
	console.log(url);
	sendAjaxRequest('post', url, {state: state}, updateTaskState);
}

function updateTaskState(){
	let data = JSON.parse(this.responseText);
	
	let task = document.querySelector("div[data-id='" + data.task_id + "']");
	console.log(task);

	if(data.state === "Completed"){
		task.classList.add("task_completed");

		if(data.coordinator){
			let coordinator_options = document.querySelector("div[data-id='" + data.task_id + "'] div.coordinator_options");
			coordinator_options.remove();
		}
		else {
			let button = document.querySelector("div[data-id='" + data.task_id + "'] a.claim");
			button.remove();
		}

		let assigned_users = document.querySelector("div[data-id='" + data.task_id + "'] div.assigned_users");
		if(assigned_users !== null)
			assigned_users.remove();


	} else if(data.state === "Uncompleted") {

		task.classList.remove("task_completed");

		if(data.coordinator){

			let referenceNode = document.querySelector("div[data-id='" + data.task_id + "'] a.task_name");
	
			let newDiv = document.createElement("div");
			newDiv.classList.add("coordinator_options");

			let button1 = document.createElement("button");
			button1.innerHTML = "<i class='fas fa-pencil-alt'></i>";
			//ADD URL
			button1.classList.add("btn", "edit_task");
			newDiv.appendChild(button1);

			referenceNode.parentNode.insertBefore(newDiv, referenceNode.nextSibling);

		} else {

			if(data.user_username != null){
				createAssignUserDiv(data);
			}
			
			let newButton = document.createElement("a");
			newButton.classList.add("btn", "claim");
			newButton.href = data.claim_url;

			if(data.assigned)
				newButton.innerHTML = "Unclaim Task"
			else
				newButton.innerHTML = "Claim Task";
			task.appendChild(newButton);
		} 
	}
}

/**
 * Assign or unassign self to task
 * @param {*} event 
 */
function assignSelfTask(event){
	event.preventDefault();

	sendAjaxRequest('post', event.target.href, null, updateAssignUsers);
}

/**
 * Updates the assign_users Div and buttons
 */
function updateAssignUsers(){
	let data = JSON.parse(this.responseText);

	let assigned_user = document.querySelector("div[data-id='" + data.task_id + "'] div.assigned_users");
	let claimButton = document.querySelector("div[data-id='" + data.task_id + "'] a.claim");

	// the request was to unassign user of the task
	if(data.claim_url != null){

		assigned_user.remove();
		claimButton.href = data.claim_url;
		claimButton.innerHTML = "Claim Task";

	}
	else {
		// if the request was to assign user to task
		if(assigned_user !== null){
			
			//update assigned_user
			assigned_user.firstChild.src = data.image;
			assigned_user.firstChild.title = data.username;
		}
		else {
			createAssignUserDiv(data);
		}
			
		claimButton.innerHTML = "Unclaim Task";
		claimButton.href = data.unclaim_url;
	}
}

/**
 * Creates a assign_users DIV with the current assigned user to the task
 * @param {*} data 
 */
function createAssignUserDiv(data) {
	let referenceNode = document.querySelector("div[data-id='" + data.task_id + "'] a.task_name");

	let divUsers = document.createElement("div");
	divUsers.classList.add("assigned_users");

	let assigned_user = document.createElement("img");
	assigned_user.src = data.image;
	assigned_user.title = data.user_username;

	divUsers.appendChild(assigned_user);

	referenceNode.parentNode.insertBefore(divUsers, referenceNode.nextSibling);
}


addEventListeners();