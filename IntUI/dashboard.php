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
	
		$dashboardTabs = App::getDB()->getArrayFromDB("SELECT tabId, tabName, tabDescription FROM dashboardtab WHERE userId = '".App::getAuthUser()->getUserId()."'");
	
		$tabsHtml = "";
		
		foreach($dashboardTabs as $tab) {
			$tabsHtml .= '<li><a href="?tabId='.$tab['tabId'].'" title="'.$tab['tabDescription'].'">'.$tab['tabName'].'</a></li>';
		}
		
		$dashboardHtml = "";
		if(isset($_GET['tabId']) && is_numeric($_GET['tabId'])) {
			// Set a cookie and get the charts for that tab.
			setcookie("tabId", $_GET['tabId'], time()+2592000);
			// Get the charts
			$dashboardTabs = App::getDB()->getArrayFromDB("SELECT chartPos, tabId, chartId FROM dashboardLayout WHERE tabId = '".mysql_real_escape_string($_GET['tabId'])."'");
			// Build dashboard table rows/columns
			$chartPos = 1;
			
			for($i = 1; $i <= App::$noRows; $i++) {
				$dashboardHtml .= "<tr>" . PHP_EOL;
				
				for($j = 1; $j <= App::$noCols; $j++) {
					// Get chart
					$chartId = -1;
					$colWidth = 100/App::$noCols;
					
					foreach($dashboardTabs as $chart) {
						if($chart['chartPos'] == $chartPos)
							$chartId = $chart['chartId'];
					}
					
					$dashboardHtml .= '		<td width="'.$colWidth.'%" id="'.$chartPos.'">'. PHP_EOL;
					$dashboardHtml .= '		<a title="Modify Chart" class="changeChart">Change Chart</a>';
					
					if($chartId == -1)
						$dashboardHtml .= '			No chart selected';
					else {
						$dashboardHtml .= '		<a title="Change Filter" class="changeFilter">Change Filter</a>';
						$dashboardHtml .= '			<img src="../AppClasses/drawChart.php?chartId='.$chartId.'" class="chart" alt="'.$chartId.'" />';
					}
					

					$dashboardHtml .= "		</td>". PHP_EOL;
					$chartPos++;
				}
				
				$dashboardHtml .= "</tr>" . PHP_EOL;
			}
		}
		
		$chartDDHtml = "";
		$allCharts = App::getDB()->getArrayFromDB("SELECT chartId, chartName FROM chart");
		
		foreach($allCharts as $chart) {
			$chartDDHtml .= '<option value="'.$chart['chartId'].'">'.$chart['chartName'].'</option>';
		}
	
	// Page PHP Backend Code End

?>
	

		<ul id="dashboardTabs">
			<?=$tabsHtml?>
			<li><a href="#">Create Tab</a></li>
		</ul>
		<div id="">
		<table id="dashboardTable">
			<?=$dashboardHtml?>
		</table>
		
		<div id="select-chart" title="Select Chart">
			<form method="POST" id="selectChart" action="">
			<table>
				<tr>
					<td><label for="chartName">Chart Name</label><td>
					<td>
						<select name="chartName">
							<?=$chartDDHtml;?>
						</select>
					</td>
				</tr>
			</table>
		</form>
		<p class="result"><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
			<span class="result"></span></p>
		</div>
	</div>
		
			<script type="text/javascript">
			 function readCookie(name) 
			{
				var nameEQ = name + "=";
				var ca = document.cookie.split( ';');
				for( var i=0;i < ca.length;i++) 
				{
						var c = ca[i];
						while ( c.charAt( 0)==' ') c = c.substring( 1,c.length);
						if ( c.indexOf( nameEQ) == 0) return c.substring( nameEQ.length,c.length);
				}
				return null;
			}

			$(function() {
				chartId = 0;
				chartPos = 0;
				
				$(".changeFilter").button();
				
				$(".changeChart").button().click(function() {
					chartPos = $(this).parent().attr("id");
					chartId = $(this).find('img').html();
				//	alert(chartId);
					$( "#select-chart" ).dialog( "open" );
				});
				
				$("p.result").hide();
				
				$( "#select-chart" ).dialog({
				autoOpen: false,
				height: 170,
				width: 410,
				modal: true,
				buttons: {
					"Change Chart": function() {
						$("img.spinningWheel").show();
						var $form = $( this ),
						newChartId = $form.find( 'select[name="chartName"]' ).val();
						//alert($form.find( 'select[name="chartName"]' ).val());

						/* Send the data using post and put the results in a div */
						$.post( "ajaxFunctions.php?do=selectChart", { chartId: newChartId, tabId: readCookie('tabId'), chartPos: chartPos},
						  function( data ) {			  
							$("p.result").show();
							$("span.result").empty().append(data);
						  }
						);
					},
					Cancel: function() {
						location.reload();
						$( this ).dialog( "close" );
					}
				},
				close: function() {
					location.reload();
					allFields.val( "" ).removeClass( "ui-state-error" );
				}
			});			
				
				
			});
			
			
		</script>
<?php	
	$page->getFooter();
?>