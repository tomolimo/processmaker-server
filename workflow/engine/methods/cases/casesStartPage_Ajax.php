<?php

if (! isset ( $_REQUEST ['action'] )) {
  $ruturn ['success'] = 'failure';
  $ruturn ['message'] = 'You may request an action';
  print json_encode ( $ruturn );
  die ();
}
if (! function_exists ( $_REQUEST ['action'] )) {
  $ruturn ['success'] = 'failure';
  $ruturn ['message'] = 'The requested action doesn\'t exists';
  print json_encode ( $ruturn );
  die ();
}

$functionName = $_REQUEST ['action'];
$functionParams = isset ( $_REQUEST ['params'] ) ? $_REQUEST ['params'] : array ();

$functionName ( $functionParams );

function getProcessList() {
  G::LoadClass ( 'case' );
  $oCase = new Cases ( );
  $bCanStart = $oCase->canStartCase ( $_SESSION ['USER_LOGGED'] );
  if ($bCanStart) {
    $processListInitial = $oCase->getStartCasesPerType ( $_SESSION ['USER_LOGGED'], 'category' );
    $processList = array ();
    foreach ( $processListInitial as $key => $procInfo ) {
      if (isset ( $procInfo ['cat'] )) {
        if (trim ( $procInfo ['cat'] ) == "")
          $procInfo ['cat'] = "_OTHER_";
        $processList [$procInfo ['catname']] [$procInfo ['value']] = $procInfo;
      }
    }
    ksort ( $processList );
    foreach ( $processList as $key => $processInfo ) {
      ksort ( $processList [$key] );
    }
    
    if (! isset ( $_REQUEST ['node'] )) {
      $node = 'root';
    } else {
      $node = $_REQUEST ['node'];
    }
    
    $processListTree = array ();
    if ($node == 'root') {
      foreach ( $processList as $key => $processInfo ) {
        $tempTree ['text'] = $key;
        $tempTree ['id'] = $key;
        $tempTree ['cls'] = 'folder';
        $tempTree ['draggable'] = true;
        $tempTree ['optionType'] = "category";
        //$tempTree['allowDrop']=false;
        $tempTree ['singleClickExpand'] = true;
        if ($key != "No Category") {
          $tempTree ['expanded'] = true;
        } else {
          $tempTree ['expanded'] = false;
        }
        $processListTree [] = $tempTree;
      }
    } else {
      foreach ( $processList [$node] as $key => $processInfo ) {
        //print_r($processInfo);
        $tempTree ['text'] = ellipsis ( $key, 50 );
        //$tempTree['text']=$key;
        $tempTree ['id'] = $key;
        $tempTree ['draggable'] = true;
        $tempTree ['leaf'] = true;
        $tempTree ['icon'] = '/images/icon.trigger.png';
        $tempTree ['allowChildren'] = false;
        $tempTree ['optionType'] = "startProcess";
        $tempTree ['pro_uid'] = $processInfo ['pro_uid'];
        $tempTree ['tas_uid'] = $processInfo ['uid'];
        
        //$tempTree['cls']='file';
        $processListTree [] = $tempTree;
      }
    
    }
    $processList = $processListTree;
  } else {
    $processList ['success'] = 'failure';
    $processList ['message'] = 'User can\'t start process';
  }
  print json_encode ( $processList );
  die ();
}
function ellipsis($text, $numb) {
  $text = html_entity_decode ( $text, ENT_QUOTES );
  if (strlen ( $text ) > $numb) {
    $text = substr ( $text, 0, $numb );
    $text = substr ( $text, 0, strrpos ( $text, " " ) );
    //This strips the full stop:
    if ((substr ( $text, - 1 )) == ".") {
      $text = substr ( $text, 0, (strrpos ( $text, "." )) );
    }
    $etc = "...";
    $text = $text . $etc;
  }
  
  return $text;
}
function startCase() {
  /* Includes */
  G::LoadClass ( 'case' );
  
  /* GET , POST & $_SESSION Vars */
  
  /* unset any variable, because we are starting a new case */
  if (isset ( $_SESSION ['APPLICATION'] ))
    unset ( $_SESSION ['APPLICATION'] );
  if (isset ( $_SESSION ['PROCESS'] ))
    unset ( $_SESSION ['PROCESS'] );
  if (isset ( $_SESSION ['TASK'] ))
    unset ( $_SESSION ['TASK'] );
  if (isset ( $_SESSION ['INDEX'] ))
    unset ( $_SESSION ['INDEX'] );
  if (isset ( $_SESSION ['STEP_POSITION'] ))
    unset ( $_SESSION ['STEP_POSITION'] );
    
  /* Process */
  try {
    $oCase = new Cases ( );
    $aData = $oCase->startCase ( $_REQUEST ['taskId'], $_SESSION ['USER_LOGGED'] );
    $_SESSION ['APPLICATION'] = $aData ['APPLICATION'];
    $_SESSION ['INDEX'] = $aData ['INDEX'];
    $_SESSION ['PROCESS'] = $aData ['PROCESS'];
    $_SESSION ['TASK'] = $_REQUEST ['taskId'];
    $_SESSION ['STEP_POSITION'] = 0;
    
    $_SESSION ['CASES_REFRESH'] = true;
    
    $oCase = new Cases ( );
    $aNextStep = $oCase->getNextStep ( $_SESSION ['PROCESS'], $_SESSION ['APPLICATION'], $_SESSION ['INDEX'], $_SESSION ['STEP_POSITION'] );
    $_SESSION ['BREAKSTEP'] ['NEXT_STEP'] = $aNextStep;
    $aData ['status'] = 'success';
    $aData ['openCase'] = $aNextStep;
    print (json_encode ( $aData )) ;
    //G::header('location: ' . $aNextStep['PAGE']);
  } catch ( Exception $e ) {
    $_SESSION ['G_MESSAGE'] = $e->getMessage ();
    $_SESSION ['G_MESSAGE_TYPE'] = 'error';
    //G::header('location: cases_New' );
    $aData ['status'] = 'failure';
    $aData ['message'] = $e->getMessage ();
    print_r ( json_encode ( $aData ) );
  }
}

