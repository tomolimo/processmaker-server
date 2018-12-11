<?php

use ProcessMaker\BusinessModel\Task as BusinessModelTask;

/* Permissions */
switch ($RBAC->userCanAccess('PM_CASES')) {
    case - 2:
        G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
        G::header('location: ../login/login');
        die();
        break;
    case - 1:
        G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
        G::header('location: ../login/login');
        die();
        break;
}

/* GET , POST & $_SESSION Vars */

/* Menues */
$_SESSION['bNoShowSteps'] = true;
$G_MAIN_MENU = 'processmaker';
$G_SUB_MENU = 'caseOptions';
$G_ID_MENU_SELECTED = 'CASES';
$G_ID_SUB_MENU_SELECTED = '_';

/* Prepare page before to show */
$oCase = new Cases();
//Check the authorization
$objCase = new \ProcessMaker\BusinessModel\Cases();
$aUserCanAccess = $objCase->userAuthorization(
    $_SESSION['USER_LOGGED'],
    $_SESSION['PROCESS'],
    $_GET['APP_UID'],
    array('PM_ALLCASES'),
    array('SUMMARY_FORM' => 'VIEW')
);

if (isset($_SESSION['ACTION']) && ($_SESSION['ACTION'] == 'jump')) {
    $Fields = $oCase->loadCase($_SESSION['APPLICATION'], $_SESSION['INDEX'], $_SESSION['ACTION']);
    $process = new Process();
    $processData = $process->load($Fields['PRO_UID']);
    if (isset($processData['PRO_DYNAFORMS']['PROCESS']) && $processData['PRO_DYNAFORMS']['PROCESS'] != '' &&
        $aUserCanAccess['objectPermissions']['SUMMARY_FORM']
    ) {
        $_REQUEST['APP_UID'] = $Fields['APP_UID'];
        $_REQUEST['DEL_INDEX'] = $Fields['DEL_INDEX'];
        $_REQUEST['DYN_UID'] = $processData['PRO_DYNAFORMS']['PROCESS'];
        require_once(PATH_METHODS . 'cases' . PATH_SEP . 'summary.php');
        exit();
    }
} else {
    $Fields = $oCase->loadCase($_SESSION['APPLICATION'], $_SESSION['INDEX']);
}

if (!$aUserCanAccess['participated'] && !$aUserCanAccess['supervisor'] && !$aUserCanAccess['rolesPermissions']['PM_ALLCASES'] && !$aUserCanAccess['objectPermissions']['SUMMARY_FORM']) {
    $aMessage['MESSAGE'] = G::LoadTranslation('ID_NO_PERMISSION_NO_PARTICIPATED');
    $G_PUBLISH = new Publisher();
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage);
    G::RenderPage('publishBlank', 'blank');
    die();
}
if (isset($aRow['APP_TYPE'])) {
    switch ($aRow['APP_TYPE']) {
        case 'PAUSE':
            $Fields['STATUS'] = ucfirst(strtolower(G::LoadTranslation('ID_PAUSED')));
            break;
        case 'CANCEL':
            $Fields['STATUS'] = ucfirst(strtolower(G::LoadTranslation('ID_CANCELLED')));
            break;
    }
}

$actions = 'false';
if (isset($_GET['action']) && $_GET['action'] == 'paused') {
    $actions = 'true';
}

    /* Render page */
$oHeadPublisher = headPublisher::getSingleton();

$oHeadPublisher->addScriptCode("
  if (typeof parent != 'undefined') {
    if (parent.showCaseNavigatorPanel) {
      parent.showCaseNavigatorPanel('{$Fields['APP_STATUS']}');
    }
  }");

$oHeadPublisher->addScriptCode('
  var Cse = {};
  Cse.panels = {};
  var leimnud = new maborak();
  leimnud.make();
  leimnud.Package.Load("rpc,drag,drop,panel,app,validator,fx,dom,abbr",{Instance:leimnud,Type:"module"});
  leimnud.Package.Load("cases",{Type:"file",Absolute:true,Path:"/jscore/cases/core/cases.js"});
  leimnud.Package.Load("cases_Step",{Type:"file",Absolute:true,Path:"/jscore/cases/core/cases_Step.js"});
  leimnud.Package.Load("processmap",{Type:"file",Absolute:true,Path:"/jscore/processmap/core/processmap.js"});
  leimnud.exec(leimnud.fix.memoryLeak);
  ');

require_once 'classes/model/Process.php';

$objProc = new Process();
$aProc = $objProc->load($Fields['PRO_UID']);
$Fields['PRO_TITLE'] = $aProc['PRO_TITLE'];

$objTask = new Task();
if (!isset($Fields['TAS_UID']) || $Fields['TAS_UID'] == '') {
    $Fields['TAS_UID'] = $Fields['APP_DATA']['TASK'];
}

$tasksInParallel = explode('|', $Fields['TAS_UID']);
$tasksInParallel = array_filter($tasksInParallel, function ($value) {
    return !empty($value);
});
$nTasksInParallel = count($tasksInParallel);

if ($nTasksInParallel > 1) {
    $aTask = $objTask->load($tasksInParallel[$nTasksInParallel - 1]);
} else {
    $aTask = $objTask->load($Fields['TAS_UID']);
}

$Fields['TAS_TITLE'] = $aTask['TAS_TITLE'];

$objUser = new Users();
$oHeadPublisher = headPublisher::getSingleton();
$oHeadPublisher->addScriptFile('/jscore/cases/core/cases_Step.js');
$G_PUBLISH = new Publisher();
$G_PUBLISH->AddContent('xmlform', 'xmlform', 'cases/cases_Resume.xml', '', $Fields, '');
if ($Fields['APP_STATUS'] != 'COMPLETED') {
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'cases/cases_Resume_Current_Task_Title.xml', '', $Fields, '');
    $objDel = new AppDelegation();
    $parallel = $objDel->LoadParallel($Fields['APP_UID']);
    $FieldsPar = $Fields;
    foreach ($parallel as $row) {
        $FieldsPar['TAS_UID'] = $row['TAS_UID'];
        $task = $objTask->load($row['TAS_UID']);
        $FieldsPar['TAS_TITLE'] = $task['TAS_TITLE'];
        $FieldsPar['USR_UID'] = $row['USR_UID'];
        if (isset($row['USR_UID']) && !empty($row['USR_UID'])) {
            $user = $objUser->loadDetails($row['USR_UID']);
            $FieldsPar['CURRENT_USER'] = $user['USR_FULLNAME'];
        } else {
            $dummyTaskTypes = BusinessModelTask::getDummyTypes();
            if (!in_array($task["TAS_TYPE"], $dummyTaskTypes)) {
                $FieldsPar['CURRENT_USER'] = G::LoadTranslation('ID_TITLE_UNASSIGNED');
            } else {
                $FieldsPar['CURRENT_USER'] = '';
            }
        }
        $FieldsPar['DEL_DELEGATE_DATE'] = $row['DEL_DELEGATE_DATE'];
        $FieldsPar['DEL_INIT_DATE']     = $row['DEL_INIT_DATE'];
        $FieldsPar['DEL_TASK_DUE_DATE'] = $row['DEL_TASK_DUE_DATE'];
        $FieldsPar['DEL_FINISH_DATE']   = $row['DEL_FINISH_DATE'];
        $G_PUBLISH->AddContent('xmlform', 'xmlform', 'cases/cases_Resume_Current_Task.xml', '', $FieldsPar);
    }
}

G::RenderPage('publish', 'blank');
