<?php

$action = (isset($_REQUEST["action"])) ? $_REQUEST["action"] : "consolidated";

$oCriteria = new Criteria("workflow");
$oCriteria->addSelectColumn(CaseConsolidatedCorePeer::CON_STATUS);
$oCriteria->add(CaseConsolidatedCorePeer::CON_STATUS, "ACTIVE");
$activeNumRows = CaseConsolidatedCorePeer::doCount($oCriteria);

G::LoadClass ("BasePeer");
G::LoadClass ("configuration");
G::loadClass("pmFunctions");

$headPublisher = &headPublisher::getSingleton();

//cambiar esto por PROPEL //CASE_CONSOLIDATED   TASK
$usrUid = $_SESSION["USER_LOGGED"];

$oCriteria = new Criteria("workflow");
$oCriteria->addSelectColumn("*");
$oCriteria->addSelectColumn(CaseConsolidatedCorePeer::TAS_UID);
$oCriteria->addJoin(CaseConsolidatedCorePeer::TAS_UID,ContentPeer::CON_ID, Criteria::LEFT_JOIN);
$oCriteria->addJoin(CaseConsolidatedCorePeer::TAS_UID,TaskPeer::TAS_UID, Criteria::LEFT_JOIN);
$oCriteria->addAnd(ContentPeer::CON_CATEGORY, "TAS_TITLE");
$oCriteria->addAnd(ContentPeer::CON_LANG, "en");

$params = array(); //This will be filled with the parameters
$sql = BasePeer::createSelectSql($oCriteria, $params);

$oDataset = CaseConsolidatedCorePeer::doSelectRS($oCriteria);
$oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
//$oDataset->next();
while ($oDataset->next()) {
    $aRow = $oDataset->getRow();
    //$aTaskConsolidated [] = $aRow;
}

$query = "SELECT *
          FROM   CASE_CONSOLIDATED LEFT JOIN CONTENT ON
                 (CASE_CONSOLIDATED.TAS_UID = CONTENT.CON_ID) LEFT JOIN TASK ON (CASE_CONSOLIDATED.TAS_UID = TASK.TAS_UID)
          WHERE  CONTENT.CON_CATEGORY='TAS_TITLE' AND CONTENT.CON_LANG='en'";
$aTaskConsolidated = executeQuery($query);

$conf = new Configurations();

try {
    $confCasesList        = $conf->getConfiguration("casesList", $action);
    $generalConfCasesList = $conf->getConfiguration("ENVIRONMENT_SETTINGS", "");
} catch (Exception $e) {
    $confCasesList = array();
    $generalConfCasesList = array();
}

$config = getAdditionalFields($action, $confCasesList);

if (isset($generalConfCasesList["casesListRowNumber"]) && !empty($generalConfCasesList["casesListRowNumber"])) {
    $pageSize = intval($generalConfCasesList["casesListRowNumber"]);
} else {
    $pageSize = intval($config["rowsperpage"]);
}

$arrayTabItem = array();
$aAllData = array();
//$aQTY     = array();
$i = 0;

//SQL
$cnn = Propel::getConnection("workflow");
$stmt = $cnn->createStatement();

//foreach ($aTaskConsolidated as $value)
//{
$i++;

$sql = "SELECT COUNT(APP_CACHE_VIEW.TAS_UID) AS NUMREC,
               APP_CACHE_VIEW.PRO_UID,
               (SELECT CON.CON_VALUE
                FROM   CONTENT AS CON
                WHERE  CON.CON_ID = APP_CACHE_VIEW.PRO_UID AND CON.CON_CATEGORY = 'PRO_TITLE' AND CON.CON_LANG = '" . SYS_LANG . "'
               ) AS PROCESS_TITLE,
               APP_CACHE_VIEW.TAS_UID,
               CONTASK.CON_VALUE AS TASK_TITLE,
               CASE_CONSOLIDATED.DYN_UID
        FROM   CASE_CONSOLIDATED
               LEFT JOIN CONTENT AS CONTASK ON (CASE_CONSOLIDATED.TAS_UID = CONTASK.CON_ID AND CONTASK.CON_CATEGORY = 'TAS_TITLE' AND CONTASK.CON_LANG = '" . SYS_LANG . "')
               LEFT JOIN APP_CACHE_VIEW ON (CASE_CONSOLIDATED.TAS_UID = APP_CACHE_VIEW.TAS_UID)
        WHERE  APP_CACHE_VIEW.USR_UID = '$usrUid' AND
               APP_CACHE_VIEW.DEL_THREAD_STATUS = 'OPEN' AND
               APP_CACHE_VIEW.APP_STATUS = 'TO_DO'
        GROUP BY APP_CACHE_VIEW.TAS_UID";

