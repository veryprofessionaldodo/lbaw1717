let sprintButton = document.querySelector("li.nav-item a#sprint_btn");
let taskButton = document.querySelector("li.nav-item a#task_btn");
let memberButton = document.querySelector("li.nav-item a#member_btn");

let settingsRequestsBtn = document.querySelector("li.nav-item a#requests");
let settingsMembersBtn = document.querySelector("li.nav-item a#members");

let newSprintButton = document.querySelector("section.container-fluid div.col-12.new_sprint a");

function addEventListeners() {

	settingsRequestsBtn.addEventListener('click',switchRequestView);
	settingsMembersBtn.addEventListener('click',switchMembersView);

	sprintButton.addEventListener('click', switchSprintsView);
	taskButton.addEventListener('click', switchTasksView);
	memberButton.addEventListener('click', switchMembersView);

	if(newSprintButton !== null){
		newSprintButton.addEventListener('click', getSprintForm);
	}

	submitComment = document.querySelector("div.comment div.form_comment form");
	submitComment.addEventListener('submit', addComment);
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

/*settings*/

function switchRequestView(event){
	event.preventDefault();

	settingsRequestsBtn.classList.add('active');
	settingsMembersBtn.classList.remove('active');

	sendAjaxRequest('get', event.target.href, null, showSettingsView);
}

function switchMembersView(event){
	event.preventDefault();

	settingsMembersBtn.classList.add('active');
	settingsRequestsBtn.classList.remove('active');

	sendAjaxRequest('get', event.target.href, null, showSettingsView);
}

function showSettingsView() {

	let data = JSON.parse(this.responseText);

	let content = document.querySelector("section.container-fluid div.content_view");
	content.innerHTML = data.html;
}

addEventListeners();