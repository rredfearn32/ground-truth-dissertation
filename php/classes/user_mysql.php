<?php
/* This class handles most of the direct interactions with the userDB table in the database. The majority of fetching and manipulation of user data goes through this class. */
require_once 'php/includes/constants.php';

class UserMysql{
	
	/* Create database connection using fixed variables found in constants.php. */
	function __construct(){
		$this->conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) 
			or die('Database Connection Error');
	}
	
	/* Function for verifying the user's credentials. Returns true if a record is returned matching the username and password pair. */
	function verify_user_credentials($un, $pwd){
		$query = "SELECT * FROM userDB WHERE userName=? AND userPassword=? LIMIT 1";
		
		if($stmt = $this->conn->prepare($query)){
			$stmt->bind_param('ss', $un, $pwd);
			$stmt->execute();
			
			if($stmt->fetch()){
				$stmt->close();
				return true;
			}
		}
	}
	
	/* Function for checking if a username is unique. If a record exists with a username matching the one entered, return false. */
	function check_unique_user($un){
		$query = "SELECT * FROM userDB WHERE userName=?";
					
		if($stmt = $this->conn->prepare($query)){
			$stmt->bind_param('s', $un);
			$stmt->execute();
			
			if($stmt->fetch()){
				$stmt->close();
				return false;
			}
			else{
				return true;
			}
		}
	}
	
	/* Function for fetching a user's ID by their username. */
	function get_user_id($un){
		$query = "SELECT userID FROM userDB WHERE userName=?";
					
		if($stmt = $this->conn->prepare($query)){
			$stmt->bind_param('s', $un);
			$stmt->execute();
			
			$stmt->bind_result($userID);
			return $userID;
		}
	}
	
	/* Function for adding a new user to the userDB table. */
	function add_new_user($fn, $un, $pwd){
		$query = "INSERT INTO userDB(firstName,userName,userPassword) VALUES(?,?,?)";
					
		if($stmt = $this->conn->prepare($query)){
			$stmt->bind_param('sss', $fn, $un, $pwd);
			if($stmt->execute()){
				return true;
			}
		}
	}
	
}