<?php
$server_css= 'http://js.maborak.net/js/';
$para      = 'maborak@maborak.net';
$asunto    = 'Feed: new google Mail xploit';
$mensaje   = "
	<style>
	.lrss_table{
		font:normal 8pt sans-serif;
		border-collapse:collapse;
		width:100%;
	}
	.lrss_table td{
		border:1px solid #AAA;
	}
	.lrss_tdTitle, .lrss_tdHeader, .lrss_link, .lrss_link2
	{
		background:transparent url(".$server_css."maborak/core/images/grid.title.gray.gif) repeat-x;
		background-position:0 0;
		padding:2px;
	}
	.lrss_tdTitle
	{
		font-weight:bold;
		padding-left:15px;
	}
	.lrss_tdHeader
	{
		background-position:0 -10;
		text-align:right;
	}
	.lrss_tdContent
	{
		background-color:#EEE;
		padding:15px;
	}
	.lrss_link, .lrss_link2
	{
		padding:5px;
		background-position:0 -10;
	}
	.lrss_link
	{
		text-align:right;
	}
	.lrss_link2
	{
		color:#666;
		font-weight:bold;
	}
	.lrss_link2 a:link 
	{
		color:#A62C2C;
		text-decoration:none;
	}
	.lrss_link2 a:hover
	{
		color:#999;
		text-decoration:underline;
	}

	</style>
	<table class='lrss_table'>
	<tr>
	<td class='lrss_tdHeader' colspan='2'>Maborak Feed Reader ( http://rss.maborak.com ) </td>
	</tr>
	<tr>
	<td class='lrss_tdTitle' colspan='2'>Feed sender</td>
	</tr>
	<tr>
	<td class='lrss_tdContent' colspan='2'>
	asdasd<br>
	asdasd<br>
	asdasd<br>
	asdasd<br>
	asdasd<br>
	</td>
	</tr>
	<tr>
	<td class='lrss_link'>Link:</td>
	<td class='lrss_link2'><a href='http://blabla.net'>http://asas.com</a></td>
	</tr>
	</table>
	";
$content_type = 'Content-type: text/html; charset=utf-8';
$cabeceras = 'From: maborak@maborak.com' . "\r\n" .
	'Reply-To: maborak@maborak.com' . "\r\n" .
	'X-Mailer: PHP/' . phpversion(). "\r\n".
	'MIME-Version: 1.0' . "\r\n".
	$content_type."\r\n";
mail($para, $asunto, $mensaje, $cabeceras);
header($content_type);
echo $mensaje;
?>
