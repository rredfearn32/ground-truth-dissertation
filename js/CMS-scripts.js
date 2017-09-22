//CMS Scripts//

// LOTS OF CODE ON THIS PAGE IS NOT MINE, AND WAS ADAPTED FROM TUTORIALS.  
// Only the deleteFile() and manualFileUpload() functions were written by me.
// The other functions were from a tutorial which was unable to be located
// at the time of writing. I claim no ownership of them!

// Definition of variables used for testing browser support of necessary technologies, and the dropzone page element. //
var holder = document.getElementById('dropzone'),
	tests = {
		filereader: typeof FileReader != 'undefined',
		dnd: 'draggable' in document.createElement('span'),
		formdata: !!window.FormData,
		progress: "upload" in new XMLHttpRequest
	},
	support = {
		filereader: document.getElementById('filereader'),
		formdata: document.getElementById('formdata'),
		progress: document.getElementById('progress')
	},
	acceptedTypes = {
		'image/png': true,
		'image/jpeg': true,
		'image/gif': true
	},
	progress = document.getElementById('uploadprogress'),
	fileupload = document.getElementById('upload');

// Uses FileReader to read the file submitted, then puts the result into an Image element and appends the Image into the dropzone <div>. //
function previewfile(file) {
	if (tests.filereader === true && acceptedTypes[file.type] === true) {
		var reader = new FileReader();
		reader.onload = function (event) {
			var image = new Image();
			image.src = event.target.result;
			image.width = 250; // a fake resize
			holder.appendChild(image);
		};
		reader.readAsDataURL(file);
	}
	else{
		holder.innerHTML += '<p>Uploaded ' + file.name + ' ' + (file.size ? (file.size/1024|0) + 'K' : '');
		console.log(file);
	}
}

// Creates a FormData object. Iterates through the files uploaded by the user, adds them all to the FormData object, then submits the FormData object via AJAX to the upload PHP file. //
function readfiles(files) {
	var formData = tests.formdata ? new FormData() : null;
	for (var i = 0; i < files.length; i++) {
		if (tests.formdata) formData.append('file' + i, files[i]);
		previewfile(files[i]);
	}

	if (tests.formdata) {
		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'php/functions/upload_file.php');
		xhr.onload = function() {
			progress.value = progress.innerHTML = 100;
			alert(xhr.responseText);
			window.location.href = "index.php";
		};
		
		// Sets the page's <progress> element to reflect the upload progress of the files. //
		// Individual progress bars for each image were planned to be implemented, but could not be due to time constraints. I forgot to put that in my write up, so I'm putting it here, for what it's worth. //
		if (tests.progress) {
			xhr.upload.onprogress = function (event) {
				if (event.lengthComputable) {
					var complete = (event.loaded / event.total * 100 | 0);
					progress.value = progress.innerHTML = complete;
				}
			}
		}
		xhr.send(formData);
	}
}

// Performs checks of all the necessary technology for drag & drop file upload. If test fails, error message is displayed on page, and if all are passed then initiate event listners. If event listners are triggered, results of the event are passed to the readfiles() function. //
function dragUploadInnit(){
	"filereader formdata progress".split(' ').forEach(function (api) {
		if(tests[api] === false) {
			support[api].className = 'fail';
		}
		else{
			support[api].className = 'hidden';
		}
	});
	if (tests.dnd) {
		var form = document.getElementById("file-input");
		holder.ondragover = function () { this.className = 'hover'; return false; };
		holder.ondragend = function () { this.className = ''; return false; };
		holder.ondrop = function (e) {
			this.className = '';
			e.preventDefault();
			readfiles(e.dataTransfer.files);
		}
	}
	else{
		fileupload.className = 'hidden';
		fileupload.querySelector('input').onchange = function () {
			readfiles(this.files);
		};
	}
}

// If the file upload element is modified, the results are passed to the readfile() function. //
function manualFileUpload(){
	var fileInput = document.getElementById('file-input');
	var files = fileInput.files;
	readfiles(files);
}

/*Function to delete file. If user confirms, passes the request to delete_file.php, which handles the delete and then reloads dashboard.*/
function deleteFile(id){
	var conf = confirm("Are you sure you want to delete this image?");
	if(conf == true){
		if (window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}
		else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				window.location.reload();
			}
			else{
				console.log(xmlhttp.responseText);
			}
		}
		xmlhttp.open("GET","../ground-truth/php/functions/delete_file.php?id="+id,true);
		xmlhttp.send();
	}
	else{
		return false;
	}
}