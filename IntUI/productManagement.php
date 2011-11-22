<?php
	require_once('../App.php');
	require_once('../AppClasses/Product.php');
	require_once('../AppClasses/Artist.php');
	require_once('../AppClasses/Genre.php');
	
	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Product Management";
		$page->getHeader();
		
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=productManagement.php">log in</a>.');
		}
	
		// Get the artist list and populate table.
		$allProducts = App::getDB()->getArrayFromDB("SELECT productId FROM product");
		$html = "";
		
		foreach($allProducts as $arr) {
			$product = new Product();
			$product->populateId($arr['productId']);
			$html .= "<tr id=\"".$arr['productId']."\">" . PHP_EOL;
			$html .= "	<td>".$product->getProductId()."</td>" . PHP_EOL;
			$html .= "	<td>".$product->artist->bandName."</td>" . PHP_EOL;
			$html .= "	<td>".$product->genre->genreName."</td>" . PHP_EOL;
			$html .= "	<td>".$product->name."</td>" . PHP_EOL;
			$html .= "	<td>".$product->releaseDate."</td>" . PHP_EOL;
			$html .= "	<td>&pound;".$product->price."</td>" . PHP_EOL;
			$html .= '	<td class="options" style=""><a href="modifyProduct.php?productId='.$arr['productId'].'" title="Modify User"><span class="ui-icon ui-icon-pencil"></span><a title="Delete Product" id="deleteArtist"><span class="ui-icon ui-icon-trash"></span></a></td>';
			$html .= "</tr>" . PHP_EOL;
		}
	
		$allArtist = App::getDB()->getArrayFromDB("SELECT artistId FROM artist");
		
		$bandHtml = "";
		foreach($allArtist as $arr) {
			$artist = new Artist();
			$artist->populateId($arr['artistId']);
			$bandHtml .= '<option value="'.$artist->getArtistId().'">'.$artist->bandName.'</option>';
		}
		
		$allGenre = App::getDB()->getArrayFromDB("SELECT genreId FROM genre");
		
		$genreHtml = "";
		foreach($allGenre as $arr) {
			$genre = new Genre();
			$genre->populateId($arr['genreId']);
			$genreHtml .= '<option value="'.$genre->getGenreId().'">'.$genre->genreName.'</option>';
		}
	
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				
				var productId;
				
				$("p.result").hide();
				$("img.spinningWheel").hide();
				$( "div#tabs" ).tabs({cookie:{}});
				
				$("a#addProduct").button().click(function() {
					$( "#product-dialog-form" ).dialog( "open" );
				});
				
				$(".options a").button();
				
				$("a#deleteArtist").button().click(function() {
					productId = $(this).parent().parent().attr("id")
					$( "#dialog-confirm-delete" ).dialog( "open" );
				});
				
				$('#productList').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
				
				$( "#releaseDate" ).datepicker({  dateFormat: 'yy-mm-dd',  yearRange: '1900:' + new Date().getFullYear(), maxDate: '0', changeYear: true, changeMonth: true, showOn: 'button'   });

				$( "#dialog-confirm-delete" ).dialog({
					autoOpen: false,
					resizable: false,
					height:170,
					modal: true,
					buttons: {
						"Delete product": function() {
							$.post( "ajaxFunctions.php?do=deleteProduct", { product: productId});
							location.reload();
						},
						Cancel: function() {
							$( this ).dialog( "close" );
						}
					}
				});
				
			$( "#product-dialog-form" ).dialog({
				autoOpen: false,
				height: 350,
				width: 410,
				modal: true,
				buttons: {
					"Create Product": function() {
						$("img.spinningWheel").show();
						var $form = $( this ),
						artistId = $form.find( 'select[name="band"]' ).val(),
						genreId = $form.find( 'select[name="genre"]' ).val(),
						releaseDate = $form.find( 'input[name="releaseDate"]' ).val(),
						name = $form.find( 'input[name="name"]' ).val(),
						price = $form.find( 'input[name="price"]' ).val()
					
						/* Send the data using post and put the results in a div */
						$.post( "ajaxFunctions.php?do=addProduct", { artistId: artistId, genreId: genreId, releaseDate: releaseDate, name: name, price: price },
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

			<p><a id="addProduct" href="#">Add New Product</a></p>
			
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="productList">
				<thead>
					<tr>
						<th>Product ID</th>
						<th>Artist</th>
						<th>Genre</th>
						<th>Name</th>
						<th>Release Date</th>
						<th>Price</th>
						<th>Options</th>
					</tr>

				</thead>
				<tbody>
					<?php print $html; ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Product ID</th>
						<th>Artist</th>
						<th>Genre</th>
						<th>Name</th>
						<th>Release Date</th>
						<th>Price</th>
						<th>Options</th>
					</tr>
				</tfoot>
			</table>
	
		
	<div id="product-dialog-form" title="Create new Product">
			<p class="validateTips">All form fields are required.</p>

			<form method="POST" id="addProduct" action="?do=addProduct">
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
					<td><input type="text" name="name" size="15" /></td>
				</tr>
				<tr>
					<td><label for="dob">Release Date</label><td>
					<td><input type="text" name="releaseDate" size="15" id="releaseDate" /></td>
				</tr>
				<tr>
					<td><label for="price">Price (&poundGBP)</label><td>
					<td><input type="text" name="price" size="15" id="price" /></td>
				</tr>
			</table>
		</form>
		<p><img src="../Images/spinningWheel.gif" class="spinningWheel" alt="Loading" /></p>
		<p class="result"><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
			<span class="result"></span></p>
		</div>
	</div>

	
	<div id="dialog-confirm-delete" title="Delete Product?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This product, as well as all things tied to this product (sales, etc.) will be deleted.  Are you sure?</p>

	
<?php	
	$page->getFooter();
?>