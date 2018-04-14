function addEventListeners() {
	let sprintButton = document.querySelector("li.nav-item a#sprint_btn");
	let taskButton = document.querySelector("li.nav-item a#task_btn");
	let memberButton = document.querySelector("li.nav-item a#member_btn");

	sprintButton.addEventListener('click', switchSprintsView);
	taskButton.addEventListener('click', switchTasksView);
	memberButton.addEventListener('click', switchMembersView);
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
	sendAjaxRequest('get', event.target.href, null, showSprintsView);
}

function switchTasksView(event) {
	event.preventDefault();
	sendAjaxRequest('get', event.target.href, null, showTasksView);
}

function switchMembersView(event) {
	event.preventDefault();
	sendAjaxRequest('get', event.target.href, null, showmembersView);
}

function showSprintsView() {
	$data = JSON.parse(this.reponseText);

	let content = document.querySelector("div.row.content_view");
	content.innerHTML = $data.html;
}

function showTasksView() {
	$data = JSON.parse(this.reponseText);
	
	let content = document.querySelector("div.row.content_view");
	content.innerHTML = $data.html;
}

function showMembersView() {
	$data = JSON.parse(this.reponseText);
	console.log(window.innerHTML);
	let content = document.querySelector("div.row.content_view");
	content.innerHTML = $data.html;
}

addEventListeners();