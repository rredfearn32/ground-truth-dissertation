//UI Scripts//
var testFancybox;

// Function that makes all links with class='various' to open their linked media in a Fancybox iframe. //
$(document).ready(function() {
	if($(".various").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	})){
		testFancybox = true;
	}
	else{
		testFancybox = false;
	}
});

// Function to trim the name of images when displayed in Dashboard thumbnails. //
var dashboardImageTitle = document.getElementsByClassName("dashboardImageTitle");
for(var i=0;i<dashboardImageTitle.length;i++){
	if(dashboardImageTitle[i].textContent.length > 20){
		dashboardImageTitle[i].textContent = dashboardImageTitle[i].textContent.substring(0,20) + "...";
	}
}

// Upon attempted user registration, checks that all fields have values and returns false (stopping registration) if a field is incomplete. //
function validateRegister(){
	if($("input:text[name='fname']").val() == "" || $("input:text[name='username']").val() == "" || $("input:password[name='pwd']").val() == "" || $("input:password[name='pwd_conf']").val() == ""){
		alert("Please complete all the registration fields.");
		return false;
	}
	else{
		return true;
	}
}

// Upon attempted search, checks that all fields have values and returns false (stopping search) if a field is incomplete. //
function validateSearch(){
	if($("input#search").val() == ""){
		alert("Please enter a search term.");
		return false;
	}
	else{
		return true;
	}
}
// Upon attempted user login, checks that all fields have values and returns false (stopping login) if a field is incomplete. //
function validateLogin(){
	if($("input:text[name='username']").val() == "" || $("input:password[name='pwd']").val() == ""){
		alert("Please enter your login credentials.");
		return false;
	}
	else{
		return true;
	}
}