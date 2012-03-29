<?php

	// Page PHP Backend Code Begin
	include("page.php");
	include("../App.php");
	$page = new Page();
	$page->title = "Quicksilver Music";
	$page->getHeader();
	$errorMsg = null;
	$infoMsg = null;


?>
<div id="left">
<div class="post">
<div class="post_h">

</div>

<div class="postcontent">
<h2>New Releases</h2>
  <? include("new-releases.php"); ?>
</div>
<div class="post_b">
</div>
</div>

</div>

<? require_once("top10.php"); ?>

			
<?php	
	$page->getFooter();
?>
