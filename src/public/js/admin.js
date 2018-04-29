function addEventListeners() {
	let userRepBtn = document.querySelector("aside#navbar div a:first-of-type");
	let commentRepBtn = document.querySelector("aside#navbar div a:last-of-type");

	userRepBtn.addEventListener('click', showUserReports);
	commentRepBtn.addEventListener('click', showCommentReports);
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
}

function dismissReport(button){
	let href = button.getAttribute('href');
	let report_id = button.id;

	sendAjaxRequest('post', href, {report_id: report_id},refreshPage);
}

addEventListeners();