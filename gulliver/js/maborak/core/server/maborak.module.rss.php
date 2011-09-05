<?php
$pget	= ($_POST)?$_POST:$_GET;
$action = $pget['action'];
if($action=="proxy")
{
	header('Content-Type: text/xml;');
	$url = $pget['url'];
	if($url)
	{
		$content = @file_get_contents($url);
		echo ($content)?$content:"<error>Error loading RSS</error>";
	}
}
else if($action=="add")
{
	include_once('class.json.php');
	$jsonFile	= "../data/maborak.module.rss.feeds.json";
	$json		= new Services_JSON();
	$data		= $json->decode(stripslashes($pget['data']));
	$gestor		= @fopen($jsonFile, "r");
	$contenido	= ($gestor)?fread($gestor, filesize($jsonFile)):"[]";
	$dataParent	= $json->decode($contenido);
	$dataParent	= is_array($dataParent)?$dataParent:Array();
	$data->hash	= md5($data->url);
	array_push($dataParent,$data);
	$gestor = @fopen($jsonFile, 'w+');
	$writed = @fwrite($gestor, $json->encode($dataParent));
	$data->ok	= (!$gestor || !$writed)?"read:".$gestor."\nwrite:".$writed:"ok";
	echo $json->encode($data);
}
else if($action=="sendmail")
{
	$gpc = (get_magic_quotes_gpc() && $_SERVER['REQUEST_METHOD']=="POST")?true:false;
	//print_r($_SERVER);
	//print_r($_GET);
	//print_r($_POST);
	$server_css= 'http://js.maborak.com/';
	$to      = $pget['to']?$pget['to']:'maborak@maborak.com';
	$subject = $pget['subject']?(($gpc)?stripslashes($pget['subject']):$pget['subject']):"";
	$feed	 = $pget['feed']?$pget['feed']:"";
	$content = $pget['content']?(($gpc)?stripslashes($pget['content']):$pget['content']):"";
	$link	 = $pget['link']?$pget['link']:"#";
	$asunto    = $subject;
	$style	   = Array(
		'table'		=>'font:normal 8pt sans-serif;border-collapse:collapse;width:100%;',
		'td'		=>'border:1px solid #AAA;',
		'ima'		=>$server_css.'maborak/core/images/grid.title.gray.gif',
		'im'		=>'background-image:url('.$server_css.'maborak/core/images/grid.title.gray.gif);background-repeat:repeat-x;background-position:0 0;padding:2px;',
		'tdh'		=>'background-position:0 -10;text-align:right;padding-right:15px;',
		'tdtitle'	=>'font-weight:bold;padding-left:15px;background-color:#E7E7E7;',
		'tdcontent'	=>'padding:15px;background-color:#FAFAFA;',
		'link'		=>'color:#A62C2C;text-decoration:none;font-weight:bold;'
	
	);
	$header	   = htmlspecialchars(stripslashes($feed));
	$title	   = htmlspecialchars($asunto);
	$mensaje   = "
		<table style='".$style['table']."'>
		<tr>
		<td background='".$style['ima']."' style='".$style['td'].$style['im'].$style['tdh']."' colspan='2'>".$header."</td>
		</tr>
		<tr>
		<td style='".$style['td'].$style['im'].$style['tdtitle']."' colspan='2'>".$title."</td>
		</tr>
		<tr>
		<td style='".$style['td'].$style['tdcontent']."' colspan='2'>".$content."</td>
		</tr>
		<tr>
		<td style='".$style['td'].$style['im'].$style['tdh']."'width:30%;>Link:</td>
		<td style='".$style['td'].$style['im'].$style['tdh']."width:70%;text-align:left'><a style='".$style['link']."' href='".$link."'>".$link."</a></td>
		</tr>
		<tr>
		<td style='".$style['td'].$style['im'].$style['tdh']."'width:30%;>Rss reader:</td>
		<td style='".$style['td'].$style['im'].$style['tdh']."width:70%;text-align:left'><a href='http://rss.maborak.com'>http://rss.maborak.com</a></td>
		</tr>
		</table>
	";
	$content_type = 'Content-type: text/html; charset=utf-8';
	$cabeceras = 'From: Maborak reader <maborak@maborak.com>' . "\r\n" .
		'Reply-To: maborak@maborak.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion(). "\r\n".
		'MIME-Version: 1.0' . "\r\n".
		$content_type."\r\n";
	$toArr = explode(",",$to);
	for($i=0;$i<count($toArr);$i++)
	{
		if(strlen($toArr[$i])>8)
		{
			mail($toArr[$i], $asunto,$mensaje, $cabeceras);
		}
	}
	echo "Mail enviado";
}
?>
