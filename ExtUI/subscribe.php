<?php
		// Page PHP Backend Code Begin
		require_once('../App.php');
		require_once('page.php');
		$page = new Page();
		$page->title = "Quicksilver Music ";
		$page->getHeader();
		$errorMsg = null;
		$infoMsg = null;
		
		$email = $_REQUEST['email'];
?>
<div id="left">


<!-- START OF POST BLOCK -->
<div class="post">
	<div class="post_h"></div>
	<div class="postcontent">
		<h2>Sign up to the Quicksilver Music Newsletter</h2>
        <p>Fill in your details below and we will keep you informed about all the latest deals at Quicksilver.</p>
        <form method="post" id="signUp" action="email.php">
			<table>
				<tr>
					<td><label for="forename">Forename*</label><td>
					<td><input type="text" required="required" name="forename" id="forename" size="15" placeholder="Forename" /></td>
				</tr>
				<tr>
					<td><label for="surname">Surname*</label><td>
					<td><input type="text" required="required" name="surname" id="surname" size="15"  placeholder="Surname" /></td>
				</tr>
				<tr>
					<td><label for="emailAddress">Email address*</label><td>
					<td><input type="email" required="required" name="emailAddress" value=<?=$_REQUEST['emailAddress'];?> id="emailAddress" size="15"  placeholder="Email" /></td>
				</tr>
				<tr>
					<td><label for="telephoneNumber">Telephone Number</label><td>
					<td><input type="tel" name="telephoneNumber" id="telephoneNumber" size="15"  placeholder="Telephone" /></td>
				</tr>
				<tr>
					<td><label for="addressLine1">Address Line 1</label><td>
					<td><input type="text" name="addressLine1" id="addressLine1" size="15"  placeholder="Address" /></td>
				</tr>
				<tr>
					<td><label for="addressLine2">Address Line 2</label><td>
					<td><input type="text" name="addressLine2" id="addressLine2" size="15"  placeholder="Address" /></td>
				</tr>
				<tr>
					<td><label for="town">Town</label><td>
					<td><input type="text" name="town" size="15" id="town" value=""  placeholder="Town" /></td>
				</tr>
				<tr>
				<td><label for="city">City</label><td>
					<td><input type="text" name="city" size="15" id="city" value=""  placeholder="City" /></td>
				</tr>
				<tr>
				<td><label for="postcode">Postcode</label><td>
					<td><input type="text" name="postcode" size="15" id="postcode" value=""  placeholder="Postcode" /></td>
				</tr>
                <td>&nbsp;<td>
					<td><input type="submit" value="Sign Me Up!" /></td>
				</tr>
			</table>
		</form>
	</div>
	<div class="post_b"></div>
</div>
<!-- END OF POST BLOCK -->

</div>

<?php
	$page->getFooter();
?>
