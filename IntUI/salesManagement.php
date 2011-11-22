<?php
	require_once('../App.php');
	require_once('../AppClasses/Product.php');
	require_once('../AppClasses/Sale.php');
	require_once('../AppClasses/Genre.php');
	require_once('../AppClasses/store.php');
	require_once('../AppClasses/Product.php');
	
	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Sales Management";
		$page->getHeader();
		
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=productManagement.php">log in</a>.');
		}
	
		// Get the artist list and populate table.
	
		$allArtist = App::getDB()->getArrayFromDB("SELECT artistId FROM artist");
		
		$bandHtml = "";
		foreach($allArtist as $arr) {
			$selected = "";
			$artist = new Artist();
			$artist->populateId($arr['artistId']);
			if($_POST['band'] == $artist->getArtistId()) {
				$selected = "selected";
			}
			$bandHtml .= '<option value="'.$artist->getArtistId().'" '.$selected.'>'.$artist->bandName.'</option>';
		}
		
		$allGenre = App::getDB()->getArrayFromDB("SELECT genreId FROM genre");
		
		$genreHtml = "";
		foreach($allGenre as $arr) {
			$selected = "";
			$genre = new Genre();
			$genre->populateId($arr['genreId']);
			if($_POST['genre']  == $genre->getGenreId()) {
				$selected = "selected";
			}
			$genreHtml .= '<option value="'.$genre->getGenreId().'" '.$selected.'>'.$genre->genreName.'</option>';
		}
		
		$allStore = App::getDB()->getArrayFromDB("SELECT storeId FROM store");
		
		$storeHtml = "";
		foreach($allStore as $arr) {
			$selected = "";
			$store = new Store();
			$store->populateId($arr['storeId']);
			if($_POST['store'] == $store->getStoreId()) {
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

	if(isset($_GET['do']) && $_GET['do'] == "search") {
		$startDate = $_POST['startDate'];
		$endDate = $_POST['endDate'];
		$customer = $_POST['customer'];
		$storeId = $_POST['store'];
		$artistId = $_POST['band'];
		$genreId = $_POST['genre'];
		
		$filters = array();
		$joins = array();
		
		if($startDate == null || $endDate == null) 
				$page->error("You must complete the Start Date and End Date fields.");
		else  {
			$filters[] = "(date between '" . $startDate . "' and '".$endDate."')";
		
			if($customer != null) 
				$filters[] = "(customerEmail = '" . $customer . "')";
			if($storeId != null) 
				$filters[] = "(storeId = '" . $storeId . "')";
			if($artistId != null) {
				$filters[] = "(artistId = '" . $artistId . "')";
				$joins[] = "LEFT JOIN product ON itemId = productId";
			}
			if($genreId != null) 
				$filters[] = "(genreId = '" . $genreId . "')";

			$query = "SELECT saleId FROM salesdata ";
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
			
			print $query . " " . $joinString . " " . $filterString;
			$sales = App::getDB()->getArrayFromDB($query . " " . $joinString . " " . $filterString);
			
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
				$saleHtml .= '	<td>'.$sale->customerEmail.'</td>'.PHP_EOL;
				$saleHtml .= '</tr>'.PHP_EOL;
				
			}		
			
		}
	}

?>
			<h3>Search for Sale</h3>
			<form method="POST" id="addProduct" action="?do=search">
				<table>
					<tr>
						<td>Start Date:</td>
						<td><input type="text" name="startDate" size="15" id="startDate" value="<?php print $_POST['startDate']; ?>"  /></td>
						<td><em>Required</em></td>
					</tr>
					<tr>
						<td>End Date:</td>
						<td><input type="text" name="endDate" size="15" id="endDate" value="<?php print $_POST['endDate']; ?>" /></td>
						<td><em>Required</em></td>
					</tr>
					<tr>
						<td>Customer:</td>
						<td><input type="text" name="customer" size="15" id="customer" value="<?php print $_POST['customer']; ?>" /></td>
						<td></td>
					</tr>
					<tr>
						<td>Store:</td>
						<td><select name="store">
								<option value=""></option>
								<?php print $storeHtml; ?>
							</select></td>
						<td></td>
					</tr>
					<tr>
						<td>Artist/Band:</td>
						<td>
							<select name="band">
								<option value=""></option>
								<?php print $bandHtml; ?>
							</select>
						</td>
						<td></td>
					</tr>
					<tr>
						<td>Genre:</td>
						<td>
							<select name="genre">
								<option value=""></option>
								<?php print $genreHtml; ?>
							</select>
						</td>
						<td></td>
					</tr>
					<tr>
					<td colspan="3" style="text-align:center;"><input type="submit" value="Search" class="submit-button" /></td>
					</tr>
				</table>
			</form>
		
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="salelist">
				<thead>
					<tr>
						<th>Sale ID</th>
						<th>Date</th>
						<th>Store</th>
                        <th>Cashier</th>
						<th>Item ID</th>
						<th>Item Name</th>
						<th>Discount</th>
						<th>Customer</th>
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
						<th>Discount</th>
						<th>Customer</th>
					</tr>
				</tfoot>
			</table>
		
			<script type="text/javascript">
			$(function() {
				var availableTags = [<?php print $customersList ?>];
				$(".submit-button").button();
				
				$('#salelist').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
				
				$(".options a").button();
				
				$('#productList').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
				
				$( "#startDate" ).datepicker({  dateFormat: 'yy-mm-dd',  yearRange: '1900:' + new Date().getFullYear(), maxDate: '0', changeYear: true, changeMonth: true, showOn: 'button'   });
				$( "#endDate" ).datepicker({  dateFormat: 'yy-mm-dd',  yearRange: '1900:' + new Date().getFullYear(), maxDate: '0', changeYear: true, changeMonth: true, showOn: 'button'   });
				
				$( "#customer" ).autocomplete({
					source: availableTags
				});
			});
		</script>
<?php	
	$page->getFooter();
?>