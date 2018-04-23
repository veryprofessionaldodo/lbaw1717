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
    {name: thread_title, description: thread_description, project_id: project_id, user_username : user_creator_username},showThreadsUpdated);
}

function showThreadsUpdated() {

    let data = JSON.parse(this.responseText);

    document.open();
    document.write(data.html);
    document.close();
}

addEventListeners();