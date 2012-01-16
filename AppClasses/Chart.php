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
		public $chartId;
		private $xmlConfig;
		public $chartName;
		public $chartType;
		public $sqlColumns = array();
		public $sqlTables = array();
		public $sqlOrder = array();
		public $sqlGroupBy = array();
		public $sqlAliases = array();
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
		
		public function addSQLColumn($colName, $colTable, $alias, $aggregation) {
			if(in_array($alias, $this->sqlAliases)) 
				throw new Exception("Alias " . $alias . " already exists, please choose another!");
			$this->sqlAliases[] = $alias;
			if($aggregation != null) {
				$this->sqlColumns[] = $aggregation . '(' . $colTable. '.' .$colName . ') as ' . $alias;
			} else {
				$this->sqlColumns[] = $colTable. '.' .$colName . ' as ' . $alias;
			}
			$this->sqlTables[] = $colTable;
		}
		
		public function addSQLGroupBy($colName, $colTable) {
			//$this->sqlGroupBy[] = $colTable. '.' .$colName;
			$this->sqlGroupBy[] = $colName;
		}
		
		public function addSQLOrder($colName, $order) {
			$this->sqlOrder[] = $colName . " " . $order;
		}
		
		public function addChartSeries($seriesName, $dbCol, $description, $axisNo) {
			$series = array();
			$series['name'] = $seriesName;
			
			if(!in_array($dbCol, $this->sqlAliases))
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
			if(!in_array($dbCol, $this->sqlAliases))
				throw new Exception("Error adding chart series, no such DB column exists.");
		
			$this->abscissa['name'] = $name;
			$this->abscissa['dbCol'] = $dbCol;
		
		}
		
		public function generateSQLQuery() {
			$sql = "SELECT ";
			$sql .= implode(", ", $this->sqlColumns);
			$sql .= " FROM ";
			$sql .= implode(", ", array_unique($this->sqlTables));
			
			if(count($this->sqlOrder) != 0) {
				$sql .= " ORDER BY ";
				$sql .= implode(", ", $this->sqlOrder);
			}
			
			if(count($this->sqlGroupBy) != 0) {
				$sql .= " GROUP BY ";
				$sql .= implode(", ", $this->sqlGroupBy);
			}
			
			return $sql;
		}
		
		function __construct($chartId = "", $chartName = "", $chartType = "") {
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
		public static function getChart($chartId){
			$sql = "SELECT * FROM chart WHERE chartId = '".mysql_real_escape_string($chartId)."'";
			$row = App::getDB()->getDataRow($sql);
			$chart = base64_decode($row['serialisedClass']);
			$chart = unserialize($chart);
			$chart->chartId = $chartId;
			$chart->isLoaded = true;
			
			return $chart;
		}
		
		/*	This function populates the object with data given a datarow.
		*/
		public function getRow($row){
			
		}

		/* 	This function allows the object to be saved back to the database, whether it is a new object or an object being updated.
		*/
		public function save() {
			try{
				if($this->chartName == null || $this->chartType == null) {
					throw new Exception('One or more required fields are not completed.');
				}
				

				if ($this->isLoaded === true) {
					$SQL = "UPDATE chart SET 
							chartName = '".mysql_real_escape_string($this->chartName)."' , 
							chartType = '".mysql_real_escape_string($this->chartType)."',
							serialisedClass = '".base64_encode(serialize($this))."'
							WHERE chartId = '".mysql_real_escape_string($this->chartId)."'";
					 App::getDB()->execute($SQL);
				} else {
					$SQL = "INSERT INTO chart (chartName, chartType, serialisedClass) VALUES (
							'".mysql_real_escape_string($this->chartName)."', 
							'".mysql_real_escape_string($this->chartType)."',
							'".base64_encode(serialize($this))."'
							)";
					$this->isLoaded = true;
					$this->groupId = App::getDB()->execute($SQL);
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
	$test = Chart::getChart(60);
	$test->chartName = "Sales per artist";
	$test->chartType = "Bar";
	$test->save();
	/*$test = new Chart();
	$test->chartType = "Line";
	
	$test->chartName = "Sales over Time";
	
	$test->addSQLColumn("saleId", "SALES_VIEW", "noSales", "COUNT");
	$test->addSQLColumn("name", "SALES_VIEW", "name", null);
	$test->addSQLGroupBy("name", "SALES_VIEW");
	
	$test->addChartAxis("noSales", "Sales", "AXIS_POSITION_LEFT");
	$test->addChartSeries("Sales", "noSales", "Number Sales", 0);

	
	$test->setAbscissa("Name", "name");
	
	$test->save(); 
	
	print $test->generateSQLQuery(); */
	
//	print_r($test->sqlColumns);
//	print_r(array_unique($test->sqlTables));

?>