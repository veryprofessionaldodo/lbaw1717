
function addEventListenersNotifications() {

    let dismissNotifications = document.querySelectorAll("div#notifications_box ul li.row div.notification_options a.dismiss");
    for (let i = 0; i < dismissNotifications.length; i++) {
        dismissNotifications[i].addEventListener('click', dismissNotification);
    }

    let acceptInviteNotifications = document.querySelectorAll("div#notifications_box ul li.row div.notification_options a.accept");
    for (let i = 0; i < acceptInviteNotifications.length; i++) {
        acceptInviteNotifications[i].addEventListener('click', acceptInviteNotification);
    }

    let rejectInviteNotifications = document.querySelectorAll("div#notifications_box ul li.row div.notification_options a.reject");
    for (let i = 0; i < rejectInviteNotifications.length; i++) {
        rejectInviteNotifications[i].addEventListener('click', rejectInviteNotification);
    }
}

function dismissNotification(event) {
    event.preventDefault();

    let index = event.target.href.indexOf('notifications');
    let notification_id = event.target.href.substring(index + 14, event.target.href.length - 7);

    sendAjaxRequest('post', event.target.href, { notification_id: notification_id }, updateNotifications);
}

function acceptInviteNotification(event) {
    event.preventDefault();

    let index = event.target.href.indexOf('notifications');
    let notification_id = event.target.href.substring(index + 14, event.target.href.length - 7);

    sendAjaxRequest('post', event.target.href, { notification_id: notification_id }, updateNotifications);
}

function rejectInviteNotification(event) {
    event.preventDefault();

    let index = event.target.href.indexOf('notifications');
    let notification_id = event.target.href.substring(index + 14, event.target.href.length - 7);

    sendAjaxRequest('post', event.target.href, { notification_id: notification_id }, updateNotifications);
}

function updateNotifications() {

    let data = JSON.parse(this.responseText);
    if (data.success) {
        let notifications = document.querySelectorAll("div#notifications_box ul li");

        if (notifications.length == 1) {
            let notificationDropdown = document.querySelector("nav .user #notifications #notifications_box");
            notificationDropdown.style.height = 0;
            notificationDropdown.style.opacity = 0;
        } else {
            let notification = document.querySelector("div#notifications_box ul li[data-id='" + data.notification_id + "']");
            notification.remove();
        }
    }
}

addEventListenersNotifications();