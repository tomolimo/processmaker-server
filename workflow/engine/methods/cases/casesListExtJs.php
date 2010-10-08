<?php
  //get the action from GET or POST, default is todo
  $action   = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : 'todo');

  G::LoadClass ( "BasePeer" );
  G::LoadClass ( 'configuration' );
  require_once ( "classes/model/Fields.php" );
  require_once ( "classes/model/AppCacheView.php" );

  $oHeadPublisher   =& headPublisher::getSingleton();
  // oHeadPublisher->setExtSkin( 'xtheme-blue');

  //get the configuration for this action
  $conf = new Configurations();
  try {
    $confCasesList = $conf->getConfiguration('casesList',$action=='search'?'sent':$action );
  } 
  catch (Exception $e){
    $confCasesList = array();
  }

  // evaluates an action and the list that will be rendered
  $config = getAdditionalFields($action);
  $columns      = $config['caseColumns'];
  $readerFields = $config['caseReaderFields'];
  
  $userUid = ( isset($_SESSION['USER_LOGGED'] ) && $_SESSION['USER_LOGGED'] != '' ) ? $_SESSION['USER_LOGGED'] : null;
  $oAppCache = new AppCacheView();
  $oAppCache->confCasesList = $confCasesList;
  
// get the action based list
  switch ( $action ) {
    case 'draft' :
         $cProcess      = $oAppCache->getDraftListCriteria($userUid);
         $cStatus       = $oAppCache->getDraftListCriteria($userUid);
         break;
    case 'sent' :
         $cProcess      = $oAppCache->getSentListCriteria($userUid);
         $cStatus       = $oAppCache->getSentListCriteria($userUid);
         break;
    case 'search' :
         $cProcess      = $oAppCache->getSearchStatusCriteria(null);
         $cStatus       = $oAppCache->getSearchStatusCriteria(null);
         $cUsers        = $oAppCache->getSearchStatusCriteria(null);
         break;
    case 'selfservice' :
         $cProcess      = $oAppCache->getUnassignedListCriteria($userUid);
         $cStatus       = $oAppCache->getUnassignedListCriteria($userUid);
         break;
    case 'paused' :
         $cProcess      = $oAppCache->getPausedListCriteria($userUid);
         $cStatus       = $oAppCache->getPausedListCriteria($userUid);
         break;
    case 'todo' :
    default:
         $cProcess      = $oAppCache->getToDoListCriteria($userUid);
         $cStatus       = $oAppCache->getToDoListCriteria($userUid);
    break;
  }

  //get the processes for this user in this action
  $processes = array();
  $processes[] = array ( '', 'All processes' );
  $cProcess->clearSelectColumns ( );
  $cProcess->setDistinct();
  $cProcess->addSelectColumn ( AppCacheViewPeer::PRO_UID );
  $cProcess->addSelectColumn ( AppCacheViewPeer::APP_PRO_TITLE );
  //$cProcess->addAscendingOrderbyColumn ( AppCacheViewPeer::APP_PRO_TITLE );
  $oDataset = AppCacheViewPeer::doSelectRS($cProcess);
  $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
  $oDataset->next();
      
  while($aRow = $oDataset->getRow()){
    $processes[] = array ( $aRow['PRO_UID'], $aRow['APP_PRO_TITLE'] );
    $oDataset->next();
  }

  //get the status for this user in this action
  $status = array();
  $users = array();
  $status[] = array( '', 'All status' );
  if ( $action != 'todo' && $action != 'draft' ) {
    $cStatus = new Criteria('workflow');
    $cStatus->clearSelectColumns ( );
    $cStatus->setDistinct();
    $cStatus->addSelectColumn ( AppCacheViewPeer::APP_STATUS );
    //$cStatus->addSelectColumn ( AppCacheViewPeer::APP_PRO_TITLE );
    $oDataset = AppCacheViewPeer::doSelectRS($cStatus);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();
    while($aRow = $oDataset->getRow()){
      $status[] = array( $aRow['APP_STATUS'], G::LoadTranslation('ID_CASES_STATUS_'.$aRow['APP_STATUS'])  ); //here we can have a translation for the status ( the second param)
      $oDataset->next();
    }
  }

    //now users
  if ( $action == 'search' ) {
    $cUsers->clearSelectColumns ( );
    $cUsers->setDistinct();
    $cUsers->addSelectColumn ( AppCacheViewPeer::USR_UID );
    $cUsers->addSelectColumn ( AppCacheViewPeer::APP_CURRENT_USER );
    $oDataset = AppCacheViewPeer::doSelectRS($cUsers);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();
    while($aRow = $oDataset->getRow()){
      $users[] = array( $aRow['USR_UID'], $aRow['APP_CURRENT_USER'] );
      $oDataset->next();
    }

  }  
    
  $oHeadPublisher->assign( 'pageSize',      intval($config['rowsperpage']) ); //sending the page size
  $oHeadPublisher->assign( 'columns',       $columns ); //sending the columns to display in grid
  $oHeadPublisher->assign( 'readerFields',  $readerFields ); //sending the fields to get from proxy
  $oHeadPublisher->assign( 'action',        $action ); //sending the fields to get from proxy
  $oHeadPublisher->assign( 'PMDateFormat',  $config['dateformat'] ); //sending the fields to get from proxy
  $oHeadPublisher->assign( 'statusValues',  $status ); //sending the columns to display in grid
  $oHeadPublisher->assign( 'processValues', $processes); //sending the columns to display in grid
  $oHeadPublisher->assign( 'userValues',    $users); //sending the columns to display in grid
  
  $TRANSLATIONS = new stdClass();
  $TRANSLATIONS->LABEL_GRID_LOADING     = G::LoadTranslation('ID_CASES_LIST_GRID_LOADING');
  $TRANSLATIONS->LABEL_REFRESH          = G::LoadTranslation('ID_REFRESH_LABEL');
  $TRANSLATIONS->MESSAGE_REFRESH        = G::LoadTranslation('ID_REFRESH_MESSAGE');
  $TRANSLATIONS->LABEL_OPT_READ         = G::LoadTranslation('ID_OPT_READ');
  $TRANSLATIONS->LABEL_OPT_UNREAD       = G::LoadTranslation('ID_OPT_UNREAD');
  $TRANSLATIONS->LABEL_OPT_ALL          = G::LoadTranslation('ID_OPT_ALL');
  $TRANSLATIONS->LABEL_OPT_STARTED      = G::LoadTranslation('ID_OPT_STARTED');
  $TRANSLATIONS->LABEL_OPT_COMPLETED    = G::LoadTranslation('ID_OPT_COMPLETED');
  $TRANSLATIONS->LABEL_EMPTY_PROCESSES  = G::LoadTranslation('ID_EMPTY_PROCESSES');
  $TRANSLATIONS->LABEL_EMPTY_SEARCH     = G::LoadTranslation('ID_EMPTY_SEARCH');
  $TRANSLATIONS->LABEL_EMPTY_CASE       = G::LoadTranslation('ID_EMPTY_CASE');
  $TRANSLATIONS->LABEL_SEARCH           = G::LoadTranslation('ID_SEARCH');
  $TRANSLATIONS->LABEL_OPT_JUMP         = G::LoadTranslation('ID_OPT_JUMP');
  $TRANSLATIONS->LABEL_DISPLAY_ITEMS    = G::LoadTranslation('ID_DISPLAY_ITEMS');
  $TRANSLATIONS->LABEL_DISPLAY_OF       = G::LoadTranslation('ID_DISPLAY_OF');
  $TRANSLATIONS->LABEL_DISPLAY_EMPTY    = G::LoadTranslation('ID_DISPLAY_EMPTY');
  
  $oHeadPublisher->assign( 'TRANSLATIONS',   $TRANSLATIONS); //translations
  
  $oHeadPublisher->addExtJsScript('cases/casesList', false );    //adding a javascript file .js

  $oHeadPublisher->addContent( 'cases/casesListExtJs'); //adding a html file  .html.

  G::RenderPage('publish', 'extJs');
 
  // needing to improve the assembling of the configuration
  
  //maybe this getXX should be using the default fields in casesListSetup
  function getToDo() {
    $caseColumns = array ();
    $caseColumns[] = array( 'header' =>'#',          'dataIndex' => 'APP_NUMBER',        'width' => 45, 'align' => 'center');
    $caseColumns[] = array( 'header' =>'Case',       'dataIndex' => 'APP_TITLE',         'width' => 150 );
    $caseColumns[] = array( 'header' =>'Task',       'dataIndex' => 'APP_TAS_TITLE',     'width' => 120 );
    $caseColumns[] = array( 'header' =>'Process',    'dataIndex' => 'APP_PRO_TITLE',     'width' => 120 );
    $caseColumns[] = array( 'header' =>'Sent by',    'dataIndex' => 'APP_DEL_PREVIOUS_USER', 'width' => 90 );
    $caseColumns[] = array( 'header' =>'Due Date',   'dataIndex' => 'DEL_TASK_DUE_DATE', 'width' => 110);
    $caseColumns[] = array( 'header' =>'Last Modify', 'dataIndex' => 'APP_UPDATE_DATE',  'width' => 110 );
    $caseColumns[] = array( 'header' =>'Priority',   'dataIndex' => 'DEL_PRIORITY',      'width' => 50 );
    
    $caseReaderFields = array();
    $caseReaderFields[] = array( 'name' => 'APP_UID' );
    $caseReaderFields[] = array( 'name' => 'DEL_INDEX' );
    $caseReaderFields[] = array( 'name' => 'APP_NUMBER' );
    $caseReaderFields[] = array( 'name' => 'APP_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_PRO_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_TAS_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_DEL_PREVIOUS_USER' );
    $caseReaderFields[] = array( 'name' => 'DEL_TASK_DUE_DATE' );
    $caseReaderFields[] = array( 'name' => 'APP_UPDATE_DATE' );
    $caseReaderFields[] = array( 'name' => 'DEL_PRIORITY' );
    $caseReaderFields[] = array( 'name' => 'APP_FINISH_DATE' );

    $caseReaderFields[] = array( 'name' => 'APP_CURRENT_USER' );
    $caseReaderFields[] = array( 'name' => 'APP_STATUS' );
    
    return array ( 'caseColumns' => $caseColumns, 'caseReaderFields' => $caseReaderFields, 'rowsperpage' => 20, 'dateformat' => 'M d, Y' );  
  }
  
  function getDraft() {
    $caseColumns = array ();
    $caseColumns[] = array( 'header' =>'#',           'dataIndex' => 'APP_NUMBER',        'width' => 45, 'align' => 'center');
    $caseColumns[] = array( 'header' =>'Case',        'dataIndex' => 'APP_TITLE',         'width' => 150 );
    $caseColumns[] = array( 'header' =>'Task',        'dataIndex' => 'APP_TAS_TITLE',     'width' => 120 );
    $caseColumns[] = array( 'header' =>'Process',     'dataIndex' => 'APP_PRO_TITLE',     'width' => 120 );
    $caseColumns[] = array( 'header' =>'Due Date',    'dataIndex' => 'DEL_TASK_DUE_DATE', 'width' => 110);
    $caseColumns[] = array( 'header' =>'Last Modify', 'dataIndex' => 'APP_UPDATE_DATE',   'width' => 110 );
    $caseColumns[] = array( 'header' =>'Priority',    'dataIndex' => 'DEL_PRIORITY',      'width' => 50 );
    
    $caseReaderFields = array();
    $caseReaderFields[] = array( 'name' => 'APP_UID' );
    $caseReaderFields[] = array( 'name' => 'APP_NUMBER' );
    $caseReaderFields[] = array( 'name' => 'APP_STATUS' );
    $caseReaderFields[] = array( 'name' => 'DEL_INDEX' );
    $caseReaderFields[] = array( 'name' => 'APP_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_PRO_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_TAS_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_DEL_PREVIOUS_USER' );
    $caseReaderFields[] = array( 'name' => 'APP_CURRENT_USER' );
    $caseReaderFields[] = array( 'name' => 'DEL_TASK_DUE_DATE' );
    $caseReaderFields[] = array( 'name' => 'APP_UPDATE_DATE' );
    $caseReaderFields[] = array( 'name' => 'DEL_PRIORITY' );
    $caseReaderFields[] = array( 'name' => 'APP_STATUS' );
    $caseReaderFields[] = array( 'name' => 'APP_FINISH_DATE' );

    return array ( 'caseColumns' => $caseColumns, 'caseReaderFields' => $caseReaderFields, 'rowsperpage' => 20, 'dateformat' => 'M d, Y'  );
  }
  
  function getParticipated() {
    $caseColumns = array ();
    $caseColumns[] = array( 'header' =>'#',            'dataIndex' => 'APP_NUMBER',        'width' => 45, 'align' => 'center');
    $caseColumns[] = array( 'header' =>'Case',         'dataIndex' => 'APP_TITLE',         'width' => 150 );
    $caseColumns[] = array( 'header' =>'Task',         'dataIndex' => 'APP_TAS_TITLE',     'width' => 120 );
    $caseColumns[] = array( 'header' =>'Process',      'dataIndex' => 'APP_PRO_TITLE',     'width' => 120 );
    $caseColumns[] = array( 'header' =>'sent By',      'dataIndex' => 'APP_DEL_PREVIOUS_USER', 'width' => 90 );
    $caseColumns[] = array( 'header' =>'Current User', 'dataIndex' => 'APP_CURRENT_USER', 'width' => 90 );
    $caseColumns[] = array( 'header' =>'Last Modify',  'dataIndex' => 'APP_UPDATE_DATE',  'width' => 110 );
    $caseColumns[] = array( 'header' =>'Status',       'dataIndex' => 'APP_STATUS',      'width' => 50 );
    
    $caseReaderFields = array();
    $caseReaderFields[] = array( 'name' => 'APP_UID' );
    $caseReaderFields[] = array( 'name' => 'APP_NUMBER' );
    $caseReaderFields[] = array( 'name' => 'APP_STATUS' );
    $caseReaderFields[] = array( 'name' => 'DEL_INDEX' );
    $caseReaderFields[] = array( 'name' => 'APP_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_PRO_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_TAS_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_DEL_PREVIOUS_USER' );
    $caseReaderFields[] = array( 'name' => 'APP_CURRENT_USER' );
    $caseReaderFields[] = array( 'name' => 'DEL_TASK_DUE_DATE' );
    $caseReaderFields[] = array( 'name' => 'APP_UPDATE_DATE' );
    $caseReaderFields[] = array( 'name' => 'DEL_PRIORITY' );
    $caseReaderFields[] = array( 'name' => 'APP_STATUS' );
    $caseReaderFields[] = array( 'name' => 'APP_FINISH_DATE' );

    return array ( 'caseColumns' => $caseColumns, 'caseReaderFields' => $caseReaderFields, 'rowsperpage' => 20, 'dateformat' => 'M d, Y'  );
   }
  
  function getUnassigned() {
    $caseColumns = array ();
    $caseColumns[] = array( 'header' =>'#',           'dataIndex' => 'APP_NUMBER',        'width' => 45, 'align' => 'center');
    $caseColumns[] = array( 'header' =>'Case',        'dataIndex' => 'APP_TITLE',         'width' => 150 );
    $caseColumns[] = array( 'header' =>'Task',        'dataIndex' => 'APP_TAS_TITLE',     'width' => 120 );
    $caseColumns[] = array( 'header' =>'Process',     'dataIndex' => 'APP_PRO_TITLE',     'width' => 120 );
    $caseColumns[] = array( 'header' =>'Completed By User', 'dataIndex' => 'APP_CURRENT_USER',   'width' => 110 );
    $caseColumns[] = array( 'header' =>'Finish Date',    'dataIndex' => 'APP_FINISH_DATE',      'width' => 50 );

    $caseReaderFields = array();
    $caseReaderFields[] = array( 'name' => 'APP_UID' );
    $caseReaderFields[] = array( 'name' => 'APP_NUMBER' );
    $caseReaderFields[] = array( 'name' => 'APP_STATUS' );
    $caseReaderFields[] = array( 'name' => 'DEL_INDEX' );
    $caseReaderFields[] = array( 'name' => 'APP_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_PRO_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_TAS_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_DEL_PREVIOUS_USER' );
    $caseReaderFields[] = array( 'name' => 'APP_CURRENT_USER' );
    $caseReaderFields[] = array( 'name' => 'DEL_TASK_DUE_DATE' );
    $caseReaderFields[] = array( 'name' => 'APP_UPDATE_DATE' );
    $caseReaderFields[] = array( 'name' => 'DEL_PRIORITY' );
    $caseReaderFields[] = array( 'name' => 'APP_STATUS' );
    $caseReaderFields[] = array( 'name' => 'APP_FINISH_DATE' );

    return array ( 'caseColumns' => $caseColumns, 'caseReaderFields' => $caseReaderFields, 'rowsperpage' => 20, 'dateformat' => 'M d, Y'  );
  }

  function getPaused() {
    $caseColumns = array ();
    $caseColumns[] = array( 'header' =>'#',           'dataIndex' => 'APP_NUMBER',        'width' => 45, 'align' => 'center');
    $caseColumns[] = array( 'header' =>'Case',        'dataIndex' => 'APP_TITLE',         'width' => 150 );
    $caseColumns[] = array( 'header' =>'Task',        'dataIndex' => 'APP_TAS_TITLE',     'width' => 120 );
    $caseColumns[] = array( 'header' =>'Process',     'dataIndex' => 'APP_PRO_TITLE',     'width' => 120 );
    $caseColumns[] = array( 'header' =>'Sent By',     'dataIndex' => 'APP_DEL_PREVIOUS_USER', 'width' => 90 );

    $caseReaderFields = array();
    $caseReaderFields[] = array( 'name' => 'APP_UID' );
    $caseReaderFields[] = array( 'name' => 'APP_NUMBER' );
    $caseReaderFields[] = array( 'name' => 'APP_STATUS' );
    $caseReaderFields[] = array( 'name' => 'DEL_INDEX' );
    $caseReaderFields[] = array( 'name' => 'APP_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_PRO_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_TAS_TITLE' );
    $caseReaderFields[] = array( 'name' => 'APP_DEL_PREVIOUS_USER' );
    $caseReaderFields[] = array( 'name' => 'APP_CURRENT_USER' );
    $caseReaderFields[] = array( 'name' => 'DEL_TASK_DUE_DATE' );
    $caseReaderFields[] = array( 'name' => 'APP_UPDATE_DATE' );
    $caseReaderFields[] = array( 'name' => 'DEL_PRIORITY' );
    $caseReaderFields[] = array( 'name' => 'APP_STATUS' );
    $caseReaderFields[] = array( 'name' => 'APP_FINISH_DATE' );

    return array ( 'caseColumns' => $caseColumns, 'caseReaderFields' => $caseReaderFields, 'rowsperpage' => 20, 'dateformat' => 'M d, Y'  );
  }


  /**
   * loads the PM Table field list from the database based in an action parameter
   * then assemble the List of fields with these data, for the configuration in cases list.
   * @param  String $action
   * @return Array $config
   */
