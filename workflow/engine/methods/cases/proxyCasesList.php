<?php

  // getting the extJs parameters
  $callback = isset($_POST['callback']) ? $_POST['callback'] : 'stcCallback1001';
  $dir      = isset($_POST['dir'])    ? $_POST['dir']    : 'DESC';
  $sort     = isset($_POST['sort'])   ? $_POST['sort']   : '';
  $start    = isset($_POST['start'])  ? $_POST['start']  : '0';
  $limit    = isset($_POST['limit'])  ? $_POST['limit']  : '25';
  $filter   = isset($_POST['filter']) ? $_POST['filter'] : '';
  $search   = isset($_POST['search']) ? $_POST['search'] : '';
  $process  = isset($_POST['process']) ? $_POST['process'] : '';
  $action   = isset($_GET['action'])   ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : 'todo');
  $type     = isset($_GET['type'])     ? $_GET['type'] : (isset($_POST['type']) ? $_POST['type'] : 'extjs');

  try {  	

  G::LoadClass("BasePeer" );
  require_once ( "classes/model/AppCacheView.php" );
  require_once ( "classes/model/AppDelegation.php" );
  require_once ( "classes/model/AdditionalTables.php" );
  require_once ( "classes/model/AppDelay.php" );
  G::LoadClass ( "BasePeer" );
  G::LoadClass ( 'configuration' );
  require_once ( "classes/model/Fields.php" );

	$userUid = ( isset($_SESSION['USER_LOGGED'] ) && $_SESSION['USER_LOGGED'] != '' ) ? $_SESSION['USER_LOGGED'] : null;
  $oAppCache = new AppCacheView();
  
// get the action based list
  switch ( $action ) {
  	case 'draft' :
         $Criteria      = $oAppCache->getDraftListCriteria($userUid);
         $CriteriaCount = $oAppCache->getDraftCountCriteria($userUid);
         break;
  	case 'sent' :
         $Criteria      = $oAppCache->getSentListCriteria($userUid);
         $CriteriaCount = $oAppCache->getSentCountCriteria($userUid);
         break;
  	case 'selfservice' :
         $Criteria      = $oAppCache->getUnassignedListCriteria($userUid);
         $CriteriaCount = $oAppCache->getUnassignedCountCriteria($userUid);
         break;
  	case 'paused' :
         $Criteria      = $oAppCache->getPausedListCriteria($userUid);
         $CriteriaCount = $oAppCache->getPausedCountCriteria($userUid);
         break;
  	case 'completed' :
         $Criteria      = $oAppCache->getCompletedListCriteria($userUid);
         $CriteriaCount = $oAppCache->getCompletedCountCriteria($userUid);
         break;
  	case 'cancelled' :
         $Criteria      = $oAppCache->getCancelledListCriteria($userUid);
         $CriteriaCount = $oAppCache->getCancelledCountCriteria($userUid);
         break;
    case 'todo' :
    default:
         $Criteria      = $oAppCache->getToDoListCriteria($userUid);
         $CriteriaCount = $oAppCache->getToDoCountCriteria($userUid);
    break;
  }

  //add the process filter
  if ( $process != '' ) {
    $Criteria->add      (AppCacheViewPeer::APP_PRO_TITLE, $process, Criteria::EQUAL );
    $CriteriaCount->add (AppCacheViewPeer::APP_PRO_TITLE, $process, Criteria::EQUAL );
  }

  //add the filter 
  if ( $filter != '' ) {
  	switch ( $filter ) {
  		case 'read' : 
        $Criteria->add      (AppCacheViewPeer::DEL_INIT_DATE, null, Criteria::ISNOTNULL);
        $CriteriaCount->add (AppCacheViewPeer::DEL_INIT_DATE, null, Criteria::ISNOTNULL);
        break;
  		case 'unread' : 
        $Criteria->add      (AppCacheViewPeer::DEL_INIT_DATE, null, Criteria::ISNULL);
        $CriteriaCount->add (AppCacheViewPeer::DEL_INIT_DATE, null, Criteria::ISNULL);
        break;
  	}
  }  

  //add the search filter
  if ( $search != '' ) {
    $Criteria->add      (AppCacheViewPeer::APP_TITLE, $search . '%', Criteria::LIKE );
    $CriteriaCount->add (AppCacheViewPeer::APP_TITLE, $search . '%', Criteria::LIKE );
  }  

  //here we count how many records exists for this criteria.
  //$totalCount = GulliverBasePeer::doCount( $Criteria );
  $totalCount = AppCacheViewPeer::doCount( $CriteriaCount, true );
      
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
    $rows[] = $aRow;
      
    $oDataset->next();
  }
  $result['data'] = $rows;
 // print the result in json format
  print json_encode( $result ) ;
  
  }
  catch ( Exception $e ) {
    print json_encode( $e->getMessage() ) ;
  }      


  /**
   * gets the participated cases list criteria
   * @return Criteria object $Criteria
   */
  function getParticipated() {
    global $sUIDUserLogged;
    $oResultCriteria = new Criteria('workflow');
    $oResultCriteria->clearSelectColumns ( );
    // adding configuration fields from the configuration options
    // and forming the criteria object
    $oResultCriteria = addPMFieldsToCriteria('sent');
    
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(AppDelayPeer::APP_UID);
    $oCriteria->add(
      $oCriteria->getNewCriterion(
        AppDelayPeer::APP_DISABLE_ACTION_USER, null, Criteria::ISNULL
      )->addOr(
        $oCriteria->getNewCriterion(AppDelayPeer::APP_DISABLE_ACTION_USER, 0)
      )
    );

    //$oCriteria->add(AppDelayPeer::APP_DISABLE_ACTION_USER, null, Criteria::ISNULL);

    $oDataset = AppDelayPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();
    $aProcesses = array();
    while ($aRow = $oDataset->getRow()) {
        $aProcesses[] = $aRow['APP_UID'];
        $oDataset->next();
    }

    if( isset($aAdditionalFilter) && isset($aAdditionalFilter['MINE']) ){
        $oResultCriteria->add($oResultCriteria->getNewCriterion(ApplicationPeer::APP_INIT_USER, $sUIDUserLogged));
    } 
    else {
      $oResultCriteria->add(
        $oResultCriteria->getNewCriterion(
          ApplicationPeer::APP_INIT_USER, $sUIDUserLogged
        )->addOr(
          $oResultCriteria->getNewCriterion(
            AppDelegationPeer::USR_UID, $sUIDUserLogged
          )
        )
      );
    }

    //$c->add($c->getNewCriterion(ApplicationPeer::APP_INIT_USER, $sUIDUserLogged));

    if ( isset($aAdditionalFilter) && isset($aAdditionalFilter['APP_STATUS_FILTER']) ){
      $oResultCriteria->add(ApplicationPeer::APP_STATUS, $sValue, Criteria::EQUAL);
    } 
    else {
      $oResultCriteria->add(ApplicationPeer::APP_STATUS, 'DRAFT', Criteria::NOT_EQUAL);
    }

    $oResultCriteria->add(
      $oResultCriteria->getNewCriterion(
          AppDelegationPeer::DEL_THREAD_STATUS, 'CLOSED'
      )->addOr(
        $oResultCriteria->getNewCriterion(
          ApplicationPeer::APP_STATUS, 'COMPLETED'
        )->addAnd(
          $oResultCriteria->getNewCriterion(AppDelegationPeer::DEL_PREVIOUS, 0)
        )
      )
    );

    $oResultCriteria->add($oResultCriteria->getNewCriterion(ApplicationPeer::APP_UID, $aProcesses, Criteria::NOT_IN));
    $oResultCriteria->addDescendingOrderByColumn(ApplicationPeer::APP_NUMBER);

    return $oResultCriteria;
  }
  /**
   * gets the unassigned cases list criteria
   * @return Criteria object $Criteria
   */
  function getUnassigned() {

    global $sUIDUserLogged;
    $oCriteria = new Criteria('workflow');
    $oCriteria->clearSelectColumns ( );

    $oCriteria = addPMFieldsToCriteria('selfservice');

    // self service filter
    if (!class_exists('Cases')){
      G::LoadClass("case" );
    }

    $oCase = new Cases();
    $tasks = $oCase->getSelfServiceTasks( $_SESSION['USER_LOGGED'] );
    $aTasks = array();
    foreach ( $tasks as $key => $val ) {
      if ( strlen(trim($val['uid'])) > 10 ) $aTasks[] = $val['uid'];
    }

    $oCriteria->add(AppCacheViewPeer::USR_UID, '');
    $oCriteria->add(AppCacheViewPeer::TAS_UID, $aTasks , Criteria::IN );

    // end selfservice filter

    // adding configuration fields from the configuration options
    // and forming the criteria object


    return $oCriteria;
  }
