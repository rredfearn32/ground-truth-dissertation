test("Delete File - Fail" ,function() {
	var testDelete = deleteFile();
	
	equal(testDelete,false);
});