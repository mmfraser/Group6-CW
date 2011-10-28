<?php
	require_once('../AppClasses/App.php');
	$page = new Page();
	$page->title = "test";
	$page->getHeader();
	?>
	
	My form goes here.
	
	
<?php	$page->getFooter();
	
	
?>