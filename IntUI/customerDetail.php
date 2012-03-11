<?php
	require_once('../App.php');
	require_once('../AppClasses/Customer.php');
	require_once('../AppClasses/Sale.php');
	require_once('../AppClasses/Product.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Customer Management - Customer Detail";
		$page->getHeader();
		
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=customerManagement.php">log in</a>.');
		}
	
		$customerId = $_GET['customerId'];
		$customer = new Customer();
		$customer->populateId($customerId);
		
		if(!$customer->isLoaded)
				App::fatalError($page, 'Invalid customer.  Please select a valid customer by <a href="customerSearch.php">searching</a>.');
		
			$sales = App::getDB()->getArrayFromDB("SELECT saleId, itemId FROM salesdata WHERE customerEmail = '".$customer->emailAddress."'");
			
			$grossSaleValue = 0.00;
			$discountValue = 0.00;
			
			$saleHtml = '';
			foreach($sales as $row) {
				$sale = new Sale();
				$sale->populateId($row['saleId']);
				$product = new Product();
				$product->populateId($sale->itemId);
				$store = new Store();
				$store->populateId($sale->storeId);
				
				$saleHtml .= '<tr>'.PHP_EOL;
				$saleHtml .= '	<td>'.$sale->getSaleId().'</td>'.PHP_EOL;
				$saleHtml .= '	<td>'.$sale->date.'</td>'.PHP_EOL;
				$saleHtml .= '	<td>'.$store->storeName.'</td>'.PHP_EOL;
				$saleHtml .= '	<td>'.$sale->cashierName.'</td>'.PHP_EOL;
				$saleHtml .= '	<td>'.$sale->itemId.'</td>'.PHP_EOL;
				$saleHtml .= '	<td>'.$product->name.'</td>'.PHP_EOL;
				$saleHtml .= '	<td>'.$sale->itemDiscount.'</td>'.PHP_EOL;
				$saleHtml .= '	<td>'.$product->price.'</td>'.PHP_EOL;
				$discount += $product->price*($sale->itemDiscount/100);
				$grossSaleValue += $product->price;
				$netValue = $product->price - ($product->price*($sale->itemDiscount/100));
				$saleHtml .= '	<td>'.$netValue.'</td>'.PHP_EOL;
				$saleHtml .= '</tr>'.PHP_EOL;
				
			}	
		
	// Page PHP Backend Code End

?>
	<h3>Viewing details of customer: <u><?=$customer->emailAddress;?></u></h3>
	
	<div style="float:left; border:1px solid #000; width:400px; padding:0 10px 10px 10px; min-height:300px">
	<h4>Personal Details</h4>
		<table>
			<tr>
				<td><strong>Forename:</strong></td>
				<td><?=$customer->forename;?></td>
			</tr>
			<tr>
				<td><strong>Surname:</strong></td>
				<td><?=$customer->surname;?></td>
			</tr>
			<tr>
				<td><strong>Address:</strong></td>
				<td>
					<?=$customer->addressLine1;?><br/>
					<?=$customer->addressLine2;?><br/>
					<?=$customer->town;?><br/>
					<?=$customer->postcode;?><br/>
					<?=$customer->city;?><br/>
				</td>
			</tr>
			<tr>
				<td><strong>Telephone Number:</strong></td>
				<td><?=$customer->telephoneNumber;?></td>
			</tr>
		</table>
	</div>
	<img src="../AppClasses/drawChart.php?chartId=121&amp;Filter=<?=$customer->emailAddress;?>&amp;preview=true" class="chart" alt="115">
	<img src="../AppClasses/drawChart.php?chartId=122&amp;Filter=<?=$customer->emailAddress;?>&amp;preview=true" class="chart" alt="115">
	<div class="clear"></div>		
	<div style="margin-top:10px;float:left; border:1px solid #000; width:98%; padding:0 10px 10px 10px;">
	<h4>Latest Purchases</h4>
		<table>
			<tr><td><strong>Gross Value of all Sales:</strong></td><td>&pound;<?=$grossSaleValue?></td></tr>
			<tr><td><strong>Total discount:</strong></td><td>&pound;<?=$discount?></td></tr>
			<tr><td><strong>Gross Value of all Sales:</strong></td><td>&pound;<?=$grossSaleValue-$discount?></td></tr>
		</table>

	
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="salelist">
				<thead>
					<tr>
						<th>Sale ID</th>
						<th>Date</th>
						<th>Store</th>
                        <th>Cashier</th>
						<th>Item ID</th>
						<th>Item Name</th>
						<th>Discount(%)</th>
						<th>Gross Value (£)</th>
						<th>Net Value (£)</th>
					</tr>

				</thead>
				<tbody>
					<?php print $saleHtml; ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Sale ID</th>
						<th>Date</th>
						<th>Store</th>
                        <th>Cashier</th>
						<th>Item ID</th>
						<th>Item Name</th>
						<th>Discount(%)</th>
						<th>Gross Value (£)</th>
						<th>Net Value (£)</th>
					</tr>
				</tfoot>
			</table>
		
			<script type="text/javascript">
			$(function() {
				$('#salelist').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
			});
		</script>
	</div>
<?php	
	$page->getFooter();
?>