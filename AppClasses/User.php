<?php

include ('App.php');
include ('Collection.php');

class UserClass {
	//  Region:  DB / DB Fields
	const __TBL = "user";
		const __FLD_USERID = "userId";	
  	const __FLD_FORENAME = "forename";	
  	const __FLD_SURNAME = "surname";	
  	const __FLD_PASSWORD = "password";	
  	const __FLD_ACTIVE = "active";	
  
      private $conn;
	//  Region:  DB / DB Fields End

	//  Region:  Properties
		private $userId;
  	private $Forename;
  	private $Surname;
  	private $Password;
  	private $Active;
  	
      private $isLoaded;

	function __construct($userId = "", $Forename = "", $Surname = "", $Password = "", $Active = "") {
		$this->conn 		=		App::getDB();
		$this->userId = $userId;
    $this->Forename = $Forename;
    $this->Surname = $Surname;
    $this->Password = $Password;
    $this->Active = $Active;
    	
	}

	public function returnTable(){
		return 	self::__TBL;
	}

// Region: Standard Functions
	public function populate($PrimKeyValue){
		App::$DAL->SqlBuilder->Table(self::__TBL);
				App::$DAL->SqlBuilder->Where(self::__FLD_USERID,"=",$PrimKeyValue);
		$sql = App::$DAL->SqlBuilder->SELECTStatement();
		$row = App::$DAL->GetDataRow($sql);
		$this->getRow($row);
	}
	private function getRow($row){
		
		$this->userId = $row[self::_FLD_USERID];
		$this->Forename = $row[self::_FLD_FORENAME];
		$this->Surname = $row[self::_FLD_SURNAME];
		$this->Password = $row[self::_FLD_PASSWORD];
		$this->Active = $row[self::_FLD_ACTIVE];
		$this->isLoaded = true;
	}

	public function save() {
		App::$DAL->SqlBuilder->Table(self::__TBL);
				App::$DAL->SqlBuilder->Field(self::__FLD_USERID,$this->userId);
				App::$DAL->SqlBuilder->Field(self::__FLD_FORENAME,$this->Forename);
				App::$DAL->SqlBuilder->Field(self::__FLD_SURNAME,$this->Surname);
				App::$DAL->SqlBuilder->Field(self::__FLD_PASSWORD,$this->Password);
				App::$DAL->SqlBuilder->Field(self::__FLD_ACTIVE,$this->Active);
		
		if ($this->isLoaded === true) {
						App::$DAL->SqlBuilder->Where(self::__FLD_USERID,"=",$this->userId);      
			$SQL = App::$DAL->SqlBuilder->UPDATEStatement();
		} else {
			$SQL = App::$DAL->SqlBuilder->INSERTStatement();
		}
		App::$DAL->Execute($SQL);
	}
}

class UserClassCollection{

	private $Class;
	private $__TBL;
	public $Collection;
	
	function __construct() {
		$this->Collection 			= 	new Collection;
		$this->Class				= 	new UserClass;
		$this->__TBL				= 	$this->Class->returnTable();
	}
	
	public  function __toString(){
		return $this->Class->UserName;
	}
	
	public function Retrieve(){
		try{
			App::$DAL->SqlBuilder->Table($this->__TBL);
			$sql = App::$DAL->SqlBuilder->SELECTStatement();
			$DataTable = App::$DAL->getArrayFromDB($sql);
			foreach ($DataTable as $row) {
				$this->Collection->addItem(
					new UserClass(
                      $row['USERID'], 
                      $row['FORENAME'], 
                      $row['SURNAME'], 
                      $row['PASSWORD'], 
                      $row['ACTIVE']
					),
          	$row['USERID']
				);
			}
		}catch (Exception $e){
			App::$Logger->Log(LogType::Error,$e->getMessage());
		}
	}

	public function getItemByKey($Key){
		try{
			return $this->Collection->getItem($Key);
		}catch (Exception $e){
			App::$Logger->Log(LogType::Error,$e->getMessage());
		}
	}
	
	public function Count(){
		try{
			return $this->Collection->length();
		}catch (Exception $e){
			App::$Logger->Log(LogType::Error,$e->getMessage());
		}
	}
	
	public function RemoveItemByKey($Key){
		try{
			return $this->Collection->removeItem($key);
		}catch (Exception  $e){
			App::$Logger->Log(LogType::Error,$e->getMessage());
		}
	}
}
?>