<?php	// messages.php
include_once "header.php";

if (!$loggedIn)
	die("<br /><center>You must be logged in to view this page</center>");
	
$username = $_SESSION['username'];

if (isset($_GET['view']))
	$view = sanitize($_GET['view']);
else $view = $username;

if (isset($_POST['message'])) {
	$message = sanitize($_POST['message']);
	
	if ($message != "") {
		$pm = substr(sanitize($_POST['pm']), 0, 1); // personal/private message
		$time = time();
		queryMysql("INSERT INTO messages VALUES(NULL, '$username', '$view', '$pm', '$time', '$message')");
	}
}

if ($view != "") {
	if ($view == $username) {
		$placeholder1 = $placeholder2 = "Your";
		echo "<h3>$placeholder1 Messages</h3>";
		showProfile($view);
	} else {
		$placeholder1 = "<a href='members.php?view=$view'>$view</a>'s";
		$placeholder2 = "$view's";
		echo "<h3>$placeholder1 Messages</h3>";
		showProfile($view);
		
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#radio-div-private").click(function(){
					$('#radio-div-public').css({
						'background-position': '0 40px',
						'-moz-transition': 'all 500ms ease',
						'-webkit-transition': 'all 500ms ease',
						'transition': 'all 500ms ease'
					});
					$('#radio-div-private').css({
						'background-position': '0 0',
						'-moz-transition': 'all 500ms ease',
						'-webkit-transition': 'all 500ms ease',
						'transition': 'all 500ms ease'
					});
					$('#message-span-public').css({
						'color': 'gray',
						'-moz-transition': 'all 500ms ease',
						'-webkit-transition': 'all 500ms ease',
						'transition': 'all 500ms ease'
					});
					$('#message-span-private').css({
						'color': 'white',
						'-moz-transition': 'all 500ms ease',
						'-webkit-transition': 'all 500ms ease',
						'transition': 'all 500ms ease'
					});
				});
				
				$("#radio-div-public").click(function(){
					$('#radio-div-public').css({
						'background-position': '0 0',
						'-moz-transition': 'all 500ms ease',
						'-webkit-transition': 'all 500ms ease',
						'transition': 'all 500ms ease'
					});
					$('#radio-div-private').css({
						'background-position': '0 -40px',
						'-moz-transition': 'all 500ms ease',
						'-webkit-transition': 'all 500ms ease',
						'transition': 'all 500ms ease'
					});
					$('#message-span-public').css({
						'color': 'white',
						'-moz-transition': 'all 500ms ease',
						'-webkit-transition': 'all 500ms ease',
						'transition': 'all 500ms ease'
					});
					$('#message-span-private').css({
						'color': 'gray',
						'-moz-transition': 'all 500ms ease',
						'-webkit-transition': 'all 500ms ease',
						'transition': 'all 500ms ease'
					});
				});
			});
		</script>
	
		<form method='post' action='messages.php?view=<?= $view ?>'>
		Type below to send a message: <br><br>
			<textarea name='message' cols='40' rows='3'></textarea><br />
			<table id="profile-table" cellspacing="0" cellpadding="0">
					<tr>
						<td>
							<div id="radio-div-public">
								<span id="message-span-public">Public</span>
								<input id="radio-public" type='radio' name='pm' value='0' checked='checked' />
							</div>
						</td>
						<td rowspan="2"><input class="btn" id='send-message-btn' type='submit' value='Send' /></td>
					</tr>
					<tr>
						<td>
							<div id="radio-div-private">
								<span id="message-span-private">Private</span>
								<input id="radio-private" type='radio' name='pm' value=1 />
							</div>
						</td>
					</tr>
			</table>
		</form>
	<?php 
	} 
	
	if (isset($_GET['erase'])) {
		$erase = sanitize($_GET['erase']);
		queryMysql("DELETE FROM messages WHERE id=$erase AND recip='$username'");
	}
	$result = queryMysql("SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC");
	$num = mysql_num_rows($result);
	
	for ($i=0; $i<$num; ++$i) {
		$row = mysql_fetch_row($result);
		
		if ($row[3] == 0 || $row[1] == $username || $row[2] == $username) {
			echo date('M jS \'y g:ia:', $row[4]);
			echo " <a href='messages.php?view=$row[1]'>$row[1]</a> ";
			
			if ($row[3] == 0) {
				echo "wrote: &quot;"; echo stripslashes(wordwrap($row[5], 70, '<br />', true)); echo "&quot;";
				
			} else {
				echo "whispered: <i>&quot;"; echo stripslashes(wordwrap($row[5], 70, '<br />', true)); echo "&quot</i>";
			}
			if ($row[2] == $username) {
				echo "[<a href='messages.php?view=$view" . "&erase=$row[0]'>erase</a>]";
			}
			echo "<br>";
		}
	}
}

if (!$num)
	echo "<li>No messages yet</li><br />";
echo "<br><a href='messages.php?view=$view'>Refresh messages</a>";
echo " | <a href='friends.php?view=$view'>View $placeholder2 friends</a>"	
?>