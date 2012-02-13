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
			$chartHtml .= '<tr id="'.$arr['chartId'].'">' . PHP_EOL;
			$chartHtml .= "	<td>".$arr['chartName']."</td>" . PHP_EOL;
			$chartHtml .= "	<td>".$arr['chartType']."</td>" . PHP_EOL;
			$chartHtml .= '	<td class="options" style="width:40px;"><a href="createChart1.php?chartId='.$arr['chartId'].'" title="Modify Chart"><span class="ui-icon ui-icon-pencil"></span></a><a href="chartPermission.php?chartId='.$arr['chartId'].'" title="Modify Chart Permissions"><span class="ui-icon ui-icon-locked"></span></a><a title="Delete Chart" id="delete-chart"><span class="ui-icon ui-icon-trash"></span></a></td>';
			$chartHtml .= "</tr>" . PHP_EOL;
		}
		
		
	
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				chartId = 0;
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
			
				$("a#delete-chart").button().click(function() {
					chartId = $(this).parent().parent().attr("id")
					$( "#dialog-confirm-delete" ).dialog( "open" );
				});
			
			
				$( "#dialog-confirm-delete" ).dialog({
					autoOpen: false,
					resizable: false,
					height:170,
					modal: true,
					buttons: {
						"Delete chart": function() {
							$.post( "createChart1.php?chartId="+chartId+"&do=cancel");
						location.reload();
						},
						Cancel: function() {
							$( this ).dialog( "close" );
						}
					}
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
			
	<div id="dialog-confirm-delete" title="Delete chart?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This chart will be deleted for ALL users.  Are you sure this is what you wish to do?</p>
	</div>

<?php	
	$page->getFooter();
?>