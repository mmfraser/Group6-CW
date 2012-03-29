<?php
	$productId = $_REQUEST['id'];
	$q = $_REQUEST['q'];
	require_once("../App.php");
	// Fetch the product and its artist details from the database.
	
	
	// Page PHP Backend Code Begin
	require_once("page.php");
	$page = new Page();
	$page->title = "Quicksilver Music | Products";
	$page->getHeader();
	$errorMsg = null;
	$infoMsg = null;
	setlocale(LC_MONETARY, 'en_GB');
	
	if ($productId){
		$product = App::getDB()->getDataRow("SELECT p.name, p.productId, a.artistId, a.bandName, p.price FROM artist a, product p WHERE p.productId = '".$productId."' AND p.artistId = a.artistId");	
		// If the product is not in the DB $product will be null, if it is, go to the home page.
		$filename = '../Images/Albums/'.$productId.'.jpg';
		if (file_exists($filename)) {
			$imgSrc = $filename;
		} else {
			$imgSrc = "../Images/Albums/cd.jpg";
		}
		$productHtml = '<table class="productTable">' . PHP_EOL;
		$productHtml .= '	<tr>' . PHP_EOL;
		$productHtml .= '		<td class="productTableImage"><img src="'.$imgSrc.'" /></td>' . PHP_EOL;
		$productHtml .= '		<td>' . PHP_EOL;
		$productHtml .= '			<h3>'.$product['name'].'</h3>' . PHP_EOL;
		$productHtml .= '			<div><span class="productLabel">Artist:</span> <a href="artist.php?id='.$product['artistId'].'">'.$product['bandName'].'</a></div>' . PHP_EOL;
		$productHtml .= '			<div><span class="productLabel">Price:</span> '.money_format("%n", $product['price']).'</div>' . PHP_EOL;
		$productHtml .= '			<div><span class="productLabel">Quicksilver Item Number:</span> '.$productId.'</div>' . PHP_EOL;
		$productHtml .= '		</td>' . PHP_EOL;
		$productHtml .= '	</tr>' . PHP_EOL;
		$productHtml .= '</table>' . PHP_EOL;
	} elseif ($q){
		$searchResults = App::getDB()->getArrayFromDB("SELECT p.name, p.productId, a.bandName, a.artistId FROM product p, artist a WHERE p.artistId = a.artistId AND p.name like '%".$q."%' order by p.name");
		$i = 0;
		foreach($searchResults as $arr) {
			$filename = '../Images/Albums/'.$arr['productId'].'.jpg';
			if (file_exists($filename)) {
				$imgSrc = $filename;
			} else {
				$imgSrc = "../Images/Albums/cd.jpg";
			}
			$searchOutput .= '<div class="searchResult"><p><a href="product.php?id='.$arr['productId'].'" class="searchResults"><img src="'.$imgSrc.'" alt="" /> '.$arr['name'].' - '.$arr['bandName'].'</a></p></div>' . PHP_EOL;
			$i++;
		}
		$searchOutput .= '<p>Your search for "'.$q.'" returned '.$i.' results.</p>' . PHP_EOL;
	}
		
?>
<div id="left">

<!-- START OF POST BLOCK -->
<div class="post">
	<div class="post_h"></div>
	<div class="postcontent">
		<h2><form action="product.php" action="post"><label for="q">Find a product <input name="q" type="text" placeholder="Search" /></label><input type="submit" value="Search" /></form></h2>
		<?=$searchOutput?>
		<?=$productHtml?>
	</div>
	<div class="post_b"></div>
</div>
<!-- END OF POST BLOCK -->

<!-- START OF POST BLOCK -->
<!-- END OF POST BLOCK -->
</div>
<? include("top10.php"); ?>
<?php	
	$page->getFooter();
?>
