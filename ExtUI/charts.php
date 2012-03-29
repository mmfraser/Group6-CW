<?php
		// Page PHP Backend Code Begin
		require_once("page.php");
		require_once("../App.php");
		//require_once('../AppClasses/Genre.php');
		$page = new Page();
		$page->title = "Quicksilver Music | Charts";
		$page->getHeader();
		$errorMsg = null;
		$infoMsg = null;
		
		$from = $_REQUEST['fromDate'];
		$to = $_REQUEST['toDate'];
		$genreChoice = $_REQUEST['genre'];
		$allGenre = App::getDB()->getArrayFromDB("SELECT genreId, genreName FROM genre");
		/*
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
		*/
		$genreHtml = "";
		foreach($allGenre as $arr) {
			/*$selected = "";
			$genre = new Genre();
			$genre->populateId($arr['genreId']);*/
			if($_POST['genre']  == $$arr['genreId']) {
				$selected = "selected";
			}
			$genreHtml .= '<option value="'.$arr['genreId'].'" '.$selected.'>'.$arr['genreName'].'</option>';
		}
		
		if ($from && $to){
			if ($genreChoice == "all"){
				$chartSql = "SELECT distinct s.`itemId`, g.genreName, p.name, p.productId, a.bandName, a.artistId, a.forename, a.surname FROM genre g, `salesdata` s, artist a, product p WHERE s.date < '".$to."' AND s.date > '".$from."' AND s.itemId = p.productId AND p.artistId = a.ArtistId AND p.genreId = g.genreId ORDER BY itemID desc limit 10";	
			} else {
				$chartSql = "SELECT distinct s.`itemId`, g.genreName, p.name, p.productId, a.bandName, a.artistId, a.forename, a.surname FROM genre g, `salesdata` s, artist a, product p WHERE s.date < '".$to."' AND s.date > '".$from."' AND s.itemId = p.productId AND p.artistId = a.ArtistId AND p.genreId = g.genreId AND g.genreId = '".$genreChoice."' ORDER BY itemID desc limit 10";
			}
		} else {
			// Display a default chart.
		
			// Set the default date ranges.
			$to = date("Y-m-d", strtotime("last Sunday"));
			$from = date("Y-m-d", strtotime("last Sunday-1 week")); 
			$chartSql = "SELECT distinct s.`itemId`, g.genreName, p.name, p.productId, a.bandName, a.artistId, a.forename, a.surname FROM genre g, `salesdata` s, artist a, product p WHERE s.date < '".$to."' AND s.date > '".$from."' AND s.itemId = p.productId AND p.artistId = a.ArtistId AND p.genreId = g.genreId ORDER BY itemID desc limit 10";
			$genreName = "all";
		}
		
		// Display the top sales of either the default sql statement or the one built by the user's specification.
		
		$chartBuilder = App::getDB()->getArrayFromDB($chartSql);
			$chartHtml = "";
			$positon = 0;
			foreach($chartBuilder as $arr){
				$position++; // Start at number 1 and count up to 10 (database selects max 10 records so pos wont be > 10).
				$artist = $arr['bandName'];
				$title = $arr['name'];
				$productId = $arr['productId'];
				$artistId = $arr['artistId'];
				$genreName = $arr['genreName'];
				if ($position % 2) {
					$oddeven = "Odd";
				} else {
					$oddeven = "Even";
				}
				$number1Colour = "";
				if ($position == 1){
					$oddeven = "Num1";
					$number1Colour = " chartBuilderNumber1";
				}
				$filename = '../Images/Albums/'.$productId.'.jpg';
				if (file_exists($filename)) {
					$imgSrc = $filename;
				} else {
					$imgSrc = "../Images/Albums/cd.jpg";
				}
				$chartHtml .= '<tr class="chartBuilder'.$oddeven.'Row">' . PHP_EOL;
				$chartHtml .= '		<td class="chartBuilderPosition'.$number1Colour.'">'.$position.'</td>' . PHP_EOL;
				$chartHtml .= '		<td class="chartBuilderPic"><a href="product.php?id='.$productId.'"><img src="'.$imgSrc.'" alt="" /></a></td>' . PHP_EOL;
				$chartHtml .= '		<td class="chartBuilderInfo">' . PHP_EOL;
				$chartHtml .= '			<div class="chartBuilderTitle"><a href="product.php?id='.$productId.'">'.$title.'</a></div>' . PHP_EOL;
				$chartHtml .= '			<div class="chartBuilderArtist"><a href="artist.php?id='.$artistId.'">'.$artist.'</a></div>' . PHP_EOL;
				$chartHtml .= '		</td>' . PHP_EOL;
				$chartHtml .= '		<td>&nbsp;</td>' . PHP_EOL;
				$chartHtml .= '</tr>' . PHP_EOL;
			}
			if ($position == 0){
				$chartHtml = "<p>Sorry. There were no sales of ".$genreName." in that period.</p>";
			}
			
			if (!$fromDate && !$toDate && !$genreChoice){
				$genreName = "all";
			}
			if ($genreChoice == "all"){
				$genreName = "all";
			}
		
