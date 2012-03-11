<?php
	/*
		Store.php
		Created by: David (01/11/11)
		Adapted from User.php
		Description: Store object
		Update log:
			01/11/11 (DT) - Creation.
	*/
require_once(dirname(__FILE__).'/../App.php');

class Store {
	
	private $storeId;
  	public $storeName;
  	public $address;
  	public $city;

	function __construct($storeId = "", $storeName = "", $address = "", $city = "") {
		$this->conn = App::getDB();
		$this->storeId = $storeId;
		$this->storeName = $storeName;
		$this->address = $address;
		$this->city = $city;
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
		
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->storeId = $row['storeId'];
		$this->storeName = $row['storeName'];
		$this->address = $row['address'];
		$this->city = $row['city'];
		$this->isLoaded = true;
	}
	
	/*	This function returns the storeId propery's value
	*/
	public function getStoreId(){
		return $this->storeId;
	}
	
	
	
	public function saveStore() {	
		if($this->storeName == null || $this->address == null || $this->city == null) {
			throw new Exception('One or more required fields are not completed.');
		}

		if ($this->isLoaded === true) {
	
			$SQL = "UPDATE store SET 
					storeName = '".mysql_real_escape_string($this->storeName)."' , 
					address = '".mysql_real_escape_string($this->address)."', 
					city = '".mysql_real_escape_string($this->city)."' 
					WHERE storeId = '".mysql_real_escape_string($this->storeId)."'";
			$this->conn->execute($SQL);
	
		} else {
		
			$SQL = "INSERT INTO store (storeName, address, city) VALUES (
					'".mysql_real_escape_string($this->storeName)."', 
					'".mysql_real_escape_string($this->address)."',  
					'".mysql_real_escape_string($this->city)."')";
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
		return $str;
	}
}

?>
