<?php
	require_once('../App.php');
	include("../pChart/class/pData.class.php");
	include("../pChart/class/pDraw.class.php");
	include("../pChart/class/pImage.class.php");
	include("Chart.php");

		$user = App::getAuthUser();
		$chart = Chart::getChart($_GET['chartId']);
	
		
		if(in_array($user->getUserId(), $chart->userPermissions) || count(array_intersect($user->groupMembership, $chart->groupPermissions)) != 0 || $_GET['preview'] == true) {
			// Check if the filters are overridden.
			foreach(array_keys($chart->sqlFilter) as $key) {
				if(isset($_GET[$key])) {
					if($_GET[$key] != null) {
						// Override filter's value. 
						$oldFilter = $chart->sqlFilter[$key];
						$chart->addFilter($key, $oldFilter['dbAlias'], $oldFilter['operator'], mysql_real_escape_string($_GET[$key]), $oldFilter['combinator'], true);		
					} else {
						// If key is null then delete the filter
						$chart->deleteFilter($key);
					}
				}
			}
				
			// Generate select query
			$query = $chart->generateSQLQuery();
				
			// Execute query and put in array.
			$darr = App::getDB()->getArrayFromDB($query);
				
			$chartSeriesStoreFilter = array();	
			// Get the series array 	
			foreach($chart->chartSeries as $series) {
				$chartSeriesStoreFilter[$series['dbCol']] = $series['storeFilter'];//$series['storeFilter']
			}
			
			// Create our data arrays for reference in the series and abscissa.
			foreach($darr as $row) {
				foreach(array_map(function($item) {return $item['alias'];}, $chart->sqlColumns) as $col){
						if(array_key_exists($col, $chartSeriesStoreFilter) && !empty($chartSeriesStoreFilter[$col])) {
							if($row['STORE_ID'] == $chartSeriesStoreFilter[$col]) {
								${$col}[] = (string) $row[$col]; 
							} else {	
								${$col}[] = 0;
							}
						} else {
							${$col}[] = (string) $row[$col]; 
							
						}
					}
					
			}
			
			$myData = new pData();
			// Add points and series
				
			foreach($chart->chartSeries as $serie) {
				$arr = ${$serie['dbCol']};

				$myData->addPoints($arr, $serie['name']);
				$myData->setSerieDescription($serie['name'], $serie['description']);
				$myData->setSerieOnAxis($serie['name'], (int)$serie['axisNo']);	
			}
			
			// Set abscissa
			$myData->addPoints(${str_replace(".", "",$chart->abscissa['dbColAlias'])}, $chart->abscissa['name']);
			$myData->setAbscissaName($chart->abscissa['name']);
			$myData->setAbscissa($chart->abscissa['name']);

			// Set axis 
			foreach($chart->axes as $key => $axis) {
				$myData->setAxisPosition((int)$key,constant((string)$axis['position']));
				$myData->setAxisName((int)$key,(string)$axis['name']);
				$myData->setAxisUnit((int)$key,(string)$axis['unit']);
			}
			$myData->setPalette("DEFCA",array("R"=>55,"G"=>91,"B"=>127));
			$myPicture = new pImage($chart->imgSize['X'], $chart->imgSize['Y'], $myData);
			  
			// This sets the background colour and dash of the chart.
			/*$Settings = array(
			"R"=>$chart->bgRGB['R'],
			"G"=>$chart->bgRGB['G'],
			"B"=>$chart->bgRGB['B'],
			"Dash"=>$dashBool,
			"DashR"=>$chart->dashRGB['R'],
			"DashG"=>$chart->dashRGB['G'],
			"DashB"=>$chart->dashRGB['B']);*/
			
			$Settings = array(
			"R"=>255,
			"G"=>255,
			"B"=>255,
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
				
				//$myPicture->drawGradientArea(0,0,$chart->imgSize['X'],$chart->imgSize['Y'],constant($chart->gradDirection),$Settings);

				// This draws the black border around the chart
				//$myPicture->drawRectangle(0,0,$chart->imgSize['X']-1,(int)$chart->imgSize['Y']-1,array("R"=>0,"G"=>0,"B"=>0));
			}

			$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

			// This is the chart's title configuration
			$myPicture->setFontProperties(array(
				"FontName"=>"../pChart/fonts/" . $chart->titleFont,
				"FontSize"=>$chart->titleFontSize)
			);
			$TextSettings = array("Align"=>constant($chart->titleAlign), 
			"R"=>$chart->titleRGB['R'], 
			"G"=>$chart->titleRGB['G'], 
			"B"=>$chart->titleRGB['B']);
		
			$titlePosX = ($chart->imgSize['X']/2);
			$myPicture->drawText($titlePosX,$chart->titlePos['Y'],$chart->chartName,$TextSettings);

			$myPicture->setShadow(FALSE);
			$x2 = 675 - (675-($chart->imgSize['X']-25)); // 25 is the margin.
			$y2 = 200 - (200-($chart->imgSize['Y']-50));

			$myPicture->setGraphArea(50,55,$x2,$y2);
			$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"../pChart/fonts/pf_arma_five.ttf","FontSize"=>6));
		
		/*	$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
			, "Mode"=>SCALE_MODE_FLOATING
			, "LabelingMethod"=>LABELING_ALL
			, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50, "DrawYLines"=>ALL);*/
			$Settings = array("GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
			$myPicture->drawScale($Settings);

		//	$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

			//$Config = "";
			

			$function = "draw" . $chart->chartType . "Chart";
			$myPicture->$function(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO));

			//$myPicture->draw{}Chart($Config);

			$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"../pChart/fonts/pf_arma_five.ttf", "FontSize"=>6, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_BOX
			, "Mode"=>LEGEND_HORIZONTAL, "Family"=>LEGEND_FAMILY_CIRCLE
			);
			$myPicture->drawLegend(25,$chart->imgSize['Y']-20,$Config);
			$myPicture->stroke();
		} else {
			// User doesn't have permission.
			$im = imagecreatefrompng("../Images/access_denied.png");
			header('Content-Type: image/png');
			imagepng($im);
			imagedestroy($im);	
		}
		
		
		
			
	// Page PHP Backend Code End 
?>