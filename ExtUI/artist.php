<?php
		// Page PHP Backend Code Begin
		require_once('../App.php');
		require_once('../AppClasses/Artist.php');
		//require_once('../AppClasses/Product.php');
		require_once('page.php');
		$page = new Page();
		$page->title = "Quicksilver Music | Artists";
		$page->getHeader();
		$errorMsg = null;
		$infoMsg = null;
		
		setlocale(LC_MONETARY, 'en_GB');
		
		$id = $_REQUEST['id']; // If an artist Id has been specified. 
		$az = $_REQUEST['az']; // If the user is searching by letter.
		$q = $_REQUEST['q']; // If the user has used the search facility.
		
		// Initilise the output strings.
		$out = '';
		$albumHtml = '';
		$artistHtml = '';
		$searchOutput = '';
		$azList = '';
		
		if ($q){ // If the user has entered search data through the form.
			/*$searchOutput .= "<div class=\"post\">" . PHP_EOL;
			$searchOutput .= "	<div class=\"post_h\"></div>" . PHP_EOL;
			$searchOutput .= "	<div class=\"postcontent\">" . PHP_EOL;
			$searchOutput .= "		<h2>Search Results</h2>" . PHP_EOL;*/
			$searchResults = App::getDB()->getArrayFromDB("SELECT artistId, forename, surname, bandName FROM artist WHERE bandName like '%".$q."%' order by surname");
			$i = 0;
			foreach($searchResults as $arr) {
				$artist = new Artist();
				$artist->populateId($arr['artistId']);
				$filename = '../Images/Artists/'.$artist->getArtistId().'.jpg';
				if (file_exists($filename)) {
					$imgSrc = $filename;
				} else {
					$imgSrc = "../Images/Albums/cd.jpg";
				}
				$searchOutput .= '<div class="searchResult"><p><a href="artist.php?id='.$artist->getArtistId().'" class="searchResults"><img src="'.$imgSrc.'" alt="" /> '.$artist->bandName.'</a></p></div>' . PHP_EOL;
				$i++;
			}
			$searchOutput .= '<p>Your search for "'.$q.'" returned '.$i.' results.</p>' . PHP_EOL;
			/*$searchOutput .= "	</div>" . PHP_EOL;
			$searchOutput .= "	<div class=\"post_b\"></div>" . PHP_EOL;
			$searchOutput .= "</div>" . PHP_EOL;*/			
		} else if($id){ // If an artist has been chosen.
			$mode = "displayArtist";
			$artistHtml = "";
			$artist = new Artist();
			$artist->populateId($id);
			$albumHtml .= "<h3>Products available from ".$artist->bandName."</h3>";
			$albumHtml .= '<table class="productTable">' . PHP_EOL;
			$allAlbums = App::getDB()->getArrayFromDB("SELECT a.artistId, a.bandName, p.productId, p.name, p.releaseDate, p.price FROM product p, artist a WHERE a.artistId = p.artistId AND a.bandName = '".$artist->bandName."' ORDER BY p.name desc");
			$albumCount = 0;
			foreach($allAlbums as $arr) {
				$albumCount++;
				$productId = $arr['productId'];
				$productName = $arr['name'];
				$price = $arr['price'];
				$filename = '../Images/Albums/'.$productId.'.jpg';
				if (file_exists($filename)) {
					$imgSrc = $filename;
				} else {
					$imgSrc = "../Images/Artists/placeholder.gif";
				}
				$albumHtml .= '<tr class="productRow"><td class="productRowImg" style="width:100px;"><a href="product.php?id='.$productId.'"><img src="'.$imgSrc.'" style="max-width:100px; max-height:100px;" alt="" /></a></td><td><a href="product.php?id='.$productId.'">'.$productName.'</a><div>Available in store now for '.money_format("%n", $price).'</div></td></tr>';
			}
			
			$albumHtml .= '</table>' . PHP_EOL;
			
			if ($albumCount == 0)
				$albumHtml = ""; // If the artist has no albums, clear the header and empty table.
				
			$filename = '../Images/Artists/'.$id.'.jpg';
			if (file_exists($filename)) {
				$imgSrc = $filename;
			} else {
				$imgSrc = "../Images/Artists/placeholder.gif";
			}
			
			// Output different 'terms' for bands and solo artists.
			if ($artist->bandName != $artist->forename." ".$artist->surname){ // If this is a band
				$artistHtml .= "<div class=\"post\">" . PHP_EOL;
				$artistHtml .= "	<div class=\"post_h\"></div>" . PHP_EOL;
				$artistHtml .= "	<div class=\"postcontent\">" . PHP_EOL;
				$artistHtml .= "		<h2>About ".$artist->bandName."</h2>" . PHP_EOL;
				$artistHtml .= "		<img src=\"".$imgSrc."\" alt=\"Image of ".$artist->bandName."\"\" /><br />" . PHP_EOL;
				$artistHtml .= "		<p>".$artist->bandName." are ".$artist->nationality.". You can find out more about them at ".$artist->websiteUrl."</p>" .PHP_EOL;
				$artistHtml .= "		<div>".$albumHtml."</div>" .PHP_EOL;
				$artistHtml .= "	</div>" . PHP_EOL;
				$artistHtml .= "	<div class=\"post_b\"></div>" . PHP_EOL;
				$artistHtml .= "</div>" . PHP_EOL;
			} else { // If it is a solo artist...
				$artistHtml .= "<div class=\"post\">" . PHP_EOL;
				$artistHtml .= "	<div class=\"post_h\"></div>" . PHP_EOL;
				$artistHtml .= "	<div class=\"postcontent\">" . PHP_EOL;
				$artistHtml .= "		<h2>About ".$artist->bandName."</h2>" . PHP_EOL;
				$artistHtml .= "		<img src=\"".$imgSrc."\" alt=\"Image of ".$artist->bandName."\" /><br />" . PHP_EOL;
				$artistHtml .= "		<p>".$artist->bandName." is ".$artist->nationality.". You can find out more information <a href=\"".$artist->websiteUrl."\">here</a></p>" .PHP_EOL;
				$artistHtml .= "		<div>".$albumHtml."</div>" .PHP_EOL;
				$artistHtml .= "	</div>" . PHP_EOL;
				$artistHtml .= "	<div class=\"post_b\"></div>" . PHP_EOL;
				$artistHtml .= "</div>" . PHP_EOL;	
			}
			
		}
		
		
		if($az){ // If a letter has been chosen.
			
			
			$azArtist = App::getDB()->getArrayFromDB("SELECT artistId, forename, surname, bandName FROM artist order by surname");
			
			foreach($azArtist as $arr) {
				$artist = new Artist();
				$artist->populateId($arr['artistId']);
				$filename = '../Images/Artists/'.$artist->getArtistId().'.jpg';
				if (file_exists($filename)) {
					$imgSrc = $filename;
				} else {
					$imgSrc = "../Images/Artists/placeholder.gif";
				}
				if ((strcmp(strtoupper($artist->forename." ".$artist->surname), strtoupper($artist->bandName))) == 0){
				//if ($artists['forename']." ".$artists['surname'] == $artists['bandName']){
					// forename surname = bandname means artist is a solo artist with a name so search by surname.
					$surname = str_split($artist->surname);
					if (strtoupper($surname[0]) == strtoupper($az)){ // Search by the surname
						$azList .= '<div class="searchResult"><a href="artist.php?id='.$artist->getArtistId().'&az='.$az.'" class="searchResults"><img src="'.$imgSrc.'" alt="" /> '.$artist->bandName.'</a></div>';
						$i++;
					}
				} else {
					$band = str_split($artist->bandName);
					if (strtoupper($band[0]) == strtoupper($az)){
						$azList .= '<div class="searchResult"><a href="artist.php?id='.$artist->getArtistId().'&az='.$az.'" class="searchResults"><img src="'.$imgSrc.'" alt="" /> '.$artist->bandName.'</a></div>';
						$i++;
					}
				}
			}
			
			if ($i == 0){ // If no artists returned.
					$azList = "<p>Sorry. There are no artists beginning with '".strtoupper($az)."'</p>";
			}
			
		}
		
		
