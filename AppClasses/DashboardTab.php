<?php
	/*
		DashboardTab.php
		Created by: Marc (31/01/12)
		Description: Dashboard Tab object
		Update log:
			31/01/12 (MF) - Creation.
	*/
require_once('../App.php');

class DashboardTab {
	private $tabId;
  	public $tabName;
	public $tabDescription;
	public $userId;
	private $chartPosArr;
	private $customFilters;

	function __construct($tabId = "", $tabName = "", $tabDescription = "", $userId = "") {
		$this->conn = App::getDB();
		$this->tabId = $tabId;
		$this->tabName = $tabName;
		$this->tabDescription = $tabDescription;
		$this->userId = $userId;
		$this->chartPosArr = array();
	}

	/*	This function returns the tabId.
	*/
	public function getTabId() {
		return $this->tabId;
	}
	/*	This function gets the object with data given the tabId.
	*/
	public function populateId($id){
		$sql = "SELECT * FROM dashboardtab WHERE tabId = '".mysql_real_escape_string($id)."'";
		$this->tabId = $id;
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
		
		// Also get the chart layout for this tab.
		$chartPosSQL = "SELECT chartPos, chartId, customFilter FROM dashboardLayout WHERE tabId = '".mysql_real_escape_string($id)."'";
		$chartPos = $this->conn->getArrayFromDB($chartPosSQL);
		$this->populateChartPos($chartPos);
		
	}
	
	/*	This function populates the chart position array with the layout in the database.
	*/
	private function populateChartPos($posArr) {
		foreach($posArr as $pos) {
			$this->chartPosArr[$pos['chartPos']]['chartId'] = $pos['chartId'];
			$this->chartPosArr[$pos['chartPos']]['customFilter'] = unserialize($pos['customFilter']);
		}
	}
	
	/*	This function sets a chart at a certain position on the grid.
	*/
	public function changeLayout($chartId, $chartPos) {
		if($this->chartPosArr[$chartPos] == null) {
			$SQL = "INSERT INTO dashboardLayout (chartPos, tabId, chartId) VALUES ('".mysql_real_escape_string($chartPos)."', '".mysql_real_escape_string($this->tabId)."', '".mysql_real_escape_string($chartId)."')";
		} else {
			if($chartId == -1) {
				$SQL = "DELETE FROM dashboardLayout WHERE chartPos = '".mysql_real_escape_string($chartPos)."' AND tabId = '".mysql_real_escape_string($this->tabId)."'";
			} else 
				$SQL = "UPDATE dashboardLayout SET chartId = '".mysql_real_escape_string($chartId)."' WHERE chartPos = '".mysql_real_escape_string($chartPos)."' AND tabId = '".mysql_real_escape_string($this->tabId)."'";
		}

		$this->conn->execute($SQL);
		$this->chartPosArr[$chartPos] = $chartId;
	}
	
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->tabId = $row['tabId'];
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
	
	
	public function getTabLayoutHtml() {
		$chartPos = 1;
			$dashboardHtml = '<table id="dashboardTable">';
			for($i = 1; $i <= App::$noRows; $i++) {
				$dashboardHtml .= "<tr>" . PHP_EOL;
	
				for($j = 1; $j <= App::$noCols; $j++) {
					// Get chart
					$chartId = -1;
					$customFilter = null;
					$colWidth = 100/App::$noCols;
					
					foreach($this->chartPosArr as $pos => $chart) {
						if($pos == $chartPos) {
							$chartId = $chart['chartId'];
							$customFilter = $chart['customFilter'];
						}
					}
					
					$dashboardHtml .= '		<td width="'.$colWidth.'%" id="'.$chartPos.'">'. PHP_EOL;
					$dashboardHtml .= '		<a title="Modify Chart" class="changeChart">Change Chart</a>';
					
					if($chartId == -1)
						$dashboardHtml .= '			No chart selected';
					else {
						// Check if custom filter is set and if so include it in the url string.
					
						$url = "../AppClasses/drawChart.php?chartId=".$chartId;
						
						// Build url if filters are set.
						if($customFilter != null) {
							foreach($customFilter as $key => $value) {
								$url .= "&amp;" . $key . "=" . $value;
							}
						}
			
						$dashboardHtml .= '		<a title="Change Filter" class="changeFilter">Change Filter</a>';
						$dashboardHtml .= '			<img src="'.$url.'" class="chart" alt="'.$chartId.'" />';
					}
					

					$dashboardHtml .= "		</td>". PHP_EOL;
					$chartPos++;
				}
				
				$dashboardHtml .= "</tr>" . PHP_EOL;
			}
			
			$dashboardHtml .= "</table>";
			return $dashboardHtml;
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
//$test->tabName = "Classical";
//$test->tabDescription = "Classical";
//$test->userId = 1;
//$test->save();
print $test->toString();
*/
?>
