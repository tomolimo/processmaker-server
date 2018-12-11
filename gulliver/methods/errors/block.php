<?php
if (function_exists("http_response_code")) {
    http_response_code(200);
}

$http = G::is_https() ? "https" : "http";
$host = $_SERVER["SERVER_NAME"] . (($_SERVER["SERVER_PORT"] != "80") ? ":" . $_SERVER["SERVER_PORT"] : "");

$urlLogin = $http . "://" . $host . "/sys/en/neoclassic/login/login";
$urlHome = $urlLogin;

if (isset($_GET["url"]) && $_GET["url"] != "") {

    $url = urldecode($_GET["url"]);
    $url = explode("/", $url);

    $sysSys = "";
    $sysLang = "";
    $sysSkin = "";

    if (isset($url[1]) && preg_match("/^sys(.+)$/", $url[1], $match)) {
        $sysSys = $match[1];

        // Check if sys path exists
        $checkDir = PATH_DATA . "sites/" . $sysSys;
        if (!is_dir($checkDir)) {
            $sysSys = '';
        }
    }


    if (isset($url[2])) {
        $sysLang = $url[2];
    }

    if (isset($url[3])) {
        $sysSkin = $url[3];

        // Check if sys path exists
        $checkDir = PATH_SKIN_ENGINE . $sysSkin;
        if (!is_dir($checkDir)) {
            // Try this again
            $checkDir = PATH_CUSTOM_SKINS . $sysSkin;
            if (!is_dir($checkDir)) {
                $sysSkin = '';
            }
        }
    }

    if ($sysSys != "" && $sysLang != "" && $sysSkin != "") {
        $urlLogin = $http . "://" . $host . "/sys" . $sysSys . "/" . $sysLang . "/" . $sysSkin . "/login/login";
        $urlHome = $http . "://" . $host . "/sys" . $sysSys . "/" . $sysLang . "/" . $sysSkin . "/cases/main";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
    <link href="../lib/pmdynaform/libs/bootstrap-3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="block.css" type="text/css" rel="stylesheet"/>
</head>
<body class="nav-md special-page" style="margin: 0px; overflow: auto;">
<div class="container body ">

    <div class="main_container">

        <!-- page content -->
        <div class="col-md-12">
            <div class="col-middle">
                <div class="text-center">
                    <p><img src="images/fa-hand-pointer-o.png"></p>
                </div>
                <div class="text-center text-error">
                    <h1>Hi there, please check your tabs!</h1>
                    <p><b>A browser instance of ProcessMaker is actually open.</b> You can start only one at the time.
                        For more information:
                    </p>
                    <div class="mid_center">
                        <a type="button" class="btn btn-success btn-lg" href="http://wiki.processmaker.com">Click here
                            to see our Wiki</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

<!-- footer content -->
<div id="footer">
    <div class="container">
        <p><img src="images/processmaker.logow.png"></p>
        <p class="muted credit">Supplied free of charge with no support, certification, warranty, maintenance nor indemnity by ProcessMaker and its Certified Partners<br>
            Copyright © 2003-<?php echo date('Y');?> ProcessMaker, Inc. All rights reserved. </p>
    </div>
</div>

</body>
</html>
