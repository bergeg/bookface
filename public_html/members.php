<?php	// members.php
include_once 'header.php';

if (!$loggedIn)
	die("<br /><center>You must be logged in to view this page</center>");
$username = $_SESSION['username'];

if (isset($_GET['view'])) {
	$view = sanitize($_GET['view']);
	if ($view == $username) {
		$username = "Your";
	}
	else
		$username = "$view's";
	echo "<h3>$username Page</h3>";
	showProfile($view);
	$username = $_SESSION['username'];
	if ($view == $username) {
		$username = "Your";
	}
	else
		$username = "$view's";
	echo "<br><a href='messages.php?view=$view'>$username Messages</a><br /><br />";
	echo "<a href='friends.php?view=$view'>$username Friends</a><br />";
	$username = $_SESSION['username'];
	if ($view == $username)
		die();
	else {
		$result = queryMysql("SELECT username FROM members WHERE username='$view'");
		$num = mysql_num_rows($result);
		echo "<br><a href='members.php?view=$view'>$view</a>";
		
		$query = "SELECT * FROM friends WHERE username='$username' AND friend='$view'";
		$following = mysql_num_rows(queryMysql($query));
		
		$query = "SELECT * FROM friends WHERE username='$view' AND friend='$username'";
		$follower = mysql_num_rows(queryMysql($query));
		$follow = "follow";
		
		if ($following) {
			$follow = "unfollow";
			echo " &larr; you are following";
		}
		else if ($follower) {
			$follow = "follow back";
			echo " &rarr; is following you";
		}
		
		if (!$following) {
			echo " [<a href='members.php?add=$view'>$follow</a>]";
		} else {
			echo "[<a href='members.php?remove=$view'>$follow</a>]";
		}
	}
}

if (isset($_GET['add'])) {
	$add = sanitize($_GET['add']);
	echo "<h3>$add's Page</h3>";
	$username = $_SESSION['username'];
	$query = "SELECT * FROM friends WHERE username='$username' AND friend='$add'";
	if (!mysql_num_rows(queryMysql($query))) {
		$query = "INSERT INTO friends VALUES ('$username', '$add')";
		queryMysql($query);
	}
	showProfile($add);
	echo "<br><a href='messages.php?view=$add'>$add's Messages</a><br /><br />";
	echo("<a href='friends.php?view=$add'>$add's Friends</a><br />");
	
	$result = queryMysql("SELECT username FROM members WHERE username = '$add'");
	$num = mysql_num_rows($result);
	echo "<br><a href='members.php?view=$add'>$add</a>";
	
	$query = "SELECT * FROM friends WHERE username='$username' AND friend='$add'";
	$following = mysql_num_rows(queryMysql($query));
	
	$query = "SELECT * FROM friends WHERE username='$add' AND friend='$username'";
	$follower = mysql_num_rows(queryMysql($query));
	$follow = "follow";
	
	if ($following) {
		echo " &larr; you are following";
	}
	else if ($follower) {
		$follow = "follow back";
		echo " &rarr; is following you";
	}
	
	if (!$following) {
		echo " [<a href='members.php?add=$add'>$follow</a>]";
	} else {
		echo "[<a href='members.php?remove=$add'>unfollow</a>]";
	}
}
elseif (isset($_GET['remove'])) {
	$remove = sanitize($_GET['remove']);
	echo "<h3>$remove's Page</h3>";
	$username = $_SESSION['username'];
	$query = "DELETE FROM friends WHERE username = '$username' AND friend='$remove'";
	queryMysql($query);
	showProfile($remove);
	echo "<br><a href='messages.php?view=$remove'>$remove's Messages</a><br /><br />";
	echo("<a href='friends.php?view=$remove'>$remove's Friends</a><br />");
	
	$result = queryMysql("SELECT username FROM members WHERE username='$remove'");
	$num = mysql_num_rows($result);
	
	echo "<br><a href='members.php?view=$remove'>$remove</a>";
	$query = "SELECT * FROM friends WHERE username='$username' AND friend='$remove'";
	$following = mysql_num_rows(queryMysql($query));
	
	$query = "SELECT * FROM friends WHERE username='$remove' AND friend='$username'";
	$follower = mysql_num_rows(queryMysql($query));
	$follow = "follow";
	
	if ($following) {
		echo " &larr; you are following";
	}
	else if ($follower) {
		$follow = "follow back";
		echo " &rarr; is following you";
	}
	
	if (!$following) {
		echo " [<a href='members.php?add=$remove'>$follow</a>]";
	} else {
		echo "[<a href='members.php?remove=$remove'>unfollow</a>]";
	}
}

?>