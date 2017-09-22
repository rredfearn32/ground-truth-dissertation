<?php

/* This class provides functionality relevant to the Image Annotator operations. */

require_once "image_mysql.php";

class Canvas{
	/* Takes an Image's ID and returns the Image's URL. */
	function load_image_url($imgId){
		$imageMysql = new ImageMysql();
		$idArray = $imageMysql->get_image_url_by_id($imgId);
		echo $idArray[0];
	}
	
	/* Function for saving ROI Tags. Called from the Annotator page. */
	function process_rois($user, $imgID, $post){
		$imageMysql = new ImageMysql();
		/* First, delete all ROI Tags associated with the relevant image... */
		$imageMysql->delete_rois($imgID);
		/* ...then, for each input element submitted from the Annotator page... */
		foreach($post as $item){
			/* ...apart from the hidden "empty" input element... */
			if($item != "empty"){
				/* ...save the value of the input field. */
				$result = $imageMysql->save_roi($user, $imgID, $item);
			}
		}
		return $result;
	}
	
	/* Function for exporting ROI Tag data as CSV format file. Called from the Export page. */
	function export_csv($imgID){
		$imageMysql = new ImageMysql();
		/* Load all the ROI Tags associated with the relevant image. */
		$result = $imageMysql->sql_load_rois($imgID);
		/* Manually enter the column headers as the first line of the file. */
		$string =  "ID,X,Y,Width,Height\n";
		/* For each result of the ROI Tag query... */
		foreach($result as $item){
			/* ...add the result to the $string variable on a new line. */
			$string .= $item . "\n";
		}
		/* Finally, return the  $string variable to be printed into the csv file. */
		return $string;
	}
	
	/* Function for exporting ROI Tag data as YAML format file. Called from the Export page. */
	function export_yaml($imgID){
		$imageMysql = new ImageMysql();
		/* Load all the ROI Tags associated with the relevant image. */
		$result = $imageMysql->sql_load_rois($imgID);
		/* Create an empty string to hold the contents of the file. */
		$string = "";
		/* For each result of the ROI Tag query... */
		foreach($result as $item){
			/* Break the string down by comma characters into an array. */
			$itemPieces = explode(",", $item);
			/* Structure the array pieces into the first format for YAML, inserting new lines and tab characters where needed. */
			$string .= "ROI Tag : " . $itemPieces[0] . "\n\tX : " . $itemPieces[1] . "\n\tY : " . $itemPieces[2] . "\n\tW : " . $itemPieces[3] . "\n\tH : " . $itemPieces[4] . "\n"; 
			}
		/* Finally, return the $string variable to be printed into the yaml file. */
		return($string);
	}
}