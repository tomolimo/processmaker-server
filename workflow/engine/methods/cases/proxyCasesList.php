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
  $status   = isset($_POST['status'])  ? strtoupper($_POST['status']) : '';
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
    case 'search' :
         $Criteria      = $oAppCache->getSearchListCriteria();
         $CriteriaCount = $oAppCache->getSearchCountCriteria();
         break;         
    case 'todo' :
    default:
         $Criteria      = $oAppCache->getToDoListCriteria($userUid);
         $CriteriaCount = $oAppCache->getToDoCountCriteria($userUid);
    break;
  }

  $conf = new Configurations();
  $confCasesList = $conf->loadObject('casesList',$action,'','','');
  if ( !is_array($confCasesList) ) {
    	$rows = getDefaultFields( $action );
    	$result = genericJsonResponse( '', array(), $rows , 20, '' );
      $conf->saveObject($result,'casesList',$action,'','','');
  }

  //add the process filter
  if ( $process != '' ) {
    $Criteria->add      (AppCacheViewPeer::PRO_UID, $process, Criteria::EQUAL );
    $CriteriaCount->add (AppCacheViewPeer::PRO_UID, $process, Criteria::EQUAL );
  }

  if ( $status != '' ) {
    $Criteria->add      (AppCacheViewPeer::APP_STATUS, $status, Criteria::EQUAL );
    $CriteriaCount->add (AppCacheViewPeer::APP_STATUS, $status, Criteria::EQUAL );
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
  		case 'started' : 
        $Criteria->add      (AppCacheViewPeer::DEL_INDEX, 1, Criteria::EQUAL);
        $CriteriaCount->add (AppCacheViewPeer::DEL_INDEX, 1, Criteria::EQUAL);
        break;
  	}
  }  

  //add the search filter
  if ( $search != '' ) {
    $Criteria->add      (AppCacheViewPeer::APP_TITLE, '%' . $search . '%', Criteria::LIKE );
    $CriteriaCount->add (AppCacheViewPeer::APP_TITLE, '%' . $search . '%', Criteria::LIKE );
/*    
    require_once( PATH_DATA_SITE . 'classes/Usuarios.php' );
    $Criteria->add      ('USUARIOS.CEDULA', $search . '%', Criteria::LIKE );
    $CriteriaCount->add ('USUARIOS.CEDULA', $search . '%', Criteria::LIKE );
*/    
  }  

  //here we count how many records exists for this criteria.
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
  	$msg = array ( 'error' => $e->getMessage() );
    print json_encode( $msg ) ;
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
  
 //TODO: Encapsulates these and another default generation functions inside a class
  /**
   * generate all the default fields
   * @return Array $fields
   */
   function setDefaultFields() {
     $fields = array();
     $fields[] = array( 'name' => 'APP_UID'    ,             'fieldType' => 'key',         'label' => G::loadTranslation('ID_CASESLIST_APP_UID'),                'width' => 80,  'align' => 'left');
     $fields[] = array( 'name' => 'DEL_INDEX'  ,             'fieldType' => 'key' ,        'label' => G::loadTranslation('ID_CASESLIST_DEL_INDEX')  ,            'width' => 50,  'align' => 'left');
     $fields[] = array( 'name' => 'APP_NUMBER' ,             'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_NUMBER') ,            'width' => 80,  'align' => 'left');
     $fields[] = array( 'name' => 'APP_STATUS' ,             'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_STATUS') ,            'width' => 80,  'align' => 'left');
     $fields[] = array( 'name' => 'APP_TITLE'  ,             'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_TITLE')  ,            'width' => 140, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_PRO_TITLE'  ,         'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_PRO_TITLE') ,         'width' => 140, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_TAS_TITLE'  ,         'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_TAS_TITLE') ,         'width' => 140, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_DEL_PREVIOUS_USER'  , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_DEL_PREVIOUS_USER') , 'width' => 120, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_CURRENT_USER'       , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_CURRENT_USER')  ,     'width' => 120, 'align' => 'left');
     $fields[] = array( 'name' => 'DEL_TASK_DUE_DATE'      , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_DEL_TASK_DUE_DATE') ,     'width' => 100, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_UPDATE_DATE'        , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_UPDATE_DATE') ,       'width' => 100, 'align' => 'left');
     $fields[] = array( 'name' => 'DEL_PRIORITY'           , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_DEL_PRIORITY')    ,       'width' => 80,  'align' => 'left');
     $fields[] = array( 'name' => 'APP_FINISH_DATE'        , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_FINISH_DATE') ,       'width' => 100, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_DELAY_UID'          , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_DELAY_UID') ,       'width' => 100, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_THREAD_INDEX'       , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_THREAD_INDEX') ,       'width' => 100, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_DEL_INDEX'          , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_DEL_INDEX') ,       'width' => 100, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_TYPE'               , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_TYPE') ,       'width' => 100, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_DELEGATION_USER'    , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_DELEGATION_USER') ,       'width' => 100, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_ENABLE_ACTION_USER' , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_ENABLE_ACTION_USER') ,       'width' => 100, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_ENABLE_ACTION_DATE' , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_AAPP_ENABLE_ACTION_DATE') ,       'width' => 100, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_DISABLE_ACTION_USER', 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_DISABLE_ACTION_USER') ,       'width' => 100, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_DISABLE_ACTION_DATE', 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_DISABLE_ACTION_DATE') ,       'width' => 100, 'align' => 'left');
     $fields[] = array( 'name' => 'APP_AUTOMATIC_DISABLED_DATE' , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_AUTOMATIC_DISABLED_DATE') ,       'width' => 100, 'align' => 'left');
     return $fields;

   }

 /**
  * this function return the default fields for a default case list
  * @param $action
  * @return an array with the default fields for an specific case list (action)
  */
  function getDefaultFields ( $action ) {
    $rows = array();
    switch ( $action ) {
      case 'todo' : // #, Case, task, process, sent by, due date, Last Modify, Priority
        $rows = setDefaultFields();
        $rows = removeItem( 'APP_STATUS' , $rows );
        $rows = removeItem( 'APP_FINISH_DATE',$rows);
        $rows = removeItem( 'APP_CURRENT_USER',$rows);
        // APP_DELAY fields
        $rows = removeItem( 'APP_DELAY_UID',$rows);
        $rows = removeItem( 'APP_THREAD_INDEX',$rows);
        $rows = removeItem( 'APP_DEL_INDEX',$rows);
        $rows = removeItem( 'APP_TYPE',$rows);
        $rows = removeItem( 'APP_DELEGATION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_AUTOMATIC_DISABLED_DATE',$rows);
        $rows = calculateGridIndex($rows);

        break;

      case 'draft' :    //#, Case, task, process, due date, Last Modify, Priority },
        $rows = setDefaultFields();
        $rows = removeItem( 'APP_STATUS' , $rows );
        $rows = removeItem( 'APP_FINISH_DATE',$rows);
        $rows = removeItem( 'APP_CURRENT_USER',$rows);
        $rows = removeItem( 'APP_DEL_PREVIOUS_USER',$rows);
        $rows = removeItem( 'APP_FINISH_DATE',$rows);
        // APP_DELAY fields
        $rows = removeItem( 'APP_DELAY_UID',$rows);
        $rows = removeItem( 'APP_THREAD_INDEX',$rows);
        $rows = removeItem( 'APP_DEL_INDEX',$rows);
        $rows = removeItem( 'APP_TYPE',$rows);
        $rows = removeItem( 'APP_DELEGATION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_AUTOMATIC_DISABLED_DATE',$rows);
        $rows = calculateGridIndex($rows);
        break;

      case 'sent' : // #, Case, task, process, current user, sent by, Last Modify, Status
        $rows = setDefaultFields();
        $rows = removeItem( 'APP_FINISH_DATE',$rows);
        $rows = removeItem( 'DEL_TASK_DUE_DATE',$rows);
        $rows = removeItem( 'DEL_PRIORITY',$rows);
        // APP_DELAY fields
        $rows = removeItem( 'APP_DELAY_UID',$rows);
        $rows = removeItem( 'APP_THREAD_INDEX',$rows);
        $rows = removeItem( 'APP_DEL_INDEX',$rows);
        $rows = removeItem( 'APP_TYPE',$rows);
        $rows = removeItem( 'APP_DELEGATION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_AUTOMATIC_DISABLED_DATE',$rows);
        $rows = calculateGridIndex($rows);
        break;

      case 'unassigned' :  //#, Case, task, process, completed by user, finish date
        $rows = setDefaultFields();
        $rows = removeItem( 'APP_FINISH_DATE' , $rows );
        $rows = removeItem( 'DEL_TASK_DUE_DATE' , $rows );
        $rows = removeItem( 'DEL_PRIORITY' , $rows );
        $rows = removeItem( 'APP_STATUS' , $rows );
        $rows = removeItem( 'APP_CURRENT_USER' , $rows );
        // APP_DELAY fields
        $rows = removeItem( 'APP_DELAY_UID',$rows);
        $rows = removeItem( 'APP_THREAD_INDEX',$rows);
        $rows = removeItem( 'APP_DEL_INDEX',$rows);
        $rows = removeItem( 'APP_TYPE',$rows);
        $rows = removeItem( 'APP_DELEGATION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_AUTOMATIC_DISABLED_DATE',$rows);
        $rows = calculateGridIndex( $rows );
        break;

      case 'paused' : //#, Case, task, process, sent by
        $rows = setDefaultFields();
        $rows = removeItem( 'APP_FINISH_DATE' , $rows );
        $rows = removeItem( 'DEL_TASK_DUE_DATE' , $rows );
        $rows = removeItem( 'DEL_PRIORITY' , $rows );
        $rows = removeItem( 'APP_STATUS' , $rows );
        $rows = removeItem( 'APP_CURRENT_USER' , $rows );
        // APP_DELAY fields
        $rows = removeItem( 'APP_DELAY_UID',$rows);
        $rows = removeItem( 'APP_TYPE',$rows);
        $rows = removeItem( 'APP_DELEGATION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_AUTOMATIC_DISABLED_DATE',$rows);
        $rows = calculateGridIndex( $rows );
        break;
      case 'completed' : //#, Case, task, process, completed by user, finish date
        $rows = setDefaultFields();
        $rows = removeItem( 'APP_FINISH_DATE' , $rows );
        $rows = removeItem( 'DEL_TASK_DUE_DATE' , $rows );
        $rows = removeItem( 'DEL_PRIORITY' , $rows );
        $rows = removeItem( 'APP_STATUS' , $rows );
        $rows = removeItem( 'APP_CURRENT_USER' , $rows );
        $rows = calculateGridIndex( $rows );
        // APP_DELAY fields
        $rows = removeItem( 'APP_DELAY_UID',$rows);
        $rows = removeItem( 'APP_THREAD_INDEX',$rows);
        $rows = removeItem( 'APP_DEL_INDEX',$rows);
        $rows = removeItem( 'APP_TYPE',$rows);
        $rows = removeItem( 'APP_DELEGATION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_AUTOMATIC_DISABLED_DATE',$rows);
        break;

      case 'cancelled' : //#, Case, task, process, due date, Last Modify
        $rows = setDefaultFields();
        $rows = removeItem( 'APP_FINISH_DATE' , $rows );
        $rows = removeItem( 'DEL_TASK_DUE_DATE' , $rows );
        $rows = removeItem( 'DEL_PRIORITY' , $rows );
        $rows = removeItem( 'APP_STATUS' , $rows );
        $rows = removeItem( 'APP_CURRENT_USER' , $rows );
        // APP_DELAY fields
        $rows = removeItem( 'APP_DELAY_UID',$rows);
        $rows = removeItem( 'APP_THREAD_INDEX',$rows);
        $rows = removeItem( 'APP_DEL_INDEX',$rows);
        $rows = removeItem( 'APP_TYPE',$rows);
        $rows = removeItem( 'APP_DELEGATION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_ENABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_USER',$rows);
        $rows = removeItem( 'APP_DISABLE_ACTION_DATE',$rows);
        $rows = removeItem( 'APP_AUTOMATIC_DISABLED_DATE',$rows);
        $rows = calculateGridIndex( $rows );
        break;
    }
    return $rows;
  }

 /**
  * set the generic Json Response, using two array for the grid stores and a string for the pmtable name
  * @param string $pmtable
  * @param array $first
  * @param array $second
  * @return $response a json string
  */
  function genericJsonResponse($pmtable, $first, $second, $rowsperpage, $dateFormat ) {
    $firstGrid['totalCount']  = count($first);
    $firstGrid['data']        = $first;
    $secondGrid['totalCount'] = count($second);
    $secondGrid['data']       = $second;
    $result = array();
    $result['first']   = $firstGrid;
    $result['second']  = $secondGrid;
    $result['PMTable'] = isset($pmtable) ? $pmtable : '';
    $result['rowsperpage'] = isset($rowsperpage) ? $rowsperpage : 20;
    $result['dateformat']  = isset($dateFormat) && $dateFormat != '' ? $dateFormat : 'M d, Y';
    return $result;
  }

  /**
   *
   * @param String $itemKey
   * @param array $fields
   * @return Boolean
   */
  function removeItem($itemKey,$fields) {
    $removedField = false;
    for ($i=0;$i<count($fields);$i++){
      if ($fields[$i]['name']==$itemKey){
        unset($fields[$i]);
        $removedField = true;
      }
    }
    $fields = array_values($fields);
    //$fields = calculateGridIndex( $fields );
    return ( $fields );
  }

 /**
  *
  * @param Array $fields
  * @return Array
  *
  */
  function calculateGridIndex( $fields ) {
    for ( $i=0;$i<count( $fields );$i++ ) {
      $fields[$i]['gridIndex']=$i+1;
    }
    return ( $fields );
  }