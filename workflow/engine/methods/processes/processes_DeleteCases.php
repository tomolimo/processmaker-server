<?php
global $RBAC;
$RBAC->requirePermissions( 'PM_DELETE_PROCESS_CASES', 'PM_FACTORY' );
$resp = new StdClass();
try {
    $uids = explode(',', $_POST['PRO_UIDS']);
    $oProcess = new Process();
    foreach ($uids as $uid) {
        $oProcess->deleteProcessCases($uid);
    }
    $oProcess->refreshUserAllCountersByProcessesGroupUid($uids);

    $resp->status = true;
    $resp->msg = G::LoadTranslation('ID_ALL_RECORDS_DELETED_SUCESSFULLY');

    echo G::json_encode($resp);

} catch (Exception $e) {
    $resp->status = false;
    $resp->msg = $e->getMessage();
    $resp->trace = $e->getTraceAsString();
    echo G::json_encode($resp);
}


