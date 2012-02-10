<?php
	require_once('../App.php');
	require_once('../AppClasses/Chart.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Chart Detail";
		$page->getHeader();

		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=chartManagement.php">log in</a>.');
		}
		
		if(isset($_GET['chartId']) && is_numeric($_GET['chartId'])) {
			$chart = Chart::getChart($_GET['chartId']);
			$_SESSION['CHARTWIZARD'] = serialize($chart);
		} else if(isset($_SESSION['CHARTWIZARD'])) {
			$chart = unserialize($_SESSION['CHARTWIZARD']);
		} else {
			$chart = new Chart();
		}
		
		if(isset($_GET['do']) && $_GET['do'] == "submit") {
			if(empty($_POST['chartName'])) {
				$page->error("The Chart Name field is mandatory must be completed.");
			} else if(empty($_POST['chartType'])) {
				$page->error("The Chart Type is mandatory must be selected.");
			} else {
				// Set the values
				$chart->chartName = $_POST['chartName'];
				$chart->chartType = $_POST['chartType'];
				$chart->dataView = $_POST['dataView'];
				$chartName = $chart->chartName;
				$chartType = $chart->chartType;
				$dataView = $chart->dataView;
				// 3600 is one hour.
				$_SESSION['CHARTWIZARD'] = serialize($chart);
				header('Location: createChart2.php');
			}
		} else if($_GET['do'] == "cancel") {
			if($chart->isLoaded)
				$chart->delete();
			unset($_SESSION['CHARTWIZARD']);
			$chart = null;
			header('Location: chartManagement.php');
		} else {
			if(isset($_SESSION['CHARTWIZARD'])) {
				$chartName = $chart->chartName;
				$chartType = $chart->chartType;
				$dataView = $chart->dataView;
			} else {
				$chartName = $_POST['chartName'];
				$chartType = $_POST['chartType'];
				$dataView = $_POST['dataView'];
			}
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
		</script>
			<div>
				<div style="border:1px solid #000; float:right; width: 200px;min-height:100px;">
					<h4>Steps:</h4>
				</div>
			
			<form method="POST" action="createChart1.php?do=submit">
				<fieldset>
					<legend>Chart Detail</legend>
					<table>
						<tr>
							<td>Chart Name:</td>
							<td><input type="text" name="chartName" size="15" value="<?php print $chartName; ?>" />*</td>
						</tr>
						<tr>
							<td>Chart Type: </td>
							<td><input type="radio" name="chartType" value="Bar" <?php if($chartType == "Bar") print "checked"; ?> />Bar  
								<input type="radio" name="chartType" value="Line" <?php if($chartType == "Line") print "checked"; ?> />Line *
							</td>
						</tr>
						<tr>
							<td>Data View: </td>
							<td>
								<select name="dataView">
									<option value="SALES_VIEW_V2" <?php if($dataView == "SALES_VIEW_V2") print "selected"; ?>>Sales View</option>
									<option value="AUTHOR_VIEW" <?php if($dataView == "AUTHOR_VIEW") print "selected"; ?>>Author View</option>
								</select>*
							</td>
						</tr>
					</table>
				
					
					<div style="margin:15px 0 0 0;">
						<a href="?do=cancel" class="cancel-button">Cancel</a>
						<input type="submit" value="Next Step" class="submit-button" style="float:right;" />
					</div>
				</fieldset>
			</form>
			
			
			
			</div>
			
			<div class="clear"></div>
			
<?php	
	$page->getFooter();
?>