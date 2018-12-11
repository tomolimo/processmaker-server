<?php

$http = G::is_https() ? "https" : "http";
$host = $_SERVER["SERVER_NAME"] . (($_SERVER["SERVER_PORT"] != "80")? ":" . $_SERVER["SERVER_PORT"] : "");

$urlLogin = $http . "://" . $host . "/sys/en/neoclassic/login/login";
$urlHome =  $urlLogin;

if (isset($_GET["url"]) && $_GET["url"] != "") {
    $url = urldecode($_GET["url"]);
    $url = explode("/", $url);

    $sysSys = "";
    $sysLang = "";
    $sysSkin = "";

    if (isset($url[1]) && preg_match("/^sys(.+)$/", $url[1], $match)) {
        $sysSys = $match[1];
    }

    if (isset($url[2])) {
        $sysLang = $url[2];
    }

    if (isset($url[3])) {
        $sysSkin = $url[3];
    }

    if ($sysSys != "" && $sysLang != "" && $sysSkin != "") {
        $urlLogin = $http . "://" . $host . "/sys" . $sysSys . "/" . $sysLang . "/" . $sysSkin . "/login/login";
        $urlHome =  $http . "://" . $host . "/sys" . $sysSys . "/" . $sysLang . "/" . $sysSkin . "/cases/main";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Processmaker Styleguide</title>
    <!-- Bootstrap core CSS -->
    <link href="../lib/pmdynaform/libs/bootstrap-3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="../lib/pmdynaform/libs/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <!-- Custom styling plus plugins -->
    <link href="../lib/pmdynaform/libs/custom/custom.css" rel="stylesheet">
    <script type="text/javascript" src="../lib/pmdynaform/libs/jquery/jquery-1.11.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var btnLogin = $("#login"),
                btnGoBack = $("#goBack");
            btnLogin.click(function(e) {
                e.preventDefault();
                e.stopPropagation();
                window.location.href = '<?php echo  $urlLogin;?>';
            });
            btnGoBack.click(function(e){
                e.preventDefault();
                e.stopPropagation();
                history.back();
            });
        });
    </script>
</head>
<body class="nav-md special-page">
    <div class="container body ">
        <div class="main_container">
            <!-- page content -->
            <div class="col-md-12">
                <div class="col-middle">
                    <div class="text-center">
                        <h1 class="error-number"><i class="fa fa-hand-paper-o"></i></h1>
                    </div>
                    <div class="text-center text-error">
                        <h1>403 Access denied</h1>
                        <p>You don't have privileges to access with those credentials. You can contact your administrator, <br> <a id="goBack" href="#">go back</a> to where you came from, or
                        </p>
                        <div class="mid_center">
                            <button type="button" id="login" class="btn btn-success btn-lg">Login with other credentials</button>
                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->
        </div>
        <!-- footer content -->
        <div id="footer">
            <div class="container">
                <p><img src="../lib/pmdynaform/build/img/logow.png"></p>
                <p class="muted credit">Supplied free of charge with no support, certification, warranty, maintenance nor indemnity by ProcessMaker and its Certified Partners<br>
Copyright © 2003-<?php echo date("Y");?> ProcessMaker, Inc. All rights reserved. </p>
            </div>
        </div>
    </div>

</body>
</html>