<?

error_reporting(E_ALL);

//require("phpsqlsearch_dbinfo.php");

// Get parameters from URL
$center_lat = $_GET['lat'];
$center_lng = $_GET['lng'];
$radius = $_GET['radius'];

// Opens a connection to a mySQL server
$connection = mysql_pconnect('localhost', '0', '00');
if (!$connection) {
  die("Not connected : " . mysql_error());
}

// Set the active mySQL database
$db_selected = mysql_select_db("sales");
if (!$db_selected) {
  die ("Can\'t use db : " . mysql_error());
}

// Start XML file, create parent node
$dom = new DOMDocument('1.0');
header("Content-type: text/xml");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);



// Search the rows in the markers table
$query = sprintf("SELECT address, name, lat, lng, ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < '%s' ORDER BY distance LIMIT 0 , 20", mysql_real_escape_string($center_lat), mysql_real_escape_string($center_lng), mysql_real_escape_string($center_lat), mysql_real_escape_string($radius));
$result = mysql_query($query);

if (!$result) {
  die("Invalid query: " . mysql_error());
}

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("name", $row['name']);
  $newnode->setAttribute("address", $row['address']);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
  $newnode->setAttribute("distance", $row['distance']);
}

echo $dom->saveXML();
?>

