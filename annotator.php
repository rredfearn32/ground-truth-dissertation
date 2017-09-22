<?php
	session_start();
	$user = $_SESSION['user'];
	$imgID = $_GET['id'];
	
	require_once "php/classes/membership.php";
	$membership = new Membership();
	
	/*Used on every page to ensure the user is logged in.*/
	$membership->confirm_member();

	require_once "php/classes/canvas.php";
	$canvas = new Canvas();
	
	/*Perform save of ROI Tag data if set in POST object.*/
	if(isset($_POST) && $_POST != array()){
		$canvas->process_rois($user, $imgID, $_POST);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" >
		<title>Ground Truth &amp; Computer Vision | Image Annotator</title>
	</head>
	<body onload="canvasInit(<?php echo $imgID; ?>)">
	<?php require("header.php"); ?>
	<div id="annotator" class="main">
		<div class="container_12">
			<div id="canvas-tools" class="grid_8">
				<div class="inner">
					<!-- Image src is loaded from database using $imgID passed to page. -->
					<img id='canvas-bg' src="<?php $canvas->load_image_url($imgID); ?>" />
					<!-- Canvas with interior message visible to users on legacy browsers. -->
					<canvas id='annotator-canvas'>
						Your browser does not appear to support the HTML5 Canvas element. Please upgrade your browser to experience the full features of this service.
					</canvas>
					<br />
					<!-- Link to details page, opened in Fancybox. -->
					<a href='details.php?imgID=<?php echo $imgID; ?>' data-fancybox-type='iframe' title='View &amp; Edit Image Details' class='various'><input type="button" id="detailsButton" value="Details" /></a>
					<!-- Link to export page, opened in Fancybox. -->
					<a href="export.php?imgID=<?php echo $imgID; ?>" data-fancybox-type='iframe' class="various"><input type="button" id="exportButton" value="Export" /></a>
				</div>
			</div>
			<!-- Right side of the page, which contains list of ROI Tag Input elements.. -->
			<div id="image-boxes" class="grid_4">
				<div class="inner">
					<h2>Regions of Interest</h2>
					<form id="roi-meta" action="" method="post">
						<div><input type="submit" value="Save ROI tags" /></div>
						<!-- Hidden <div> is always submited along with the form to stop the submission being a blank array() if no ROI Tags are present. Code to avoid submiting the 'empty' field is present in the Canvas PHP class. -->
						<input type="hidden" name="empty" value="empty" />
					</form>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
<?php require("footer.php"); ?>