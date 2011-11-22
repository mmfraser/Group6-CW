<?php
	require_once('../App.php');
	require_once('../AppClasses/ImportLog.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Import Log Viewer";
		$page->getHeader();
		
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=artistManagement.php">log in</a>.');
		}
	
	if(isset($_GET['logId']) && is_numeric($_GET['logId'])) {
		$entry = new ImportLog();
		$entry->populate($_GET['logId']);
		$user = new User();
		$user->populateId($entry->ranBy);
	} else 
		App::fatalError($page, 'You must select a valid log entry in which to edit.');
	
	
	// Page PHP Backend Code End

?>
	<table style="margin-bottom:10px;">
		<tr>
			<td><strong>Import Type:</strong></td>
			<td><?php print $entry->importName; ?></td>
			<td>&nbsp;</td>
			<td><strong>Total Rows:</strong></td>
			<td><?php print $entry->getNumRows(); ?></td>
		</tr>
		<tr>
			<td><strong>Import Date:</strong></td>
			<td><?php print $entry->logDate; ?></td>
			<td>&nbsp;</td>
			<td><strong>No. Successes:</strong></td>
			<td><?php print $entry->getNumSuccesses(); ?></td>
		</tr>
		<tr>
			<td><strong>Ran by:</strong></td>
			<td><?php print $user->username; ?></td>
			<td>&nbsp;</td>
			<td><strong>No. Failures:</strong></td>
			<td><?php print $entry->getNumUnsuccessful(); ?></td>
		</tr>
	</table>
	
	<?php print $entry->entriesToTable(); ?>

	<script type="text/javascript">
			$(function() {
			});
		</script>
	
<?php	
	$page->getFooter();
?>