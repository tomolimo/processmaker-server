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
      case 'read'         : xActionRead(); break;
      case 'applyChanges' : xActionApplyChanges(); break;
      case 'getFieldsFromPMTable' : xgetFieldsFromPMTable($tabUid); break;
      case 'create'       : xActionCreate(); break;
    }
    die;
  }
  catch ( Exception $e ) {
    print json_encode ( $e->getMessage() );
  }

  /*
  * this function return the default fields for a default case list
  * @param $action
  * @return an array with the default fields for an specific case list (action)
  */
  function getDefaultFields ( $action ) {
    $rows = array();
    switch ( $action ) {
      case 'todo' : // #, Case, task, process, sent by, due date, Last Modify, Priority
        $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '1', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '2', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '3', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '4', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '5', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_DEL_PREVIOUS_USER', 'gridIndex' =>  '6', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'DEL_TASK_DUE_DATE',     'gridIndex' =>  '7', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '8', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'DEL_PRIORITY',          'gridIndex' =>  '9', 'fieldType' => 'case field' );
        break;
      case 'draft' :    //#, Case, task, process, due date, Last Modify, Priority },
        $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '1', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '2', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '3', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '4', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '5', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'DEL_TASK_DUE_DATE',     'gridIndex' =>  '6', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '7', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'DEL_PRIORITY',          'gridIndex' =>  '8', 'fieldType' => 'case field' );
        break;
      case 'sent' : // #, Case, task, process, current user, sent by, Last Modify, Status
        $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '1', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '2', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '3', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '4', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '5', 'fieldType' => 'case field' );        
        $rows[] = array( 'name' => 'APP_CURRENT_USER',      'gridIndex' =>  '6', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_DEL_PREVIOUS_USER', 'gridIndex' =>  '7', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '8', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_STATUS',            'gridIndex' =>  '9', 'fieldType' => 'case field' );
        break;
      case 'unassigned' :  //#, Case, task, process, completed by user, finish date
        $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '1', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '2', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '3', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '4', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '5', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_DEL_PREVIOUS_USER', 'gridIndex' =>  '6', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '7', 'fieldType' => 'case field' );
        break;
      case 'paused' : //#, Case, task, process, sent by
        $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '1', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '2', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '3', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '4', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '5', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_DEL_PREVIOUS_USER', 'gridIndex' =>  '6', 'fieldType' => 'case field' );
//        $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  'x', 'fieldType' => '9' );
//        $rows[] = array( 'name' => 'APP_STATUS',            'gridIndex' =>  'x', 'fieldType' => '2' );
        break;
      case 'completed' : //#, Case, task, process, completed by user, finish date
        $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '1', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '2', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '3', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '4', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '5', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '6', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_CURRENT_USER',      'gridIndex' =>  '7', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '8', 'fieldType' => 'case field' );
        break;
      case 'cancelled' : //#, Case, task, process, due date, Last Modify
        $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '1', 'fieldType' => 'key' );
        $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '2', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '3', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '4', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '5', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'DEL_TASK_DUE_DATE',     'gridIndex' =>  '6', 'fieldType' => 'case field' );
        $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '7', 'fieldType' => 'case field' );
