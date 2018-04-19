
function addEventListeners() {

    let newThreadButton = document.querySelector("div.container div.overlay a#newThread-btn");

    newThreadButton.addEventListener('click', addNewThread);
}
/*
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
}*/

function addNewThread(request){
    event.preventDefault();

    let title;
    let description;
    let date;
    let project_id;
    let user_id;


    //sendAjaxRequest('post',event.taget.href,,);
}

addEventListeners();