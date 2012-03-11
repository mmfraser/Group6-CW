<?php
	/*
		ExcelImport.php
		Created by: Marc
		Description: Looks in the directory for spreasheets to import.
		Update log:
			07/03/12 (MF) - Creation.
	*/
		
/*	switch($_GET['doImport']) {
		case "artist":
		
			break;
		case "sales":
		
			break;
	}
	$target_path = "Uploads/ArtistImport/Incomplete/";
	$imp = new ArtistImport();
	$imp->setImportName("ArtistImport");
	$imp->setFileType($fileType);
	$imp->setFile($target_path);
	$imp->setDBCols(Array(
	Array("ColName" => "forename", "DataType" => "String", "Ignore" => "False"),
	Array("ColName" => "surname", "DataType" => "String" , "Ignore" => "False"),
	Array("ColName" => "websiteUrl", "DataType" => "String" , "Ignore" => "False"),
	Array("ColName" => "dob", "DataType" => "Date" , "Ignore" => "False"),
	Array("ColName" => "nationality", "DataType" => "String" , "Ignore" => "False"), 
	Array("ColName" => "bandName", "DataType" => "String" , "Ignore" => "False")));
	$imp->setDBTable("artist");
		
		
		try {
			$imp->import();		
			print "<p>There were ".$imp->getLog()->getNumSuccesses()." successful imports and " .$imp->getLog()->getNumUnsuccessful(). " unsuccessful imports.</p>";
			print $imp->getLog()->entriesToTable();
			rename($target_path, $completed_folder . $filename);
		} catch (Exception $e) {
			rename($target_path, $errored_folder . $filename);
			uploadForm("There was an error importing the file, please try again with the correct format.", $page);
		}*/
		
		print "here";

		
?>