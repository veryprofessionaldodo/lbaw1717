
/*settings*/

let deleteRequestBtn = document.querySelectorAll("section.container-fluid div.content_view div.option_buttons a.request_delete");
let acceptRequestBtn = document.querySelectorAll("section.container-fluid div.content_view div.option_buttons a.request_accept");

let promoteMemberBtn = document.querySelectorAll("section.container-fluid div#members div.member div.buttons a.promote");
let removeMemberBtn = document.querySelectorAll("section.container-fluid div#members div.member div.buttons a.remove");

let settingsRequestsBtn = document.querySelector("li.nav-item a#requests");
let settingsMembersBtn = document.querySelector("li.nav-item a#members");

function addEventListenersSettings() {


    let promoteMemberBtn = document.querySelectorAll("section.container-fluid div#members div.member-row div.buttons a.promote");
    let removeMemberBtn = document.querySelectorAll("section.container-fluid div#members div.member-row div.buttons a.remove");

    if (settingsRequestsBtn !== null)
        settingsRequestsBtn.addEventListener('click', switchRequestView);
    if (settingsMembersBtn !== null)
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

    let editProjectButton = document.querySelector(".container-fluid div.row div.edit_project a");
    if (editProjectButton !== null)
        editProjectButton.addEventListener('click', editProjectForm);

    let searchTeamMember = document.querySelector("div#members form.user_search_settings");
    if (searchTeamMember !== null) {
        searchTeamMember.addEventListener('submit', submitSearchTeamMember);
    }

    let inviteNewMemberButton = document.querySelector("div#members div.user_search_settings a.new_invite");
    if (inviteNewMemberButton !== null)
        inviteNewMemberButton.addEventListener('click', inviteNewMember);

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

    if(data.success){
        let content = document.querySelector("section.container-fluid div.content_view");
        content.innerHTML = data.html;
    
        addEventListenersSettings();
    }

}

function deleteRequest(event) {
    event.preventDefault();

    let href = event.currentTarget.href;

    let index = href.indexOf('projects');
    let index2 = href.indexOf('settings');
    let project_id = href.substring(index + 9, index2 - 1);
    index = href.indexOf('requests');
    let request_id = href.substring(index + 9, href.length - 7);

    swal({
        title: "Are you sure you want to reject this request?",
        text: "Once deleted, you will not be able to recover this request!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                sendAjaxRequest('post', href, { project_id: project_id, request_id: request_id }, updateRequests);
                swal("Poof! Your request has been deleted!", {
                    icon: "success",
                });
            } else {
                swal("Your request is safe!");
            }
        });
}

function acceptRequest(event) {
    event.preventDefault();

    let href = event.currentTarget.href;

    let index = href.indexOf('projects');
    let index2 = href.indexOf('settings');
    let project_id = href.substring(index + 9, index2 - 1);
    index = href.indexOf('requests');
    let request_id = href.substring(index + 9, href.length - 7);

    swal({
        title: "Are you sure you want to accept this request?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                sendAjaxRequest('post', href, { project_id: project_id, request_id: request_id }, updateRequests);
                swal("Your request has been accepted !", {
                    icon: "success",
                });
            } else {
                swal("Your request is safe!");
            }
        });
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

    let href = event.currentTarget.href;

    let index = href.indexOf('projects');
    let index2 = href.indexOf('settings');
    let project_id = href.substring(index + 9, index2 - 1);
    index = href.indexOf('members');
    let member_username = href.substring(index + 8, href.length - 8);

    swal({
        title: "Are you sure you want to promote this member to Coordenator?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                sendAjaxRequest('post', href, { project_id: project_id, username: member_username }, promotedMemberUpdate);
            }
        });
}

function promotedMemberUpdate() {
    let data = JSON.parse(this.responseText);
    if (data.success) {
        let button = document.querySelector("div.member[data-id='" + data.member_username + "'] div.buttons a");
        button.remove();
        swal("The Team Member has been promoted to Coordinator!", {
            icon: "success",
        });
    }
}

function removeMember(event) {
    event.preventDefault();

    let href = event.currentTarget.href;

    let index = href.indexOf('projects');
    let index2 = href.indexOf('settings');
    let project_id = href.substring(index + 9, index2 - 1);
    index = href.indexOf('members');
    let member_username = href.substring(index + 8, href.length - 7);

    swal({
        title: "Are you sure you want to remove this member from the project?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                sendAjaxRequest('post', href, { project_id: project_id, username: member_username }, removeMemberUpdate);
            }
        });
}

function removeMemberUpdate() {
    let data = JSON.parse(this.responseText);
    if (data.success) {
        let div = document.querySelector("div.member-row[data-id='" + data.member_username + "']");
        div.remove();
        swal("The user has been removed from the project!", {
            icon: "success",
        });
    }
}

function editProjectForm(event) {
    event.preventDefault();
    sendAjaxRequest('get', event.target.href, null, showEditProjectForm);
}

function showEditProjectForm() {
    let data = JSON.parse(this.responseText);

    if(data.success){
        document.body.innerHTML = data.html;
        let submitProject = document.querySelector("div#container div#overlay div.jumbotron form p.lead button#editProject-btn");
        submitProject.addEventListener('click', editProjectAction);
    }
}

function editProjectAction(event) {
    event.preventDefault();
    let project_name = document.querySelector("input[name='name']").value;
    let project_description = document.querySelector("textarea[name='description']").value;
    let project_public = document.querySelector("input#public").value;

    let select = document.querySelector("select");
    let categories = getSelectValues(select);


    let href = event.currentTarget.parentNode.parentNode.action;

    let index = href.indexOf('projects');
    let index2 = href.indexOf('members');
    let project_id = href.substring(index + 9, index2 - 1);

    sendAjaxRequest('post', href,
        {
            name: project_name, description: project_description, public: project_public,
            project_id: project_id, categories: categories
        }, editProjectActionUpdate);
}

function editProjectActionUpdate() {
    document.body.innerHTML = this.responseText;
}


function submitSearchTeamMember(event) {
    event.preventDefault();

    let inputVal = document.querySelector("div#members form.user_search_settings input").value;

    sendAjaxRequest("POST", event.target.action, { search: inputVal }, showProjectMemberSearch);
}

function showProjectMemberSearch() {
    let data = JSON.parse(this.responseText);

    if(data.success){
        let div = document.querySelector("div#show_members_settings");
    
        div.innerHTML = data.html;
    }
}


function inviteNewMember(event) {
    event.preventDefault();
    let username = document.querySelector("div#members div.user_search_settings input").value;

    let href = event.target.href;
    let index = href.indexOf('projects');
    let index2 = href.indexOf('settings');
    let project_id = href.substring(index + 9, index2 - 1);

    sendAjaxRequest("POST", href, { project_id: project_id, username: username }, inviteMemberHandler);
}

function inviteMemberHandler() {

    let data = JSON.parse(this.responseText);

    if (data.success) {
        swal("Has been sent an invite to" + data.username + " to join the project!", {
            icon: "success",
        });
    } else if (data.reason == "invite") {
        swal("Has already been sent an invite to the user!", {
            icon: "error",
        });
    } else if (data.reason == "project_member") {
        swal("The user is already a member of this project!", {
            icon: "error",
        });
    } else if (data.reason == 'user') {
        swal("This user does not exist!", {
            icon: "error",
        });
    }
}

addEventListenersSettings();