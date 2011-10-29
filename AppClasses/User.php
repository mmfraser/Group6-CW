<?php
	/*
		User.php
		Created by: Marc (23/10/11)
		Description: User object
		Update log:
			23/10/11 (MF) - Creation.
			
	*/
require_once('../App.php');

class User {
	private $userId;
  	public $forename;
  	public $surname;
  	public $password;
  	public $active;
	public $username;
	private $oldPassword;
  	public $isLoaded;

	function __construct($userId = "", $userName = "", $Forename = "", $Surname = "", $Password = "", $Active = "") {
		$this->conn = App::getDB();
		$this->userId = $userId;
		$this->username = $userName;
		$this->forename = $Forename;
		$this->surname = $Surname;
		$this->password = $Password;
		$this->active = $Active;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($userId){
		$sql = "SELECT * FROM user WHERE userId = '".mysql_real_escape_string($userId)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}
	
	/*	This function get the object with data given the username.
	*/
	public function populateUsername($username){
		$sql = "SELECT * FROM user WHERE username = '".mysql_real_escape_string($username)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}
		
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->userId = $row['userId'];
		$this->forename = $row['forename'];
		$this->surname = $row['surname'];
		$this->password = $row['password'];
		$this->oldPassword = $row['password'];
		$this->active = $row['active'];
		$this->username = $row['username'];
		$this->isLoaded = true;
	}
	
	/*	This function checks that the username is not in use before adding a new user.
	*/
	private function validUsername() {
		if($this->conn->getNumResults("SELECT username FROM user WHERE username = '".mysql_real_escape_string($this->username)."'") == 0)
			return true;
		return false;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->forename == null || $this->surname == null || $this->password == null || $this->username == null) {
			throw new Exception('One or more required fields are not completed.');
		}
	
		if($this->active == null)
			$this->active = 0;

		if ($this->isLoaded === true) {
			if($this->oldPassword == $this->password) {
				$pass = $this->password;
			} else {
				$pass = App::secureString($this->password);
			}
	
			$SQL = "UPDATE user SET 
					forename = '".mysql_real_escape_string($this->forename)."' , 
					surname = '".mysql_real_escape_string($this->surname)."', 
					username = '".mysql_real_escape_string($this->username)."', 
					password = '".$pass."', 
					active = ".mysql_real_escape_string($this->active)." 
					WHERE userId = '".mysql_real_escape_string($this->userId)."'";
		} else {
			if(!$this->validUsername()) {
				throw new Exception('Username already in use.');
			}
		
			$SQL = "INSERT INTO user (forename, surname, password, active, username) VALUES (
					'".mysql_real_escape_string($this->forename)."', 
					'".mysql_real_escape_string($this->surname)."', 
					'".App::secureString($this->password)."', 
					'".mysql_real_escape_string($this->active)."', 
					'".mysql_real_escape_string($this->username)."')";
			$this->isLoaded = true;
		}

		return $this->conn->execute($SQL);
		
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />userId: " . $this->userId;
		$str .= "<br />username: " . $this->username;
		$str .= "<br />surname: " . $this->surname;
		$str .= "<br />forename: " . $this->forename;
		$str .= "<br />password: " . $this->password;
		$str .= "<br />active: " . $this->active;
		return $str;
	}
}

/*$test = new User();
//$test->populateUsername("Marc2");
$test->username = "Marc5";
$test->forename = "Marc2";
$test->surname = "Fraser2";
$test->password = "test";
$test->active = true;
$test->save(); 
//$test->username = "marc3";


//$test->populateUsername("marc3");
//print "<br />" . $test->forename; 
print $test->toString(); */

?>