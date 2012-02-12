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
		
		$viewCols = App::getDB()->getArrayFromDB("SHOW COLUMNS FROM " . $chart->dataView);
		
		$filterColHtml = "";
		foreach($viewCols as $col) {
			$filterColHtml .= '<option value="'.$col['Field'].'" '. $selected.'>'.$col['Field'].'</option>';
		}
		
		function filterRow($filterCol, $filterOperator, $value, $filterCombinator, $viewCols) {	
			$filterColHtml = "";
			foreach($viewCols as $col) {
				if($col['Field'] == $filterCol)
					$selected = "selected";
				else 
					$selected = "";
				$filterColHtml .= '<option value="'.$col['Field'].'" '. $selected.'>'.$col['Field'].'</option>';
			}

			if($filterOperator == "eq") {
				$eq = "selected";
			} else if($filterOperator == "neq") {
				$neq = "selected";
			} else if($filterOperator == "lte") {
				$lte = "selected";
			} else if($filterOperator == "lt") {
				$lt = "selected";
			} else if($filterOperator == "gte") {
				$gte = "selected";
			} else if($filterOperator == "gt") {
				$gt = "selected";
			}
			
			if($filterCombinator == "AND") {
				$and = "selected";
			} else if($filterCombinator == "OR") {
				$or = "selected";
			}
			
			$filterHtml = '<tr>
					<td>
						<select name="filterCol[]">
							'.$filterColHtml.'
						</select>
					</td>
					<td>
						<select name="filterOperator[]" class="operator">
							<option value="eq" '.$eq.'>equals</option>
							<option value="neq" '.$neq.'>not equal</option>
							<option value="lte" '.$lte.'>less than or equal to</option>
							<option value="lt" '.$lt.'>less than</option>
							<option value="gte" '.$gte.'>greater than or equal to</option>
							<option value="gt" '.$gt.'>greater than</option>
						</select>
					</td>
					<td class="value">
						<input type="text" name="value[]" size="15" value="'.$value.'" /> <span class="between" style="display:none;">&amp; <input type="text" name="value1[]" size="15" /></span>
					</td>
					<td>
						<select name="filterCombinator[]">
							<option value="AND" '.$and.'>AND</option>
							<option value="OR" '.$or.'>OR</option>
						</select>
					</td>
					<td id="commands"><a title="Delete Filter" id="deleteRow"><span class="ui-icon ui-icon-trash"></span></a></td>
				</tr>';
			return $filterHtml;
		}
		
		if(isset($_GET['do']) && $_GET['do'] == "submit") {
			$noFilters = count($_POST['filterCol']);
			$errMsg = "";
			$chart->sqlFilter = array();
			$filterHtml = "";
			for($i = 0; $i < $noFilters; $i++) {
				try {
					$chart->addFilter("Filter", $_POST['filterCol'][$i], $_POST['filterOperator'][$i], $_POST['value'][$i],  $_POST['filterCombinator'][$i], false);
				} catch(Exception $e) {
					$page->error($e->getMessage());
				}
				
				$filterHtml .= filterRow($_POST['filterCol'][$i], $_POST['filterOperator'][$i], $_POST['value'][$i], $_POST['filterCombinator'][$i], $viewCols);
			}
	
			$chart->save();
			$_SESSION['CHARTWIZARD'] = serialize($chart);
		} else if(isset($_GET['do']) && $_GET['do'] == "saveChart") {
			unset($_SESSION['CHARTWIZARD']);
			header('Location: chartManagement.php');
		}else {
			if(isset($chart)) {	
				$filterHtml = "";
				foreach($chart->sqlFilter as $filter) {
					$filterHtml .= filterRow($filter['dbAlias'], $filter['operator'], $filter['value'], $filter['combinator'], $viewCols);
				}	
			}
		}
					
	// Page PHP Backend Code End

?>
	
			<div>
				<div style="border:1px solid #000; float:right; width: 200px;min-height:100px;">
					<h4>Steps:</h4>
				</div>
				
				<fieldset>
					<legend>Chart Filter</legend>
					<h3>Chart Filter:</h3>
					
					<form method="POST" action="?do=submit">
						<table id="filterTable">
							<thead>
								<td><strong>Column</strong></td><td><strong>Operator</strong></td><td><strong>Value</strong></td><td><strong>Combinator</strong></td><td></td>
							</thead>						
							<?=$filterHtml;?>
						</table>
						<input type="button" value="Add Filter" class="add-filter" />
						<input type="submit" value="Submit" class="submit-button" />
						
					</form>
					
				</fieldset>
				
				<fieldset>
					<legend>Chart Preview</legend>
					<h3>Chart Preview:</h3>
					<img src="../AppClasses/drawChart.php?chartId=<?=$chart->chartId?>&amp;preview=true" style="clear:both;" />
				</fieldset>
				
				<a href="createChart2.php" class="back-button" style="margin-top:15px;">Back</a>
				<a href="createChart1.php?do=cancel" class="cancel-button" style="margin-top:15px;">Delete Chart</a>
				<a href="?do=saveChart" class="cancel-button" style="margin-top:15px;">Save Chart</a>
				<a href="chartPermission.php?chartId=<?=$chart->chartId?>" class="cancel-button" style="margin-top:15px;">Chart Permissions</a>
				
			</div>
			
			<div class="clear"></div>
			
			<script type="text/javascript">
				$(function() {
					$("a.cancel-button").button();
				});
				$(function() {
					$("a.back-button").button();
				});
				$(function() {
					$("input.submit-button").button();
				});
				
				$('select.operator').change(function() {
					
				});
				
				$("input.add-filter").button();
				$("#commands a#deleteRow").button();
				$("input.add-filter").click(function() {
					$("#filterTable tr:last").after('<tr><td><select name="filterCol[]"><?=$filterColHtml;?></select></td><td><select name="filterOperator[]" class="operator"><option value="eq">equals</option><option value="neq">not equal</option><option value="lte">less than or equal to</option><option value="lt">less than</option><option value="gte">greater than or equal to</option><option value="gt">greater than</option></select></td><td class="value"><input type="text" name="value[]" size="15" /> <span class="between" style="display:none;">&amp; <input type="text" name="value1[]" size="15" /></span></td><td><select name="filterCombinator[]"><option value="AND">AND</option><option value="OR">OR</option></select></td><td id="commands"><a title="Delete Product" id="deleteRow"><span class="ui-icon ui-icon-trash"></span></a></td></tr>');
					$("#commands a#deleteRow").button();
					
					$("#commands a#deleteRow").click(function() {
						$(this).parent().parent().remove();
					});
				});
				
				$("#commands a#deleteRow").click(function() {
						$(this).parent().parent().remove();
					});
			</script>
<?php	
	$page->getFooter();
?>