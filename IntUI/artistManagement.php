<?php
	require_once('../App.php');
	require_once('../AppClasses/Artist.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Artist Management";
		$page->getHeader();
		
		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=artistManagement.php">log in</a>.');
		}
	
		// Get the artist list and populate table.
		$allArtists = App::getDB()->getArrayFromDB("SELECT artistId FROM artist");
		$artistHtml = "";
		
		foreach($allArtists as $arr) {
			$artist = new Artist();
			$artist->populateId($arr['artistId']);
			$artistHtml .= "<tr id=\"".$arr['artistId']."\">" . PHP_EOL;
			$artistHtml .= "	<td>".$artist->bandName."</td>" . PHP_EOL;
			$artistHtml .= "	<td>".$artist->forename."</td>" . PHP_EOL;
			$artistHtml .= "	<td>".$artist->surname."</td>" . PHP_EOL;
			$artistHtml .= "	<td>".$artist->dob."</td>" . PHP_EOL;
			$artistHtml .= "	<td>".$artist->nationality."</td>" . PHP_EOL;
			$artistHtml .= "	<td><a href=\"".$artist->websiteUrl."\">".$artist->websiteUrl."</a></td>" . PHP_EOL;
			$artistHtml .= '	<td class="options" style=""><a href="modifyArtist.php?artistId='.$arr['artistId'].'" title="Modify User"><span class="ui-icon ui-icon-pencil"></span><a title="Delete User" id="deleteArtist"><span class="ui-icon ui-icon-trash"></span></a></td>';
			$userHtml .= "</tr>" . PHP_EOL;
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
				var artistId;
				
				$("p.result").hide();
				$("img.spinningWheel").hide();
				$( "div#tabs" ).tabs({cookie:{}});
				
				$("a#addArtist").button().click(function() {
					$( "#artist-dialog-form" ).dialog( "open" );
				});
				
				$( "#nationality" ).autocomplete({
					source: availableTags
				});

				
				$(".options a").button();
				
				$("a#deleteArtist").button().click(function() {
					artistId = $(this).parent().parent().attr("id")
					$( "#dialog-confirm-delete" ).dialog( "open" );
				});
				
				$('#artistList').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
				
				$( "#dob" ).datepicker({ formatDate: 'yyyy-mm-dd',  yearRange: '1900:' + new Date().getFullYear(), maxDate: '0', changeYear: true, changeMonth: true, showOn: 'button'   });

				$( "#dialog-confirm-delete" ).dialog({
					autoOpen: false,
					resizable: false,
					height:170,
					modal: true,
					buttons: {
						"Delete user": function() {
							$.post( "ajaxFunctions.php?do=deleteArtist", { artistId: artistId});
							location.reload();
						},
						Cancel: function() {
							$( this ).dialog( "close" );
						}
					}
				});
				
			$( "#artist-dialog-form" ).dialog({
				autoOpen: false,
				height: 350,
				width: 410,
				modal: true,
				buttons: {
					"Create Artist": function() {
						$("img.spinningWheel").show();
						var $form = $( this ),
						bandName = $form.find( 'input[name="bandName"]' ).val(),
						forename = $form.find( 'input[name="forename"]' ).val(),
						surname = $form.find( 'input[name="surname"]' ).val(),
						dob = $form.find( 'input[name="dob"]' ).val(),
						nationality = $form.find( 'input[name="nationality"]' ).val();
						website = $form.find( 'input[name="website"]' ).val();

						/* Send the data using post and put the results in a div */
						$.post( "ajaxFunctions.php?do=addArtist", { bandName: bandName, forename: forename, surname: surname, dob: dob, nationality: nationality, website: website },
						  function( data ) {
							$("img.spinningWheel").hide();					  
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
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Manage Artists</a></li>
		</ul>
		<div id="tabs-1">
			<p><a id="addArtist" href="#">Add New Artist</a></p>
			
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="artistList">
				<thead>
					<tr>
						<th>Band Name</th>
						<th>Forename</th>
						<th>Surname</th>
						<th>Date of Birth</th>
						<th>Nationality</th>
						<th>Website</th>
						<th>Options</th>
					</tr>

				</thead>
				<tbody>
					<?php print $artistHtml; ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Band Name</th>
						<th>Forename</th>
						<th>Surname</th>
						<th>Date of Birth</th>
						<th>Nationality</th>
						<th>Website</th>
						<th>Options</th>
					</tr>
				</tfoot>
			</table>
		</div>
		
	<div id="artist-dialog-form" title="Create new Artist">
			<p class="validateTips">All form fields are required.</p>

			<form method="POST" id="addArtist" action="?do=addArtist">
			<table>
				<tr>
					<td><label for="bandName">Band Name</label><td>
					<td><input type="text" name="bandName" size="15" /></td>
				</tr>
				<tr>
					<td><label for="forename">Forename</label><td>
					<td><input type="text" name="forename" size="15"  /></td>
				</tr>
				<tr>
					<td><label for="surname">Surname</label><td>
					<td><input type="text" name="surname" size="15" /></td>
				</tr>
				<tr>
					<td><label for="dob">Date of Birth</label><td>
					<td><input type="text" name="dob" size="15" id="dob" /></td>
				</tr>
				<tr>
					<td><label for="nationality">Nationality</label><td>
					<td><input type="text" name="nationality" size="15" id="nationality" /></td>
				</tr>
				<tr>
					<td><label for="website">Website</label><td>
					<td><input type="text" name="website" size="30" value="http://" /></td>
				</tr>
			</table>
		</form>
		<p><img src="../Images/spinningWheel.gif" class="spinningWheel" alt="Loading" /></p>
		<p class="result"><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
			<span class="result"></span></p>
		</div>
	</div>

	
	<div id="dialog-confirm-delete" title="Delete artist?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This artist, as well as all things tied to this user (sales, etc.) will be deleted.  Are you sure?</p>
</div>
	
<?php	
	$page->getFooter();
?>