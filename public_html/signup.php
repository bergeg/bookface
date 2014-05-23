<?php	// signup.php
ob_start();
include_once 'header.php';
require 'password.php';

$username = $pass = $passconf = "";
if (loggedIn) 
	destroySession();

if (isset($_POST['username'])) {
	$username = sanitize($_POST['username']);
	$pass = sanitize($_POST['pass']);
	$error = "";
	
	if ($username == "" || $pass == "") {
		$error = "Fill all fields";
		$pass = "";
		$js='$("#tip-username").animate({
			"opacity":"1",
			"height":"30px",
			"padding-top":"10px"
		}, 200);';		
	} 
	else if (preg_match("/^[^a-z]{1}|[^-a-z0-9_.-]/i", $username)) {
		$error = "Invalid character in username. <br> Try again";
		$js='$("#tip-username").animate({
			"opacity":"1",
			"height":"50px",
			"padding-top":"10px"
		}, 200);';
	}
	else if (strlen($username)<2) {
		$error = "Username is less than 2 chars. <br> Try again";
		$js='$("#tip-username").animate({
			"opacity":"1",
			"height":"50px",
			"padding-top":"10px"
		}, 200);';
	}
	else if (strlen($username)>15) {
		$error = "Username is more than 15 chars. <br> Try again";
		$js='$("#tip-username").animate({
			"opacity":"1",
			"height":"50px",
			"padding-top":"10px"
		}, 200);';
	}
	else if (strlen($pass)<6) {
		$error = "Password must be at least 6 chars. Try again";
		$js='$("#tip-username").animate({
			"opacity":"0"
		}, 200);
			$("#tip-pass").animate({
			"opacity":"1",
			"height":"50px",
			"padding-top":"10px"
		}, 200);';
	}
	else if ($_POST['pass'] != $_POST['pass-conf']) {
		$error = "Password did not match. <br>Try again";
		$pass = "";
		$js='$("#tip-username").animate({
			"opacity":"0"
		}, 200);
			$("#tip-pass").animate({
			"opacity":"1",
			"height":"50px",
			"padding-top":"10px"
		}, 200);';
	} else {
		$query = "SELECT * FROM members WHERE username='$username'";
		if (mysql_num_rows(queryMysql($query))) {
			$error = "Username already taken. <br>Try again";
			$username = "" ; 
			$pass = "" ; 
			$passconf = "";
			$js='$("#tip-username").animate({
				"opacity":"1",
				"height":"50px",
				"padding-top":"10px"
			}, 200);';
		} else { 
			$pass = password_hash($pass, PASSWORD_BCRYPT);
			queryMysql("INSERT INTO members VALUES(LOWER('$username'), '$pass')");
			die("<center><h4>Account created</h4>Please Log in.</center><br /><br />");
		}
	}
}
ob_end_flush();
?>
<script type="text/javascript">

loader = new Image(16, 16); 
loader.src = "img/loader.gif";

$(document).ready(function() {
	<?= $js; ?>
	$("#username").change(function() { 
		var username = $("#username").val();
		if (username.length==0) {
			$("#username").removeClass('textbox-error').removeClass('textbox-ok')
			.css({
			'background-image':'none'
			});
			$('#tip-username').animate({
				'opacity':'0',
				'height':'0px',
				"padding-top":"0"
			}, 200);
			}
		else if (/^[^a-z]{1}|[^-a-z0-9_.-]/i.test(username) == true) {
			$("#username").removeClass('textbox-ok')
			.addClass("textbox-error");
			$("#tip-username").html('Invalid character')
			.animate({
				"opacity":"1",
				"height":"30px",
				"padding-top":"10px"
			}, 200);
		}
		else if ((username.length >= 2) && (username.length <= 15)) {
			$("#username").removeClass('textbox-error')
			.css({
			"background": "url(img/loader.gif) no-repeat  10px 10px",
			"background-position": "270px",
			"background-color":"white"
			});
			
			$.ajax({
				type: "POST",
				cache: false,
				url: "ajax_checkusername.php",
				data: "username=" + username,
				success: function(msg) {
					if (msg == 'OK') {
						$("#username").removeClass('textbox-error')
						.addClass("textbox-ok")
						$("#tip-username").animate({
							"opacity":"0",
							"height":"0px",
							"padding-top":"0"
						}, 200);
					} else {
						$("#username").removeClass('textbox-ok')
						.addClass("textbox-error")
						$("#tip-username").html('Username already taken')
						.animate({
							"opacity":"1",
							"height":"30px",
							"padding-top":"10px"
						}, 200);
					}
				}
			}); 
		} else {
			$("#username").removeClass('textbox-ok')
			.addClass("textbox-error");
			$("#tip-username").html('Username must be 2-15 characters long')
			.animate({
				"opacity":"1",
				"height":"50px",
				"padding-top":"10px"
			}, 200);
		}
	});
	
	$("#password, #pass-conf").change(function() { 
		var password = $("#password").val();
		var passconf = $("#pass-conf").val();
		if (password.length==0) {
			$("#password").removeClass('textbox-error').removeClass('textbox-ok')
			.css({
			'background-image':'none'
			});
			$("#pass-conf").removeClass('textbox-error').removeClass('textbox-ok')
			.css({
			'background-image':'none'
			});
			$('#tip-pass').animate({
				'opacity':'0',
				'height':'0px'
			}, 200);
		}
		else if (password.length < 6){
			$("#password").removeClass('textbox-ok')
			.addClass("textbox-error");
			if(passconf.length>0) {
				$("#pass-conf").removeClass('textbox-ok')
				.addClass("textbox-error");
			}
			$("#tip-pass").html('Password must be at least 6 characters long')
			.animate({
				"opacity":"1",
				"height":"50px",
				"padding-top":"10px"
			}, 200);
		}
		else if (password != passconf) {
			$("#password").removeClass('textbox-ok')
			.addClass("textbox-error");
			$("#pass-conf").removeClass('textbox-ok')
			.addClass("textbox-error");
			$("#tip-pass").html('Password did not match')
			.animate({
				"opacity":"1",
				"height":"30px"
			}, 200);
		} else {
			$("#password").removeClass('textbox-error')
			.addClass("textbox-ok");
			$("#pass-conf").removeClass('textbox-error')
			.addClass("textbox-ok");
			$("#tip-pass").animate({
				"opacity":"0",
				"height":"0"
			}, 200);
		}
	});
});

</script>
	<h3 align="center">Create new Account</h3>
		<form method='post' action='signup.php'>
			<table id="signup-table" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<div align=center><input class="textbox" id="username" size="25" type="text" name="username" placeholder="Username"></div>
						<div align=center><input class="textbox" id="password" size="25" type="password" name="pass" placeholder="Password"></div>
						<div align=center><input class="textbox" id="pass-conf" size="25" type="password" name="pass-conf" placeholder="Repeat Password"></div>
					</td>
					<td><div align=center><input class="btn" id='submit-signup-btn' type='submit' value='Signup' height="150px"/></div></td>
				</tr>
				<tr>
					<td>
						<span class="tip" id="tip-username"><?= $error; ?></span>
						<span class="tip" id="tip-pass"><?= $error; ?></span>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>