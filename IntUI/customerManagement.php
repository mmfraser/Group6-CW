<?php
	require_once('../App.php');
	require_once('../AppClasses/Customer.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Customer Management";
		$page->getHeader();
		
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=customerManagement.php">log in</a>.');
		}
	
		// Get the artist list and populate table.
		$allCustomers = App::getDB()->getArrayFromDB("SELECT emailAddress, customerId FROM customer");
		$customerHtml = "";
				
		foreach($allCustomers as $arr) {
			$customer = new Customer();
			$customer->populate($arr['emailAddress']);
			$customerHtml .= "<tr id=\"".$arr['emailAddress']."\">" . PHP_EOL;
			$customerHtml .= "	<td>".$customer->forename."</td>" . PHP_EOL;
			$customerHtml .= "	<td>".$customer->surname."</td>" . PHP_EOL;
			$customerHtml .= "	<td>".$customer->emailAddress."</td>" . PHP_EOL;
			$customerHtml .= "	<td>".$customer->addressLine1."</td>" . PHP_EOL;
			$customerHtml .= "	<td>".$customer->addressLine2."</td>" . PHP_EOL;
			$customerHtml .= "	<td>".$customer->town."</td>" . PHP_EOL;
			$customerHtml .= "	<td>".$customer->city."</td>" . PHP_EOL;
			$customerHtml .= "	<td>".$customer->postcode."</td>" . PHP_EOL;
			$customerHtml .= "	<td>".$customer->telephoneNumber."</td>" . PHP_EOL;
			$customerHtml .= '	<td class="options" style=""><a href="modifyCustomer.php?customerId='.$arr['customerId'].'" title="Modify Customer"><span class="ui-icon ui-icon-pencil"></span><a title="Delete Customer" id="deleteCustomer"><span class="ui-icon ui-icon-trash"></span></a></td>';
			$customerHtml .= "</tr>" . PHP_EOL;
		}
	
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {	
				var emailAddress;
				$("p.result").hide();
				$("img.spinningWheel").hide();
				
				$("a#addCustomer").button().click(function() {
					$( "#customer-dialog-form" ).dialog( "open" );
				});
				
				$(".options a").button();
				
				$("a#deleteCustomer").button().click(function() {
					emailAddress = $(this).parent().parent().attr("id")
					$( "#dialog-confirm-delete" ).dialog( "open" );
				});
				
				$('#customerList').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
				
				
				$( "#dialog-confirm-delete" ).dialog({
					autoOpen: false,
					resizable: false,
					height:170,
					modal: true,
					buttons: {
						"Delete customer": function() {
							$.post( "ajaxFunctions.php?do=deleteCustomer", { emailAddress: emailAddress});
						location.reload();
						},
						Cancel: function() {
							$( this ).dialog( "close" );
						}
					}
				});
				
			$( "#customer-dialog-form" ).dialog({
				autoOpen: false,
				height: 400,
				width: 410,
				modal: true,
				buttons: {
					"Create Customer": function() {
						$("img.spinningWheel").show();
						var $form = $( this ),
						emailAddress = $form.find( 'input[name="emailAddress"]' ).val(),
						forename = $form.find( 'input[name="forename"]' ).val(),
						surname = $form.find( 'input[name="surname"]' ).val(),
						addressLine1 = $form.find( 'input[name="addressLine1"]' ).val(),
						addressLine2 = $form.find( 'input[name="addressLine2"]' ).val();
						town = $form.find( 'input[name="town"]' ).val();
						city = $form.find( 'input[name="city"]' ).val();
						postcode = $form.find( 'input[name="postcode"]' ).val();
						telephoneNumber = $form.find( 'input[name="telephoneNumber"]' ).val();

						/* Send the data using post and put the results in a div */
						$.post( "ajaxFunctions.php?do=addCustomer", { emailAddress: emailAddress, forename: forename, surname: surname, addressLine1: addressLine1, addressLine2: addressLine2, town: town, city: city, postcode: postcode, telephoneNumber: telephoneNumber },
						  function( data ) {
							$("img.spinningWheel").hide();					  
							$("p.result").show();
							$("span.result").empty().append(data);
						  }
						);
					},
					Cancel: function() {
						location.reload();
						$( this ).dialog( "close" );
					}
				},
				close: function() {
					location.reload();
					allFields.val( "" ).removeClass( "ui-state-error" );
				}
			});			
	});
		</script>
	
			<p><a id="addCustomer" href="#">Add New Customer</a></p>
			
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="customerList">
				<thead>
					<tr>
						<th>Forename</th>
						<th>Surname</th>
						<th>Email Address</th>
						<th>Address Line 1</th>
						<th>Address Line 2</th>
						<th>Town</th>
						<th>City</th>
						<th>Postcode</th>
						<th>Telephone Number</th>
						<th>Options</th>
					</tr>

				</thead>
				<tbody>
					<?php print $customerHtml; ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Forename</th>
						<th>Surname</th>
						<th>Email Address</th>
						<th>Address Line 1</th>
						<th>Address Line 2</th>
						<th>Town</th>
						<th>City</th>
						<th>Postcode</th>
						<th>Telephone Number</th>
						<th>Options</th>
					</tr>
				</tfoot>
			</table>
		
		
	<div id="customer-dialog-form" title="Create new Customer">
			<p class="validateTips">Required form fields are denoted by *.</p>

			<form method="POST" id="addArtist" action="?do=addArtist">
			<table>
				<tr>
					<td><label for="forename">Forename*</label><td>
					<td><input type="text" name="forename" size="15" /></td>
				</tr>
				<tr>
					<td><label for="surname">Surname*</label><td>
					<td><input type="text" name="surname" size="15"  /></td>
				</tr>
				<tr>
					<td><label for="emailAddress">Email address*</label><td>
					<td><input type="text" name="emailAddress" size="15" /></td>
				</tr>
				<tr>
					<td><label for="telephoneNumber">Telephone Number*</label><td>
					<td><input type="text" name="telephoneNumber" size="15" /></td>
				</tr>
				<tr>
					<td><label for="addressLine1">Address Line 1</label><td>
					<td><input type="text" name="addressLine1" size="15" /></td>
				</tr>
				<tr>
					<td><label for="addressLine2">Address Line 2</label><td>
					<td><input type="text" name="addressLine2" size="15" /></td>
				</tr>
				<tr>
					<td><label for="town">Town</label><td>
					<td><input type="text" name="town" size="15" value="" /></td>
				</tr>
				<tr>
				<td><label for="city">City</label><td>
					<td><input type="text" name="city" size="15" value="" /></td>
				</tr>
				<tr>
				<td><label for="postcode">Postcode</label><td>
					<td><input type="text" name="postcode" size="15" value="" /></td>
				</tr>
			</table>
		</form>
		<p><img src="../Images/spinningWheel.gif" class="spinningWheel" alt="Loading" /></p>
		<p class="result"><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
			<span class="result"></span></p>
		</div>
	</div>

	
	<div id="dialog-confirm-delete" title="Delete customer?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This artist, as well as all things tied to this user (sales, etc.) will be deleted.  Are you sure?</p>
	</div>

	
<?php	
	$page->getFooter();
?>