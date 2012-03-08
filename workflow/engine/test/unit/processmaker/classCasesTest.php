<?php
/**
 * classCasesTest.php
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
  $unitFilename = $_SERVER['PWD'] . '/test/bootstrap/unit.php';
  require_once( $unitFilename );

  require_once( PATH_THIRDPARTY . '/lime/lime.php');
  require_once( PATH_THIRDPARTY.'lime/yaml.class.php');
 
  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');
  require_once( 'propel/Propel.php' );
  require_once ( "creole/Creole.php" );
  require_once (  PATH_CORE . "config/databases.php");
 
  G::LoadClass ( 'case');

 
  //$obj = new Cases ($dbc);
  $t   = new lime_test( 3, new lime_output_color() );

  $className = "Cases";
  $className = strtolower ( substr ($className, 0,1) ) . substr ($className, 1 );
  
  $reflect = new ReflectionClass( $className );
	$method = array ( );
	$testItems = 0;
 
  foreach ( $reflect->getMethods() as $reflectmethod )  {
  	$params = '';
  	foreach ( $reflectmethod->getParameters() as $key => $row )   {
  	  if ( $params != '' ) $params .= ', ';
  	  $params .= '$' . $row->name;
  	}

 		$testItems++;
  	$methods[ $reflectmethod->getName() ] = $params;
  }
  
  $className = ucwords($className);
  $t->diag("class $className" );

  $t->is( count($methods) , 75,  "class $className have " . 73 . ' methods.' );

 // Methods
  $aMethods = array_keys ( $methods );


  /*
  //checking method 'canStartCase'
   $t->is ( $aMethods[0],  'canStartCase',   'canStartCase() is callable' );

  //$result = $obj->canStartCase ( $sUIDUser);
  //$t->isa_ok( $result,      'NULL',   'call to method canStartCase ');
  $t->todo( "call to method canStartCase using $sUIDUser ");


  //checking method 'getStartCases'
  $t->is ( $aMethods[1],      'getStartCases',   'getStartCases() is callable' );

  //$result = $obj->getStartCases ( $sUIDUser);
  //$t->isa_ok( $result,      'NULL',   'call to method getStartCases ');
  $t->todo( "call to method getStartCases using $sUIDUser ");

  //checking method 'loadCase'
  $t->is ( $aMethods[2],      'loadCase',   'loadCase() is callable' );

  //$result = $obj->loadCase ( $sAppUid, $iDelIndex);
  //$t->isa_ok( $result,      'NULL',   'call to method loadCase ');
  $t->todo( "call to method loadCase using $sAppUid, $iDelIndex ");


  //checking method 'loadCaseByNumber'
  $t->is ( $aMethods[3],      'loadCaseByNumber',   'loadCaseByNumber() is callable' );

  //$result = $obj->loadCaseByNumber ( $sCaseNumber);
  //$t->isa_ok( $result,      'NULL',   'call to method loadCaseByNumber ');
  $t->todo( "call to method loadCaseByNumber using $sCaseNumber ");


  //checking method 'refreshCaseLabel'
  $t->is ( $aMethods[4],      'refreshCaseLabel',   'refreshCaseLabel() is callable' );

  //$result = $obj->refreshCaseLabel ( $sAppUid, $aAppData, $sLabel);
  //$t->isa_ok( $result,      'NULL',   'call to method refreshCaseLabel ');
  $t->todo( "call to method refreshCaseLabel using $sAppUid, $aAppData, $sLabel ");


  //checking method 'refreshCaseTitle'
  $t->is ( $aMethods[5],      'refreshCaseTitle',   'refreshCaseTitle() is callable' );

  //$result = $obj->refreshCaseTitle ( $sAppUid, $aAppData);
  //$t->isa_ok( $result,      'NULL',   'call to method refreshCaseTitle ');
  $t->todo( "call to method refreshCaseTitle using $sAppUid, $aAppData ");


  //checking method 'refreshCaseDescription'
  $t->is ( $aMethods[6],      'refreshCaseDescription',   'refreshCaseDescription() is callable' );

  //$result = $obj->refreshCaseDescription ( $sAppUid, $aAppData);
  //$t->isa_ok( $result,      'NULL',   'call to method refreshCaseDescription ');
  $t->todo( "call to method refreshCaseDescription using $sAppUid, $aAppData ");


  //checking method 'refreshCaseStatusCode'
  $t->is ( $aMethods[7],      'refreshCaseStatusCode',   'refreshCaseStatusCode() is callable' );

  //$result = $obj->refreshCaseStatusCode ( $sAppUid, $aAppData);
  //$t->isa_ok( $result,      'NULL',   'call to method refreshCaseStatusCode ');
  $t->todo( "call to method refreshCaseStatusCode using $sAppUid, $aAppData ");


  //checking method 'updateCase'
  $t->is ( $aMethods[8],      'updateCase',   'updateCase() is callable' );

  //$result = $obj->updateCase ( $sAppUid, $Fields);
  //$t->isa_ok( $result,      'NULL',   'call to method updateCase ');
  $t->todo( "call to method updateCase using $sAppUid, $Fields ");


  //checking method 'removeCase'
  $t->is ( $aMethods[9],      'removeCase',   'removeCase() is callable' );

  //$result = $obj->removeCase ( $sAppUid);
  //$t->isa_ok( $result,      'NULL',   'call to method removeCase ');
  $t->todo( "call to method removeCase using $sAppUid ");


  //checking method 'setDelInitDate'
  $t->is ( $aMethods[10],      'setDelInitDate',   'setDelInitDate() is callable' );

  //$result = $obj->setDelInitDate ( $sAppUid, $iDelIndex);
  //$t->isa_ok( $result,      'NULL',   'call to method setDelInitDate ');
  $t->todo( "call to method setDelInitDate using $sAppUid, $iDelIndex ");


  //checking method 'GetOpenThreads'
  $t->is ( $aMethods[11],      'GetOpenThreads',   'GetOpenThreads() is callable' );

  //$result = $obj->GetOpenThreads ( $sAppUid);
  //$t->isa_ok( $result,      'NULL',   'call to method GetOpenThreads ');
  $t->todo( "call to method GetOpenThreads using $sAppUid ");


  //checking method 'getSiblingThreads'
  $t->is ( $aMethods[12],      'getSiblingThreads',   'getSiblingThreads() is callable' );

  //$result = $obj->getSiblingThreads ( $sAppUid, $iDelIndex);
  //$t->isa_ok( $result,      'NULL',   'call to method getSiblingThreads ');
  $t->todo( "call to method getSiblingThreads using $sAppUid, $iDelIndex ");


  //checking method 'getOpenSiblingThreads'
  $t->is ( $aMethods[13],      'getOpenSiblingThreads',   'getOpenSiblingThreads() is callable' );

  //$result = $obj->getOpenSiblingThreads ( $sNextTask, $sAppUid, $iDelIndex, $sCurrentTask);
  //$t->isa_ok( $result,      'NULL',   'call to method getOpenSiblingThreads ');
  $t->todo( "call to method getOpenSiblingThreads using $sNextTask, $sAppUid, $iDelIndex, $sCurrentTask ");


  //checking method 'CountTotalPreviousTasks'
  $t->is ( $aMethods[14],      'CountTotalPreviousTasks',   'CountTotalPreviousTasks() is callable' );

  //$result = $obj->CountTotalPreviousTasks ( $sTasUid);
  //$t->isa_ok( $result,      'NULL',   'call to method CountTotalPreviousTasks ');
  $t->todo( "call to method CountTotalPreviousTasks using $sTasUid ");


  //checking method 'getOpenNullDelegations'
  $t->is ( $aMethods[15],      'getOpenNullDelegations',   'getOpenNullDelegations() is callable' );

  //$result = $obj->getOpenNullDelegations ( $sAppUid, $sTasUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getOpenNullDelegations ');
  $t->todo( "call to method getOpenNullDelegations using $sAppUid, $sTasUid ");


  //checking method 'isRouteOpen'
  $t->is ( $aMethods[16],      'isRouteOpen',   'isRouteOpen() is callable' );

  //$result = $obj->isRouteOpen ( $sAppUid, $sTasUid);
  //$t->isa_ok( $result,      'NULL',   'call to method isRouteOpen ');
  $t->todo( "call to method isRouteOpen using $sAppUid, $sTasUid ");


  //checking method 'newAppDelegation'
  $t->is ( $aMethods[17],      'newAppDelegation',   'newAppDelegation() is callable' );

  //$result = $obj->newAppDelegation ( $sProUid, $sAppUid, $sTasUid, $sUsrUid, $sPrevious, $iPriority, $sDelType, $iAppThreadIndex);
  //$t->isa_ok( $result,      'NULL',   'call to method newAppDelegation ');
  $t->todo( "call to method newAppDelegation using $sProUid, $sAppUid, $sTasUid, $sUsrUid, $sPrevious, $iPriority, $sDelType, $iAppThreadIndex ");


  //checking method 'updateAppDelegation'
  $t->is ( $aMethods[18],      'updateAppDelegation',   'updateAppDelegation() is callable' );

  //$result = $obj->updateAppDelegation ( $sAppUid, $iDelIndex, $iAppThreadIndex);
  //$t->isa_ok( $result,      'NULL',   'call to method updateAppDelegation ');
  $t->todo( "call to method updateAppDelegation using $sAppUid, $iDelIndex, $iAppThreadIndex ");


  //checking method 'GetAllDelegations'
  $t->is ( $aMethods[19],      'GetAllDelegations',   'GetAllDelegations() is callable' );

  //$result = $obj->GetAllDelegations ( $sAppUid);
  //$t->isa_ok( $result,      'NULL',   'call to method GetAllDelegations ');
  $t->todo( "call to method GetAllDelegations using $sAppUid ");


  //checking method 'GetAllThreads'
  $t->is ( $aMethods[20],      'GetAllThreads',   'GetAllThreads() is callable' );

  //$result = $obj->GetAllThreads ( $sAppUid);
  //$t->isa_ok( $result,      'NULL',   'call to method GetAllThreads ');
  $t->todo( "call to method GetAllThreads using $sAppUid ");


  //checking method 'updateAppThread'
  $t->is ( $aMethods[21],      'updateAppThread',   'updateAppThread() is callable' );

  //$result = $obj->updateAppThread ( $sAppUid, $iAppThreadIndex, $iNewDelIndex);
  //$t->isa_ok( $result,      'NULL',   'call to method updateAppThread ');
  $t->todo( "call to method updateAppThread using $sAppUid, $iAppThreadIndex, $iNewDelIndex ");


  //checking method 'closeAppThread'
  $t->is ( $aMethods[22],      'closeAppThread',   'closeAppThread() is callable' );

  //$result = $obj->closeAppThread ( $sAppUid, $iAppThreadIndex);
  //$t->isa_ok( $result,      'NULL',   'call to method closeAppThread ');
  $t->todo( "call to method closeAppThread using $sAppUid, $iAppThreadIndex ");


  //checking method 'closeAllThreads'
  $t->is ( $aMethods[23],      'closeAllThreads',   'closeAllThreads() is callable' );

  //$result = $obj->closeAllThreads ( $sAppUid);
  //$t->isa_ok( $result,      'NULL',   'call to method closeAllThreads ');
  $t->todo( "call to method closeAllThreads using $sAppUid ");


  //checking method 'newAppThread'
  $t->is ( $aMethods[24],      'newAppThread',   'newAppThread() is callable' );

  //$result = $obj->newAppThread ( $sAppUid, $iNewDelIndex, $iAppParent);
  //$t->isa_ok( $result,      'NULL',   'call to method newAppThread ');
  $t->todo( "call to method newAppThread using $sAppUid, $iNewDelIndex, $iAppParent ");


  //checking method 'closeAllDelegations'
  $t->is ( $aMethods[25],      'closeAllDelegations',   'closeAllDelegations() is callable' );

  //$result = $obj->closeAllDelegations ( $sAppUid);
  //$t->isa_ok( $result,      'NULL',   'call to method closeAllDelegations ');
  $t->todo( "call to method closeAllDelegations using $sAppUid ");


  //checking method 'CloseCurrentDelegation'
  $t->is ( $aMethods[26],      'CloseCurrentDelegation',   'CloseCurrentDelegation() is callable' );

  //$result = $obj->CloseCurrentDelegation ( $sAppUid, $iDelIndex);
  //$t->isa_ok( $result,      'NULL',   'call to method CloseCurrentDelegation ');
  $t->todo( "call to method CloseCurrentDelegation using $sAppUid, $iDelIndex ");


  //checking method 'ReactivateCurrentDelegation'
  $t->is ( $aMethods[27],      'ReactivateCurrentDelegation',   'ReactivateCurrentDelegation() is callable' );

  //$result = $obj->ReactivateCurrentDelegation ( $sAppUid, $iDelegation);
  //$t->isa_ok( $result,      'NULL',   'call to method ReactivateCurrentDelegation ');
  $t->todo( "call to method ReactivateCurrentDelegation using $sAppUid, $iDelegation ");


  //checking method 'startCase'
  $t->is ( $aMethods[28],      'startCase',   'startCase() is callable' );

  //$result = $obj->startCase ( $sTasUid, $sUsrUid);
  //$t->isa_ok( $result,      'NULL',   'call to method startCase ');
  $t->todo( "call to method startCase using $sTasUid, $sUsrUid ");


  //checking method 'getNextStep'
  $t->is ( $aMethods[29],      'getNextStep',   'getNextStep() is callable' );

  //$result = $obj->getNextStep ( $sProUid, $sAppUid, $iDelIndex, $iPosition);
  //$t->isa_ok( $result,      'NULL',   'call to method getNextStep ');
  $t->todo( "call to method getNextStep using $sProUid, $sAppUid, $iDelIndex, $iPosition ");


  //checking method 'getPreviousStep'
  $t->is ( $aMethods[30],      'getPreviousStep',   'getPreviousStep() is callable' );

  //$result = $obj->getPreviousStep ( $sProUid, $sAppUid, $iDelIndex, $iPosition);
  //$t->isa_ok( $result,      'NULL',   'call to method getPreviousStep ');
  $t->todo( "call to method getPreviousStep using $sProUid, $sAppUid, $iDelIndex, $iPosition ");


  //checking method 'getNextSupervisorStep'
  $t->is ( $aMethods[31],      'getNextSupervisorStep',   'getNextSupervisorStep() is callable' );

  //$result = $obj->getNextSupervisorStep ( $sProcessUID, $iPosition, $sType);
  //$t->isa_ok( $result,      'NULL',   'call to method getNextSupervisorStep ');
  $t->todo( "call to method getNextSupervisorStep using $sProcessUID, $iPosition, $sType ");


  //checking method 'getPreviousSupervisorStep'
  $t->is ( $aMethods[32],      'getPreviousSupervisorStep',   'getPreviousSupervisorStep() is callable' );

  //$result = $obj->getPreviousSupervisorStep ( $sProcessUID, $iPosition, $sType);
  //$t->isa_ok( $result,      'NULL',   'call to method getPreviousSupervisorStep ');
  $t->todo( "call to method getPreviousSupervisorStep using $sProcessUID, $iPosition, $sType ");


  //checking method 'getTransferHistoryCriteria'
  $t->is ( $aMethods[33],      'getTransferHistoryCriteria',   'getTransferHistoryCriteria() is callable' );

  //$result = $obj->getTransferHistoryCriteria ( $sAppUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getTransferHistoryCriteria ');
  $t->todo( "call to method getTransferHistoryCriteria using $sAppUid ");

  //checking method 'prepareCriteriaForToDo'
  $t->is ( $aMethods[34],      'prepareCriteriaForToDo',   'prepareCriteriaForToDo() is callable' );

  //$result = $obj->getConditionCasesList ( $sTypeList, $sUIDUserLogged);
  //$t->isa_ok( $result,      'NULL',   'call to method getConditionCasesList ');
  $t->todo( "call to method prepareCriteriaForToDo using $sTypeList, $sUIDUserLogged ");


  //checking method 'getConditionCasesList'
  $t->is ( $aMethods[35],      'getConditionCasesList',   'getConditionCasesList() is callable' );

  //$result = $obj->getConditionCasesList ( $sTypeList, $sUIDUserLogged);
  //$t->isa_ok( $result,      'NULL',   'call to method getConditionCasesList ');
  $t->todo( "call to method getConditionCasesList using $sTypeList, $sUIDUserLogged ");


  //checking method 'ThrowUnpauseDaemon'
  $t->is ( $aMethods[36],      'ThrowUnpauseDaemon',   'ThrowUnpauseDaemon() is callable' );

  //$result = $obj->ThrowUnpauseDaemon ( );
  //$t->isa_ok( $result,      'NULL',   'call to method ThrowUnpauseDaemon ');
  $t->todo( "call to method ThrowUnpauseDaemon using  ");


  //checking method 'getApplicationUIDByNumber'
  $t->is ( $aMethods[37],      'getApplicationUIDByNumber',   'getApplicationUIDByNumber() is callable' );

  //$result = $obj->getApplicationUIDByNumber ( $iApplicationNumber);
  //$t->isa_ok( $result,      'NULL',   'call to method getApplicationUIDByNumber ');
  $t->todo( "call to method getApplicationUIDByNumber using $iApplicationNumber ");


  //checking method 'getCurrentDelegation'
  $t->is ( $aMethods[38],      'getCurrentDelegation',   'getCurrentDelegation() is callable' );

  //$result = $obj->getCurrentDelegation ( $sApplicationUID, $sUserUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getCurrentDelegation ');
  $t->todo( "call to method getCurrentDelegation using $sApplicationUID, $sUserUID ");


  //checking method 'loadTriggers'
  $t->is ( $aMethods[39],      'loadTriggers',   'loadTriggers() is callable' );

  //$result = $obj->loadTriggers ( $sTasUid, $sStepType, $sStepUidObj, $sTriggerType);
  //$t->isa_ok( $result,      'NULL',   'call to method loadTriggers ');
  $t->todo( "call to method loadTriggers using $sTasUid, $sStepType, $sStepUidObj, $sTriggerType ");


  //checking method 'executeTriggers'
  $t->is ( $aMethods[40],      'executeTriggers',   'executeTriggers() is callable' );

  //$result = $obj->executeTriggers ( $sTasUid, $sStepType, $sStepUidObj, $sTriggerType, $aFields);
  //$t->isa_ok( $result,      'NULL',   'call to method executeTriggers ');
  $t->todo( "call to method executeTriggers using $sTasUid, $sStepType, $sStepUidObj, $sTriggerType, $aFields ");


  //checking method 'getTriggerNames'
  $t->is ( $aMethods[41],      'getTriggerNames',   'getTriggerNames() is callable' );

  //$result = $obj->getTriggerNames ( $triggers);
  //$t->isa_ok( $result,      'NULL',   'call to method getTriggerNames ');
  $t->todo( "call to method getTriggerNames using $triggers ");


  //checking method 'getInputDocumentsCriteria'
  $t->is ( $aMethods[42],      'getInputDocumentsCriteria',   'getInputDocumentsCriteria() is callable' );

  //$result = $obj->getInputDocumentsCriteria ( $sApplicationUID, $iDelegation, $sDocumentUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getInputDocumentsCriteria ');
  $t->todo( "call to method getInputDocumentsCriteria using $sApplicationUID, $iDelegation, $sDocumentUID ");

  //checking method 'getInputDocumentsCriteriaToRevise'
  $t->is ( $aMethods[43],      'getInputDocumentsCriteriaToRevise',   'getInputDocumentsCriteriaToRevise() is callable' );

  //$result = $obj->getInputDocumentsCriteriaToRevise ( $sApplicationUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getInputDocumentsCriteriaToRevise ');
  $t->todo( "call to method getInputDocumentsCriteriaToRevise using $sApplicationUID ");


  //checking method 'getOutputDocumentsCriteriaToRevise'
  $t->is ( $aMethods[44],      'getOutputDocumentsCriteriaToRevise',   'getOutputDocumentsCriteriaToRevise() is callable' );

  //$result = $obj->getOutputDocumentsCriteriaToRevise ( $sApplicationUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getOutputDocumentsCriteriaToRevise ');
  $t->todo( "call to method getOutputDocumentsCriteriaToRevise using $sApplicationUID ");


  //checking method 'getCriteriaProcessCases'
  $t->is ( $aMethods[45],      'getCriteriaProcessCases',   'getCriteriaProcessCases() is callable' );

  //$result = $obj->getCriteriaProcessCases ( $status, $PRO_UID);
  //$t->isa_ok( $result,      'NULL',   'call to method getCriteriaProcessCases ');
  $t->todo( "call to method getCriteriaProcessCases using $status, $PRO_UID ");


  //checking method 'pauseCase'
  $t->is ( $aMethods[46],      'pauseCase',   'pauseCase() is callable' );

  //$result = $obj->pauseCase ( $sApplicationUID, $iDelegation, $sUserUID, $sUnpauseDate);
  //$t->isa_ok( $result,      'NULL',   'call to method pauseCase ');
  $t->todo( "call to method pauseCase using $sApplicationUID, $iDelegation, $sUserUID, $sUnpauseDate ");


  //checking method 'unpauseCase'
  $t->is ( $aMethods[47],      'unpauseCase',   'unpauseCase() is callable' );

  //$result = $obj->unpauseCase ( $sApplicationUID, $iDelegation, $sUserUID);
  //$t->isa_ok( $result,      'NULL',   'call to method unpauseCase ');
  $t->todo( "call to method unpauseCase using $sApplicationUID, $iDelegation, $sUserUID ");


  //checking method 'cancelCase'
  $t->is ( $aMethods[48],      'cancelCase',   'cancelCase() is callable' );

  //$result = $obj->cancelCase ( $sApplicationUID, $iIndex, $user_logged);
  //$t->isa_ok( $result,      'NULL',   'call to method cancelCase ');
  $t->todo( "call to method cancelCase using $sApplicationUID, $iIndex, $user_logged ");


  //checking method 'reactivateCase'
  $t->is ( $aMethods[49],      'reactivateCase',   'reactivateCase() is callable' );

  //$result = $obj->reactivateCase ( $sApplicationUID, $iIndex, $user_logged);
  //$t->isa_ok( $result,      'NULL',   'call to method reactivateCase ');
  $t->todo( "call to method reactivateCase using $sApplicationUID, $iIndex, $user_logged ");


  //checking method 'reassignCase'
  $t->is ( $aMethods[50],      'reassignCase',   'reassignCase() is callable' );

  //$result = $obj->reassignCase ( $sApplicationUID, $iDelegation, $sUserUID, $newUserUID, $sType);
  //$t->isa_ok( $result,      'NULL',   'call to method reassignCase ');
  $t->todo( "call to method reassignCase using $sApplicationUID, $iDelegation, $sUserUID, $newUserUID, $sType ");


  //checking method 'getAllDynaformsStepsToRevise'
  $t->is ( $aMethods[51],      'getAllDynaformsStepsToRevise',   'getAllDynaformsStepsToRevise() is callable' );

  //$result = $obj->getAllDynaformsStepsToRevise ( $APP_UID);
  //$t->isa_ok( $result,      'NULL',   'call to method getAllDynaformsStepsToRevise ');
  $t->todo( "call to method getAllDynaformsStepsToRevise using $APP_UID ");


  //checking method 'getAllInputsStepsToRevise'
  $t->is ( $aMethods[52],      'getAllInputsStepsToRevise',   'getAllInputsStepsToRevise() is callable' );

  //$result = $obj->getAllInputsStepsToRevise ( $APP_UID);
  //$t->isa_ok( $result,      'NULL',   'call to method getAllInputsStepsToRevise ');
  $t->todo( "call to method getAllInputsStepsToRevise using $APP_UID ");


  //checking method 'getAllUploadedDocumentsCriteria'
  $t->is ( $aMethods[53],      'getAllUploadedDocumentsCriteria',   'getAllUploadedDocumentsCriteria() is callable' );

  //$result = $obj->getAllUploadedDocumentsCriteria ( $sProcessUID, $sApplicationUID, $sTasKUID, $sUserUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getAllUploadedDocumentsCriteria ');
  $t->todo( "call to method getAllUploadedDocumentsCriteria using $sProcessUID, $sApplicationUID, $sTasKUID, $sUserUID ");


  //checking method 'getAllGeneratedDocumentsCriteria'
  $t->is ( $aMethods[54],      'getAllGeneratedDocumentsCriteria',   'getAllGeneratedDocumentsCriteria() is callable' );

  //$result = $obj->getAllGeneratedDocumentsCriteria ( $sProcessUID, $sApplicationUID, $sTasKUID, $sUserUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getAllGeneratedDocumentsCriteria ');
  $t->todo( "call to method getAllGeneratedDocumentsCriteria using $sProcessUID, $sApplicationUID, $sTasKUID, $sUserUID ");


  //checking method 'getallDynaformsCriteria'
  $t->is ( $aMethods[55],      'getallDynaformsCriteria',   'getallDynaformsCriteria() is callable' );

  //$result = $obj->getallDynaformsCriteria ( $sProcessUID, $sApplicationUID, $sTasKUID, $sUserUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getallDynaformsCriteria ');
  $t->todo( "call to method getallDynaformsCriteria using $sProcessUID, $sApplicationUID, $sTasKUID, $sUserUID ");


  //checking method 'sendNotifications'
  $t->is ( $aMethods[56],      'sendNotifications',   'sendNotifications() is callable' );

  //$result = $obj->sendNotifications ( $sCurrentTask, $aTasks, $aFields, $sApplicationUID, $iDelegation, $sFrom);
  //$t->isa_ok( $result,      'NULL',   'call to method sendNotifications ');
  $t->todo( "call to method sendNotifications using $sCurrentTask, $aTasks, $aFields, $sApplicationUID, $iDelegation, $sFrom ");


  //checking method 'getAllObjects'
  $t->is ( $aMethods[57],      'getAllObjects',   'getAllObjects() is callable' );

  //$result = $obj->getAllObjects ( $PRO_UID, $APP_UID, $TAS_UID, $USR_UID);
  //$t->isa_ok( $result,      'NULL',   'call to method getAllObjects ');
  $t->todo( "call to method getAllObjects using $PRO_UID, $APP_UID, $TAS_UID, $USR_UID ");


  //checking method 'getAllObjectsFrom'
  $t->is ( $aMethods[58],      'getAllObjectsFrom',   'getAllObjectsFrom() is callable' );

  //$result = $obj->getAllObjectsFrom ( $PRO_UID, $APP_UID, $TAS_UID, $USR_UID, $ACTION);
  //$t->isa_ok( $result,      'NULL',   'call to method getAllObjectsFrom ');
  $t->todo( "call to method getAllObjectsFrom using $PRO_UID, $APP_UID, $TAS_UID, $USR_UID, $ACTION ");


  //checking method 'verifyCaseTracker'
  $t->is ( $aMethods[59],      'verifyCaseTracker',   'verifyCaseTracker() is callable' );

  //$result = $obj->verifyCaseTracker ( $case, $pin);
  //$t->isa_ok( $result,      'NULL',   'call to method verifyCaseTracker ');
  $t->todo( "call to method verifyCaseTracker using $case, $pin ");


  //checking method 'Permisos'
  $t->is ( $aMethods[60],      'Permisos',   'Permisos() is callable' );

  //$result = $obj->caseTrackerPermissions ( $PRO_UID);
  //$t->isa_ok( $result,      'NULL',   'call to method Permisos ');
  $t->todo( "call to method Permisos using $PRO_UID ");


  //checking method 'verifyTable'
  $t->is ( $aMethods[61],      'verifyTable',   'verifyTable() is callable' );

  //$result = $obj->verifyTable ( );
  //$t->isa_ok( $result,      'NULL',   'call to method verifyTable ');
  $t->todo( "call to method verifyTable using  ");


  //checking method 'getAllUploadedDocumentsCriteriaTracker'
  $t->is ( $aMethods[62],      'getAllUploadedDocumentsCriteriaTracker',   'getAllUploadedDocumentsCriteriaTracker() is callable' );

  //$result = $obj->getAllUploadedDocumentsCriteriaTracker ( $sProcessUID, $sApplicationUID, $sDocUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getAllUploadedDocumentsCriteriaTracker ');
  $t->todo( "call to method getAllUploadedDocumentsCriteriaTracker using $sProcessUID, $sApplicationUID, $sDocUID ");


  //checking method 'getAllGeneratedDocumentsCriteriaTracker'
  $t->is ( $aMethods[63],      'getAllGeneratedDocumentsCriteriaTracker',   'getAllGeneratedDocumentsCriteriaTracker() is callable' );

  //$result = $obj->getAllGeneratedDocumentsCriteriaTracker ( $sProcessUID, $sApplicationUID, $sDocUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getAllGeneratedDocumentsCriteriaTracker ');
  $t->todo( "call to method getAllGeneratedDocumentsCriteriaTracker using $sProcessUID, $sApplicationUID, $sDocUID ");


  //checking method 'getHistoryMessagesTracker'
  $t->is ( $aMethods[64],      'getHistoryMessagesTracker',   'getHistoryMessagesTracker() is callable' );

  //$result = $obj->getHistoryMessagesTracker ( $sApplicationUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getHistoryMessagesTracker ');
  $t->todo( "call to method getHistoryMessagesTracker using $sApplicationUID ");


  //checking method 'getHistoryMessagesTrackerView'
  $t->is ( $aMethods[65],      'getHistoryMessagesTrackerView',   'getHistoryMessagesTrackerView() is callable' );

  //$result = $obj->getHistoryMessagesTrackerView ( $sApplicationUID, $Msg_UID);
  //$t->isa_ok( $result,      'NULL',   'call to method getHistoryMessagesTrackerView ');
  $t->todo( "call to method getHistoryMessagesTrackerView using $sApplicationUID, $Msg_UID ");


  //checking method 'getAllObjectsFromProcess'
  $t->is ( $aMethods[66],      'getAllObjectsFromProcess',   'getAllObjectsFromProcess() is callable' );

  //$result = $obj->getAllObjectsFromProcess ( $PRO_UID, $OBJ_TYPE);
  //$t->isa_ok( $result,      'NULL',   'call to method getAllObjectsFromProcess ');
  $t->todo( "call to method getAllObjectsFromProcess using $PRO_UID, $OBJ_TYPE ");


  //checking method 'executeTriggersAfterExternal'
  $t->is ( $aMethods[67],      'executeTriggersAfterExternal',   'executeTriggersAfterExternal() is callable' );

  //$result = $obj->executeTriggersAfterExternal ( $sProcess, $sTask, $sApplication, $iIndex, $iStepPosition, $aNewData);
  //$t->isa_ok( $result,      'NULL',   'call to method executeTriggersAfterExternal ');
  $t->todo( "call to method executeTriggersAfterExternal using $sProcess, $sTask, $sApplication, $iIndex, $iStepPosition, $aNewData ");


  //checking method 'thisIsTheCurrentUser'
  $t->is ( $aMethods[68],      'thisIsTheCurrentUser',   'thisIsTheCurrentUser() is callable' );

  //$result = $obj->thisIsTheCurrentUser ( $sApplicationUID, $iIndex, $sUserUID, $sAction, $sURL);
  //$t->isa_ok( $result,      'NULL',   'call to method thisIsTheCurrentUser ');
  $t->todo( "call to method thisIsTheCurrentUser using $sApplicationUID, $iIndex, $sUserUID, $sAction, $sURL ");


  //checking method 'getCriteriaUsersCases'
  $t->is ( $aMethods[69],      'getCriteriaUsersCases',   'getCriteriaUsersCases() is callable' );

  //$result = $obj->getCriteriaUsersCases ( $status, $USR_UID);
  //$t->isa_ok( $result,      'NULL',   'call to method getCriteriaUsersCases ');
  $t->todo( "call to method getCriteriaUsersCases using $status, $USR_UID ");


  //checking method 'getAdvancedSearch'
  $t->is ( $aMethods[70],      'getAdvancedSearch',   'getAdvancedSearch() is callable' );

  //$result = $obj->getAdvancedSearch ( $sCase, $sProcess, $sTask, $sCurrentUser, $sSentby, $sLastModFrom, $sLastModTo, $sStatus, $permisse, $userlogged, $aSupervisor);
  //$t->isa_ok( $result,      'NULL',   'call to method getAdvancedSearch ');

  */
  $t->todo( "call to method getAdvancedSearch using $sCase, $sProcess, $sTask, $sCurrentUser, $sSentby, $sLastModFrom, $sLastModTo, $sStatus, $permisse, $userlogged, $aSupervisor ");
  $t->todo (  'review all pendings methods in this class');
