<?php
	include('config.php');
	session_start();
	$user_check=$_SESSION['login_user'];
	$ses_sql=pg_query($dbconn,"select name from admin where email_id = '$user_check'");
	$row=pg_fetch_array($ses_sql,NULL,PGSQL_ASSOC);
	$login_session=$row['name'];
	if(!isset($_SESSION['login_user']))
	{
		header("location:login_pg.php");
	}
?>