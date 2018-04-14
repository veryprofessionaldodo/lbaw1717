function addEventListeners() {
	let userDropButton = document.querySelector("nav .user > img");
	userDropButton.addEventListener('click', dropUserOptions);

	let notificationDropIcon = document.querySelector("nav .user #notifications label");
	notificationDropIcon.addEventListener('click', dropNotificationsMenu);
}

function dropUserOptions() {
	let userDropdownMenu = document.querySelector("nav .user div#profile_options");
	if(userDropdownMenu.style.height == "0px" || userDropdownMenu.style.height == 0){
		userDropdownMenu.style.height = "auto";
		userDropdownMenu.style.opacity = "1";
	}
	else {
		userDropdownMenu.style.height = "0";
		userDropdownMenu.style.opacity = "0";
	}
}

function dropNotificationsMenu() {
	let notificationDropdown = document.querySelector("nav .user #notifications #notifications_box");
	let notifications = document.querySelector("nav .user #notifications #notifications_box ul li");
	
	if(notifications != null){
		if(notificationDropdown.style.height == 0){
			notificationDropdown.style.height = "300px";
			notificationDropdown.style.opacity = 1;
		}
		else {
			notificationDropdown.style.height = 0;
			notificationDropdown.style.opacity = 0;
		}
	}
}

addEventListeners();
