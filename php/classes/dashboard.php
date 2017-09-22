<?php
	require_once "image_mysql.php";
	
	/* Dashboard class contains functions relevant to the Dashboard (Index) page. */
	class Dashboard{
		
		/* Function used to generate the image thumbnailsfor each user's Dashboard page. Takes into account arguments for searches and filters. */
		function display_thumbnails($user, $filter, $search){
			$imageMysql = new ImageMysql();
			/* Load the URL, Name and ID of each of the user's images. */
			$urlArr = $imageMysql->load_user_images($user, $filter, $search);
			$idArr = $imageMysql->get_image_id($user, $filter, $search);
			$nameArr = $imageMysql->get_image_name($user, $filter, $search);
			
			/* Set a count variable equal to the number of image URLs returned, used to iterate through the arrays of image data. */
			$count = count($urlArr);
			
			/* If there is at least one image returned... */
			if($count > 0){			
				/* ...for each image... */
				for($i=0;$i<$count;$i++){
					/* ...construct a thumbnail <div>, with the name, url and id plugged into the various elements. */
					echo "
						<div class='grid_3 dt-container'>
							<div class='dt-overlay'>
								<span class='dashboardImageTitle'>$nameArr[$i]</span>
								<hr />
								<a href='#' title='Delete Image'><span class='icon' onclick='deleteFile($idArr[$i])'>I</span></a>
								<a href='annotator.php?id=$idArr[$i]' title='Annotate Image'><span class='icon'>e<span></a>
								<a href='details.php?imgID=$idArr[$i]' data-fancybox-type='iframe' title='View &amp; Edit Image Details' class='various'><span class='icon'>=</span></a>
							</div>
							<img src='$urlArr[$i]' alt='$idArr[$i] & $urlArr[$i] & $nameArr[$i]' class='dashboard-thumbnail' />
						</div>
					";
				}
			}
			else{
				/* If there are no images returned by the database queries, display this message. */
				echo "<h2>There are no images... yet!</h2><p>Go and <a href='upload.php'>upload</a> some, then get tagging!</p>";
			}
		}
		
		
		/* Function for submitting changes to an image's details. Called from the Details page. */
		function submit_changes($name, $access, $imgID){
			$imageMysql = new ImageMysql();
			$imageMysql->set_image_properties($name, $access, $imgID);
		}
	}