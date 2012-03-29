<?php

function dbConnect($username, $password) {
	$dbConn = @mysql_pconnect ("localhost", $username, $password) ;
//	$dbConn = @mysql_pconnect ("localhost", "root", "root");
	if (!$dbConn )
	{
		echo $dbcon;
		echo "Cannot connect to database";
	}
}

function dbSelect($dbname) {
	$db = mysql_select_db($dbname);
	if (!$db)
	{
		print "<p>Cannot connect to database $dbname</br>";
		print mysql_error()."</p>";
		print "</body>";
   	print "</html>";
		exit("Bye");
	}
}

?>