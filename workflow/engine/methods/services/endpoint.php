<?php
G::LoadSystem('rbac');
G::loadClass('pmFunctions');
function FupdateAPPDATATYPO3($APP_UID, $new = 0)
{
    $_SESSION["PM_RUN_OUTSIDE_MAIN_APP"] = true;
    G::LoadClass("case");
    $caseInstance                              = new Cases();
    $newFields                                 = $caseInstance->loadCase($APP_UID);
    $newFields['APP_DATA']['FLAGTYPO3']        = 'On';
    $newFields['APP_DATA']['FLAG_ACTIONTYPO3'] = 'actionCreateCase';

    if ($_REQUEST['redirect'])
        $newFields['APP_DATA']['FLAG_REDIRECT_PAGE'] = urldecode($_REQUEST['redirect']);

    if ($new == 1) {

        $newFields['APP_DATA']['NUM_DOSSIER'] = $newFields['APP_NUMBER'];
    }

    PMFSendVariables($APP_UID, $newFields['APP_DATA']);
    $caseInstance->updateCase($APP_UID, $newFields);

}

function authentication($user, $pass)
{
    global $RBAC;

    if (strpos($pass, 'md5:') === false) {
        $pass = 'md5:' . $pass;
    }

    $uid = $RBAC->VerifyLogin($user, $pass);

    if ($uid < 0) {
        throw new Exception('Wrong user or pass.');
    }

    return $uid;
}

$result = new stdclass();
try {
    // Validating request data
    if (!isset($_REQUEST['a'])) {
        throw new Exception('The required parameter "a" is empty.');
    }

    ########### VALIDATE BROWSER NAVIGATION #####
    //validate_BrowserNavigation('full_assoc');

    ############## AUTHENTICATION ###############

    //conv_validateRole($_REQUEST['u']); // Validate Role

    if(isset($_SESSION['USER_LOGGED']) && $_SESSION['USER_LOGGED']!=''){
        @session_destroy();
        session_start();
        session_regenerate_id();
    }

    // Authentication
    $_SESSION['USER_LOGGED']  = authentication($_REQUEST['u'], $_REQUEST['p']);
    $_SESSION['USR_USERNAME'] = $_REQUEST['u'];
    switch ($_REQUEST['a']) {
        case 'webEntry':
            // Redirect to web entry
            if (!isset($_REQUEST['task'])) {
                throw new Exception('The required parameter "task" is empty.');
            }
            G::header('Location: ../' . urldecode($_REQUEST['task']));
            die();
            break;
        case 'mesdemandes':
            // Redirect to inbox
            //G::header('Location: ../convergenceList/inboxDinamic.php?table=95654345151237be9ca3ab2040266744&filter=demande');
            if (!isset($_REQUEST['task'])) {
                G::header('Location: ../convergenceList/inboxDinamic.php?idInbox=DEMANDES');
            } else
                G::header('Location: ../convergenceList/inboxDinamic.php?idInbox=' . $_REQUEST['task']);
            die();
            break;
        case 'inbox':
            // Redirect to inbox

            G::header('Location: ../cases/casesListExtJs');
            die();
            break;
        case 'main_init':
            // Redirect to inbox
            G::header('Location: ../cases/main_init');
            die();
            break;
        case 'main':
            // Redirect to inbox
            G::header('Location: ../cases/main');
            die();
            break;
        case 'editProfile':
            G::header('Location: ../fieldcontrol/my_profile');
            die();
            break;
        case 'editPassword':
            G::header('Location: ../fieldcontrol/my_profile.php?type=onlyPassword');
            die();
            break;
        case 'start':
            // Validating request data
            if (!isset($_REQUEST['task'])) {
                throw new Exception('The required parameter "task" is empty.');
            }
            // Start a case

            # Get the last draft case
            G::loadClass('pmFunctions');
            G::LoadClass('case');
            $caseInstance = new Cases();

            $sqlGetProcess    = 'SELECT PRO_UID FROM TASK WHERE TAS_UID="' . $_REQUEST['task'] . '"';
            $resultGetProcess = executeQuery($sqlGetProcess);

            $sqlDraft    = "SELECT MAX(APP_NUMBER), APP_UID FROM APPLICATION
                     WHERE APP_STATUS = 'DRAFT' AND PRO_UID='" . $resultGetProcess[1]['PRO_UID'] . "' AND APP_CUR_USER= '" . $_SESSION['USER_LOGGED'] . "'  GROUP BY APP_NUMBER ";
            $resultDraft = executeQuery($sqlDraft);
            if (sizeof($resultDraft)) {
                $sqlprocessTask    = "SELECT * FROM APP_DELEGATION WHERE APP_UID= '" . $resultDraft[1]['APP_UID'] . "' AND DEL_INDEX= 1 ";
                $resultprocessTask = executeQuery($sqlprocessTask);
                if (sizeof($resultprocessTask)) {
                    $_SESSION['APPLICATION']   = $resultDraft[1]['APP_UID'];
                    $_SESSION['INDEX']         = 1;
                    $_SESSION['PROCESS']       = $resultprocessTask[1]['PRO_UID'];
                    $_SESSION['TASK']          = $resultprocessTask[1]['TAS_UID'];
                    $_SESSION['STEP_POSITION'] = 0;
                    FupdateAPPDATATYPO3($_SESSION['APPLICATION']); // typo3
                    // Execute events
                    require_once 'classes/model/Event.php';
                    $eventInstance = new Event();
                    $eventInstance->createAppEvents($resultprocessTask[1]['PRO_UID'], $resultDraft[1]['APP_UID'], '1', $resultprocessTask[1]['TAS_UID']);

                    // Redirect to cases steps
                    $nextStep = $caseInstance->getNextStep($resultprocessTask[1]['PRO_UID'], $resultDraft[1]['APP_UID'], '1', 0);
                    G::header('Location: ../cases/' . $nextStep['PAGE']);
                }
            }
            # End Get the last draft case
            else {

                $data                      = $caseInstance->startCase($_REQUEST['task'], $_SESSION['USER_LOGGED']);
                $_SESSION['APPLICATION']   = $data['APPLICATION'];
                $_SESSION['INDEX']         = $data['INDEX'];
                $_SESSION['PROCESS']       = $data['PROCESS'];
                $_SESSION['TASK']          = $_REQUEST['task'];
                $_SESSION['STEP_POSITION'] = 0;

                // Execute events
                require_once 'classes/model/Event.php';
                $eventInstance = new Event();
                $eventInstance->createAppEvents($_SESSION['PROCESS'], $_SESSION['APPLICATION'], $_SESSION['INDEX'], $_SESSION['TASK']);

                FupdateAPPDATATYPO3($_SESSION['APPLICATION'], 1); // typo3

                // Redirect to cases steps
                $nextStep = $caseInstance->getNextStep($_SESSION['PROCESS'], $_SESSION['APPLICATION'], $_SESSION['INDEX'], $_SESSION['STEP_POSITION']);
                G::header('Location: ../cases/' . $nextStep['PAGE']);
            }
            die();
            break;
        default:
            throw new Exception('Unknow action ("' . $_REQUEST['a'] . '").');
            break;
    }
}
catch (Exception $error) {
    $result->status  = 'error';
    $result->message = $error->getMessage();
}

$messageError = "<p style='margin-bottom: 0cm'>« ce compte n’existe pas , veuillez vous inscrire » ?</p>";
die($messageError);