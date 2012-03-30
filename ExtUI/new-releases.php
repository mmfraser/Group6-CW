<?
	setlocale(LC_MONETARY, 'en_GB');
	$newReleases = '';
	$newReleases .= '<table>' . PHP_EOL;
	$newReleases .= '	<tr>' . PHP_EOL;
	
	// Select the three most recent entries into the product table.
	$release = App::getDB()->getArrayFromDB("SELECT distinct s.`itemId`, p.name, p.productId, p.price, a.bandName, a.artistId, a.forename, a.surname FROM `salesdata` s, artist a, product p WHERE s.itemId = p.productId AND p.artistId = a.ArtistId ORDER BY p.releaseDate desc limit 3");
	foreach($release as $arr) {
		$filename = '../Images/Albums/'.$arr['productId'].'.jpg';
		if (file_exists($filename)) {
			$imgSrc = $filename;
		} else {
			$imgSrc = "../Images/Albums/cd.jpg";
		}
		$newReleases .= '		<td class="new-release">' . PHP_EOL;
		$newReleases .= '		<a href="product.php?id='.$arr['productId'].'"><img src="'.$imgSrc.'" /></a>' . PHP_EOL;
		$newReleases .= '          <div class="albumBox">' . PHP_EOL;
		$newReleases .= '              <a href="product.php?id='.$arr['productId'].'"><div class="albumTitle">'.$arr['name'].'</div></a>' . PHP_EOL;
		$newReleases .= '              <a href="artist.php?id='.$arr['artistId'].'"><div class="artistName">'.$arr['bandName'].'</a></div>' . PHP_EOL;
		$newReleases .= '              <div class="albumBoxPrice">&pound;'.$arr['price'].'</div>' . PHP_EOL;
		$newReleases .= '          </div>' . PHP_EOL;
		$newReleases .= '		</td>' . PHP_EOL;
	}
	$newReleases .= '	</tr>' . PHP_EOL;
	$newReleases .= '</table>' . PHP_EOL;
	
	echo $newReleases;

?>