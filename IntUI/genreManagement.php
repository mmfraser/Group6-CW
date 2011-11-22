<?php
	require_once('../App.php');
	require_once('../AppClasses/Genre.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "Genre Management";
		$page->getHeader();

		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=genreManagement.php">log in</a>.');
		}
	
		// Get the Store list and populate table.
		$allGenres = App::getDB()->getArrayFromDB("SELECT genreId, genreName FROM genre");
		$genreHtml = "";
		
		
		foreach($allGenres as $arr) {
			$genreHtml .= "<tr>" . PHP_EOL;
			$genreHtml .= "	<td>".$arr['genreId']."</td>" . PHP_EOL;
			$genreHtml .= "	<td>".$arr['genreName']."</td>" . PHP_EOL;
			$genreHtml .= '	<td class="options" style="width:20px;"><a href="modifyGenre.php?genreId='.$arr['genreId'].'" title="Modify Genre"><span class="ui-icon ui-icon-pencil"></span></a></td>';
			$genreHtml .= "</tr>" . PHP_EOL;
		}
		
		
	
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				$("p.result").hide();
				$("img.spinningWheel").hide();
			
				$("a#addGenre").button().click(function() {
					$( "#genre-dialog-form" ).dialog( "open" );
				});
				
				$('#grouplist').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
			
				$(".options a").button();
				$('#storelist').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
			
			$( "#genre-dialog-form" ).dialog({
				autoOpen: false,
				height: 300,
				width: 350,
				modal: true,
				buttons: {
					"Add Genre": function() {
						$("img.spinningWheel").show();
						var $form = $( this ),
						genreName = $form.find( 'input[name="genreName"]' ).val();
				
						
						/* Send the data using post and put the results in a div */
	
						$.post( "ajaxFunctions.php?do=addGenre", { genreName: genreName },
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
					allFields.val( "" ).removeClass( "ui-state-error" );
				}
			});
			
			
			
			
	});
		</script>

			<p><a id="addGenre" href="#">Add New Genre</a></p>
			
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="storelist">
				<thead>
					<tr>
						<th>Genre ID</th>
						<th>Genre Name</th>
						<th>Options</th>
					</tr>
				</thead>
				<tbody>
					<?php print $genreHtml; ?>
				</tbody>
			</table>
	
		
		
	<div id="genre-dialog-form" title="Create new Genre">
			<p class="validateTips">All form fields are required.</p>

			<form method="POST" id="addStore" action="?do=addStore&amp;tab=1">
			<table>
				<tr>
					<td><label for="genreName">Genre Name</label><td>
					<td><input type="text" name="genreName" size="15" /></td>
				</tr>
			</table>
		</form>
		<p><img src="../Images/spinningWheel.gif" class="spinningWheel" alt="Loading" /></p>
		<p class="result"><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
			<span class="result"></span></p>
	</div>



	
	
	
	
	
<?php	
	$page->getFooter();
?>