<?php
	require_once('../App.php');
	require_once('../AppClasses/Import.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Import - Sales Import";
		$page->getHeader();
		
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=userManagement.php">log in</a>.');
		}
	
		$do = $_GET['do'];
		if(isset($do) && $do == "upload") {
			// Where the file is going to be placed 
			$target_path = "uploads/";

			/* Add the original filename to our target path.  
			Result is "uploads/filename.extension" */
			$target_path = $target_path . basename($_FILES['uploadedfile']['name']);
			
			$fileType = "";
			if($_FILES["uploadedfile"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") 
				$fileType = "xlsx";
			else if($_FILES["uploadedfile"]["type"] == "application/vnd.ms-excel") 
				$fileType = "xls";
		
			if($fileType != "") {
				if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
					$imp = new SalesImport();
					$imp->setImportName("SalesImport");
					$imp->setFileType($fileType);
					$imp->setFile($target_path);
					$imp->setDBCols(Array(
					Array("ColName" => "date", 			"DataType" => "Date", 		"Ignore" => "False"),
					Array("ColName" => "storeId", 		"DataType" => "String" , 	"Ignore" => "False"),
					Array("ColName" => "cashierName", 	"DataType" => "String" , 	"Ignore" => "False"),
					Array("ColName" => "itemId", 		"DataType" => "int" , 		"Ignore" => "False"),
					Array("ColName" => "itemDiscount", 	"DataType" => "float" , 	"Ignore" => "False"),
					Array("ColName" => "customerEmail", "DataType" => "string" ,	"Ignore" => "False")));
					$imp->setDBTable("salesdata");
					
					try {
						$imp->import();		
						print "<p>There were ".$imp->getLog()->getNumSuccesses()." successful imports and " .$imp->getLog()->getNumUnsuccessful(). " unsuccessful imports.</p>";
						print $imp->getLog()->entriesToTable();
						
					} catch (Exception $e) {
						$page->error($e->getMessage());
						uploadForm("There was an error importing the file, please try again with the correct format.");
						
					}
				} else{
					uploadForm("There was an error uploading the file, please try again.");
				}
			} else {
				uploadForm("Invalid file type.  Only files of type .xls and .xlsx are allowed.  Please try again.");
			}
		} else {
			uploadForm("");
		}
		
		function uploadForm($error) {  
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
			<p><em>Please note: Spreadsheets are expected to be in a specific format for this import.  If the spreadsheet is not in the specified format the import will fail.</em></p>
			<p>Spreadsheets are expected to be in the following format:</p>
			<table border="1">
				<tr style="font-weight:bold;">
					<td>Date</td>
					<td>Store ID</td>
					<td>Cashier Name</td>
					<td>Item ID</td>
					<td>Item Discount (%)</td>
					<td>Customer Email</td>
				</tr>
				<tr>
					<td>11/11/2011</td>
					<td>1</td>
					<td>Marc Fraser</td>
					<td>1234</td>
					<td>10</td>
					<td>mf111@hw.ac.uk</td>
				</tr>
					<tr>
					<td>11/11/2011</td>
					<td>1</td>
					<td>James Dickinson</td>
					<td>4321</td>
					<td>0</td>
					<td>jd@somemail.co.uk</td>
				</tr>
			</table>
		
<? }	// Page PHP Backend Code End
?>
		<script type="text/javascript">
			$(function() {		
				$('.logEntries').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});	
			});
		</script>

	
<?php	
	$page->getFooter();
?>