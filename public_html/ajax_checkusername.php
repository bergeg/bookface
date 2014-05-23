<?php	// checkusername.php
require 'functions.php';
if (isset($_POST['username'])) {
	$username = sanitize($_POST['username']);
	$result = mysql_query("SELECT username FROM members WHERE username='$username'") or die(mysql_error());
	if (mysql_num_rows($result)) {
		echo 'error';	// username taken
	} else {
		echo 'OK';		// username available
	}
}
?>
