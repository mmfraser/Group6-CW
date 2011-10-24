<?php
include('App.php');

class User {
	private $userId;
  	public $forename;
  	public $surname;
  	public $password;
  	public $active;
  	public $isLoaded;

	function __construct($userId = "", $Forename = "", $Surname = "", $Password = "", $Active = "") {
		$this->conn = App::getDB();
		$this->userId = $userId;
		$this->forename = $Forename;
		$this->surname = $Surname;
		$this->password = $Password;
		$this->active = $Active;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populate($userId){
		$sql = "SELECT * FROM user WHERE userId = '".mysql_real_escape_string($userId)."'";
		$row = $this->conn->getDataRow($sql);
		$this->getRow($row);
		return $row;
	}
	
	/*	This function populates the object with data given a datarow
	*/
	private function getRow($row){
		$this->userId = $row['userId'];
		$this->forename = $row['forename'];
		$this->surname = $row['surname'];
		$this->password = $row['password'];
		$this->active = $row['active'];
		$this->isLoaded = true;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->forename == null || $this->surname == null || $this->password == null || $this->active == null) {
			throw new exception('One or more required fields are not completed.');
		}
		
		if ($this->isLoaded === true) {
			$SQL = "UPDATE user SET 
					forename = '".mysql_real_escape_string($this->forename)."' , 
					surname = '".mysql_real_escape_string($this->surname)."', 
					password = '".mysql_real_escape_string($this->password)."', 
					active = ".mysql_real_escape_string($this->active)." 
					WHERE userId = '".mysql_real_escape_string($this->userId)."'";
		} else {
			$SQL = "INSERT INTO user (forename, surname, password, active) VALUES (
					'".mysql_real_escape_string($this->forename)."', 
					'".mysql_real_escape_string($this->surname)."', 
					'".mysql_real_escape_string($this->password)."', 
					'".mysql_real_escape_string($this->active)."')";
		}
		
		return $this->conn->execute($SQL);
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />userId: " . $this->userId;
		$str .= "<br />forename: " . $this->forename;
		$str .= "<br />surname: " . $this->surname;
		$str .= "<br />password: " . $this->password;
		$str .= "<br />active: " . $this->active;
	}
}

/*$test = new User();
$test->populate(1);
print $test->forename;
$test->forename = "Marc";
$test->save();
$test->populate(1);
print "<br />" . $test->forename; */

?>