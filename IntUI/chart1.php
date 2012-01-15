<?php
	require_once('../App.php');
	include("../pChart/class/pData.class.php");
	include("../pChart/class/pDraw.class.php");
	include("../pChart/class/pImage.class.php");

		$chartId = 1;
		
		$chart = App::getDB()->getDataRow("SELECT * FROM chart WHERE chartId = '".$chartId."'");
		
		// Load in the XML configuration.
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
			
		// Execute query and put in array.
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

		// This sets the background colour and dash of the chart.
		$Settings = array("R"=>(int)$xml->background->rColour,
		"G"=>(int)$xml->background->gColour,
		"B"=>(int)$xml->background->bColour,
		"Dash"=>(int)$xml->background->dash,
		"DashR"=>(int)$xml->background->rColourDash,
		"DashG"=>(int)$xml->background->gColourDash,
		"DashB"=>(int)$xml->background->bColourDash);
		
		$myPicture->drawFilledRectangle(0,0,(int)$xml->image->xSize,(int)$xml->image->ySize,$Settings);

		// Optional gradient
		if((int)$xml->background->gradient->on == 1) {
			$Settings = array("StartR"=>(int)$xml->background->gradient->startRColour, "StartG"=>(int)$xml->background->gradient->startGColour,
			"StartB"=>(int)$xml->background->gradient->startBColour,
			"EndR"=>(int)$xml->background->gradient->endRColour,
			"EndG"=>(int)$xml->background->gradient->endGColour,
			"EndB"=>(int)$xml->background->gradient->endBColour,
			"Alpha"=>(int)$xml->background->gradient->transparency);
			
			$myPicture->drawGradientArea(0,0,(int)$xml->image->xSize,(int)$xml->image->ySize,constant((string)$xml->background->gradient->direction),$Settings);

			// This draws the black border around the chart
			$myPicture->drawRectangle(0,0,(int)$xml->image->xSize-1,(int)$xml->image->ySize-1,array("R"=>0,"G"=>0,"B"=>0));
		}

//$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

		// This is the chart's title configuration
		$myPicture->setFontProperties(array(
			"FontName"=>"../pChart/fonts/" . (string)$xml->title->fontName,
			"FontSize"=>(int)$xml->title->fontSize)
		);
		$TextSettings = array("Align"=>constant($xml->title->align), 
		"R"=>(int)$xml->title->rColour, 
		"G"=>(int)$xml->title->gColour, 
		"B"=>(int)$xml->title->bColour);
		
		$myPicture->drawText((int)$xml->title->xPos,(int)$xml->title->yPos,(string)$xml->title->name,$TextSettings);

		$myPicture->setShadow(FALSE);
		$myPicture->setGraphArea(50,80,675,200);
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