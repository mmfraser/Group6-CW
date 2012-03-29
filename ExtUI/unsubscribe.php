<?php
		$email = $_REQUEST['email'];
		if (!$email){
			header("Location: index.php");
		}
		// Page PHP Backend Code Begin
		require_once('../App.php');
		require_once('page.php');
		require_once('../AppClasses/Customer.php');
		$page = new Page();
		$page->title = "Quicksilver Music ";
		$page->getHeader();
		$errorMsg = null;
		$infoMsg = null;
		
		$sql = "DELETE FROM customer WHERE emailAddress = '".$email."'";

		$createCustomer = App::getDB()->execute($sql);
		
?>
<div id="left">


<!-- START OF POST BLOCK -->
<div class="post">
	<div class="post_h"></div>
	<div class="postcontent">
		<h2>Quicksilver Music Newsletter</h2>
		<h3>You have successfully been removed from our mailing list.</h3>
        <p>Should you wish to re-subscribe to our newsletter, use the link at the bottom of the page.</p>
	</div>
	<div class="post_b"></div>
</div>
<!-- END OF POST BLOCK -->

</div>

<?php
	$page->getFooter();
?>
