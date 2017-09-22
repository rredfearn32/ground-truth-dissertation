<?php
	session_start();
	$user = $_SESSION['user'];
	
	require_once "php/classes/membership.php";
	$membership = new Membership();

	/* Used on every page to ensure the user is logged in. */
	$membership->confirm_member();
?>

<!DOCTYPE html>
<html>
	<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Ground Truth & Computer Vision</title>
	</head>
	<!-- On page load, initiate JavaScript function with event listners for drag & drop events. -->
	<body onload="dragUploadInnit()">
		<?php require("header.php"); ?>
		<div id="uploader" class="main">
			<div class="container_12">
				<p>Upload progress: <progress id="uploadprogress" min="0" max="100" value="0">0</progress></p>
				<!-- Dropzone <div> reacts when files are dragged into it. -->
				<div id="dropzone" class="grid_12">
					<!-- Form for allowing manual uploading of files. -->
					<form id="upload-form" enctype="multipart/form-data">
						<label for="file">Drag a file here, or..</label><br />
						<input id="file-input" type="file" name="files[]" onchange="manualFileUpload()" multiple />
					</form>
				</div>
					<!-- The following elements are displayed as error messages if necessary technologies of the website are not supported by the browser. -->
					<p id="upload" class="hidden"><label>Drag & drop not supported, but you can still upload via this input field:<br><input type="file"></label></p>
					<p id="filereader">File API & FileReader API not supported</p>
					<p id="formdata">XHR2's FormData is not supported</p>
					<p id="progress">XHR2's upload progress isn't supported</p>
			</div>
			<div class="clear"></div>
		</div>
<?php require("footer.php"); ?>