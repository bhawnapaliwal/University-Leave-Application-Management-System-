<?php   
	ini_set('display_errors', 1);
	echo PGSQL_LIBPQ_VERSION;
	$dbconn = pg_connect("host=localhost dbname=db1 user=postgres password=2097")
    or die('Could not connect: ' . pg_last_error());
    $query = "SELECT * FROM demo";
    $result = pg_exec($dbconn, $query);   
	echo "Number of rows: " . pg_numrows($result);   
	pg_freeresult($result);   
	pg_close($dbconn);
	// $db_handle = pg_connect("dbname=bpsimple user=jon");   
	// $query = "SELECT * FROM item";   
	// $result = pg_exec($db_handle, $query);   
	// echo "Number of rows: " . pg_numrows($result);   
	// pg_freeresult($result);   
	// pg_close($db_handle);   
?>