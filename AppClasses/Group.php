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
	 
		function __construct($groupId = "", $name = "", $description = "") {
			$this->conn = App::getDB();
			$this->name = $name;
			$this->description = $description;
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
					
				if ($this->isLoaded === true) {
					$SQL = "UPDATE usergroup SET 
							name = '".mysql_real_escape_string($this->name)."' , 
							description = '".mysql_real_escape_string($this->description)."'
							WHERE groupId = '".mysql_real_escape_string($this->groupId)."'";
					 $this->conn->execute($SQL);
				} else {
					$SQL = "INSERT INTO usergroup (name, description) VALUES (
							'".mysql_real_escape_string($this->name)."', 
							'".mysql_real_escape_string($this->description)."')";
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
			return $str;
		}
	}
?>