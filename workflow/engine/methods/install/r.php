<?php
$sh=md5(filemtime(PATH_GULLIVER."/class.g.php"));
$h=G::encrypt("localhost".$sh."mysql-root".$sh."mysql-root-password".$sh.(1),$sh);
$db_text = "
	HASH_INSTALLATION={$h}\n
	SYSTEM_HASH: {$sh}\n";
echo $db_text;
?>
