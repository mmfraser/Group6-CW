<?php
	/*
		Page.php
		Created by: Marc
		Description: User object
		Update log:
			23/10/11 (MF) - Creation.
			30/10/11 (MF) - Adding group membership functionality.
	*/
	
	class Page {
		public $title;
		private $headJS = "";
		
		public function getHeader() {
			$output = '<!DOCTYPE html>' . PHP_EOL;
			$output .= '	<head>' . PHP_EOL;
			$output .= '		<title>' . $this->title . '</title>' . PHP_EOL;
			$output .= '		<link type="text/css" href="../css/IntUI.css" rel="Stylesheet" />' . PHP_EOL;
			$output .= '		<link type="text/css" href="../css/Navigation.css" rel="Stylesheet" />' . PHP_EOL;
			$output .= '		<link type="text/css" href="../css/smoothness/jquery-ui-1.8.16.custom.css" rel="Stylesheet" />' . PHP_EOL;
			$output .= '		<link type="text/css" href="../css/datatable/demo_table_jui.css" rel="Stylesheet" />' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/jquery.cookie.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js"></script>' . PHP_EOL; 	
			$output .= '		<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/custom.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript">' . $this->headJS . '</script>';
			$output .= '	</head>' . PHP_EOL;
			$output .= '	<body>' .PHP_EOL;
			$output .= '		<div id="wrapper">' . PHP_EOL;
			$output .= 			$this->header();
			$output .= 			$this->navigation();
			$output .= '		<div id="mainContent">' . PHP_EOL;
			$output .= '			<h2>'.$this->title.'</h2>' . PHP_EOL;
			echo $output; 
		
		}
		
		private function header() { 
			$output = '<div id="header">'. PHP_EOL;
			//$output .= '	Quicksilver Music'. PHP_EOL;
			$output .= '<img src="../Images/logo4.png" alt="Quicksilver Music" />';
			$output .='</div>'. PHP_EOL;
			return $output;
		}
		
		private function navigation() {
			if(App::checkAuth()) {
					$output = '<div style="margin-left:auto; margin-right:auto; width:100%;">' .PHP_EOL;
					$output .= '<div class="menu" id="navigation">' .PHP_EOL;
					$output .= '<ul>' .PHP_EOL;
					$output .= '<li><a href="dashboard.php">Dashboard<!--[if gte IE 7]><!--></a><!--<![endif]--></li>' .PHP_EOL;
					$output .= '<li><a href="chartManagement.php">Chart Management<!--[if gte IE 7]><!--></a><!--<![endif]--></li>' .PHP_EOL;
					$output .= '<li><a href="#">Management<!--[if gte IE 7]><!--></a><!--<![endif]-->' .PHP_EOL;
					$output .= '<!--[if lte IE 6]><table><tr><td><![endif]-->' .PHP_EOL;
					$output .= '	<ul>' .PHP_EOL;
					$output .= '		<li><a href="userManagement.php">User Management</a></li>' .PHP_EOL;
					$output .= '        <li><a href="storeManagement.php">Store Management</a></li>' .PHP_EOL;
					$output .= '        <li><a href="genreManagement.php">Genre Management</a></li>' .PHP_EOL;
					$output .= '	</ul>' .PHP_EOL;
					$output .= '<!--[if lte IE 6]></td></tr></table></a><![endif]-->' .PHP_EOL;
					$output .= '</li>' .PHP_EOL;
					$output .= '<li><a href="#">Data Import<!--[if gte IE 7]><!--></a><!--<![endif]-->' .PHP_EOL;
					$output .= '<!--[if lte IE 6]><table><tr><td><![endif]-->' .PHP_EOL;
					$output .= '	<ul>' .PHP_EOL;
					$output .= '	<!--	<li><a href="">Import Overview</a></li> -->' .PHP_EOL;
					$output .= '        <li><a href="ArtistImport.php">Artist Import</a></li>' .PHP_EOL;
					$output .= '        <li><a href="ProductImport.php">Products Import</a></li>' .PHP_EOL;
					$output .= '        <li><a href="SalesImport.php">Sales Import</a></li>' .PHP_EOL;
					$output .= '	</ul>' .PHP_EOL;					
					$output .= '<!--[if lte IE 6]></td></tr></table></a><![endif]-->' .PHP_EOL;
					$output .= '</li>' .PHP_EOL;
					$output .= '<li><a href="#">Data Management<!--[if gte IE 7]><!--></a><!--<![endif]-->' .PHP_EOL;
					$output .= '<!--[if lte IE 6]><table><tr><td><![endif]-->' .PHP_EOL;
					$output .= '	<ul>' .PHP_EOL;
					$output .= '		<li><a href="artistManagement.php">Artist Management</a></li>' .PHP_EOL;
					$output .= '        <li><a href="productManagement.php">Product Management</a></li>' .PHP_EOL;
					$output .= '        <li><a href="salesManagement.php">Sales Management</a></li>' .PHP_EOL;
					$output .= '</ul>' .PHP_EOL;
					$output .= '<li><a href="login.php?do=logout">Logout<!--[if gte IE 7]><!--></a><!--<![endif]--></li>' .PHP_EOL;
					$output .='</div>' .PHP_EOL;
					$output .='</div>' .PHP_EOL;
			} else {
				// User is not logged in therefore no need to see the navigation.
				$output = "";
			}
			return $output;
		}
		
		/*private function navigation2() {
			if(App::checkAuth()) {
				$output = '		<div id="navigation">' .PHP_EOL;
				$output .= '		<ul>'.PHP_EOL;
				$output .= '			<li><a href="">Dashboard</a></li>' .PHP_EOL;
				$output .= '			<li><a href="chartManagement.php">Chart Management</a></li>' .PHP_EOL;
				$output .= '			<li><a href="userManagement.php">User Management</a></li>' .PHP_EOL;
				$output .= '			<li><a href="storeManagement.php">Store Management</a></li>' .PHP_EOL;
				$output .= '			<li><a href="genreManagement.php">Genre Management</a></li>' .PHP_EOL;
				$output .= '			<li>Data Import
											<ul>
											<!--	<li><a href="">Import Overview</a></li> -->
												<li><a href="ArtistImport.php">Artist Import</a></li>
												<li><a href="ProductImport.php">Products Import</a></li>
												<li><a href="SalesImport.php">Sales Import</a></li>

											</ul>
										</li>' .PHP_EOL;
				$output .= '			<li>Data Management
											<ul>
												<li><a href="artistManagement.php">Artist Management</a></li>
												<li><a href="productManagement.php">Product Management</a></li>
												<li><a href="salesManagement.php">Sales Management</a></li>
											</ul>
										</li>' .PHP_EOL;
				$output .= '			<li><a href="login.php?do=logout">Log Out</a></li>' .PHP_EOL;
				$output .= '		</ul>'.PHP_EOL;
				$output .= '	</div>' .PHP_EOL;
			} else {
				// User is not logged in therefore no need to see the navigation.
				$output = "";
			}
			return $output;
		}*/

		private function footerContent() {
			$output = '		<div class="clear"></div>'.PHP_EOL;
			$output .= '	<div id="footer">'.PHP_EOL;
			$output .= '		test'.PHP_EOL;
			$output .= '	</div>'.PHP_EOL;
			return $output;
		}
		
		public function getFooter() {
			$output = '				</div>' .PHP_EOL; // This is the content div close tag
			$output .=  $this->footerContent();
			$output .= '		</div>' .PHP_EOL; // This is the wrapper div close tag
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