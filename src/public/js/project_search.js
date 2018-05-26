
function addEventListenersProjectSearch() {

    let joinProjectButtons = document.querySelectorAll("div#all_projects div.project a.join");
    for (let i = 0; i < joinProjectButtons.length; i++) {
        joinProjectButtons[i].addEventListener('click', createRequest);
    }
}

function createRequest(event) {
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

addEventListenersProjectSearch();