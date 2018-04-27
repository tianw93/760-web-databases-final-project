<?php
	// read file and put all the keywords into an array
	$lines = file("p2-keywordphrases.txt");
	$words = array();
	foreach ($lines as $line){
		array_push($words, $line);
	}
	$word = $_POST['keywords'];
	// if no search term is entered, return blank
	if (strlen($word) == 0){
		echo '';
	}
	else {
		$results = array();
		// compare the string sent by Ajax to keywords list
		foreach ($words as $var){
		if (substr($var, 0, strlen($word)) === $word){
			array_push($results, $var);
			}
		}
		echo "<p style='border:1px solid black;padding:1em;'>";
		// only return the first 10 keyword suggestions
		for ($i=0; $i<10; $i++) {
			echo $results[$i] . '<br />';
		}
		echo "</p>";
	}
?>