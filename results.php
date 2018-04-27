<html>
<head>
<link rel='stylesheet' type='text/css' href='tianw93-p2.css'>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'></script>
<script>
$(document).ready(function(){ 
			// search suggestions function, activated after each keypress
			// get the value of search term, post to keyword-suggestions.php
			$('#name').keyup(function() {
				word = document.form1.name.value;
				$.post('keyword-suggestions.php', 
					{keywords:word}, 
					function(data,stauts){
						$('#result').html(data);
					});
				});
			// result details function, displayed when user moves mouse over a result
			// post the corresponding videoid to result-details.php
			// when moving mouse off of a result, nothing displays
			$('.vids').hover(function() {
					videoid = $(this).attr('videoid');
					$.post('result-details.php',
						{vid: videoid},
						function(data, stauts){
							$('#details').html(data);
						});
				}, function() {
					$.post('result-details.php',
						{vid: 0},
						function(data, stauts){
							$('#details').html(data);
						});
				});
		});
</script>

</head>

<?php
	// advanced functionality
	// check if the user has provided a username and password
	if(isset($_POST['user']) && isset($_POST['pass'])) {
		require "dbconnect.php";
		$user = mysqli_real_escape_string($db, $_POST['user']);
		// encryption for password
		$pass = sha1($_POST['pass']);
		// check if the username and password are stored in the database
		$query = "select * from users where uname='" . $user . "' and upass='" . $pass . "'";
		if ($result = mysqli_query($db, $query)) {
			$num_rows = mysqli_num_rows($result);
			// if so, set a session variable
			if ($num_rows > 0) {
				session_start();
				$row = mysqli_fetch_row($result);
				$_SESSION['valid_user'] = $user;
			}
		}
		mysql_close($db);
	}
?>

<?php
	// get the username from session
	$username = $_SESSION['valid_user'];
	// use GET method to get the search term
	$header = "<section class='content'>
	<div id='search'>
    <form name='form1' method='get' action='results.php'>
    <input id='name' type='text' name='name'>
    <br /><input type='submit' value='Search' align='left'></form>
    Suggestions: <br />
    <p id='result'></p>
    </div>
    <div id='lists' style='border:1px solid black;padding:1em;'>";
    // if login is sucessful, show the initial interaction page
	if (isset($_SESSION['valid_user'])) {
		echo "<body><h1>Open Video</h1>";
		echo "<p align='right'>Hello, ". $username . " (<a href='results.php?logout'>Logout</a>)</p>";
		echo $header;
	}
	// if the user enters a search term, display the result
	elseif (isset($_GET['name'])) {
		session_start();
		$uname = $_SESSION['valid_user'];
		echo "<body><h1>Open Video</h1>";
		echo "<p align='right'>Hello, ". $uname . " (<a href='results.php?logout'>Logout</a>)</p>";
		echo $header;
    	$name = $_GET['name'];
    	// make sure results will only be shown if user enters a search term
    	if (strlen($name) > 0) {
    		echo "Showing results of: " . $name . "<br /><br />";
    	}
    	require "dbconnect.php";
    	// use match..against to do the search with fulltext index
		$query = "select * from p2records where match (title, description, keywords) against ('" . $name . "')";
		if($result = mysqli_query($db,$query)) {
			while ($row = mysqli_fetch_assoc($result)) {
				// only display the first 200 characters of the description field
				if(strlen($row['description']) > 200) {
					$desc = substr($row['description'], 0, 200);
				}
				else {
					$desc = $row['description'];
				}
				$vid = $row['videoid'];
				echo "<div class='vids' videoid='". $vid . "'>";
				// link the image to the corresponding website
				echo "<a href='http://www.open-video.org/details.php?videoid=" . $vid . "'><img src='http://www.open-video.org/surrogates/keyframes/" . $vid . "/" . $row['keyframeurl'] . "' align='left'></a>"; 
				// title in bold, followed by year, link to the corresponding website
				echo "<a href='http://www.open-video.org/details.php?videoid=" . $vid . "'><b>" .$row['title'] . " (" . $row['creationyear'] . ")</b></a>";
				echo "<br />" . $desc;
				echo "<br /></div>";
			}
	
		mysql_close($db);
		echo "</div>";
		echo "<div id='details'></div>";
		echo "</section></body>";
		}
	}
	// if session hasn't started yet or login was unsuccessful, ask user to login again 
	else {
		echo "Please log in.
			<form method='post' action='results.php'>
			Username:&nbsp;<input type='text' name='user'><p>
			Password:&nbsp;<input type='password' name='pass'><p>
			<input type='submit' value='Login'>
			</form>";
	}
	// if user clicks logout, destroy the session, return to login
	if(isset($_GET["logout"])){
		session_start();
		session_destroy();
	}
?>

</html>
