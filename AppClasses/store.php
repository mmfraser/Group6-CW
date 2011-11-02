<?php
	/*
		Store.php
		Created by: David (01/11/11)
		Adapted from User.php
		Description: Store object
		Update log:
			01/11/11 (DT) - Creation.
	*/
require_once('../App.php');

class Store {
/*	private $userId;
  	public $forename;
  	public $surname;
  	public $password;
  	public $active;
	public $username;
	private $oldPassword;
  	public $isLoaded;
	public $groupMembership = array();
*/
	
	private $storeId;
  	public $storeName;
  	public $address;
  	public $city;
  	public $manager;

	function __construct($storeId = "", $storeName = "", $address = "", $city = "", $manager = "") {
		$this->conn = App::getDB();
		$this->storeId = $storeId;
		$this->storeName = $storeName;
		$this->address = $address;
		$this->city = $city;
		$this->manager = $manager;
	}

	/*	This function gets the object with data given the storeId.
	*/
	public function populateId($storeId){
		$sql = "SELECT * FROM store WHERE storeId = '".mysql_real_escape_string($storeId)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}

	
	/*	This function gets the user's group memberships and populates the array.
	*/
/*	public function getGroupMembership($userId) {
		$sql = "SELECT groupId FROM groupmembership WHERE userId = '".mysql_real_escape_string($userId)."'";
		$memberships = $this->conn->getArrayFromDB($sql);
		foreach($memberships as $arr) {
			$this->groupMembership[] = $arr['groupId'];
		}
	}*/
		
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->storeId = $row['storeId'];
		$this->storeName = $row['storeName'];
		$this->address = $row['address'];
		$this->city = $row['city'];
		$this->manager = $row['manager'];
		$this->isLoaded = true;
	}
	
	/*	This function checks that the username is not in use before adding a new user.
	*/
/*	private function validUsername() {
		if($this->conn->getNumResults("SELECT username FROM user WHERE username = '".mysql_real_escape_string($this->username)."'") == 0)
			return true;
		return false;
	}*/

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function saveStore() {	
		if($this->storeName == null || $this->address == null || $this->city == null || $this->manager == null) {
			throw new Exception('One or more required fields are not completed.');
		}
	
	/*	if($this->active == null)
			$this->active = 0;*/

		if ($this->isLoaded === true) {
			/*if($this->oldPassword == $this->password) {
				$pass = $this->password;
			} else {
				$pass = App::secureString($this->password);
			}*/
	
			$SQL = "UPDATE store SET 
					storeName = '".mysql_real_escape_string($this->storeName)."' , 
					address = '".mysql_real_escape_string($this->address)."', 
					city = '".mysql_real_escape_string($this->city)."', 
					manager = ".mysql_real_escape_string($this->manager)." 
					WHERE storeId = '".mysql_real_escape_string($this->storeId)."'";
					
			$this->conn->execute($SQL);
			
			// Update user groups.
		/*	$SQL = "DELETE FROM groupmembership WHERE userId = '".$this->userId."'; "; 
			$this->conn->execute($SQL);
			
			foreach($this->groupMembership as $arr) {
				$SQL = "INSERT INTO groupmembership (userId, groupId) VALUES ('".$this->userId."','".$arr."') ";
				$this->conn->execute($SQL);
			}		*/	
		} else {
			/*if(!$this->validUsername()) {
				throw new Exception('Username already in use.');
			}*/
		
			$SQL = "INSERT INTO store (storeName, address, city, manager) VALUES (
					'".mysql_real_escape_string($this->storeName)."', 
					'".mysql_real_escape_string($this->address)."',  
					'".mysql_real_escape_string($this->city)."', 
					'".mysql_real_escape_string($this->manager)."')";
			$this->isLoaded = true;
			$this->storeId = $this->conn->execute($SQL);
		}		
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />storeId: " . $this->storeId;
		$str .= "<br />storeName: " . $this->storeName;
		$str .= "<br />address: " . $this->address;
		$str .= "<br />city: " . $this->city;
		$str .= "<br />manager: " . $this->manager;
		return $str;
	}
}

/*$test = new User();
$test->populateUsername("Marc");

//$test->save(); 
//$test->username = "marc3";


//$test->populateUsername("marc3");
//print "<br />" . $test->forename; 
print $test->toString();  */

?>
