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
  $user     = isset($_POST['user'])    ? $_POST['user']    : '';
  $status   = isset($_POST['status'])  ? strtoupper($_POST['status']) : '';
  $action   = isset($_GET['action'])   ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : 'todo');
  $type     = isset($_GET['type'])     ? $_GET['type'] : (isset($_POST['type']) ? $_POST['type'] : 'extjs');
  $user     = isset($_POST['user'])    ? $_POST['user'] : '';
  $dateFrom = isset($_POST['dateFrom'])? substr($_POST['dateFrom'],0,10) : '';
  $dateTo   = isset($_POST['dateTo']) ? substr($_POST['dateTo'],0,10) : '';

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
        case 'unassigned':
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
    case 'to_revise' :
         $Criteria      = $oAppCache->getToReviseListCriteria($userUid);
         $CriteriaCount = $oAppCache->getToReviseCountCriteria($userUid);
         break;
    case 'to_reassign' :
         $Criteria      = $oAppCache->getToReassignListCriteria();
         $CriteriaCount = $oAppCache->getToReassignCountCriteria();
         break;
    case 'todo' :
    default:
         $Criteria      = $oAppCache->getToDoListCriteria($userUid);
         $CriteriaCount = $oAppCache->getToDoCountCriteria($userUid);
    break;
  }

  $conf = new Configurations();
  $confCasesList = $conf->getConfiguration('casesList',$action=='search'?'sent':$action );
  if ( !is_array($confCasesList) ) {
    	$rows = getDefaultFields( $action );
    	$result = genericJsonResponse( '', array(), $rows , 20, '' );
      $conf->saveObject($result,'casesList',$action,'','','');
  }

  // add the process filter
  if ( $process != '' ) {
    $Criteria->add      (AppCacheViewPeer::PRO_UID, $process, Criteria::EQUAL );
    $CriteriaCount->add (AppCacheViewPeer::PRO_UID, $process, Criteria::EQUAL );
  }

  // add the user filter
  if ( $user != '' ) {
    $Criteria->add      (AppCacheViewPeer::USR_UID, $user, Criteria::EQUAL );
    $CriteriaCount->add (AppCacheViewPeer::USR_UID, $user, Criteria::EQUAL );
  }

  if ( $status != '' ) {
    $Criteria->add      (AppCacheViewPeer::APP_STATUS, $status, Criteria::EQUAL );
    $CriteriaCount->add (AppCacheViewPeer::APP_STATUS, $status, Criteria::EQUAL );
  }


  if ( $dateFrom != '' ) {
    if( $dateTo != '' ){
      $Criteria->add(
        $Criteria->getNewCriterion(
          AppCacheViewPeer::DEL_DELEGATE_DATE,
          $dateFrom, Criteria::GREATER_EQUAL
        )->addAnd($Criteria->getNewCriterion(
          AppCacheViewPeer::DEL_DELEGATE_DATE,
          $dateTo, Criteria::LESS_EQUAL
        ))
      );
      $CriteriaCount->add(
        $CriteriaCount->getNewCriterion(
          AppCacheViewPeer::DEL_DELEGATE_DATE,
          $dateFrom, Criteria::GREATER_EQUAL
        )->addAnd($Criteria->getNewCriterion(
          AppCacheViewPeer::DEL_DELEGATE_DATE,
          $dateTo, Criteria::LESS_EQUAL
        ))
      );
    } else {
      $Criteria->add      (AppCacheViewPeer::DEL_DELEGATE_DATE, $dateFrom, Criteria::GREATER_EQUAL );
      $CriteriaCount->add (AppCacheViewPeer::DEL_DELEGATE_DATE, $dateFrom, Criteria::GREATER_EQUAL );
    }
  } else if ( $dateTo != '' ) {
    $Criteria->add      (AppCacheViewPeer::DEL_DELEGATE_DATE, $dateTo, Criteria::LESS_EQUAL );
    $CriteriaCount->add (AppCacheViewPeer::DEL_DELEGATE_DATE, $dateTo, Criteria::LESS_EQUAL );
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
    //$Criteria->add      (AppCacheViewPeer::APP_TITLE, '%' . $search . '%', Criteria::LIKE );
    //$CriteriaCount->add (AppCacheViewPeer::APP_TITLE, '%' . $search . '%', Criteria::LIKE );
    
    $Criteria->add(
      $Criteria->getNewCriterion(
        AppCacheViewPeer::APP_TITLE, '%' . $search . '%', Criteria::LIKE
      )->addOr($Criteria->getNewCriterion(
        AppCacheViewPeer::APP_TAS_TITLE, '%' . $search . '%', Criteria::LIKE
      )->addOr($Criteria->getNewCriterion(
        AppCacheViewPeer::APP_NUMBER, '%' . $search . '%', Criteria::LIKE
      ))
    ));

    $CriteriaCount->add(
      $CriteriaCount->getNewCriterion(
        AppCacheViewPeer::APP_TITLE, '%' . $search . '%', Criteria::LIKE
      )->addOr($CriteriaCount->getNewCriterion(
        AppCacheViewPeer::APP_TAS_TITLE, '%' . $search . '%', Criteria::LIKE
      )->addOr($CriteriaCount->getNewCriterion(
        AppCacheViewPeer::APP_NUMBER, '%' . $search . '%', Criteria::LIKE
      ))
    ));
  }

  //here we count how many records exists for this criteria.
  //BUT there are some special cases, and if we dont optimize them the server will crash.
  $doCountAlreadyExecuted = false;
  //case 1. when the SEARCH action is selected and none filter, search criteria is defined, 
  //we need to count using the table APPLICATION, because APP_CACHE_VIEW takes 3 seconds
  
  if ( $action == 'search' && $filter == '' && $search == '' && $process == '' && $status == '' && $dateFrom == '' && $dateTo == '') {
  	$totalCount = $oAppCache->getSearchAllCount();
    $doCountAlreadyExecuted = true;
  }
  
  if ( $doCountAlreadyExecuted == false ) {
    // in the case of reassign the distinct attribute shows a diferent count result comparing to the
    // original list
    if ($action == 'to_reassign' || $action == 'todo'){
      $distinct = false;
    } else{
      $distinct = true;
    }

    $totalCount = AppCacheViewPeer::doCount( $CriteriaCount, $distinct );

  }
      
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

  // getting the casesList configuration record
  // getting the additional table name based in the id saved in the configuration
  // record
  if (isset($confCasesList['PMTable'])&&!empty($confCasesList['PMTable'])) {
    $oAdditionalTables = new AdditionalTables();
    $oAdditionalTables = AdditionalTablesPeer::retrieveByPK($confCasesList['PMTable']);
    $tableName         = $oAdditionalTables->getAddTabName();
    // getting the default fields list
    $defaultFields     = $oAppCache->getDefaultFields();

    // so if the fields are not in the default list those are pm fields
      foreach($confCasesList['second']['data'] as $fieldData){
        // assembling the query based in the configuration array
        if (!in_array($fieldData['name'],$defaultFields)){
          $fieldName = $tableName.'.'.$fieldData['name'];
          $Criteria->addSelectColumn (  $fieldName );
        }
      }
    // adding the join instruction to the pmTable
    $Criteria->addJoin(AppCacheViewPeer::APP_UID, $tableName.'.APP_UID', Criteria::LEFT_JOIN);
  }
  //execute the query      
  $oDataset = AppCacheViewPeer::doSelectRS($Criteria);
  $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
  $oDataset->next();
      
  $result = array();
  $result['totalCount'] = $totalCount;
  $rows = array();
  $index = $start;
  while($aRow = $oDataset->getRow()){
    if( isset($aRow['APP_STATUS']) ){
      $aRow['APP_STATUS'] = G::LoadTranslation("ID_{$aRow['APP_STATUS']}");
    }
    
    $rows[] = $aRow;
    $oDataset->next();
  }
  $result['data'] = $rows;
