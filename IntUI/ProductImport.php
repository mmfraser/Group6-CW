<?php
	require_once('../App.php');
	require_once('../AppClasses/Import.php');
	require_once('../AppClasses/ImportLogCollection.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Import - Product Import";
		$page->getHeader();
		
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=userManagement.php">log in</a>.');
		}
	
		$do = $_GET['do'];
		if(isset($do) && $do == "upload") {
			// Where the file is going to be placed 
			$target_path = "Uploads/ProductImport/Incomplete/";
			$completed_folder = "Uploads/ProductImport/Completed/";
			$errored_folder = "Uploads/ProductImport/Errored/";

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
				
				$imp = new ProductImport();
				$imp->setImportName("ProductImport");
				$imp->setFileType($fileType);
				$imp->setFile($target_path);
				$imp->setDBCols(Array(
				Array("ColName" => "artistId", "DataType" => "int", "Ignore" => "False"),
				Array("ColName" => "genreId", "DataType" => "int" , "Ignore" => "False"),
				Array("ColName" => "name", "DataType" => "String" , "Ignore" => "False"),
				Array("ColName" => "releaseDate", "DataType" => "Date" , "Ignore" => "False"),
				Array("ColName" => "price", "DataType" => "String" , "Ignore" => "False")));
				$imp->setDBTable("product");
					
					
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
		
		/* Populate the recent imports table */
		$recentImports = new ImportLogCollection();
		$recentImports->populateImportName("ProductImport");
		$recentHtml = $recentImports->getHtmlTable();
		
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
			<?php } ?>
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Recent Imports</a></li>
					<li><a href="#tabs-2">Import Specification</a></li>
				</ul>

				<div id="tabs-1">
					<?php print $recentHtml; ?>
				</div>
				
				<div id="tabs-2">
					<p>Spreadsheets are expected to be in the format outlined below or else the import may fail or the data within the datbase may not be reliable.</p>
					<p>The only allowable spreadsheets are .xls and .xlsx spreadsheets.</p>
					<p><strong>Dependancies:</strong> both the Artist ID and Genre ID must already exist in the database.</p>
					<table border="1">
				<tr style="font-weight:bold;">
					<td>Artist ID</td>
					<td>Genre ID</td>
					<td>Name</td>
					<td>Release Date</td>
					<td>Price (&pound;)</td>
				</tr>
				<tr>
					<td>18</td>
					<td>3</td>
					<td>The Blues</td>
					<td>12/12/1999</td>
					<td>9.99</td>
				</tr>
				<tr>
					<td>134</td>
					<td>2</td>
					<td>Symphonics II</td>
					<td>09/08/2003</td>
					<td>5.99</td>
				</tr>
			</table>
				</div>
			</div>
						
		<script type="text/javascript">
			$(function() {
				$( "div#tabs" ).tabs({cookie:{}});
			});
		</script>

	
<?php	
	$page->getFooter();
?>