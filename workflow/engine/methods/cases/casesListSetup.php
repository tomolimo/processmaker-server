<?php
global $RBAC;

if ($RBAC->userCanAccess("PM_SETUP") != 1 || $RBAC->userCanAccess("PM_SETUP_ADVANCE") != 1) {
    G::SendTemporalMessage("ID_USER_HAVENT_RIGHTS_PAGE", "error", "labels");
    exit(0);
}

$availableFields = array();

$oHeadPublisher = headPublisher::getSingleton();

$oHeadPublisher->addExtJsScript('cases/casesListSetup', false); //adding a javascript file .js
$oHeadPublisher->addContent('cases/casesListSetup'); //adding a html file  .html.
$oHeadPublisher->assignNumber("pageSize", 20); //sending the page size
$oHeadPublisher->assignNumber("availableFields", G::json_encode($availableFields));

G::RenderPage("publish", "extJs");
