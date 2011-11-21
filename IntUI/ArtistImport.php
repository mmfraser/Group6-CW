<?php
	require_once('../App.php');
	require_once('../AppClasses/Import.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Import - Artist Import";
		$page->getHeader();
		
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=userManagement.php">log in</a>.');
		}
	
		$do = $_GET['do'];
		if(isset($do) && $do == "upload") {
			// Where the file is going to be placed 
			$target_path = "Uploads/ArtistImport/Incomplete/";
			$completed_folder = "Uploads/ArtistImport/Completed/";
			$errored_folder = "Uploads/ArtistImport/Errored/";

			/* Add the original filename to our target path.  
			Result is "uploads/filename.extension" */
			$filename = basename(date("d-m-Y-His") . $_FILES['uploadedfile']['name']);
			$target_path = $target_path . $filename;
						
			$fileType = "";
			if($_FILES["uploadedfile"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") 
				$fileType = "xlsx";
			else if($_FILES["uploadedfile"]["type"] == "application/vnd.ms-excel") 
				$fileType = "xls";
		
			if($fileType != "") {
				if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
				
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
					}
				} else{
					uploadForm("There was an error uploading the file, please try again.", $page);
				}
			} else {
				uploadForm("Invalid file type.  Only files of type .xls and .xlsx are allowed.  Please try again.", $page);
			}
		} else {
			uploadForm("", $page);
		}
		
		function uploadForm($error, $page) {  
			if($error != "") { $page->error($error); } ?>
				
			<form enctype="multipart/form-data" action="?do=upload" method="POST">
				<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
				<table>
					<tr>
						<td>Select file:</td>
						<td><input name="uploadedfile" type="file" /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value="Upload File" /></td>
					</tr>
				</table>		
			</form>
			
				<div class="ui-widget ui-state-highlight ui-corner-all">
					<p><span class=" ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>Please note that there is a defined specification for this import which can be found on the 'Import Specification' tab below.</p>
				</div>
			
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Recent Imports</a></li>
					<li><a href="#tabs-2">Import Specification</a></li>
				</ul>

				<div id="tabs-1">
				</div>
				
				<div id="tabs-2">
					<p>Spreadsheets are expected to be in the format outlined below or else the import may fail or the data within the database may not be reliable.</p>
					<p>The only allowable spreadsheets are .xls and .xlsx spreadsheets.</p>
						<table border="1">
				<tr style="font-weight:bold;">
					<td>Forename</td>
					<td>Surname</td>
					<td>Website URL</td>
					<td>Date of Birth</td>
					<td>Nationality</td>
				</tr>
				<tr>
					<td>11/11/2011</td>
					<td>1</td>
					<td>Marc Fraser</td>
					<td>1234</td>
					<td>10</td>
				</tr>
					<tr>
					<td>11/11/2011</td>
					<td>1</td>
					<td>James Dickinson</td>
					<td>4321</td>
					<td>0</td>
				</tr>
			</table>
				</div>
			
			</div>
		
<? }	// Page PHP Backend Code End
?>
		<script type="text/javascript">
			$(function() {
				$( "div#tabs" ).tabs({cookie:{}});
				$('.logEntries').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});	
			});
		</script>

	
<?php	
	$page->getFooter();
?>