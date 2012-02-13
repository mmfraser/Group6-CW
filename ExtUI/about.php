<?php

	// Page PHP Backend Code Begin
		include("page.php");
		include("AppClasses/db_conn.php");
		$page = new Page();
		$page->title = "Quicksilver Music";
		$page->getHeader();
		$errorMsg = null;
		$infoMsg = null;
		
?>

<div class="container-615">
	<h2 class="header-615-blue item-heading">About Quicksilver Music</h2>
    <div class="content-615-blue">
    	Quicksilver Music is a bunch of shops that sell music
    </div>
</div>

<span class="box-spacer">
	&nbsp;
</span>

<div class="container-300">
	<h2 class="header-300-red item-heading">Some links, perhaps.</h2>
    <div class="content-300-red">
        <p>&nbsp;</p>
        <p>Perhaps some links could go here since I forgot to make containers that span the whole page</p>
    </div>
</div>




<?php	
	$page->getFooter();
?>