function addEventListenersReport() {
    let newReportForm = document.querySelector("div#container div#overlay div.jumbotron div#lead a#newReport-btn");

    if(newReportForm !== null)
        newReportForm.addEventListener('click', createReportAction);
}

function createReportAction(event) {
    event.preventDefault();

    let report_summary = document.querySelector("textarea[name='report_summary']").value;
    let type = event.target.attributes.type.value;
    let index;


    if (type === 'USER') {
        index = event.target.href.indexOf('users');
        let user_reported = event.target.href.substring(index + 6, event.target.href.length);

        type = 'userReported';

        sendAjaxRequest('post', event.target.href,
            { summary: report_summary, user_reported: user_reported, type: type }, showPageUpdated);

    } else if (type === 'COMMENT') {
        index = event.target.href.indexOf('comments');
        let comment_id = event.target.href.substring(index + 8, event.target.href.length);

        type = 'commentReported';

        sendAjaxRequest('post', event.target.href,
            { summary: report_summary, comment_id: comment_id, type: type }, showPageUpdated);
    } else {
        console.log('ERROR IN REPORT ACTION !!!');
    }
}

addEventListenersReport();