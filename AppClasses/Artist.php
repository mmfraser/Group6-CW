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
	public $bandName;

	function __construct($artistId = "", $forename = "", $surname = "", $websiteUrl = "", $dob = "", $nationality = "", $bandName = "") {
		$this->conn = App::getDB();
		$this->artistId = $artistId;
		$this->forename = $forename;
		$this->surname = $surname;
		$this->websiteUrl = $websiteUrl;
		$this->dob = $dob;
		$this->nationality = $nationality;
		$this->bandName = $bandName;
	}

	public function getArtistId() {
		return $this->artistId;
	}
	/*	This function gets the object with data given the artistId.
	*/
	public function populateId($artistId){
		$sql = "SELECT * FROM artist WHERE artistId = '".mysql_real_escape_string($artistId)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}
		
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->artistId = $row['artistId'];
		$this->forename = $row['forename'];
		$this->surname = $row['surname'];
		$this->websiteUrl = $row['websiteUrl'];
		$this->dob = $row['dob'];
		$this->nationality = $row['nationality'];
		$this->bandName = $row['bandName'];
		$this->isLoaded = true;
	}
	
	public function save() {	
		if($this->forename == null || $this->surname == null || $this->dob == null || $this->nationality == null) {
			throw new Exception('One or more required fields are not completed.');
		}
		print $this->date;
		
		if(strpos($this->date, "/") === true)
			throw new Exception('Invalid date format.  Expecting YYYY-MM-DD.');
			
			
		if($this->websiteUrl == null)
			$this->websiteUrl == null;
		else if(strpos($this->websiteUrl, "http://") !== true) 
			$this->websiteUrl = "http://" . $this->websiteUrl;
	
		if ($this->isLoaded === true) {
			$SQL = "UPDATE artist SET 
					forename = '".mysql_real_escape_string($this->forename)."' , 
					surname = '".mysql_real_escape_string($this->surname)."', 
					dob = '".mysql_real_escape_string($this->dob)."', 
					nationality = '".mysql_real_escape_string($this->nationality)."', 
					bandName = '".mysql_real_escape_string($this->bandName)."', 
					websiteUrl = '".mysql_real_escape_string($this->websiteUrl)."' 
					WHERE artistId = '".mysql_real_escape_string($this->artistId)."'";
			$this->conn->execute($SQL);
	
		} else {
		
			$SQL = "INSERT INTO artist (forename, surname, websiteUrl, dob, bandName, nationality) VALUES (
					'".mysql_real_escape_string($this->forename)."', 
					'".mysql_real_escape_string($this->surname)."', 
					'".mysql_real_escape_string($this->websiteUrl)."',
					'".mysql_real_escape_string($this->dob)."',			
					'".mysql_real_escape_string($this->bandName)."',					
					'".mysql_real_escape_string($this->nationality)."')";
			$this->isLoaded = true;
			$this->artistId = $this->conn->execute($SQL);
		}		
	}
	
	public function delete($boolean) {
		// Boolean is a secondary "confirmation" check and we can't delete an object from the DB if we don't have it in the DB in the first place!
		if($this->isLoaded && $boolean) 
			$q = $this->conn->execute("DELETE FROM artist WHERE artistId = ". $this->artistId);
			if($q == 1) return true; else return false;
			
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />artistId: " . $this->artistId;
		$str .= "<br />bandName: " . $this->bandName;
		$str .= "<br />forename: " . $this->forename;
		$str .= "<br />surname: " . $this->surname;
		$str .= "<br />dob: " . $this->dob;
		$str .= "<br />nationality: " . $this->nationality;
		$str .= "<br />websiteUrl: " . $this->websiteUrl;
		return $str;
	}
}
?>
