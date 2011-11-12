<?php
	/*
		ImportLog.php
		Created by: Marc 
		Description: ImportLog object
		UplogDate log:
			12/11/11 (MF) - Creation.
	*/
require_once('../App.php');

class ImportLog {
	private $logId;
  	public $logDate;
  	public $dbTable;
  	public $log;
	public $inputtedIds;
	public $importName;
  	public $isLoaded;

	function __construct($logId = "", $logDate = "", $dbTable = "", $log = "", $inputtedIds = "", $importName = "") {
		$this->conn = App::getDB();
		$this->logId = $logId;
		$this->logDate = $logDate;
		$this->dbTable = $dbTable;
		$this->log = $log;
		$this->inputtedIds = $inputtedIds;
		$this->importName = $importName;
	}

	/*	This function gets the object with data given the customer's email address.
	*/
	public function populate($logId){
		$sql = "SELECT * FROM importlog WHERE logId = '".mysql_real_escape_string($logId)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}
			
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->logId = $row['logId'];;
		$this->logDate = $row['logDate'];
		$this->dbTable = $row['dbTable'];
		$this->log = $row['log'];
		$this->inputtedIds = $row['inputtedIds'];
		$this->importName = $row['importName'];
		$this->isLoaded = true;
	}
	
	/* This function adds an entry to the log.
	*/
	public function addEntry($message, $dbTable, $insertId) {
		$this->log .= "<entry><logDate>".date("d/m/Y")."</logDate><dbTable>".$dbTable."</dbTable><message>" . $message . "</message><insertId>".$insertId."</insertId></entry>";
		$this->save();
	}
	
	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being uplogDated.
	*/
	public function save() {	
	
		if ($this->isLoaded === true) {	
			$SQL = "UPDATE importlog SET 
					logDate = NOW(), 
					dbTable = '".mysql_real_escape_string($this->dbTable)."',
					log = '".mysql_real_escape_string($this->log)."', 
					inputtedIds = '".mysql_real_escape_string($this->inputtedIds)."', 
					importName = '".mysql_real_escape_string($this->importName)."' 
					WHERE logId = ".mysql_real_escape_string($this->logId);
			$this->conn->execute($SQL);
		} else {		
			$SQL = "INSERT INTO importlog (logDate, dbTable, log, inputtedIds, importName) VALUES (
					NOW(), 
					'".mysql_real_escape_string($this->dbTable)."', 
					'".mysql_real_escape_string($this->log)."', 
					'".mysql_real_escape_string($this->inputtedIds)."', 
					'".mysql_real_escape_string($this->importName)."')";
			$this->isLoaded = true;
			$this->logId = $this->conn->execute($SQL);
		}		
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />logID: " . $this->logID;
		$str .= "<br />logDate: " . $this->logDate;
		$str .= "<br />dbTable: " . $this->dbTable;
		$str .= "<br />log: " . $this->log;
		$str .= "<br />inputtedIds: " . $this->inputtedIds;
		$str .= "<br />importName: " . $this->importName;
		$str .= "<br />town: " . $this->town;
		$str .= "<br />city: " . $this->city;
		$str .= "<br />postcode: " . $this->postcode;
		$str .= "<br />telephoneNumber: " . $this->telephoneNumber;
		return $str;
	}
}
?>