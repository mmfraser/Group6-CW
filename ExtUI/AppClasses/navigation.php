<?php
$output = '<div style="margin-left:auto; margin-right:auto; width:100%;">' .PHP_EOL;
$output .= '<div class="menu" id="navigation">' .PHP_EOL;
$output .= '<ul>' .PHP_EOL;
$output .= '<li><a href="#">Dashboard<!--[if gte IE 7]><!--></a><!--<![endif]--></li>' .PHP_EOL;
$output .= '' .PHP_EOL;
$output .= '<li><a href="#">Chart Management<!--[if gte IE 7]><!--></a><!--<![endif]--></li>' .PHP_EOL;
$output .= '' .PHP_EOL;
$output .= '<li><a href="#">Management<!--[if gte IE 7]><!--></a><!--<![endif]-->' .PHP_EOL;
$output .= '<!--[if lte IE 6]><table><tr><td><![endif]-->' .PHP_EOL;
$output .= '	<ul>' .PHP_EOL;
$output .= '		<li><a href="userManagement.php">User Management</a></li>' .PHP_EOL;
$output .= '        <li><a href="storeManagement.php">Store Management</a></li>' .PHP_EOL;
$output .= '        <li><a href="genreManagement.php">Genre Management</a></li>' .PHP_EOL;
$output .= '	</ul>' .PHP_EOL;
$output .= '' .PHP_EOL;
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
$output .= '' .PHP_EOL;
$output .= '<!--[if lte IE 6]></td></tr></table></a><![endif]-->' .PHP_EOL;
$output .= '</li>' .PHP_EOL;
$output .= '<li><a href="#">Data Management<!--[if gte IE 7]><!--></a><!--<![endif]-->' .PHP_EOL;
$output .= '<!--[if lte IE 6]><table><tr><td><![endif]-->' .PHP_EOL;
$output .= '	<ul>' .PHP_EOL;
$output .= '		<li><a href="artistManagement.php">Artist Management</a></li>' .PHP_EOL;
$output .= '        <li><a href="productManagement.php">Product Management</a></li>' .PHP_EOL;
$output .= '        <li><a href="salesManagement.php">Sales Management</a></li>' .PHP_EOL;
$output .= '	</ul>' .PHP_EOL;
$output .= '	<li><a href="login.php?do=logout">Logout<!--[if gte IE 7]><!--></a><!--<![endif]--></li>' .PHP_EOL;
$output .= '		</div>' .PHP_EOL;
$output .= '</div>' .PHP_EOL;
?>