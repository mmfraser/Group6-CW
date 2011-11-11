<?php
	/*
		ExcelImport.php
		Created by: Marc
		Description: Excel Import object, used to import an excel file into an array.
						Uses PHPExcel for the import of Excel spreadsheets and Excel XML.
		Update log:
			11/11/11 (MF) - Creation.
	*/

	include('PHPExcel.php');
	
	class ImportFileType {
		const xlsx = "Excel2007";
		const xls = "Excel5";
		const excelXML = "Excel2003XML";
	}
	
	class Import {
		private $file;
		private $fileType;
		private $dbCols = array();
		private $dbTable;
		private $dataReader;
		private $data = array();
		private $ignoreFirstRow = false;
		
		public function __construct() {
	
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
		
		private function getDataArray() {
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
							$rowData[$dbCol["ColName"]] = date("d/m/Y", PHPExcel_Shared_Date::ExcelToPHP($cell->getValue()));
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
				
				foreach($this->getDataArray() as $row) {
					//Insert the data into the Database.
					print_r($row);
					print "<br>";
				}
				
		}
	}
	
	$test = new Import();
	$test->setFileType("xlsx");
	$test->setFile("TestImport.xlsx");
	$test->setDBCols(Array(Array("ColName" => "Date", "DataType" => "Date", "Ignore" => "False"),Array("ColName" => "Store", "DataType" => "String" , "Ignore" => "False"),Array("ColName" => "Cashier", "DataType" => "String" , "Ignore" => "False"),Array("ColName" => "ItemID", "DataType" => "int" , "Ignore" => "False"),Array("ColName" => "ItemDiscount", "DataType" => "float" , "Ignore" => "False"),Array("ColName" => "CustomerEmail", "DataType" => "string" , "Ignore" => "False")));
	$test->setDBTable("test");
	$test->import();
	
/*	$objReader = new PHPExcel_Reader_Excel2007();
//$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load("TestImport.xlsx");
$objWorksheet = $objPHPExcel->getActiveSheet();
$rowCount = 0;
$colNames = array();
$data = array();
foreach($objWorksheet->getRowIterator() as $row) {
	$cellIterator = $row->getCellIterator();
	
	$col = 0;
	$row = array();
	foreach($cellIterator as $cell) {
		if($rowCount == 0) 
			$colNames[] = $cell->getValue();
			
			if($colNames[$col] == "Date") 
				$row[$colNames[$col]] = date("l jS \of F Y h:i:s A", PHPExcel_Shared_Date::ExcelToPHP($cell->getValue())); 
			else
				$row[$colNames[$col]] = $cell->getValue();
		$col++;
	}
	$data[] = $row;
	$rowCount++;
}

print_r($data);
*/
?>