//print the result in json format
  print G::json_encode( $result ) ;
  
  }
  catch ( Exception $e ) {
  	$msg = array ( 'error' => $e->getMessage() );
    print G::json_encode( $msg ) ;
  }      


  
 //TODO: Encapsulates these and another default generation functions inside a class
  /**
   * generate all the default fields
   * @return Array $fields
   */
   function setDefaultFields() {
     $fields = array();
     $fields['APP_UID']                 = array( 'name' => 'APP_UID'    ,             'fieldType' => 'key',         'label' => G::loadTranslation('ID_CASESLIST_APP_UID'),                'width' => 80,  'align' => 'left');
     $fields['DEL_INDEX']               = array( 'name' => 'DEL_INDEX'  ,             'fieldType' => 'key' ,        'label' => G::loadTranslation('ID_CASESLIST_DEL_INDEX')  ,            'width' => 50,  'align' => 'left');
     $fields['APP_NUMBER']              = array( 'name' => 'APP_NUMBER' ,             'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_NUMBER') ,            'width' => 80,  'align' => 'left');
     $fields['APP_TITLE']               = array( 'name' => 'APP_TITLE'  ,             'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_TITLE')  ,            'width' => 140, 'align' => 'left');
     $fields['APP_PRO_TITLE']           = array( 'name' => 'APP_PRO_TITLE'  ,         'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_PRO_TITLE') ,         'width' => 140, 'align' => 'left');
     $fields['APP_TAS_TITLE']           = array( 'name' => 'APP_TAS_TITLE'  ,         'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_TAS_TITLE') ,         'width' => 140, 'align' => 'left');
     $fields['APP_DEL_PREVIOUS_USER']   = array( 'name' => 'APP_DEL_PREVIOUS_USER'  , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_DEL_PREVIOUS_USER') , 'width' => 120, 'align' => 'left');
     $fields['APP_CURRENT_USER']        = array( 'name' => 'APP_CURRENT_USER'       , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_CURRENT_USER')  ,     'width' => 120, 'align' => 'left');
     $fields['DEL_TASK_DUE_DATE']       = array( 'name' => 'DEL_TASK_DUE_DATE'      , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_DEL_TASK_DUE_DATE') ,     'width' => 100, 'align' => 'left');
     $fields['APP_UPDATE_DATE']         = array( 'name' => 'APP_UPDATE_DATE'        , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_UPDATE_DATE') ,       'width' => 100, 'align' => 'left');
     $fields['DEL_PRIORITY']            = array( 'name' => 'DEL_PRIORITY'           , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_DEL_PRIORITY')    ,       'width' => 80,  'align' => 'left');
     $fields['APP_STATUS']              = array( 'name' => 'APP_STATUS'             , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_STATUS') ,            'width' => 80,  'align' => 'left');
     $fields['APP_FINISH_DATE']         = array( 'name' => 'APP_FINISH_DATE'        , 'fieldType' => 'case field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_FINISH_DATE') ,       'width' => 100, 'align' => 'left');
     $fields['APP_DELAY_UID']           = array( 'name' => 'APP_DELAY_UID'          , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_DELAY_UID') ,       'width' => 100, 'align' => 'left');
     $fields['APP_THREAD_INDEX']        = array( 'name' => 'APP_THREAD_INDEX'       , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_THREAD_INDEX') ,       'width' => 100, 'align' => 'left');
     $fields['APP_DEL_INDEX']           = array( 'name' => 'APP_DEL_INDEX'          , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_DEL_INDEX') ,       'width' => 100, 'align' => 'left');
     $fields['APP_TYPE']                = array( 'name' => 'APP_TYPE'               , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_TYPE') ,       'width' => 100, 'align' => 'left');
     $fields['APP_DELEGATION_USER']     = array( 'name' => 'APP_DELEGATION_USER'    , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_DELEGATION_USER') ,       'width' => 100, 'align' => 'left');
     $fields['APP_ENABLE_ACTION_USER']  = array( 'name' => 'APP_ENABLE_ACTION_USER' , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_ENABLE_ACTION_USER') ,       'width' => 100, 'align' => 'left');
     $fields['APP_ENABLE_ACTION_DATE']  = array( 'name' => 'APP_ENABLE_ACTION_DATE' , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_AAPP_ENABLE_ACTION_DATE') ,       'width' => 100, 'align' => 'left');
     $fields['APP_DISABLE_ACTION_USER'] = array( 'name' => 'APP_DISABLE_ACTION_USER', 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_DISABLE_ACTION_USER') ,       'width' => 100, 'align' => 'left');
     $fields['APP_DISABLE_ACTION_DATE'] = array( 'name' => 'APP_DISABLE_ACTION_DATE', 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_DISABLE_ACTION_DATE') ,       'width' => 100, 'align' => 'left');
     $fields['APP_AUTOMATIC_DISABLED_DATE'] = array( 'name' => 'APP_AUTOMATIC_DISABLED_DATE' , 'fieldType' => 'delay field' , 'label' => G::loadTranslation('ID_CASESLIST_APP_AUTOMATIC_DISABLED_DATE') ,       'width' => 100, 'align' => 'left');
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
        $fields = setDefaultFields();
        $rows[] = $fields['APP_UID'];
        $rows[] = $fields['DEL_INDEX'];
        $rows[] = $fields['APP_NUMBER'];
        $rows[] = $fields['APP_TITLE'];
        $rows[] = $fields['APP_PRO_TITLE'];
        $rows[] = $fields['APP_TAS_TITLE'];
        $rows[] = $fields['APP_DEL_PREVIOUS_USER'];
        $rows[] = $fields['DEL_TASK_DUE_DATE'];
        $rows[] = $fields['APP_UPDATE_DATE'];
        $rows[] = $fields['DEL_PRIORITY'];

        break;

      case 'draft' :    //#, Case, task, process, due date, Last Modify, Priority },
        $fields = setDefaultFields();
        $rows[] = $fields['APP_UID'];
        $rows[] = $fields['DEL_INDEX'];
        $rows[] = $fields['APP_NUMBER'];
        $rows[] = $fields['APP_TITLE'];
        $rows[] = $fields['APP_PRO_TITLE'];
        $rows[] = $fields['APP_TAS_TITLE'];
        $rows[] = $fields['DEL_TASK_DUE_DATE'];
        $rows[] = $fields['APP_UPDATE_DATE'];
        $rows[] = $fields['DEL_PRIORITY'];

        break;
      case 'sent' : // #, Case, task, process, current user, sent by, Last Modify, Status
        $fields = setDefaultFields();
        $rows[] = $fields['APP_UID'];
        $rows[] = $fields['DEL_INDEX'];
        $rows[] = $fields['APP_NUMBER'];
        $rows[] = $fields['APP_TITLE'];
        $rows[] = $fields['APP_PRO_TITLE'];
        $rows[] = $fields['APP_TAS_TITLE'];
        $rows[] = $fields['APP_DEL_PREVIOUS_USER'];
        $rows[] = $fields['APP_CURRENT_USER'];
        $rows[] = $fields['APP_UPDATE_DATE'];
        $rows[] = $fields['APP_STATUS'];

        break;
      case 'unassigned' :  //#, Case, task, process, completed by user, finish date
        $fields = setDefaultFields();
        $rows[] = $fields['APP_UID'];
        $rows[] = $fields['DEL_INDEX'];
        $rows[] = $fields['APP_NUMBER'];
        $rows[] = $fields['APP_TITLE'];
        $rows[] = $fields['APP_PRO_TITLE'];
        $rows[] = $fields['APP_TAS_TITLE'];
        $rows[] = $fields['APP_DEL_PREVIOUS_USER'];
        $rows[] = $fields['APP_UPDATE_DATE'];

        break;
      case 'paused' : //#, Case, task, process, sent by
        $fields = setDefaultFields();
        $rows[] = $fields['APP_UID'];
        $rows[] = $fields['DEL_INDEX'];
        $rows[] = $fields['APP_NUMBER'];
        $rows[] = $fields['APP_TITLE'];
        $rows[] = $fields['APP_PRO_TITLE'];
        $rows[] = $fields['APP_TAS_TITLE'];
        $rows[] = $fields['APP_DEL_PREVIOUS_USER'];
        $rows[] = $fields['APP_UPDATE_DATE'];
        $rows[] = $fields['APP_THREAD_INDEX'];
        $rows[] = $fields['APP_DEL_INDEX'];

        break;
      case 'completed' : //#, Case, task, process, completed by user, finish date
        $fields = setDefaultFields();
        $rows[] = $fields['APP_UID'];
        $rows[] = $fields['DEL_INDEX'];
        $rows[] = $fields['APP_NUMBER'];
        $rows[] = $fields['APP_TITLE'];
        $rows[] = $fields['APP_PRO_TITLE'];
        $rows[] = $fields['APP_TAS_TITLE'];
        $rows[] = $fields['APP_DEL_PREVIOUS_USER'];
        $rows[] = $fields['APP_UPDATE_DATE'];

        break;

      case 'cancelled' : //#, Case, task, process, due date, Last Modify
        $fields = setDefaultFields();
        $rows[] = $fields['APP_UID'];
        $rows[] = $fields['DEL_INDEX'];
        $rows[] = $fields['APP_NUMBER'];
        $rows[] = $fields['APP_TITLE'];
        $rows[] = $fields['APP_PRO_TITLE'];
        $rows[] = $fields['APP_TAS_TITLE'];
        $rows[] = $fields['APP_DEL_PREVIOUS_USER'];
        $rows[] = $fields['APP_UPDATE_DATE'];

        
        break;

      case 'to_revise' : //#, Case, task, process, due date, Last Modify
        $fields = setDefaultFields();
        $rows[] = $fields['APP_UID'];
        $rows[] = $fields['APP_NUMBER'];
        $rows[] = $fields['APP_TITLE'];
        $rows[] = $fields['APP_PRO_TITLE'];
        $rows[] = $fields['APP_TAS_TITLE'];
        $rows[] = $fields['APP_DEL_PREVIOUS_USER'];
        $rows[] = $fields['APP_CURRENT_USER'];
        $rows[] = $fields['DEL_PRIORITY'];
        $rows[] = $fields['APP_STATUS'];


        break;

      case 'to_reassign' : //#, Case, task, process, due date, Last Modify
        $fields = setDefaultFields();
        $rows[] = $fields['APP_UID'];
        $rows[] = $fields['APP_NUMBER'];
        $rows[] = $fields['APP_TITLE'];
        $rows[] = $fields['APP_PRO_TITLE'];
        $rows[] = $fields['APP_TAS_TITLE'];
        $rows[] = $fields['APP_CURRENT_USER'];
        $rows[] = $fields['APP_DEL_PREVIOUS_USER'];
        $rows[] = $fields['APP_UPDATE_DATE'];
        $rows[] = $fields['APP_STATUS'];

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