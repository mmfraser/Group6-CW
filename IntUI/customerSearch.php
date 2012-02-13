<?php
	require_once('../App.php');
	require_once('../AppClasses/Customer.php');
	
	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Customer Search";
		$page->getHeader();
		
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=customerSearch.php">log in</a>.');
		}
	
		$customers = App::getDB()->getArrayFromDB("SELECT emailAddress FROM customer");
		$customersList = "";
		$i = 0;
		$countCustomers= count($customers);
		foreach($customers as $arr) {
			if($i < ($countCustomers - 1))
				$customersList .= '"'.$arr["emailAddress"] . '", ';
			else 
				$customersList .= '"'. $arr["emailAddress"] . '"';
			$i++;
		}
		
	// Page PHP Backend Code End

	if(isset($_GET['do']) && $_GET['do'] == "search") {
		$forename = $_POST['forename'];
		$surname = $_POST['surname'];
		$town = $_POST['town'];
		$postcode = $_POST['postcode'];
		$emailAddress = $_POST['emailAddress'];

		$filters = array();
		$joins = array();
		
		
			if($forename != null) 
				$filters[] = "(forename LIKE '%" . $forename . "%')";
			if($surname != null) 
				$filters[] = "(surname LIKE '%" . $surname . "%')";
			if($town != null) {
				$filters[] = "(town LIKE '%" . $town . "%')";
			}
			if($postcode != null) 
				$filters[] = "(postcode LIKE '%" . $postcode . "%')";
			if($emailAddress != null) 
				$filters[] = "(emailAddress = '" . $emailAddress . "')";

			$query = "SELECT emailAddress, customerId FROM customer ";
			$filterString = " WHERE ";
			for($i = 0; $i < count($filters); $i++) {
				if($i == 0)
					$filterString .= $filters[$i];
				else 
					$filterString .= " AND " . $filters[$i];
			}
			
			$joinString = "";
			
			for($i = 0; $i < count($joins); $i++) {
				$joinString .= $joins[$i];
			}
			
			if(count($filters) < 1) {
				$page->error("You must apply at least one filter.");
			} else {
				$allCustomers = App::getDB()->getArrayFromDB($query . " " . $joinString . " " . $filterString);
			
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
			
			}
			
		
	}

?>
			<h3>Search for Sale</h3>
			<form method="POST" id="search" action="?do=search">
				<table>
					<tr>
						<td>Forename</td>
						<td><input type="text" name="forename" size="15" value="<?php print $_POST['forename']; ?>"  /></td>
						<td></td>
					</tr>
					<tr>
						<td>Surname</td>
						<td><input type="text" name="surname" size="15" value="<?php print $_POST['surname']; ?>" /></td>
						<td><em></em></td>
					</tr>
					<tr>
						<td>Email Address:</td>
						<td><input type="text" name="emailAddress" size="15" id="emailAddress" value="<?php print $_POST['emailAddress']; ?>" /></td>
						<td></td>
					</tr>
					<tr>
						<td>Postcode:</td>
						<td><input type="text" name="postcode" size="15" value="<?php print $_POST['postcode']; ?>" /></td>
						<td></td>
					</tr>
					<tr>
						<td>Town:</td>
						<td><input type="text" name="town" size="15" value="<?php print $_POST['town']; ?>" /></td>
						<td></td>
					</tr>
					<tr>
					<td colspan="3" style="text-align:center;"><input type="submit" value="Search" class="submit-button" /></td>
					</tr>
				</table>
			</form>
		
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
		
			<script type="text/javascript">
			$(function() {
				var availableTags = [<?php print $customersList ?>];
				$(".submit-button").button();
				
				$('#customerList').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
				
				$(".options a").button();
				
				$('#productList').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
				
				$( "#emailAddress" ).autocomplete({
					source: availableTags
				});
			});
		</script>
<?php	
	$page->getFooter();
?>