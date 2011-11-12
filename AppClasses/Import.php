<?php
	/*
		ExcelImport.php
		Created by: Marc
		Description: Excel Import object, used to import an excel file into an array.
						Uses PHPExcel for the import of Excel spreadsheets and Excel XML.
		Update log:
			11/11/11 (MF) - Creation.
			12/11/11 (MF) - Adding DB import functionality.
	*/
		

	require_once('PHPExcel.php');
	require_once('../App.php');
	require_once('Customer.php');
	require_once('ImportLog.php');
	
	class ImportFileType {
		const xlsx = "Excel2007";
		const xls = "Excel5";
		const excelXML = "Excel2003XML";
	}
	
	class Import {
		protected $file;
		protected $fileType;
		protected $dbCols = array();
		protected $dbTable;
		protected $dataReader;
		protected $data = array();
		protected $ignoreFirstRow = false;
		protected $importName;
		protected $log;
		
		public function __construct() {
			$this->log = new ImportLog();
		}
		
		public function setImportName($name) {
			$this->importName = $name;
		}
		
		public function setFile($file) {
			$this->file = $file;
		}
		
		public function setFileType($type) {
			switch($type) {
				case "xlsx" : 
					$this->fileType = ImportFileType::xlsx; 
					$this->dataReader = new PHPExcel_Reader_Excel2007(); 
					break;
				case "xls" : 
					$this->fileType = ImportFileType::xls; 
					$this->dataReader = new PHPExcel_Reader_Excel5(); 
					break;
				case "excelXML" : 
					$this->fileType = ImportFileType::excelXML;
					$this->dataReader = new PHPExcel_Reader_Excel2003XML();
				break;
				default:
					throw new Exception("Invalid file type entered.");
			}
		}
		
		public function setDBTable($table) {
			$this->dbTable = $table;
		}
		
		public function setDBCols($cols) {
			$this->dbCols = $cols; 
		}
		
		public function setIgnoreFirstRow($bool) {
			$this->ignoreFirstRow = $bool;
		}
		
		protected function getDataArray() {
			if($this->fileType == null) 
				throw new Exception("No import file type specified.");
			if($this->file == null) 
				throw new Exception("No import file specified.");
			
			$this->dataReader->setReadDataOnly(true);
			$objPHPExcel = $this->dataReader->load($this->file);
			
			/*TODO: Handle multiple sheets */
			$objWorksheet = $objPHPExcel->getActiveSheet();
			
			$rowNo = 0;
			foreach($objWorksheet->getRowIterator() as $row) {
				$cellIterator = $row->getCellIterator();
				
				$col = 0;
				$rowData = array();
				foreach($cellIterator as $cell) {
					$dbCol = $this->dbCols[$col];
					
					if(strtolower($dbCol["Ignore"]) == "true" || $rowNo == 0) {
						break;
					} else {
						if($dbCol["DataType"] == "Date") {
							$rowData[$dbCol["ColName"]] = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($cell->getValue()));
						} else {
							$rowData[$dbCol["ColName"]] = $cell->getValue();
						}
					}
					$col++;
				}
				
				if($rowNo != 0)
					$this->data[] = $rowData;
				$rowNo++;
			}
			return $this->data;
		}
		
		public function import() {
			if($this->dbTable == null) 
				throw new Exception("No database table specified.");
				
			// Build up the static part of the SQL statement.
			$insertArray = array();
			$staticSql = "INSERT INTO " . $this->dbTable . " (";
			for($i = 0; $i < count($this->dbCols); $i++) {
				$dbCol = $this->dbCols[$i];
				
				// Don't include columns that are to be ignored.
				if(strtolower($dbCol["Ignore"]) != "true") {
					if($i == 0)
						$comma = "";
					else
						$comma = ", ";
					
					$staticSql .= $comma . $dbCol["ColName"];
					$insertArray[] = $dbCol["ColName"];
				}	
			}
			$staticSql .= ") VALUES (";
			
			// Build up the dynamic SQL
			$dataArray = $this->getDataArray();
			for($i = 0; $i < count($dataArray); $i++) {
				$sql = $staticSql;
					for($x = 0; $x < count($insertArray); $x++) {
						if($x == 0)
							$comma = "";
						else
							$comma = ", ";
							
						$sql .= $comma . "'" . mysql_real_escape_string($dataArray[$i][$insertArray[$x]]) . "'";
						
					}
				$sql .= ")";
					
				// Execute the sql and trap any errors.
				$db = App::getDB();
				try {
					$insertId = $db->execute($sql);
					$this->log->addEntry("Entry added successfully", $this->dbTable, $insertId);
				} catch(Exception $e) {
					
					$this->log->addEntry("Error adding entry: " . $e->getMessage(), $this->dbTable, $insertId);
				}		
			}
		}
	}
	/* This class extension is required as for a sales import we may need to add a record to the Customer table.
	*/
	class SalesImport extends Import {
		public function import() {
			if($this->dbTable == null) 
				throw new Exception("No database table specified.");
				
			// Build up the static part of the SQL statement.
			$insertArray = array();
			$staticSql = "INSERT INTO " . $this->dbTable . " (";
			for($i = 0; $i < count($this->dbCols); $i++) {
				$dbCol = $this->dbCols[$i];
				
				// Don't include columns that are to be ignored.
				if(strtolower($dbCol["Ignore"]) != "true") {
					if($i == 0)
						$comma = "";
					else
						$comma = ", ";
					
					$staticSql .= $comma . $dbCol["ColName"];
					$insertArray[] = $dbCol["ColName"];
				}	
			}
			$staticSql .= ") VALUES (";
			
			// Build up the dynamic SQL
			$dataArray = $this->getDataArray();
			for($i = 0; $i < count($dataArray); $i++) {
				// Insert into customer if required.
				$cust = new Customer();
				if($dataArray[$i]["customerEmail"] != null && $cust->populate($dataArray[$i]["customerEmail"]) === false) {
					// insert new customer
					$cust->emailAddress = $dataArray[$i]["customerEmail"];
					$cust->save();
				}
			
				// Join static and dynamic SQL for execution.
				$sql = $staticSql;
					for($x = 0; $x < count($insertArray); $x++) {
						if($x == 0)
							$comma = "";
						else
							$comma = ", ";
						$sql .= $comma . "'" . mysql_real_escape_string($dataArray[$i][$insertArray[$x]]) . "'";
					}
				$sql .= ")";
					
				// Execute the sql and log any errors.
				$db = App::getDB();
				try {
					$insertId = $db->execute($sql);
					$this->log->addEntry("Entry added successfully", $this->dbTable, $insertId);
				} catch(Exception $e) {
					
					$this->log->addEntry("Error adding entry: " . $e->getMessage(), $this->dbTable, $insertId);
				}		
			}
		}
	}
	
	
	
	$test = new SalesImport();
	$test->setFileType("xlsx");
	$test->setFile("TestImport.xlsx");
	$test->setDBCols(Array(Array("ColName" => "date", "DataType" => "Date", "Ignore" => "False"),Array("ColName" => "storeId", "DataType" => "String" , "Ignore" => "False"),Array("ColName" => "cashierName", "DataType" => "String" , "Ignore" => "False"),Array("ColName" => "itemId", "DataType" => "int" , "Ignore" => "False"),Array("ColName" => "itemDiscount", "DataType" => "float" , "Ignore" => "False"),Array("ColName" => "customerEmail", "DataType" => "string" , "Ignore" => "False")));
	$test->setDBTable("salesdata");
	$test->import();

?>