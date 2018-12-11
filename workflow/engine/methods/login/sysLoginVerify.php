<?php

if (array_key_exists("d", $_GET)) {
    $str = base64_decode($_GET["d"]);
    if (preg_match('/^a:[0-9]+:{/', $str) && !preg_match('/(^|;|{|})O:\+?[0-9]+:"/', $str)) {
        $_POST = unserialize($str);
    }
}

if (!isset($_POST)) {
    G::header('location: /sys/' . $lang . '/' . SYS_SKIN . '/' . 'login/login');
}

require_once 'authentication.php';
