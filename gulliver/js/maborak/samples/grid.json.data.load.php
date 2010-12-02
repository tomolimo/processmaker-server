<?php
$action = $_POST['action'];
if($action==="loadSimpleGrid")
{
	$nombre_archivo = 'grid.txt';
	//$nombre_archivo = $_SERVER ['DOCUMENT_ROOT'].'/js/maborak/samples/grid.json';
	$gestor = fopen($nombre_archivo, "r");
	$contenido = fread($gestor, filesize($nombre_archivo));
	echo $contenido;
	fclose($gestor);
}
elseif ($action==="scanDir")
{
	$dir = scandir("grid.files/");
	$suf = array();
	for($i=0;$i<count($dir);$i++)
	{
		if (substr($dir[$i],-5,5)===".grid")
		{
			array_push($suf,"\"".stripslashes($dir[$i])."\"");
		}
	}
	echo "[".implode(",",$suf)."]";
}
?>
