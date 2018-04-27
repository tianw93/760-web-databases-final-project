<?php
	//connect to the database
    $h = 'pearl.ils.unc.edu';
	$u = 'webdb_tianw93';
	$p = '930712';
	$dbname = 'webdb_tianw93';
	$db = mysqli_connect($h,$u,$p,$dbname);
	if (mysqli_connect_errno()) {
        echo "Connect failed" . mysqli_connect_error();
		exit(); 
	}
?>