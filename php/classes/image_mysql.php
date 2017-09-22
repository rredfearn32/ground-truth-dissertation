<?php
	/* This class handles most of the direct interactions with the imageDB and roiDB tables in the database. The majority of fetching and manipulation of image data goes through this class. */
	
	require 'php/includes/constants.php';
	
	class ImageMysql{
		
		/* Create database connection using fixed variables found in constants.php. */
		function __construct(){
			$this->conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME)
			or die('Database Connection Error');
		}
		
		/* Function for loading the URL of a user's images, taking into account the filter and search parameters. */
		function load_user_images($un, $filter, $search){
			/* Get the search term the user set. */
			$search = "%" . $search . "%";
			/* If the filter variable is set... */
			if($filter=="1"){
				/* ...include the filter variable in the query and search for public images... */
				$query = "SELECT imageURL FROM imageDB WHERE imagePublic=? AND imageName LIKE ? ORDER BY imageID DESC";
			}	
			/* ...and if it's not... */
			else{
				/* ...leave the filter variable out and just show images from the owner. */
				$query = "SELECT imageURL FROM imageDB WHERE imageOwner=? AND imageName LIKE ? ORDER BY imageID DESC";
			}

			if($stmt = $this->conn->prepare($query)){
				/* If filter variables are set, pass the relevant query parameters in. Include the search parameter either way.*/
				if($filter=="1"){
					$stmt->bind_param('ss', $filter, $search);
				}
				else{
					$stmt->bind_param('ss', $un, $search);
				}
				if($stmt->execute()){
					if($stmt->bind_result($imageURL)){
						$arr = array();
						while($stmt->fetch() != ""){
							/* Return the results of the query in an array to iterate through in later functions. */
							$arr[] = $imageURL;
						}
						return $arr;
					}
					else{
						echo "You have not uploaded any images yet. Please upload some!";
					}
				}
			}
		}
		
		/* Function for loading the ID of a user's images, taking into account the filter and search parameters. It functions in exactly the same way as the previous function, except for fetching the image's ID rather than URL.*/
		function get_image_id($un, $filter, $search){
			$search = "%" . $search . "%";
			if($filter=="1"){
				$query = "SELECT imageID FROM imageDB WHERE imagePublic=? AND imageName LIKE ? ORDER BY imageID DESC";
			}
			else{
				$query = "SELECT imageID FROM imageDB WHERE imageOwner=? AND imageName LIKE ? ORDER BY imageID DESC";
			}

			if($stmt = $this->conn->prepare($query)){
				if($filter=="1"){
					$stmt->bind_param('ss', $filter, $search);
				}
				else{
					$stmt->bind_param('ss', $un, $search);
				}
				if($stmt->execute()){
					if($stmt->bind_result($imageID)){
						$arr = array();
						while($stmt->fetch() != ""){
							$arr[] = $imageID;
						}
						return $arr;
					}
					else{
						echo "You have not uploaded any images yet. Please upload some!";
					}
				}
			}
		}
		/* Function for loading the name of a user's images, taking into account the filter and search parameters. It functions in exactly the same way as the previous function, except for fetching the image's name rather than URL.*/
		function get_image_name($un, $filter, $search){
			$search = "%" . $search . "%";
			if($filter=="1"){
				$query = "SELECT imageName FROM imageDB WHERE imagePublic=? AND imageName LIKE ? ORDER BY imageID DESC";
			}
			else{
				$query = "SELECT imageName FROM imageDB WHERE imageOwner=? AND imageName LIKE ? ORDER BY imageID DESC";
			}

			if($stmt = $this->conn->prepare($query)){
				if($filter=="1"){
					$stmt->bind_param('ss', $filter, $search);
				}
				else{
					$stmt->bind_param('ss', $un, $search);
				}
				if($stmt->execute()){
					if($stmt->bind_result($imageName)){
						$arr = array();
						while($stmt->fetch() != ""){
							$arr[] = $imageName;
						}
						return $arr;
					}
					else{
						echo "You have not uploaded any images yet. Please upload some!";
					}
				}
			}
		}
		
		/* Function to get a single image's url by its ID. */
		function get_image_url_by_id($id){
			$query = "SELECT imageURL FROM imageDB WHERE imageID=?";

			if($stmt = $this->conn->prepare($query)){
				$stmt->bind_param('s', $id);
				if($stmt->execute()){
					if($stmt->bind_result($imageURL)){
						$arr = array();
						while($stmt->fetch() != ""){
							$arr[] = $imageURL;
						}
						return $arr;
					}
					else{
						echo "You have not uploaded any images yet. Please upload some!";
					}
				}
			}
		}
		
		/* Function to get a single image's name by its ID. */
		function get_image_name_by_id($id){
			$query = "SELECT imageName FROM imageDB WHERE imageID=?";

			if($stmt = $this->conn->prepare($query)){
				$stmt->bind_param('s', $id);
				if($stmt->execute()){
					if($stmt->bind_result($imageURL)){
						$arr = array();
						while($stmt->fetch() != ""){
							$arr[] = $imageURL;
						}
						return $arr;
					}
					else{
						echo "You have not uploaded any images yet. Please upload some!";
					}
				}
			}
		}
		
		/* Function to save ROI tags. This function is called by the Canvas class when passing ROI Tag input strings to be saved. */
		function save_roi($user, $imgID, $item){
			/* Break the ROI Tag string down into seperate pieces by comma characters */
			$pieces = explode(",", $item);
			
			/* Construct prepared statement for MySQLi query. */
			$query = "INSERT INTO roiDB (roiX, roiY, roiWidth, roiHeight, imgOwner, imgID) VALUES (?, ?, ?, ?, ?, ?)";

			if($stmt = $this->conn->prepare($query)){
				/* Insert the pieces of the ROI Tag string, along with the image's ID and it's owner's ID into the roiDB table. */
				$stmt->bind_param('iiiisi', $pieces[0], $pieces[1], $pieces[2], $pieces[3], $user, $imgID);
				if($stmt->execute()){
					return true;
				}
				else{
					return false;
				}
			}
		}
		
		/* Function for loading ROI Tags from the database. Called by the Canvas class. Works in the opposite way to the save_roi function, in that this function takes separate values loaded by the database and compiles them all into single strings.*/
		function sql_load_rois($imgID){
			$query = "SELECT roiID, roiX, roiY, roiWidth, roiHeight FROM roiDB WHERE imgID=?";

			if($stmt = $this->conn->prepare($query)){
				$stmt->bind_param('s', $imgID);
				if($stmt->execute()){
					if($stmt->bind_result($roiID, $roiX, $roiY, $roiW, $roiH)){
						$roiArray = array();
						$count = 0;
						while ($stmt->fetch() != "") {
							/* Compile the results of the query into a single string of coordinate and size data. */
							$roiArray[$count] = ($roiID . "," . $roiX . "," . $roiY . "," . $roiW . "," . $roiH);
							$count++;
						}
						return $roiArray;
					}
					else{
						echo "You have not uploaded any images yet. Please upload some!";
					}
				}
			}
		}
		
		/* Function for setting an images name and access rights. Called by the Canvas class when used on the Details page. */
		function set_image_properties($name, $access, $imgID){
			$query = "UPDATE imageDB SET imageName=?, imagePublic=? WHERE imageID=?";
			if($stmt = $this->conn->prepare($query)){
				$stmt->bind_param('sss', $name, $access, $imgID);
				if($stmt->execute()){
					return true;
				}
				else{
					return false;
				}
			}
		}
		
		/* Function for removing ROI Tag records by their ID. */
		function delete_rois($imgID){
			$query = "DELETE FROM cs394_12_13_rbj9.roiDB WHERE imgID=?";
		
			if($stmt = $this->conn->prepare($query)){
				$stmt->bind_param('s', $imgID);
				if($stmt->execute()){
					return true;
				}
			}
			else{
				echo "Could not remove images ROI tag records.";
				return false;
			}
		}
	}