<?php
	/*
		ImportCollection.php
		Created by: Marc (13/11/11)
		Description: ImportCollection object
		Update log:
			21/11/11 (MF) - Creation.
	*/
require_once('../App.php');
require_once('ImportLog.php');

class ImportLogCollection {
	private $imports = array();

	function __construct($imports = "") {
		$this->conn = App::getDB();
	}

	/*	This function gets the object with data given the import name.
	*/
	public function populateImportName($impName){
		$sql = "SELECT * FROM importLog WHERE importName = '".mysql_real_escape_string($impName)."'";
		$rows = $this->conn->getArrayFromDB($sql);
		
		foreach($rows as $row) {
			$impLog = new ImportLog();
			$impLog->populate($row['logId']);
			$this->imports[] = $impLog;
		}
	}
	
	/*	This function returns the array of imports.
	*/	
	public function getImports() {
		return $this->imports;
	}
	
	/* 	This function will output the imports in the form of an HTML table ready for use by jQuery datatable.
	*/
	public function getHtmlTable() {
		$html = "<table class=\"logEntries display\">";
		$html .= '<thead>
					<tr>
						<th>Date</th>
						<th>Total Entries</th>
						<th>Entries Succeeded</th>
						<th>Entries Failed</th>
						<th></th>
					</tr>
				</thead>
				<tbody>'. PHP_EOL;
		foreach($this->imports as $entry) {
			$successes = $entry->getNumSuccesses();
			$unsuccessful = $entry->getNumUnsuccessful();
			$totalEntries = $successes + $unsuccessful;
			$html .= '<tr>';
			$html .= '	<td>'.$entry->logDate.'</td>'. PHP_EOL;
			$html .= '	<td>'.$totalEntries.'</td>'. PHP_EOL;
			$html .= '	<td>'.$successes.'</td>'. PHP_EOL;
			$html .= '	<td>'.$unsuccessful.'</td>'. PHP_EOL;
			$html .= '	<td><a href="ViewImportLog.php?logId='.$entry->getLogId().'">View Log</a></td>'. PHP_EOL;
			$html .= '</tr>'. PHP_EOL;
		}
		$html .= '</tbody></table>';
		return $html;
	}	

		
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->artistId = $row['artistId'];
		$this->forename = $row['forename'];
		$this->surname = $row['surname'];
		$this->websiteUrl = $row['websiteUrl'];
		$this->dob = $row['dob'];
		$this->nationality = $row['nationality'];
		$this->bandName = $row['bandName'];
		$this->isLoaded = true;
	}
}
?>
