<?php
	require_once('../AppClasses/App.php');
	$page = new Page();
	$page->title = "test";
	$page->getHeader();
?>
	<script>
		$(function() {
			$( "input:submit").button();
			//$( "input:submit").click(function () {alert('nice click');});
		});
	</script>
<form method="POST" action="?do=login">
  Username: <input type="text" name="username" size="15" /><br />
  Password: <input type="password" name="passwort" size="15" /><br />
  <input type="submit" value="Login" />
</form>

<?php	
	$page->getFooter();
?>