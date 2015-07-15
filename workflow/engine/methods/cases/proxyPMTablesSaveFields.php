<?php

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

G::LoadSystem('inputfilter');
$filter = new InputFilter();

$callback = isset($_POST['callback']) ? $_POST['callback'] : 'stcCallback1001';
$callback = $filter->xssFilterHard($callback);
$dir      = isset($_POST['dir'])    ? $_POST['dir']    : 'DESC';
$dir      = $filter->xssFilterHard($dir);
$sort     = isset($_POST['sort'])   ? $_POST['sort']   : '';
$sort     = $filter->xssFilterHard($sort);
$query    = isset($_POST['query'])  ? $_POST['query']  : '';
$query    = $filter->xssFilterHard($query);
$tabUid   = isset($_POST['table'])  ? $_POST['table']  : '';
$tabUid   = $filter->xssFilterHard($tabUid);
$action   = isset($_POST['action']) ? $_POST['action'] : 'todo';
$action   = $filter->xssFilterHard($action);

try {
    G::LoadClass("BasePeer" );
    require_once ( "classes/model/Fields.php" );
    //$sUIDUserLogged = $_SESSION['USER_LOGGED'];
    $oCriteria = new Criteria('workflow');
    $oCriteria->clearSelectColumns();
    $oCriteria->setDistinct();
    $oCriteria->addSelectColumn (FieldsPeer::FLD_NAME);
    $oCriteria->addSelectColumn (FieldsPeer::FLD_UID);
    $oCriteria->addSelectColumn (FieldsPeer::FLD_INDEX);

    if ($query != '') {
        $oCriteria->add (FieldsPeer::FLD_NAME, $query . '%', Criteria::LIKE);
    }
    //$oCriteria->addJoin(AdditionalTablesPeer::ADD_TAB_UID, FieldsPeer::ADD_TAB_UID);
    $oCriteria->add (FieldsPeer::ADD_TAB_UID, $tabUid , CRITERIA::EQUAL );
    $oCriteria->add (FieldsPeer::FLD_NAME, 'APP_UID' , CRITERIA::NOT_EQUAL );
    //$oCriteria->add (AppCacheViewPeer::APP_STATUS, "TO_DO" , CRITERIA::EQUAL );
    //$oCriteria->add (AppCacheViewPeer::USR_UID, $sUIDUserLogged);
    //$totalCount = AppCacheViewPeer::doCount( $Criteria );
    //if ( isset($limit) ) $oCriteria->setLimit  ( $limit );
    //if ( isset($start) ) $oCriteria->setOffset ( $start );
    if ($sort != '') {
        if ($dir == 'DESC') {
            $oCriteria->addDescendingOrderByColumn( $sort );
        } else {
            $oCriteria->addAscendingOrderByColumn( $sort );
        } //else {
        //    $oCriteria->addDescendingOrderByColumn('FLD_INDEX');
        //}
        $oDataset = FieldsPeer::doSelectRS($oCriteria);
        $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $oDataset->next();

        $result = array();
        $rows = array();
        switch ($action) {
            case 'todo':
                // #, Case, task, process, sent by, due date, Last Modify, Priority
                $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'column2' => '0' );
                $rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '3', 'column2' => '3' );
                $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '1', 'column2' => '1' );
                $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '4', 'column2' => '4' );
                $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '6', 'column2' => '6' );
                $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '5', 'column2' => '5' );
                $rows[] = array( 'name' => 'APP_DEL_PREVIOUS_USER', 'gridIndex' =>  '7', 'column2' => '7' );
                $rows[] = array( 'name' => 'DEL_TASK_DUE_DATE',     'gridIndex' =>  '8', 'column2' => '8' );
                $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '9', 'column2' => '9' );
                $rows[] = array( 'name' => 'DEL_PRIORITY',          'gridIndex' => '10', 'column2' =>'10' );
                break;
            case 'draft':
                //#, Case, task, process, due date, Last Modify, Priority },
                $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'column2' => '0' );
                $rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '3', 'column2' => '3' );
                $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '1', 'column2' => '1' );
                $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '4', 'column2' => '4' );
                $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '6', 'column2' => '6' );
                $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '5', 'column2' => '5' );
                $rows[] = array( 'name' => 'DEL_TASK_DUE_DATE',     'gridIndex' =>  '8', 'column2' => '8' );
                $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '9', 'column2' => '9' );
                $rows[] = array( 'name' => 'DEL_PRIORITY',          'gridIndex' => '10', 'column2' =>'10' );
                break;
            case 'sent':
                // #, Case, task, process, current user, sent by, Last Modify, Status
                $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'column2' => '0' );
                $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '1', 'column2' => '1' );
                $rows[] = array( 'name' => 'APP_STATUS',            'gridIndex' =>  '2', 'column2' => '2' );
                $rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '3', 'column2' => '3' );
                $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '4', 'column2' => '4' );
                $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '5', 'column2' => '5' );
                $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '6', 'column2' => '6' );
                $rows[] = array( 'name' => 'APP_DEL_PREVIOUS_USER', 'gridIndex' =>  '7', 'column2' => '7' );
                $rows[] = array( 'name' => 'DEL_TASK_DUE_DATE',     'gridIndex' =>  '8', 'column2' => '8' );
                $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '9', 'column2' => '9' );
                $rows[] = array( 'name' => 'DEL_PRIORITY',          'gridIndex' => '10', 'column2' =>'10' );
                break;
            case 'unassigned':
                $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'column2' => '0' );
                $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '1', 'column2' => '1' );
                //$rows[] = array( 'name' => 'APP_STATUS',            'gridIndex' =>  '2', 'column2' => '2' );
                //$rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '3', 'column2' => '3' );
                $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '2', 'column2' => '2' );
                $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '3', 'column2' => '3' );
                $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '4', 'column2' => '4' );
                $rows[] = array( 'name' => 'APP_DEL_PREVIOUS_USER', 'gridIndex' =>  '5', 'column2' => '5' );
                //$rows[] = array( 'name' => 'DEL_TASK_DUE_DATE',     'gridIndex' =>  '8', 'column2' => '8' );
                $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '6', 'column2' => '6' );
                //$rows[] = array( 'name' => 'DEL_PRIORITY',          'gridIndex' => '10', 'column2' =>'10' );
                break;
            case 'paused':
                $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'column2' => '0' );
                $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '1', 'column2' => '1' );
                //$rows[] = array( 'name' => 'APP_STATUS',            'gridIndex' =>  '2', 'column2' => '2' );
                //$rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '3', 'column2' => '3' );
                $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '2', 'column2' => '2' );
                $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '3', 'column2' => '3' );
                $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '4', 'column2' => '4' );
                $rows[] = array( 'name' => 'APP_DEL_PREVIOUS_USER', 'gridIndex' =>  '5', 'column2' => '5' );
                //$rows[] = array( 'name' => 'DEL_TASK_DUE_DATE',     'gridIndex' =>  '8', 'column2' => '8' );
                //$rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '9', 'column2' => '9' );
                //$rows[] = array( 'name' => 'DEL_PRIORITY',          'gridIndex' => '10', 'column2' =>'10' );
                break;
            case 'completed':
                $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'column2' => '0' );
                $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '1', 'column2' => '1' );
                //$rows[] = array( 'name' => 'APP_STATUS',            'gridIndex' =>  '2', 'column2' => '2' );
                //$rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '3', 'column2' => '3' );
                $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '2', 'column2' => '2' );
                $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '3', 'column2' => '3' );
                $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '4', 'column2' => '4' );
                $rows[] = array( 'name' => 'APP_DEL_PREVIOUS_USER', 'gridIndex' =>  '5', 'column2' => '5' );
                //$rows[] = array( 'name' => 'DEL_TASK_DUE_DATE',     'gridIndex' =>  '8', 'column2' => '8' );
                $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '6', 'column2' => '6' );
                //$rows[] = array( 'name' => 'DEL_PRIORITY',          'gridIndex' => '10', 'column2' =>'10' );
                break;
            case 'cancelled':
                $rows[] = array( 'name' => 'APP_UID',               'gridIndex' =>  '0', 'column2' => '0' );
                $rows[] = array( 'name' => 'APP_NUMBER',            'gridIndex' =>  '1', 'column2' => '1' );
                //$rows[] = array( 'name' => 'APP_STATUS',            'gridIndex' =>  '2', 'column2' => '2' );
                //$rows[] = array( 'name' => 'DEL_INDEX',             'gridIndex' =>  '3', 'column2' => '3' );
                $rows[] = array( 'name' => 'APP_TITLE',             'gridIndex' =>  '2', 'column2' => '2' );
                $rows[] = array( 'name' => 'APP_PRO_TITLE',         'gridIndex' =>  '3', 'column2' => '3' );
                $rows[] = array( 'name' => 'APP_TAS_TITLE',         'gridIndex' =>  '4', 'column2' => '4' );
                //$rows[] = array( 'name' => 'APP_DEL_PREVIOUS_USER', 'gridIndex' =>  '7', 'column2' => '7' );
                $rows[] = array( 'name' => 'DEL_TASK_DUE_DATE',     'gridIndex' =>  '5', 'column2' => '5' );
                $rows[] = array( 'name' => 'APP_UPDATE_DATE',       'gridIndex' =>  '6', 'column2' => '6' );
                $rows[] = array( 'name' => 'DEL_PRIORITY',          'gridIndex' => '7', 'column2' =>'7' );
                break;
        }
    }
    $index =  count($rows);

    while ($aRow = $oDataset->getRow()) {
        $aRow['index'] = ++$index;
        $aTempRow['name'] = $aRow['FLD_NAME'];
        $aTempRow['gridIndex'] = $aRow['index'];
        $aTempRow['column2'] = $aTempRow['gridIndex'];
        $rows[] = $aTempRow;
        $oDataset->next();
    }
    $result['totalCount'] = count($rows);
    $result['data'] = $rows;
    //$jsonResult['records'] = $result;
    print G::json_encode( $result ) ;
} catch (Exception $e) {
    print G::json_encode ($e->getMessage());
}

