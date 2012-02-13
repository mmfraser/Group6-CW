<?php
	
	class Page {
		public $title;
		private $headJS = "";
		
		public function getHeader() {
			$output = '<!DOCTYPE html>' . PHP_EOL;
			$output .= '<html>' . PHP_EOL;
			$output .= '	<head>' . PHP_EOL;
			$output .= '	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . PHP_EOL;
			$output .= '		<title>' . $this->title . '</title>' . PHP_EOL;
			$output .= '	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />' . PHP_EOL;
			$output .= '	</head>' . PHP_EOL;
			$output .= '	<body onload="load()">' .PHP_EOL;
			$output .= '		<div id="container">' . PHP_EOL;
			$output .= '			<div id="mainbg">' . PHP_EOL;
			$output .= 					$this->header();
			$output .= '					<div id="main-content">' . PHP_EOL;
			echo $output; 
		
		}
		
		private function header() { 
			$output = '' . PHP_EOL;
			$output .= '<div id="header-container">' . PHP_EOL;
			$output .= '<div id="header">' . PHP_EOL;
			$output .= $this->navigation();
			$output .= '</div>' . PHP_EOL;
			$output .= '</div>' . PHP_EOL;
			return $output;
		}
		
		private function navigation() {
					$output = '<div id="nav">' .PHP_EOL;
					$output .=	'<div class="nav-item"><a href="index.php">Home</a></div>' .PHP_EOL;			
					$output .=	'<div class="nav-item"><a href="charts.php">Charts</a></div>' .PHP_EOL;			
					$output .=	'<div class="nav-item"><a href="artist.php">Artists</a></div>' .PHP_EOL;			
					$output .=	'<div class="nav-item"><a href="store.php">Stores</a></div>' .PHP_EOL;			
					
					$output .= '</div>' .PHP_EOL;
			return $output;
		}
		
		

		private function footerContent() {
		//	$output = '		<div class="clear"></div>'.PHP_EOL;
			$output = '' . PHP_EOL;
			$output .= '	<div id="footer-container">'.PHP_EOL;
			$output .= '		<div id="footer">'.PHP_EOL;
			$output .= '			<div class="footer-left">'.PHP_EOL;
			$output .= '				<h3>Quicksilver Music</h3><br />'.PHP_EOL;
			$output .= '				<a href="about.php">About Us</a><br />'.PHP_EOL;
			$output .= '				<a href="contact.php">Contact Us</a><br />'.PHP_EOL;
			$output .= '			</div>'.PHP_EOL;
			$output .= '			<div class="footer-center">' . PHP_EOL;
			$output .= '				<a href="http://validator.w3.org/check?uri=referer">Check HTML Validation</a>' . PHP_EOL;
			$output .= '				<p>Keep this here while we\'re developing.</p>' . PHP_EOL;
			$output .= '			</div>' . PHP_EOL;
			$output .= '			<div class="footer-right">'.PHP_EOL;
			$output .= '				<h3>Connect with Quicksilver Music</h3>'.PHP_EOL;
			$output .= '				<br /><a href="http://www.facebook.com/"><img src="images/facebook.gif" alt="Facebook" /></a>&nbsp;'.PHP_EOL;
			$output .= '				<a href="http://www.twitter.com/"><img src="images/twitter.gif" alt="Twitter" /></a>'.PHP_EOL;
			$output .= '			</div>'.PHP_EOL;			
			$output .= '		</div>'.PHP_EOL;
			$output .= '	</div>'.PHP_EOL;
			return $output;
		}
		
		public function getFooter() {
			$output .=  $this->footerContent();
			$output .= '				</div>' .PHP_EOL; // Close the main-content tag
			$output .= '			</div>' .PHP_EOL; // Close the main-bg tag
			$output .= '		</div>' .PHP_EOL; // Close the container tag.
			$output .= '	</body>' .PHP_EOL;
			$output .= '</html>';
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