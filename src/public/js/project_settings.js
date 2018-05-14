
/*settings*/

let deleteRequestBtn = document.querySelectorAll("section.container-fluid div.content_view div.option_buttons a.request_delete");
let acceptRequestBtn = document.querySelectorAll("section.container-fluid div.content_view div.option_buttons a.request_accept");

let promoteMemberBtn = document.querySelectorAll("section.container-fluid div#members div.member div.buttons a.promote");
let removeMemberBtn = document.querySelectorAll("section.container-fluid div#members div.member div.buttons a.remove");

let settingsRequestsBtn = document.querySelector("li.nav-item a#requests");
let settingsMembersBtn = document.querySelector("li.nav-item a#members");

function addEventListenersSettings() {


    let promoteMemberBtn = document.querySelectorAll("section.container-fluid div#members div.member div.buttons a.promote");
    let removeMemberBtn = document.querySelectorAll("section.container-fluid div#members div.member div.buttons a.remove");

    settingsRequestsBtn.addEventListener('click', switchRequestView);
    settingsMembersBtn.addEventListener('click', switchMembersView);

    for (let i = 0; i < deleteRequestBtn.length; i++) {
        deleteRequestBtn[i].addEventListener('click', deleteRequest);
    }

    for (let i = 0; i < acceptRequestBtn.length; i++) {
        acceptRequestBtn[i].addEventListener('click', acceptRequest);
    }

    for (let i = 0; i < promoteMemberBtn.length; i++) {
        promoteMemberBtn[i].addEventListener('click', promoteMember);
    }

    for (let i = 0; i < removeMemberBtn.length; i++) {
        removeMemberBtn[i].addEventListener('click', removeMember);
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

    addEventListenersSettings();
}

function deleteRequest(event) {
    event.preventDefault();

    let index = event.target.href.indexOf('projects');
    let project_id = event.target.href.substring(index + 9, event.target.href.length);
    index = event.target.href.indexOf('requests');
    let request_id = event.target.href.substring(index + 9, event.target.href.length);

    if (confirm('Are you sure you want to reject this request?\n')) {
        sendAjaxRequest('post', event.target.href, { project_id: project_id[0], request_id: request_id[0] }, updateRequests);
    } else {
        return;
    }
}

function acceptRequest(event) {
    event.preventDefault();

    let index = event.target.href.indexOf('projects');
    let project_id = event.target.href.substring(index + 9, event.target.href.length);
    index = event.target.href.indexOf('requests');
    let request_id = event.target.href.substring(index + 9, event.target.href.length);

    if (confirm('Are you sure you want to accept this request?\n')) {
        sendAjaxRequest('post', event.target.href, { project_id: project_id[0], request_id: request_id[0] }, updateRequests);
    } else {
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

function promoteMember(event) {
    event.preventDefault();

    let index = event.target.href.indexOf('projects');
    let project_id = event.target.href.substring(index + 9, event.target.href.length);
    index = event.target.href.indexOf('members');
    let member_username = event.target.href.substring(index + 8, event.target.href.length - 8);

    if (confirm('Are you sure you want to promote this member to Coordenator?\n')) {
        sendAjaxRequest('post', event.target.href, { project_id: project_id[0], username: member_username }, promotedMemberUpdate);
    } else {
        return;
    }
}

function promotedMemberUpdate() {
    let data = JSON.parse(this.responseText);
    if (data.success) {
        let button = document.querySelector("div.member[data-id='" + data.member_username + "'] div.buttons a");
        button.remove();
    }
}

function removeMember(event) {
    event.preventDefault();

    let index = event.target.href.indexOf('projects');
    let project_id = event.target.href.substring(index + 9, event.target.href.length);
    index = event.target.href.indexOf('members');
    let member_username = event.target.href.substring(index + 8, event.target.href.length - 7);

    if (confirm('Are you sure you want to remove this member from the project?\n')) {
        sendAjaxRequest('post', event.target.href, { project_id: project_id[0], username: member_username }, removeMemberUpdate);
    } else {
        return;
    }

}

function removeMemberUpdate() {
    let data = JSON.parse(this.responseText);
    if (data.success) {
        let div = document.querySelector("div.member[data-id='" + data.member_username + "']");
        div.remove();
    }
}

addEventListenersSettings();