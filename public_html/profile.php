<?php	// profile.php
ini_set("memory_limit","32M");
include_once 'header.php';

if (!$loggedIn)
	die("<br /><center>You must be logged in to view this page</center>");

$user = $_SESSION['username'];

if (isset($_POST['info'])) {
	$info = sanitize($_POST['info']);
	$info = preg_replace('/\s\s+/', ' ', $info);
	$query = "SELECT * FROM profiles WHERE username='$username'";
	if (mysql_num_rows(queryMysql($query))) {
		queryMysql("UPDATE profiles SET info='$info' WHERE username='$username'");
	} else {
		$query = "INSERT INTO profiles VALUES('$username', '$info')";
		queryMysql($query);
	}
} else {
	$query = "SELECT * FROM profiles WHERE username='$username'";
	$result = queryMysql($query);
	
	if (mysql_num_rows($result)) {
		$row = mysql_fetch_row($result);
		$info = stripcslashes($row[1]);
	} else $info = "";
}

$info = stripcslashes(preg_replace('/\s\s+/', ' ', $info));

if (isset($_FILES['profile-image']['name'])) {
	$typeOk = TRUE;
	$fileSize = $_FILES['profile-image']['size'];
	if ($fileSize > 5000000) {
		$output = "error";
	} else {
		$profileImage = "user-img/$username.jpg";
		move_uploaded_file($_FILES['profile-image']['tmp_name'], $profileImage);
		switch ($_FILES['profile-image']['type']) {
			case "image/gif": $src = imagecreatefromgif($profileImage); break;
			case "image/jpeg": $src = imagecreatefromjpeg($profileImage); break;
			case "image/jpg": $src = imagecreatefromjpeg($profileImage); break;
			case "image/png": $src = imagecreatefrompng($profileImage); break;
			default: $typeOk = FALSE; break;
		}
		if ($typeOk) {
			list($width, $height) = getimagesize($profileImage);
			$max = 200;
			$newWidth = $width;
			$newHeight = $height;
			
			if ($width > $height && $max < $width) {
				$newHeight = $max / $width * $height;
				$newWidth = $max;
			} 
			elseif ($height > $width && $max < $height) {
				$newWidth = $max / $height * $width;
				$newHeight = $max;
			}
			elseif ($max < $width) {
				$newWidth = $newHeight = $max;
			}
			
			$tmp = imagecreatetruecolor($newWidth, $newHeight);
			imagecopyresampled($tmp, $src, 0, 0 ,0 ,0, $newWidth, $newHeight, $width, $height);
			imageconvolution($tmp, 	array(
										array(-1, -1, -1),
										array(-1, 16, -1),
										array(-1, -1, -1)
									), 8, 0);
			imagejpeg($tmp, $profileImage);
			imagedestroy($tmp);
			imagedestroy($src);			
		}
		$output = "success";
	}
}
if ($output=="success") {
	$error = "Profile successfully updated";
	$js='$("#profile-tip").animate({
			"opacity":"1",
		}, 500)
		.css({"background": "none repeat scroll 0 0 #282"})
		setTimeout(function() {
		$("#profile-tip").fadeOut("slow");
		}, 2000);';
}
if ($output=="error") {
	$error = "Image filesize must be less than 5MB";
	$js='$("#profile-tip").animate({
			"opacity":"1",
		}, 500)
		.css({"background": "none repeat scroll 0 0 #f66"})
		setTimeout(function() {
		$("#profile-tip").fadeOut("slow");
		}, 2000);';
}
?>

	<script type="text/javascript">
	$(window).load(function() {
		<?= $js; ?>
	});
	</script>
		<h3>Edit your profile</h3>
		<div><?php showProfile($user); ?></div>
		<form method='post' action='profile.php' enctype='multipart/form-data'>
			Enter your details and/or upload an image: <br /><br />
			<textarea name='info' cols='40' rows='3'><?= $info ?></textarea><br />
			<table id="profile-table" cellspacing="1" cellpadding="0">
				<tr>
					<td>
						<div class="btn" id="profile-img-btn">
							<span id="profile-img-span">Upload Image</span>
							<input type="file" class="upload" name="profile-image" size="14" maxlength="32" accept="image/jpeg, image/png, image/gif"/>
						</div>
					</td>
					<td><input type="submit" class="btn" id="profile-save-btn" value="Save Profile" /></td>
				</tr>
				<tr>
					<td colspan="2"><div id="profile-tip"><?= $error; ?></div></td>
				</tr>
			</table>
		</form>
	</body>
</html>