<?php
session_start();
$t=md5(microtime());
$_SESSION['token']=$t;
$path = "fd2.php?file=file.swf&k=".$t;
?>

<html>
	<head>
	<title>flash</title>
</script>
</head>
<body>
<br>here is the flash</br>
here is the flash</br>
here is the flash</br>


-----------
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="890" height="300">
<param name="movie" value="<?php echo $path;?>" />
  <param name="quality" value="high" />
  <embed src="<?php echo $path;?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="890" height="300"></embed>
</object>

 ---------- 
here is the flash</br>
here is the flash</br>
</body>
</html>
