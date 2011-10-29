<?php
	/*
		DB.php
		Created by: Marc (23/10/11)
		Description: Used to allow connections to the Database.
		Update log:
			23/10/11 (MF) - Adding basic connect select and execute functionality. TODO: handle errors
	*/

	class DB {
		private $conn;
		private static $obj;
		public $DB_NAME = 'sales';
		public $DB_HOST = 'localhost';
		public $DB_PASS = '';
		public $DB_USER = 'root'; 
		
		/*public $DB_NAME = 'marcfras_sales';
		public $DB_HOST = 'localhost';
		public $DB_PASS = '';
		public $DB_USER = 'marcfras_sales';*/
		
		public function __construct() {
			$this->conn = mysql_connect($this->DB_HOST, $this->DB_USER, $this->DB_PASS) or die(mysql_error());
				mysql_select_db($this->DB_NAME, $this->conn) or die(mysql_error());
		}
		
		/* 	If there is no connection, this will start a new one.
			If there is already a connection, this will return it. 
		*/
		public static function getInstance() {
			if (self::$obj == null)
				self::$obj = new DB;

				
			return self::$obj;
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
					} else {
						throw new Exception('No DB Connection');
					}	
					
					return $toReturn;
				
			} catch (Exception $e) {
				
			}
		}
		
		/* Fetches a single result 
		*/
		public function getDataRow($sql){
			try{
				$result = mysql_query($sql) or die(mysql_error());
				return mysql_fetch_array($result);
			}catch (Exeception $e){
				
			}
		}
		
		public function execute($Sql){
			try{
				// We can't execute a SELECT in this function as it doesn't return anything.
				$pos = strpos($Sql,"SELECT");
				if($pos === false) {
					if ($this->conn != null) {
						$query = mysql_query($Sql);
						
						if(!$query) {
							throw new Exception(mysql_error());
						} else {
							return mysql_affected_rows();
						}
					} else {
						throw new Exception('No DB Connection');
					}
				} else {
					throw new Exception('Cannot do SELECTs using this function.');
				}
			} catch (Exception $e) {
				
			}
		}
		
		public function getNumResults($sql) {
			if(strpos($sql, "SELECT") === false) 
				throw new Exception('getNumResults() : Can only be used for SELECT statements.');
			$query = mysql_query($sql);
			return mysql_num_rows($query);
		}
	}
?>