function taskPageEventListeners() {
    let editButton = document.querySelector("button.edit_task");
    let assignTaskUserButton = document.querySelector("div.coordinator_options button#assign_user");

    if(editButton !== null){
        editButton.addEventListener('click', showEditForm);
    }

    if(assignTaskUserButton !== null){
        assignTaskUserButton.addEventListener('click', showAssignUserForm);
    }

    // tasks completion
	let tasksCheckbox = document.querySelector("div#checkbox input[type='checkbox']");
	tasksCheckbox.addEventListener('click', updateTaskCompletion);

	// assign to task
	let assignSelfTaskButton = document.querySelector("div.sprint-task a.claim");
	assignSelfTaskButton.addEventListener('click', assignSelfTask);
}

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

    let taskInfo = document.querySelector("div#task_info div#task_description");
    let taskForm = document.querySelector("div#task_info form");
    
    if(taskInfo.style.display !== "none"){
        taskInfo.style.display = "none";

        let textarea = document.querySelector("div#task_info form textarea");
        textarea.innerHTML = taskInfo.innerHTML;
        initTinyMCE();
        taskForm.classList.remove('hidden');
    }
    else {
        taskInfo.style.display = "block";
        taskForm.classList.add('hidden');
    }
}

function showAssignUserForm(event) {

    let assignUserForm = document.querySelector("section#task_page div#assign_user_form");

    if(assignUserForm.classList.contains('hidden')){
        assignUserForm.classList.remove('hidden');
    }
    else {
        assignUserForm.classList.add('hidden');
    }
}


function updateTaskCompletion() {
	let url = this.getAttribute("data-url");
	
	let state;
	if(this.checked){
		state = "Completed";
	} else if(!this.checked){
		state = "Uncompleted";
	}

	sendAjaxRequest('post', url, {state: state}, updateTaskState);
}

function updateTaskState(){
	let data = JSON.parse(this.responseText);
	
	let task = document.querySelector("div[data-id='" + data.task_id + "']");

	if(data.state === "Completed"){

		let task_options = document.querySelector("div.task_options");
		task_options.remove();

		let assigned_users = document.querySelector("div.assigned_users");
		if(assigned_users !== null)
			assigned_users.remove();


	} else if(data.state === "Uncompleted") {

        // STILL TO BE DONE!!
		if(data.coordinator){

			let referenceNode = document.querySelector("div[data-id='" + data.task_id + "'] a.task_name");
	
			let newDiv = document.createElement("div");
			newDiv.classList.add("coordinator_options");

			let button1 = document.createElement("button");
			button1.innerHTML = "<i class='fas fa-pencil-alt'></i>";
			//ADD URL
			button1.classList.add("btn", "edit_task");
			newDiv.appendChild(button1);

			referenceNode.parentNode.insertBefore(newDiv, referenceNode.nextSibling);

		} else {

			if(data.user_username != null){
				createAssignUserDiv(data);
			}
			
			let newButton = document.createElement("a");
			newButton.classList.add("btn", "claim");
			newButton.href = data.claim_url;

			if(data.assigned)
				newButton.innerHTML = "Unclaim Task"
			else
				newButton.innerHTML = "Claim Task";
			task.appendChild(newButton);
		} 
	}
}

/**
 * Assign or unassign self to task
 * @param {*} event 
 */
function assignSelfTask(event){
	event.preventDefault();

	sendAjaxRequest('post', event.target.href, null, updateAssignUsers);
}

/**
 * Updates the assign_users Div and buttons
 */
function updateAssignUsers(){
	let data = JSON.parse(this.responseText);

	let assigned_user = document.querySelector("div[data-id='" + data.task_id + "'] div.assigned_users");
	let claimButton = document.querySelector("div[data-id='" + data.task_id + "'] a.claim");

	// the request was to unassign user of the task
	if(data.claim_url != null){

		assigned_user.remove();
		claimButton.href = data.claim_url;
		claimButton.innerHTML = "Claim Task";

	}
	else {
		// if the request was to assign user to task
		if(assigned_user !== null){
			
			//update assigned_user
			assigned_user.firstChild.src = data.image;
			assigned_user.firstChild.title = data.username;
		}
		else {
			createAssignUserDiv(data);
		}
			
		claimButton.innerHTML = "Unclaim Task";
		claimButton.href = data.unclaim_url;
	}
}

/**
 * Creates a assign_users DIV with the current assigned user to the task
 * @param {*} data 
 */
function createAssignUserDiv(data) {
	let referenceNode = document.querySelector("div[data-id='" + data.task_id + "'] a.task_name");

	let divUsers = document.createElement("div");
	divUsers.classList.add("assigned_users");

	let assigned_user = document.createElement("img");
	assigned_user.src = data.image;
	assigned_user.title = data.user_username;

	divUsers.appendChild(assigned_user);

	referenceNode.parentNode.insertBefore(divUsers, referenceNode.nextSibling);
}

taskPageEventListeners();