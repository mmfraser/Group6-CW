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
		
	
		if(isset($_GET['do']) && $_GET['do'] == "submit") {
		
				// Set the values
				$xAxisAlias = $chart->addSQLColumn($_POST['xAxisData'], $chart->dataView, $_POST['xAxisData'], null);
				
				$chart->setAbscissa($_POST['xAxisName'], $_POST['xAxisData'], $xAxisAlias);
				
				$xAxisData = $chart->abscissa['dbCol'];
				$xAxisName = $chart->abscissa['name'];
	
				// 3600 is one hour.
				setcookie("CHARTWIZARD", serialize($chart), time()+3600);
		}
	
	
	// View data columns
	$viewCols = App::getDB()->getArrayFromDB("SHOW COLUMNS FROM " . $chart->dataView);
	
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
				$series = 5;
				$("input.add-series").button();
				
				$("input.add-series").click(function() {
					$("#seriesTable tr:last").after('<tr><td>Series '+ ++$series +':</td><td><input type="text" name="seriesName[]" size="15" value="<?php print $xAxisName; ?>" /></td><td><select name="seriesAgg[]"><option value="SUM">Sum</option><option value="COUNT" >Count</option></select></td><td></td></tr>');
				
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
							<td><strong>Aggreation</strong></td>
							<td></td>
						</thead>
						<tr>
							<td>Series 1:</td>
							<td>
								<input type="text" name="seriesName[]" size="15" value="<?php print $xAxisName; ?>" />
							</td>
							<td>
								<select name="seriesData[]">
									<option value="SUM" <?php if($dataView == "SALES_VIEW") print "selected"; ?>>Sum</option>
									<option value="COUNT" <?php if($dataView == "AUTHOR_VIEW") print "selected"; ?>>Count</option>
								</select>
							</td>
							<td>
								<select name="seriesAggregation[]">
									<option value="SUM" <?php if($dataView == "SALES_VIEW") print "selected"; ?>>Sum</option>
									<option value="COUNT" <?php if($dataView == "AUTHOR_VIEW") print "selected"; ?>>Count</option>
								</select>
							</td>
							<td></td>
						</tr>
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