function getSimpleDashboardData() {
  G::LoadClass ( "BasePeer" );
  require_once ("classes/model/AppCacheView.php");
  require_once 'classes/model/Process.php';
  $sUIDUserLogged = $_SESSION ['USER_LOGGED'];
  
  $Criteria = new Criteria ( 'workflow' );
  
  $Criteria->clearSelectColumns ();
  
  $Criteria->addSelectColumn ( AppCacheViewPeer::PRO_UID );
  $Criteria->addSelectColumn ( AppCacheViewPeer::APP_UID );
  $Criteria->addSelectColumn ( AppCacheViewPeer::APP_NUMBER );
  $Criteria->addSelectColumn ( AppCacheViewPeer::APP_STATUS );
  $Criteria->addSelectColumn ( AppCacheViewPeer::DEL_INDEX );
  $Criteria->addSelectColumn ( AppCacheViewPeer::APP_TITLE );
  $Criteria->addSelectColumn ( AppCacheViewPeer::APP_PRO_TITLE );
  $Criteria->addSelectColumn ( AppCacheViewPeer::APP_TAS_TITLE );
  $Criteria->addSelectColumn ( AppCacheViewPeer::APP_DEL_PREVIOUS_USER );
  $Criteria->addSelectColumn ( AppCacheViewPeer::DEL_TASK_DUE_DATE );
  $Criteria->addSelectColumn ( AppCacheViewPeer::APP_UPDATE_DATE );
  $Criteria->addSelectColumn ( AppCacheViewPeer::DEL_PRIORITY );
  $Criteria->addSelectColumn ( AppCacheViewPeer::DEL_DELAYED );
  $Criteria->addSelectColumn ( AppCacheViewPeer::USR_UID );
  $Criteria->addSelectColumn ( AppCacheViewPeer::APP_THREAD_STATUS );
  
  $Criteria->add ( AppCacheViewPeer::APP_STATUS, array ("TO_DO", "DRAFT" ), CRITERIA::IN );
  $Criteria->add ( AppCacheViewPeer::USR_UID, array ($sUIDUserLogged, "" ), CRITERIA::IN );
  
  $Criteria->add ( AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL );
  
  //$Criteria->add ( AppCacheViewPeer::APP_THREAD_STATUS, 'OPEN' );
  

  $Criteria->add ( AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN' );
  
  //execute the query      
  $oDataset = AppCacheViewPeer::doSelectRS ( $Criteria );
  $oDataset->setFetchmode ( ResultSet::FETCHMODE_ASSOC );
  $oDataset->next ();
  
  $oProcess = new Process ( );
  
  $rows = array ();
  $processNames = array ();
  while ( $aRow = $oDataset->getRow () ) {
    // G::pr($aRow);
    if (! isset ( $processNames [$aRow ['PRO_UID']] )) {
      $aProcess = $oProcess->load ( $aRow ['PRO_UID'] );
      $processNames [$aRow ['PRO_UID']] = $aProcess ['PRO_TITLE'];
    }
    
    if ($aRow ['USR_UID'] == "")
      $aRow ['APP_STATUS'] = "UNASSIGNED";
    if (((in_array ( $aRow ['APP_STATUS'], array ("TO_DO", "UNASSIGNED" ) )) && ($aRow ['APP_THREAD_STATUS'] == "OPEN")) || ($aRow ['APP_STATUS'] == "DRAFT")) {
      $rows [$processNames [$aRow ['PRO_UID']]] [$aRow ['APP_STATUS']] [$aRow ['DEL_DELAYED']] [] = $aRow ['APP_UID'];
      if(!isset($rows [$processNames [$aRow ['PRO_UID']]] [$aRow ['APP_STATUS']]['count'])) $rows [$processNames [$aRow ['PRO_UID']]] [$aRow ['APP_STATUS']]['count']=0;
      $rows [$processNames [$aRow ['PRO_UID']]][$aRow ['APP_STATUS']]['count']++;
    }
    
    $oDataset->next ();
  }
  //Generate different groups of data for graphs
  $rowsResponse=array();
  $i=0;
  foreach($rows as $processID => $processInfo){
  	$i++;
  	if($i<=10){
  	$rowsResponse['caseStatusByProcess'][]=array('process'=>$processID,'inbox'=>isset($processInfo['TO_DO']['count'])?$processInfo['TO_DO']['count']:0,'draft'=>isset($processInfo['DRAFT']['count'])?$processInfo['DRAFT']['count']:0,'unassigned'=>isset($processInfo['UNASSIGNED']['count'])?$processInfo['UNASSIGNED']['count']:0);
  	
  	}
  }
  $rowsResponse['caseDelayed'][]=array('delayed'=>'On Time','total'=>100);
  $rowsResponse['caseDelayed'][]=array('delayed'=>'Delayed','total'=>50);
  
  print_r ( json_encode ( $rowsResponse ) );
}
function getRegisteredDashboards() {
  $oPluginRegistry = & PMPluginRegistry::getSingleton ();
  $dashBoardPages = $oPluginRegistry->getDashboardPages ();
  print_r ( json_encode ( $dashBoardPages ) );
}