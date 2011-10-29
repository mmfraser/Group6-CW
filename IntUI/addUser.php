<?php
error_reporting(E_ERROR);
	require_once('../App.php');
	
	try {
		$usr = new User();
		$usr->username = $_POST['username'];
		$usr->forename = $_POST['forename'];
		$usr->surname = $_POST['surname'];
		$usr->active = $_POST['active'];
		$usr->password = $_POST['password'];
		$usr->save();
		
		if($_GET['do'] == "add")
			print "User successfully added.";
	} catch (Exception $e) {
		print "Error: " . $e->getMessage();
	}
	

	
	
?>