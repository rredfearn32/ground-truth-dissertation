<?php
	session_start();
	$user = $_SESSION['user'];
?>
<html>
	<head>
		<title>About | Coming Soon!</title>
	</head>
	<body>
	<?php require("header.php"); ?>
		<div class="main">
			<div class="container_12">
				<div class="grid_2">&nbsp;</div>
				<div class="grid_8">
					<h1>Error 40FAIL!</h1>
					<p>This page is still in development. Please come back soon!</p>
				</div>
				<div class="grid_2">&nbsp;</div>
			</div>
		</div>
	<?php include("footer.php") ?>