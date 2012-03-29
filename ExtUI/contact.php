<?php

		// Page PHP Backend Code Begin
		include("../App.php");
		include("page.php");
		$page = new Page();
		$page->title = "Quicksilver Music | Contact Us";
		$page->getHeader();
		$errorMsg = null;
		$infoMsg = null;
		
?>
<div id="left">


<!-- START OF POST BLOCK -->
<div class="post">
	<div class="post_h"></div>
	<div class="postcontent">
		<h2>Contact Quicksilver Music</h2>
		<p>
            <b>Email Us: </b>
            <a href="mailto:customer-services@quicksilver-music.co.uk">customer-services@quicksilver-music.co.uk</a>
            <br>
            <br>
            <b>Telephone Us: </b>
            0845 1234567
            <br>
            <br>
            <b>Head Office:</b>
            <br>
            100 Princes Street
            <br>
            Edinburgh
            <br>
            EH2 4AH
        </p>
    
	</div>
	<div class="post_b"></div>
</div>
<!-- END OF POST BLOCK -->

</div>


<?php	
	$page->getFooter();
?>
