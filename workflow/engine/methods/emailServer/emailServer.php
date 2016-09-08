<?php

global $RBAC;



$resultRbac  = $RBAC->requirePermissions('PM_SETUP_EMAIL');

if (!$resultRbac) {

    G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');

    G::header('location: ../login/login');

    die();

}



//Data

$configuration = new Configurations();

$arrayConfigPage = $configuration->getConfiguration("emailServerList", "pageSize", null, $_SESSION["USER_LOGGED"]);



$arrayConfig = array();

$arrayConfig["pageSize"] = (isset($arrayConfigPage["pageSize"]))? $arrayConfigPage["pageSize"] : 20;



$headPublisher = &headPublisher::getSingleton();

$headPublisher->addContent("emailServer/emailServer"); //Adding a HTML file

$headPublisher->addExtJsScript("emailServer/emailServer", false); //Adding a JavaScript file

$headPublisher->assign("CONFIG", $arrayConfig);



/*----------------------------------********---------------------------------*/



G::RenderPage("publish", "extJs");


