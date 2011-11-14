<?php
	/*
		Artist.php
		Created by: Marc (13/11/11)
		Description: Artist object
		Update log:
			13/11/11 (MF) - Creation.
	*/
require_once('../App.php');

class Artist {
	private $artistId;
  	public $forename;
  	public $surname;
	public $websiteUrl;
	public $dob;
	public $nationality;

	function __construct($artistId = "", $forename = "", $surname = "", $websiteUrl = "", $dob = "", $nationality = "") {
		$this->conn = App::getDB();
		$this->artistId = $artistId;
		$this->forename = $forename;
		$this->surname = $surname;
		$this->websiteUrl = $websiteUrl;
		$this->dob = $dob;
		$this->nationality = $nationality;
	}

	/*	This function gets the object with data given the artistId.
	*/
	public function populateId($artistId){
		$sql = "SELECT * FROM artist WHERE artistId = '".mysql_real_escape_string($storeId)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}
		
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
	$this->artistId = $artistId;
		$this->forename = $row['forename'];
		$this->surname = $row['surname'];
		$this->websiteUrl = $row['websiteUrl'];
		$this->dob = $row['dob'];
		$this->nationality = $row['nationality'];
		$this->isLoaded = true;
	}
	
	public function save() {	
		if($this->forename == null || $this->surname == null || $this->dob == null || $this->nationality == null) {
			throw new Exception('One or more required fields are not completed.');
		}

		if ($this->isLoaded === true) {
			$SQL = "UPDATE artist SET 
					forename = '".mysql_real_escape_string($this->forename)."' , 
					surname = '".mysql_real_escape_string($this->surname)."', 
					dob = '".mysql_real_escape_string($this->dob)."', 
					nationality = '".mysql_real_escape_string($this->nationality)."', 
					websiteUrl = '".mysql_real_escape_string($this->websiteUrl)."' 
					WHERE artistId = '".mysql_real_escape_string($this->artistId)."'";
			$this->conn->execute($SQL);
	
		} else {
		
			$SQL = "INSERT INTO artist (forename, surname, websiteUrl, dob, nationality) VALUES (
					'".mysql_real_escape_string($this->forename)."', 
					'".mysql_real_escape_string($this->surname)."', 
					'".mysql_real_escape_string($this->websiteUrl)."',
					'".mysql_real_escape_string($this->dob)."',					
					'".mysql_real_escape_string($this->nationality)."')";
			$this->isLoaded = true;
			$this->artistId = $this->conn->execute($SQL);
		}		
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />artistId: " . $this->artistId;
		$str .= "<br />forename: " . $this->forename;
		$str .= "<br />surname: " . $this->surname;
		$str .= "<br />dob: " . $this->dob;
		$str .= "<br />nationality: " . $this->nationality;
		$str .= "<br />websiteUrl: " . $this->websiteUrl;
		return $str;
	}
}
?>
