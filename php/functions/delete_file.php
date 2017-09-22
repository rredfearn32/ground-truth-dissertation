<?php
	/* This file is called by the AJAX function in the CMS-scripts.js file. Unlike most other PHP files in this project, it is procedural based, since there was not time to determine how to call functions from PHP classes over AJAX. */
	$user = $_SESSION["user"];
	$id = $_GET["id"];
	require '../../php/includes/constants.php';
	
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) 
			or die('Database Connection Error');
	
	/* First of all, get the URL of the relevant image from the database... */
	$delete_file_query = "SELECT imageURL FROM cs394_12_13_rbj9.imageDB WHERE imageID=?";

	if($stmt = $conn->prepare($delete_file_query)){
		$stmt->bind_param('s', $id);
		if($stmt->execute()){
			if($stmt->bind_result($imageURL)){
				while($stmt->fetch() != ""){
					/* ...then, using that URL, delete the image from the user's Upload directory. */
					unlink("../../$imageURL");
					echo "Image file deleted.";
				}
			}
			else{
				echo "No such image file exists.";
				return false;
			}
		}
	}
	
	/* Then remove the record of the image in the imageDB table. */
	$remove_record_query = "DELETE FROM cs394_12_13_rbj9.imageDB WHERE imageID=?";

	if($stmt = $conn->prepare($remove_record_query)){
		$stmt->bind_param('s', $id);
		if($stmt->execute()){
			echo "Image record removed from database.";
		}
	}
	else{
		echo "Could not remove image record from database.";
		return false;
	}
	
	/* And finally, delete all ROI Tags from the roiDB table that are associated with that image. */
	$clear_roi_query = "DELETE FROM cs394_12_13_rbj9.roiDB WHERE imgID=?";
	
	if($stmt = $conn->prepare($clear_roi_query)){
		$stmt->bind_param('s', $id);
		if($stmt->execute()){
			echo "Images ROI tag records removed from database.";
		}
	}
	else{
		echo "Could not remove images ROI tag records.";
		return false;
	}
?>
