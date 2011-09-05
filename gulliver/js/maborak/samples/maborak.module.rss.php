<?php
header('Content-Type: text/xml; charset=utf-8');
$action = $_POST['action'];
if($action=="proxy")
{
	$url = $_POST['url'];
	if($url)
	{
		$content = @file_get_contents($url);
		echo ($content)?$content:"<error>Error loading RSS</error>";
	}
}
?>
