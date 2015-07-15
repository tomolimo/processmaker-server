<?php
$config = array();
$config["pageSize"] = 20;

$headPublisher = &headPublisher::getSingleton();
//$headPublisher->addContent("oauth2" . PATH_SEP . "clientSetup"); //Adding a HTML file .html
$headPublisher->addExtJsScript("oauth2" . PATH_SEP . "clientSetup", false); //Adding a JavaScript file .js
$headPublisher->assign("CONFIG", $config);
$headPublisher->assign("CREATE_CLIENT", (isset($_GET["create_app"]))? 1 : 0);

G::RenderPage("publish", "extJs");

