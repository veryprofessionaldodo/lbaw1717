function addEventListeners() {
    /*let newThreadButtonForm = document.querySelector("div#container div#overlay div.jumbotron p.lead a#newThread-btn");

    newThreadButtonForm.addEventListener('click',createThreadAction);*/

    submitComment = document.querySelector("div.comment#thread form");
    submitComment.addEventListener('submit', addCommentThread);
}
/*
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
*/
/*
function showPageUpdated() {

    let data = JSON.parse(this.responseText);
    console.log(data);
    let doc = document.querySelector("body");
    doc.innerHTML = data.html;
}*/

function addCommentThread(event) {
    event.preventDefault();

    let content = document.querySelector("div.comment#thread form input[name='content']").value;

    sendAjaxRequest('post', event.target.action, { content: content }, updateCommentsThread);
}

function updateCommentsThread() {
    let data = JSON.parse(this.responseText);
    console.log(data.comment);
    let lastComment = document.querySelector("div#thread div.comment:last-of-type");

    //let form = document.querySelector("div#task-"+ data.task_id + " div.comment:last-of-type");

    lastComment.insertAdjacentHTML('beforebegin', data.comment);

    let input = document.querySelector("div.comment#thread form input[name='content']");
    input.value = "";

}

function deleteCommentThread(button) {

    let href = button.getAttribute('href');

    let r = confirm("Are you sure you want to delete this comment?\n");
    
    if (r == true) {
        let comment_id = button.id;

        sendAjaxRequest('post', href, { comment_id: comment_id }, updateCommentThreadDeletion);
    } else {
        return;
    }
}

function updateCommentThreadDeletion() {
    let data = JSON.parse(this.responseText);
    if (data.success) {
        let comment = document.querySelector("div.comment[data-id='" + data.comment.id + "']");
        comment.remove();
    }
}

function deleteThread(button) {

    let href = button.getAttribute('href');

    let r = confirm("Are you sure you want to delete this thread and the comments in it?\n");

    if (r == true) {
        let thread_id = button.id;

        sendAjaxRequest('delete', href, { thread_id: thread_id }, redirectForum);
    } else {
        return;
    }
}

function redirectForum() {
    let data = JSON.parse(this.responseText);
    window.location.href = data.url;
}

addEventListeners();