<?php
$nombre_archivo = 'grid.txt';
$contenido = ($_POST['data']);
if (!$gestor = fopen($nombre_archivo, 'w+')) {
	echo "Error to write: $nombre_archivo";
	exit;
}
echo (fwrite($gestor, $contenido) === false)?"Failed":"Saved";
?>