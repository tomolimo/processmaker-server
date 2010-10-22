<?php
/**
 * processes_Save.php
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
 
global $RBAC;
$access = $RBAC->userCanAccess('PM_FACTORY');
if( $access != 1 ){
  switch ($access)
  {
  	case -1:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	case -2:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	default:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;  	
  }
}  


G::LoadThirdParty('pear/json','class.json');

if ( isset($_GET['PRO_UID'])) {
 $_POST['form']['PRO_UID'] = $_GET['PRO_UID'];
}

  G::LoadClass('processMap');
  $oProcessMap = new ProcessMap();
  if( !isset($_POST['form']['PRO_UID']) ) {
  	$_POST['form']['USR_UID'] = $_SESSION['USER_LOGGED'];
	  $oJSON  = new Services_JSON();
	  require_once 'classes/model/Task.php';

    $sProUid = $oProcessMap->createProcess($_POST['form']);
    
    //call plugins
    $oData['PRO_UID']      = $sProUid;
    $oData['PRO_TEMPLATE'] = (isset($_POST['form']['PRO_TEMPLATE']) && $_POST['form']['PRO_TEMPLATE']!='')?$_POST['form']['PRO_TEMPLATE']:'';
    $oData['PROCESSMAP']   = $oProcessMap;

    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $oPluginRegistry->executeTriggers ( PM_NEW_PROCESS_SAVE , $oData );
    
    G::header('location: processes_Map?PRO_UID='. $sProUid );
    
    die;
  }
  else {
	  $oProcessMap->updateProcess($_POST['form']);
	  $sProUid = $_POST['form']['PRO_UID'];
  }
  //Save Calendar ID for this process
  G::LoadClass("calendar");
  $calendarObj=new Calendar();
  $calendarObj->assignCalendarTo($sProUid,$_POST['form']['PRO_CALENDAR'],'PROCESS');

  if ($_POST['form']['THETYPE'] == '')
  {
    G::header('location: main');
  }
