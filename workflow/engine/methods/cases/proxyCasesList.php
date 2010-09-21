<?php
  $callback = isset($_POST['callback']) ? $_POST['callback'] : 'stcCallback1001';
  $dir      = isset($_POST['dir'])    ? $_POST['dir']    : 'DESC';
  $sort     = isset($_POST['sort'])   ? $_POST['sort']   : '';
  $start    = isset($_POST['start'])  ? $_POST['start']  : '0';
  $limit    = isset($_POST['limit'])  ? $_POST['limit']  : '25';
  $filter   = isset($_POST['filter']) ? $_POST['filter'] : '';
  $search   = isset($_POST['search']) ? $_POST['search'] : '';
  $process  = isset($_POST['process']) ? $_POST['process'] : '';
  $action   = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : 'todo');
 

  try {  	

  G::LoadClass("BasePeer" );
  require_once ( "classes/model/AppCacheView.php" );
  require_once ( "classes/model/AppDelegation.php" );
  require_once ( "classes/model/AppDelay.php" );
  G::LoadClass ( "BasePeer" );
  G::LoadClass ( 'configuration' );
  require_once ( "classes/model/Fields.php" );

  $sUIDUserLogged = $_SESSION['USER_LOGGED'];

  switch ( $action ) {
  	case 'todo' :
         $Criteria = getToDo(); 
         break;
  	case 'draft' :
         $Criteria = getDraft();
         break;
  	case 'participated' :
         $Criteria = getParticipated();
         break;
  	case 'unassigned' :
         $Criteria = getUnassigned();
         break;
  	case 'paused' :
         $Criteria = getPaused();
         break;
  	case 'completed' :
         $Criteria = getCompleted();
         break;
  	case 'cancelled' :
         $Criteria = getCancelled();
         break;
  }
   
  //add the process filter
  if ( $process != '' ) {
    $Criteria->add (AppCacheViewPeer::APP_PRO_TITLE, $process, Criteria::EQUAL );
  }  

  //add the filter 
  if ( $filter != '' ) {
  	switch ( $filter ) {
  		case 'read' : 
        $Criteria->add (AppCacheViewPeer::DEL_INIT_DATE, null, Criteria::ISNOTNULL);
        break;
  		case 'unread' : 
        $Criteria->add (AppCacheViewPeer::DEL_INIT_DATE, null, Criteria::ISNULL);
        break;
  	}
  }  

  //add the search filter
  if ( $search != '' ) {
    $Criteria->add (AppCacheViewPeer::APP_TITLE, $search . '%', Criteria::LIKE );
  }  

  //here we count how many records exists for this criteria.
  $totalCount = GulliverBasePeer::doCount( $Criteria );
      
  //add sortable options    
  if ( $sort != '' ) {
    if ( $dir == 'DESC' )
      $Criteria->addDescendingOrderByColumn( $sort );
    else
      $Criteria->addAscendingOrderByColumn( $sort );
    }

  //limit the results according the interface    
  $Criteria->setLimit( $limit );
  $Criteria->setOffset( $start );

  //execute the query      
  $oDataset = AppCacheViewPeer::doSelectRS($Criteria);
  $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
  $oDataset->next();
      
  $result = array();
  $result['totalCount'] = $totalCount;
  $rows = array();
  $index = $start;
  while($aRow = $oDataset->getRow()){
    //$aRow['index'] = ++$index;
    $rows[] = $aRow;
      
    $oDataset->next();
  }
  $result['data'] = $rows;

  print json_encode( $result ) ;
  
  }
  catch ( Exception $e ) {
    $G_PUBLISH = new Publisher;
    $aMessage['MESSAGE'] = $e->getMessage();
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
    G::RenderPage( 'publish', 'blank' );
  }      

  function getToDo () {
  	global $sUIDUserLogged ;
    $Criteria = new Criteria('workflow');
  
    $Criteria->clearSelectColumns ( );
    
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UID );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_NUMBER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_STATUS );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_INDEX );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_PRO_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_TAS_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_DEL_PREVIOUS_USER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_CURRENT_USER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_TASK_DUE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UPDATE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_PRIORITY );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_STATUS );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_FINISH_DATE );
//    $Criteria = addPMFieldsToCriteria('todo',$Criteria);
//JUST TO TEST DAYTOP
/*
 *
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.NAME_OF_PHONE_SCREENER' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.LAST_NAME' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.FIRST_NAME' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.AGE' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.GENDER' );
    $Criteria->addJoin(AppCacheViewPeer::APP_UID, 'PATIENT_INFORMATION.APP_UID', Criteria::LEFT_JOIN);
*/
//JUST TO TEST DAYTOP
    
    $Criteria->add (AppCacheViewPeer::APP_STATUS, "TO_DO" , CRITERIA::EQUAL );
    $Criteria->add (AppCacheViewPeer::USR_UID, $sUIDUserLogged);

    $Criteria->add (AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL);
    $Criteria->add (AppCacheViewPeer::APP_THREAD_STATUS, 'OPEN');
    $Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');
