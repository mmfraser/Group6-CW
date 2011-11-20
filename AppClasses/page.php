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
			$output .= '		<link type="text/css" href="../css/smoothness/jquery-ui-1.8.16.custom.css" rel="Stylesheet" />' . PHP_EOL;
			$output .= '		<link type="text/css" href="../css/datatable/demo_table_jui.css" rel="Stylesheet" />' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/jquery.cookie.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js"></script>' . PHP_EOL; 	
			$output .= '		<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript">' . $this->headJS . '</script>';
			$output .= '	</head>' . PHP_EOL;
			$output .= '	<body>' .PHP_EOL;
			$output .= '		<div id="container">' . PHP_EOL;
			$output .= $this->navigation();
			$output .= '		<div id="mainContent">' . PHP_EOL;;
			$output .= '			<h2>'.$this->title.'</h2>' . PHP_EOL;;
			echo $output; 
		}
		
		private function navigation() {
			$output = '		<div id="navigation">' .PHP_EOL;
			$output .= '		<ul>'.PHP_EOL;
			$output .= '			<li><a href="">Dashboard</a></li>' .PHP_EOL;
			$output .= '			<li><a href="">Chart Management</a></li>' .PHP_EOL;
			$output .= '			<li><a href="userManagement.php">User Management</a></li>' .PHP_EOL;
			$output .= '			<li><a href="storeManagement.php">Store Management</a></li>' .PHP_EOL;
			$output .= '			<li>Data Management
										<ul>
											<li><a href="">Import Overview</a></li>
											<li><a href="SalesImport.php">Sales Import</a></li>
											<li><a href="ArtistImport.php">Artist Import</a></li>
											<li><a href="ProductImport.php">Products Import</a></li>
										</ul>
									</li>' .PHP_EOL;
			$output .= '		</ul>'.PHP_EOL;
			$output .= '	</div>' .PHP_EOL;
			return $output;
		}
		
		public function getFooter() {
			$output = '		</div></div></body>';
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