function addEventListenersForum() {

    let submitCommentThread = document.querySelector("div.comment#thread form");
    if (submitCommentThread !== null)
        submitCommentThread.addEventListener('submit', addCommentThread);
}



function editCommentThread(button) {

    let ps = document.getElementsByClassName("content");

    var order = 0;

    for (let i = 0; i < ps.length; i++) {
        if (ps[i].id == button.id) {
            order = i
        }
    }

    let commentInfo = document.querySelectorAll("p.content")[order];
    let commentForm = document.querySelectorAll("div form#edit")[order];

    if (commentInfo.style.display !== "none") {
        let input = commentForm.querySelector("input#content.form-control.col-10");
        input.value = commentInfo.innerHTML;
        commentInfo.style.display = "none"
        commentForm.style.display = "block";
    }
    else {
        commentInfo.style.display = "block";
        commentForm.style.display = "none";

    }
}


function showPageUpdated() {

    let data = JSON.parse(this.responseText);
    
    if(data.success){
        let doc = document.querySelector("body");
        doc.innerHTML = data.html;
    }
}

function addCommentThread(event) {
    event.preventDefault();

    let content = document.querySelector("div.comment#thread form input[name='content']").value;

    sendAjaxRequest('post', event.target.action, { content: content }, updateCommentsThread);
}

function updateCommentsThread() {
    let data = JSON.parse(this.responseText);
    
    if(data.success){
        let lastComment = document.querySelector("div#thread div.comment:last-of-type");
    
        lastComment.insertAdjacentHTML('beforebegin', data.comment);
    
    }
    let input = document.querySelector("div.comment#thread form input[name='content']");
    input.value = "";

}

function deleteCommentThread(button) {

    let href = button.getAttribute('href');
    swal({
        title: "Are you sure you want to delete this comment?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                let comment_id = button.getAttribute("data-id");
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
        title: "Are you sure you want to delete this thread and the comments in it?",
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

    if(data.success){
        swal("Successfully deleted the thread and the comments in it !", {
            icon: "success",
        });
        window.location.href = data.url;
    }
}

addEventListenersForum();