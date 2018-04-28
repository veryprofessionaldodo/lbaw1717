function addEventListeners() {
    let newThreadButtonForm = document.querySelector("div#container div#overlay div.jumbotron p.lead a#newThread-btn");

    newThreadButtonForm.addEventListener('click',createThreadAction);
}

function createThreadAction(event) {
    event.preventDefault();

	let thread_title = document.querySelector("input[name='thread_title']").value;
    let thread_description = document.querySelector("textarea[name='thread_description']").value;
    let user_creator_username = event.target.attributes.user.value;

    let index = event.target.href.indexOf('projects');
    let project_id = event.target.href.substring(index + 9, event.target.href.length)[0];

	sendAjaxRequest('post', event.target.href, 
        {name: thread_title, description: thread_description, project_id: project_id, user_username : user_creator_username},showPageUpdated);
}

function editThreadAction(event) {
    event.preventDefault();

	let thread_title = document.querySelector("input[name='thread_title']").value;
    let thread_description = document.querySelector("textarea[name='thread_description']").value;
    let user_creator_username = event.target.attributes.user.value;

    let index = event.target.href.indexOf('projects');
    let project_id = event.target.href.substring(index + 9, event.target.href.length)[0];
    let thread_id = 

	sendAjaxRequest('post', event.target.href, 
        {name: thread_title, description: thread_description, project_id: project_id, user_username : user_creator_username},showPageUpdated);
}

function showPageUpdated() {

    let data = JSON.parse(this.responseText);

    document.open();
    document.write(data.html);
    document.close();
}


function deleteComment(button){

    let href = button.getAttribute('href');

    let r = confirm("Are you sure you want to delete this comment?\n");

    if (r == true) {
        let comment_id = button.id; 

        sendAjaxRequest('post', href, {comment_id: comment_id},commentsUpdated);
    } else {
        return;
    }
}

function commentsUpdated(){
    //TODO change to ajax
    location.reload();
}

addEventListeners();