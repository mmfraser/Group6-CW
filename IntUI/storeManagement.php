<?php
	require_once('../App.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Store Management";
		$page->getHeader();

		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=storeManagement.php">log in</a>.');
		}
	
		// Get the Store list and populate table.
		$allStores = App::getDB()->getArrayFromDB("SELECT * FROM store");
		$storeHtml = "";
		
		
		foreach($allStores as $arr) {
			$storeHtml .= "<tr>" . PHP_EOL;
			$storeHtml .= "	<td>".$arr['storeName']."</td>" . PHP_EOL;
			$storeHtml .= "	<td>".$arr['address']."</td>" . PHP_EOL;
			$storeHtml .= "	<td>".$arr['city']."</td>" . PHP_EOL;
			$storeHtml .= '	<td class="options" style="width:20px;"><a href="modifyStore.php?storeId='.$arr['storeId'].'" title="Modify Store"><span class="ui-icon ui-icon-pencil"></span></a></td>';
			$storeHtml .= "</tr>" . PHP_EOL;
		}
		
		
	
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				$("p.result").hide();
				$("img.spinningWheel").hide();
				$("a#addGroup").button().click(function() {
					$( "#group-dialog-form" ).dialog( "open" );
				});
				$("a#addStore").button().click(function() {
					$( "#store-dialog-form" ).dialog( "open" );
				});
				
				$('#grouplist').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
			
				$(".options a").button();
				$('#storelist').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
			
			$( "#store-dialog-form" ).dialog({
				autoOpen: false,
				height: 300,
				width: 350,
				modal: true,
				buttons: {
					"Add a Store": function() {
						$("img.spinningWheel").show();
						var $form = $( this ),
						storeName = $form.find( 'input[name="storeName"]' ).val(),
						address = $form.find( 'input[name="address"]' ).val(),
						city = $form.find( 'input[name="city"]' ).val();
						
						/* Send the data using post and put the results in a div */
						//$.post( "ajaxFunctions.php?do=addUser", { username: username, password: password, forename: forename, surname: surname, active: active },
						$.post( "ajaxFunctions.php?do=addStore", { storeName: storeName, address: address, city: city },
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
					allFields.val( "" ).removeClass( "ui-state-error" );
				}
			});
			
			
			
			
	});
		</script>

			<p><a id="addStore" href="#">Add New Store</a></p>
			
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="storelist">
				<thead>
					<tr>
						<th>Store Name</th>
						<th>Address</th>
						<th>City</th>
                        <th>Options</th>
					</tr>

				</thead>
				<tbody>
					<?php print $storeHtml; ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Store Name</th>
						<th>Address</th>
						<th>City</th>
                         <th>Options</th>
					</tr>
				</tfoot>
			</table>
	
		
		
	<div id="store-dialog-form" title="Create new store">
			<p class="validateTips">All form fields are required.</p>

			<form method="POST" id="addStore" action="?do=addStore&amp;tab=1">
			<table>
				<tr>
					<td><label for="storeName">Store Name</label><td>
					<td><input type="text" name="storeName" size="15" /></td>
				</tr>
				<tr>
					<td><label for="address">Address</label><td>
					<td><input type="text" name="address" size="15"  /></td>
				</tr>
				<tr>
					<td><label for="city">City</label><td>
					<td><input type="text" name="city" size="15" /></td>
				</tr>
			</table>
		</form>
		<p><img src="../Images/spinningWheel.gif" class="spinningWheel" alt="Loading" /></p>
		<p class="result"><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
			<span class="result"></span></p>
	</div>



	
	
	
	
	
<?php	
	$page->getFooter();
?>