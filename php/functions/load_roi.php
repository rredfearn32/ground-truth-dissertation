<?php
	/* This file is called by the AJAX function in the ANT-scripts.js file. Unlike most other PHP files in this project, it is procedural based, since there was not time to determine how to call functions from PHP classes over AJAX. */
	$imgID = $_GET["imgID"];
	require_once "../../php/includes/constants.php";
	
	/* Create database connection using fixed variables found in constants.php. */
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) 
			or die('Database Connection Error');
	
	/* Construct the query to be used in the MySQLi statement. */
	$query = "SELECT roiX, roiY, roiWidth, roiHeight FROM roiDB WHERE imgID=?";

	if($stmt = $conn->prepare($query)){
		/* Pass the image ID into the query as a parameter... */
		$stmt->bind_param('s', $imgID);
		if($stmt->execute()){
			/* ...then bind the results of the query to four variables. */
			if($stmt->bind_result($roiX, $roiY, $roiW, $roiH)){
				$roiArray = array();
				/* Start a count to iterate through the indexs of the array created above... */
				$count = 0;
				/* ...and whilst there are still results from the query... */
				while ($stmt->fetch() != "") {
					/* ...set the relevant index of the array to equal the a string comprised of the values returned from the database. */
					$roiArray[$count] = ($roiX . "," . $roiY . "," . $roiW . "," . $roiH);
					/* Incrememnt the counter by one to focus on the next array index... */
					$count++;
				}
				/* ...and encode the array object as a JSON object before returning it to the JavaScript. */
				echo json_encode($roiArray);
			}
		}
	}
?>