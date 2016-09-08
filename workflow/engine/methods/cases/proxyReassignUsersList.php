<?php
$appUid = isset( $_POST['application'] ) ? $_POST['application'] : '';
$TaskUid = isset( $_POST['task'] ) ? $_POST['task'] : '';
$sReassignFromUser = isset( $_POST['currentUser'] ) ? $_POST['currentUser'] : '';
$query = (isset($_POST['query']))? $_POST['query'] : '';

$oConf = new Configurations();

$arrayUsersToReassign = [];
$ConfEnv = $oConf->getFormats();

if ($TaskUid != '') {
    $case = new \ProcessMaker\BusinessModel\Cases();

    $result = $case->getUsersToReassign(
        $_SESSION['USER_LOGGED'],
        $TaskUid,
        ['filter' => $query],
        $oConf->userNameFormatGetFirstFieldByUsersTable(),
        'ASC',
        null,
        25
    );

    foreach ($result['data'] as $row) {
        $sCaseUser = G::getFormatUserList( $ConfEnv['format'], $row );
        $arrayUsersToReassign[] = ['userUid' => $row['USR_UID'], 'userFullname' => $sCaseUser];
    }
}

$result = array ();
$result['data'] = $arrayUsersToReassign;
print G::json_encode( $result );