$rsSql = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);

while ($rsSql->next()) {
    $row = $rsSql->getRow();

    $processUid  = $row["PRO_UID"];
    $proTitle    = $row["PROCESS_TITLE"];
    $taskUid     = $row["TAS_UID"];
    $taskTitle   = $row["TASK_TITLE"];
    $dynaformUid = $row["DYN_UID"];

    $tabTitle = $taskTitle . " (" . (($activeNumRows > 0)? $row["NUMREC"] : 0) . ")";

    $grdTitle = htmlentities($proTitle . " / " . $tabTitle, ENT_QUOTES, "UTF-8");
    $tabTitle = htmlentities(substr($proTitle, 0, 25) . ((strlen($proTitle) > 25)? "..." : null) . " / " . $tabTitle, ENT_QUOTES, "UTF-8");

    $oProcess = new Process();
    $isBpmn   = $oProcess->isBpmnProcess($processUid);
    if($isBpmn){
        $arrayTabItem[] = "
        {
            title: \"<span onmouseover=\\\"toolTipTab('$grdTitle', 1);\\\" onmouseout=\\\"toolTipTab('', 0);\\\">$tabTitle</span>\",
            listeners: {
                activate: function ()
                {
                      generateGrid(\"$processUid\", \"$taskUid\", \"$dynaformUid\");
                }
            }
        }";
    }else{
        $arrayTabItem[] = "
        {
            title: \"<span onmouseover=\\\"toolTipTab('$grdTitle', 1);\\\" onmouseout=\\\"toolTipTab('', 0);\\\">$tabTitle</span>\",
            listeners: {
                activate: function ()
                {
                      generateGridClassic(\"$processUid\", \"$taskUid\", \"$dynaformUid\");
                }
            }
        }";
    }
}

if (count($arrayTabItem) > 0) {
    $urlProxy = System::getHttpServerHostnameRequestsFrontEnd() . '/api/1.0/' . SYS_SYS . '/consolidated/';
    $clientId = 'x-pm-local-client';
    $client = getClientCredentials($clientId);
    $authCode = getAuthorizationCode($client);
    $debug = false; //System::isDebugMode();

    $loader = Maveriks\Util\ClassLoader::getInstance();
    $loader->add(PATH_TRUNK . 'vendor/bshaffer/oauth2-server-php/src/', "OAuth2");

    $request = array(
        'grant_type' => 'authorization_code',
        'code' => $authCode
    );
    $server = array(
        'REQUEST_METHOD' => 'POST'
    );
    $headers = array(
        "PHP_AUTH_USER" => $client['CLIENT_ID'],
        "PHP_AUTH_PW" => $client['CLIENT_SECRET'],
        "Content-Type" => "multipart/form-data;",
        "Authorization" => "Basic " . base64_encode($client['CLIENT_ID'] . ":" . $client['CLIENT_SECRET'])
    );

    $request = new \OAuth2\Request(array(), $request, array(), array(), array(), $server, null, $headers);
    $oauthServer = new \ProcessMaker\Services\OAuth2\Server();
    $response = $oauthServer->postToken($request, true);

    $clientToken = $response->getParameters();
    $clientToken["client_id"] = $client['CLIENT_ID'];
    $clientToken["client_secret"] = $client['CLIENT_SECRET'];


    $items = "[" . implode(",", $arrayTabItem) ."]";

    $userUid = (isset($_SESSION["USER_LOGGED"]) && $_SESSION["USER_LOGGED"] != "")? $_SESSION["USER_LOGGED"] : null;
    $processes = getProcessArray($action, $userUid);

    $headPublisher->assign("pageSize", $pageSize);          //Sending the page size
    $headPublisher->assign("action", $action);              //Sending the fields to get from proxy
    $headPublisher->assign("Items", $items);
    $headPublisher->assign("processValues", $processes);    //Sending the columns to display in grid
    $headPublisher->assign("varSkin", SYS_SKIN);            //Sending the current Skin
    $headPublisher->assign("FORMATS", $conf->getFormats());
    $headPublisher->assign("urlProxy", $urlProxy);
    $headPublisher->assign('credentials', $clientToken );

    $oHeadPublisher->assign('isIE', Bootstrap::isIE());

    $headPublisher->addExtJsScript("app/main", true);
    $headPublisher->addExtJsScript("cases/casesListConsolidated", false);   //Adding a JavaScript file .js
    $headPublisher->addContent("cases/casesListConsolidated");              //Adding a HTML file .html

    G::RenderPage("publish", "extJs");
} else {
    echo "<span style=\"font: 0.75em normal arial, verdana, helvetica, sans-serif;\">" . G::LoadTranslation("ID_NO_RECORDS_FOUND") . "</span>";
}



function getProcessArray($action, $userUid)
{
    $processes = array();
    $processes[] = array("", G::LoadTranslation("ID_ALL_PROCESS"));

    switch ($action) {
        case "simple_search":
        case "search":
            //In search action, the query to obtain all process is too slow, so we need to query directly to
            //process and content tables, and for that reason we need the current language in AppCacheView.
            G::loadClass("configuration");
            $oConf = new Configurations;
            $oConf->loadConfig($x, "APP_CACHE_VIEW_ENGINE", "", "", "", "");
            $appCacheViewEngine = $oConf->aConfig;
            $lang = isset($appCacheViewEngine["LANG"])? $appCacheViewEngine["LANG"] : "en";

            $cProcess = new Criteria("workflow");
            $cProcess->clearSelectColumns();
            $cProcess->addSelectColumn(ProcessPeer::PRO_UID);
            $cProcess->addSelectColumn(ContentPeer::CON_VALUE);

            $del = DBAdapter::getStringDelimiter();

            $conds = array();
            $conds[] = array(ProcessPeer::PRO_UID,      ContentPeer::CON_ID);
            $conds[] = array(ContentPeer::CON_CATEGORY, $del . "PRO_TITLE" . $del);
            $conds[] = array(ContentPeer::CON_LANG,     $del . $lang . $del);
            $cProcess->addJoinMC($conds, Criteria::LEFT_JOIN);
            $cProcess->add(ProcessPeer::PRO_STATUS, "ACTIVE");
            $oDataset = ProcessPeer::doSelectRS($cProcess);
            $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $processes[] = array($aRow["PRO_UID"], $aRow["CON_VALUE"]);
                $oDataset->next();
            }

            return ($processes);
            break;
        case "consolidated":
        default:
            $oAppCache = new AppCacheView();
            $cProcess = $oAppCache->getToDoListCriteria($userUid); //fast enough
            break;
    }

    $cProcess->clearSelectColumns();
    $cProcess->setDistinct();
    $cProcess->addSelectColumn(AppCacheViewPeer::PRO_UID);
    $cProcess->addSelectColumn(AppCacheViewPeer::APP_PRO_TITLE);
    $oDataset = AppCacheViewPeer::doSelectRS($cProcess);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();

    while ($aRow = $oDataset->getRow()) {
        $processes[] = array($aRow["PRO_UID"], $aRow["APP_PRO_TITLE"]);
        $oDataset->next();
    }

    return ($processes);
}

function getConsolidated()
{
    $caseColumns = array ();
    $caseColumns[] = array("header" =>"#",           "dataIndex" => "APP_NUMBER",            "width" => 45, "align" => "center");
    $caseColumns[] = array("header" =>"Case",        "dataIndex" => "APP_TITLE",             "width" => 150);
    $caseColumns[] = array("header" =>"UserUid",     "dataIndex" => "USR_UID",               "width" => 50, "hidden" => true, "hideable" => false);
    $caseColumns[] = array("header" =>"PreUsrUid",   "dataIndex" => "PREVIOUS_USR_UID",      "width" => 50, "hidden" => true, "hideable" => false);
    $caseColumns[] = array("header" =>"Task",        "dataIndex" => "APP_TAS_TITLE",         "width" => 120);
    $caseColumns[] = array("header" =>"Process",     "dataIndex" => "APP_PRO_TITLE",         "width" => 120);
    $caseColumns[] = array("header" =>"Sent by",     "dataIndex" => "APP_DEL_PREVIOUS_USER", "width" => 90);
    $caseColumns[] = array("header" =>"Due Date",    "dataIndex" => "DEL_TASK_DUE_DATE",     "width" => 110);
    $caseColumns[] = array("header" =>"Last Modify", "dataIndex" => "APP_UPDATE_DATE",       "width" => 110);
    $caseColumns[] = array("header" =>"Priority",    "dataIndex" => "DEL_PRIORITY",          "width" => 50);

    $caseReaderFields = array();
    $caseReaderFields[] = array("name" => "APP_UID");
    $caseReaderFields[] = array("name" => "USR_UID");
    $caseReaderFields[] = array("name" => "PREVIOUS_USR_UID");
    $caseReaderFields[] = array("name" => "DEL_INDEX");
    $caseReaderFields[] = array("name" => "APP_NUMBER");
    $caseReaderFields[] = array("name" => "APP_TITLE");
    $caseReaderFields[] = array("name" => "APP_PRO_TITLE");
    $caseReaderFields[] = array("name" => "APP_TAS_TITLE");
    $caseReaderFields[] = array("name" => "APP_DEL_PREVIOUS_USER");
    $caseReaderFields[] = array("name" => "DEL_TASK_DUE_DATE");
    $caseReaderFields[] = array("name" => "APP_UPDATE_DATE");
    $caseReaderFields[] = array("name" => "DEL_PRIORITY");
    $caseReaderFields[] = array("name" => "APP_FINISH_DATE");
    $caseReaderFields[] = array("name" => "APP_CURRENT_USER");
    $caseReaderFields[] = array("name" => "APP_STATUS");

    return (array("caseColumns" => $caseColumns, "caseReaderFields" => $caseReaderFields, "rowsperpage" => 20, "dateformat" => "M d, Y"));
}

function getAdditionalFields($action, $confCasesList)
{
    $caseColumns = array();
    $caseReaderFields = array();

    if (!empty($confCasesList) && !empty($confCasesList["second"]["data"])) {
        foreach ($confCasesList["second"]["data"] as $fieldData) {
            if ($fieldData["fieldType"] != "key") {
                $label = $fieldData["label"];
                $caseColumns[]      = array("header" => $label, "dataIndex" => $fieldData["name"], "width" => $fieldData["width"], "align" => $fieldData["align"]);
                $caseReaderFields[] = array("name"   => $fieldData["name"]);
            }
        }
        return (array("caseColumns" => $caseColumns, "caseReaderFields" => $caseReaderFields, "rowsperpage" => $confCasesList["rowsperpage"], "dateformat" => $confCasesList["dateformat"]));
    } else {
        switch ($action) {
            case "consolidated":
            default:
                $action = "consolidated";
                $config = getConsolidated();
                break;
        }
        return ($config);
    }
}

function getClientCredentials($clientId)
{
    $oauthQuery = new ProcessMaker\Services\OAuth2\PmPdo(getDsn());
    return $oauthQuery->getClientDetails($clientId);
}

function getDsn()
{
    list($host, $port) = strpos(DB_HOST, ':') !== false ? explode(':', DB_HOST) : array(DB_HOST, '');
    $port = empty($port) ? '' : ";port=$port";
    $dsn = DB_ADAPTER.':host='.$host.';dbname='.DB_NAME.$port;

    return array('dsn' => $dsn, 'username' => DB_USER, 'password' => DB_PASS);
}


function getAuthorizationCode($client)
{
    \ProcessMaker\Services\OAuth2\Server::setDatabaseSource(getDsn());
    \ProcessMaker\Services\OAuth2\Server::setPmClientId($client['CLIENT_ID']);

    $oauthServer = new \ProcessMaker\Services\OAuth2\Server();
    $userId = $_SESSION['USER_LOGGED'];
    $authorize = true;
    $_GET = array_merge($_GET, array(
        'response_type' => 'code',
        'client_id' => $client['CLIENT_ID'],
        'scope' => implode(' ', $oauthServer->getScope())
    ));

    $response = $oauthServer->postAuthorize($authorize, $userId, true);
    $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);

    return $code;
}