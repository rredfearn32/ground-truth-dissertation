QUnit.config.reorder = false;
var canvas = document.getElementById('annotator-canvas');
var bgimage = document.getElementById('canvas-bg');
var ctx = canvas.getContext('2d');

test( "Add ROI Tag", function() {
	addRect(200,200,100,100,"#444444");
	
	equal(boxes2.length,1);
	equal(document.getElementById("roi-meta").innerHTML,"<div><input name=\"Box_0\" id=\"Box_0\" value=\"200,200,100,100\"><span class=\"icon\" id=\"del_Box_0\" onclick=\"deleteROI(Box_0)\">x</span></div>");
	equal(canvasValid,false);
});

test("Add Another ROI Tag", function() {
	addRect(250,250,100,100,"#444444");
	
	equal(boxes2.length,2);
});

test("Delete ROI Tag", function() {
	addRect(250,250,100,100,"#444444");
	deleteROI(boxes2[2]);
	
	equal(boxes2.length,2);
});