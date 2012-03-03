<?php
	require_once('../App.php');
	require_once('../AppClasses/Chart.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Chart Data";
		$page->getHeader();

		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=chartManagement.php">log in</a>.');
		}
	
		function seriesRow($seriesNo, $seriesName, $seriesData, $seriesStoreFilter, $seriesAggeregation, $viewCols, $storeData) {
						if($seriesAggeregation == "SUM") {
							$sumSel = "selected";
						} else if($seriesAggeregation == "COUNT") {
							$countSel = "selected";
						}
											
						$seriesColHtml = "";
						
						foreach($viewCols as $col) {
							$selected = "";
							if($seriesData == $col['Field']) {
								$selected = "selected";
							}
							
							$seriesColHtml .= '<option value="'.$col['Field'].'" '. $selected.'>'.$col['Field'].'</option>';
						}
						
						$seriesStoreFilterHtml = "";
						foreach($storeData as $col) {
							$selected = "";
							if($seriesStoreFilter == $col['storeId'])
								$selected = "selected";
							$seriesStoreFilterHtml .= '<option value="'.$col['storeId'].'" '. $selected.'>'.$col['storeName'].'</option>';
						}
						
						$seriesHtml = '	<tr>
											<td>Series '.$seriesNo.':</td>
											<td>
												<input type="text" name="seriesName[]" size="15" value="'.$seriesName.'" />
											</td>
											<td>
												<select name="seriesData[]">
													'.$seriesColHtml.'
												</select>
											</td>
											<td>
												<select name="seriesStoreFilter[]">
													<option value="" '.$sumSel.'>N/A</option>
													'.$seriesStoreFilterHtml.'
												</select>
											</td>
											<td id="commands">
												<select name="seriesAggregation[]">
													<option value="SUM" '.$sumSel.'>Sum</option>
													<option value="COUNT" '.$countSel.'>Count</option>
												</select>
											</td>
											<td id="commands">
												<a title="Delete Row" id="deleteRow"><span class="ui-icon ui-icon-trash"></span></a>
											</td>
										</tr>';
						return $seriesHtml;
					}
		
		if(isset($_SESSION['CHARTWIZARD'])) {
			$chart = unserialize($_SESSION['CHARTWIZARD']);
		} else {
			App::fatalError($page, 'You must first select which data(view) to use in the chart.  Please go <a href="createChart1.php">back</a> and do this.');
		}
		
		$viewCols = App::getDB()->getArrayFromDB("SHOW COLUMNS FROM " . $chart->dataView);
		$storeData = App::getDB()->getArrayFromDB("SELECT storeId, storeName FROM store");
		
		if(isset($_GET['do']) && $_GET['do'] == "submit") {
			$error = false;
			if(empty($_POST['xAxisName'])) {
				$page->error("The X-Axis name field is mandatory must be completed.");
				$error = true;
			} else if(empty($_POST['yAxisName'])) {
				$page->error("The Y-Axis name field is mandatory must be completed.");
				$error = true;
			} 
			
			// Set the values
				try {
					$chart->unsetVars();
					$xAxisAlias = $chart->addSQLColumn($_POST['xAxisData'], $chart->dataView, $_POST['xAxisData'], null, false);
					$chart->setAbscissa($_POST['xAxisName'], $_POST['xAxisData'], $xAxisAlias);
					
					$chart->addSQLGroupBy($xAxisAlias, $chart->dataView);
			
					$xAxisData = $chart->abscissa['dbCol'];
					$xAxisName = $chart->abscissa['name'];
					$yAxisName = $_POST['yAxisName'];
					$yAxisUnit = $_POST['yAxisUnit'];
					
					// Set standard chart size
					$chart->setImageSize(380, 300);
					
					$chart->setYAxis($_POST['yAxisName'], $_POST['yAxisUnit'], "AXIS_POSITION_LEFT");
					$noSeries = count($_POST['seriesName']);
					
					$seriesHtml = "";
					for($i = 0; $i < $noSeries; $i++) {
						//Check that the series name isn't null
						if($_POST['seriesName'][$i] != null) {			
							// Add the series, first by adding the nesecary SQL column
							$seriesName = $chart->addSQLColumn($_POST['seriesData'][$i], $chart->dataView, $_POST['seriesData'][$i], $_POST['seriesAggregation'][$i], true);
					
							$chart->addChartSeries($seriesName, $seriesName, $_POST['seriesName'][$i], 0, $_POST['seriesAggregation'][$i], $_POST['seriesStoreFilter'][$i]);						
						} else {
							$error = true;
							$page->error("There must be at least one series defined.  Series also cannot have blank names.");
						}	
					
						$seriesHtml .= seriesRow($i+1, $_POST['seriesName'][$i], $_POST['seriesData'][$i], $_POST['seriesStoreFilter'][$i], $_POST['seriesAggregation'][$i], $viewCols, $storeData);
					}
					
				} catch (Exception $e) {
					$page->error($e->getMessage());
				}
				
				if(!$error) {
					$chart->save();
					$_SESSION['CHARTWIZARD'] = serialize($chart);
					header('Location: createChart3.php');
				}
				$_SESSION['CHARTWIZARD'] = serialize($chart);
		} else {
			if(isset($chart)) {
				$xAxisData = $chart->abscissa['dbCol'];
				$xAxisName = $chart->abscissa['name'];
				$yAxisName = $chart->axes[0]['name'];
				$yAxisUnit = $chart->axes[0]['unit'];
				
				$seriesHtml = "";
				$noSeries = count($chart->chartSeries);
				for($i = 0; $i < $noSeries; $i++) {					
					// Look up SQL columns for the underlying column name for the Series Data drop down.
					$underlayingCol = "";
					foreach($chart->sqlColumns as $col) {
						if($col['alias'] == $chart->chartSeries[$i]['dbCol']) {
							$underlayingCol = $col['underlaying'];
							break;
						} 
					}
					
					$seriesHtml .= seriesRow($i+1, $chart->chartSeries[$i]['description'], $underlayingCol, $chart->chartSeries[$i]['storeFilter'], $chart->chartSeries[$i]['aggregation'], $viewCols, $storeData);
				}
			}
		}
	// View data columns
	$xAxisColHtml = "";
	foreach($viewCols as $col) {
		if($col['Field'] == $xAxisData) 
			$selected = "selected";
		else 
			$selected = "";
	
		$xAxisColHtml .= '<option value="'.$col['Field'].'" '. $selected.'>'.$col['Field'].'</option>';
	}
	
	$yAxisColHtml = "";
	foreach($viewCols as $col) {
		$yAxisColHtml .= '<option value="'.$col['Field'].'" '. $selected.'>'.$col['Field'].'</option>';
	}
	
	$seriesColHtml = "";
	foreach($viewCols as $col) {
		$seriesColHtml .= '<option value="'.$col['Field'].'" '. $selected.'>'.$col['Field'].'</option>';
	}
		
	$seriesStoreFilterHtml = "";
	foreach($storeData as $col) {
		$seriesStoreFilterHtml .= '<option value="'.$col['storeId'].'" '. $selected.'>'.$col['storeName'].'</option>';
	}
		
	if($noSeries == 0) {
				$seriesColHtml = "";
						foreach($viewCols as $col) {
							$selected = "";
							if($seriesData == $col['Field']) {
								$selected = "selected";
							}
							
							$seriesColHtml .= '<option value="'.$col['Field'].'" '. $selected.'>'.$col['Field'].'</option>';
						}
		$seriesHtml = '	<tr>
							<td>Series '.++$noSeries.':</td>
							<td>
								<input type="text" name="seriesName[]" size="15" />
							</td>
							<td>
								<select name="seriesData[]">
									'.$seriesColHtml.'
								</select>
							</td>
							<td>
								<select name="seriesStoreFilter[]">
									<option value="" '.$sumSel.'>N/A</option>
									'.$seriesStoreFilterHtml.'
								</select>
							</td>
							<td>
								<select name="seriesAggregation[]">
									<option value="SUM">Sum</option>
									<option value="COUNT">Count</option>
								</select>
							</td>
							<td id="commands">
								<a title="Delete Product" id="deleteRow"><span class="ui-icon ui-icon-trash"></span></a>
							</td>
							
						</tr>';
	}
		
	// Page PHP Backend Code End
