<?php  
error_reporting(E_ALL);
require_once('../App.php');

//require("phpsqlsearch_dbinfo.php");

// Get parameters from URL
$center_lat = $_GET["lat"];
$center_lng = $_GET["lng"];
$radius = $_GET["radius"];

// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Search the rows in the markers table
$query2 = sprintf("SELECT address, name, lat, lng, ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < '%s' ORDER BY distance LIMIT 0 , 20",
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($center_lng),
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($radius));
  
  $query = sprintf("SELECT s.address, s.name, s.lat, s.lng, t.mon, t.tue, t.wed, t.thu, t.fri, t.sat, t.sun, ( 3959 * acos( cos( radians('%s') ) * cos( radians( s.lat ) ) * cos( radians( s.lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( s.lat ) ) ) ) AS distance FROM markers s, storetimes t WHERE s.id = t.storeId HAVING distance < '%s' ORDER BY distance LIMIT 0 , 20",
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($center_lng),
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($radius),
  mysql_real_escape_string($mon),
  mysql_real_escape_string($tue),
  mysql_real_escape_string($wed),
  mysql_real_escape_string($thu),
  mysql_real_escape_string($fri),
  mysql_real_escape_string($sat),
  mysql_real_escape_string($sun));
$result = App::getDB()->getArrayFromDB($query);

/*if (!$result) {
  die("Invalid query: " . mysql_error());
}*/

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
foreach ($result as $row){
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("name", $row['name']);
  $newnode->setAttribute("address", $row['address']);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
  $newnode->setAttribute("distance", $row['distance']);
  
  $newnode->setAttribute("mon", $row['mon']);
  $newnode->setAttribute("tue", $row['tue']);
  $newnode->setAttribute("wed", $row['wed']);
  $newnode->setAttribute("thu", $row['thu']);
  $newnode->setAttribute("fri", $row['fri']);
  $newnode->setAttribute("sat", $row['sat']);
  $newnode->setAttribute("sun", $row['sun']);
}

echo $dom->saveXML();
?>

