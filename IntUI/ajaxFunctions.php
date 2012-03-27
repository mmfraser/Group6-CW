<?php
	error_reporting(E_ERROR);
	require_once('../App.php');
	require_once('../AppClasses/store.php');
	require_once('../AppClasses/Artist.php');
	require_once('../AppClasses/Product.php');
	require_once('../AppClasses/Genre.php');
	require_once('../AppClasses/DashboardTab.php');
	require_once('../AppClasses/Customer.php');
	
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
		} else if($do == "deleteGroup") {
			$grp = new Group();
			$grp->populateId($_POST['groupId']);
			$grp->delete(true);
		}  else if($do == "addArtist") {
			$artist = new Artist();
			$artist->forename = $_POST['forename'];
			$artist->surname = $_POST['surname'];
			$artist->websiteUrl = $_POST['website'];
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
			
		} else if($do == "addGenre") {
			$genre = new Genre();
			$genre->genreName = $_POST['genreName'];
			$genre->save();
			die("Genre successfully added.");
		} else if($do == "selectChart") {
			$tab = new DashboardTab();
			$tab->populateId($_POST['tabId']);
			$tab->changeLayout($_POST['chartId'], $_POST['chartPos']);
			die('Layout successfully changed');
		} else if($do == "changeFilter") {
			// Insert the filter query string into the database, but first convert it to a PHP array.
			
			$overriddenFilters = array();
			
			$filterPieces = explode(";", $_POST['filterQuery']);
			foreach($filterPieces as $filterPiece) {
				$filter = explode("=>", $filterPiece);
				// 0th element is the filter name, 1st element is the new value of the filter.
				if($filter[0] != null)
					$overriddenFilters[$filter[0]] = $filter[1];
			}
		
			// Serialize array and put in database
			App::getDB()->execute("UPDATE dashboardlayout SET customFilter = '".serialize($overriddenFilters)."' WHERE dashboardLayoutId = '".$_POST['layoutId']."'");
			
			die("Filter(s) successfully updated.");
			
		} else if($do == "createTab") {
			$tab = new DashboardTab();
			$tab->userId = App::getAuthUser()->getUserId();
			$tab->tabName = $_POST['tabName'];
			$tab->tabDescription = $_POST['tabDescription'];
			
			$tab->save();
			die("Tab successfully added.");
		} else if($do == "deleteTab") {
			$tab = new DashboardTab();
			$tab->populateId($_POST['tabId']);
			$tab->delete();

			die("Tab successfully deleted.");
		} else if($do == "addCustomer") {
			// Check email address
			if(!App::validateEmail($_POST['emailAddress'])) 
				die('Invalid email address.');
				
			if($_POST['emailAddress'] == null || $_POST['forename'] == null || $_POST['surname'] == null || $_POST['telephoneNumber'] == null) 
				die('One or more required fields are not completed.');
				
			$customer = new Customer();
			$customer->emailAddress = $_POST['emailAddress'];
			$customer->forename = $_POST['forename'];
			$customer->surname = $_POST['surname'];
			$customer->addressLine1 = $_POST['addressLine1'];
			$customer->addressLine2 = $_POST['addressLine2'];
			$customer->town = $_POST['town'];
			$customer->city = $_POST['city'];
			$customer->postcode = $_POST['postcode'];
			
			$customer->save();
			
			die('Customer successfully added.');
		} else if($do == "deleteCustomer") {
			$customer = new Customer();
			$customer->populate($_POST['emailAddress']);
			$customer->delete();
		} else 
			die("Error: Invalid request.");
		
	} catch (Exception $e) {
		die("Error: " . $e->getMessage());
	}
?>