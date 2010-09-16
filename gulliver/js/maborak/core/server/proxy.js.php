<?php
header("content-Type:text/javascript");
//$data = isset($_GET)?$_GET:$_POST;
$data = isset($_GET)?$_GET:Array();
if(isset($data['tmp']) && isset($data['url']))
{
	echo "try{\n";
		echo"/*\n";
		include_once('class.json.php');
		$json	= new Services_JSON();
		$options= Array(
			'method'=>(isset($data['method']) && in_array($data['method'],Array("GET","POST"))?$data['method']:"GET"),
			'args'	=>isset($data['args'])?$data['args']:" ",
			'url'	=>isset($data['url'])?$data['url']:""
		);
		$content=" ";
		if($options['url'])
		{
			if($options['method']==="POST")
			{
//				echo"por POST:\n";
//				print_r($_GET);
				$toOpen	= $options['args'];
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_POST,1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$options['args']);
				curl_setopt($ch, CURLOPT_URL,$options['url']);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
				curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				$content = @curl_exec ($ch);
				curl_close ($ch);
			}
			else
			{
//				echo"por GET:\n";
//				print_r($_GET);

//				echo"\nprint SERVER:------\n";
//		print_r($_SERVER);
//				echo"\n-----------------------\n";
				$toOpen	= $options['url']."?".($options['args']);
//				echo "\n".$toOpen."\n";
				$content= @file_get_contents($toOpen);
			}
		}
		echo "\n*/";
		echo 'window["'.addslashes($data['tmp']).'"]={data:"'.addslashes($json->encode($content)).'",loaded:true};';
		//echo 'window["'.addslashes($data['tmp']).'"]={data:"'.addslashes($json->encode("")).'",loaded:true};';
	echo"}catch(e){}";
}
else
{
	
}
?>
