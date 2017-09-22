<?php

// LOTS OF CODE ON THIS PAGE WAS ADAPTED FROM A VIDEO TUTORIAL BY JEFFREY WAY, WHICH CAN BE FOUND AT:
// http://net.tutsplus.com/articles/news/how-to-build-a-login-system-for-a-simple-website/
// SOME MODIFICATIONS WERE MADE BY THE ME, BUT MUCH OF THE CODE CAME FROM THE TUTORIAL.

require 'user_mysql.php';

class Membership{
	
	function validate_user($un, $pwd){

		$userMysql = new UserMysql();
		$pwd = secure_crypt($pwd, $un);
		
		// Checks if there is a user with that username and password combination...
		$ensure_credentials = $userMysql->verify_user_credentials($un, $pwd);
		
		//If there is, then set the session variables and progress to the user Dashboard.
		if($ensure_credentials){
			$_SESSION['status'] = 'authorized';
			$_SESSION['user'] = $_POST['username'];
			header("location:index.php");
		}
		else{
			//Else, keep the user logged out and display the following error message...
			return "Please enter correct username and password.";
		}
	}
	
	function log_out(){
		if(isset($_SESSION['status'])){
			//Unset the status session variable.
			unset($_SESSION['status']);
			
			//Completely remove the session data.
			if(isset($_COOKIE[session_name()])){
				setcookie(session_name(), '', time() - 10000);
				$_SESSION = Array(); // <- Courtesy of php.net, session page.
				session_destroy();
			}
		}
	}
	
	//Confirm that a user logged in session is present.
	function confirm_member(){
		session_start();
		if($_SESSION['status'] != 'authorized'){
			header("location:login.php");
		}
	}
	
	function register_user($fn, $un, $pwd, $pwd_conf){
		$userMysql = new UserMysql();
		
		//Hash both the password and password confirmation fields. 
		$pwd = secure_crypt($pwd, $un);
		$pwd_conf = secure_crypt($pwd_conf, $un);
		
		if($pwd == $pwd_conf){
			if($userMysql->check_unique_user($un) == true){
				//Make the user's upload and export folders.
				if(mkdir("upload/" . $un, 0755, true) && mkdir("export/" . $un, 0755, true)){
					$add_user = $userMysql->add_new_user($fn, $un, $pwd);
					if($add_user){
						$_SESSION['status'] = 'authorized';
						$_SESSION['user'] = $un;
						header("location:index.php");
					}
					else{
						return "Sorry, something went wrong.";
					}
				}
				else{
					return "Could not create folder";
				}
			}
			else{
				return "Sorry, a user with that username already exists. Please choose another.";
			}
		}
		else{
			return "Please make sure your two entered passwords match.";
		}
	}
}

function secure_crypt($password, $un){
	//Uses Blowfish encryption on a combined string of username + password.
	$hash = crypt($password . $un, '$2a$07$pleasekeepthissafe$');
	return $hash;
}