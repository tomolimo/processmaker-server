<?php
  $callback = isset($_POST['callback']) ? $_POST['callback'] : 'stcCallback1001';
  $dir      = isset($_POST['dir'])      ? $_POST['dir']      : 'DESC';
  $sort     = isset($_POST['sort'])     ? $_POST['sort']     : '';
  $query    = isset($_POST['query'])    ? $_POST['query']    : '';
  $tabUid   = isset($_POST['table'])    ? $_POST['table']    : '';
  $action   = isset($_POST['action'])   ? $_POST['action']   : 'todo';
  $xaction  = isset($_POST['xaction'])  ? $_POST['xaction']  : 'read';
  
  try {
    G::LoadClass("BasePeer" );
    require_once ( "classes/model/Fields.php" );
    switch ( $xaction ) {
      case 'read'   : xActionRead(); break;
      case 'create' : xActionCreate(); break;
    }
    die;
  }
  catch ( Exception $e ) {
    print json_encode ( $e->getMessage() );
  }


  function xActionRead () {
    global $callback;
    global $dir;
    global $sort;
    global $query;
    global $tabUid;
    global $action;
    
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

    $index =  count($rows);

    while($aRow = $oDataset->getRow()){
      $aRow['index'] = ++$index;
      $aTempRow['name'] = $aRow['FLD_NAME'];
      $aTempRow['gridIndex'] = $aRow['index'];
      $aTempRow['fieldType'] = 'field from PM Table';
      $rows[] = $aTempRow;
      $oDataset->next();
    }
    $result['totalCount'] = count($rows);
    $result['data'] = $rows;
    $result['secondGrid'] = $rows;
    //$jsonResult['records'] = $result;
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
