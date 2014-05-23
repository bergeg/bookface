<?php
require 'functions.php';
if (isset($_POST['username'])) {
	$username = sanitize($_POST['username']);
	$result = queryMysql("SELECT username FROM members WHERE username LIKE '%$username%' ORDER BY username");
	$num = mysql_num_rows($result);
	for ($j=0; $j<$num; ++$j) {
		$row = mysql_fetch_row($result);
		echo "<a href='members.php?view=$row[0]'>$row[0]</a><br>";
    }
}
?>