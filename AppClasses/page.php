<?php
	
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
			
			echo $output; 
		}
		
		public function getFooter() {
			$output = '		</body>';
			$output .= '</html>';
			echo $output;
		}	

		public function addHeadJS($js) {
			$this->headJS .= $js;
		}
		
	}
	
?>