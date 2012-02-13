<?php
	require_once('../App.php');
	require_once('../AppClasses/Customer.php');
	
	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Customer Management - Modify Customer";
		$page->getHeader();
		
		$errorMsg = null;
		$infoMsg = null;
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=genreManagement.php">log in</a>.');
		}
		
		if(isset($_GET['customerId'])) { 
			$customer = new Customer();
			$customer->populateId($_GET['customerId']);
		} else {
			App::fatalError($page, 'You must select a Customer in which to edit.  Please go back to the <a href="customerManagement.php">Customer Management</a> page.');
		}
		
		if(isset($_POST['emailAddress'])) {
			$emailAddress = $_POST['emailAddress'];
			$forename = $_POST['forename'];
			$surname = $_POST['surname'];
			$addressLine1 = $_POST['addressLine1'];
			$addressLine2 = $_POST['addressLine2'];
			$postcode = $_POST['postcode'];
			$town = $_POST['town'];
			$city = $_POST['city'];
			$telephoneNumber = $_POST['telephoneNumber'];
		} else {
			$emailAddress = $customer->emailAddress;
			$forename = $customer->forename;
			$surname = $customer->surname;
			$addressLine1 = $customer->addressLine1;
			$addressLine2 = $customer->addressLine2;
			$postcode = $customer->postcode;
			$town = $customer->town;
			$city = $customer->city;
			$telephoneNumber = $customer->telephoneNumber;
		}
		
		if(isset($_GET['do']) && $_GET['do'] == "updateCustomer" && isset($_GET['customerId'])) {
			if($_POST['emailAddress'] == null || $_POST['forename'] == null || $_POST['surname'] == null || $_POST['telephoneNumber'] == null) 
				$errorMsg = 'One or more required fields are not completed.';
			else {
				try {
					$customer->emailAddress = $_POST['emailAddress'];
					$customer->forename = $_POST['forename'];
					$customer->surname = $_POST['surname'];
					$customer->addressLine1 = $_POST['addressLine1'];
					$customer->addressLine2 = $_POST['addressLine2'];
					$customer->postcode = $_POST['postcode'];
					$customer->town = $_POST['town'];
					$customer->city = $_POST['city'];
					$customer->telephoneNumber = $_POST['telephoneNumber'];
					
					$customer->save();
					$infoMsg = "Customer updated successfully.";	
				} catch (Exception $e) {
					$errorMsg = $e->getMessage();
				}
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
			
			<form method="POST" id="addStr" action="?do=updateCustomer&amp;customerId=<?php print $_GET['customerId']; ?>">
				<table>
				<tr>
					<td><label for="forename">Forename*</label><td>
					<td><input type="text" name="forename" size="15" value="<?=$forename?>" /></td>
				</tr>
				<tr>
					<td><label for="surname">Surname*</label><td>
					<td><input type="text" name="surname" size="15" value="<?php print $surname?>"  /></td>
				</tr>
				<tr>
					<td><label for="emailAddress">Email address*</label><td>
					<td><input type="text" name="emailAddress" size="15"  value="<?php print $emailAddress?>" /></td>
				</tr>
				<tr>
					<td><label for="telephoneNumber">Telephone Number*</label><td>
					<td><input type="text" name="telephoneNumber" size="15" value="<?php print $telephoneNumber?>" /></td>
				</tr>
				<tr>
					<td><label for="addressLine1">Address Line 1</label><td>
					<td><input type="text" name="addressLine1" size="15" value="<?php print $addressLine1?>" /></td>
				</tr>
				<tr>
					<td><label for="addressLine2">Address Line 2</label><td>
					<td><input type="text" name="addressLine2" size="15" value="<?php print $addressLine2?>" /></td>
				</tr>
				<tr>
					<td><label for="town">Town</label><td>
					<td><input type="text" name="town" size="15" value="<?php print $town?>" /></td>
				</tr>
				<tr>
				<td><label for="city">City</label><td>
					<td><input type="text" name="city" size="15" value="<?php print $city?>"  /></td>
				</tr>
				<tr>
				<td><label for="postcode">Postcode</label><td>
					<td><input type="text" name="postcode" size="15" value="<?php print $postcode?>" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><input type="submit" value="Update" class="submit-button" /></td>
				</tr>
			</table>
				<div class="ui-state-error ui-corner-all" style="<?php if($errorMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-alert" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $errorMsg; ?></span></div>
			<div class="ui-state-info ui-corner-all" style="<?php if($infoMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-info" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $infoMsg; ?></span></div>
			</form>
			<p><a href="customerManagement.php" class="back-str-mgmt">Back to Customer Management</a></p>
		</div>
		
		</div>
		
	</div>

	
<?php	
	$page->getFooter();
?>