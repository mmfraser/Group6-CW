<?php
	require_once('../App.php');
	include("../pChart/class/pData.class.php");
	include("../pChart/class/pDraw.class.php");
	include("../pChart/class/pImage.class.php");

		$chartId = 1;
		
		$chart = App::getDB()->getDataRow("SELECT * FROM chart WHERE chartId = '".$chartId."'");
		
		$xml = simplexml_load_string($chart['config']);
		$sqlCols = $xml->sqlQuery->columns;
		$noCols = count($sqlCols->column);
	
		
		// Generate select query
		$query = "SELECT ";
			
		for($i = 0; $i < $noCols; $i++) {
			//$$sqlCols[$i] = array();
			$query .= $sqlCols->column[$i];
			$dbCols[] = (string) $sqlCols->column[$i];
			if(($i+1) < $noCols)
				$query .= ", ";
			else 
				$query .= " ";
		}
		
		//TODO: This should be altered to include more than one table..
		$query .= "FROM " . $xml->sqlQuery->tables->table;
					
		$darr = App::getDB()->getArrayFromDB($query);
		
		// Create our data arrays for reference in the series and abscissa.
		foreach($darr as $row) {
			foreach($dbCols as $col){
				${$col}[] = (string) $row[$col];
			}
		}
				
		$myData = new pData();
		
		// Add points and series
		$series = $xml->chartSeries->series;
		foreach($series as $serie) {
			$arr = ${(string)$serie->dbCol};
		
			$myData->addPoints($arr, (string)$serie->name);
			$myData->setSerieDescription((string)$serie->name, (string)$serie->description);
			$myData->setSerieOnAxis((string)$serie->name, (int)$serie->axis);	
		}

		// Set abscissa
		$myData->addPoints(${(string)$xml->abscissa->dbCol}, (string)$xml->abscissa->name);
		$myData->setAbscissa((string)$xml->abscissa->name);

		// Set axis 
		foreach($xml->axes->axis as $axis) {
			$myData->setAxisPosition((int)$axis->number,constant((string)$axis->position));
			$myData->setAxisName((int)$axis->number,(string)$axis->name);
			$myData->setAxisUnit((int)$axis->number,(string)$axis->unit);
		}
		
		$myPicture = new pImage((int)$xml->image->xSize, (int)$xml->image->ySize, $myData);

$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);

$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);

$myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

$myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/Forgotte.ttf","FontSize"=>14));
$TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE
, "R"=>255, "G"=>255, "B"=>255);
$myPicture->drawText(350,25,"My first pChart project",$TextSettings);

$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(50,50,675,190);
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"../pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
, "Mode"=>SCALE_MODE_FLOATING
, "LabelingMethod"=>LABELING_ALL
, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50, "DrawYLines"=>ALL);
$myPicture->drawScale($Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

$Config = "";

$function = "draw" . (string)$xml->chartType . "Chart";
$myPicture->$function($Config);

//$myPicture->draw{}Chart($Config);

$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"../pChart/fonts/pf_arma_five.ttf", "FontSize"=>6, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
, "Mode"=>LEGEND_HORIZONTAL
);
$myPicture->drawLegend(563,16,$Config);

$myPicture->stroke();
		
			
	// Page PHP Backend Code End 
?>