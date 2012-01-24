<?php
	require_once('../App.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Chart Management";
		$page->getHeader();

		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=chartManagement.php">log in</a>.');
		}
	
		// Get the chart list and populate table.
		$allCharts = App::getDB()->getArrayFromDB("SELECT chartId, chartName, chartType FROM chart");
		$chartHtml = "";
		
		
		foreach($allCharts as $arr) {
			$chartHtml .= "<tr>" . PHP_EOL;
			$chartHtml .= "	<td>".$arr['chartName']."</td>" . PHP_EOL;
			$chartHtml .= "	<td>".$arr['chartType']."</td>" . PHP_EOL;
			$chartHtml .= '	<td class="options" style="width:20px;"><a href="createChart1.php?chartId='.$arr['chartId'].'" title="Modify Chart"><span class="ui-icon ui-icon-pencil"></span></a></td>';
			$chartHtml .= "</tr>" . PHP_EOL;
		}
		
		
	
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				$("p.result").hide();
				$("img.spinningWheel").hide();
				$("a#addChart").button();
			
				
				$('#chartList').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
			
				$(".options a").button();
				$('#storelist').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
			
			
			
			
			
			
	});
		</script>

			<p><a id="addChart" href="createChart1.php">Create New Chart</a></p>
			
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="chartList">
				<thead>
					<tr>
						<th>Chart Name</th>
						<th>Chart Type</th>
						<th>Options</th>
					</tr>
				</thead>
				<tbody>
					<?php print $chartHtml; ?>
				</tbody>
			</table>
<?php	
	$page->getFooter();
?>