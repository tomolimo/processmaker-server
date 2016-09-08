<?php
session_start();
if (isset($_GET['code'])) {
    $_SESSION['CODE_GMAIL'] = $_GET['code'];

    echo "<SCRIPT language='Javascript' type='text/javascript'>";
    echo "window.close()";
    echo "</script>";
    exit;
}