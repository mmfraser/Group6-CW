<?php
	/*
		Product.php
		Created by: Marc (13/11/11)
		Description: Product object
		Update log:
			13/11/11 (MF) - Creation.
	*/
require_once('../App.php');
require_once('Artist.php');
require_once('Genre.php');

class Product {
	private $productId;
  	public $artistId;
  	public $genreId;
	public $name;
	public $releaseDate;
	public $price;
	public $artist;
	public $genre;

	function __construct($productId = "", $artistId = "", $genreId = "", $name = "", $releaseDate = "", $price = "") {
		$this->conn = App::getDB();
		$this->productId = $productId;
		$this->artistId = $artistId;
		$this->genreId = $genreId;
		$this->name = $name;
		$this->releaseDate = $releaseDate;
		$this->price = $price;
	}

	/*	This function returns the productId.
	*/
	public function getProductId() {
		return $this->productId;
	}
	/*	This function gets the object with data given the artistId.
	*/
	public function populateId($productId){
		$sql = "SELECT * FROM product WHERE productId = '".mysql_real_escape_string($productId)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}
		
	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
	$this->artistId = $artistId;
		$this->productId = $row['productId'];
		$this->artistId = $row['artistId'];
		$this->genreId = $row['genreId'];
		$this->name = $row['name'];
		$this->releaseDate = $row['releaseDate'];
		$this->price = $row['price'];
		
		$artist = new Artist();
		$artist->populateId($this->artistId);
		$this->artist = $artist;
		
		$genre = new Genre();
		$genre->populateId($this->genreId);
		$this->genre = $genre;
		
		$this->isLoaded = true;
	}
	
	public function save() {	
		if($this->artistId == null || $this->genreId == null || $this->name == null || $this->releaseDate == null || $this->price == null) 
			throw new Exception('One or more required fields are not completed.');

		try {
			if(strpos($this->releaseDate, '/') !== false) {
				$dateArr = date_parse_from_format("d/m/Y", $this->releaseDate);
				$dateFormat = $dateArr["year"] . "-" . $dateArr["month"] . "-" . $dateArr["day"];
			} else if(strpos($this->releaseDate, '-') !== false) {
				$dateFormat = $this->releaseDate;
			} else 
				throw new Exception("Invalid date format.  Expected dd/mm/yyyy or yyyy-mm-dd.");
		} catch(Exception $e) {
			throw $e;
		}

		$artist = new Artist();
		if($artist->populateId($this->artistId) === false) 
			throw new Exception("Artist must exist before entering an item attached to it in the database");
		
		$genre = new Genre();
		if($genre->populateId($this->genreId) === false) 
			throw new Exception("Genre must exist before entering an item attached to it in the database");
		
		if ($this->isLoaded === true) {
			$SQL = "UPDATE product SET 
					productId = '".mysql_real_escape_string($this->productId)."' , 
					genreId = '".mysql_real_escape_string($this->genreId)."', 
					name = '".mysql_real_escape_string($this->name)."', 
					releaseDate = '".mysql_real_escape_string($this->releaseDate)."', 
					price = '".mysql_real_escape_string($this->price)."'
					WHERE productId = '".mysql_real_escape_string($this->productId)."'";
			$this->conn->execute($SQL);
		} else {
		
			$SQL = "INSERT INTO product (artistId, genreId, name, releaseDate, price) VALUES (
					'".mysql_real_escape_string($this->artistId)."', 
					'".mysql_real_escape_string($this->genreId)."', 
					'".mysql_real_escape_string($this->name)."',
					'".mysql_real_escape_string($this->releaseDate)."',			
					'".mysql_real_escape_string($this->price)."')";
			$this->isLoaded = true;
			$this->productId = $this->conn->execute($SQL);
		}		
	}
	
	public function delete($boolean) {
		// Boolean is a secondary "confirmation" check and we can't delete an object from the DB if we don't have it in the DB in the first place!
		if($this->isLoaded && $boolean) 
			$q = $this->conn->execute("DELETE FROM product WHERE productId = ". $this->productId);
			if($q == 1) return true; else return false;
			
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />productId: " . $this->productId;
		$str .= "<br />artistId: " . $this->artistId;
		$str .= "<br />genreId: " . $this->genreId;
		$str .= "<br />name: " . $this->name;
		$str .= "<br />releaseDate: " . $this->releaseDate;
		$str .= "<br />price: " . $this->price;
		return $str;
	}
}
/*
$test = new Product();
$test->artistId = 8;
$test->genreId = 2;
$test->name = "Can't be moved";
$test->releaseDate = "11/12/2011";
$test->price = "10.99";
$test->save();
print $test->toString();
*/

?>
