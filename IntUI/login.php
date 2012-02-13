<?php
	require_once('../App.php');

	// Page PHP Backend Code Begin
		$errorMsg = null;
		if(isset($_GET['do']) && $_GET['do'] == 'login') {
			// Process the form.
			if($_POST['username'] == "" || $_POST['password'] == "")
				$errorMsg = "Please ensure that both the Username and Password fields are complete.";
			else {
				// Do the login.
				$loggedIn = App::authUser($_POST['username'], $_POST['password']);
				if($loggedIn) {
					if(isset($_GET['page']))
						header('Location: ' . $_GET['page']);
					else 
						header('Location: dashboard.php');
				} else 
					$errorMsg = "Username and/or password incorrect.";
			}
		} else if(isset($_GET['do']) && $_GET['do'] == "logout") {
			App::logoutUser();
			header('Location: login.php');
		}

	// Page PHP Backend Code End
	
	$page = new Page();
	$page->title = "Login";
	$page->getHeader();
?>
	<script>
		$(function() {
			$( "input:submit").button();
			/* Validate the submit
			$( "input:submit.submit-button").click(function () {
				if($("input[name='username']").val() == "" || $("input[name='password']").val() == "") {
					$("div.ui-state-error").fadeIn();
				}
			}); */
		});
	</script>
	
	<div class="ui-state-error ui-corner-all" style="<?php if($errorMsg == null) print "display:none;"?>"><span class="ui-icon ui-icon-alert" style="float:left;margin:2px 5px 0 0;"></span><span><?php print $errorMsg; ?></span></div>
	
<form method="POST" action="?do=login">
  Username: <input type="text" name="username" size="15" value="<?php if(isset($_POST['username'])) print $_POST['username']; ?>" /><br />
  Password: <input type="password" name="password" size="15" /><br />
  <input type="submit" value="Login" class="submit-button" />
</form>

<?php	
	$page->getFooter();
?>