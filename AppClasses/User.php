<?php
	/*
		User.php
		Created by: Marc Fraser (23/10/11)
		Description: User object
		Update log:
			23/10/11 (MF) - Creation.
			
	*/
include('App.php');

class User {
	private $userId;
  	public $forename;
  	public $surname;
  	public $password;
  	public $active;
	public $username;
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
		$this->getRow($row);
	}
	
	/*	This function get the object with data given the username.
	*/
	public function populateUsername($username){
		$sql = "SELECT * FROM user WHERE username = '".mysql_real_escape_string($username)."'";
		$row = $this->conn->getDataRow($sql);
		$this->getRow($row);
	}
	
	/*	This function populates the object with data given a datarow.
	*/
	private function getRow($row){
		$this->userId = $row['userId'];
		$this->forename = $row['forename'];
		$this->surname = $row['surname'];
		$this->password = $row['password'];
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
		if($this->forename == null || $this->surname == null || $this->password == null || $this->active == null || $this->username == null) {
			throw new Exception('One or more required fields are not completed.');
		}
		
		if ($this->isLoaded === true) {
			$SQL = "UPDATE user SET 
					forename = '".mysql_real_escape_string($this->forename)."' , 
					surname = '".mysql_real_escape_string($this->surname)."', 
					password = '".mysql_real_escape_string($this->password)."', 
					active = ".mysql_real_escape_string($this->active)." 
					WHERE userId = '".mysql_real_escape_string($this->userId)."'";
		} else {
			if(!$this->validUsername())
				throw new Exception('Username already in use.');
		
			$SQL = "INSERT INTO user (forename, surname, password, active, username) VALUES (
					'".mysql_real_escape_string($this->forename)."', 
					'".mysql_real_escape_string($this->surname)."', 
					'".mysql_real_escape_string($this->password)."', 
					'".mysql_real_escape_string($this->active)."', 
					'".mysql_real_escape_string($this->username)."')";
		}
		
		return $this->conn->execute($SQL);
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />userId: " . $this->userId;
		$str .= "<br />username: " . $this->username;
		$str .= "<br />forename: " . $this->forename;
		$str .= "<br />surname: " . $this->surname;
		$str .= "<br />password: " . $this->password;
		$str .= "<br />active: " . $this->active;
		return $str;
	}
}

$test = new User();
$test->username = "Marc1";
$test->forename = "Marc1";
$test->surname = "Fraser1";
$test->password = "test";
$test->active = true;
//$test->save();
$test->populateUsername("Marc1");
print "<br />" . $test->forename; 
print $test->toString();

?>