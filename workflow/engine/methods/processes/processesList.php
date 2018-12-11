<?php

require_once 'classes/model/Process.php';

$start = isset($_POST['start']) ? $_POST['start'] : 0;
$limit = isset($_POST['limit']) ? $_POST['limit'] : '';
$dir = isset($_POST['dir']) ? $_POST['dir'] : 'ASC';
$sort = isset($_POST['sort']) ? $_POST['sort'] : '';

$oProcess = new Process();
$oProcess->dir = $dir;
$oProcess->sort = $sort;

$memkey = 'no memcache';
$memcacheUsed = 'not used';
$totalCount = 0;
if (isset($_POST['category']) && $_POST['category'] !== '<reset>') {
    if (isset($_POST['processName'])) {
        $proData = $oProcess->getAllProcesses($start, $limit, $_POST['category'], $_POST['processName'], true, false, $_SESSION["USER_LOGGED"]);
    } else {
        $proData = $oProcess->getAllProcesses($start, $limit, $_POST['category'], null, true, false, $_SESSION["USER_LOGGED"]);
    }
} else {
    if (isset($_POST['processName'])) {
        $memkey = 'processList-' . $start . '-' . $limit . '-' . $_POST['processName'];
        $memcacheUsed = 'yes';
        $proData = $memcache->get($memkey);
        if ($proData === false) {
            $proData = $oProcess->getAllProcesses($start, $limit, null, $_POST['processName'], true, false, $_SESSION["USER_LOGGED"]);
            $memcache->set($memkey, $proData, PMmemcached::ONE_HOUR);
            $totalCount = count($proData);
            $proData = array_splice($proData, $start, $limit);
            $memcacheUsed = 'no';
        } else {
            $proData = $oProcess->orderMemcache($proData, $start, $limit);
            $totalCount = $proData->totalCount;
            $proData = $proData->dataMemcache;
        }
    } else {
        $memkey = 'processList-allProcesses-' . $start . '-' . $limit;
        $memkeyTotal = $memkey . '-total';
        $memcacheUsed = 'yes';
        if (($proData = $memcache->get($memkey)) === false || ($totalCount = $memcache->get($memkeyTotal)) === false) {
            $proData = $oProcess->getAllProcesses($start, $limit, null, null, true, false, $_SESSION["USER_LOGGED"]);
            $totalCount = count($proData);
            $proData = array_splice($proData, $start, $limit);
            $memcache->set($memkey, $proData, PMmemcached::ONE_HOUR);
            $memcache->set($memkeyTotal, $totalCount, PMmemcached::ONE_HOUR);
            $memcacheUsed = 'no';
        } else {
            $proData = $oProcess->orderMemcache($proData, $start, $limit);
            $totalCount = $proData->totalCount;
            $proData = $proData->dataMemcache;
        }
    }
}
$r = new stdclass();
$r->memkey = htmlspecialchars($memkey);
$r->memcache = $memcacheUsed;
$r->data = \ProcessMaker\Util\DateTime::convertUtcToTimeZone($proData);
$r->totalCount = $totalCount;

echo G::json_encode($r);
