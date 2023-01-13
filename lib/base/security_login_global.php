<?php
session_start();
if(!isset($_SESSION['userid']))
{
	header("Location: $dir"."login.php");
	echo "<script>window.top.location.href ='".$dir."login.php';</script>";
}


//CEK TIMEOUT LOGIN
$time = $_SERVER['REQUEST_TIME'];

$timeout_duration = $expired_login;

if (isset($_SESSION['LAST_ACTIVITY']) && 
   ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    if(session_destroy())
	{
		header("Location: $dir"."login.php");
	}
}

$_SESSION['LAST_ACTIVITY'] = $time;


?>