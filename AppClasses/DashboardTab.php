<?php
	/*
		Genre.php
		Created by: Marc (31/01/12)
		Description: Genre object
		Update log:
			31/01/12 (MF) - Creation.
	*/
require_once('../App.php');

class DashboardTab {
	private $tabId;
  	public $tabName;
	public $tabDescription;
	public $userId;

	function __construct($tabId = "", $tabName = "", $tabDescription = "", $userId = "") {
		$this->conn = App::getDB();
		$this->tabId = $tabId;
		$this->tabName = $tabName;
		$this->tabDescription = $tabDescription;
		$this->userId = $userId;
	}

	/*	This function returns the tabId.
	*/
	public function getTabId() {
		return $this->tabId;
	}
	/*	This function gets the object with data given the tabId.
	*/
	public function populateId($tabId){
		$sql = "SELECT * FROM dashboardtab WHERE tabId = '".mysql_real_escape_string($tabId)."'";
		$this->tabId = $tabId;
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}
		
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->tabId = $tabId;
		$this->tabName = $row['tabName'];
		$this->tabDescription = $row['tabDescription'];
		$this->userId = $row['userId'];
		$this->isLoaded = true;
	}
	
	public function save() {	
		if($this->tabName == null || $this->userId == null) 
			throw new Exception('One or more required fields are not completed.');
		
		if ($this->isLoaded === true) {
			$SQL = "UPDATE dashboardtab SET 
					tabName = '".mysql_real_escape_string($this->tabName)."', 
					tabDescription = '".mysql_real_escape_string($this->tabDescription)."', 
					userId = '".mysql_real_escape_string($this->userId)."' 
					WHERE tabId = '".mysql_real_escape_string($this->tabId)."'";
			$this->conn->execute($SQL);
		} else {
		
			$SQL = "INSERT INTO dashboardtab (tabName, tabDescription, userId) VALUES (
					'".mysql_real_escape_string($this->tabName)."',
					'".mysql_real_escape_string($this->tabDescription)."',
					'".mysql_real_escape_string($this->userId)."')";
			$this->isLoaded = true;
			$this->tabId = $this->conn->execute($SQL);
		}		
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />tabId: " . $this->tabId;
		$str .= "<br />tabName: " . $this->tabName;
		$str .= "<br />tabDescription: " . $this->tabDescription;
		$str .= "<br />userId: " . $this->userId;
		return $str;
	}
}


/*$test = new DashboardTab();
$test->populateId(5);
$test->tabName = "Classical";
$test->tabDescription = "Classical";
$test->userId = 1;
$test->save();
print $test->toString();*/

?>
