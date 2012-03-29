<?php
	
	class Page {
		public $title;
		private $headJS = "";
		
		public function getHeader() {
			$output = '<!DOCTYPE html>' . PHP_EOL;
			$output .= '<html>' . PHP_EOL;
			$output .= '	<head>' . PHP_EOL;
			$output .= '		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . PHP_EOL;
			$output .= '		<title>' . $this->title . '</title>' . PHP_EOL;
			$output .= '		<link href="style.css" rel="stylesheet" type="text/css" />' . PHP_EOL;
			$output .= '		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">' . PHP_EOL;
			$output .= '		<link type="text/css" href="../css/smoothness/jquery-ui-1.8.16.custom.css" rel="Stylesheet" />' . PHP_EOL;
			$output .= '		<link type="text/css" href="../css/datatable/demo_table_jui.css" rel="Stylesheet" />' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/jquery.cookie.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/custom.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript"></script>' . PHP_EOL;
			$output .= '	</head>' . PHP_EOL;
			$output .= '	<body onload="load()">' .PHP_EOL;
			$output .= '		<div id="wrap">' . PHP_EOL;
			$output .= 			$this->header();
			$output .= '				<div id="content">' . PHP_EOL;
			echo $output; 
		
		}
		
		private function header() { 
			// Initialise the strings for the tabs.
			$homeActive = "";
			$chartsActive = "";
			$storesActive = "";
			$artistsActive = "";
			$productsActive = "";
			
			// Depending on which page the user is on - given by the page title - make one of the page links highlighted to show the user what page/area they are on/in.
			switch($this->title){
				case "Quicksilver Music" :
					$homeActive = 'class="active"';
					break;
				case "Quicksilver Music | Charts" :
					$chartsActive = 'class="active"';
					break;
				case "Quicksilver Music | Artists" :
					$artistsActive = 'class="active"';
					break;
				case "Quicksilver Music | Stores" :
					$storesActive = 'class="active"';
					break;
				case "Quicksilver Music | Products" :
					$productsActive = 'class="active"';
					break;
			}
			// When adding a new page/section, as well as adding it to the navigation (below in the header section), also add the page's title here so one of the navigation tabs can be selected when the user is on that page.

			
			$output = '' . PHP_EOL;
			$output .= '	<div id="header">' . PHP_EOL;
			$output .= '		<div id="topnav">' . PHP_EOL;
			$output .= '			<ul>' . PHP_EOL;
			$output .= '				<li '.$homeActive.'><a href="index.php">Home</a></li>' . PHP_EOL;
			$output .= '				<li '.$chartsActive.'><a href="charts.php">Charts</a></li>' . PHP_EOL;
			$output .= '				<li '.$productsActive.'><a href="product.php">Products</a></li>' . PHP_EOL;
			$output .= '				<li '.$artistsActive.'><a href="artist.php">Artists</a></li>' . PHP_EOL;
			$output .= '				<li '.$storesActive.'><a href="stores.php">Stores</a></li>' . PHP_EOL;
			$output .= '			</ul>' . PHP_EOL;
			$output .= '		</div>' . PHP_EOL;
			$output .= '	</div>' . PHP_EOL;
			return $output;
		}
		
		
		private function footerContent() {
			$output = '' . PHP_EOL;
			$output .= '<div id="pagination">' . PHP_EOL;
			$output .= '<table style="border:0; width:100%;">' . PHP_EOL;
			$output .= '<tr><td>' . PHP_EOL;
			$output .= '	<h3>Quicksilver Music Newsletter</h3>' . PHP_EOL;
			$output .= '	<p>Sign up and we will keep you up to date with all the latest releases.</p>' . PHP_EOL;
			$output .= '	<form action="subscribe.php" method="post">' . PHP_EOL;
			$output .= '		<input name="emailAddress" type="email" placeholder="Enter your email address" required="required" />' . PHP_EOL;
			$output .= '		<input type="submit" value="Submit" />' . PHP_EOL;
			$output .= '	</form>' . PHP_EOL;
			$output .= '</td>' . PHP_EOL;
			$output .= '<td>' . PHP_EOL;
			$output .= '<div><a href="http://www.twitter.com/quicksilvrmusic">Follow us on Twitter <img src="images/twitter.gif" alt="t" /></a></div>' . PHP_EOL;
			$output .= '<p>&nbsp;</p>' . PHP_EOL;
			$output .= '<p><a href="about.php">About Us</a></p>' . PHP_EOL;
			$output .= '<p><a href="contact.php">Contact Us</a></p>' . PHP_EOL;
			$output .= '</td>' . PHP_EOL;
			$output .= '</table>' . PHP_EOL;
			$output .= '</div>' . PHP_EOL;
			
			//$output .= '<span><a href="http://www.twitter.com/QuicksilvrMusic"><img src="images/twitter.gif" alt="twitter" /></a>&nbsp;<a href="http://www.facebook.com/"><img src="images/facebook.gif" alt="facebook" /></a></span>';
			return $output;
		}
		
		public function getFooter() {
			$output .= '' .PHP_EOL;
			//$output .= '				</div>'.PHP_EOL;
			$output .= '				<div class="clear"></div>'.PHP_EOL;
			$output .= '				</div>'.PHP_EOL;
			$output .= '				<div id="pagebottom"></div>	'.PHP_EOL;
			$output .= '				<div id="footer">'.PHP_EOL;
			$output .= 					$this->footerContent();
			$output .= '				</div>'.PHP_EOL;
			$output .= '			</div>'.PHP_EOL;
			$output .= '		</body>'.PHP_EOL;
			$output .= '	</html>'.PHP_EOL;
			
			echo $output;
		}	

		public function addHeadJS($js) {
			$this->headJS .= $js;
		}
		
		public function error($err) {
			print '<div class="ui-state-error ui-corner-all">
						<span class="ui-icon ui-icon-alert" style="float:left;margin:2px 5px 0 0;"></span>
						<span>'.$err.'</span>
				   </div>';
		}
		
	}
	
?>