//    g::pr($Criteria);
    return $Criteria;
  }
  
  function getDraft() {
  	global $sUIDUserLogged ;
    $Criteria = new Criteria('workflow');
    $Criteria->clearSelectColumns ( );

    $Criteria = addPMFieldsToCriteria('draft');
        
    $Criteria->add (AppCacheViewPeer::APP_STATUS, "DRAFT" , CRITERIA::EQUAL );
    $Criteria->add (AppCacheViewPeer::USR_UID, $sUIDUserLogged);
    $Criteria->add (AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL);
    $Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');

    return $Criteria;
  }

  function getParticipated() {

    global $sUIDUserLogged;
    $Criteria = new Criteria('workflow');

    $Criteria->clearSelectColumns ( );

    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UID );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_NUMBER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_STATUS );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_INDEX );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_PRO_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_TAS_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_DEL_PREVIOUS_USER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_CURRENT_USER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_TASK_DUE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UPDATE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_PRIORITY );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_STATUS );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_FINISH_DATE );

//JUST TO TEST DAYTOP
/*
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.NAME_OF_PHONE_SCREENER' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.LAST_NAME' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.FIRST_NAME' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.AGE' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.GENDER' );
    $Criteria->addJoin(AppCacheViewPeer::APP_UID, 'PATIENT_INFORMATION.APP_UID', Criteria::LEFT_JOIN);
*/
//JUST TO TEST DAYTOP
    return $Criteria;
  }
  
  function getUnassigned() {

    global $sUIDUserLogged;
    $Criteria = new Criteria('workflow');

    $Criteria->clearSelectColumns ( );

    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UID );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_NUMBER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_STATUS );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_INDEX );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_PRO_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_TAS_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_DEL_PREVIOUS_USER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_CURRENT_USER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_TASK_DUE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UPDATE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_PRIORITY );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_STATUS );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_FINISH_DATE );

//JUST TO TEST DAYTOP
/*
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.NAME_OF_PHONE_SCREENER' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.LAST_NAME' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.FIRST_NAME' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.AGE' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.GENDER' );
    $Criteria->addJoin(AppCacheViewPeer::APP_UID, 'PATIENT_INFORMATION.APP_UID', Criteria::LEFT_JOIN);
*/
//JUST TO TEST DAYTOP
    return $Criteria;
  }

  function getPaused(){
    global $sUIDUserLogged ;
    $Criteria = new Criteria('workflow');

    $Criteria->clearSelectColumns ( );

    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UID );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_NUMBER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_STATUS );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_INDEX );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_PRO_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_TAS_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_DEL_PREVIOUS_USER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_CURRENT_USER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_TASK_DUE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UPDATE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_PRIORITY );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_STATUS );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_FINISH_DATE );

