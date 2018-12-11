<?php

$filewsdl = PATH_METHODS . 'services' . PATH_SEP . 'pmos2.wsdl';
$content = file_get_contents($filewsdl);

$http = G::is_https() ? 'https' : 'http';
$port = $_SERVER['SERVER_PORT'] === '80' ? '' : ':' . $_SERVER['SERVER_PORT'];
$lang = defined('SYS_LANG') ? SYS_LANG : 'en';
$endpoint = $http . '://' . $_SERVER['SERVER_NAME'] . $port . '/sys' . config("system.workspace") . '/' . $lang . '/neoclassic/services/soap2';

$content = str_replace("___SOAP_ADDRESS___", $endpoint, $content);

header("Content-Type: application/xml;");
print $content;
