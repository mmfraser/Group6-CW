<?php
	require_once('../App.php');

	// Page PHP Backend Code Begin
	$errorMsg = null;
	if(!App::checkAuth()) {
		// User not authenticated.
		$errorMsg = 'You are not authorised to view this page.  If you have a username and password for this application please <a href="login.php?page=login.php">log in</a>.';
	}
	// Page PHP Backend Code End
	
	$page = new Page();
	$page->title = "User Management";
	$page->getHeader();
?>
		<div class="ui-state-error ui-corner-all" style="<?php if($errorMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-alert" style="float:left;margin:2px 5px 0 0;"></span><span><?php print $errorMsg; ?></span></div>
	
		<script type="text/javascript">
		
	
		
	$(function() {
	
	
	
		$( "div#tabs" ).tabs();
		$("a#addUser").button();
		$('#example').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
		
		
	});
	</script>

	
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">View Users</a></li>
			
		</ul>
		<div id="tabs-1">
			<p><a id="addUser" href="#">Add New User</a></p>
			
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Username</th>
			<th>Forename</th>
			<th>Surname</th>
			<th>Active</th>
			
		</tr>

	</thead>
	<tbody>
		<tr>
			<td>Marc</td><td>Marc</td><td>Marc</td><td>Marc</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th>Username</th>
			<th>Forename</th>
			<th>Surname</th>
			<th>Active</th>
		</tr>
		
	</tfoot>
</table>

			<p>Table of users here.</p>
		</div>
<!--		<div id="tabs-2">
			<form method="POST" action="?do=addUser&amp;tab=1">
  Username: <input type="text" name="username" size="15" value="<?php if(isset($_POST['username'])) print $_POST['username']; ?>" /><br />
   Forename: <input type="text" name="forname" size="15" value="<?php if(isset($_POST['forename'])) print $_POST['forename']; ?>" /><br />
    Surname: <input type="text" name="surname" size="15" value="<?php if(isset($_POST['surname'])) print $_POST['surname']; ?>" /><br />
	 Active: <input type="checkbox" name="active" size="15" checked="<?php if(isset($_POST['active'])) print $_POST['active']; ?>" /><br />
  Password: <input type="password" name="password" size="15" /><br />
  <input type="submit" value="Login" class="submit-button" />
</form>
		</div> -->
		
	</div>
	
	
<?php	
	$page->getFooter();
?>