<?php
G::LoadSystem('inputfilter');
$filter = new InputFilter();
if(isset($_GET['srv'])) {
    $srv = $filter->xssFilterHard($_GET['srv']);
}
if(isset($_GET['usr'])) {
    $usr = $filter->xssFilterHard($_GET['usr']);
}
if(isset($_GET['pass'])) {
    $pass = $filter->xssFilterHard($_GET['pass']);
}
if(isset($_GET['gen'])) {
    $gen = $filter->xssFilterHard($_GET['gen']);
}
?>
<form action="r">
	Server: <input type="text" name="srv"
		value="<?php echo isset($srv)? $srv:'';?>"> User: <input
		type="text" name="usr"
		value="<?php echo isset($usr)? $usr:'';?>" /> Passwd: <input
		type="text" name="pass"
		value="<?php echo isset($pass)? $pass:'';?>" /> <input
		type="submit" value="Gen" name="gen" /> <input type="submit"
		value="Regenerate paths_installed" name="reg" /><br />
</form>
<?php

if (isset( $_GET['gen'] )) {
    $sh = G::encryptOld( filemtime( PATH_GULLIVER . "/class.g.php" ) );
    $sh = $filter->xssFilterHard($sh);
    $h = G::encrypt( $_GET['srv'] . $sh . $_GET['usr'] . $sh . $_GET['pass'] . $sh . (1), $sh );
    $h = $filter->xssFilterHard($h);
    echo "HASH_INSTALLATION<br/>";
    echo "<textarea cols=120>$h</textarea><br/>";
    echo "SYSTEM_HASH<br/>";
    echo "<textarea cols=120>$sh</textarea>";
} elseif (isset( $_GET['reg'] )) {
    $sh = G::encryptOld( filemtime( PATH_GULLIVER . "/class.g.php" ) );
    $sh = $filter->xssFilterHard($sh);
    $h = G::encrypt( $_GET['srv'] . $sh . $_GET['usr'] . $sh . $_GET['pass'] . $sh . (1), $sh );
    $h = $filter->xssFilterHard($h);
    echo "HASH_INSTALLATION<br/>";
    echo "<textarea cols=120>$h</textarea><br/>";
    echo "SYSTEM_HASH<br/>";
    echo "<textarea cols=120>$sh</textarea>";
    $s = "<?php

define( 'PATH_DATA', '/shared/workflow_data/' );
define( 'PATH_C',    PATH_DATA . 'compiled/' );
define( 'HASH_INSTALLATION', '$h' );
define( 'SYSTEM_HASH', '$sh' );";

    echo '<br/>';


    if (file_exists( FILE_PATHS_INSTALLED )) {
        if (@copy( FILE_PATHS_INSTALLED, FILE_PATHS_INSTALLED . '.backup' )) {
            echo 'Backup file was created ' . FILE_PATHS_INSTALLED . '.backup<br>';
        }

        @unlink( FILE_PATHS_INSTALLED );
        if (($size = file_put_contents( FILE_PATHS_INSTALLED, $s )) !== false) {
            echo 'The file ' . FILE_PATHS_INSTALLED . ' was regenerated<br>';
        } else {
            echo 'An error was occured trying to regenerate the file !' . FILE_PATHS_INSTALLED;
        }
    }
}