?>
<div id="left">
<!-- START OF POST CONTENT -->
<div class="post">
<div class="post_h"></div>
<div class="postcontent">
<h2>The Official Classical Charts</h2>
  <h3>Build your own chart using the menu on the right.</h3>
  <h3>Showing <span class="customContent"><?=ucfirst($genreName)?></span> sales from <span class="customContent"><?=$from?></span> to <span class="customContent"><?=$to?></span>.</h3>
  <div class="chartBuilderContent">
  <table class="chartBuilderTable">
	<?=$chartHtml?>
 <!-- <tr class="chartBuilderOddRow">
    <td class="chartBuilderPosition">3</td>
    <td class="chartBuilderInfo">pic</td>
    <td class="chartBuilderInfo">
    	<div class="chartBuilderTitle"><a href="product.php?id=81">SongTitle</a></div>
        <div class="chartBuilderArtist"><a href="artist.php?id=81">SongArtist</a></div>
    </td>
    <td>&nbsp;</td>
  </tr>-->
  
</table>
  </div>
</div>
<div class="post_b"></div></div>
<!-- END OF POST CONTENT-->

</div>
<div id="sidebar">

<h3>Build a Chart</h3>
<p>Dates are formatted YYYY-MM-DD</p>
<div>
    <form method="post" action="?do=search" onSubmit="return checkForm()">
    <table>
        <tr>
            <td>From</td>
            <td><input type="text" name="fromDate" size="10" id="fromDate" value="<?=$from?>" readonly="readonly" required="required" /></td>
        </tr>
        <tr>
            <td>To</td>
            <td><input type="text" name="toDate" size="10" id="toDate" value="<?=$to?>" readonly="readonly" required="required" /></td>
        </tr>
        <tr>
            <td>Genre</td>
            <td>
                <select name="genre" id="genre">
                    <option value="all">All genres</option>
                    <?php print $genreHtml; ?>
                </select>	
            </td>
        </tr>
        <tr>
            <td colspan="2"><div class="warning" id="formWarningMessage">&nbsp;</div></td>
        </tr>
        <tr>
        <td colspan="2" style="text-align:center;"><input type="submit" value="Submit" class="submit-button" /></td>
        </tr>
    </table>
</form>
</div>



</div>


		<script type="text/javascript">
			<!--
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
				
				$( "#fromDate" ).datepicker({  dateFormat: 'yy-mm-dd',  yearRange: '1900:' + new Date().getFullYear(), maxDate: '0', changeYear: true, changeMonth: true, showOn: 'button'   });
				$( "#toDate" ).datepicker({  dateFormat: 'yy-mm-dd',  yearRange: '1900:' + new Date().getFullYear(), maxDate: '0', changeYear: true, changeMonth: true, showOn: 'button'   });
				
				$( "#customer" ).autocomplete({
					source: availableTags
				});
			});
			-->
		</script>
        
        <script type="text/javascript">
		<!--
			function checkForm(){
				var message = document.getElementById('formWarningMessage');
				var from = document.getElementById('fromDate').value;
				var to = document.getElementById('toDate').value;
				if (from == "" || to == ""){
					message.innerHTML = "Please select a date range.";
					return false;
				}
				from = from.split("-");
				to = to.split("-");
				var fromDate = new Date();
				var toDate = new Date();
				fromDate.setFullYear(from[0],from[1],from[2]);
				toDate.setFullYear(to[0],to[1],to[2]);
				
				if (fromDate >= toDate){
					message.innerHTML = "Please select a 'from' date that is before the 'to' date.";
					return false;
				}
			}
			-->
		</script>
        

<?php	
	$page->getFooter();
?>
