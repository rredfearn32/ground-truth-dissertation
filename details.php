<?php
	require_once "php/classes/image_mysql.php";
	require_once "php/classes/dashboard.php";
	require_once "php/classes/membership.php";
	
	$membership = new Membership();

	/* Used on every page to ensure the user is logged in. */
	$membership->confirm_member();
	
	/* These variables catch the results of posts from this page. */
	$imgID = $_GET["imgID"];
	$name = $_POST["image-name"];
	$access = $_POST["access"];
	
	$dashboard = new Dashboard();
	
	/* Variable to cause page to reload ONLY when form is submited. */
	$reload = false;
	
	/* Checks that the name field is set, and submits the form data for saving. */
	if(isset($name) && $name!=""){
		$dashboard->submit_changes($name, $access, $imgID);
		/* Sets the $reload vaiable to true. */
		$reload = true;
	}
	
	$imageMysql = new ImageMysql();
	
	/* Get image data and metadata from databse. */
	$nameArr = $imageMysql->get_image_name_by_id($imgID);
	$imageROIs = $imageMysql->sql_load_rois($imgID);
	
	$nameLength = strlen($nameArr[0]);
	
	/* Begin printing of HTML page. */
	echo "
	<html>
	<head>
		<link rel='stylesheet' type='text/css' href='css/style.css'/>
	";
	
	if($reload == true){
		/* If $reload is true, this JavaScript is loaded along with the page, which causes the top-level window to reload, forcibly returning the user to the Dashboard. */
		echo "<script>window.top.location.reload();</script>";
	}
	
	/* Image name fetched from the database is printed here as the input element's value. */
	echo "
	</head>
	<body>
		<form id='update_details' method='post' action=''>
			<p>Image Name: <input name='image-name' class='update-details-input' value='$nameArr[0]' /></p>
			<p>
				<input type='radio' name='access' value='0' checked='checked'>Private <br />
				<input type='radio' name='access' value='1'>Public
			</p>
	";
	
	/* If there are ROI Tags... */
	if(count($imageROIs) > 0){
		/* ..create a table element.. */
		echo "
			<p>Image Tags:<br />
			<table class='overlay-tags-table'>
		";
			/* ..and for each ROI Tag data field found in the database.. */
			foreach($imageROIs as $item){
				/* ..create a table row and print the string of coordinates and size. */
				echo "
					<tr>
						<td>$item</td>
					</tr>
				";
			}
		echo "
			</table>
		";
	}
	echo "
			<input type='submit' value='Submit Changes' />
		</form>
	</body>
	</html>
	";
	/* End printing of HTML page. */
?>