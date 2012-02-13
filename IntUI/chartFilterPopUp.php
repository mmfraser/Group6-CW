<?php
	require_once('../App.php');
	require_once('../AppClasses/Chart.php');
	
	$dashRow = App::getDB()->getDataRow("SELECT * FROM dashboardlayout WHERE tabId = '" . mysql_real_escape_string($_GET['tabId'])."' AND chartPos = '".mysql_real_escape_string($_GET['chartPos'])."'");
	
	if($dashRow == null) 
		die("<p><strong>An error has occured, please try again or contact Support.</strong></p>");
	
	
	$chart = Chart::getChart($dashRow['chartId']);
	
	if(count($chart->sqlFilter) < 1) 
		die("<p><strong>There are currently no filters defined on this chart.  Please edit the chart to add filters.</strong></p>");
	
	$signs = array();
	$signs['eq'] = "equals";
	$signs['neq'] = "does not equal";
	$signs['gte'] = "greater than or equal to";
	$signs['gt'] = "greater than";
	$signs['lt'] = "less than";
	$signs['lte'] = "less than or equal to";
	
	$custFilter = null;
	if($dashRow['customFilter'] != null) 
		$custFilter = unserialize($dashRow['customFilter']);
	
	
	$html = '<input type="hidden" name="dashLayoutId" value="'.$dashRow['dashboardLayoutId'].'" />';
	$html .= '<table width="100%">' . PHP_EOL;
	$html .= "<thead><td><strong>Combinator</strong></td><td><strong>Column Name</strong></td><td><strong>Operator</strong></td><td><strong>Value</strong></td></thead>";
	foreach($chart->sqlFilter as $filterName => $filter) {
		$html .= "<tr>" . PHP_EOL;
		$html .= "		<td>".$filter['combinator']."</td>" . PHP_EOL;
		$html .= "		<td>".$filter['dbAlias']."</td>" . PHP_EOL;
		$html .= "		<td>".$signs[$filter['operator']]."</td>" . PHP_EOL;
		
		// If an override of the filter is set, use its value.
		if($custFilter != null) 
			$filterValue = $custFilter[$filterName];
		else 
			$filterValue = $filter['value'];
		
		
		$html .= '		<td><input type="hidden" name="filterName[]" value="'.$filterName.'" /><input type="text" name="value[]" value="'.$filterValue.'" /></td>' . PHP_EOL;
		$html .= "</tr>" . PHP_EOL;
	}
	
	$html .= "</table>" . PHP_EOL;
	
	$html .= '<p class="resultcf" style="display:none;"><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
			<span class="resultcf"></span></p>';
	print $html;
	
	/*$filter['dbAlias'] = $dbAlias;
			$filter['operator'] = $operator;
			$filter['value'] = $value;
			$filter['combinator'] = $combinator;
			$filter['sql'] = $sql;*/

?>