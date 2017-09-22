<?php
	/* This file is called by the AJAX function in the CMS-scripts.js file. Unlike most other PHP files in this project, it is procedural based, since there was not time to determine how to call functions from PHP classes over AJAX. */
	session_start();
	
	require '../../php/includes/constants.php';
	
	/* Create database connection using fixed variables found in constants.php. */
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) 
			or die('Database Connection Error');
	
	$user = $_SESSION['user'];
	$formData = $_FILES;
	
	if (!$conn){
		die('Could not connect: ' . mysql_error());
	}
	else{
		/* Create a counter to iterate through the FormData array. */
		$i = 0;
		/* Crete empty response string. */
		$response = "";
		/* For each file uploaded... */
		foreach($formData as $file){
			/* ...check that the file is of a valid type... */
			$allowedExts = array("jpg", "JPG", "jpeg", "JPEG", "gif", "GIF", "png", "PNG");
			$extension = end(explode(".", $formData["file" . $i]["name"]));
			if ((($formData["file" . $i]["type"] == "image/gif")
			|| ($formData["file" . $i]["type"] == "image/jpeg")
			|| ($formData["file" . $i]["type"] == "image/png")
			|| ($formData["file" . $i]["type"] == "image/pjpeg"))
			&& in_array($extension, $allowedExts))
			{
				/* ...or if the file already exists. */
				if (file_exists("../../upload/$user/" . $formData["file" . $i]["name"])){
					$response = "Upload Failed. \n" . $formData["file" . $i]["name"] . " already exists. ";
				}
					/* If the file is unique, and type is valid... */
				else{
					/* ...upload the file to the user's folder in the Upload directory... */
					if(move_uploaded_file($formData["file" . $i]["tmp_name"], "../../upload/$user/" . $formData["file" . $i]["name"])){
						/* ...and create a record for the file, including its name, url and owner, in the imageDB table... */
						$fileName = $formData["file" . $i]["name"];
						$filePath = "upload/" . $user . "/" . $formData['file' . $i]['name'] . "";
						/* ...using a MySQLi prepared statement... */
						$query = "INSERT INTO imageDB(imageOwner,imageName,imageURL) VALUES (?,?,?)";
						if($stmt = $conn->prepare($query)){
							$stmt->bind_param('sss', $user, $fileName, $filePath);
							$stmt->execute();
							/* ...then set the response text to a successful message. */
							$response = "Files Uploaded!";
						}
						/* ...and increment the counter by one so that the next file can be referenced. */
						$i++;
					}
					else{
						/* But if the file could not be moved to the server... */
						$response = "Upload Failed. \n Could not move file to server.";
					}
				}
			}
			else{
				/* ...or if the file is an invalid type then set the response to a fail message. */
				$response = "Upload Failed. \n Invalid File Type.";
			}
		}
		/* Finally, return the reponse to be displayed to the user. */
		echo $response;
	}
?>