<?php	// header.php
include 'functions.php';
session_start();
session_regenerate_id(true);

if (isset($_SESSION['username'])) {
	$username = $_SESSION['username'];
	$loggedIn = TRUE;
}
else $loggedIn = FALSE;
?>

<html>
	<head>
		<title><?= $appname ?>
			<?php if ($loggedIn) echo " -- $username"; ?>
		</title>
		<link href='http://fonts.googleapis.com/css?family=Consolas' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Metrophobic' rel='stylesheet' type='text/css'>
		<script type='text/javascript' src='js/jquery-1.11.0.min.js'></script>
		<script type='text/javascript'>
			function unhideBody() {
				document.getElementsByTagName("body")[0].style.display = "block";
			}		
		</script>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
	</head>
	<body onload=unhideBody();>
		<center>
			<font face='Consolas' size='3' color='#787878'>
			<h1><?= $appname ?><?php if ($loggedIn) echo " -- $username"; ?></h1>
			<?php if ($loggedIn) { ?>
				<div class="menu"><a href='members.php?view=<?= $username ?>'>Home</a> |
				<a href='search.php'>Search</a> |
				<a href='friends.php'>Friends</a> |
				<a href='messages.php'>Messages</a> |
				<a href='profile.php'>Profile</a> |
				<a href='logout.php'>Log out</a></div><br />
			<?php } else { ?>
				<div class="menu"><a href='/'>Home</a> |
				<a href='signup.php'>Sign up</a> |
				<a href='login.php'>Log in</a></div>
		</center>
			<?php } ?>