?>
		<script type="text/javascript">
			$(function() {
				$("input.submit-button").button();
			});
			$(function() {
				$("a.cancel-button").button();
			});
			
			
			$(function() {
				$("#commands a#deleteRow").button();
				$("#commands a#deleteRow").click(function() {
					$(this).parent().parent().remove();
				});
			
				$series = <?=$noSeries;?> + 1;
				$("input.add-series").button();
				
				$("input.add-series").click(function() {
					$("#seriesTable tr:last").after('<tr><td>Series '+ $series++ +':</td><td><input type="text" name="seriesName[]" size="15" value="" /></td><td><select name="seriesData[]"><?=$seriesColHtml ?></select></td><td><select name="seriesStoreFilter[]"><option value="">N/A</option><?=$seriesStoreFilterHtml?></select></td><td><select name="seriesAggregation[]"><option value="SUM">Sum</option><option value="COUNT">Count</option></select></td><td id="commands"><a title="Delete Product" id="deleteRow"><span class="ui-icon ui-icon-trash"></span></a></td></tr>');
					$("#commands a#deleteRow").button();
					
					$("#commands a#deleteRow").click(function() {
						$(this).parent().parent().remove();
					});
				});
				
				
				
				
			});
		</script>
			<div>
		
			<form method="POST" action="?do=submit">
				<fieldset>
					<legend>Chart Data</legend>
					<h3>Axes Data:</h3>
					<table>
						<tr>
							<td>X-Axis Name: </td>
							<td>
								<input type="text" name="xAxisName" size="15" value="<?php print $xAxisName; ?>" />*
							</td>
							<td></td>
						</tr>
						<tr>
							<td>X-Axis Data: </td>
							<td>
								<select name="xAxisData">
									<?=$xAxisColHtml;?>
								</select>
							</td>
							<td>
							<!--	<select name="xAxisFormat">
									<option value="Month/Year" <?php if($dataView == "SALES_VIEW") print "selected"; ?>>Month/Year</option>
									<option value="Day/Month" <?php if($dataView == "AUTHOR_VIEW") print "selected"; ?>>Day/Month</option>
								</select> -->
							</td>
						</tr>
						<tr>
							<td>Y-Axis Name: </td>
							<td>
								<input type="text" name="yAxisName" size="15" value="<?php print $yAxisName; ?>" />*
							</td>
							<td>	
							</td>
						</tr>
						<tr>
							<td>Y-Axis Unit: </td>
							<td>
								<input type="text" name="yAxisUnit" size="15" value="<?php print $yAxisUnit; ?>" />
							</td>
							<td>	
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</table>
					<h3>Series Data:</h3>
					<table id="seriesTable">
						<thead>
							<td></td>
							<td><strong>Series Name</strong></td>
							<td><strong>Series Data</strong></td>
							<td><strong>Store Filter</strong></td>
							<td><strong>Series Aggregation</strong></td>
						</thead>
						<?=$seriesHtml ?>
					</table>
					<input type="button" value="Add Series" class="add-series" />
					<div style="margin:15px 0 0 0;">
						<a href="createChart1.php" class="cancel-button">Back</a>
						<input type="submit" value="Next Step" class="submit-button" style="float:right;" />
					</div>
				</fieldset>
			</form>
			
			</div>
			<div class="clear"></div>	
<?php	
	$page->getFooter();
?>