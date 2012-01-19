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
		public $dataView;
				
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
			// This function tries to give the alias passed to it, but if it cant, it'll give it a unique alias
			if(count($this->sqlColumns)==0) {
				if($aggregation != null) {
					$sql = $aggregation . '(' . $colTable. '.' .$colName . ') as ' . $alias;
				} else {
					$sql = $colTable. '.' .$colName . ' as ' . $alias;
				}
				
				$newCol = array();
				$newCol['query'] = $sql;
				$newCol['alias'] = $alias;
				$this->sqlColumns[] = $newCol;
				return $alias;
			}
	
			foreach($this->sqlColumns as $col) {
				if($aggregation != null) {
					$sql = $aggregation . '(' . $colTable. '.' .$colName . ') as ' . $alias;
					// If the column already exists with the same data, return the alias for that and don't create another. Else create a new column.
					if($col['query'] == $sql) {
						return $col['alias'];
					} 
				} else {
					$sql = $colTable. '.' .$colName . ' as ' . $alias;
					
					if($col['query'] == $sql) {
						return $col['alias'];
					} 
				}
			}
			
			while($this->checkColExists($alias)) {
				$alias .= "_1";
			}
			
			if($aggregation != null) {
				$newCol = array();
				$newCol['query'] = $sql;
				$newCol['alias'] = $alias;
				$this->sqlColumns[] = $newCol;
			} else {
				$newCol = array();
				$newCol['query'] = $sql;
				$newCol['alias'] = $alias;
				$this->sqlColumns[] = $newCol;
			}
			$this->sqlTables[] = $colTable;
			return $alias;
		}
		
		private function checkColExists($colName) {
			foreach($this->sqlColumns as $col) {
				if($col['alias'] == $colName) {
					return true;
				} 			
			} 
			return false;
		}
		
		public function addSQLGroupBy($colName, $colTable) {
			// There is no need for more than one group by currently.
			if(!$this->checkColExists($colName))
				throw new Exception("Error adding group by, no such DB column exists.");
			$this->sqlGroupBy[0] = $colName;
		}
		
		public function addSQLOrder($colName, $order) {
			$this->sqlOrder[] = $colName . " " . $order;
		}
		
		public function addChartSeries($seriesName, $dbCol, $description, $axisNo, $aggregation) {
			foreach($this->chartSeries as $series) {
				if($series['name'] == $seriesName) {
					if($series['dbCol'] == $dbCol && $series['description'] == $description)
						return;
					else
						throw new Exception("Cannot have duplicate series name.");
				} 
			}

			if(!$this->checkColExists($seriesName)) {
				throw new Exception("Error adding chart series, no such DB column exists.");
			}
				
			
		
			$series = array();
			$series['name'] = $seriesName;		
			$series['dbCol'] = $dbCol;
			$series['description'] = $description;
			
		
			if(!array_key_exists($axisNo, $this->axes))
				throw new Exception("Error adding chart series, no such axes exists - you need to add it first.");
				
			$series['axisNo'] = $axisNo;
			$series['aggregation'] = $aggregation;
			$this->chartSeries[] = $series;
		}
		
		/*public function addChartAxis($axisName, $axisUnit, $axisPosition) {
			if($axisName == null || $axisPosition == null) {
				throw new Exception("Axis Name or Axis Position cannot be null");
			}
			
			foreach($this->axes as $axis) {
				if($axis['name'] == $axisName && $axis['unit'] == $axisUnit) {
					return;
				} else if($axis['name'] == $axisName && $axis['unit'] != $axisUnit) {
					$axis['unit'] = $axisUnit;
				}
			}
		
			$axis = array();
			$axis['name'] = $axisName;
			$axis['unit'] = $axisUnit;
			$axis['position'] = $axisPosition;
			
			$this->axes[] = $axis;
		}*/
		
		public function setYAxis($axisName, $axisUnit, $axisPosition) {
			if($axisName == null || $axisPosition == null) {
				throw new Exception("Y-axis Name or Y-axis Position cannot be null");
			}
			$this->axes[0]['name'] = $axisName;
			$this->axes[0]['unit'] = $axisUnit;
			$this->axes[0]['position'] = $axisPosition;
		}
		
		public function setAbscissa($name, $dbCol, $dbColAlias) {
			if(!$this->checkColExists($dbColAlias))
				throw new Exception("Error adding chart series, no such DB column exists.");
		
			$this->abscissa['name'] = $name;
			$this->abscissa['dbCol'] = $dbCol;
			$this->abscissa['dbColAlias'] = $dbColAlias;
		}
		
		public function generateSQLQuery() {
			$sql = "SELECT ";
			$columnsArr = array_map(function($item) {return $item['query'];}, $this->sqlColumns);
			$sql .= implode(", ", $columnsArr);
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
		
		public function setImageSize($x, $y) {
			$this->imgSize = self::fillCoordArray($x, $y);
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
					$this->chartId = App::getDB()->execute($SQL);
				}
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		public function delete() {
			if($this->chartId != null) {
				$SQL = "DELETE FROM chart WHERE chartId = " . $this->chartId;
				$this->chartId = App::getDB()->execute($SQL);
			}
		}
		
		/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
		*/
		public function toString() {
			
		}
	}
	/*$test = new Chart();
	$test->setImageSize(380, 300);
	$test->chartName = "Sales per artistt";
	$test->chartType = "Line";
	$test->addSQLColumn("Col1", "TEST", "Col1", "");
	$test->addSQLColumn("Col1", "TEST", "Col1", "SUM");
	print_r($test->sqlColumns);
	$test->setAbscissa("Col1", "Col1", "Col1");*/
	/*$test = Chart::getChart(60);
	$test->chartName = "Sales per artistt";
	$test->chartType = "Line";
	//$test->setAbscissa("Name", "name", "name");
	$test->addSQLColumn("itemDiscount", "SALES_VIEW", "itemDiscount", null);
	$test->addChartSeries("Disocunt", "itemDiscount", "Item Discount", 0);
	print_r($test);
	
	$test->save();*/
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