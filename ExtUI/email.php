<?php
		// Page PHP Backend Code Begin
		require_once('../App.php');
		require_once('page.php');
		require_once('../AppClasses/Customer.php');
		$page = new Page();
		$page->title = "Quicksilver Music ";
		$page->getHeader();
		$errorMsg = null;
		$infoMsg = null;
		
		$forename = ucfirst($_REQUEST['forename']);
		$surname = ucfirst($_REQUEST['surname']);
		$email = strtolower($_REQUEST['emailAddress']);
		$telephone = $_REQUEST['telephoneNumber'];
		$addressLine1 = ucwords($_REQUEST['addressLine1']);
		$addressLine2 = ucfirst($_REQUEST['addressLine2']);
		$town = ucwords($_REQUEST['town']);
		$city = ucwords($_REQUEST['city']);
		$postcode = strtoupper($_REQUEST['postcode']);
		
		$sql = "INSERT INTO customer (emailAddress, forename, surname, addressLine1, addressLine2, town, city,  postcode, telephoneNumber) VALUES (
					'".$email."', 
					'".$forename."', 
					'".$surname."', 
					'".$addressLine1."', 
					'".$addressLine2."', 
					'".$town."', 
					'".$city."', 
					'".$postcode."', 
					'".$telephoneNumber."')";

		$createCustomer = App::getDB()->execute($sql);
		
		if ($createCustomer){
			$subject = "Thank you for joining Quicksilver Music";
			$emailHtml = '<html>';
			$emailHtml .= '	<head>';
			$emailHtml .= '		<title>Thank you for joining Quicksilver Music!</title>';
			$emailHtml .= '		<style type="text/css">';
			$emailHtml .= '			<!--body{ font-family:Arial; }';
			$emailHtml .= '		</style>';
			$emailHtml .= '	</head>';
			$emailHtml .= '	<body>';
			$emailHtml .= '		<div><a href="http://www.quicksilver-music.co.uk"><img src="http://81.86.240.5/~david/project/Images/QuicksilverEmailHeader.gif" alt="Quicksilver Music" /></a></div>';
			$emailHtml .= '		<h1>Thank you for joining Quicksilver Music!</h1>';
			$emailHtml .= '		<p>Hi '.$forename.',</p>';
			$emailHtml .= '		<p>Thank you for signing up for the Quicksilver Music newsletter. We will keep you up to date with all the latest releases and deals at Quicksilver Music</p>';
			$emailHtml .= '		<p>&nbsp;</p>';
			$emailHtml .= '		<p>Click here to <a href="http://www.quicksilver-music.co.uk/unsubscribe.php?email=dt84@hw.ac.uk">unsubscribe</a>.</p>';
			$emailHtml .= '	</body>';
			$emailHtml .= '</html>';
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			// Additional headers
			$headers .= 'To: '.$forename.' '.$surname.' <'.$email.'>' . "\r\n";
			$headers .= 'From: Quicksilver Music <no-relpy@quicksilver-music.co.uk>' . "\r\n";
			mail($email, $subject, $emailHtml, $headers);
		}
?>
<div id="left">


<!-- START OF POST BLOCK -->
<div class="post">
	<div class="post_h"></div>
	<div class="postcontent">
		<h2>Quicksilver Music Newsletter</h2>
		<h3>Thank you for signing up <?=$forename?>.</h3>
        <p>If you wish to unsubscribe at any time, please click the unsubscribe link which you will find at the bottom of every email we send you.</p>
		<p><a href="index.php">Click here to return to the home page.</a></p>
	</div>
	<div class="post_b"></div>
</div>
<!-- END OF POST BLOCK -->

</div>

<?php
	$page->getFooter();
?>
