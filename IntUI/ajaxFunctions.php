<?php
	error_reporting(E_ERROR);
	require_once('../App.php');
	
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
		} else if($do = "addGroup") {
			$grp = new Group();
			$grp->name = $_POST['groupname'];
			$grp->description = $_POST['groupdescription'];
			
			$grp->save();
			
			die("Group successfully added.");
		} else 
			die("Error: Invalid request.");
	} catch (Exception $e) {
		die("Error: " . $e->getMessage());
	}
?>