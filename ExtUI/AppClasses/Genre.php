<?php
	/*
		Genre.php
		Created by: Marc (13/11/11)
		Description: Genre object
		Update log:
			13/11/11 (MF) - Creation.
	*/
require_once('../App.php');
require_once('Artist.php');

class Genre {
	private $genreId;
  	public $genreName;

	function __construct($genreId = "", $genreName = "") {
		$this->conn = App::getDB();
		$this->genreId = $productId;
		$this->genreName = $artistId;
	}

	/*	This function returns the productId.
	*/
	public function getGenreId() {
		return $this->genreId;
	}
	/*	This function gets the object with data given the artistId.
	*/
	public function populateId($genreId){
		$sql = "SELECT * FROM genre WHERE genreId = '".mysql_real_escape_string($genreId)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}
		
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
	$this->artistId = $artistId;
		$this->genreId = $row['genreId'];
		$this->genreName = $row['genreName'];
		$this->isLoaded = true;
	}
	
	public function save() {	
		if($this->genreName == null) 
			throw new Exception('One or more required fields are not completed.');
		
		if ($this->isLoaded === true) {
			$SQL = "UPDATE genre SET 
					genreName = '".mysql_real_escape_string($this->genreName)."' 
					WHERE genreId = '".mysql_real_escape_string($this->genreId)."'";
			$this->conn->execute($SQL);
		} else {
		
			$SQL = "INSERT INTO genre (genreName) VALUES (
					'".mysql_real_escape_string($this->genreName)."')";
			$this->isLoaded = true;
			$this->genreId = $this->conn->execute($SQL);
		}		
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />genreId: " . $this->genreId;
		$str .= "<br />genreName: " . $this->genreName;
		return $str;
	}
}

/*
$test = new Genre();
$test->genreName = "Classical";
$test->save();
print $test->toString();*/

?>
