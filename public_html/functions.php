<?php	// functions.php
$dbhost = 'localhost';
$dbname = 'bookface_1';
$dbuser = 'bookface_admin';
$dbpass = '';
$appname = "Bookface";

mysql_connect($dbhost, $dbuser, $dbpass, $dbname) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());


function queryMysql($query) {
	$result = mysql_query($query) or die(mysql_error());
	return $result;
}

function destroySession() {
	$_SESSION=array();
	if (session_id() != "" || isset($_COOKIE[session_name()]))
		setcookie(session_name(), '', time()-3600);
	session_destroy();
}

function sanitize($string) {
	$string = strip_tags($string);
	$string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	$string = stripslashes($string);
	$string = trim($string);
	return mysql_real_escape_string($string);
}

function showProfile($username) {
	if (file_exists("user-img/$username.jpg"))
		echo "<img src='user-img/$username.jpg' border='1' align='center' /><br /><br />";
	$result = queryMysql("SELECT * FROM profiles WHERE username='$username'");
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_row($result);
		echo stripslashes(wordwrap($row[1], 100, "<br />", true)) . "<br /><br />";
	}
}

?>