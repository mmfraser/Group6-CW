<?php
	require_once('../App.php');
	require_once('../AppClasses/Artist.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Artist Management - Modify Artist";
		$page->getHeader();
		
		$errorMsg = null;
		$infoMsg = null;
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=userManagement.php">log in</a>.');
		}
		
		if(isset($_GET['artistId']) && is_numeric($_GET['artistId'])) { 
			$artist = new Artist();
			$artist->populateId($_GET['artistId']);
		} else {
			App::fatalError($page, 'You must select an Artist in which to edit.  Please go back to the <a href="artistManagement.php">Artist Management</a> page.');
		}
		
		if(isset($_POST['bandName'])) {
			$bandName = $_POST['bandName'];
			$forename = $_POST['forename'];
			$surname = $_POST['surname'];
			$websiteUrl = $_POST['website'];
			$dob = $_POST['dob'];
			$nationality = $_POST['nationality'];
		} else {
			$bandName = $artist->bandName;
			$forename = $artist->forename;
			$surname = $artist->surname;
			$websiteUrl = $artist->websiteUrl;
			$dob = $artist->dob;
			$nationality = $artist->nationality;
		}
		
		if(isset($_GET['do']) && $_GET['do'] == "updateArtist" && isset($_GET['artistId'])) {
			try {
				$artist->bandName = $_POST['bandName'];
				$artist->forename = $_POST['forename'];
				$artist->surname = $_POST['surname'];
				$artist->websiteUrl = $_POST['website'];
				$artist->dob = $_POST['dob'];
				$artist->nationality = $_POST['nationality'];
			
				$artist->save();
				$infoMsg = "Artist updated successfully.";	
			} catch (Exception $e) {
				$errorMsg = $e->getMessage();
			}
		} 

		$nationalities = App::getDB()->getArrayFromDB("SELECT nationality FROM nationality");
		$nationalitiesList = "";
		$i = 0;
		$countNationalities = count($nationalities);
		foreach($nationalities as $arr) {
			if($i < ($countNationalities - 1))
				$nationalitiesList .= '"'.$arr["nationality"] . '", ';
			else 
				$nationalitiesList .= '"'. $arr["nationality"] . '"';
			$i++;
		}
		
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				var availableTags = [<?php print $nationalitiesList ?>];
				$( "div#tabs" ).tabs({cookie:{}});
				$("input.submit-button").button();
				$("a.back-artist-mgmt").button();
				$( "#nationality" ).autocomplete({
					source: availableTags
				});
				$( "#dob" ).datepicker({ dateFormat: 'yy-mm-dd',  yearRange: '1900:' + new Date().getFullYear(), maxDate: '0', changeYear: true, changeMonth: true, showOn: 'button'   });

			});
		</script>

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">General</a></li>
		</ul>
		<div id="tabs-1">
			<p class="validateTips">All form fields are required.</p>
			
			<form method="POST" id="addUsr" action="?do=updateArtist&amp;artistId=<?php print $_GET['artistId']; ?>">
				<table>
				<tr>
					<td><label for="bandName">Band Name</label><td>
					<td><input type="text" name="bandName" size="15" value="<?php print $bandName ?>" /></td>
				</tr>
				<tr>
					<td><label for="forename">Forename</label><td>
					<td><input type="text" name="forename" size="15" value="<?php print $forename ?>"  /></td>
				</tr>
				<tr>
					<td><label for="surname">Surname</label><td>
					<td><input type="text" name="surname" size="15" value="<?php print $surname ?>" /></td>
				</tr>
				<tr>
					<td><label for="dob">Date of Birth</label><td>
					<td><input type="text" name="dob" size="15" id="dob" value="<?php print $dob ?>" /></td>
				</tr>
				<tr>
					<td><label for="nationality">Nationality</label><td>
					<td><input type="text" name="nationality" size="15" id="nationality" value="<?php print $nationality ?>" /></td>
				</tr>
				<tr>
					<td><label for="website">Website</label><td>
					<td><input type="text" name="website" size="30" value="<?php print $websiteUrl ?>" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><input type="submit" value="Update" class="submit-button" /></td>
				</tr>
			</table>
				<div class="ui-state-error ui-corner-all" style="<?php if($errorMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-alert" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $errorMsg; ?></span></div>
			<div class="ui-state-info ui-corner-all" style="<?php if($infoMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-info" style="float:left;margin:0px 5px 0 0;"></span><span><?php print $infoMsg; ?></span></div>
			</form>
			<p><a href="artistManagement.php" class="back-artist-mgmt">Back to User Artist</a></p>
		</div>	
	</div>

	
<?php	
	$page->getFooter();
?>