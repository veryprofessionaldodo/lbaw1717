sendAjaxRequest('get', "/",null, loadimage);

swal("You have been banned from this Website by the administrator !", {
    icon: "error",
});


function loadimage(){

	document.open();
	document.write(this.responseText);
	document.close();

    swal("You have been banned from this Website by the administrator !", {
        icon: "error",
    });
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