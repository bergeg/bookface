<?php	// friends.php
include_once 'header.php';

if (!$loggedIn)
	die("<br /><center>You must be logged in to view this page</center>");
	
$username = $_SESSION['username'];
if (isset($_GET['view']))
	$view = sanitize($_GET['view']);
else 
	$view = $username;
	
if ($view == $username) {
	$placeholder1 = "Your";
	$placeholder2 = "You are";
} else {
	$placeholder1 = "$view's";
	$placeholder2 = "$view is";
}

echo "<div>";

	$followers = array();
	$followings = array();
	
	$result = queryMysql("SELECT * FROM friends WHERE friend='$view'");	// followers
	$num = mysql_num_rows($result);
	
	for ($i=0; $i<$num; ++$i) {
		$row = mysql_fetch_row($result);
		$followers[$i] = $row[0];
	}
	
	$result = queryMysql("SELECT * FROM friends WHERE username='$view'");	// followings
	$num = mysql_num_rows($result);
	
	for ($i=0; $i<$num; ++$i) {
		$row = mysql_fetch_row($result);
		$followings[$i] = $row[1];
	}
	
	$mutual = array_intersect($followers, $followings);
	$followers = array_diff($followers, $mutual);
	$followings = array_diff($followings, $mutual);
	$friends = FALSE;
	
	if (sizeof($mutual)) {
		echo "$placeholder1 mutual friends<ul>";
		foreach ($mutual as $mutualFriend)
			echo "<li><a href='members.php?view=$mutualFriend'>$mutualFriend</a></li>";
		echo "</ul>";
		$friends = TRUE;
	}
	
	if (sizeof($followers)) {
		echo "$placeholder1 followers<ul>";
		foreach ($followers as $follower)
			echo "<li><a href='members.php?view=$follower'>$follower</a></li>";
		echo "</ul>";
		$friends = TRUE;
	}
	
	if (sizeof($followings)) {
		echo "$placeholder2 following<ul>";
		foreach ($followings as $following) {
			echo "<li><a href='members.php?view=$following'>$following</a></li>";
		}
		echo "</ul>";
		$friends = TRUE;
	}
	if ($view != $username) {
		echo "<br><a href='messages.php?view=$view'>"."View $placeholder1 messages</a>";
		die();
	}
		
	$result = queryMysql("SELECT friend FROM friends WHERE friend NOT IN
						 (SELECT friend FROM friends WHERE username='$view') AND username='$mutualFriend' AND friend <> '$view'"); // suggest a friend
	$num = mysql_num_rows($result);
	
	for ($i=0; $i<$num; ++$i) {
		$row = mysql_fetch_row($result);
		$suggest[$i] = $row[0];
	}
	
	if (sizeof($suggest)) {
		echo "People you may know</span><ul>";
		foreach ($suggest as $friend)
			echo "<li><a href='members.php?view=$friend'>$friend</a></li>";
		echo "</ul>";
		$friends = TRUE;
	}
	
	if (!$friends)
		echo "<br>You don't have any friends yet. <br>";
	echo "<br><a href='messages.php?view=$view'>"."View $placeholder1 messages</a>";
	?>
</div>
</body>
</html>
