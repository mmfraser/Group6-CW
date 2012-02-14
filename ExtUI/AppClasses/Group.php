<?php
	/*
		Group.php
		Created by: Marc 
		Description: Group object
		Update log:
			30/10/11 (MF) - Creation.
			
	*/
	require_once('../App.php');

	class Group {
		private $groupId;
		public $name;
		public $description;
		public $storeId;
	 
		function __construct($groupId = "", $name = "", $description = "", $storeId = "") {
			$this->conn = App::getDB();
			$this->name = $name;
			$this->description = $description;
			$this->storeId = $storeId;
		}

		/*	This function gets the object with data given the groupId.
		*/
		public function populateId($groupId){
			$sql = "SELECT * FROM usergroup WHERE groupId = '".mysql_real_escape_string($groupId)."'";
			$row = $this->conn->getDataRow($sql);
			if($row == null)
				return false;
			$this->getRow($row);
		}
		
		/*	This function populates the object with data given a datarow.
		*/
		public function getRow($row){
			$this->groupId = $row['groupId'];
			$this->name = $row['name'];
			$this->description = $row['description'];
			$this->storeId = $row['storeId'];
			
			$this->isLoaded = true;
		}
		
		/*	This function checks to see if the group being created/modified will have a valid name.
		*/
		private function validName() {
			if($this->conn->getNumResults("SELECT name FROM usergroup WHERE name = '".mysql_real_escape_string($this->name)."' AND groupId != '".$this->groupId."'") == 0)
				return true;
			return false;
		}

		/* 	This function allows the object to be saved back to the database, whether it is a new object or an object being updated.
		*/
		public function save() {
			try{
				if($this->name == null || $this->description == null) {
					throw new Exception('One or more required fields are not completed.');
				}
				if(!$this->validName())
					throw new Exception('Invalid group name.  Group already exists with this name.');
				
					if($this->storeId != null || $this->storeId != 0) 
						$storeId = mysql_real_escape_string($this->storeId);
					else 
						$storeId = "NULL";

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
			$str = "<br />";
			$str .= "<br />groupId: " . $this->groupId;
			$str .= "<br />name: " . $this->name;
			$str .= "<br />description: " . $this->description;
			$str .= "<br />storeId: " . $this->storeId;
			return $str;
		}
	}
	
?>