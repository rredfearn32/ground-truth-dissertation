<?php
	session_start();
	$imgID = $_GET["imgID"];
	$user = $_SESSION["user"];
	
	require_once "php/classes/membership.php";
	$membership = new Membership();

	/* Used on every page to ensure the user is logged in. */
	$membership->confirm_member();
	
	require_once "php/classes/canvas.php";
	$canvas = new Canvas();
	
	/* Takes the output from the export_canvas PHP function and puts it into the export_csv.csv file. */
	$result = $canvas->export_csv($imgID);
	$fp = fopen("export/$user/export_csv.csv","wb");
	fwrite($fp,$result);
	fclose($fp);
	
	/* Same as above function, but with YAML format. */
	$result = $canvas->export_yaml($imgID);
	$fp = fopen("export/$user/export_yaml.yaml","wb");
	fwrite($fp,$result);
	fclose($fp);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Ground Truth &amp; Computer Vision</title>
	</head>
	<body>
		<!-- Links to the exportable files in the relevant user's directory. -->
		<a href="export/<?php echo $user; ?>/export_csv.csv">Download CSV</a><br />
		<a href="export/<?php echo $user; ?>/export_yaml.yaml">Download YAML</a>
	</body>
</html>