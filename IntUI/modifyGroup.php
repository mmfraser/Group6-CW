<?php
	require_once('../App.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "User Management - Modify Group";
		$page->getHeader();
		
		$errorMsg = null;
		$infoMsg = null;
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=userManagement.php">log in</a>.');
		}
		
		if(isset($_GET['groupId']) && is_numeric($_GET['groupId'])) { 
			$grp = new Group();
			$grp->populateId($_GET['groupId']);
			if($grp->name == null) {
				App::fatalError($page, 'You must select a group in which to edit.  Please go back to the <a href="userManagement.php">User Management</a> page.');
			}
		} else {
			App::fatalError($page, 'You must select a group in which to edit.  Please go back to the <a href="userManagement.php">User Management</a> page.');
		}
		
		if(isset($_GET['do']) && $_GET['do'] == "updateGroup" && isset($_GET['groupId'])) {
			try {
				$grp->name = $_POST['name'];
				$grp->description = $_POST['description'];			
				$grp->save();
				
				$infoMsg = "Group updated successfully.";	
			} catch (Exception $e) {
				$errorMsg = $e->getMessage();
			}
		}
			
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				$( "div#tabs" ).tabs();
				$("input.submit-button").button();
				$("a.back-usr-mgmt").button();
			});
		</script>

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Modify Group</a></li>
		</ul>
		<div id="tabs-1">
			<p class="validateTips">All form fields are required.</p>
			
			<form method="POST" id="addUsr" action="?do=updateGroup&amp;groupId=<?php print $_GET['groupId']; ?>">
				<table>
				<tr>
					<td><label for="name">Group Name</label><td>
					<td><input type="text" name="name" size="15" value="<?php  if($_POST['name'] == null) print $grp->name; else print $_POST['name']; ?>" /></td>
				</tr>
				<tr>
					<td><label for="description">Group Description</label><td>
					<td><textarea type="text" name="description" rows="2" cols="30"><?php  if($_POST['description'] == null) print $grp->description; else print $_POST['description']; ?></textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><input type="submit" value="Update" class="submit-button" /></td>
				</tr>
			</table>
				<div class="ui-state-error ui-corner-all" style="<?php if($errorMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-alert" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $errorMsg; ?></span></div>
			<div class="ui-state-info ui-corner-all" style="<?php if($infoMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-info" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $infoMsg; ?></span></div>
			</form>
			<p><a href="userManagement.php" class="back-usr-mgmt">Back to User Management</a></p>
		</div>
	</div>

	
<?php	
	$page->getFooter();
?>