//        $rows[] = array( 'name' => 'APP_DEL_PREVIOUS_USER', 'gridIndex' =>  '7', 'fieldType' => '7' );
        break;
    }
    return $rows;
  }

  /*
  * set the generic Json Response, using two array for the grid stores and a string for the pmtable name
  * @param string $pmtable
  * @param array $first
  * @param array $second
  * @return $response a json string
  */
  function genericJsonResponse($pmtable, $first, $second) {
/*
  	//normalize first array
    foreach ( $first as $key => $val) {
    	if ( count($first) == 1 ) {
    		$first[ $key ] = array ( 'name' => $val, 'fieldType' => 'case field', 'gridIndex' => $key );
    	}
    };
*/
    $firstGrid['totalCount']  = count($first);
    $firstGrid['data']        = $first;
    $secondGrid['totalCount'] = count($second);
    $secondGrid['data']       = $second;    
    $result = array();
    $result['first']  = $firstGrid;
    $result['second'] = $secondGrid;
    $result['PMTable'] = isset($pmtable) ? $pmtable : '';
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
      $validConfig = ( /* isset($confCasesList['PMTable']) && */ isset($confCasesList['first']) && isset($confCasesList['second']) );
    }
    else
      $validConfig = false;
    
    if ( ! $validConfig ) {
    	$rows = getDefaultFields( $action);
    	$result = genericJsonResponse( '', array(), $rows );
      $conf->saveObject($result,'casesList',$action,'','','');
      print json_encode( $result ) ;
  	}
  	else {
      print json_encode( $confCasesList ) ;
    }
    die;
    
    $oCriteria = new Criteria('workflow');

    $oCriteria->clearSelectColumns ( );
    $oCriteria->setDistinct();
    $oCriteria->addSelectColumn ( FieldsPeer::FLD_NAME );
    $oCriteria->addSelectColumn ( FieldsPeer::FLD_UID );
    $oCriteria->addSelectColumn ( FieldsPeer::FLD_INDEX );


    if ( $query != '' ) {
      $oCriteria->add (FieldsPeer::FLD_NAME, $query . '%', Criteria::LIKE);
    }
    $oCriteria->add (FieldsPeer::ADD_TAB_UID, $tabUid , CRITERIA::EQUAL );
    $oCriteria->add (FieldsPeer::FLD_NAME, 'APP_UID' , CRITERIA::NOT_EQUAL );

    if ( $sort != '' ) {
      if ( $dir == 'DESC' )
        $oCriteria->addDescendingOrderByColumn( $sort );
      else
        $oCriteria->addAscendingOrderByColumn( $sort );
    } 
    else {
      $oCriteria->addDescendingOrderByColumn('FLD_INDEX');
    }
    $oDataset = FieldsPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();

    $result = array();


    $index =  count($rows);

    while($aRow = $oDataset->getRow()){
      $aRow['index'] = ++$index;
      $aTempRow['name'] = $aRow['FLD_NAME'];
      $aTempRow['gridIndex'] = $aRow['index'];
      $aTempRow['fieldType'] = 'field from PM Table';
      $rows[] = $aTempRow;
      $oDataset->next();
    }
    
    $firstGrid['totalCount'] = count($rows);
    $firstGrid['data']       = $rows;
    $firstGrid['secondGrid'] = $rows;

    $rows = array();
    $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'fieldType' => 'key' );
    $rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '1', 'fieldType' => 'key' );
  
    $secondGrid['totalCount'] = count($rows);
    $secondGrid['data']       = $rows;
    $secondGrid['secondGrid'] = $rows;
    
    $result['first']  = $firstGrid;
    $result['second'] = $secondGrid;
    
    
    print json_encode( $result ) ;
    die;
  }

  function xActionApplyChanges() {
    global $conf;
  	global $action;
  	
  	//get values from json request
    $first   = json_decode(isset($_POST['first'])    ? $_POST['first']   : json_encode(array()));
    $second  = json_decode(isset($_POST['second'])   ? $_POST['second']  : json_encode(array()));
    $pmtable = isset($_POST['pmtable'])  ? $_POST['pmtable'] : '';
    
    //now apply validations
    //remove app_uid and del_index from both arrays
    foreach ( $first as $key => $val ) {
    	if ( $val == 'APP_UID' || $val == 'DEL_INDEX' ) unset ( $first[$key] );
    }
    foreach ( $second as $key => $val ) {
    	if ( $val == 'APP_UID' || $val == 'DEL_INDEX' ) unset ( $second[$key] );
    }

    //adding the key fields to second array //put APP_UID and DEL_INDEX like first fields
    array_unshift ($second, 'DEL_INDEX' );    
    array_unshift ($second, 'APP_UID' );    
        
    
    //get complete domain of fields, if there is a pmtable, get all values from that pmtable.
    $defaults = getDefaultFields ( $action );
    
    //normalize tables, before work with them
    $i = 0;
    foreach ( $first as $key => $val ) {
    	$fieldType = 'PM Table';
    	foreach ( $defaults as $defkey => $defval ) {
    		if ( $defval['name'] == $val ) $fieldType = $defval['fieldType'];
    	}
    	//if ( $val == 'APP_UID' || $val == 'DEL_INDEX' ) $fieldType = 'key';
    	$newFirst[$i] = array ( 'name' => $val, 'gridIndex' => $i, 'fieldType' => $fieldType );
    	$i++;
    }
    $i = 0;
    foreach ( $second as $key => $val ) {
    	$fieldType = 'PM Table';
    	foreach ( $defaults as $defkey => $defval ) {
    		if ( $defval['name'] == $val ) $fieldType = $defval['fieldType'];
    	}
    	//if ( $val == 'APP_UID' || $val == 'DEL_INDEX' ) $fieldType = 'key';
    	$newSecond[$i] = array ( 'name' => $val, 'gridIndex' => $i, 'fieldType' => $fieldType, 'align' => 'left', 'label' => $val, 'width' => 90 );
    	$i++;
    }
    
    //get back the result in json 
    $result = genericJsonResponse( $pmtable, $newFirst, $newSecond) ;
    $conf->saveObject($result,'casesList',$action,'','','');
    print  json_encode( $result);
    die;
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
    die;
  }
  
  function xActionCreate () {
    global $callback;
    global $dir;
    global $sort;
    global $query;
    global $tabUid;
    global $action;

    $data  = isset($_POST['data'])      ? json_decode($_POST['data'] )     : array();
    $data->gridIndex = 11;
    //$data->success = true;
    $rows = array();
    
    $result['success']    = true;
    $result['totalCount']  =  0;
    $result['message'] = 'hola';
//    $result['gridIndex'] = 11;
    $result['data']    = $data;
    
    $result['action'] = $action;
    $result['xaction'] = 'create';
    
    print json_encode( $result ) ;
    die;
  }
