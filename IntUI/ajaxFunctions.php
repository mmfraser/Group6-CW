<?php
	error_reporting(E_ERROR);
	require_once('../App.php');
	require_once('../AppClasses/store.php');
	require_once('../AppClasses/Artist.php');
	require_once('../AppClasses/Product.php');
	
	if(!App::checkAuth()) {
			// User not authenticated.
			die('Error: You do not have access to this functonality.');
		}
		
	try {
		$do = $_GET['do'];
		if(!isset($do))
			die("Error: Invalid request.");
			
		if($do == "addUser") {
			$usr = new User();
			$usr->username = $_POST['username'];
			$usr->forename = $_POST['forename'];
			$usr->surname = $_POST['surname'];
			if($_POST['active'] == "true")
				$usr->active = true;
			else
				$usr->active = false;
			$usr->password = $_POST['password'];				
			$usr->save();
			
			die("User successfully added.");
		} else if($do == "addGroup") {
			$grp = new Group();
			$grp->name = $_POST['groupname'];
			$grp->description = $_POST['groupdescription'];
			
			$grp->save();
			
			die("Group successfully added.");
		} else if($do == "addStore") {
			$str = new Store();
			$str->storeName = $_POST['storeName'];
			$str->address = $_POST['address'];
			$str->city = $_POST['city'];
			
			$str->saveStore();
			
			// Create store's user group.
			$grp = new Group();
			$grp->name = $str->storeName;
			$grp->description = $str->storeName . " permissions user group.";
			$grp->storeId = $str->getStoreId();
			$grp->save();
			
			die("Store successfully added.");
		} else if($do == "deleteUser") {
			$usr = new User();
			$usr->populateId($_POST['userId']);
			$usr->delete(true);
		}  else if($do == "addArtist") {
			$artist = new Artist();
			$artist->forename = $_POST['forename'];
			$artist->surname = $_POST['surname'];
			$artist->websiteUrl = $_POST['websiteUrl'];
			$dob = strtotime($_POST['dob']);
			$artist->dob = date("Y-m-d", $dob);
			$artist->nationality = $_POST['nationality'];
			$artist->bandName = $_POST['bandName'];
			$artist->save();
			
			die("Artist successfully added.");
		}  else if($do == "deleteArtist") {
			$artist = new Artist();
			$artist->populateId($_POST['artist']);
			$artist->delete(true);
		}  else if($do == "addProduct") {
			$product = new Product();
			$product->artistId = $_POST['artistId'];
			$product->genreId = $_POST['genreId'];
			$product->name = $_POST['name'];
			$product->releaseDate = $_POST['releaseDate'];
			$product->price = $_POST['price'];
			$product->save();
			
			die("Product successfully added.");
		}  else if($do == "deleteProduct") {
			$product = new Product();
			$product->populateId($_POST['product']);
			$product->delete(true);
		} else 
			die("Error: Invalid request.");
		
	} catch (Exception $e) {
		die("Error: " . $e->getMessage());
	}
?>