<?php
header('Content-Type: text/xml; charset=utf-8');
$url = $_POST['url'];
if($url)
{
	echo @file_get_contents($url);
}
?>