//JUST TO TEST DAYTOP
/*
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.NAME_OF_PHONE_SCREENER' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.LAST_NAME' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.FIRST_NAME' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.AGE' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.GENDER' );
    $Criteria->addJoin(AppCacheViewPeer::APP_UID, 'PATIENT_INFORMATION.APP_UID', Criteria::LEFT_JOIN);
*/
//JUST TO TEST DAYTOP
    $appDelayConds[] = array(AppCacheViewPeer::APP_UID, AppDelayPeer::APP_UID);
    $appDelayConds[] = array(AppDelegationPeer::DEL_INDEX, AppDelayPeer::APP_DEL_INDEX);
//    $appDelayConds[] = array(AppDelegationPeer::DEL_INDEX, AppDelayPeer::APP_DEL_INDEX);
//    $Criteria->addJoin(AppCacheViewPeer::APP_UID, AppDelayPeer::APP_UID, Criteria::LEFT_JOIN);
    $Criteria->addJoinMC($appDelayConds, Criteria::LEFT_JOIN);

    $Criteria->add(AppDelayPeer::APP_DELAY_UID, null, Criteria::ISNOTNULL);
    $Criteria->add(AppDelayPeer::APP_TYPE, array("REASSIGN","ADHOC","CANCEL"), Criteria::NOT_IN);
    $Criteria->add($Criteria->getNewCriterion(AppDelayPeer::APP_DISABLE_ACTION_USER, null, Criteria::ISNULL)->addOr($Criteria->getNewCriterion(AppDelayPeer::APP_DISABLE_ACTION_USER, 0)));
    $Criteria->addDescendingOrderByColumn(AppCacheViewPeer::APP_NUMBER);
    $Criteria->add (AppCacheViewPeer::USR_UID, $sUIDUserLogged);
    return $Criteria;
  }

  function getCompleted(){
    global $sUIDUserLogged ;
    $Criteria = new Criteria('workflow');

    $Criteria->clearSelectColumns ( );

    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UID );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_NUMBER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_STATUS );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_INDEX );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_PRO_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_TAS_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_DEL_PREVIOUS_USER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_CURRENT_USER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_TASK_DUE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UPDATE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_PRIORITY );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_STATUS );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_FINISH_DATE );

//JUST TO TEST DAYTOP
/*
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.NAME_OF_PHONE_SCREENER' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.LAST_NAME' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.FIRST_NAME' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.AGE' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.GENDER' );
    $Criteria->addJoin(AppCacheViewPeer::APP_UID, 'PATIENT_INFORMATION.APP_UID', Criteria::LEFT_JOIN);
*/
//JUST TO TEST DAYTOP
    $Criteria->addJoin(AppCacheViewPeer::APP_UID, AppDelegationPeer::APP_UID, Criteria::LEFT_JOIN);
    $Criteria->add(AppCacheViewPeer::APP_STATUS, 'COMPLETED');
    $Criteria->add(AppDelegationPeer::DEL_PREVIOUS, '0', Criteria::NOT_EQUAL);

    //$c->addAsColumn('DEL_FINISH_DATE', 'max('.AppDelegationPeer::DEL_FINISH_DATE.')');
    $Criteria->addGroupByColumn(AppCacheViewPeer::APP_UID);
    $Criteria->addDescendingOrderByColumn(AppCacheViewPeer::APP_NUMBER);
    return $Criteria;
  }

  function getCancelled(){
    global $sUIDUserLogged ;
    $Criteria = new Criteria('workflow');

    $Criteria->clearSelectColumns ( );

    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UID );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_NUMBER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_STATUS );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_INDEX );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_PRO_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_TAS_TITLE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_DEL_PREVIOUS_USER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_CURRENT_USER );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_TASK_DUE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UPDATE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_PRIORITY );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_STATUS );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_FINISH_DATE );

