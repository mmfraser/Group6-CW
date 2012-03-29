<?php
	
	$last_sun = date("Y-m-d", strtotime("last Sunday"));
	$second_last_sun = date("Y-m-d", strtotime("last Sunday-1 week")); 
	$date2 = date();
	$top10output = '' . PHP_EOL;
	$top10output .= '<div id="sidebar">' . PHP_EOL;;
	$top10output .= '	<div class="top-10-content">' . PHP_EOL;;
	$top10output .= '	<h3>This Week\'s Top 10</h3>' . PHP_EOL;;
	$top10output .= '		<ol class="top-10-list">' . PHP_EOL;
	$top10 = App::getDB()->getArrayFromDB("SELECT distinct s.`itemId`, p.name, p.productId, a.bandName, a.artistId, a.forename, a.surname FROM `salesdata` s, artist a, product p WHERE date < '".$last_sun."' AND date > '".$second_last_sun."' AND s.itemId = p.productId AND p.artistId = a.ArtistId ORDER BY itemID desc limit 10");
	foreach($top10 as $arr) {	
		$top10output .= '		<li class="top-10-item">' . PHP_EOL;
		$top10output .= '			<div class="top-10-album"><a href="product.php?id='.$arr['productId'].'">'.$arr['name'].'</a></div>' . PHP_EOL;
		$top10output .= '			<div class="top-10-artist"><a href="artist.php?id='.$arr['artistId'].'">'.$arr['bandName'].'</a></div>' . PHP_EOL;
		$top10output .= '		</li>' . PHP_EOL;
	
	}
	$top10output .= '</ol>' . PHP_EOL;
	$top10output .= '	</div>' . PHP_EOL;
	$top10output .= '</div>' . PHP_EOL;
	echo $top10output;
?>