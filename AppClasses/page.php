<?php
	
	class Page {
		public $title;
		public function getHeader() {
			$output = '<!DOCTYPE html>' . PHP_EOL;
			$output .= '	<head>' . PHP_EOL;
			$output .= '		<title>' . $this->title . '</title>' . PHP_EOL;
			$output .= '		<link type="text/css" href="../css/themename/jquery-ui-1.8.16.custom.css" rel="Stylesheet" />' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/jquery-1.4.4.min.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js"></script>' . PHP_EOL;
			$output .= "	</head>" . PHP_EOL;
			$output .= "	<body>" .PHP_EOL;
			echo $output;
		}
		
		public function getFooter() {
			$output = "		</body>";
			$output .= "</html>";
			echo $output;
		}		
		
	}
	
?>