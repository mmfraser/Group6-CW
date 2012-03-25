<?php
	require_once('../App.php');
	require_once('../AppClasses/Chart.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Chart Management - Chart Permission";
		$page->getHeader();
		
		$errorMsg = null;
		$infoMsg = null;
		if(!App::checkAuth()) 
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=chartPermission.php">log in</a>.');
		
		// Fetch the chart's details.
		if((isset($_GET['chartId']) && is_numeric($_GET['chartId'])) || isset($_POST['chartId'])) { 
			$chart = new Chart();
			if(!isset($_GET['chartId'])) {
				$chart = Chart::getChart($_POST['chartId']);
			} else {
				$chart = Chart::getChart($_GET['chartId']);
			}
		} else {
			App::fatalError($page, 'You must select a chart in which to set permissions.  Please go back to the <a href="chartManagement.php">Chart Management</a> page.');
		}
	
		// Populate the permissions arrays.
		if(isset($_POST)) {		
			if(!isset($_POST['groupPermissions']))
				$groupPermissions = $chart->getChartGroupPermissions();
			else 
				$groupPermissions = $_POST['groupPermissions'];
				
			if(!isset($_POST['userPermissions']))
				$userPermissions = $chart->getChartUserPermissions();
			else 
				$userPermissions = $_POST['userPermissions'];
			
		} else {
			$userPermissions = $chart->getChartUserPermissions();
			$groupPermissions = $chart->getChartGroupPermissions();
		}
		
		if(isset($_GET['do']) && $_GET['do'] == "update" && isset($_GET['chartId'])) {
			try {
				$chart->updateUserPermissions($userPermissions);
				$chart->updateGroupPermissions($groupPermissions);
				$infoMsg = "Chart permissions updated successfully.";	
			} catch (Exception $e) {
				$errorMsg = $e->getMessage();
			}
		} 
		
		// Build group checkboxes
		$groupSql = "SELECT * FROM usergroup";
		$allGroups = App::getDB()->getArrayFromDB($groupSql);
		$groupsHtml = "";
		$updateVisible = true;
		if($allGroups == null) {
			if(!$updateVisible)
				$updateVisible = false;
			$groupsHtml = '<p>There are no groups in which to set chart permissions on.  Please add these on the <a href="userManagement.php">Group Management</a> tab.</p>';
		}
		
		foreach($allGroups as $arr) {
			$checked = "";
			if(in_array($arr['groupId'], $groupPermissions))
				$checked = "checked";
				
			$groupsHtml .= '<input type="checkbox" name="groupPermissions[]" value="'.$arr['groupId'].'" '.$checked.' /> '.$arr['name'].' <br />' . PHP_EOL;
		}
		
		$userSql = "SELECT * FROM user";
		$allUsers = App::getDB()->getArrayFromDB($userSql);
		$usersHtml = "";
		
		if($allUsers == null) {
			if(!$updateVisible)
				$updateVisible = false;
			$usersHtml = '<p>There are no users in which to grant chart permissions on.  Please add these on the <a href="userManagement.php">User Management</a> tab.</p>';
		}
		
		foreach($allUsers as $arr) {
			$checked = "";
			if(in_array($arr['userId'], $userPermissions))
				$checked = "checked";
				
			$usersHtml .= '<input type="checkbox" name="userPermissions[]" value="'.$arr['userId'].'" '.$checked.' /> '.$arr['forename'].' ' . $arr['surname'] .' <br />' . PHP_EOL;
		}
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				$( "div#tabs" ).tabs({cookie:{}});
				$("input.submit-button").button();
				$("a.back-chart-mgmt").button();
			});
		</script>

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">User Permissions</a></li>
			<li><a href="#tabs-2">Group Permissions</a></li>
		</ul>
		<form method="POST" id="addUser" action="?do=update&amp;chartId=<?php print $_GET['chartId']; ?>">
			<div id="tabs-1">
				
					<?php print $usersHtml; ?>

				<div class="ui-state-error ui-corner-all" style="<?php if($errorMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-alert" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $errorMsg; ?></span></div>
				
				<div class="ui-state-info ui-corner-all" style="<?php if($infoMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-info" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $infoMsg; ?></span></div>
			
			</div>
			
			<div id="tabs-2">	
					<?php print $groupsHtml; ?>
									
				<div class="ui-state-error ui-corner-all" style="<?php if($errorMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-alert" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $errorMsg; ?></span></div>
				
				<div class="ui-state-info ui-corner-all" style="<?php if($infoMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-info" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $infoMsg; ?></span></div>
			</div>
			<input type="submit" value="Update" class="submit-button" style="<?php if($updateVisible === false) print "display:none"; ?>" />
		</form>
	</div>
	
	<p><a href="chartManagement.php" class="back-chart-mgmt">Back to Chart Management</a></p>

	
<?php	
	$page->getFooter();
?>