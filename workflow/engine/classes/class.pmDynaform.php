<?php

/**
 * class.pmDynaform.php
 * Implementing pmDynaform library in the running case.
 * 
 * @author Roly Rudy Gutierrez Pinto
 * @package engine.classes
 */
class pmDynaform
{

    public static $instance = null;
    public $dyn_uid = null;
    public $record = null;
    public $app_data = null;

    public function __construct($dyn_uid, $app_data)
    {
        $this->dyn_uid = $dyn_uid;
        $this->app_data = $app_data;
        $this->getDynaform();
    }

    public function getDynaform()
    {
        if ($this->record != null) {
            return $this->record;
        }
        $a = new Criteria("workflow");
        $a->addSelectColumn(DynaformPeer::DYN_VERSION);
        $a->addSelectColumn(DynaformPeer::DYN_CONTENT);
        $a->addSelectColumn(DynaformPeer::PRO_UID);
        $a->addSelectColumn(DynaformPeer::DYN_UID);
        $a->add(DynaformPeer::DYN_UID, $this->dyn_uid, Criteria::EQUAL);
        $ds = ProcessPeer::doSelectRS($a);
        $ds->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $ds->next();
        $row = $ds->getRow();
        $this->record = isset($row) ? $row : null;

        return $this->record;
    }

    public function getMergeValues()
    {
        $dataJSON = G::json_decode($this->record["DYN_CONTENT"]);
        $dt = $dataJSON->items[0]->items;
        $n = count($dt);
        for ($i = 0; $i < $n; $i++) {
            $dr = $dt[$i];
            $n2 = count($dr);
            for ($j = 0; $j < $n2; $j++) {
                if (isset($dr[$j]->name)) {
                    $valueField = isset($this->app_data[$dr[$j]->name]) ? $this->app_data[$dr[$j]->name] : "";
                    $dataJSON->items[0]->items[$i][$j]->defaultValue = $valueField;
                }
            }
        }
        $a = G::json_encode($dataJSON);
        $a = str_replace("\/", "/", $a);
        return $a;
    }

    public function mergeValues()
    {
        $this->record["DYN_CONTENT"] = $this->getMergeValues();
    }

    public function isResponsive()
    {
        return $this->record != null && $this->record["DYN_VERSION"] == 2 ? true : false;
    }

    public function printView($pm_run_outside_main_app, $application)
    {
        ob_clean();
        $file = file_get_contents(PATH_HOME . 'public_html/lib/pmdynaform/build/cases_Step_Pmdynaform_View.html');
        $file = str_replace("{JSON_DATA}", $this->record["DYN_CONTENT"], $file);
        $file = str_replace("{PM_RUN_OUTSIDE_MAIN_APP}", $pm_run_outside_main_app, $file);
        $file = str_replace("{DYN_UID}", $this->dyn_uid, $file);
        $file = str_replace("{DYNAFORMNAME}", $this->record["PRO_UID"] . "_" . $this->record["DYN_UID"], $file);
        $file = str_replace("{APP_UID}", $application, $file);
        echo $file;
        exit();
    }

    public function printEdit($pm_run_outside_main_app, $application, $headData)
    {
        ob_clean();

        $a = $this->clientToken();
        $clientToken = array(
            "accessToken" => $a["access_token"],
            "expiresIn" => $a["expires_in"],
            "tokenType" => $a["token_type"],
            "scope" => $a["scope"],
            "refreshToken" => $a["refresh_token"],
            "clientId" => $a["client_id"],
            "clientSecret" => $a["client_secret"]
        );

        $file = file_get_contents(PATH_HOME . 'public_html/lib/pmdynaform/build/cases_Step_Pmdynaform.html');
        $file = str_replace("{JSON_DATA}", $this->record["DYN_CONTENT"], $file);
        $file = str_replace("{CASE}", $headData["CASE"], $file);
        $file = str_replace("{APP_NUMBER}", $headData["APP_NUMBER"], $file);
        $file = str_replace("{TITLE}", $headData["TITLE"], $file);
        $file = str_replace("{APP_TITLE}", $headData["APP_TITLE"], $file);
        $file = str_replace("{PM_RUN_OUTSIDE_MAIN_APP}", $pm_run_outside_main_app, $file);
        $file = str_replace("{DYN_UID}", $this->dyn_uid, $file);
        $file = str_replace("{DYNAFORMNAME}", $this->record["PRO_UID"] . "_" . $this->record["DYN_UID"], $file);
        $file = str_replace("{APP_UID}", $application, $file);
        $file = str_replace("{PRJ_UID}", $this->app_data["PROCESS"], $file);
        $file = str_replace("{WORKSPACE}", $this->app_data["SYS_SYS"], $file);
        $file = str_replace("{credentials}", json_encode($clientToken), $file);
        echo $file;
        exit();
    }

    private function clientToken()
    {
        $client = $this->getClientCredentials();
        $authCode = $this->getAuthorizationCode($client);


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
        $response = $oauthServer->getServer()->handleTokenRequest($request);
        $clientToken = $response->getParameters();
        $clientToken["client_id"] = $client['CLIENT_ID'];
        $clientToken["client_secret"] = $client['CLIENT_SECRET'];

        return $clientToken;
    }

    protected $clientId = 'x-pm-local-client';

    protected function getClientCredentials()
    {
        $oauthQuery = new ProcessMaker\Services\OAuth2\PmPdo($this->getDsn());
        return $oauthQuery->getClientDetails($this->clientId);
    }

    protected function getAuthorizationCode($client)
    {
        \ProcessMaker\Services\OAuth2\Server::setDatabaseSource($this->getDsn());
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
        $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=') + 5, 40);

        return $code;
    }

    private function getDsn()
    {
        list($host, $port) = strpos(DB_HOST, ':') !== false ? explode(':', DB_HOST) : array(DB_HOST, '');
        $port = empty($port) ? '' : ";port=$port";
        $dsn = DB_ADAPTER . ':host=' . $host . ';dbname=' . DB_NAME . $port;

        return array('dsn' => $dsn, 'username' => DB_USER, 'password' => DB_PASS);
    }

}
