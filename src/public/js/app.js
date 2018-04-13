function addEventListeners() {
	let userDropButton = document.querySelector("nav .user > img");

	userDropButton.addEventListener('click', dropUserOptions);
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

addEventListeners();
