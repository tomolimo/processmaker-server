<?php
$config = array();
$config["pageSize"] = 20;

$arrayScope = array(
    array("value" => "view_processes", "label" => "View Processes"),
    array("value" => "edit_processes", "label" => "Edit Processes")
);

$headPublisher = headPublisher::getSingleton();
$headPublisher->addContent("oauth2" . PATH_SEP . "accessTokenSetup"); //Adding a HTML file .html
$headPublisher->addExtJsScript("oauth2" . PATH_SEP . "accessTokenSetup", false); //Adding a JavaScript file .js
$headPublisher->assign("CONFIG", $config);
$headPublisher->assign("SCOPE", $arrayScope);

G::RenderPage("publish", "extJs");
