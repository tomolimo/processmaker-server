<?php
define('MABORAK_PATH',defined('PATH_GULLIVER_HOME')?PATH_GULLIVER_HOME.'js/maborak/':'../../');
//define('MABORAK_PATH','../');   #path normal
$modules = Array('rpc','validator','drop','drag','dom','abbr','fx','panel','app','rss','dashboard','xmlform','dynaform');
$l = ($_GET && $_GET['load'])?$_GET['load']:'';
$j = explode(',',$l);
$js= '';
header('Content-type: text/javascript');
for($i =0;$i<count($j);$i++)
{
	if(in_array($j[$i],$modules))
	{
		$a = MABORAK_PATH.'core/module.'.$j[$i].'.js';
		$r = fopen($a, "r");
		$c = fread($r, filesize($a));
//		$c = preg_replace("/(\/\*[\w\W]*?\*\/|\/\/[\w\W]*?\n|\t|\r|\n| {4})/","",$c);
		$c = preg_replace("/(\/\*[\w\W]*?\*\/|\/\/[\w\W]*?\n|\t)/","",$c);
		fclose($r);
		$js.=$c;
	}
}
echo $js;
?>
