<?php

G::pr($_GET);

if (! empty($_GET['error'])) {
    G::pr($_GET);
    die();
}

$http = G::is_https() ? 'https' : 'http';
$host = $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != '80' ? ':' . $_SERVER['SERVER_PORT'] : '');
$endpoint = sprintf('%s://%s/%s/oauth2/token', $http, $host, config("system.workspace"));
$code = empty($_GET['code']) ? 'NN' : $_GET['code'];

$clientId = 'x-pm-local-client';
$secret = '179ad45c6ce2cb97cf1029e212046e81';
$userPwd = $clientId.':'.$secret;
$data = array(
    'grant_type' => 'authorization_code',
    'code' => $code
);

$ch = curl_init($endpoint);

curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_USERPWD, $userPwd);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

$data = @json_decode(curl_exec($ch));
curl_close($ch);

G::pr((array) $data);