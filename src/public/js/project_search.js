
function addEventListenersProjectSearch() {

    let joinProjectButtons = document.querySelectorAll("div#all_projects div.row div.project div.row a.join");
    console.log(joinProjectButtons);
    for (let i = 0; i < joinProjectButtons.length; i++) {
        joinProjectButtons[i].addEventListener('click', createRequest);
    }
}

function createRequest(event) {
    event.preventDefault();

    alert("yolo");

    return;

    let index = event.target.href.indexOf('projects');
    let project_id = event.target.href.substring(index + 9, event.target.href.length - 8);

    sendAjaxRequest('post', event.target.href, { project_id: project_id }, responseCreateRequest);
}

function responseCreateRequest() {
    let data = JSON.parse(this.responseText);
    let message;

    if (data.success) {
        message = 'Request send to join project ' + data.project_name;
    } else if (data.reason == "request") {
        message = 'You have already rquested to join this project (' + data.project_name + ')';
    } else {
        message = 'You have already received an invite to join this project (' + data.project_name + ')';
    }

    alert(message);
}

addEventListenersProjectSearch();