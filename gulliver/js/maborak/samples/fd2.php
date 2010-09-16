<?php
session_start();
if(isset($_SESSION) && isset($_GET) && isset($_GET['file']) && isset($_GET['k']) && isset($_SESSION['token']))
{
	$file =  $_GET['file'];
	$t	= $_SESSION['token'];
	$k  = $_GET['k'];	
	if($k===$t)
	{
		unset($_SESSION['token']);
		header('Content-Type: application/x-shockwave-flash');
		readfile($file);
	}
	else
	{
		echo "Bad request";
	}
}
?>
