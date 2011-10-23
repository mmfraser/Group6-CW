<?php
	/*
		DB.php
		Created by: Marc Fraser (23/10/11)
		Description: Used to allow connections to the Database.
		Update log:
			23/10/11 (MF) - Adding basic connect select and execute functionality. TODO: handle errors
	*/

	class DB {
		private $conn;
		public $DB_NAME;
		public $DB_HOST;
		public $DB_PASS;
		public $DB_USER;
		
		function __construct($DB_NAME,$DB_HOST,$DB_PASS,$DB_USER) {
			//Setup database Connection
			$this->DB_NAME = $DB_NAME;
			$this->DB_HOST = $DB_HOST;
			$this->DB_PASS = $DB_PASS;
			$this->DB_USER = $DB_USER;
		}
		
		private function DAL() {
			try{
				//Setup Database connection.
				if ($this->conn == null) {
					$this->conn = mysql_connect($this->DB_HOST, $this->DB_USER, $this->DB_PASS) or die(mysql_error());
					mysql_select_db($this->DB_NAME, $this->conn) or die(mysql_error());
				}
				return $this->conn;
			} catch (Exception $e) {
				
			}	
		}
		/* 	If there is no connection, this will start a new one.
			If there is already a connection, this will return it. 
		*/
		public static function getInstance() {
			
			if ($this->conn == null){	
				return $this->DAL();	
			}else{
				return $this->conn;
			}
		}
		
		public function getArrayFromDB($Sql){
			try{
				// Remove all the old results
				unset($toReturn);
				unset($row);
				unset($results);
				
				// Fetch all the new results
				if ($this->conn != null) {
						$results = mysql_query($Sql) or die(mysql_error());
						$toReturn = array();
						while ($row = mysql_fetch_assoc($results)) {
							// This adds a result to the array to return
							array_push($toReturn,$row);
						}
					}
					return $toReturn;
				} else {
					throw new Exception('No DB Connection');
				}	
			} catch (Exception $e) {
				
			}
			
		}
		
		/* Fetches a single result 
		*/
		public function GetDataRow($Sql){
			try{
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				return $row[0];
			}catch (Exeception $e){
				
			}
		}
		
		public function Execute($Sql){
			try{
				// We can't execute a SELECT in this function as it doesn't return anything.
				$pos = strpos($Sql,"SELECT");
				if($pos === false) {
					if ($this->conn != null) {
						mysql_query($Sql) or throw new mysql_error();
					} else {
						throw new Exception('No DB Connection');
					}
				} else {
					throw new Exception('Cannot do SELECTs using this function.');
				}
			} catch (Exception $e) {
				
			}
		}
	}
?>