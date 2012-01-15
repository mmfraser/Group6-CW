<?php
	require_once('../App.php');
	include("../pChart/class/pData.class.php");
	include("../pChart/class/pDraw.class.php");
	include("../pChart/class/pImage.class.php");
	include("Chart.php");

	/*	$chartId = 1;
		
		$chart = new Chart();
	
		$chart->chartType = "Bar";
		
		$chart->chartName = "Sales over Time";
		
		$chart->addSQLColumn("otherCol", "sales");
		$chart->addSQLColumn("month", "sales");
		$chart->addSQLColumn("noSales", "sales");
		
		$chart->addSQLOrder("sales.month", "DESC");
		
		$chart->addChartAxis("otherCol", "CDs", "AXIS_POSITION_LEFT");
		
		$chart->addChartSeries("Sales", "sales.otherCol", "Number Sales", 0);
		$chart->addChartSeries("noSales", "sales.noSales", "Number Sales 1", 0);
		
		$chart->setAbscissa("Month", "sales.month");*/
		
		$chart = Chart::getChart(2);
			
		//	print $chart->generateSQLQuery();
		// Generate select query
		$query = $chart->generateSQLQuery();
			
		// Execute query and put in array.
		$darr = App::getDB()->getArrayFromDB($query);
		
	
		
		// Create our data arrays for reference in the series and abscissa.
		foreach($darr as $row) {
			foreach($chart->sqlColumns as $col){
				// REVISIT THIS
				$expl = explode(".", $col);
				$colName = $expl[1];
					${str_replace(".", "",$col)}[] = (string) $row[$colName];
				}
		}
			
		$myData = new pData();
		// Add points and series
		$series = $xml->chartSeries->series;
		foreach($chart->chartSeries as $serie) {
			$arr = ${str_replace(".", "",$serie['dbCol'])};
		
			$myData->addPoints($arr, $serie['name']);
			$myData->setSerieDescription($serie['name'], $serie['description']);
			$myData->setSerieOnAxis($serie['name'], (int)$serie['axisNo']);	
		}
		
		// Set abscissa
		$myData->addPoints(${str_replace(".", "",$chart->abscissa['dbCol'])}, $chart->abscissa['name']);
		$myData->setAbscissa($chart->abscissa['name']);

		// Set axis 
		foreach($chart->axes as $key => $axis) {
			$myData->setAxisPosition((int)$key,constant((string)$axis['position']));
			$myData->setAxisName((int)$key,(string)$axis['name']);
			$myData->setAxisUnit((int)$key,(string)$axis['unit']);
		}
		
		$myPicture = new pImage($chart->imgSize['X'], $chart->imgSize['Y'], $myData);

		// This sets the background colour and dash of the chart.
		$Settings = array(
		"R"=>$chart->bgRGB['R'],
		"G"=>$chart->bgRGB['G'],
		"B"=>$chart->bgRGB['B'],
		"Dash"=>$dashBool,
		"DashR"=>$chart->dashRGB['R'],
		"DashG"=>$chart->dashRGB['G'],
		"DashB"=>$chart->dashRGB['B']);
		
		$myPicture->drawFilledRectangle(0,0,$chart->imgSize['X'],$chart->imgSize['Y'],$Settings);

		
		// Optional gradient
		if(($chart->gradientBool == 1)) {
			$Settings = array(
			"StartR"=>$chart->bgStartRGB['R'], 
			"StartG"=>$chart->bgStartRGB['G'],
			"StartB"=>$chart->bgStartRGB['B'],
			"EndR"=>$chart->bgEndRGB['R'],
			"EndG"=>$chart->bgEndRGB['G'],
			"EndB"=>$chart->bgEndRGB['B'],
			"Alpha"=>$chart->gradTransparency
			);
			
			$myPicture->drawGradientArea(0,0,$chart->imgSize['X'],$chart->imgSize['Y'],constant($chart->gradDirection),$Settings);

			// This draws the black border around the chart
			$myPicture->drawRectangle(0,0,$chart->imgSize['X']-1,(int)$chart->imgSize['Y']-1,array("R"=>0,"G"=>0,"B"=>0));
		}

//$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

		// This is the chart's title configuration
		$myPicture->setFontProperties(array(
			"FontName"=>"../pChart/fonts/" . $chart->titleFont,
			"FontSize"=>$chart->titleFontSize)
		);
		$TextSettings = array("Align"=>constant($chart->titleAlign), 
		"R"=>$chart->titleRGB['R'], 
		"G"=>$chart->titleRGB['G'], 
		"B"=>$chart->titleRGB['B']);
	
		$myPicture->drawText($chart->titlePos['X'],$chart->titlePos['Y'],$chart->chartName,$TextSettings);

		$myPicture->setShadow(FALSE);
		$myPicture->setGraphArea(50,80,675,200);
		$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"../pChart/fonts/pf_arma_five.ttf","FontSize"=>6));
	
		$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
		, "Mode"=>SCALE_MODE_FLOATING
		, "LabelingMethod"=>LABELING_ALL
		, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50, "DrawYLines"=>ALL);
		$myPicture->drawScale($Settings);

		$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

		//$Config = "";

		$function = "draw" . $chart->chartType . "Chart";
		$myPicture->$function($Config);

		//$myPicture->draw{}Chart($Config);

		$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"../pChart/fonts/pf_arma_five.ttf", "FontSize"=>6, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
		, "Mode"=>LEGEND_HORIZONTAL
		);
		$myPicture->drawLegend($chart->legendPos['X'],$chart->legendPos['Y'],$Config);

		$myPicture->stroke();
		
			
	// Page PHP Backend Code End 
?>