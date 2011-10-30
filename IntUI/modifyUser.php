<?php
	require_once('../App.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "User Management - Modify User";
		$page->getHeader();
		
		$errorMsg = null;
		$infoMsg = null;
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=userManagement.php">log in</a>.');
		}
		
		if(isset($_GET['userId']) && is_numeric($_GET['userId'])) { 
			$usr = new User();
			$usr->populateId($_GET['userId']);
		} else {
			App::fatalError($page, 'You must select a user in which to edit.  Please go back to the <a href="userManagement.php">User Management</a> page.');
		}
		
		if(isset($_POST['forename'])) {
			$username = $usr->username;
			$forename = $_POST['forename'];
			$surname = $_POST['surname'];
			$active = $_POST['active'];
		} else {
			$username = $usr->username;
			$forename = $usr->forename;
			$surname = $usr->surname;
			$active = $usr->active;
			if($active == 1)
				$active = "on";	
		}
		
		if(isset($_GET['do']) && $_GET['do'] == "updateUser" && isset($_GET['userId'])) {
			try {
				$usr->forename = $_POST['forename'];
				$usr->surname = $_POST['surname'];
				
				if($_POST['active'] == null || $_POST['active'] != "on") {
					$usr->active = false;
				} else
					$usr->active = true;
				
				// Allow editing without changing the password.
				if($_POST['password'] != null)
					$usr->password = $_POST['password'];
		
				$usr->save();
				$infoMsg = "User updated successfully.";	
			} catch (Exception $e) {
				$errorMsg = $e->getMessage();
			}
		} else if(isset($_GET['do']) && $_GET['do'] == "updateUserGroup" && isset($_GET['userId'])) {
			try{
				if(isset($_POST['usergroup']))
					$usr->groupMembership = $_POST['usergroup'];
				
				$usr->save();
				$infoMsg = "User groups updated successfully.";	
			} catch (Exception $e) {
				$errorMsg = $e->getMessage();
			}
		}
		
		// Build group checkboxes
		$groupSql = "SELECT * FROM usergroup";
		$allGroups = App::getDB()->getArrayFromDB($groupSql);
		$groupsHtml = "";
		
		foreach($allGroups as $arr) {
			$checked = "";
			if(in_array($arr['groupId'], $usr->groupMembership) && !isset($_POST['usergroup']))
				$checked = "checked";
				
			if(isset($_POST['usergroup']) && in_array($arr['groupId'], $_POST['usergroup']))
				$checked = "checked";
				
			$groupsHtml .= '<input type="checkbox" name="usergroup[]" value="'.$arr['groupId'].'" '.$checked.' /> '.$arr['name'].' <br />' . PHP_EOL;
		}
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				$( "div#tabs" ).tabs({cookie:{}});
				$("input.submit-button").button();
				$("a.back-usr-mgmt").button();
			});
		</script>

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">General</a></li>
			<li><a href="#tabs-2">Group Membership</a></li>
		</ul>
		<div id="tabs-1">
			<p class="validateTips">All form fields are required.</p>
			
			<form method="POST" id="addUsr" action="?do=updateUser&amp;userId=<?php print $_GET['userId']; ?>">
				<table>
				<tr>
					<td><label for="username">Username</label><td>
					<td><input type="text" name="username" size="15" value="<?php print $username ?>" disabled="true" /></td>
				</tr>
				<tr>
					<td><label for="forename">Forename</label><td>
					<td><input type="text" name="forename" size="15" value="<?php print $forename ?>" /></td>
				</tr>
				<tr>
					<td><label for="surname">Surname</label><td>
					<td><input type="text" name="surname" size="15" value="<?php print $surname ?>" /></td>
				</tr>
				<tr>
					<td><label for="username">Password</label><td>
					<td><input type="password" name="password" size="15" value="<?php print $password ?>" /></td>
				</tr>
				<tr>
					<td><label for="username">Active</label><td>
					<td><input type="checkbox" name="active" size="15" <?php if($active=="on") print "checked";?> /></td>
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
		
		<div id="tabs-2">	
			<form method="POST" id="addUsrGrp" action="?do=updateUserGroup&amp;userId=<?php print $_GET['userId']; ?>">
			
				
				<?php print $groupsHtml ?>
				
				
			<input type="submit" value="Update" class="submit-button" />
				
			<div class="ui-state-error ui-corner-all" style="<?php if($errorMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-alert" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $errorMsg; ?></span></div>
			
			<div class="ui-state-info ui-corner-all" style="<?php if($infoMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-info" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $infoMsg; ?></span></div>
			</form>
			<p><a href="userManagement.php" class="back-usr-mgmt">Back to User Management</a></p>
		</div>
		
	</div>

	
<?php	
	$page->getFooter();
?>