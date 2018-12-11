<?php
global $RBAC;

use ProcessMaker\Plugins\PluginRegistry;
use ProcessMaker\BusinessModel\Process as BmProcess;

$resultRbac  = $RBAC->requirePermissions('PM_SETUP_ADVANCE', 'PM_SETUP_LOGS');
if (!$resultRbac) {
    G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
    G::header('location: ../login/login');
    die();
}

$c = new Configurations();
$configPage = $c->getConfiguration('eventList', 'pageSize', '', $_SESSION['USER_LOGGED']);
$Config['pageSize'] = isset($configPage['pageSize']) ? $configPage['pageSize'] : 20;

$G_MAIN_MENU = 'processmaker';
$G_SUB_MENU = 'logs';
$G_ID_MENU_SELECTED = 'logs';
$G_ID_SUB_MENU_SELECTED = 'EMAILS';

//get values for the comboBoxes
$userUid = (isset($_SESSION['USER_LOGGED']) && $_SESSION['USER_LOGGED'] != '') ? $_SESSION['USER_LOGGED'] : null;

//Get the status values
$status = AppMessage::getAllStatus();

//Get the process values
$process = new BmProcess();
$processes = $process->getProcessList('', true);

//Review if the plugin External is enable
$pluginRegistry = PluginRegistry::loadSingleton();
$flagER = $pluginRegistry->isEnable('externalRegistration') ? 1 : 0;

$G_PUBLISH = new Publisher();

$oHeadPublisher = headPublisher::getSingleton();
$oHeadPublisher->addExtJsScript('mails/emailList', false); //adding a javascript file .js
$oHeadPublisher->addContent('mails/emailList'); //adding a html file  .html.
//sending the columns to display in grid
$oHeadPublisher->assign('statusValues', $status);
$oHeadPublisher->assign('processValues', $processes);
$oHeadPublisher->assign('flagER', $flagER);

G::RenderPage( 'publish', 'extJs' );
