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
			//print $chart->generateSQLQuery();
			// Execute query and put in array.
			$darr = App::getDB()->getArrayFromDB($query);
			
			$chartSeriesStoreFilter = array();	
			$chartFilterSeriesNames = array();
			$chartSeries = array();
			$aliases = array();
			
			foreach($chart->sqlColumns as $col) {
				$aliases[] = $col['alias'];
			}

			// Get the series array 	
			foreach($chart->chartSeries as $series) {
				if($series['storeFilter'] != null) {
					$chartSeriesStoreFilter[$series['dbCol']] = $series['storeFilter'];//$series['storeFilter']
					$chartFilterSeriesNames[] = $series['dbCol'];
				}
				$chartSeries[] = $series['dbCol'];
				
			//	if($series['storeFilter'] != null) 
					
			}
		//	print_r($darr);
		//	print_r($chartSeries);
		//  print_r($chartSeriesStoreFilter);
		//	print_r($aliases);
				foreach($darr as $row) {
					foreach($aliases as $col){
						if($col == $chart->abscissa['dbColAlias']) {
							if(!in_array($row[$col], (array) ${$col})) 
								${$col}[] = (string) $row[$col]; 
						} else if($row['STORE_ID'] != null) {
							if(array_key_exists($col, $chartSeriesStoreFilter) && $chartSeriesStoreFilter[$col] == $row['STORE_ID']) {
								${$col}[$row[$chart->abscissa['dbColAlias']]] = (string) $row[$col]; 
							}
						} else if (!array_key_exists($col, $chartSeriesStoreFilter)){
						//	print $col . "\n";
						${$col}[$row[$chart->abscissa['dbColAlias']]] = (string) $row[$col]; 
						}
					}
				}
		//	}
		
	//	print_r($PRODUCT_PRICE);
			$myData = new pData();
			// Add points and series
			foreach($chart->chartSeries as $serie) {
				$serieArray = array();
				$arr = ${$serie['dbCol']};
				foreach(${str_replace(".", "",$chart->abscissa['dbColAlias'])} as $abs) {
					if(array_key_exists($abs, $arr)) {
						$serieArray[] = $arr[$abs];
					} else {
						$serieArray[] = null;
					}
				}
				
			//	print_r($serieArray);
			
			//print $serie['dbCol'] . "\n";
				
								
				$myData->addPoints($serieArray, $serie['name']);
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
	
			$Settings = array("GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
			$myPicture->drawScale($Settings);

	
			$function = "draw" . $chart->chartType . "Chart";
			$myPicture->$function(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO));

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