<?php
	/*
		ExcelImport.php
		Created by: Marc
		Description: Looks in the directory for spreasheets to import.
		Update log:
			07/03/12 (MF) - Creation.
	*/
		
	require('../AppClasses/Import.php');
	switch($_GET['doImport']) {
		case "artist":
			$target_path = "Uploads/ArtistImport/Incomplete/";
			$completed_folder = "Uploads/ArtistImport/Completed/";
			$errored_folder = "Uploads/ArtistImport/Errored/";
			
			if ($handle = opendir($target_path)) {
		

				/* This is the correct way to loop over the directory. */
				while (false !== ($entry = readdir($handle))) {
				if ($entry == '.' or $entry == '..') continue;
				print_r($entry);
				
					if($_FILES["uploadedfile"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") 
						$fileType = "xlsx";
					else if($_FILES["uploadedfile"]["type"] == "application/vnd.ms-excel") 
						$fileType = "xls";
					
					$imp = new ArtistImport();
					$imp->setImportName("ArtistImport");
				//	$imp->setFileType($fileType);
					$filename = basename(date("d-m-Y-His") . $entry);
					
					$target_path = $target_path . $filename;
					//print $target_path;
					$fileType = "";
					
					
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
						//$imp->import();		
						print "<p>There were ".$imp->getLog()->getNumSuccesses()." successful imports and " .$imp->getLog()->getNumUnsuccessful(). " unsuccessful imports.</p>";
					//	print $imp->getLog()->entriesToTable();
					//	rename($target_path, $completed_folder . $filename);
					} catch (Exception $e) {
					//	rename($target_path, $errored_folder . $filename);
						uploadForm("There was an error importing the file, please try again with the correct format.", $page);
					}
				}
			
			}
			
			closedir($handle);
			break;
		case "sales":
		
			break;
	}
	

		
?>