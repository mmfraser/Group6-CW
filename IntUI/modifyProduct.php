<?php
	require_once('../App.php');
	require_once('../AppClasses/Product.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Product Management - Modify Product";
		$page->getHeader();
		
		$errorMsg = null;
		$infoMsg = null;
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=userManagement.php">log in</a>.');
		}
		
		if(isset($_GET['productId']) && is_numeric($_GET['productId'])) { 
			$product = new Product();
			$product->populateId($_GET['productId']);
		} else {
			App::fatalError($page, 'You must select a product in which to edit.  Please go back to the <a href="productManagement.php">Product Management</a> page.');
		}
		
		if(isset($_POST['band'])) {
			$artistId = $_POST['band'];
			$genreId = $_POST['genre'];
			$productName = $_POST['name'];
			$releaseDate = $_POST['releaseDate'];
			$price = $_POST['price'];
		} else {
			$artistId = $product->artistId;
			$genreId = $product->genreId;
			$productName = $product->name;
			$releaseDate = $product->releaseDate;
			$price = $product->price;
		}
		
		if(isset($_GET['do']) && $_GET['do'] == "updateProduct" && isset($_GET['productId'])) {
			try {
				$product->artistId = $_POST['band'];
				$product->genreId = $_POST['genre'];
				$product->name = $_POST['name'];
				$product->releaseDate = $_POST['releaseDate'];
				$product->price = $_POST['price'];
				$product->save();
				$infoMsg = "Product updated successfully.";	
			} catch (Exception $e) {
				$errorMsg = $e->getMessage();
			}
		} 
		
		$allArtist = App::getDB()->getArrayFromDB("SELECT artistId FROM artist");
		
		$bandHtml = "";
		foreach($allArtist as $arr) {
			$selected = "";
			$artist = new Artist();
			$artist->populateId($arr['artistId']);
			if($artistId == $artist->getArtistId()) {
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
			if($genreId == $genre->getGenreId()) {
				$selected = "selected";
			}
			$genreHtml .= '<option value="'.$genre->getGenreId().'" '.$selected.'>'.$genre->genreName.'</option>';
		}
		
	// Page PHP Backend Code End

?>

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">General</a></li>
		</ul>
		<div id="tabs-1">
			<p class="validateTips">All form fields are required.</p>
			
			<form method="POST" id="addProduct" action="?do=updateProduct&amp;productId=<?php print $_GET['productId']; ?>">
			<table>
				<tr>
					<td><label for="band">Band Name</label><td>
					<td><select name="band">
						<?php print $bandHtml; ?>
					</select></td>
				</tr>
				<tr>
					<td><label for="genre">Genre</label><td>
					<td><select name="genre">
						<?php print $genreHtml; ?>
					</select></td>
				</tr>
				<tr>
					<td><label for="surname">Product Name</label><td>
					<td><input type="text" name="name" size="15" value="<?php print $productName ?>" /></td>
				</tr>
				<tr>
					<td><label for="dob">Release Date</label><td>
					<td><input type="text" name="releaseDate" size="15" id="releaseDate" value="<?php print $releaseDate ?>" /></td>
				</tr>
				<tr>
					<td><label for="price">Price (&poundGBP)</label><td>
					<td><input type="text" name="price" size="15" id="price" value="<?php print $price ?>" /></td>
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
			<p><a href="productManagement.php" class="back-product-mgmt">Back to Product Management</a></p>
		</div>	
	</div>
		<script type="text/javascript">
			$(function() {
				$( "div#tabs" ).tabs({cookie:{}});
				$("input.submit-button").button();
				$("a.back-product-mgmt").button();
				$( "#releaseDate" ).datepicker({ dateFormat: 'yy-mm-dd',  yearRange: '1900:' + new Date().getFullYear(), maxDate: '0', changeYear: true, changeMonth: true, showOn: 'button'   });
			});
		</script>

	
<?php	
	$page->getFooter();
?>