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
	
		if(isset($_COOKIE['CHARTWIZARD'])) {
			$chart = unserialize($_COOKIE['CHARTWIZARD']);
		} else {
			App::fatalError($page, 'You must first select which data(view) to use in the chart.  Please go <a href="createChart1.php">back</a> and do this.');
		}
		
		$viewCols = App::getDB()->getArrayFromDB("SHOW COLUMNS FROM " . $chart->dataView);
		
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
					$xAxisAlias = $chart->addSQLColumn($_POST['xAxisData'], $chart->dataView, $_POST['xAxisData'], null);
					
					$chart->setAbscissa($_POST['xAxisName'], $_POST['xAxisData'], $xAxisAlias);
					$chart->addSQLGroupBy($xAxisAlias, $chart->dataView);
					print_r($chart);
					$xAxisData = $chart->abscissa['dbCol'];
					$xAxisName = $chart->abscissa['name'];
					$yAxisName = $_POST['yAxisName'];
					$yAxisUnit = $_POST['yAxisUnit'];
					
					
					$chart->setYAxis($_POST['yAxisName'], $_POST['yAxisUnit'], "AXIS_POSITION_LEFT");
					
					$noSeries = count($_POST['seriesName']);
					
					function seriesRow($seriesNo, $seriesName, $seriesData, $seriesAggeregation, $viewCols) {
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
												<select name="seriesAggregation[]">
													<option value="SUM" '.$sumSel.'>Sum</option>
													<option value="COUNT" '.$countSel.'>Count</option>
												</select>
											</td>
										</tr>';
						return $seriesHtml;
					}
					$seriesHtml = "";
					for($i = 0; $i < $noSeries; $i++) {
						//addChartSeries($seriesName, $dbCol, $description, $axisNo)
						
						if($_POST['seriesName'][$i] == null)
							$error = true;
						else {
							$chart->addSQLColumn($_POST['seriesData'][$i], $chart->dataView, $_POST['seriesData'][$i], $_POST['seriesAggregation'][$i]);
							$chart->addChartSeries($_POST['seriesName'][$i], $_POST['seriesData'][$i], $_POST['seriesName'][$i], 0);
						}
					
						$seriesHtml .= seriesRow($i+1, $_POST['seriesName'][$i], $_POST['seriesData'][$i], $_POST['seriesAggregation'][$i], $viewCols);
					}
					
					// 3600 is one hour.
					setcookie("CHARTWIZARD", serialize($chart), time()+3600);
				} catch (Exception $e) {
					$page->error($e->getMessage());
				}
				
				if($error == true) {
					print "fix errors";
				} else {
					print "saved";
					$chart->save();
				}
				
		} else {
			if(isset($_COOKIE['CHARTWIZARD'])) {
				$xAxisData = $chart->abscissa['dbCol'];
				$xAxisName = $chart->abscissa['name'];
				$yAxisName = $chart->axes[0]['name'];
				$yAxisUnit = $chart->axes[0]['unit'];
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
							<td>Series 1:</td>
							<td>
								<input type="text" name="seriesName[]" size="15" />
							</td>
							<td>
								<select name="seriesData[]">
									'.$seriesColHtml.'
								</select>
							</td>
							<td>
								<select name="seriesAggregation[]">
									<option value="SUM">Sum</option>
									<option value="COUNT">Count</option>
								</select>
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
				$series = <?=$noSeries;?> + 1;
				$("input.add-series").button();
				
				$("input.add-series").click(function() {
					$("#seriesTable tr:last").after('<tr><td>Series '+ $series++ +':</td><td><input type="text" name="seriesName[]" size="15" value="" /></td><td><select name="seriesData[]"><?=$seriesColHtml ?></select></td><td><select name="seriesAggregation[]"><option value="SUM">Sum</option><option value="COUNT">Count</option></select></td></tr>');
				
				});
				
			});
		</script>
			<div>
				<div style="border:1px solid #000; float:right; width: 200px;min-height:100px;">
					<h4>Steps:</h4>
					
				</div>
			
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
								<select name="xAxisFormat">
									<option value="Month/Year" <?php if($dataView == "SALES_VIEW") print "selected"; ?>>Month/Year</option>
									<option value="Day/Month" <?php if($dataView == "AUTHOR_VIEW") print "selected"; ?>>Day/Month</option>
								</select>
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