function getAdditionalFields($action){
global $confCasesList;
  $caseColumns = array();
  $caseReaderFields = array();

  if ( count($confCasesList)>1 ) {
    foreach($confCasesList['second']['data'] as $fieldData){
      if ( $fieldData['fieldType']!='key' ) {
//        $label = ($fieldData['fieldType']=='case field' ) ? G::loadTranslation('ID_CASESLIST_'.$fieldData['name']) : $fieldData['label'];
        $label = $fieldData['label'];
        $caseColumns[]      = array( 'header' => $label, 'dataIndex' => $fieldData['name'], 'width' => $fieldData['width'], 'align' => $fieldData['align'] );
        $caseReaderFields[] = array( 'name'   => $fieldData['name'] );
      }
    }
    return array ( 'caseColumns' => $caseColumns, 'caseReaderFields' => $caseReaderFields, 'rowsperpage' => $confCasesList['rowsperpage'], 'dateformat' => $confCasesList['dateformat'] );
  } 
  else {
    switch ( $action ) {
      case 'draft' :
        $config = getDraft();
      break;
      case 'search' :      
      case 'participated' :
        $config = getParticipated();
      break;
      case 'unassigned' :
        $config = getUnassigned();
      break;
      case 'paused' :
        $config = getPaused();
      break;
      case 'todo' :
      default : 
        $action = 'todo';
        $config = getToDo();
      break;
    }
    return $config;
  }
}

function defineTranslations(){

}
