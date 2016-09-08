<?php
if (!isset($_SESSION['USER_LOGGED'])) {
    $response = new stdclass();
    $response->message = G::LoadTranslation('ID_LOGIN_AGAIN');
    $response->lostSession = true;
    print G::json_encode( $response );
    die();
}

$start = isset( $_POST['start'] ) ? $_POST['start'] : '0';
$limit = isset( $_POST['limit'] ) ? $_POST['limit'] : '25';

if (isset($_POST['APP_UIDS'])) {
    $sentUids = explode( ',', $_POST['APP_UIDS'] );
} else {
    $sentUids = array();
}

$allUidsRecords = array ();

// getting all App Uids and task Uids
foreach ($sentUids as $sentUid) {
    $aItem = explode( '|', $sentUid );
    $allUidsRecords[] = array ('APP_UID' => $aItem[0],'TAS_UID' => $aItem[1],'DEL_INDEX' => $aItem[2]);
}

$oCases = new Cases();

$aCasesList = Array ();

foreach ($allUidsRecords as $aRecord) {
    $APP_UID = $aRecord['APP_UID'];
    $delIndex = $aRecord['DEL_INDEX'];
    $aCase = $oCases->loadCaseByDelegation( $APP_UID, $delIndex );

    $aCase['USERS'] = [];
    array_push( $aCasesList, $aCase );
}

$filedNames = Array ("APP_UID","APP_NUMBER","APP_UPDATE_DATE","DEL_PRIORITY","DEL_INDEX","TAS_UID","DEL_INIT_DATE","DEL_FINISH_DATE","USR_UID","APP_STATUS","DEL_TASK_DUE_DATE","APP_CURRENT_USER","APP_TITLE","APP_PRO_TITLE","APP_TAS_TITLE","APP_DEL_PREVIOUS_USER","USERS"
);

$aCasesList = array_merge( Array ($filedNames
), $aCasesList );
$rows = array ();
$i = $start;
for ($j = 0; $j < $limit; $j ++) {
    $i ++;
    if (isset( $aCasesList[$i] )) {
        $rows[] = $aCasesList[$i];
    }
}
$totalCount = count( $aCasesList ) - 1;
$result = array ();
$result['totalCount'] = $totalCount;

$index = $start;
$result['data'] = $rows;
//print the result in json format
print G::json_encode( $result );

