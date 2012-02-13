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
	public $ranBy;

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
		$this->ranBy = $row['ranBy'];
		$this->isLoaded = true;
	}
	
	public function getLogId() {
		return $this->logId;
	}
	
	/*	This function returns the number of successful data imports.
	*/
	public function getNumSuccesses() {
		$xml = simplexml_load_string("<log>".$this->log."</log>");
		$xpath = $xml->xpath("//entry[insertId != -1]");
		return count($xpath);
	}
	
	/*	This function returns the number of unsuccessful data imports.
	*/
	public function getNumUnsuccessful() {
		$xml = simplexml_load_string("<log>".$this->log."</log>");
		$xpath = $xml->xpath("//entry[insertId = -1]");
		return count($xpath);
	}
	
	/*	This function returns the number of	data rows.
	*/
	public function getNumRows() {
		$xml = simplexml_load_string("<log>".$this->log."</log>");
		$xpath = $xml->xpath("//entry");
		return count($xpath);
	}
	
	/*	This function returns all log entries in the format of an HTML table.
	*/
	public function entriesToTable() {
		$xml = simplexml_load_string("<log>".$this->log."</log>");
		$entries = $xml->entry;
		$html = "<table class=\"logEntries display\">";
		$html .= '<thead>
					<tr>
						<th>Date</th>
						<th>Import Row (in Spreadsheet)</th>
						<th>Database Table</th>
						<th>Message</th>
						<th>Inserted ID (-1 = error)</th>
					</tr>
				</thead>
				<tbody>';
		foreach($entries as $entry) {
			$html .= '<tr>';
			$html .= '	<td>'.$entry->logDate.'</td>' . PHP_EOL;
			$html .= '	<td>'.$entry->importRow.'</td>'. PHP_EOL;
			$html .= '	<td>'.$entry->dbTable.'</td>'. PHP_EOL;
			$html .= '	<td>'.$entry->message.'</td>'. PHP_EOL;
			$html .= '	<td>'.$entry->insertId.'</td>'. PHP_EOL;
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';
		return $html;
	}
	
	/* This function adds an entry to the log.
	*/
	public function addEntry($message, $dbTable, $insertId, $importRow) {
		$this->log .= "<entry><logDate>".date("d/m/Y")."</logDate><dbTable>".$dbTable."</dbTable><message>" . $message . "</message><insertId>".$insertId."</insertId><importRow>".$importRow."</importRow></entry>";
		$this->save();
	}
	
	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being uplogDated.
	*/
	public function save() {	
		if ($this->isLoaded === true) {	
			$SQL = "UPDATE importlog SET 
					logDate = NOW(), 
					log = '".mysql_real_escape_string($this->log)."', 
					inputtedIds = '".mysql_real_escape_string($this->inputtedIds)."', 
					ranBy = '".mysql_real_escape_string($this->ranBy)."', 
					importName = '".mysql_real_escape_string($this->importName)."' 
					WHERE logId = ".mysql_real_escape_string($this->logId);
			$this->conn->execute($SQL);
		} else {		
		print $this->ranBy;
			$SQL = "INSERT INTO importlog (logDate,log, inputtedIds, importName, ranBy) VALUES (
					NOW(), 
					'".mysql_real_escape_string($this->log)."', 
					'".mysql_real_escape_string($this->inputtedIds)."',
					'".mysql_real_escape_string($this->importName)."',
					'".mysql_real_escape_string($this->ranBy)."')";
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
		$str .= "<br />ranBy: " . $this->ranBy;
		return $str;
	}
}
?>