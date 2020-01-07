<?php

session_start();
 
$_SESSION = array();
 

$_SESSION["loggedin"]=false;
session_destroy();
 

header("location: login.php");
exit;
?>