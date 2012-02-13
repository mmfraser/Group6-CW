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
    	Some Content
    </div>
</div>

<!-- START OF TOP 10 BOX -->
<div class="container-300">
	<h2 class="header-300-red item-heading">Some links, perhaps.</h2>
    <div class="content-300-red">
        <p>&nbsp;</p>
        <p>Some links could go here because I forgot to make boxes that go across the whole page. Might do it if I can be arsed.</p>
    </div>
</div>
<!-- END OF TOP-10 BOX -->

<div class="container-300">
	<h2 class="header-300-blue item-heading">New Releases</h2>
    <div class="content-300-blue">
    	MYSQL:<br />
        SELECT * FROM product ORDER BY releaseDate DESC LIMIT 5;
    </div>
</div>

<span class="spacer">
	&nbsp;
</span>

<div class="container-300">
	<h2 class="header-300-red item-heading">Sample Links</h2>
    <div class="content-300-red">
    	<h3>For demonstration purposes.</h3>
        <p><a href="artist.php?artistId=81">Sample Artist Page</a></p>
        <p><a href="store.php?storeId=5">Sample Store Page</a></p>
    </div>
</div>

<?php	
	$page->getFooter();
?>