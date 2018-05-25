function addEventListenersForum() {
    /*let newThreadButtonForm = document.querySelector("div#container div#overlay div.jumbotron p.lead a#newThread-btn");

    newThreadButtonForm.addEventListener('click',createThreadAction);*/
    let editButton = document.querySelector("button.edit_comment");
    if(editButton !== null){
        editButton.addEventListener('click', showEditForm);
    }
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

function initTinyMCE() {
    tinymce.init({
        selector: '#mytextarea',
        plugins: 'code,codesample,lists,advlist,image,link,paste,textcolor,textpattern,contextmenu,emoticons,pagebreak,table',
        toolbar: 'code, codesample, bullist, numlist, image, link, paste,forecolor backcolor,emoticons, pagebreak, table',
        menubar: false,
        contextmenu: 'link image inserttable | cell row column deletetable',
        image_caption: true,
        image_advtab: true,
        code_dialog_height: 300,
        code_dialog_width: 350,
        advlist_bullet_styles: 'default,circle,disc,square',
        advlist_number_styles: 'lower-alpha,lower-roman,upper-alpha,upper-roman',
        codesample_languages: [
            {text: 'HTML/XML', value: 'markup'},
            {text: 'JavaScript', value: 'javascript'},
            {text: 'CSS', value: 'css'},
            {text: 'PHP', value: 'php'},
            {text: 'Ruby', value: 'ruby'},
            {text: 'Python', value: 'python'},
            {text: 'Java', value: 'java'},
            {text: 'C', value: 'c'},
            {text: 'C#', value: 'csharp'},
            {text: 'C++', value: 'cpp'}
        ]
    });
}

function showEditForm() {

    let commentInfo = document.querySelector("p#content");
    let commentForm = document.querySelector("form#edit");
    
    if(commentInfo.style.display !== "none"){
        commentInfo.style.display = "none";

        let textarea = document.querySelector("form#edit textarea");
        textarea.innerHTML = commentInfo.innerHTML;
        initTinyMCE();
        commentForm.classList.remove('hidden');
    }
    else {
        commentInfo.style.display = "block";
        commentForm.classList.add('hidden');
    }
}

function showPageUpdated() {

    let data = JSON.parse(this.responseText);
    console.log(data);
    let doc = document.querySelector("body");
    doc.innerHTML = data.html;
}

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
    swal({
        title: "Are you sure you want to delete this comment?\n",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                let comment_id = button.id;
                sendAjaxRequest('post', href, { comment_id: comment_id }, updateCommentThreadDeletion);
            }
        });
}

function updateCommentThreadDeletion() {
    let data = JSON.parse(this.responseText);
    if (data.success) {
        let comment = document.querySelector("div.comment[data-id='" + data.comment_id + "']");
        comment.remove();
        swal("The comment has been deleted !", {
            icon: "success",
        });
    }
}

function deleteThread(button) {

    let href = button.getAttribute('href');

    swal({
        title: "Are you sure you want to delete this thread and the comments in it?\n",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                let thread_id = button.id;
                sendAjaxRequest('delete', href, { thread_id: thread_id }, redirectForum);
            }
        });
}

function redirectForum() {
    let data = JSON.parse(this.responseText);
    swal("Successfully deleted the thread and the comments in it !", {
        icon: "success",
    });
    window.location.href = data.url;
}

addEventListenersForum();