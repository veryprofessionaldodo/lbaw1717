function addEventListeners() {
    let newReportForm = document.querySelector("div#container div#overlay div.jumbotron div#lead a#newReport-btn");
    
    newReportForm.addEventListener('click',createReportAction);
}

function createReportAction(event) {
    event.preventDefault();

    let report_summary = document.querySelector("textarea[name='report_summary']").value;
    let type = event.target.attributes.type.value;

    let index = event.target.href.indexOf('users');
    let user_reported = event.target.href.substring(index + 6, event.target.href.length);

    if(type === 'USER'){
        type = 'userReported';
    }else if(type === 'COMMENT'){
        type = 'commentReported';
    }

    
	sendAjaxRequest('post', event.target.href, 
    {summary: report_summary, user_reported: user_reported, type : type},reportUserhandler);
    
}

function reportUserhandler() {
    let data = JSON.parse(this.responseText);

    console.log(data);

    document.open();
    document.write(data.html);
    document.close();
}

addEventListeners();