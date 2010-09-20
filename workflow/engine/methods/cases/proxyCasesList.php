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
  
  $sUIDUserLogged = $_SESSION['USER_LOGGED'];

  switch ( $action ) {
  	case 'todo' :
         $Criteria = getToDo(); 
         break;

  	case 'draft' :
         $Criteria = getDraft(); 
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
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_TASK_DUE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UPDATE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_PRIORITY );
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
    
    $Criteria->add (AppCacheViewPeer::APP_STATUS, "TO_DO" , CRITERIA::EQUAL );
    $Criteria->add (AppCacheViewPeer::USR_UID, $sUIDUserLogged);

    $Criteria->add (AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL);
    $Criteria->add (AppCacheViewPeer::APP_THREAD_STATUS, 'OPEN');
    $Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');

    return $Criteria;
  }
  
  function getDraft() {
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
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_TASK_DUE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::APP_UPDATE_DATE );
    $Criteria->addSelectColumn (  AppCacheViewPeer::DEL_PRIORITY );
    
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
        
    $Criteria->add (AppCacheViewPeer::APP_STATUS, "DRAFT" , CRITERIA::EQUAL );
    $Criteria->add (AppCacheViewPeer::USR_UID, $sUIDUserLogged);

    $Criteria->add (AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL);
    $Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');

    return $Criteria;
  }  