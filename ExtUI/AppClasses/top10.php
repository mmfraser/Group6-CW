
<?php
	//include("db_conn.php");
	dbConnect("0", "00");
	dbSelect("sales");

	$last_sun = date("Y-m-d", strtotime("last Sunday"));
	$second_last_sun = date("Y-m-d", strtotime("last Sunday-1 week")); 
	
	$date2 = date();

	
	$sql = "SELECT distinct s.`itemId`, p.name, p.productId, a.bandName, a.artistId, a.forename, a.surname FROM `salesdata` s, artist a, product p WHERE date < '".$last_sun."' AND date > '".$second_last_sun."' AND s.itemId = p.productId AND p.artistId = a.ArtistId ORDER BY itemID desc limit 10";
	
	$result = mysql_query($sql);
	$output .= '<ol>' . PHP_EOL;
	while($row = mysql_fetch_array($result)){
		
        $output .= '    <li><div class="top-10-title"><a href="product.php?productId='.$row['productId'].'">'.$row['name'].'</a></div>' . PHP_EOL;
        $output .= '        <div class="top-10-artist"><a href="artist.php?artistId='.$row['artistId'].'">'.$row['bandName'].'</a></div>' . PHP_EOL;
        $output .= '   </li>' . PHP_EOL;

	}
	$output .= '</ol>' . PHP_EOL;
	echo $output;
?>