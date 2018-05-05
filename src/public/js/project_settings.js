
/*settings*/

let deleteRequestBtn = document.querySelectorAll("section.container-fluid div.content_view div.option_buttons a.request_delete");

let settingsRequestsBtn = document.querySelector("li.nav-item a#requests");
let settingsMembersBtn = document.querySelector("li.nav-item a#members");

function addEventListeners() {

    settingsRequestsBtn.addEventListener('click', switchRequestView);
    settingsMembersBtn.addEventListener('click', switchMembersView);

    for(let i =0; i < deleteRequestBtn.length;i++){
        deleteRequestBtn[i].addEventListener('click', deleteRequest);
    }
    
}

function switchRequestView(event) {
    event.preventDefault();

    settingsRequestsBtn.classList.add('active');
    settingsMembersBtn.classList.remove('active');

    sendAjaxRequest('get', event.target.href, null, showSettingsView);
}

function switchMembersView(event) {
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

function deleteRequest(event) {
    event.preventDefault();

    console.log(event.target.href.length);

    let index = event.target.href.indexOf('projects');
    let project_id = event.target.href.substring(index + 9, event.target.href.length);
    index = event.target.href.indexOf('requests');
    let request_id = event.target.href.substring(index + 9, event.target.href.length);

    if(confirm('Are you sure you want to reject this request?\n')){
        sendAjaxRequest('post', event.target.href, { project_id: project_id[0], request_id: request_id[0] }, updateRequests);
    }else{
        return;
    }
}

function updateRequests() {
    let data = JSON.parse(this.responseText);
    if (data.success) {
        let request = document.querySelector("div.request[data-id='" + data.request_id + "']");
        request.remove();
    }
}


addEventListeners();