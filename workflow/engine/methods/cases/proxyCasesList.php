<?php
  $callback = isset($_POST['callback']) ? $_POST['callback'] : 'stcCallback1001';
  $dir      = isset($_POST['dir'])    ? $_POST['dir']    : 'DESC';
  $sort     = isset($_POST['sort'])   ? $_POST['sort']   : '';
  $start    = isset($_POST['start'])  ? $_POST['start']  : '0';
  $limit    = isset($_POST['limit'])  ? $_POST['limit']  : '25';
  $filter   = isset($_POST['filter']) ? $_POST['filter'] : '';
  $search   = isset($_POST['search']) ? $_POST['search'] : '';
  $process  = isset($_POST['process']) ? $_POST['process'] : '';
  //$action = isset($_GET['action']) ? $_GET['action'] : 'read';
  $option = '';
  if ( isset($_GET['t'] ) ) $option = $_GET['t'];
  try {  	

  G::LoadClass("BasePeer" );
  require_once ( "classes/model/AppCacheView.php" );
  
  $sUIDUserLogged = $_SESSION['USER_LOGGED'];

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
 
/*  
  $xmlform = 'todo/todoList';
  switch ( $option ) {
  	case 'new' :  
  	     $G_ID_SUB_MENU_SELECTED = 'ID2'; 
         $Criteria->add (  todoPeer::TOD_STATUS, "NEW" , CRITERIA::EQUAL );
         $xmlform = 'todo/todoListUnassigned';
  	     break;
  	case 'assigned' :  
  	     $G_ID_SUB_MENU_SELECTED = 'ID3'; 
         $Criteria->add (  todoPeer::TOD_STATUS, "ASSIGNED" , CRITERIA::EQUAL );
  	     break;
  	case 'completed' :  
  	     $G_ID_SUB_MENU_SELECTED = 'ID4'; 
         $Criteria->add (  todoPeer::TOD_STATUS, "COMPLETED" , CRITERIA::EQUAL );
  	     break;
  	case 'bugIncompleted' :  
         $xmlform = 'todo/todoListIncompleted';
  	     $G_ID_SUB_MENU_SELECTED = 'ID5'; 
         $Criteria->add (  todoPeer::TOD_TYPE,   "BUG" ,       CRITERIA::EQUAL );
         $Criteria->add (  todoPeer::TOD_STATUS, "COMPLETED" , CRITERIA::EQUAL );
         $Criteria->add (  todoPeer::BUG_STATUS, array('closed', 'resolved'), CRITERIA::NOT_IN );
         //$Criteria->add (  todoPeer::BUG_STATUS, 80 , CRITERIA::LESS_THAN );
  	     break;
  	case 'x' :    
  	     $G_ID_SUB_MENU_SELECTED = ''; 
  	     break;
  	default :     $G_ID_SUB_MENU_SELECTED = 'ID1'; 
         $Criteria->add (  todoPeer::TOD_UID, "xx" , CRITERIA::NOT_EQUAL );
  	     break;
  }
*/
  $Criteria->add (AppCacheViewPeer::APP_STATUS, "TO_DO" , CRITERIA::EQUAL );
  $Criteria->add (AppCacheViewPeer::USR_UID, $sUIDUserLogged);

  $Criteria->add (AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL);
  $Criteria->add (AppCacheViewPeer::APP_THREAD_STATUS, 'OPEN');
  $Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');
  
  if ( $process != '' ) {
    $Criteria->add (AppCacheViewPeer::APP_PRO_TITLE, $process, Criteria::EQUAL );
  }  

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

  if ( $search != '' ) {
    $Criteria->add (AppCacheViewPeer::APP_TITLE, $search . '%', Criteria::LIKE );
  }  

  $totalCount = GulliverBasePeer::doCount( $Criteria );
      
  $Criteria->setLimit( $limit );
  $Criteria->setOffset( $start );
      
  if ( $sort != '' ) {
    if ( $dir == 'DESC' )
      $Criteria->addDescendingOrderByColumn( $sort );
    else
      $Criteria->addAscendingOrderByColumn( $sort );
    }
  $oDataset = AppCacheViewPeer::doSelectRS($Criteria);
  $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
  $oDataset->next();
      
  $result = array();
  $result['totalCount'] = $totalCount;
  $rows = array();
  $index = $start;
  while($aRow = $oDataset->getRow()){
    $aRow['index'] = ++$index;
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
