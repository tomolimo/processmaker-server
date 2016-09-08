<?php
G::LoadSystem('inputfilter');
$filter = new InputFilter();
$_GET = $filter->xssFilterHard($_GET);
$_REQUEST = $filter->xssFilterHard($_REQUEST);
$_SESSION['USER_LOGGED'] = $filter->xssFilterHard($_SESSION['USER_LOGGED']);

if (!isset($_SESSION['USER_LOGGED'])) {
    $responseObject = new stdclass();
    $responseObject->error = G::LoadTranslation('ID_LOGIN_AGAIN');
    $responseObject->success = true;
    $responseObject->lostSession = true;
    print G::json_encode( $responseObject );
    die();
}

//Getting the extJs parameters
$callback = isset( $_REQUEST["callback"] ) ? $_REQUEST["callback"] : "stcCallback1001";
$dir = isset( $_REQUEST["dir"] ) ? $_REQUEST["dir"] : "DESC";
$sort = isset( $_REQUEST["sort"] ) ? $_REQUEST["sort"] : "";
$start = isset( $_REQUEST["start"] ) ? $_REQUEST["start"] : "0";
$limit = isset( $_REQUEST["limit"] ) ? $_REQUEST["limit"] : "25";
$filter = isset( $_REQUEST["filter"] ) ? $_REQUEST["filter"] : "";
$process = isset( $_REQUEST["process"] ) ? $_REQUEST["process"] : "";
$category = isset( $_REQUEST["category"] ) ? $_REQUEST["category"] : "";
$status = isset( $_REQUEST["status"] ) ? strtoupper( $_REQUEST["status"] ) : "";
$filterStatus = isset( $_REQUEST["filterStatus"] ) ? strtoupper( $_REQUEST["filterStatus"] ) : "";
$user = isset( $_REQUEST["user"] ) ? $_REQUEST["user"] : "";
$search = isset( $_REQUEST["search"] ) ? $_REQUEST["search"] : "";
$action = isset( $_GET["action"] ) ? $_GET["action"] : (isset( $_REQUEST["action"] ) ? $_REQUEST["action"] : "todo");
$type = isset( $_GET["type"] ) ? $_GET["type"] : (isset( $_REQUEST["type"] ) ? $_REQUEST["type"] : "extjs");
$dateFrom = isset( $_REQUEST["dateFrom"] ) ? substr( $_REQUEST["dateFrom"], 0, 10 ) : "";
$dateTo = isset( $_REQUEST["dateTo"] ) ? substr( $_REQUEST["dateTo"], 0, 10 ) : "";
$first = isset( $_REQUEST["first"] ) ? true :false;

if ($sort == 'CASE_SUMMARY' || $sort == 'CASE_NOTES_COUNT') {
    $sort = 'APP_NUMBER';//DEFAULT VALUE
}
if ($sort == 'APP_STATUS_LABEL') {
    $sort = 'APP_STATUS';
}

try {
    $userUid = (isset($_SESSION["USER_LOGGED"]) && $_SESSION["USER_LOGGED"] != "")? $_SESSION["USER_LOGGED"] : null;
    $result = "";
    $solrEnabled = false;

    switch ($action) {
        case "search":
        case "to_reassign":
            if ($first) {
                $result['totalCount'] = 0;
                $result['data'] = array();
                $result = G::json_encode($result);
                echo $result;
                return ;
            }
            $user = ($user == "CURRENT_USER")? $userUid : $user;
            $userUid = $user;
            break;
        default:
            break;
    }

    if ((
        $action == "todo" || $action == "draft" || $action == "paused" || $action == "sent" ||
        $action == "selfservice" || $action == "unassigned" || $action == "search"
        ) &&
        (($solrConf = System::solrEnv()) !== false)
    ) {
        G::LoadClass("AppSolr");

        $ApplicationSolrIndex = new AppSolr(
            $solrConf["solr_enabled"],
            $solrConf["solr_host"],
            $solrConf["solr_instance"]
        );

        if ($ApplicationSolrIndex->isSolrEnabled() && $solrConf['solr_enabled'] == true) {
            //Check if there are missing records to reindex and reindex them
            $ApplicationSolrIndex->synchronizePendingApplications();
            $solrEnabled = true;
        } else{
            $solrEnabled = false;
        }
    }

    if ($solrEnabled) {
        $data = $ApplicationSolrIndex->getAppGridData(
            $userUid,
            $start,
            $limit,
            $action,
            $filter,
            $search,
            $process,
            $status,
            $type,
            $dateFrom,
            $dateTo,
            $callback,
            $dir,
            $sort,
            $category
        );

        $data['data'] = \ProcessMaker\Util\DateTime::convertUtcToTimeZone($data['data']);

        $result = G::json_encode($data);
    } else {
        G::LoadClass("applications");

        $apps = new Applications();
        $data = $apps->getAll(
            $userUid,
            $start,
            $limit,
            $action,
            $filter,
            $search,
            $process,
            $filterStatus,
            $type,
            $dateFrom,
            $dateTo,
            $callback,
            $dir,
            (strpos($sort, ".") !== false)? $sort : "APP_CACHE_VIEW." . $sort,
            $category
        );

        $data['data'] = \ProcessMaker\Util\DateTime::convertUtcToTimeZone($data['data']);

        $result = G::json_encode($data);
    }

    echo $result;
} catch (Exception $e) {
    $msg = array("error" => $e->getMessage());
    echo G::json_encode($msg);
}

