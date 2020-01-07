<?php   
	ini_set('display_errors', 1);
	$dbconn = pg_connect("host=localhost dbname=db1 user=postgres password=2097")
    or die('Could not connect: ' . pg_last_error());
	// pg_close($dbconn); 
?>