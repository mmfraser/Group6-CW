<?php
	require_once('../App.php');
	require_once('../AppClasses/Chart.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Chart Preview";
		$page->getHeader();

		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=chartManagement.php">log in</a>.');
		}
		
		if(isset($_SESSION['CHARTWIZARD'])) {
			$chart = unserialize($_SESSION['CHARTWIZARD']);
		} else {
			$chart = new Chart();
		}
		
	
		
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				$("a.cancel-button").button();
			});
			$(function() {
				$("a.back-button").button();
			});
		</script>
			<div>
				<div style="border:1px solid #000; float:right; width: 200px;min-height:100px;">
					<h4>Steps:</h4>
				</div>
				<a href="createChart2.php" class="back-button" style="margin-top:15px;">Back</a>
				<a href="createChart1.php?do=cancel" class="cancel-button" style="margin-top:15px;">Delete Chart</a>
				<img src="../AppClasses/drawChart.php?chartId=<?=$chart->chartId?>" style="clear:both;" />

				
			</div>
			
			<div class="clear"></div>
			
<?php	
	$page->getFooter();
?>