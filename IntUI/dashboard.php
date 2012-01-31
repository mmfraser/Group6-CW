<?php
	require_once('../App.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Dashboard";
		$page->getHeader();

		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=chartManagement.php">log in</a>.');
		}
	
		// Get the chart list and populate table.
	
		
	
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			
		</script>

		<ul id="dashboardTabs">
			<li><a href="#">Tab 1</a></li>
			<li><a href="#">Tab 2</a></li>
			<li><a href="#">Tab 3</a></li>
		</ul>
		<div id="">
		<table id="dashboardTable">
			<tr>
				<td><img src="../AppClasses/drawChart.php?chartId=97" style="clear:both;" /></td>
				<td><img src="../AppClasses/drawChart.php?chartId=97" style="clear:both;" /></td>
				<td><img src="../AppClasses/drawChart.php?chartId=97" style="clear:both;" /></td>
			</tr>
			<tr>
				<td><img src="../AppClasses/drawChart.php?chartId=97" style="clear:both;" /></td>
				<td><img src="../AppClasses/drawChart.php?chartId=97" style="clear:both;" /></td>
				<td><img src="../AppClasses/drawChart.php?chartId=97" style="clear:both;" /></td>
			</tr>
		</table>
		</div>
<?php	
	$page->getFooter();
?>