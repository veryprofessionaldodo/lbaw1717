function taskPageEventListeners() {
    let editButton = document.querySelector("button.edit_task");
    let assignTaskUserButton = document.querySelector("div.coordinator_options button#assign_user");

    if(editButton !== null){
        editButton.addEventListener('click', showEditForm);
    }

    if(assignTaskUserButton !== null){
        assignTaskUserButton.addEventListener('click', showAssignUserForm);
    }
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

function showAssignUserForm() {
    let assignUserForm = document.querySelector("section#task_page div#assign_user_form");
    console.log(assignUserForm.style.display);

    if(assignUserForm.style.display == "none"){
        assignUserForm.style.display = "inline";
    }
    else{
        assignUserForm.style.display = "none";
    }
}

taskPageEventListeners();