<?php

	// Page PHP Backend Code Begin
		include("page.php");
		$page = new Page();
		$page->title = "Quicksilver Music";
		$page->getHeader();
		$errorMsg = null;
		$infoMsg = null;
		
		$id = $_REQUEST['artistId'];
		$az = $_REQUEST['az'];
		$mode = "";
		////////////////////////////////////////////////////////
		
		include("AppClasses/db_conn.php");
		dbConnect("0", "00");
		dbSelect("sales");
		
		if($id){
			$mode = "displayArtist";
			// '80', 'Carl', 'Orff', 'http://en.wikipedia.org/wiki/Carl_Orff', '1985-07-29', 'German', 'Carl Orff'
			$artist = mysql_fetch_array(mysql_query("SELECT * FROM artist WHERE artistId = '".$id."'"));
			
			$artistName = $artist['bandName'];
			
			$filename = 'images/artists/'.$id.'.jpg';
			if (file_exists($filename)) {
				$imgSrc = $filename;
			} else {
				$imgSrc = "images/artists/placeholder.gif";
			}
		} elseif($az){
			$mode = "listArtists";
			$letters = mysql_query("SELECT artistId, forename, surname, bandName FROM artist WHERE bandName LIKE '".$az."%'");
		} else {
			$mode = "chooseLetter";
		}

		
		
	switch($mode){
		case "displayArtist" : 
			echo '	<div class="container-615">
					<h2 class="header-615-red item-heading">'.$artistName.'</h2>
					<div class="content-615-red">
					  <h3>About '.$artistName.'</h3>
						<table id="artist-table">
						  <tr>
							<td rowspan="3"><img src="'.$imgSrc.'" alt="'.$artistName.'"/></td>
							<td>Nationality: '.$artist['nationality'].'</td>
						  </tr>
						  <tr>
							<td>Date of Birth: '.$artist['dob'].'</td>
						  </tr>
						  <tr>
							<td><a href="'.$artist['websiteUrl'].'">Website</a></td>
						  </tr>
						</table>
				
					</div>
				</div>';
				break;
				
		case "listArtists" : 
			$list = '	<div class="container-615">
					<h2 class="header-615-red item-heading">Find an Artist</h2>
					<div class="content-615-red">
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
						<span>&nbsp;</span>
						<h3>Artists Beginning with \''.ucwords($az).'\'</h3>
						<span>&nbsp;</span>
						';
						
						while($artist = mysql_fetch_array($letters)){
							$list .= '<p><a href="artist.php?artistId='.$artist['artistId'].'">'.$artist['bandName'].'</a></p>';
						
						}
				$list .= '</div>
						</div>';
						echo $list;
				break;
				
				case "chooseLetter" :
					echo '
					<div class="container-615">
					<h2 class="header-615-red item-heading">Find an Artist</h2>
					<div class="content-615-red">
					<h3>Select a letter to find an artist</h3>
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
					  </div>
						</div>
					';
					break;

}

?>

<!-- START OF TOP 10 BOX -->
<div class="top-10-container">
	<h2>Top 10 Albums</h2>
    <div class="top-10">
        <p>&nbsp;</p>
        
        <?php include("AppClasses/top10.php"); ?>
    	
    </div>
</div>
<!-- END OF TOP-10 BOX -->

<?php	
	$page->getFooter();
?>