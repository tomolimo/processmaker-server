<?php
// General Validations
if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = '';
}

if (!isset($_REQUEST['limit'])) {
    $_REQUEST['limit'] = '';
}

if (!isset($_REQUEST['start'])) {
    $_REQUEST['start'] = '';
}

//Initialize response object
$response = new stdclass();
$response->status = 'OK';
//Main switch
try {
    $actionsByEmail = new \ProcessMaker\BusinessModel\ActionsByEmail();

    switch ($_REQUEST['action']) {
        case 'editTemplate':
            $response = $actionsByEmail->editTemplate($_REQUEST);
            break;
        case 'updateTemplate':
            $response = $actionsByEmail->updateTemplate($_REQUEST);
            break;
        case 'loadFields':
            $response = $actionsByEmail->loadFields($_REQUEST);
            break;
        case 'saveConfiguration':
            $response = $actionsByEmail->saveConfiguration2($_REQUEST);
            break;
        case 'loadActionByEmail':
            $response = $actionsByEmail->loadActionByEmail($_REQUEST);
            break;
        case 'forwardMail':
            $response = $actionsByEmail->forwardMail($_REQUEST);
            break;
        case 'viewForm':
            $response = $actionsByEmail->viewForm($_REQUEST);
            break;
    }
} catch (Exception $error) {
    $response = new stdclass();
    $response->status = 'ERROR';
    $response->message = $error->getMessage();
}

header('Content-Type: application/json;');

die(G::json_encode($response));
