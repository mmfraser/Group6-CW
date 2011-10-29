<?php
	require_once('../App.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "User Management";
		$page->getHeader();
		
		$errorMsg = null;
		if(!App::checkAuth()) {
			// User not authenticated.

			print '<div class="ui-state-error ui-corner-all"><span class="ui-icon ui-icon-alert" style="float:left;margin:2px 5px 0 0;"></span><span>You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=login.php">log in</a>.</span></div>';
			$page->getFooter();
			die();
		}
	
		$allUsers = App::getDB()->getArrayFromDB("SELECT * FROM user");
		$html = "";
		
		foreach($allUsers as $arr) {
			$html .= "<tr>" . PHP_EOL;
			$html .= "	<td>".$arr['username']."</td>" . PHP_EOL;
			$html .= "	<td>".$arr['forename']."</td>" . PHP_EOL;
			$html .= "	<td>".$arr['surname']."</td>" . PHP_EOL;
			$html .= "	<td>".$arr['active']."</td>" . PHP_EOL;
			$html .= '	<td class="options" style="width:20px;"><a href="modifyUser.php?userId='.$arr['userId'].'" title="Modify User"><span class="ui-icon ui-icon-pencil"></span></a></td>';
			$html .= "</tr>" . PHP_EOL;
		}
	
	// Page PHP Backend Code End

?>
		<div class="ui-state-error ui-corner-all" style="<?php if($errorMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-alert" style="float:left;margin:2px 5px 0 0;"></span><span><?php print $errorMsg; ?></span></div>
	
		<script type="text/javascript">
			$(function() {
				$("p.result").hide();
				$("img.spinningWheel").hide();
				$( "div#tabs" ).tabs();
				$("a#addUser").button().click(function() {
				$( "#dialog-form" ).dialog( "open" );
	
			});
				$(".options a").button();
				$('#userlist').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
				
			
			$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			buttons: {
				"Create Account": function() {
					$("img.spinningWheel").show();
					var $form = $( this ),
					username = $form.find( 'input[name="username"]' ).val(),
					forename = $form.find( 'input[name="forename"]' ).val(),
					surname = $form.find( 'input[name="surname"]' ).val(),
					password = $form.find( 'input[name="password"]' ).val(),
					active = $form.find( 'input[name="active"]' ).is(':checked');

					/* Send the data using post and put the results in a div */
					$.post( "addUser.php?do=add", { username: username, password: password, forename: forename, surname: surname, active: active },
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

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">View Users</a></li>
			
		</ul>
		<div id="tabs-1">
			<p><a id="addUser" href="#">Add New User</a></p>
			
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="userlist">
				<thead>
					<tr>
						<th>Username</th>
						<th>Forename</th>
						<th>Surname</th>
						<th>Active</th>
						<th>Options</th>
					</tr>

				</thead>
				<tbody>
					<?php print $html; ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Username</th>
						<th>Forename</th>
						<th>Surname</th>
						<th>Active</th>
						<th>Options</th>
					</tr>
				</tfoot>
			</table>
		</div>
		
	<div id="dialog-form" title="Create new user">
			<p class="validateTips">All form fields are required.</p>

			<form method="POST" id="addUsr" action="?do=addUser&amp;tab=1">
			<table>
				<tr>
					<td><label for="username">Username</label><td>
					<td><input type="text" name="username" size="15" value="<?php print $username ?>" /></td>
				</tr>
				<tr>
					<td><label for="forename">Forename</label><td>
					<td><input type="text" name="forename" size="15" value="<?php print $forename ?>" /></td>
				</tr>
				<tr>
					<td><label for="surname">Surname</label><td>
					<td><input type="text" name="surname" size="15" value="<?php print $surname ?>" /></td>
				</tr>
				<tr>
					<td><label for="username">Password</label><td>
					<td><input type="password" name="password" size="15" value="<?php print $password ?>" /></td>
				</tr>
				<tr>
					<td><label for="username">Active</label><td>
					<td><input type="checkbox" name="active" size="15"  /></td>
				</tr>
			</table>
		</form>
		<p><img src="../Images/spinningWheel.gif" class="spinningWheel" alt="Loading" /></p>
		<p class="result"><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
			<span class="result"></span></p>
		</div>
	</div>

	
<?php	
	$page->getFooter();
?>