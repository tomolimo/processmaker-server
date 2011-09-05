<?php
/**
 * classProcessesTest.php
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
  $unitFilename = $_SERVER['PWD'] . '/test/bootstrap/unit.php' ;
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

  G::LoadClass ( 'processes');


  $obj = new Processes ($dbc); 
  $t   = new lime_test( 231, new lime_output_color() );

  $className = Processes;
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

  //To change the case only the first letter of each word, TIA
  $className = ucwords($className);
  $t->diag("class $className" );

  $t->isa_ok( $obj  , $className,  "class $className created");

  //$t->is( count($methods) , 116,  "class $className have " . 116 . ' methods.' );
  $t->is( count($methods) , 120,  "class $className have " . 120 . ' methods.' );

   //checking method 'changeStatus'
  $t->can_ok( $obj,      'changeStatus',   'changeStatus() is callable' );

  //$result = $obj->changeStatus ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method changeStatus ');
  $t->todo( "call to method changeStatus using $sProUid ");


  //checking method 'changeProcessParent'
  $t->can_ok( $obj,      'changeProcessParent',   'changeProcessParent() is callable' );

  //$result = $obj->changeProcessParent ( $sProUid, $sParentUid);
  //$t->isa_ok( $result,      'NULL',   'call to method changeProcessParent ');
  $t->todo( "call to method changeProcessParent using $sProUid, $sParentUid ");


  //checking method 'processExists'
  $t->can_ok( $obj,      'processExists',   'processExists() is callable' );

  //$result = $obj->processExists ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method processExists ');
  $t->todo( "call to method processExists using $sProUid ");


  //checking method 'getUnusedProcessGUID'
  $t->can_ok( $obj,      'getUnusedProcessGUID',   'getUnusedProcessGUID() is callable' );

  //$result = $obj->getUnusedProcessGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedProcessGUID ');
  $t->todo( "call to method getUnusedProcessGUID using  ");


  //checking method 'taskExists'
  $t->can_ok( $obj,      'taskExists',   'taskExists() is callable' );

  //$result = $obj->taskExists ( $sTasUid);
  //$t->isa_ok( $result,      'NULL',   'call to method taskExists ');
  $t->todo( "call to method taskExists using $sTasUid ");


  //checking method 'getUnusedTaskGUID'
  $t->can_ok( $obj,      'getUnusedTaskGUID',   'getUnusedTaskGUID() is callable' );

  //$result = $obj->getUnusedTaskGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedTaskGUID ');
  $t->todo( "call to method getUnusedTaskGUID using  ");


  //checking method 'dynaformExists'
  $t->can_ok( $obj,      'dynaformExists',   'dynaformExists() is callable' );

  //$result = $obj->dynaformExists ( $sDynUid);
  //$t->isa_ok( $result,      'NULL',   'call to method dynaformExists ');
  $t->todo( "call to method dynaformExists using $sDynUid ");


  //checking method 'inputExists'
  $t->can_ok( $obj,      'inputExists',   'inputExists() is callable' );

  //$result = $obj->inputExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method inputExists ');
  $t->todo( "call to method inputExists using $sUid ");


  //checking method 'outputExists'
  $t->can_ok( $obj,      'outputExists',   'outputExists() is callable' );

  //$result = $obj->outputExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method outputExists ');
  $t->todo( "call to method outputExists using $sUid ");


  //checking method 'triggerExists'
  $t->can_ok( $obj,      'triggerExists',   'triggerExists() is callable' );

  //$result = $obj->triggerExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method triggerExists ');
  $t->todo( "call to method triggerExists using $sUid ");


  //checking method 'SubProcessExists'
  $t->can_ok( $obj,      'SubProcessExists',   'SubProcessExists() is callable' );

  //$result = $obj->SubProcessExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method SubProcessExists ');
  $t->todo( "call to method SubProcessExists using $sUid ");


  //checking method 'caseTrackerObjectExists'
  $t->can_ok( $obj,      'caseTrackerObjectExists',   'caseTrackerObjectExists() is callable' );

  //$result = $obj->caseTrackerObjectExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method caseTrackerObjectExists ');
  $t->todo( "call to method caseTrackerObjectExists using $sUid ");


  //checking method 'caseTrackerExists'
  $t->can_ok( $obj,      'caseTrackerExists',   'caseTrackerExists() is callable' );

  //$result = $obj->caseTrackerExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method caseTrackerExists ');
  $t->todo( "call to method caseTrackerExists using $sUid ");


  //checking method 'dbConnectionExists'
  $t->can_ok( $obj,      'dbConnectionExists',   'dbConnectionExists() is callable' );

  //$result = $obj->dbConnectionExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method dbConnectionExists ');
  $t->todo( "call to method dbConnectionExists using $sUid ");


  //checking method 'objectPermissionExists'
  $t->can_ok( $obj,      'objectPermissionExists',   'objectPermissionExists() is callable' );

  //$result = $obj->objectPermissionExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method objectPermissionExists ');
  $t->todo( "call to method objectPermissionExists using $sUid ");


  //checking method 'routeExists'
  $t->can_ok( $obj,      'routeExists',   'routeExists() is callable' );

  //$result = $obj->routeExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method routeExists ');
  $t->todo( "call to method routeExists using $sUid ");


  //checking method 'stageExists'
  $t->can_ok( $obj,      'stageExists',   'stageExists() is callable' );

  //$result = $obj->stageExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method stageExists ');
  $t->todo( "call to method stageExists using $sUid ");


  //checking method 'slExists'
  $t->can_ok( $obj,      'slExists',   'slExists() is callable' );

  //$result = $obj->slExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method slExists ');
  $t->todo( "call to method slExists using $sUid ");


  //checking method 'reportTableExists'
  $t->can_ok( $obj,      'reportTableExists',   'reportTableExists() is callable' );

  //$result = $obj->reportTableExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method reportTableExists ');
  $t->todo( "call to method reportTableExists using $sUid ");


  //checking method 'reportVarExists'
  $t->can_ok( $obj,      'reportVarExists',   'reportVarExists() is callable' );

  //$result = $obj->reportVarExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method reportVarExists ');
  $t->todo( "call to method reportVarExists using $sUid ");


  //checking method 'getUnusedInputGUID'
  $t->can_ok( $obj,      'getUnusedInputGUID',   'getUnusedInputGUID() is callable' );

  //$result = $obj->getUnusedInputGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedInputGUID ');
  $t->todo( "call to method getUnusedInputGUID using  ");


  //checking method 'getUnusedOutputGUID'
  $t->can_ok( $obj,      'getUnusedOutputGUID',   'getUnusedOutputGUID() is callable' );

  //$result = $obj->getUnusedOutputGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedOutputGUID ');
  $t->todo( "call to method getUnusedOutputGUID using  ");


  //checking method 'getUnusedTriggerGUID'
  $t->can_ok( $obj,      'getUnusedTriggerGUID',   'getUnusedTriggerGUID() is callable' );

  //$result = $obj->getUnusedTriggerGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedTriggerGUID ');
  $t->todo( "call to method getUnusedTriggerGUID using  ");


  //checking method 'getUnusedSubProcessGUID'
  $t->can_ok( $obj,      'getUnusedSubProcessGUID',   'getUnusedSubProcessGUID() is callable' );

  //$result = $obj->getUnusedSubProcessGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedSubProcessGUID ');
  $t->todo( "call to method getUnusedSubProcessGUID using  ");


  //checking method 'getUnusedCaseTrackerObjectGUID'
  $t->can_ok( $obj,      'getUnusedCaseTrackerObjectGUID',   'getUnusedCaseTrackerObjectGUID() is callable' );

  //$result = $obj->getUnusedCaseTrackerObjectGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedCaseTrackerObjectGUID ');
  $t->todo( "call to method getUnusedCaseTrackerObjectGUID using  ");


  //checking method 'getUnusedDBSourceGUID'
  $t->can_ok( $obj,      'getUnusedDBSourceGUID',   'getUnusedDBSourceGUID() is callable' );

  //$result = $obj->getUnusedDBSourceGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedDBSourceGUID ');
  $t->todo( "call to method getUnusedDBSourceGUID using  ");


  //checking method 'getUnusedObjectPermissionGUID'
  $t->can_ok( $obj,      'getUnusedObjectPermissionGUID',   'getUnusedObjectPermissionGUID() is callable' );

  //$result = $obj->getUnusedObjectPermissionGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedObjectPermissionGUID ');
  $t->todo( "call to method getUnusedObjectPermissionGUID using  ");


  //checking method 'getUnusedRouteGUID'
  $t->can_ok( $obj,      'getUnusedRouteGUID',   'getUnusedRouteGUID() is callable' );

  //$result = $obj->getUnusedRouteGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedRouteGUID ');
  $t->todo( "call to method getUnusedRouteGUID using  ");


  //checking method 'getUnusedStageGUID'
  $t->can_ok( $obj,      'getUnusedStageGUID',   'getUnusedStageGUID() is callable' );

  //$result = $obj->getUnusedStageGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedStageGUID ');
  $t->todo( "call to method getUnusedStageGUID using  ");


  //checking method 'getUnusedSLGUID'
  $t->can_ok( $obj,      'getUnusedSLGUID',   'getUnusedSLGUID() is callable' );

  //$result = $obj->getUnusedSLGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedSLGUID ');
  $t->todo( "call to method getUnusedSLGUID using  ");


  //checking method 'getUnusedRTGUID'
  $t->can_ok( $obj,      'getUnusedRTGUID',   'getUnusedRTGUID() is callable' );

  //$result = $obj->getUnusedRTGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedRTGUID ');
  $t->todo( "call to method getUnusedRTGUID using  ");


  //checking method 'getUnusedRTVGUID'
  $t->can_ok( $obj,      'getUnusedRTVGUID',   'getUnusedRTVGUID() is callable' );

  //$result = $obj->getUnusedRTVGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedRTVGUID ');
  $t->todo( "call to method getUnusedRTVGUID using  ");


  //checking method 'stepExists'
  $t->can_ok( $obj,      'stepExists',   'stepExists() is callable' );

  //$result = $obj->stepExists ( $sUid);
  //$t->isa_ok( $result,      'NULL',   'call to method stepExists ');
  $t->todo( "call to method stepExists using $sUid ");


  //checking method 'getUnusedStepGUID'
  $t->can_ok( $obj,      'getUnusedStepGUID',   'getUnusedStepGUID() is callable' );

  //$result = $obj->getUnusedStepGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedStepGUID ');
  $t->todo( "call to method getUnusedStepGUID using  ");


  //checking method 'getUnusedDynaformGUID'
  $t->can_ok( $obj,      'getUnusedDynaformGUID',   'getUnusedDynaformGUID() is callable' );

  //$result = $obj->getUnusedDynaformGUID ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getUnusedDynaformGUID ');
  $t->todo( "call to method getUnusedDynaformGUID using  ");


  //checking method 'setProcessGUID'
  $t->can_ok( $obj,      'setProcessGUID',   'setProcessGUID() is callable' );

  //$result = $obj->setProcessGUID ( $oData, $sNewProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method setProcessGUID ');
  $t->todo( "call to method setProcessGUID using $oData, $sNewProUid ");


  //checking method 'setProcessParent'
  $t->can_ok( $obj,      'setProcessParent',   'setProcessParent() is callable' );

  //$result = $obj->setProcessParent ( $oData, $sParentUid);
  //$t->isa_ok( $result,      'NULL',   'call to method setProcessParent ');
  $t->todo( "call to method setProcessParent using $oData, $sParentUid ");


  //checking method 'renewAllTaskGuid'
  $t->can_ok( $obj,      'renewAllTaskGuid',   'renewAllTaskGuid() is callable' );

  //$result = $obj->renewAllTaskGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllTaskGuid ');
  $t->todo( "call to method renewAllTaskGuid using $oData ");


  //checking method 'renewAllDynaformGuid'
  $t->can_ok( $obj,      'renewAllDynaformGuid',   'renewAllDynaformGuid() is callable' );

  //$result = $obj->renewAllDynaformGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllDynaformGuid ');
  $t->todo( "call to method renewAllDynaformGuid using $oData ");


  //checking method 'getProcessRow'
  $t->can_ok( $obj,      'getProcessRow',   'getProcessRow() is callable' );

  //$result = $obj->getProcessRow ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getProcessRow ');
  $t->todo( "call to method getProcessRow using $sProUid ");


  //checking method 'createProcessRow'
  $t->can_ok( $obj,      'createProcessRow',   'createProcessRow() is callable' );

  //$result = $obj->createProcessRow ( $row);
  //$t->isa_ok( $result,      'NULL',   'call to method createProcessRow ');
  $t->todo( "call to method createProcessRow using $row ");


  //checking method 'updateProcessRow'
  $t->can_ok( $obj,      'updateProcessRow',   'updateProcessRow() is callable' );

  //$result = $obj->updateProcessRow ( $row);
  //$t->isa_ok( $result,      'NULL',   'call to method updateProcessRow ');
  $t->todo( "call to method updateProcessRow using $row ");


  //checking method 'getSubProcessRow'
  $t->can_ok( $obj,      'getSubProcessRow',   'getSubProcessRow() is callable' );

  //$result = $obj->getSubProcessRow ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getSubProcessRow ');
  $t->todo( "call to method getSubProcessRow using $sProUid ");


  //checking method 'getCaseTrackerRow'
  $t->can_ok( $obj,      'getCaseTrackerRow',   'getCaseTrackerRow() is callable' );

  //$result = $obj->getCaseTrackerRow ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getCaseTrackerRow ');
  $t->todo( "call to method getCaseTrackerRow using $sProUid ");


  //checking method 'getCaseTrackerObjectRow'
  $t->can_ok( $obj,      'getCaseTrackerObjectRow',   'getCaseTrackerObjectRow() is callable' );

  //$result = $obj->getCaseTrackerObjectRow ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getCaseTrackerObjectRow ');
  $t->todo( "call to method getCaseTrackerObjectRow using $sProUid ");


  //checking method 'getStageRow'
  $t->can_ok( $obj,      'getStageRow',   'getStageRow() is callable' );

  //$result = $obj->getStageRow ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getStageRow ');
  $t->todo( "call to method getStageRow using $sProUid ");


  //checking method 'getAllLanes'
  $t->can_ok( $obj,      'getAllLanes',   'getAllLanes() is callable' );

  //$result = $obj->getAllLanes ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getAllLanes ');
  $t->todo( "call to method getAllLanes using $sProUid ");


  //checking method 'getTaskRows'
  $t->can_ok( $obj,      'getTaskRows',   'getTaskRows() is callable' );

  //$result = $obj->getTaskRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getTaskRows ');
  $t->todo( "call to method getTaskRows using $sProUid ");


  //checking method 'createTaskRows'
  $t->can_ok( $obj,      'createTaskRows',   'createTaskRows() is callable' );

  //$result = $obj->createTaskRows ( $aTasks);
  //$t->isa_ok( $result,      'NULL',   'call to method createTaskRows ');
  $t->todo( "call to method createTaskRows using $aTasks ");


  //checking method 'updateTaskRows'
  $t->can_ok( $obj,      'updateTaskRows',   'updateTaskRows() is callable' );

  //$result = $obj->updateTaskRows ( $aTasks);
  //$t->isa_ok( $result,      'NULL',   'call to method updateTaskRows ');
  $t->todo( "call to method updateTaskRows using $aTasks ");


  //checking method 'getRouteRows'
  $t->can_ok( $obj,      'getRouteRows',   'getRouteRows() is callable' );

  //$result = $obj->getRouteRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getRouteRows ');
  $t->todo( "call to method getRouteRows using $sProUid ");


  //checking method 'createRouteRows'
  $t->can_ok( $obj,      'createRouteRows',   'createRouteRows() is callable' );

  //$result = $obj->createRouteRows ( $aRoutes);
  //$t->isa_ok( $result,      'NULL',   'call to method createRouteRows ');
  $t->todo( "call to method createRouteRows using $aRoutes ");


  //checking method 'updateRouteRows'
  $t->can_ok( $obj,      'updateRouteRows',   'updateRouteRows() is callable' );

  //$result = $obj->updateRouteRows ( $aRoutes);
  //$t->isa_ok( $result,      'NULL',   'call to method updateRouteRows ');
  $t->todo( "call to method updateRouteRows using $aRoutes ");


  //checking method 'getLaneRows'
  $t->can_ok( $obj,      'getLaneRows',   'getLaneRows() is callable' );

  //$result = $obj->getLaneRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getLaneRows ');
  $t->todo( "call to method getLaneRows using $sProUid ");


  //checking method 'createLaneRows'
  $t->can_ok( $obj,      'createLaneRows',   'createLaneRows() is callable' );

  //$result = $obj->createLaneRows ( $aLanes);
  //$t->isa_ok( $result,      'NULL',   'call to method createLaneRows ');
  $t->todo( "call to method createLaneRows using $aLanes ");


  //checking method 'createSubProcessRows'
  $t->can_ok( $obj,      'createSubProcessRows',   'createSubProcessRows() is callable' );

  //$result = $obj->createSubProcessRows ( $SubProcess);
  //$t->isa_ok( $result,      'NULL',   'call to method createSubProcessRows ');
  $t->todo( "call to method createSubProcessRows using $SubProcess ");


  //checking method 'createCaseTrackerRows'
  $t->can_ok( $obj,      'createCaseTrackerRows',   'createCaseTrackerRows() is callable' );

  //$result = $obj->createCaseTrackerRows ( $CaseTracker);
  //$t->isa_ok( $result,      'NULL',   'call to method createCaseTrackerRows ');
  $t->todo( "call to method createCaseTrackerRows using $CaseTracker ");


  //checking method 'createCaseTrackerObjectRows'
  $t->can_ok( $obj,      'createCaseTrackerObjectRows',   'createCaseTrackerObjectRows() is callable' );

  //$result = $obj->createCaseTrackerObjectRows ( $CaseTrackerObject);
  //$t->isa_ok( $result,      'NULL',   'call to method createCaseTrackerObjectRows ');
  $t->todo( "call to method createCaseTrackerObjectRows using $CaseTrackerObject ");


  //checking method 'createObjectPermissionsRows'
  $t->can_ok( $obj,      'createObjectPermissionsRows',   'createObjectPermissionsRows() is callable' );

  //$result = $obj->createObjectPermissionsRows ( $ObjectPermissions);
  //$t->isa_ok( $result,      'NULL',   'call to method createObjectPermissionsRows ');
  $t->todo( "call to method createObjectPermissionsRows using $ObjectPermissions ");


  //checking method 'createStageRows'
  $t->can_ok( $obj,      'createStageRows',   'createStageRows() is callable' );

  //$result = $obj->createStageRows ( $Stage);
  //$t->isa_ok( $result,      'NULL',   'call to method createStageRows ');
  $t->todo( "call to method createStageRows using $Stage ");


  //checking method 'getInputRows'
  $t->can_ok( $obj,      'getInputRows',   'getInputRows() is callable' );

  //$result = $obj->getInputRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getInputRows ');
  $t->todo( "call to method getInputRows using $sProUid ");


  //checking method 'createInputRows'
  $t->can_ok( $obj,      'createInputRows',   'createInputRows() is callable' );

  //$result = $obj->createInputRows ( $aInput);
  //$t->isa_ok( $result,      'NULL',   'call to method createInputRows ');
  $t->todo( "call to method createInputRows using $aInput ");


  //checking method 'renewAllInputGuid'
  $t->can_ok( $obj,      'renewAllInputGuid',   'renewAllInputGuid() is callable' );

  //$result = $obj->renewAllInputGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllInputGuid ');
  $t->todo( "call to method renewAllInputGuid using $oData ");


  //checking method 'getOutputRows'
  $t->can_ok( $obj,      'getOutputRows',   'getOutputRows() is callable' );

  //$result = $obj->getOutputRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getOutputRows ');
  $t->todo( "call to method getOutputRows using $sProUid ");


  //checking method 'createOutputRows'
  $t->can_ok( $obj,      'createOutputRows',   'createOutputRows() is callable' );

  //$result = $obj->createOutputRows ( $aOutput);
  //$t->isa_ok( $result,      'NULL',   'call to method createOutputRows ');
  $t->todo( "call to method createOutputRows using $aOutput ");


  //checking method 'renewAllOutputGuid'
  $t->can_ok( $obj,      'renewAllOutputGuid',   'renewAllOutputGuid() is callable' );

  //$result = $obj->renewAllOutputGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllOutputGuid ');
  $t->todo( "call to method renewAllOutputGuid using $oData ");


  //checking method 'renewAllTriggerGuid'
  $t->can_ok( $obj,      'renewAllTriggerGuid',   'renewAllTriggerGuid() is callable' );

  //$result = $obj->renewAllTriggerGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllTriggerGuid ');
  $t->todo( "call to method renewAllTriggerGuid using $oData ");


  //checking method 'renewAllSubProcessGuid'
  $t->can_ok( $obj,      'renewAllSubProcessGuid',   'renewAllSubProcessGuid() is callable' );

  //$result = $obj->renewAllSubProcessGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllSubProcessGuid ');
  $t->todo( "call to method renewAllSubProcessGuid using $oData ");


  //checking method 'renewAllCaseTrackerObjectGuid'
  $t->can_ok( $obj,      'renewAllCaseTrackerObjectGuid',   'renewAllCaseTrackerObjectGuid() is callable' );

  //$result = $obj->renewAllCaseTrackerObjectGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllCaseTrackerObjectGuid ');
  $t->todo( "call to method renewAllCaseTrackerObjectGuid using $oData ");


  //checking method 'renewAllDBSourceGuid'
  $t->can_ok( $obj,      'renewAllDBSourceGuid',   'renewAllDBSourceGuid() is callable' );

  //$result = $obj->renewAllDBSourceGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllDBSourceGuid ');
  $t->todo( "call to method renewAllDBSourceGuid using $oData ");


  //checking method 'renewAllObjectPermissionGuid'
  $t->can_ok( $obj,      'renewAllObjectPermissionGuid',   'renewAllObjectPermissionGuid() is callable' );

  //$result = $obj->renewAllObjectPermissionGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllObjectPermissionGuid ');
  $t->todo( "call to method renewAllObjectPermissionGuid using $oData ");


  //checking method 'renewAllRouteGuid'
  $t->can_ok( $obj,      'renewAllRouteGuid',   'renewAllRouteGuid() is callable' );

  //$result = $obj->renewAllRouteGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllRouteGuid ');
  $t->todo( "call to method renewAllRouteGuid using $oData ");


  //checking method 'renewAllStageGuid'
  $t->can_ok( $obj,      'renewAllStageGuid',   'renewAllStageGuid() is callable' );

  //$result = $obj->renewAllStageGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllStageGuid ');
  $t->todo( "call to method renewAllStageGuid using $oData ");


  //checking method 'renewAllSwimlanesElementsGuid'
  $t->can_ok( $obj,      'renewAllSwimlanesElementsGuid',   'renewAllSwimlanesElementsGuid() is callable' );

  //$result = $obj->renewAllSwimlanesElementsGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllSwimlanesElementsGuid ');
  $t->todo( "call to method renewAllSwimlanesElementsGuid using $oData ");


  //checking method 'renewAllReportTableGuid'
  $t->can_ok( $obj,      'renewAllReportTableGuid',   'renewAllReportTableGuid() is callable' );

  //$result = $obj->renewAllReportTableGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllReportTableGuid ');
  $t->todo( "call to method renewAllReportTableGuid using $oData ");


  //checking method 'renewAllReportVarGuid'
  $t->can_ok( $obj,      'renewAllReportVarGuid',   'renewAllReportVarGuid() is callable' );

  //$result = $obj->renewAllReportVarGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllReportVarGuid ');
  $t->todo( "call to method renewAllReportVarGuid using $oData ");


  //checking method 'getStepRows'
  $t->can_ok( $obj,      'getStepRows',   'getStepRows() is callable' );

  //$result = $obj->getStepRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getStepRows ');
  $t->todo( "call to method getStepRows using $sProUid ");


  //checking method 'createStepRows'
  $t->can_ok( $obj,      'createStepRows',   'createStepRows() is callable' );

  //$result = $obj->createStepRows ( $aStep);
  //$t->isa_ok( $result,      'NULL',   'call to method createStepRows ');
  $t->todo( "call to method createStepRows using $aStep ");


  //checking method 'createStepSupervisorRows'
  $t->can_ok( $obj,      'createStepSupervisorRows',   'createStepSupervisorRows() is callable' );

  //$result = $obj->createStepSupervisorRows ( $aStepSupervisor);
  //$t->isa_ok( $result,      'NULL',   'call to method createStepSupervisorRows ');
  $t->todo( "call to method createStepSupervisorRows using $aStepSupervisor ");


  //checking method 'renewAllStepGuid'
  $t->can_ok( $obj,      'renewAllStepGuid',   'renewAllStepGuid() is callable' );

  //$result = $obj->renewAllStepGuid ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method renewAllStepGuid ');
  $t->todo( "call to method renewAllStepGuid using $oData ");


  //checking method 'getDynaformRows'
  $t->can_ok( $obj,      'getDynaformRows',   'getDynaformRows() is callable' );

  //$result = $obj->getDynaformRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getDynaformRows ');
  $t->todo( "call to method getDynaformRows using $sProUid ");


  //checking method 'getObjectPermissionRows'
  $t->can_ok( $obj,      'getObjectPermissionRows',   'getObjectPermissionRows() is callable' );

  //$result = $obj->getObjectPermissionRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getObjectPermissionRows ');
  $t->todo( "call to method getObjectPermissionRows using $sProUid ");


  //checking method 'createDynaformRows'
  $t->can_ok( $obj,      'createDynaformRows',   'createDynaformRows() is callable' );

  //$result = $obj->createDynaformRows ( $aDynaform);
  //$t->isa_ok( $result,      'NULL',   'call to method createDynaformRows ');
  $t->todo( "call to method createDynaformRows using $aDynaform ");


  //checking method 'createStepTriggerRows'
  $t->can_ok( $obj,      'createStepTriggerRows',   'createStepTriggerRows() is callable' );

  //$result = $obj->createStepTriggerRows ( $aStepTrigger);
  //$t->isa_ok( $result,      'NULL',   'call to method createStepTriggerRows ');
  $t->todo( "call to method createStepTriggerRows using $aStepTrigger ");


  //checking method 'getStepTriggerRows'
  $t->can_ok( $obj,      'getStepTriggerRows',   'getStepTriggerRows() is callable' );

  //$result = $obj->getStepTriggerRows ( $aTask);
  //$t->isa_ok( $result,      'NULL',   'call to method getStepTriggerRows ');
  $t->todo( "call to method getStepTriggerRows using $aTask ");


  //checking method 'getTriggerRows'
  $t->can_ok( $obj,      'getTriggerRows',   'getTriggerRows() is callable' );

  //$result = $obj->getTriggerRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getTriggerRows ');
  $t->todo( "call to method getTriggerRows using $sProUid ");


  //checking method 'createTriggerRows'
  $t->can_ok( $obj,      'createTriggerRows',   'createTriggerRows() is callable' );

  //$result = $obj->createTriggerRows ( $aTrigger);
  //$t->isa_ok( $result,      'NULL',   'call to method createTriggerRows ');
  $t->todo( "call to method createTriggerRows using $aTrigger ");


  //checking method 'getGroupwfRows'
  $t->can_ok( $obj,      'getGroupwfRows',   'getGroupwfRows() is callable' );

  //$result = $obj->getGroupwfRows ( $aGroups);
  //$t->isa_ok( $result,      'NULL',   'call to method getGroupwfRows ');
  $t->todo( "call to method getGroupwfRows using $aGroups ");


  //checking method 'getDBConnectionsRows'
  $t->can_ok( $obj,      'getDBConnectionsRows',   'getDBConnectionsRows() is callable' );

  //$result = $obj->getDBConnectionsRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getDBConnectionsRows ');
  $t->todo( "call to method getDBConnectionsRows using $sProUid ");


  //checking method 'getStepSupervisorRows'
  $t->can_ok( $obj,      'getStepSupervisorRows',   'getStepSupervisorRows() is callable' );

  //$result = $obj->getStepSupervisorRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getStepSupervisorRows ');
  $t->todo( "call to method getStepSupervisorRows using $sProUid ");


  //checking method 'getReportTablesRows'
  $t->can_ok( $obj,      'getReportTablesRows',   'getReportTablesRows() is callable' );

  //$result = $obj->getReportTablesRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getReportTablesRows ');
  $t->todo( "call to method getReportTablesRows using $sProUid ");


  //checking method 'getReportTablesVarsRows'
  $t->can_ok( $obj,      'getReportTablesVarsRows',   'getReportTablesVarsRows() is callable' );

  //$result = $obj->getReportTablesVarsRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getReportTablesVarsRows ');
  $t->todo( "call to method getReportTablesVarsRows using $sProUid ");


  //checking method 'getTaskUserRows'
  $t->can_ok( $obj,      'getTaskUserRows',   'getTaskUserRows() is callable' );

  //$result = $obj->getTaskUserRows ( $aTask);
  //$t->isa_ok( $result,      'NULL',   'call to method getTaskUserRows ');
  $t->todo( "call to method getTaskUserRows using $aTask ");


  //checking method 'createTaskUserRows'
  $t->can_ok( $obj,      'createTaskUserRows',   'createTaskUserRows() is callable' );

  //$result = $obj->createTaskUserRows ( $aTaskUser);
  //$t->isa_ok( $result,      'NULL',   'call to method createTaskUserRows ');
  $t->todo( "call to method createTaskUserRows using $aTaskUser ");


  //checking method 'createGroupRow'
  $t->can_ok( $obj,      'createGroupRow',   'createGroupRow() is callable' );

  //$result = $obj->createGroupRow ( $aGroupwf);
  //$t->isa_ok( $result,      'NULL',   'call to method createGroupRow ');
  $t->todo( "call to method createGroupRow using $aGroupwf ");


  //checking method 'createDBConnectionsRows'
  $t->can_ok( $obj,      'createDBConnectionsRows',   'createDBConnectionsRows() is callable' );

  //$result = $obj->createDBConnectionsRows ( $aConnections);
  //$t->isa_ok( $result,      'NULL',   'call to method createDBConnectionsRows ');
  $t->todo( "call to method createDBConnectionsRows using $aConnections ");


  //checking method 'createReportTables'
  $t->can_ok( $obj,      'createReportTables',   'createReportTables() is callable' );

  //$result = $obj->createReportTables ( $aReportTables, $aReportTablesVars);
  //$t->isa_ok( $result,      'NULL',   'call to method createReportTables ');
  $t->todo( "call to method createReportTables using $aReportTables, $aReportTablesVars ");


  //checking method 'updateReportTables'
  $t->can_ok( $obj,      'updateReportTables',   'updateReportTables() is callable' );

  //$result = $obj->updateReportTables ( $aReportTables, $aReportTablesVars);
  //$t->isa_ok( $result,      'NULL',   'call to method updateReportTables ');
  $t->todo( "call to method updateReportTables using $aReportTables, $aReportTablesVars ");


  //checking method 'createReportTablesVars'
  $t->can_ok( $obj,      'createReportTablesVars',   'createReportTablesVars() is callable' );

  //$result = $obj->createReportTablesVars ( $aReportTablesVars);
  //$t->isa_ok( $result,      'NULL',   'call to method createReportTablesVars ');
  $t->todo( "call to method createReportTablesVars using $aReportTablesVars ");


  //checking method 'cleanupReportTablesReferences'
  $t->can_ok( $obj,      'cleanupReportTablesReferences',   'cleanupReportTablesReferences() is callable' );

  //$result = $obj->cleanupReportTablesReferences ( $aReportTables);
  //$t->isa_ok( $result,      'NULL',   'call to method cleanupReportTablesReferences ');
  $t->todo( "call to method cleanupReportTablesReferences using $aReportTables ");


  //checking method 'serializeProcess'
  $t->can_ok( $obj,      'serializeProcess',   'serializeProcess() is callable' );

  //$result = $obj->serializeProcess ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method serializeProcess ');
  $t->todo( "call to method serializeProcess using $sProUid ");


  //checking method 'saveSerializedProcess'
  $t->can_ok( $obj,      'saveSerializedProcess',   'saveSerializedProcess() is callable' );

  //$result = $obj->saveSerializedProcess ( $oData);
  //$t->isa_ok( $result,      'NULL',   'call to method saveSerializedProcess ');
  $t->todo( "call to method saveSerializedProcess using $oData ");


  //checking method 'getProcessData'
  $t->can_ok( $obj,      'getProcessData',   'getProcessData() is callable' );

  //$result = $obj->getProcessData ( $pmFilename);
  //$t->isa_ok( $result,      'NULL',   'call to method getProcessData ');
  $t->todo( "call to method getProcessData using $pmFilename ");


  //checking method 'disablePreviousProcesses'
  $t->can_ok( $obj,      'disablePreviousProcesses',   'disablePreviousProcesses() is callable' );

  //$result = $obj->disablePreviousProcesses ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method disablePreviousProcesses ');
  $t->todo( "call to method disablePreviousProcesses using $sProUid ");


  //checking method 'createFiles'
  $t->can_ok( $obj,      'createFiles',   'createFiles() is callable' );

  //$result = $obj->createFiles ( $oData, $pmFilename);
  //$t->isa_ok( $result,      'NULL',   'call to method createFiles ');
  $t->todo( "call to method createFiles using $oData, $pmFilename ");


  //checking method 'removeProcessRows'
  $t->can_ok( $obj,      'removeProcessRows',   'removeProcessRows() is callable' );

  //$result = $obj->removeProcessRows ( $sProUid);
  //$t->isa_ok( $result,      'NULL',   'call to method removeProcessRows ');
  $t->todo( "call to method removeProcessRows using $sProUid ");


  //checking method 'createProcessFromData'
  $t->can_ok( $obj,      'createProcessFromData',   'createProcessFromData() is callable' );

  //$result = $obj->createProcessFromData ( $oData, $pmFilename);
  //$t->isa_ok( $result,      'NULL',   'call to method createProcessFromData ');
  $t->todo( "call to method createProcessFromData using $oData, $pmFilename ");


  //checking method 'updateProcessFromData'
  $t->can_ok( $obj,      'updateProcessFromData',   'updateProcessFromData() is callable' );

  //$result = $obj->updateProcessFromData ( $oData, $pmFilename);
  //$t->isa_ok( $result,      'NULL',   'call to method updateProcessFromData ');
  $t->todo( "call to method updateProcessFromData using $oData, $pmFilename ");


  //checking method 'getStartingTaskForUser'
  $t->can_ok( $obj,      'getStartingTaskForUser',   'getStartingTaskForUser() is callable' );

  //$result = $obj->getStartingTaskForUser ( $sProUid, $sUsrUid);
  //$t->isa_ok( $result,      'NULL',   'call to method getStartingTaskForUser ');
  $t->todo( "call to method getStartingTaskForUser using $sProUid, $sUsrUid ");


  //checking method 'ws_open'
  $t->can_ok( $obj,      'ws_open',   'ws_open() is callable' );

  //$result = $obj->ws_open ( $user, $pass);
  //$t->isa_ok( $result,      'NULL',   'call to method ws_open ');
  $t->todo( "call to method ws_open using $user, $pass ");


  //checking method 'ws_open_public'
  $t->can_ok( $obj,      'ws_open_public',   'ws_open_public() is callable' );

  //$result = $obj->ws_open_public ( );
  //$t->isa_ok( $result,      'NULL',   'call to method ws_open_public ');
  $t->todo( "call to method ws_open_public using  ");


  //checking method 'ws_processList'
  $t->can_ok( $obj,      'ws_processList',   'ws_processList() is callable' );

  //$result = $obj->ws_processList ( );
  //$t->isa_ok( $result,      'NULL',   'call to method ws_processList ');
  $t->todo( "call to method ws_processList using  ");


  //checking method 'downloadFile'
  $t->can_ok( $obj,      'downloadFile',   'downloadFile() is callable' );

  //$result = $obj->downloadFile ( $file, $local_path, $newfilename);
  //$t->isa_ok( $result,      'NULL',   'call to method downloadFile ');
  $t->todo( "call to method downloadFile using $file, $local_path, $newfilename ");


  //checking method 'ws_processGetData'
  $t->can_ok( $obj,      'ws_processGetData',   'ws_processGetData() is callable' );

  //$result = $obj->ws_processGetData ( $proId);
  //$t->isa_ok( $result,      'NULL',   'call to method ws_processGetData ');
  $t->todo( "call to method ws_processGetData using $proId ");



  $t->todo (  'review all pendings methods in this class');