//JUST TO TEST DAYTOP
/*
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.NAME_OF_PHONE_SCREENER' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.LAST_NAME' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.FIRST_NAME' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.AGE' );
    $Criteria->addSelectColumn (  'PATIENT_INFORMATION.GENDER' );
    $Criteria->addJoin(AppCacheViewPeer::APP_UID, 'PATIENT_INFORMATION.APP_UID', Criteria::LEFT_JOIN);
*/
//JUST TO TEST DAYTOP
    $Criteria->add($Criteria->getNewCriterion(AppCacheViewPeer::APP_THREAD_STATUS, 'CLOSED')->addAnd($Criteria->getNewCriterion(AppCacheViewPeer::APP_STATUS, 'CANCELLED')));
    $Criteria->addDescendingOrderByColumn(AppCacheViewPeer::APP_NUMBER);
    $Criteria->add (AppCacheViewPeer::USR_UID, $sUIDUserLogged);
    return $Criteria;
  }

function addPMFieldsToCriteria($action){
  $caseColumns = array();
  $caseReaderFields = array();
  $oCriteria  = new Criteria('workflow');
  $oCriteria->clearSelectColumns ( );
  // default configuration fields array
  $defaultFields = array (
                  'APP_UID',
                  'DEL_INDEX',
                  'APP_NUMBER',
                  'APP_STATUS',
                  'USR_UID',
                  'PREVIOUS_USR_UID',
                  'TAS_UID',
                  'PRO_UID',
                  'DEL_DELEGATE_DATE',
                  'DEL_INIT_DATE',
                  'DEL_TASK_DUE_DATE',
                  'DEL_FINISH_DATE',
                  'DEL_THREAD_STATUS',
                  'APP_THREAD_STATUS',
                  'APP_TITLE',
                  'APP_PRO_TITLE',
                  'APP_TAS_TITLE',
                  'APP_CURRENT_USER',
                  'APP_DEL_PREVIOUS_USER',
                  'DEL_PRIORITY',
                  'DEL_DURATION',
                  'DEL_QUEUE_DURATION',
                  'DEL_DELAY_DURATION',
                  'DEL_STARTED',
                  'DEL_FINISHED',
                  'DEL_DELAYED',
                  'APP_CREATE_DATE',
                  'APP_FINISH_DATE',
                  'APP_UPDATE_DATE',
                  'APP_OVERDUE_PERCENTAGE'
                );
  
  $conf = new Configurations();
  $confCasesList = $conf->loadObject('casesList',$action,'','','');
  if (count($confCasesList)>1){
  $tableName = 'JUDGEMENT';
    foreach($confCasesList['second']['data'] as $fieldData){
      if (!in_array($fieldData['name'],$defaultFields)){
        $fieldName = $tableName.'.'.$fieldData['name'];
        $oCriteria->addSelectColumn (  $fieldName );
      } else {
        $fieldName = 'APP_CACHE_VIEW.'.$fieldData['name'];
        $oCriteria->addSelectColumn (  $fieldName );
      }
    }
    $oCriteria->addJoin(AppCacheViewPeer::APP_UID, $tableName.'.APP_UID', Criteria::LEFT_JOIN);
    return $oCriteria;
  } else {
    return $oCriteria;
  }
}

/**
  function getCancelled(){
   	global $sUIDUserLogged ;
    $sTypeList = 'cancelled';
    $aAdditionalFilter = array();
    $oCases = new Cases();
    return $oCases->getConditionCasesList( $sTypeList, $sUIDUserLogged, true, $aAdditionalFilter );
  }
  function getPaused(){
   	global $sUIDUserLogged ;
    $sTypeList = 'paused';
    $aAdditionalFilter = array();
    $oCases = new Cases();
    return $oCases->getConditionCasesList( $sTypeList, $sUIDUserLogged, true, $aAdditionalFilter );

  }
  function getCompleted(){
   	global $sUIDUserLogged ;
    $sTypeList = 'completed';
    $aAdditionalFilter = array();
    $oCases = new Cases();
    return $oCases->getConditionCasesList( $sTypeList, $sUIDUserLogged, true, $aAdditionalFilter );
  }
 * */
 