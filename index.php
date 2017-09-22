<?php
	session_start();
	$user = $_SESSION['user'];
	
	/* Set $search to entered search term. */
	if(isset($_GET["search-term"])){
		$search = $_GET["search-term"];
	}
	
	/* Set $filter to desired filter option. */
	if(isset($_GET["filter"])){
		$filter = $_GET["filter"];
	}
	
	require_once "php/classes/dashboard.php";
	$dashboard = new Dashboard();
	
	require_once "php/classes/membership.php";
	$membership = new Membership();
	
	/* Used on every page to ensure the user is logged in. */
	$membership->confirm_member();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Ground Truth &amp; Computer Vision | Dashboard</title>
	</head>
	<body>
		<?php require("header.php"); ?>
		<div id="gallery" class="main">
			<div class="container_12">
				<div id="gallery-options">
					<div class="grid_6 left">
						<div id="filter-container">View...
							<ul>
								<li><a href="index.php?filter=0">Private Images</a></li>
								<li><a href="index.php?filter=1">Public Images</a></li>
							</ul>
						</div>
						<?php 
							/* If the $filter variable is set, print out whether the user is viewing public or private images. */
							if($filter=="0"){
								echo "<div>Viewing images in the <b>Private</b> category<a href='index.php'><span class='icon'>x</span></a></div>";
							}
							elseif($filter=="1"){
								echo "<div>Viewing images in the <b>Public</b> category<a href='index.php'><span class='icon'>x</span></a></div>";
							}
						?>
					</div>
					<div class="grid_6 right">
						<form id="dashboard-search" method="get" action="" onsubmit="return validateSearchInput(this);">
							<input type="text" name="search-term" id="search" placeholder="Enter a search term..." />
							<input type="submit" id="submit-search" value="Search" />
						</form>
						<?php
							/* If the $search variable is set, print out the search term the user is viewing. */
							if(isset($search)){
								echo "<div>Showing search results for: <b>$search</b><a href='index.php'><span class='icon'>x</span></a></div>";
							}
						?>
					</div>
					<div class="clear"></div>
				</div>
				<?php 
					/* Prints out dashboard thumbnails, taking into account $filter and $search terms. */
					$dashboard->display_thumbnails($user, $filter, $search); 
				?>
			</div>
			<div class="clear"></div>
		</div>
<?php require("footer.php"); ?>