?>
<div id="left">


<!-- START OF POST BLOCK -->
<div class="post">
	<div class="post_h"></div>
	<div class="postcontent">
		<h2>Find an Artist by Surname or Group Name</h2>
        <form action="artist.php" method="get">
        	<label for="search">Search for an artist by their name: </label><input name="q" id="q" type="text" placeholder="Artist name" />
            <input type="submit" name="search" value="Search" />
        </form>
        <p>&nbsp;</p>
        <p>Choose a letter to search for artists by surname or group name.</p>
		<span><a href="artist.php?az=a">A</a> | </span>
		<span><a href="artist.php?az=b">B</a> | </span>
		<span><a href="artist.php?az=c">C</a> | </span>
		<span><a href="artist.php?az=d">D</a> | </span>
		<span><a href="artist.php?az=e">E</a> | </span>
		<span><a href="artist.php?az=f">F</a> | </span>
		<span><a href="artist.php?az=g">G</a> | </span>
		<span><a href="artist.php?az=h">H</a> | </span>
		<span><a href="artist.php?az=i">I</a> | </span>
		<span><a href="artist.php?az=j">J</a> | </span>
		<span><a href="artist.php?az=k">K</a> | </span>
		<span><a href="artist.php?az=l">L</a> | </span>
		<span><a href="artist.php?az=m">M</a> | </span>
		<span><a href="artist.php?az=n">N</a> | </span>
		<span><a href="artist.php?az=o">O</a> | </span>
		<span><a href="artist.php?az=p">P</a> | </span>
		<span><a href="artist.php?az=q">Q</a> | </span>
		<span><a href="artist.php?az=r">R</a> | </span>
		<span><a href="artist.php?az=s">S</a> | </span>
		<span><a href="artist.php?az=t">T</a> | </span>
		<span><a href="artist.php?az=u">U</a> | </span>
		<span><a href="artist.php?az=v">V</a> | </span>
		<span><a href="artist.php?az=w">W</a> | </span>
		<span><a href="artist.php?az=x">X</a> | </span>
		<span><a href="artist.php?az=y">Y</a> | </span>
		<span><a href="artist.php?az=z">Z</a></span>
       	<p>&nbsp;</p>
       <?php echo "<azList>".$azList."</azList>"; ?>
       <?php echo "<searchOutput>".$searchOutput."</SearchOutput>"; ?>
	</div>
	<div class="post_b"></div>
</div>
<!-- END OF POST BLOCK -->

<!-- START OF POST BLOCK -->
<?php echo "<artistHtml>".$artistHtml."</artistHtml>";// If the user has selected an artist, display the HTML with the details.?>
<!-- END OF POST BLOCK -->

</div>

<?php require_once("top10.php"); ?>

<?php
	$page->getFooter();
?>
