<?

$i = 0;
$output = "INSERT INTO `storetimes` (`storeId`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`) VALUES";
while ($i<1000){
	$i++;
	$output .= "<br />('".$i."', '08:00 - 18:00', '08:00 - 18:00', '08:00 - 18:00', '08:00 - 18:00', '08:00 - 18:00', '09:00 - 18:00', '10:00 - 17:00'),";
}

echo $output;
?>