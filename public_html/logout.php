<?php	// logout.php
ob_start();
include_once 'header.php';

if ($loggedIn) {
	destroySession();
	header("location:index.php");
} else {
	echo "<br /><center>You are not logged in</center>";
}

ob_end_flush();
?>