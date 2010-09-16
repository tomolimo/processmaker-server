<?php
if ($_SERVER['REQUEST_METHOD']=="POST") {
	include_once('class.json.php');	
	$valid_services=array("rate","comment");
	$json		= new Services_JSON();			
	$data	=!empty($_POST['data'])?((get_magic_quotes_gpc()==1)?stripslashes($_POST['data']):$_POST['data']):array();
	$d = $json->decode($data);
	$service=isset($d->service)?$d->service:"none";
	if (file_exists("services/service.{$service}.php")) {		
		require_once("services/service.{$service}.php");
		$c = "Service_".ucfirst($service);
		$s = new $c($d);
		$s->db = mysql_connect("192.168.0.59","wilmer","sample");
		mysql_select_db("services",$s->db);
		echo $json->encode($s->{$d->action}());
	}
	else 
	{
		die('Invalid service');
	}
}
else 
{
	die("Invalid service");
}
?>