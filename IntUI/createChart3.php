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
						<table>
							<thead>
								<td>Column</td><td>Operator</td><td>Value</td><td>Combinator</td>
							</thead>
							<tr>
								<td>
									<select name="filterCol[]">
										<?=$filterColHtml;?>
									</select>
								</td>
								<td>
									<select name="filterOperator[]" class="operator">
										<option value="eq" <?php if($operator == "eq") print "selected"; ?>>equals</option>
										<option value="neq" <?php if($operator == "neq") print "selected"; ?>>not equal</option>
										<option value="lte" <?php if($operator == "lte") print "selected"; ?>>less than or equal to</option>
										<option value="lt" <?php if($operator == "lt") print "selected"; ?>>less than</option>
										<option value="gte" <?php if($operator == "gte") print "selected"; ?>>greater than or equal to</option>
										<option value="gt" <?php if($operator == "gt") print "selected"; ?>>greater than</option>
										<option value="between" <?php if($operator == "between") print "selected"; ?>>between</option>
									</select>
								</td>
								<td class="value">
									<input type="text" name="value[]" size="15" /> <span class="between" style="display:none;">&amp; <input type="text" name="value1[]" size="15" /></span>
								</td>
								<td>
									<select name="filterCombinator[]">
										<option value="AND" <?php if($operator == "AND") print "selected"; ?>>AND</option>
										<option value="OR" <?php if($operator == "OR") print "selected"; ?>>OR</option>
									</select>
								</td>
							</tr>
						</table>
					</form>
					
				</fieldset>
				
				<fieldset>
					<legend>Chart Preview</legend>
					<h3>Chart Preview:</h3>
					<img src="../AppClasses/drawChart.php?chartId=<?=$chart->chartId?>" style="clear:both;" />
				</fieldset>
				
				<a href="createChart2.php" class="back-button" style="margin-top:15px;">Back</a>
				<a href="createChart1.php?do=cancel" class="cancel-button" style="margin-top:15px;">Delete Chart</a>

				
			</div>
			
			<div class="clear"></div>
			
			<script type="text/javascript">
				$(function() {
					$("a.cancel-button").button();
				});
				$(function() {
					$("a.back-button").button();
				});
				
				$('select.operator').change(function() {
					if($(this).val() == "between") {
						alert("here");
					} else {
						//alert($('.between', $(this).parent()).html());
						//alert($(this).parent('.between').html());
						alert($(this).parent().parent('').html());
				
					}
				});
			</script>
<?php	
	$page->getFooter();
?>