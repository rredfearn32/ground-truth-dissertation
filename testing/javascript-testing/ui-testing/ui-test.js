test("Test Fancybox" ,function() {
	equal(testFancybox,true);
});

test("User Registration - Fail" ,function() {
	var fields = validateRegister();
	
	equal(fields,false);
});

test("User Registration - Pass" ,function() {
	document.getElementById("fname").value = "test";
	document.getElementById("username").value = "test";
	document.getElementById("pwd").value = "test";
	document.getElementById("pwd_conf").value = "test";
	var fields = validateRegister();
	
	equal(fields,true);
});

test("Search - Fail" ,function() {
	var search = validateSearch();
	
	equal(search,false);
});

test("Search - Pass" ,function() {
	document.getElementById("search").value = "test";
	var search = validateSearch();
	
	equal(search,true);
});

test("User Login - Fail" ,function() {
	var search = validateLogin();
	
	equal(search,false);
});

test("User Login - Fail" ,function() {
	document.getElementById("username").value = "test";
	document.getElementById("pwd").value = "test";
	var search = validateLogin();
	
	equal(search,true);
});