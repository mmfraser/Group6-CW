<?php

		// Page PHP Backend Code Begin
		include("page.php");
		include("AppClasses/db_conn.php");
		$page = new Page();
		$page->title = "Quicksilver Music | About Us";
		$page->getHeader();
		$errorMsg = null;
		$infoMsg = null;
		
?>
<div id="left">
<div class="post">
<div class="post_h">

</div>

<div class="postcontent">
<h2>About Quicksilver Music</h2>

	<p> Quicksilver Music is the UK's retailer of classical music and DVDs.</p>
    <br>
    <p> Established in 2011 through its landmark store in Princes Street in Edinburgh, and famous for its iconic 'treble-clef' trademark, years on, Quicksilver gets customers even closer to the artists and to the music they love whether in the home, on the move or via the 'live' experience. Quicksilver enables choice through 100 stores offering a specialist selection of entertainment content and through an online store at quicksilver-music.co.uk </p>

</div>
<div class="post_b">
</div>
</div>

</div>


			
<?php	
	$page->getFooter();
?>
