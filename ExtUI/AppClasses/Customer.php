<?php
	/*
		Customer.php
		Created by: Marc 
		Description: Customer object
		Update log:
			12/11/11 (MF) - Creation.
	*/
require_once('../App.php');

class Customer {
	private $customerId;
  	public $emailAddress;
  	public $forename;
  	public $surname;
  	public $addressLine1;
	public $addressLine2;
	public $town;
	public $city;
	public $postcode;
	public $telephoneNumber;
  	public $isLoaded;

	function __construct($customerId = "", $emailAddress = "", $forename = "", $surname = "", $addressLine1 = "", $addressLine2 = "", $town = "", $city = "", $postcode = "", $telephoneNumber = "") {
		$this->conn = App::getDB();
		$this->customerId = $emailAddress;
		$this->emailAddress = $emailAddress;
		$this->forename = $forename;
		$this->surname = $surname;
		$this->addressLine1 = $addressLine1;
		$this->addressLine2 = $addressLine2;
		$this->town = $town;
		$this->city = $city;
		$this->postcode = $postcode;
		$this->telephoneNumber = $telephoneNumber;
	}

	/*	This function gets the object with data given the customer's email address.
	*/
	public function populate($emailAddress){
		$sql = "SELECT * FROM customer WHERE emailAddress = '".mysql_real_escape_string($emailAddress)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}
			
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->customerId = $row['customerId'];
		$this->oldEmailAddress = $row['emailAddress'];
		$this->emailAddress = $row['emailAddress'];
		$this->forename = $row['forename'];
		$this->surname = $row['surname'];
		$this->addressLine1 = $row['addressLine1'];
		$this->addressLine2 = $row['addressLine2'];
		$this->town = $row['town'];
		$this->city = $row['city'];
		$this->postcode = $row['postcode'];
		$this->telephoneNumber = $row['telephoneNumber'];
		$this->isLoaded = true;
	}

	private function validEmail() {
		if($this->conn->getNumResults("SELECT emailAddress FROM customer WHERE emailAddress = '".mysql_real_escape_string($this->emailAddress)."'") == 0)
			return true;
		return false;
	}

	
	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->emailAddress == null) 
			throw new Exception('Email address field is required not completed.');
		
		if ($this->isLoaded === true) {
			if(($this->emailAddress != $this->oldEmailAddress) && !$this->validEmail())
				throw new Exception('Email address already in use.');
		
		
			$SQL = "UPDATE customer SET 
					forename = '".mysql_real_escape_string($this->forename)."', 
					emailAddress = '".mysql_real_escape_string($this->emailAddress)."',
					surname = '".mysql_real_escape_string($this->surname)."', 
					addressLine1 = '".mysql_real_escape_string($this->addressLine1)."', 
					addressLine2 = '".mysql_real_escape_string($this->addressLine2)."', 
					town = '".mysql_real_escape_string($this->town)."', 
					city = '".mysql_real_escape_string($this->city)."', 
					postcode = '".mysql_real_escape_string($this->postcode)."',  
					telephoneNumber = '".mysql_real_escape_string($this->telephoneNumber)."' 
					WHERE customerId = ".mysql_real_escape_string($this->customerId);
					
			$this->conn->execute($SQL);
		} else {		
			if(!$this->validEmail())
				throw new Exception('Email address already in use.');
			$SQL = "INSERT INTO customer (emailAddress, forename, surname, addressLine1, addressLine2, town, city,  postcode, telephoneNumber) VALUES (
					'".mysql_real_escape_string($this->emailAddress)."', 
					'".mysql_real_escape_string($this->forename)."', 
					'".mysql_real_escape_string($this->surname)."', 
					'".mysql_real_escape_string($this->addressLine1)."', 
					'".mysql_real_escape_string($this->addressLine2)."', 
					'".mysql_real_escape_string($this->town)."', 
					'".mysql_real_escape_string($this->city)."', 
					'".mysql_real_escape_string($this->postcode)."', 
					'".mysql_real_escape_string($this->telephoneNumber)."')";
			$this->isLoaded = true;
			$this->customerId = $this->conn->execute($SQL);
		}		
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />customerId: " . $this->customerId;
		$str .= "<br />emailAddress: " . $this->emailAddress;
		$str .= "<br />forename: " . $this->forename;
		$str .= "<br />surname: " . $this->surname;
		$str .= "<br />addressLine1: " . $this->addressLine1;
		$str .= "<br />addressLine2: " . $this->addressLine2;
		$str .= "<br />town: " . $this->town;
		$str .= "<br />city: " . $this->city;
		$str .= "<br />postcode: " . $this->postcode;
		$str .= "<br />telephoneNumber: " . $this->telephoneNumber;
		return $str;
	}
}

/*$test = new Customer();
//test->emailAddress = "M@cs.com";
//$test->forename = "marc";
$test->populate("M@cs.com");
$test->forename = "M@cs.com";
$test->save();
print $test->toString();  

$test1 = new Customer();
$test1->emailAddress = "M@cs.com1";
$test1->forename = "marc";
//$test1->populate("M@cs.com");
//$test1->forename = "M@cs.com";
$test1->save();
print $test1->toString();*/  

?>