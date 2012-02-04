<?php
	require_once('../App.php');
	require_once('../AppClasses/DashboardTab.php');

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
		
		if((isset($_GET['tabId']) && is_numeric($_GET['tabId']))) {
			// Set a cookie and get the charts for that tab.
			setcookie("tabId", $_GET['tabId'], time()+2592000);
			
			// Get the tab	
			$tab = new DashboardTab();
			$tab->populateId($_GET['tabId']);
		} else if(isset($_COOKIE['tabId']) && is_numeric($_COOKIE['tabId'])) {
			$tab = new DashboardTab();
			$tab->populateId($_COOKIE['tabId']);
		}
		
		// Generate a dropdown for use if a user wishes to change a chart.
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
			
			<?php print $tab->getTabLayoutHtml(); ?>
			
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
			
			<div id="filter-chart" title="Filter Chart">
				
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
			
				$('#filter-chart').dialog({
				autoOpen: false,
				height: 250,
				width: 500,
				modal: true,
				buttons: {
					"Change Fiter": function() {
						//alert($(this).parent().html());
					
						$("img.spinningWheel").show();
						var $form = $( this );
						
						
						/*$form.find( 'input[name^=value]' ).each(function() {
							alert($(this).val());
						});*/
						
						dashLayoutId = $form.find( 'input[name="dashLayoutId"]' ).val();
						filterNameArr = $form.find( 'input[name^=filterName]' );
						valueArr = $form.find( 'input[name^=value]' );
						queryString = "";

						for(var i = 0; i < filterNameArr.length; i++) {
							queryString += filterNameArr[i].value + "=>" + valueArr[i].value +";";
						}

						/* Send the data using post and put the results in a div */
						$.post( "ajaxFunctions.php?do=changeFilter", { filterQuery: queryString, layoutId: dashLayoutId},
						  function( data ) {
							$("p.resultcf").show();
							$("span.resultcf").empty().append(data);
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
				
				})

				$(".changeFilter").button().click(function() {
					chartPos = $(this).parent().attr("id");
					chartId = $(this).find('img').html();
				//	alert(chartId);
				
					$('#filter-chart').load('chartFilterPopUp.php?tabId=' +  readCookie('tabId') + "&chartPos=" + chartPos);
					$( "#filter-chart" ).dialog( "open" );
				});
				
				$(".changeChart").button().click(function() {
					chartPos = $(this).parent().attr("id");
					chartId = $(this).find('img').html();
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