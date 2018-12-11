<?php
try {
    if (!$_POST || count($_POST) == 0) {
        throw new Exception("The information sent is empty.");
    }

    $prossesUid = trim($_POST["form"]["PRO_UID"]);
    $caseSchedulerUid = trim($_POST["form"]["SCH_UID"]);

    $caseScheduler = new \ProcessMaker\BusinessModel\CaseScheduler();

    $caseScheduler->createUpdate($caseSchedulerUid, $prossesUid, $_SESSION["USER_LOGGED"], $_POST["form"], (isset($_POST["pluginFields"]))? $_POST["pluginFields"] : array());

    G::header("Location: cases_Scheduler_List?PRO_UID=" . $prossesUid);
} catch (Exception $e) {
    $token = strtotime("now");
    PMException::registerErrorLog($e, $token);
    G::outRes( G::LoadTranslation("ID_EXCEPTION_LOG_INTERFAZ", array($token)) );

    exit(0);
}

