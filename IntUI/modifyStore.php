<?php
	require_once('../App.php');
	
	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Store Management - Modify Store";
		$page->getHeader();
		
		$errorMsg = null;
		$infoMsg = null;
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=storeManagement.php">log in</a>.');
		}
		
		if(isset($_GET['storeId']) && is_numeric($_GET['storeId'])) { 
			$str = new Store();
			$str->populateId($_GET['storeId']);
		} else {
			App::fatalError($page, 'You must select a store in which to edit.  Please go back to the <a href="storeManagement.php">Store Management</a> page.');
		}
		
		if(isset($_POST['storeName'])) {
			$storeName = $str->storeName;
			$address = $_POST['address'];
			$city = $_POST['city'];
		} else {
			$storeName = $str->storeName;
			$address = $str->address;
			$city = $str->city;
		}
		
		if(isset($_GET['do']) && $_GET['do'] == "updateStore" && isset($_GET['storeId'])) {
			try {
				$str->storeName = $_POST['storeName'];
				$str->address = $_POST['address'];
				$str->city = $_POST['city'];
		
				$str->saveStore();
				$infoMsg = "Store updated successfully.";	
			} catch (Exception $e) {
				$errorMsg = $e->getMessage();
			}
		}
		
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				$( "div#tabs" ).tabs({cookie:{}});
				$("input.submit-button").button();
				$("a.back-str-mgmt").button();
			});
		</script>

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">General</a></li>
			<!--<li><a href="#tabs-2">Group Membership</a></li>-->
		</ul>
		<div id="tabs-1">
			<p class="validateTips">All form fields are required.</p>
			
			<form method="POST" id="addStr" action="?do=updateStore&amp;storeId=<?php print $_GET['storeId']; ?>">
				<table>
				<tr>
					<td><label for="storeName">Store Name</label><td>
					<td><input type="text" name="storeName" size="15" value="<?php print $storeName ?>" /></td>
				</tr>
				<tr>
					<td><label for="address">Address</label><td>
					<td><input type="text" name="address" size="15" value="<?php print $address ?>" /></td>
				</tr>
				<tr>
					<td><label for="city">City</label><td>
					<td><input type="text" name="city" size="15" value="<?php print $city ?>" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><input type="submit" value="Update" class="submit-button" /></td>
				</tr>
			</table>
				<div class="ui-state-error ui-corner-all" style="<?php if($errorMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-alert" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $errorMsg; ?></span></div>
			<div class="ui-state-info ui-corner-all" style="<?php if($infoMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-info" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $infoMsg; ?></span></div>
			</form>
			<p><a href="storeManagement.php" class="back-str-mgmt">Back to Store Management</a></p>
		</div>
		
		</div>
		
	</div>

	
<?php	
	$page->getFooter();
?>