<?php
	/*
		Sale.php
		Created by: Marc (13/11/11)
		Description: Sale object
		Update log:
			15/11/11 (MF) - Creation.
	*/
require_once('../App.php');
require_once('Customer.php');
require_once('store.php');

class Sale {
	private $saleId;
  	public $date;
  	public $storeId;
	public $cashierName;
	public $itemId;
	public $itemDiscount;
	public $customerEmail;

	function __construct($saleId = "", $date = "", $storeId = "", $cashierName = "", $itemId = "", $itemDiscount = "", $customerEmail = "") {
		$this->conn = App::getDB();
		$this->saleId = $saleId;
		$this->date = $date;
		$this->storeId = $storeId;
		$this->cashierName = $cashierName;
		$this->itemId = $itemId;
		$this->itemDiscount = $itemDiscount;
		$this->customerEmail = $customerEmail;
	}
	
	public function getSaleId() {
		return $this->saleId;
	}

	/*	This function gets the object with data given the artistId.
	*/
	public function populateId($saleId){
		$sql = "SELECT * FROM salesdata WHERE saleId = '".mysql_real_escape_string($saleId)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}
		
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->saleId = $row['saleId'];
		$this->date = $row['date'];
		$this->storeId = $row['storeId'];
		$this->cashierName = $row['cashierName'];
		$this->itemId = $row['itemId'];
		$this->itemDiscount = $row['itemDiscount'];
		$this->customerEmail = $row['customerEmail'];
		$this->isLoaded = true;
	}
	
	public function save() {	
		if($this->date == null || $this->storeId == null || $this->cashierName == null || $this->itemId == null) {
			throw new Exception('One or more required fields are not completed.');
		}
		
		try {
			if(strpos($this->date, '/') !== false) {
				$dateArr = date_parse_from_format("d/m/Y", $this->date);
				$dateFormat = $dateArr["year"] . "-" . $dateArr["month"] . "-" . $dateArr["day"];
			} else if(strpos($this->date, '-') !== false) {
				$dateFormat = $this->date;
				
			} else 
				throw new Exception("Invalid date format");
		} catch(Exception $e) {
			throw $e;
		}
		
		$store = new Store();
		if($store->populateId($this->storeId) === false) {
			throw new Exception('Store does not exist in database');
		}
		
		
		if ($this->isLoaded === true) {
			$SQL = "UPDATE salesdata SET 
					date = '".$dateFormat."' , 
					storeId = ".mysql_real_escape_string($this->storeId).", 
					cashierName = '".mysql_real_escape_string($this->cashierName)."', 
					itemId = ".mysql_real_escape_string($this->itemId).", 
					customerEmail = '".mysql_real_escape_string($this->customerEmail)."', 
					itemDiscount = '".mysql_real_escape_string($this->itemDiscount)."' 
					WHERE saleId = ".mysql_real_escape_string($this->saleId)."";
			$this->conn->execute($SQL);
	
		} else {
			$SQL = "INSERT INTO salesdata (date, storeId, cashierName, itemId, itemDiscount, customerEmail) VALUES (
					'".$dateFormat."', 
					".mysql_real_escape_string($this->storeId).", 
					'".mysql_real_escape_string($this->cashierName)."',
					".mysql_real_escape_string($this->itemId).",	
					'".mysql_real_escape_string($this->itemDiscount)."',					
					'".mysql_real_escape_string($this->customerEmail)."')";
			try {
			
				$cust = new Customer();
				if($this->customerEmail != null && $cust->populate($this->customerEmail) === false) {
					// insert new customer
					$cust->emailAddress = $this->customerEmail;
					$cust->save();
				}
			
				$this->saleId = $this->conn->execute($SQL);
				$this->isLoaded = true;
			} catch(Exception $e) {
				throw $e;
			}
		}		
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />saleId: " . $this->saleId;
		$str .= "<br />date: " . $this->date;
		$str .= "<br />storeId: " . $this->storeId;
		$str .= "<br />cashierName: " . $this->cashierName;
		$str .= "<br />itemId: " . $this->itemId;
		$str .= "<br />itemDiscount: " . $this->itemDiscount;
		$str .= "<br />customerEmail: " . $this->customerEmail;
		return $str;
	}
}

/*$test = new Sale();
$test->storeId = 5;
$test->date = "15/11/2011";
$test->cashierName = "Marc";
$test->itemId = 1;
$test->itemDiscount = 10;
$test->customerEmail = "marc@cs1.com";

$test->save();
print $test->toString();*/
?>
