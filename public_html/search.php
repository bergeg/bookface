<?php	// search.php

include_once 'header.php';
if (!$loggedIn)
	die("<br /><center>You must be logged in to view this page</center>");
if (isset($_POST['username'])) 
	sanitize($_POST['username']);
?>
	<script type="text/javascript">
	$(function(){
		$("#search").keyup(function(){
			var search = $("#search").val();
			if (search == ''){
				$("#search-result").html('');
				return false;
			} else {
				$("#search").css({
					"background": "url(img/loader.gif) no-repeat  10px 10px",
					"background-position": "220px",
					"background-color":"white"
				});
			}	
			$.ajax({
				type: "POST",
				url: "ajax_search.php",
				data: "username=" + search,
				cache: false,                                
				success: function(response){
					$("#search-result").html(response);
					$("#search").css({
					"background": "none",
					"background-color":"white"
				});
				}
			});
		});
	});
	</script>
	<form method="post" action="search.php" onsubmit="return false;">
		<input id="search" name="username" placeholder="Username">
	</form>
	<div id="search-result"></div>
</body>
</html>