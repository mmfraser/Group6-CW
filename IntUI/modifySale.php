<?php
	require_once('../App.php');
	require_once('../AppClasses/Product.php');
	require_once('../AppClasses/Sale.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Sales Management - Modify Sale";
		$page->getHeader();
		
		$errorMsg = null;
		$infoMsg = null;
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=salesManagement.php">log in</a>.');
		}
		
		if(isset($_GET['saleId']) && is_numeric($_GET['saleId'])) { 
			$sale = new Sale();
			$sale->populateId($_GET['saleId']);
		} else {
			App::fatalError($page, 'You must select a sale in which to edit.  Please go back to the <a href="salesManagement.php">Sales Management</a> page.');
		}
		
		if(isset($_POST['date'])) {
			$date = $_POST['date'];
			$storeId = $_POST['storeId'];
			$cashierName = $_POST['cashierName'];
			$productId = $_POST['itemId'];
			$itemDiscount = $_POST['discount'];
			$customerEmail = $_POST['customerEmail'];
		} else {
			$date = $sale->date;
			$storeId = $sale->storeId;
			$cashierName = $sale->cashierName;
			$productId = $sale->itemId;
			$itemDiscount = $sale->itemDiscount;
			$customerEmail = $sale->customerEmail;
		}
		
		if(isset($_GET['do']) && $_GET['do'] == "updateSale" && isset($_GET['saleId'])) {
			try {
				$sale->date = $_POST['date'];
				$sale->storeId = $_POST['storeId'];
				$sale->cashierName = $_POST['cashierName'];
				$sale->itemId = $_POST['itemId'];
				$sale->itemDiscount = $_POST['discount'];
				$sale->customerEmail = $_POST['customerEmail'];
				$sale->save();
				$infoMsg = "Sale updated successfully.";	
			} catch (Exception $e) {
				$errorMsg = $e->getMessage();
			}
		} 
				
				
		$allStore = App::getDB()->getArrayFromDB("SELECT storeId FROM store");
		$storeHtml = "";
		foreach($allStore as $arr) {
			$selected = "";
			$store = new Store();
			$store->populateId($arr['storeId']);
			if($storeId == $store->getStoreId()) {
				$selected = "selected";
			}
			$storeHtml .= '<option value="'.$store->getStoreId().'" '.$selected.'>'.$store->storeName.'</option>';
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

?>

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">General</a></li>
		</ul>
		<div id="tabs-1">
			<p class="validateTips">All form fields are required.</p>
			
			<form method="POST" id="updateSale" action="?do=updateSale&amp;saleId=<?php print $_GET['saleId']; ?>">
			<table>
				<tr>
					<td><label for="date">Sale Date</label><td>
					<td><input type="text" name="date" size="15" id="releaseDate" value="<?php print $date; ?>" /></td>
				</tr>
				<tr>
					<td><label for="storeId">Store Name</label><td>
					<td><select name="storeId">
						<?php print $storeHtml; ?>
					</select></td>
				</tr>
				
				<tr>
					<td><label for="surname">Cashier Name</label><td>
					<td><input type="text" name="cashierName" size="15" value="<?php print $cashierName; ?>" /></td>
				</tr>
				
				<tr>
					<td><label for="itemId">Item ID</label><td>
					<td><input type="text" name="itemId" size="15" id="itemId" value="<?php print $productId; ?>" /></td>
				</tr>
				
				
				
				<tr>
					<td><label for="itemId">Item Discount</label><td>
					<td><input type="text" name="discount" size="15" id="discount" value="<?php print $itemDiscount; ?>" /></td>
				</tr>
				
				<tr>
					<td><label for="itemId">Customer</label><td>
					<td><input type="text" name="customerEmail" size="15" id="customer" value="<?php print $customerEmail; ?>" /></td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><input type="submit" value="Update" class="submit-button" /></td>
				</tr>
			</table>
		</form>
			
		
				<div class="ui-state-error ui-corner-all" style="<?php if($errorMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-alert" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $errorMsg; ?></span></div>
			<div class="ui-state-info ui-corner-all" style="<?php if($infoMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-info" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $infoMsg; ?></span></div>
			</form>
			<p><a href="salesManagement.php" class="back-product-mgmt">Back to Sales Management</a></p>
		</div>	
	</div>
		<script type="text/javascript">
			$(function() {
				$( "div#tabs" ).tabs({cookie:{}});
				$("input.submit-button").button();
				$("a.back-product-mgmt").button();
				$( "#releaseDate" ).datepicker({ dateFormat: 'yy-mm-dd',  yearRange: '1900:' + new Date().getFullYear(), maxDate: '0', changeYear: true, changeMonth: true, showOn: 'button'   });
				
				var availableTags = [<?php print $customersList ?>];
			
				$( "#customer" ).autocomplete({
					source: availableTags
				});
				
				
			});
		</script>

	
<?php	
	$page->getFooter();
?>