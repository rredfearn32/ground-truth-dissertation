<?php
    session_start();
    require_once "php/classes/membership.php";
    $membership = new Membership();

    /* If the user clicks "Log Out" on Dashboard page... */
    if(isset($_GET['status']) && $_GET['status'] == 'logout'){
		/* ..call the log_out function. */
        $membership->log_out();
    }

    /* If the user has attempted to login from this page... */
    if($_POST && !empty($_POST['username']) && !empty($_POST['pwd'])){
		/* ...validate the user's details with the Membership class. */
        $response = $membership->validate_user($_POST['username'], $_POST['pwd']);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Ground Truth &amp; Computer Vision | Login</title>
    </head>
    <body>
		<div class="container_12">
			<div id="logout-title">
				<h1>Ground Truth</h1> <h3>& Computer Vision</h3>
			</div>
			<div class="grid_12">
				<div id="login" class="user-form-container">
					<p class="info">Enter your credentials to log in.</p>
					<form method="post" action="" onsubmit="return validateLogin(this);">
						<p>
							<label for="username" class="login"><span class="icon">f</span></label>
							<input type="text" name="username" placeholder="Username" />
						</p>
						<p>
							<label for="pwd" class="login"><span class="icon">n</span></label>
							<input type="password" name="pwd" placeholder="Password" />
						</p>
						<p>
							<input type="submit" id="submit" value="Login" name="submit" />
						</p>
					</form>
					<!-- Display error if user login is unsuccessful. -->
					<p class="error"><?php echo $response; ?></p>
					<p class="info">Not registered yet? <a href="register.php">Go register!</a><br /> ...or read <a href="test.php" rel="lightbox">about this service</a>.</p>
				</div>
			</div>
			<div class="clear"></div>
		</div>
    <?php require("footer.php"); ?>