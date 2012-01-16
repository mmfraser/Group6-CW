<?php
	require_once('../App.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Chart Detail";
		$page->getHeader();

		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=chartManagement.php">log in</a>.');
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
			
			<form method="POST" action="createChart2.php">
				<fieldset>
					<legend>Chart Detail</legend>
					Chart Name: <input type="text" name="chartName" size="15" value="<?php if(isset($_POST['chartName'])) print $_POST['chartName']; ?>" /><br />
					Chart Type: 
					<input type="radio" name="chartType" value="Bar" />Bar  
					<input type="radio" name="chartType" value="Line" />Line
					<div style="margin:15px 0 0 0;">
						<a href="chartManagement.php" class="cancel-button">Cancel</a>
						<input type="submit" value="Next Step" class="submit-button" style="float:right;" />
					</div>
				</fieldset>
			</form>
			
			
			
			</div>
			
			<div class="clear"></div>
			
<?php	
	$page->getFooter();
?>