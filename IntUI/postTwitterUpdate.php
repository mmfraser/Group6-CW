<?php
	require_once(dirname(__FILE__).'/../App.php');
	require (dirname(__FILE__).'/../TwitterPlugin/tmhOAuth.php');
	require (dirname(__FILE__).'/../TwitterPlugin/tmhUtilities.php');
		$tmhOAuth = new tmhOAuth(array(
		  'consumer_key'    => 'oxS4WMbiCfBDFnfmLPVOQ',
		  'consumer_secret' => 'nW1wydCLftCZILhFDoiWFSOAwCKpm4BLEmz3IrxU1Y',
		  'user_token'      => '517930195-vwSDCEY5hjWArb2AgZ1I7EiSKa18onShTVMy1lIP',
		  'user_secret'     => 'ubSUiY9J7mXhSZYvf11OgAobbxHtbvj39VOQtnkrag',
		));
		if($argv[1] == "WEEKLY_UPDATE") {
			
			$todaysDate = date("Y-m-d");
			$startDate = strtotime('-1 week', strtotime($todaysDate));
			$startDate = date("Y-m-d", $startDate);
			
			$lastWeek = App::getDB()->getDataRow("SELECT COUNT(*) as noSales, PRODUCT_NAME, PRODUCT_RELEASE_DATE, PRODUCT_PRICE FROM sales_view_v2 WHERE SALE_DATE between '".date("Y-m-d", strtotime('-1 week', strtotime($startDate)))."' and '".$startDate."' GROUP BY PRODUCT_NAME ORDER BY noSales DESC LIMIT 1");
			
			$thisWeek = App::getDB()->getDataRow("SELECT COUNT(*) as noSales, PRODUCT_NAME, PRODUCT_RELEASE_DATE, PRODUCT_PRICE FROM sales_view_v2 WHERE SALE_DATE between '".$startDate."' and '".$todaysDate."' GROUP BY PRODUCT_NAME ORDER BY noSales DESC LIMIT 1");
			
			if($thisWeek == null) {
				print "no data to process";
				die();
			}
			
			if($lastWeek['PRODUCT_NAME'] == $thisWeek['PRODUCT_NAME']) {
				// Check previous weeks.
				$noWeeks = 0;
				while($lastWeek['PRODUCT_NAME'] == $thisWeek['PRODUCT_NAME']) {
					$noWeeks++;
					$oldStartDate = $startDate;
					$startDate = strtotime('-1 week', strtotime($startDate));
					$startDate = date("Y-m-d", $startDate);
					
					$lastWeek = App::getDB()->getDataRow("SELECT COUNT(*) as noSales, PRODUCT_NAME, PRODUCT_RELEASE_DATE, PRODUCT_PRICE FROM sales_view_v2 WHERE SALE_DATE between '".$startDate."' and '".$oldStartDate."' GROUP BY PRODUCT_NAME ORDER BY noSales DESC LIMIT 1");
			}
				
				$message = $thisWeek['PRODUCT_NAME']." has been at number one for ".$noWeeks." weeks, buy it now for ".$thisWeek['PRODUCT_PRICE']."!";
			} else {
				$message = "NEW NUMBER ONE! ".$thisWeek['PRODUCT_NAME']." can be purchased in stores for ".$thisWeek['PRODUCT_PRICE']."!";
			}		
		}

		$code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
		  'status' => $message
		));  
		if ($code == 200) {
		  print tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
		} else {
			// error
		 print tmhUtilities::pr($tmhOAuth->response['response']);
		}

	// Page PHP Backend Code End

?>
