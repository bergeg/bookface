<?php	// login.php
ob_start();
include_once 'header.php';
require 'password.php';
$error = $username = $pass = "";

if (isset($_POST['username'])) {
	$username = sanitize($_POST['username']);
	$pass = sanitize($_POST['pass']);
	$hash = password_hash($pass, PASSWORD_BCRYPT);
	$data = queryMysql("SELECT pass FROM members WHERE username='$username'");
	$fetchedData = mysql_fetch_array($data);
	$fetchedPass = $fetchedData['pass'];
	if ($username == "" || $pass == "") {
		$error = "Fill all fields";
		$js = '$("#tip-username").animate({
			"opacity":"1",
			"height":"30px",
			"padding-top":"10px"
		}, 200);';		
	}
	else if (password_verify($pass, $fetchedPass)) {
		$_SESSION['username'] = $username;
		$_SESSION['pass'] = $pass;
		header("location:members.php?view=$username");
	} else {
		$error = "Incorrect Username/Password";
		$js = '$("#tip-username").animate({
		"opacity":"1",
		"height":"30px",
		"padding-top":"10px"
		}, 200);';
	}
}
ob_end_flush();
?>

<script>
	$(document).ready(function() {
		<?= $js; ?>
	});
</script>

		<h3 align=center>Members log in</h3>
		<form method="post" action="login.php">
			<table id="login-table" cellspacing="0" cellpadding="0" >
				<tr>
					<td>
						<div align=center><input class="textbox" id="username" type="text" name="username" size="25" placeholder="Username" /></div>
						<div align=center><input class="textbox" id="password" type="password" name="pass" size="25" placeholder="Password" /></div>	
					</td>
					<td><div align=center><input class="btn" id="submit-login-btn" type="submit" value="Login" /></div></td>
				</tr>
				<tr>
					<td><span class="tip" id="tip-username"><?= $error; ?></span></td>
				</tr>
			</table>
		</form>
	</body>
</html>