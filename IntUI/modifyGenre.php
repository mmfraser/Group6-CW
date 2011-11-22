<?php
	require_once('../App.php');
	require_once('../AppClasses/Genre.php');
	
	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Genre Management - Modify Genre";
		$page->getHeader();
		
		$errorMsg = null;
		$infoMsg = null;
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=genreManagement.php">log in</a>.');
		}
		
		if(isset($_GET['genreId']) && is_numeric($_GET['genreId'])) { 
			$genre = new Genre();
			$genre->populateId($_GET['genreId']);
		} else {
			App::fatalError($page, 'You must select a Genre in which to edit.  Please go back to the <a href="genreManagement.php">Genre Management</a> page.');
		}
		
		if(isset($_POST['genreName'])) {
			$genreName = $_POST['genreName'];
		} else {
			$genreName = $genre->genreName;
		}
		
		if(isset($_GET['do']) && $_GET['do'] == "updateGenre" && isset($_GET['genreId'])) {
			try {
				$genre->genreName = $_POST['genreName'];
				$genre->save();
				$infoMsg = "Genre updated successfully.";	
			} catch (Exception $e) {
				$errorMsg = $e->getMessage();
			}
		}
		
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				$( "div#tabs" ).tabs({cookie:{}});
				$("input.submit-button").button();
				$("a.back-str-mgmt").button();
			});
		</script>

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">General</a></li>
			<!--<li><a href="#tabs-2">Group Membership</a></li>-->
		</ul>
		<div id="tabs-1">
			<p class="validateTips">All form fields are required.</p>
			
			<form method="POST" id="addStr" action="?do=updateGenre&amp;genreId=<?php print $_GET['genreId']; ?>">
				<table>
				<tr>
					<td><label for="storeName">Genre Name</label><td>
					<td><input type="text" name="genreName" size="15" value="<?php print $genreName ?>" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><input type="submit" value="Update" class="submit-button" /></td>
				</tr>
			</table>
				<div class="ui-state-error ui-corner-all" style="<?php if($errorMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-alert" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $errorMsg; ?></span></div>
			<div class="ui-state-info ui-corner-all" style="<?php if($infoMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-info" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $infoMsg; ?></span></div>
			</form>
			<p><a href="genreManagement.php" class="back-str-mgmt">Back to Genre Management</a></p>
		</div>
		
		</div>
		
	</div>

	
<?php	
	$page->getFooter();
?>