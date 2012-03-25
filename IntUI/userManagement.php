<?php
	require_once('../App.php');

	// Page PHP Backend Code Begin
		$page = new Page();
		$page->title = "User Management";
		$page->getHeader();

		if(!App::checkAuth()) {
			// User not authenticated.
			App::fatalError($page, 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=userManagement.php">log in</a>.');
		}
	
		// Get the User list and populate table.
		$allUsers = App::getDB()->getArrayFromDB("SELECT * FROM user");
		$userHtml = "";
		
		foreach($allUsers as $arr) {
			$usr = new User();
			$usr->populateId($arr['userId']);
			
			$userHtml .= "<tr id=\"".$arr['userId']."\">" . PHP_EOL;
			$userHtml .= "	<td>".$usr->username."</td>" . PHP_EOL;
			$userHtml .= "	<td>".$usr->forename."</td>" . PHP_EOL;
			$userHtml .= "	<td>".$usr->surname."</td>" . PHP_EOL;
			$userHtml .= "	<td>".$usr->active."</td>" . PHP_EOL;
			
			$groupOutput = "<ul>";
			foreach($usr->groupMembership as $groupId) {
				$grp = new Group();
				$grp->populateId($groupId);
				$groupOutput .= "	<li>" . $grp->name . "</li>";
			}
			$groupOutput .= "</ul>";
			
			$userHtml .= "	<td>".$groupOutput."</td>" . PHP_EOL;
			$userHtml .= '	<td class="options" style=""><a href="modifyUser.php?userId='.$arr['userId'].'" title="Modify User"><span class="ui-icon ui-icon-pencil"></span><a title="Delete User" id="deleteUser"><span class="ui-icon ui-icon-trash"></span></a></td>';
			$userHtml .= "</tr>" . PHP_EOL;
		}
		
		// Get the groups list and populate table.
		$allGroups = App::getDB()->getArrayFromDB("SELECT * FROM usergroup");
		$groupHtml = "";
		
		foreach($allGroups as $arr) {
			$groupHtml .= "<tr id=\"".$arr['groupId']."\">" . PHP_EOL;
			$groupHtml .= "		<td>" . $arr['name'] . "</td>" . PHP_EOL;
			$groupHtml .= "		<td>" . $arr['description'] . "</td>" . PHP_EOL;
			$groupHtml .= '		<td class="options" style="width:20px;"><a href="modifyGroup.php?groupId='.$arr['groupId'].'" title="Modify Group"><span class="ui-icon ui-icon-pencil"></span></a><a title="Delete Group" id="deleteGroup"><span class="ui-icon ui-icon-trash"></span></a></td>' . PHP_EOL;
			$groupHtml .= "</tr>". PHP_EOL;
		}
	
	// Page PHP Backend Code End

?>
		<script type="text/javascript">
			$(function() {
				var userId
				$("p.result").hide();
				$("img.spinningWheel").hide();
				$( "div#tabs" ).tabs({cookie:{}});
				
				$("a#addGroup").button().click(function() {
					$( "#group-dialog-form" ).dialog( "open" );
				});
				
				$("a#addUser").button().click(function() {
					$( "#user-dialog-form" ).dialog( "open" );
				});
				
				$(".options a").button();
				
				$("a#deleteUser").button().click(function() {
					userId = $(this).parent().parent().attr("id")
					$( "#dialog-confirm-delete" ).dialog( "open" );
				});
				
				$("a#deleteGroup").button().click(function() {
					groupId = $(this).parent().parent().attr("id")
					$( "#dialog-confirm-delete-group" ).dialog( "open" );
				});
				
				$('#userlist').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
				
				$('#grouplist').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				
				});

				$( "#dialog-confirm-delete" ).dialog({
					autoOpen: false,
					resizable: false,
					height:170,
					modal: true,
					buttons: {
						"Delete user": function() {
							$.post( "ajaxFunctions.php?do=deleteUser", { userId: userId});
							location.reload();
						},
						Cancel: function() {
							$( this ).dialog( "close" );
						}
					}
				});
				
				$( "#dialog-confirm-delete-group" ).dialog({
					autoOpen: false,
					resizable: false,
					height:170,
					modal: true,
					buttons: {
						"Delete Group": function() {
							$.post( "ajaxFunctions.php?do=deleteGroup", { groupId: groupId});
							location.reload();
						},
						Cancel: function() {
							$( this ).dialog( "close" );
						}
					}
				});
				
			$( "#user-dialog-form" ).dialog({
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
						$.post( "ajaxFunctions.php?do=addUser", { username: username, password: password, forename: forename, surname: surname, active: active },
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
			
			$( "#group-dialog-form" ).dialog({
				autoOpen: false,
				height: 300,
				width: 350,
				modal: true,
				buttons: {
					"Create Group": function() {
						$("img.spinningWheel").show();
						var $form = $( this ),
						grpName = $form.find( 'input[name="groupname"]' ).val(),
						grpDesc = $form.find('textarea[name="groupdescription"]').val();

						/* Send the data using post and put the results in a div */
						$.post( "ajaxFunctions.php?do=addGroup", { groupname: grpName, groupdescription: grpDesc },
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
				<li><a href="#tabs-1">Manage Users</a></li>
				<li><a href="#tabs-2">Manage Groups</a></li>
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
							<th>User Groups</th>
							<th>Options</th>
						</tr>

					</thead>
					<tbody>
						<?php print $userHtml; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Username</th>
							<th>Forename</th>
							<th>Surname</th>
							<th>Active</th>
							<th>User Groups</th>
							<th>Options</th>
						</tr>
					</tfoot>
				</table>
			</div>
			
			
			<div id="tabs-2">
				<p><a id="addGroup" href="#">Add New Group</a></p>
				
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="grouplist">
					<thead>
						<tr>
							<th>Name</th>
							<th>Description</th>
							<th>Options</th>
						</tr>

					</thead>
					<tbody>
						<?php print $groupHtml; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Name</th>
							<th>Description</th>
							<th>Options</th>
						</tr>
					</tfoot>	
				</table>
			</div>
			
			
			
		<div id="user-dialog-form" title="Create new user">
				<p class="validateTips">All form fields are required.</p>

				<form method="POST" id="addUsr" action="?do=addUser&amp;tab=1">
				<table>
					<tr>
						<td><label for="username">Username</label><td>
						<td><input type="text" name="username" size="15" /></td>
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
						<td><label for="username">Password</label><td>
						<td><input type="password" name="password" size="15" /></td>
					</tr>
					<tr>
						<td><label for="username">Active</label><td>
						<td><input type="checkbox" name="active" size="15" /></td>
					</tr>
				</table>
			</form>
			<p><img src="../Images/spinningWheel.gif" class="spinningWheel" alt="Loading" /></p>
			<p class="result"><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
				<span class="result"></span></p>
		</div>
		

		<div id="group-dialog-form" title="Create new group">
				<p class="validateTips">All form fields are required.</p>

				<form method="POST" id="addUsr" action="?do=addUser&amp;tab=1">
				<table>
					<tr>
						<td><label for="groupname">Group Name</label><td>
						<td><input type="text" name="groupname" size="15" ></td>
					</tr>
					<tr>
						<td><label for="description">Description</label><td>
						<td><textarea rows="2" cols="30" name="groupdescription"></textarea></td>
					</tr>
				</table>
			</form>
			<p><img src="../Images/spinningWheel.gif" class="spinningWheel" alt="Loading" /></p>
			<p class="result"><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
				<span class="result"></span></p>
		</div>
		
		
		<div id="dialog-confirm-delete" title="Delete user?">
			<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This user, as well as all things tied to this user (group membership, etc.) will be deleted.  Are you sure?</p>
		</div>
		
		<div id="dialog-confirm-delete-group" title="Delete User Group?">
			<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This user group will be deleted.  Are you sure?</p>
		</div>
	</div>
<?php	
	$page->getFooter();
?>