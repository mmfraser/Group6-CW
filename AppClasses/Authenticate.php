<?php
	require_once('App.php');
	require_once('User.php');
	
	// TODO: add in active/disactive functionality.......
	class Authenticate {
		private $user;
		private $isAuthenticated;
		
		public function Authenticate() {
			$this->conn = App::getDB();
			$this->user = null;
			$this->isAuthenticated = false;
		}	
		
		public function doAuth($username, $password) {
			if(is_null($username) || is_null($password)) 
				return false;
				
			$query = "SELECT * FROM user WHERE username = '".mysql_real_escape_string($username)."' AND password = '".App::secureString($password)."'";
			
			$row = $this->conn->getDataRow($query);
			
			if($row == null)
				return false;
			else {  
				$user = new User();
				$user->populateId($row['userId']);
				$this->user = $user;
				return true;
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