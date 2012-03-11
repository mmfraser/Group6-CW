<?php
	/*
		Authenticate.php
		Created by: Marc
		Description: Authentication object
		Update log:
			23/10/11 (MF) - Creation.
	*/

	require_once(dirname(__FILE__).'/../App.php');
	require_once('User.php');
	require_once('Group.php');
	require_once('store.php');
	
	// TODO: add in active/disactive functionality.......
	class Authenticate {
		private $user;
		private $isAuthenticated;
		
		public function __construct() {
			$this->conn = App::getDB();
		}
		
		public function Authenticate() {
			$this->user = null;
			$this->isAuthenticated = false;
		}	
		
		public function doAuth($username, $password) {
			if(is_null($username) || is_null($password)) 
				return false;
				
			$query = "SELECT * FROM user WHERE username = '".mysql_real_escape_string($username)."' AND password = '".App::secureString($password)."' AND active=1";
			
			$row = $this->conn->getDataRow($query);
			
			if($row == null)
				return false;
			else {  
				$user = new User();
				$user->populateId($row['userId']);
				$this->user = $user;
				$this->isAuthenticated = true;
				$_SESSION['loggedIn'] = serialize($this);
			} 
		}
		
		public function getAuthUser() {
			return $this->user;
		}
		
		public function isAuth() {
			return $this->isAuthenticated;
		}
	}
?>