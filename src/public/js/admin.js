function addEventListeners() {
	let userRepBtn = document.querySelector("aside#navbar div a:first-of-type");
	let commentRepBtn = document.querySelector("aside#navbar div a:last-of-type");

	let pagination = document.querySelectorAll("div#reports div#pagination_section ul.pagination li a");

	console.log(pagination);

	userRepBtn.addEventListener('click', showUserReports);
	commentRepBtn.addEventListener('click', showCommentReports);

	for(let i = 0; i < pagination.length;i++){
		pagination[i].addEventListener('click',getPageReport);
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

function showUserReports(event){
	event.preventDefault();

	sendAjaxRequest('get', event.target.href, null, viewReports);
}

function showCommentReports(event){
	event.preventDefault();

	sendAjaxRequest('get', event.target.href, null, viewReports);
}

function viewReports() {
	let data = JSON.parse(this.responseText);

	let content = document.querySelector("section div#reports");
	content.innerHTML = data.html;

	addEventListeners();
}

function dismissReport(button){
	let href = button.getAttribute('href');
	let report_id = button.id;

	sendAjaxRequest('post', href, {report_id: report_id},refreshPage);
}

function disableUser(button){
	let href = button.getAttribute('href');

	let r = confirm("Are you sure you want to disable this user?\n");

    if (r == true) {
        let report_id = button.id;

		sendAjaxRequest('post', href, {report_id: report_id},refreshPage);
    } else {
        return;
    }
}

function deleteCommentReport(button){
	let href = button.getAttribute('href');

	let r = confirm("Are you sure you want to delete this comment?\n");

    if (r == true) {
        let report_id = button.id;

		sendAjaxRequest('post', href, {report_id: report_id},refreshPage);
    } else {
        return;
    }
}

function getPageReport(event){
	event.preventDefault();

	sendAjaxRequest('get', event.target.href, null, viewReports);
}

addEventListeners();