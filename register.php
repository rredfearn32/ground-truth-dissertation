<?php
	session_start();
	require_once "php/classes/membership.php";
	$membership = new Membership();
	
	if($_POST && !empty($_POST['username']) && !empty($_POST['pwd']) && !empty($_POST['pwd_conf']) && !empty($_POST['fname'])){
		/* If all the registration fields are set, perform registration of user. */
		$response = $membership->register_user($_POST['fname'], $_POST['username'], $_POST['pwd'], $_POST['pwd_conf']);
	}
?>

<!DOCTYPE html>
<html>
	<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Ground Truth &amp; Computer Vision | Register</title>
	</head>
	<body>
		<div class="container_12">
			<div id="logout-title">
				<h1>Ground Truth</h1> <h3>& Computer Vision</h3>
			</div>

				<div id="register" class="user-form-container">
					<p class="info">Enter your details to register and start using the service!</p>
					<form method="post" action="" onsubmit="return validateRegister(this);">
						<div id="register-labels" class="grid_6">
							<p>
								<label for="fullname">Full Name:</label>
							</p>
							<p>
								<label for="username">Username:</label>
							</p>
							<p>
								<label for="pwd">Password:</label>
							</p>
							<p>
								<label for="pwd_conf">Confirm Password:</label>
							</p>
						</div>
						<div id="register-input" class="grid_6">
							<p>
								<input type="text" name="fname" />
							</p>
							<p>
								<input type="text" name="username" />
							</p>
							<p>
								<input type="password" name="pwd" />
							</p>
							<p>
								<input type="password" name="pwd_conf" />
							</p>
						</div>
						<div class="clear"></div>
						<div class="grid_12">
							<input type="submit" id="submit" value="Submit" name="submit" />
						</div>
						<div class="clear"></div>
					</form>
					<!-- Display error if user registration is unsuccessful. -->
					<p class='error'><?php echo $response; ?></p>
					<p class="info">Already registered? <a href="login.php">Login!</a></p>
				</div>

			<div class="clear"></div>
<?php require("footer.php"); ?>