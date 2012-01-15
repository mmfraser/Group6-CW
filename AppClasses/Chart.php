<?php
	/*
		Group.php
		Created by: Marc 
		Description: Chart object
		Update log:
			15/01/12 (MF) - Creation.
			
	*/
	require_once('../App.php');

	class Chart {
		private $chartId;
		private $xmlConfig;
		public $chartName;
		public $chartType;
		public $sqlColumns = array();
		public $sqlTables = array();
		public $chartSeries = array();
		public $abscissa = array();
		public $axes = array();
				
		// Optional
		
		public $dashBool;
		public $dashRGB = array();
		public $bgRGB = array();
		
		// BG Gradient
		public $gradientBool;
		public $bgStartRGB = array(); // RGB array
		public $bgEndRGB = array(); // RGB array
		public $gradTransparency;
		public $gradDirection;
		
		public $titlePos = array(); // XY Coord. array
		public $titleFontSize;
		public $titleFont;
		public $titleAlign;
		public $titleRGB = array(); // RGB array
		public $imgSize = array(); // XY Coord. array
		public $legendPos = array(); // XY Coord. array
				
		private static function fillRGBArray($r, $g, $b) {
			$array = array();
			$array['R'] = $r;
			$array['G'] = $g;
			$array['B'] = $b;
			return $array;
		}
		
		private static function fillCoordArray($x, $y) {
			$array = array();
			$array['X'] = $x;
			$array['Y'] = $y;
			return $array;
		}
		
		public function addSQLColumn($colName, $colTable) {
			$this->sqlColumns[] = $colTable. '.' .$colName;
			$this->sqlTables[] = $colTable;
		}
		
		public function addChartSeries($seriesName, $dbCol, $description, $axisNo) {
			$series = array();
			$series['name'] = $seriesName;
			
			if(!in_array($dbCol, $this->sqlColumns))
				throw new Exception("Error adding chart series, no such DB column exists.");
				
			$series['dbCol'] = $dbCol;
			$series['description'] = $description;
			
		
			if(!array_key_exists($axisNo, $this->axes))
				throw new Exception("Error adding chart series, no such axes exists - you need to add it first.");
				
			$series['axisNo'] = $axisNo;
			$this->chartSeries[] = $series;
		}
		
		public function addChartAxis($axisName, $axisUnit, $axisPosition) {
			$axis = array();
			$axis['name'] = $axisName;
			$axis['unit'] = $axisUnit;
			$axis['position'] = $axisPosition;
			
			$this->axes[] = $axis;
		}
		
		public function setAbscissa($name, $dbCol) {
			if(!in_array($dbCol, $this->sqlColumns))
				throw new Exception("Error adding chart series, no such DB column exists.");
		
			$this->abscissa['name'] = $name;
			$this->abscissa['dbCol'] = $dbCol;
		
		}
		
		public function generateSQLQuery() {
			$sql = "SELECT ";
					
			$sql .= implode(", ", $this->sqlColumns);
			
			$sql .= " FROM ";
			
			$sql .= implode(", ", array_unique($this->sqlTables));

			return $sql;
		}
		
		function __construct($chartId = "", $chartName = "", $chartType = "") {
			$this->conn = App::getDB();
			$this->chartName = $name;
			$this->chartType = $chartType;
			$this->storeId = $storeId;
			$this->gradientBool = 1;
			$this->dashBool = 1;
			$this->dashRGB = self::fillRGBArray(190, 203, 107);
			$this->bgRGB = self::fillRGBArray(170, 183, 87);
			$this->bgStartRGB = self::fillRGBArray(219, 231, 139);
			$this->bgEndRGB = self::fillRGBArray(1, 138, 68);
			$this->gradTransparency = 50;
			$this->gradDirection = "DIRECTION_VERTICAL";
			$this->titlePos = self::fillCoordArray(350, 25);
			$this->titleFont = "Forgotte.ttf";
			$this->titleFontSize = 14;
			$this->titleAlign = "TEXT_ALIGN_MIDDLEMIDDLE";
			$this->titleRGB = self::fillRGBArray(0, 0, 0);
			$this->imgSize = self::fillCoordArray(700, 250);
			$this->legendPos = self::fillCoordArray(560, 16);
		}

		/*	This function gets the object with data given the chartId.
		*/
		public function populateId($chartId){
			$sql = "SELECT * FROM chart WHERE chartId = '".mysql_real_escape_string($chartId)."'";
			$row = $this->conn->getDataRow($sql);
			if($row == null)
				return false;
			$this->getRow($row);
		}
		
		/*	This function populates the object with data given a datarow.
		*/
		public function getRow($row){
			$this->chartId = $row['chartId'];
			$this->xmlConfig = $row['config'];
			
			$this->isLoaded = true;
		}

		/* 	This function allows the object to be saved back to the database, whether it is a new object or an object being updated.
		*/
		public function save() {
			try{
				if($this->name == null || $this->description == null) {
					throw new Exception('One or more required fields are not completed.');
				}
				

				if ($this->isLoaded === true) {
					$SQL = "UPDATE usergroup SET 
							name = '".mysql_real_escape_string($this->name)."' , 
							description = '".mysql_real_escape_string($this->description)."',
							storeId = ".$storeId."
							WHERE groupId = '".mysql_real_escape_string($this->groupId)."'";
					 $this->conn->execute($SQL);
				} else {
					$SQL = "INSERT INTO usergroup (name, description, storeId) VALUES (
							'".mysql_real_escape_string($this->name)."', 
							'".mysql_real_escape_string($this->description)."',
							".$storeId."
							)";
					$this->isLoaded = true;
					$this->groupId = $this->conn->execute($SQL);
				}
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
		*/
		public function toString() {
			
		}
	}
/*	$test = new Chart();
	
	$test->chartType = "Line";
	
	$test->chartName = "Sales over Time";
	
	$test->addSQLColumn("otherCol", "sales");
	$test->addSQLColumn("month", "sales");
	$test->addSQLColumn("noSales", "sales");
	
	$test->addChartAxis("otherCol", "CDs", "AXIS_POSITION_LEFT");
	
	$test->addChartSeries("Sales", "sales.otherCol", "Number Sales", 0);
	$test->addChartSeries("noSales", "sales.noSales", "Number Sales 1", 0);
	
	$test->setAbscissa("Month", "sales.month");
	
	print $test->generateSQLQuery();*/
	
//	print_r($test->sqlColumns);
//	print_r(array_unique($test->sqlTables));

?>