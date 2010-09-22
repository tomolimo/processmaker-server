<?php
  $callback = isset($_POST['callback']) ? $_POST['callback'] : 'stcCallback1001';
  $dir      = isset($_POST['dir'])      ? $_POST['dir']      : 'DESC';
  $sort     = isset($_POST['sort'])     ? $_POST['sort']     : '';
  $query    = isset($_POST['query'])    ? $_POST['query']    : '';
  $tabUid   = isset($_POST['table'])    ? $_POST['table']    : '';
  $action   = isset($_POST['action'])   ? $_POST['action']   : 'todo';
  $xaction  = isset($_POST['xaction'])  ? $_POST['xaction']  : 'applyChanges';

  try {
  	//load classes
    G::LoadClass("BasePeer" );
    G::LoadClass('configuration');
    require_once ( "classes/model/Fields.php" );

    //load the current configuration for this action, this configuration will be used later
    $conf = new Configurations();
    $confCasesList = $conf->loadObject('casesList',$action,'','','');
    
    switch ( $xaction ) {
      case 'read'                 : xActionRead(); break;
      case 'reset'                : xActionReset(); break;
      case 'applyChanges'         : xActionApplyChanges(); break;
      case 'getFieldsFromPMTable' : xgetFieldsFromPMTable($tabUid); break;
    }
    die;
  }
  catch ( Exception $e ) {
    print json_encode ( $e->getMessage() );
  }

  function defaultField ( $name, $index ) {
  	$label     = $name;  //this could be changed to an array with translation of each field.
    $fieldType = ( $name == 'APP_UID' || $name == 'DEL_INDEX' ) ? 'key' : 'case field' ;
    $row = array( 'name' => $name, 'gridIndex' =>  $index, 'fieldType' => $fieldType, 'label' => $label, 'width' => 100, 'align' => 'left' );
    return $row;
  }

  /**
   * set the default fields
   * @return Array
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

  /*
  * get current Case List config for this workspace,
  * if there are no config for this workspace a default workspace will be generated for this list.
  * @param string $action ( todo, draft, sent, etc )
  * @return $response a json object with:
  *   - first
  *   - second
  *   - pmtable
  */
  function xActionRead () {
    global $callback;
    global $dir;
    global $sort;
    global $query;
    global $tabUid;
    global $action;
    global $conf;
    global $confCasesList;

    if ( is_array($confCasesList) ) {
      $validConfig = ( isset($confCasesList['first']) && isset($confCasesList['second']) );
    }
    else
      $validConfig = false;
    
    if ( ! $validConfig ) {
    	$rows = getDefaultFields( $action);
    	$result = genericJsonResponse( '', array(), $rows , 20, '' );
      $conf->saveObject($result,'casesList',$action,'','','');
      print json_encode( $result ) ;
  	}
  	else {
      print json_encode( $confCasesList ) ;
    }
  }

  /*
  * reset current Case List config with defaults,
  * @param string $action ( todo, draft, sent, etc )
  * @return $response a json object with:
  *   - first
  *   - second
  *   - pmtable
  */
  function xActionReset () {
    global $callback;
    global $dir;
    global $sort;
    global $action;
    global $conf;
    global $confCasesList;

  	$rows = getDefaultFields( $action);
  	$result = genericJsonResponse( '', array(), $rows , 20, '');
    $conf->saveObject($result,'casesList',$action,'','','');
    print json_encode( $result ) ;    
  }

  /*
  * get, normalize and save the casesList setup send via _POST
  * @param string $action ( todo, draft, sent, etc )
  * @return $response a json object with last and saved changes in order to refresh data in grid:
  *   - first
  *   - second
  *   - pmtable
  */
  function xActionApplyChanges() {
    global $conf;
  	global $action;
  	
  	//get values from json request
    $first   = json_decode(isset($_POST['first'])    ? $_POST['first']   : json_encode(array()));
    $second  = json_decode(isset($_POST['second'])   ? $_POST['second']  : json_encode(array()));
    $pmtable = isset($_POST['pmtable'])  ? $_POST['pmtable'] : '';
    $rowspp  = isset($_POST['rowsperpage']) ? $_POST['rowsperpage'] : 20;
    if ( intval($rowspp) < 5) $rowspp = 20;
    $dateFormat = isset($_POST['dateformat']) && $_POST['dateformat'] != '' ? $_POST['dateformat'] : 'M d, Y';
    
    //now apply validations
    //remove app_uid and del_index from both arrays
    foreach ( $first as $key => $val ) {
    	if ( $val == 'APP_UID' || $val == 'DEL_INDEX' ) unset ( $first[$key] );
    }
    foreach ( $second as $key => $val ) {
    	if ( $val->name == 'APP_UID' || $val->name == 'DEL_INDEX' ) unset ( $second[$key] );
    }

    //adding the key fields to second array //put APP_UID and DEL_INDEX like first fields
    $appUid = new stdClass();
    $appUid->name = 'APP_UID';
    $appUid->gridIndex = 0;
    $appUid->label = '';
    $appUid->align = '';
    $appUid->width = 1;

    $delIndex = new stdClass();
    $delIndex->name = 'DEL_INDEX';
    $delIndex->gridIndex = 0;
    $delIndex->label = '';
    $delIndex->align = '';
    $delIndex->width = 1;

    array_unshift ($second, $delIndex );    
    array_unshift ($second, $appUid );    
    
    //get complete domain of fields, if there is a pmtable, get all values from that pmtable.
    $defaults = getDefaultFields ( $action );
    
    //normalize tables, before work with them
    $i = 0;
    $newFirst = array();
    foreach ( $first as $key => $val ) {
    	$fieldType = 'PM Table';
    	foreach ( $defaults as $defkey => $defval ) {
    		if ( $defval['name'] == $val ) $fieldType = $defval['fieldType'];
    	}
    	$newFirst[$i] = array ( 'name' => $val, 'gridIndex' => $i, 'fieldType' => $fieldType );
    	$i++;
    }
    $i = 0;
    foreach ( $second as $key => $val ) {
    	$fieldType  = 'PM Table';
    	$fieldName  = $val->name;
    	$fieldLabel = isset($val->label) && strlen(trim($val->label)) > 1 ? $val->label : $fieldName;
    	$fieldAlign = isset($val->align) && strlen(trim($val->align)) > 1 ? $val->align : 'left';
    	$fieldWidth = isset($val->width) && strlen(trim($val->width)) > 1 ? $val->width : 100;
    	foreach ( $defaults as $defkey => $defval ) {
    		if ( $defval['name'] == $fieldName ) $fieldType = $defval['fieldType'];
    	}
    	$newSecond[$i] = array ( 'name' => $fieldName, 'gridIndex' => $i, 'fieldType' => $fieldType, 'align' => $fieldAlign, 'label' => $fieldLabel, 'width' => $fieldWidth);
    	$i++;
    }
    
    //get back the result in json 
    $result = genericJsonResponse( $pmtable, $newFirst, $newSecond, $rowspp, $dateFormat ) ;
    $conf->saveObject($result,'casesList',$action,'','','');
    print  json_encode( $result);
  }
  
  
  
  function xgetFieldsFromPMTable( $tabUid ) {
    $rows = array();
    $result = array();
//    $rows[] = array ( 'name' => 'val 1', 'gridIndex' => '21', 'fieldType' => 'PM Table' );
//    $rows[] = array ( 'name' => 'val 2', 'gridIndex' => '22', 'fieldType' => 'PM Table' );
//    $rows[] = array ( 'name' => 'val 3', 'gridIndex' => '23', 'fieldType' => 'PM Table' );
  	
    //$result['success']    = true;
    //$result['totalCount']  =  count($rows);
    $oCriteria = new Criteria('workflow');
    $oCriteria->clearSelectColumns ( );
    $oCriteria->setDistinct();
    $oCriteria->addSelectColumn ( FieldsPeer::FLD_NAME );
    $oCriteria->addSelectColumn ( FieldsPeer::FLD_UID );
    $oCriteria->addSelectColumn ( FieldsPeer::FLD_INDEX );

    $oCriteria->add (FieldsPeer::ADD_TAB_UID, $tabUid , CRITERIA::EQUAL );
    $oCriteria->add (FieldsPeer::FLD_NAME, 'APP_UID' , CRITERIA::NOT_EQUAL );
    $oCriteria->addDescendingOrderByColumn('FLD_INDEX');

    $oDataset = FieldsPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();
    $index =  count($rows);

    while($aRow = $oDataset->getRow()){
      $aRow['index'] = ++$index;
      $aTempRow['name'] = $aRow['FLD_NAME'];
      $aTempRow['gridIndex'] = $aRow['index'];
      $aTempRow['fieldType'] = 'PM Table';
      $rows[] = $aTempRow;
      $oDataset->next();
    }

    $result['data']    = $rows;
    print json_encode( $result ) ;
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