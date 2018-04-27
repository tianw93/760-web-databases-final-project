<?php
	//get the value of videoid from results.php
	$vid = $_POST['vid'];
	require "dbconnect.php";
	$query = "select * from p2records where videoid='" . $vid . "'";
	// if no content is selected, return blank
	if ($vid == 0) {
		echo '';
	}
	else {
		echo "<p style='border:1px solid black;padding:1em;'>";
	if($result = mysqli_query($db,$query)) {
		while ($row = mysqli_fetch_assoc($result)) {
		echo "<b>". $row['title'] . "</b>";
		echo "<br /><br /><b>Genre: </b>" . $row['genre'];
		// remove the last semicolon in keywords
		echo "<br /><b>Keywords: </b>" . substr($row['keywords'], 0, strlen($row['keywords'])-1);
		echo "<br /><b>Duration: </b>" . $row['duration'];
		echo "<br /><b>Color: </b>" . $row['color'];
		echo "<br /><b>Sound: </b>" . $row['sound'];
		echo "<br /><b>Sponsor: </b>" . $row['sponsorname'];
		}
	}
		echo "</p>";
	}
	mysql_close($db);
?>