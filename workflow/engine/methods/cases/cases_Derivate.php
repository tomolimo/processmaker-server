<?
/**
 * cases_Derivate.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 *
 */


/* Permissions */
switch ($RBAC->userCanAccess('PM_CASES'))
{
	case -2:
		G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
		G::header('location: ../login/login');
		die;
		break;
	case -1:
		G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
		G::header('location: ../login/login');
		die;
		break;
}

/* Includes */
G::LoadClass('pmScript');
G::LoadClass('case');
G::LoadClass('derivation');

require_once 'classes/model/Event.php';
  

/* GET , POST & $_SESSION Vars */
/* Process the info */
$sStatus = 'TO_DO';
foreach ($_POST['form']['TASKS'] as $aValues){
//	if ($aValues['TAS_ASSIGN_TYPE'] == 'SELF_SERVICE'){
//		$sStatus = 'SELF_SERVICE';
//	}
}

try {
	//load data
	$oCase = new Cases ();
	$oCase->thisIsTheCurrentUser($_SESSION['APPLICATION'], $_SESSION['INDEX'], $_SESSION['USER_LOGGED'], 'REDIRECT', 'cases_List');
	$appFields = $oCase->loadCase( $_SESSION['APPLICATION'] );
	$appFields['APP_DATA'] = array_merge($appFields['APP_DATA'], G::getSystemConstants());

	//cleaning debug variables
	$_SESSION['TRIGGER_DEBUG']['DATA'] = Array();
    $_SESSION['TRIGGER_DEBUG']['TRIGGERS_NAMES'] = Array();
    $_SESSION['TRIGGER_DEBUG']['TRIGGERS_VALUES'] = Array();

	$triggers = $oCase->loadTriggers( $_SESSION['TASK'], 'ASSIGN_TASK', -2, 'BEFORE');

	//if there are some triggers to execute
	if( sizeof($triggers)  > 0) {
		//Execute triggers before derivation
		$appFields['APP_DATA'] = $oCase->ExecuteTriggers ( $_SESSION['TASK'], 'ASSIGN_TASK', -2, 'BEFORE', $appFields['APP_DATA'] );

		//save trigger variables for debugger
		$_SESSION['TRIGGER_DEBUG']['info'][0]['NUM_TRIGGERS'] 		= sizeof($triggers);
		$_SESSION['TRIGGER_DEBUG']['info'][0]['TIME'] 						= 'BEFORE';
		$_SESSION['TRIGGER_DEBUG']['info'][0]['TRIGGERS_NAMES'] 	= $oCase->getTriggerNames($triggers);
		$_SESSION['TRIGGER_DEBUG']['info'][0]['TRIGGERS_VALUES']	= $triggers;
	}

	$appFields['DEL_INDEX'] = $_SESSION['INDEX'];
	$appFields['TAS_UID']		= $_SESSION['TASK'];

	$oCase->updateCase ( $_SESSION['APPLICATION'], $appFields); //Save data 

	//derivate case
	 //////////////////////////////////////////////////////// 
	$oDerivation = new Derivation();
	$aCurrentDerivation = array(
	    'APP_UID'    => $_SESSION['APPLICATION'],
	    'DEL_INDEX'  => $_SESSION['INDEX'],
	    'APP_STATUS' => $sStatus,
	    'TAS_UID'    => $_SESSION['TASK'],
	    'ROU_TYPE'   => $_POST['form']['ROU_TYPE']
	);

	$oDerivation->derivate( $aCurrentDerivation, $_POST['form']['TASKS'] );
	/*
	///////////////////////////////////////////////////////// */
	
	$appFields = $oCase->loadCase( $_SESSION['APPLICATION'] ); //refresh appFields, because in derivations should change some values
	$triggers = $oCase->loadTriggers( $_SESSION['TASK'], 'ASSIGN_TASK', -2, 'AFTER'); //load the triggers after derivation
	if( sizeof($triggers) > 0 ) {
		$appFields['APP_DATA'] = $oCase->ExecuteTriggers ( $_SESSION['TASK'], 'ASSIGN_TASK', -2, 'AFTER', $appFields['APP_DATA'] ); //Execute triggers after derivation
		
		$_SESSION['TRIGGER_DEBUG']['info'][1]['NUM_TRIGGERS']	= sizeof($triggers);
		$_SESSION['TRIGGER_DEBUG']['info'][1]['TIME'] 					= 'AFTER';
		$_SESSION['TRIGGER_DEBUG']['info'][1]['TRIGGERS_NAMES'] 	= $oCase->getTriggerNames($triggers);
		$_SESSION['TRIGGER_DEBUG']['info'][1]['TRIGGERS_VALUES']	= $triggers;
	}
	$oCase->updateCase ( $_SESSION['APPLICATION'], $appFields);
	
	// Send notifications - Start
	$oUser     = new Users();
	$aUser     = $oUser->load($_SESSION['USER_LOGGED']);
	$sFromName = '"' . $aUser['USR_FIRSTNAME'] . ' ' . $aUser['USR_LASTNAME'] . '"';
	$oCase->sendNotifications($_SESSION['TASK'], $_POST['form']['TASKS'], $appFields['APP_DATA'], $_SESSION['APPLICATION'], $_SESSION['INDEX'], $sFromName);
	// Send notifications - End
	
	// Events - Start
	$oEvent = new Event();

	$oEvent->closeAppEvents($_SESSION['PROCESS'], $_SESSION['APPLICATION'], $_SESSION['INDEX'], $_SESSION['TASK']);
        $oCurrentAppDel = AppDelegationPeer::retrieveByPk($_SESSION['APPLICATION'], $_SESSION['INDEX']+1 );
        $multipleDelegation = false;
        // check if there are multiple derivations
        if(count($_POST['form']['TASKS'])>1){
          $multipleDelegation  = true;
        }
        // If the case has been delegated
        if ( isset($oCurrentAppDel) ) {
            // if there is just a single derivation the TASK_UID can be set by the delegation data
            if ( !$multipleDelegation ){
                $aCurrentAppDel = $oCurrentAppDel->toArray( BasePeer::TYPE_FIELDNAME);
                $oEvent->createAppEvents($aCurrentAppDel['PRO_UID'], $aCurrentAppDel['APP_UID'], $aCurrentAppDel['DEL_INDEX'], $aCurrentAppDel['TAS_UID']);
            } else {
                // else we need to check every task and create the events if it have any
                foreach ( $_POST['form']['TASKS'] as $taskDelegated ){
                    $aCurrentAppDel = $oCurrentAppDel->toArray( BasePeer::TYPE_FIELDNAME);
                    $oEvent->createAppEvents($aCurrentAppDel['PRO_UID'], $aCurrentAppDel['APP_UID'], $aCurrentAppDel['DEL_INDEX'], $taskDelegated['TAS_UID']);
                }
            }
        }

	//Events - End

	$aNextStep['PAGE'] = 'casesListExtJs'; //'cases_List';
	if( isset($_SESSION['PMDEBUGGER']) && $_SESSION['PMDEBUGGER'] ){
		$_SESSION['TRIGGER_DEBUG']['BREAKPAGE'] = $aNextStep['PAGE'];
		G::header('location: ' . 'cases_Step?' .'breakpoint=triggerdebug');
	}
	else {
		G::header('location: cases_List');
	}
}
catch ( Exception $e ){
	/* Render Error Page */
	//$G_MAIN_MENU        = 'processmaker';
	//$G_SUB_MENU         = 'cases';
	//$G_ID_MENU_SELECTED = 'CASES';

	$aMessage = array();
	$aMessage['MESSAGE'] = $e->getMessage();
	$G_PUBLISH = new Publisher;
	$G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
  G::RenderPage( 'publish', 'blank');
//	G::RenderPage( 'publish');
}
