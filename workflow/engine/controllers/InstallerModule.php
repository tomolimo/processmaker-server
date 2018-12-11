<?php

use ProcessMaker\Util\Common;
use ProcessMaker\Core\System;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

global $translation;

include PATH_LANGUAGECONT . "translation." . SYS_LANG;

class InstallerModule extends Controller
{
    const PHP_VERSION_MINIMUM_SUPPORTED = '5.6';
    const PHP_VERSION_NOT_SUPPORTED = '7.2';
    public $path_config;
    public $path_languages;
    public $path_plugins;
    public $path_xmlforms;
    public $path_shared;
    public $path_sep;
    public $systemName;

    public $link; #resource for database connection

    /**
     * Default name for connection
     *
     * @var string
     */
    const CONNECTION_INSTALL = 'install';
    const CONNECTION_TEST_INSTALL = 'testInstall';

    /**
     * Constructor
     * We defined the paths for the installer
     */
    public function __construct()
    {
        $this->path_config = PATH_CORE . 'config/';
        $this->path_languages = PATH_CORE . 'content/languages/';
        $this->path_plugins = PATH_CORE . 'plugins/';
        $this->path_xmlforms = PATH_CORE . 'xmlform/';
        $this->path_public = PATH_HOME . 'public_html/index.html';
        $this->path_shared = PATH_TRUNK . 'shared/';
        $this->path_sep = PATH_SEP;
        $this->systemName = '';
        //$this->path_documents = ;
        $this->path_translations = PATH_CORE . 'js/labels/';
        $this->path_translationsMafe = PATH_HOME . 'public_html/translations/';
    }

    public function index($httpData)
    {
        if (file_exists(FILE_PATHS_INSTALLED)) {
            $this->setJSVar('messageError', G::LoadTranslation('ID_PROCESSMAKER_ALREADY_INSTALLED'));
            $this->includeExtJS('installer/stopInstall');
            $this->setView('installer/mainStopInstall');
            G::RenderPage('publish', 'extJs');
            return;
        }
        if ((strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') && (file_exists($this->path_shared . 'partner.info'))) {
            $this->setJSVar('messageError', G::LoadTranslation('ID_NO_INSTALL'));
            $this->includeExtJS('installer/stopInstall');
            $this->setView('installer/mainStopInstall');
            G::RenderPage('publish', 'extJs');
            return;
        }

        $licenseContent = file_get_contents(PATH_TRUNK . 'LICENSE.txt');

        $this->includeExtJS('installer/CardLayout', false);
        $this->includeExtJS('installer/Wizard', false);
        $this->includeExtJS('installer/Header', false);
        $this->includeExtJS('installer/Card', false);

        $this->includeExtJS('installer/installer_cards');
        $this->includeExtJS('installer/main', false);

        $this->setJSVar('licenseTxt', $licenseContent);

        $this->setJSVar('path_config', $this->path_config);
        $this->setJSVar('path_languages', $this->path_languages);
        $this->setJSVar('path_plugins', $this->path_plugins);
        $this->setJSVar('path_xmlforms', $this->path_xmlforms);
        $this->setJSVar('path_public', $this->path_public);
        $this->setJSVar('path_shared', $this->path_shared);
        $this->setJSVar('path_sep', $this->path_sep);
        $this->setJSVar('path_translations', $this->path_translations);
        $this->setJSVar('path_translationsMafe', $this->path_translationsMafe);

        $this->setView('installer/main');

        G::RenderPage('publish', 'extJs');
    }

    /**
     * This function can be create a new workspace
     * The user need permission PM_SETUP_ADVANCE for this action
     * @return void
     */
    public function newSite()
    {
        if (!$this->pmIsInstalled()) {
            $textStep1 = G::LoadTranslation('ID_PROCESSMAKER_REQUIREMENTS_DESCRIPTION_STEP4_1');
            $textStep2 = G::LoadTranslation('ID_PROCESSMAKER_REQUIREMENTS_DESCRIPTION_STEP5');

            $this->includeExtJS('installer/CardLayout', false);
            $this->includeExtJS('installer/Wizard', false);
            $this->includeExtJS('installer/Header', false);
            $this->includeExtJS('installer/Card', false);
            $this->includeExtJS('installer/newSite', false);
            $this->setJSVar('textStep1', $textStep1);
            $this->setJSVar('textStep2', $textStep2);
            $this->setJSVar('DB_ADAPTER', DB_ADAPTER);
            $aux = explode(':', DB_HOST);
            $this->setJSVar('DB_HOST', $aux[0]);
            $this->setJSVar('DB_PORT', isset($aux[1]) ? $aux[1] : (DB_ADAPTER == 'mssql' ? '1433' : '3306'));
            $this->setJSVar('DB_NAME', 'workflow');
            $this->setJSVar('DB_USER', '');
            $this->setJSVar('DB_PASS', '');
            $this->setJSVar('pathConfig', PATH_CORE . 'config' . PATH_SEP);
            $this->setJSVar('pathLanguages', PATH_LANGUAGECONT);
            $this->setJSVar('pathPlugins', PATH_PLUGINS);
            $this->setJSVar('pathXmlforms', PATH_XMLFORM);
            $this->setJSVar('pathShared', PATH_DATA);
            $this->setView('installer/newSite');

            G::RenderPage('publish', 'extJs');
        } else {
            $this->displayError();
        }
    }

    /**
     * Set config connection
     *
     * @param string $nameConnection name Connection
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $database
     * @param int $port
     * @param array $options
     *
     * @throws Exception
     */
    public static function setNewConnection($nameConnection, $host, $user, $pass, $database, $port, $options = [])
    {
        try {
            if (empty($port)) {
                $dbHost = explode(':', $host);
                $port = 3306;
                if (count($dbHost) > 1) {
                    $port = $dbHost[1];
                }
                $host = $dbHost[0];
            }
            config(['database.connections.' . $nameConnection => [
                'driver' => 'mysql',
                'host' => $host,
                'port' => $port,
                'database' => $database,
                'username' => $user,
                'password' => $pass,
                'unix_socket' => '',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'strict' => false,
                'engine' => 'InnoDB',
                'options' => $options
            ]]);
            DB::connection($nameConnection)->getPdo();
        } catch (Exception $e) {
            throw new Exception(G::LoadTranslation('ID_MYSQL_CREDENTIALS_WRONG'));
        }
    }

    /**
     * Get system information for review the requirements to install ProcessMaker
     *
     * @return object
     */
    public function getSystemInfo()
    {
        $this->setResponseType('json');

        // PHP info and verification
        $phpVer = phpversion();
        preg_match('/[0-9\.]+/', $phpVer, $match);
        $phpVerNum = (float)$match[0];

        $info = new stdclass();
        $info->php = new stdclass();
        $info->mysql = new stdclass();
        $info->mssql = new stdclass();
        $info->openssl = new stdclass();
        $info->curl = new stdclass();
        $info->dom = new stdclass();
        $info->gd = new stdclass();
        $info->multibyte = new stdclass();
        $info->soap = new stdclass();
        $info->ldap = new stdclass();
        $info->mcrypt = new stdclass();
        $info->memory = new stdclass();

        $info->php->version = $phpVer;
        $info->php->result = (
            version_compare(phpversion(), self::PHP_VERSION_MINIMUM_SUPPORTED, '>=') &&
            version_compare(phpversion(), self::PHP_VERSION_NOT_SUPPORTED, '<')) ? true : false;

        // MYSQL info and verification
        $info->mysql->result = false;
        if (function_exists('mysqli_query')) {
            $mysqlVer = mysqli_get_client_info();
            preg_match('/[0-9\.]+/', $mysqlVer, $match);
            $mysqlNum = (float)$match[0];
            $info->mysql->version = 'Client API version ' . $mysqlVer;
            $info->mysql->result = $mysqlNum >= 5.0;
        }

        // MSSQL info and verification
        $info->mssql->result = false;
        $info->mssql->version = G::LoadTranslation('ID_NOT_ENABLED');
        if (function_exists('mssql_query')) {
            $info->mssql->result = true;
            $info->mssql->version = G::LoadTranslation('ID_ENABLED');
        }

        // OpenSSL info
        $info->openssl->result = false;
        $info->openssl->version = G::LoadTranslation('ID_NOT_ENABLED');
        if (function_exists('openssl_open')) {
            $info->openssl->result = true;
            $info->openssl->version = G::LoadTranslation('ID_ENABLED');
        }

        // Curl info
        $info->curl->result = false;
        $info->curl->version = G::LoadTranslation('ID_NOT_ENABLED');
        if (function_exists('curl_version')) {
            $info->curl->result = true;
            $version = curl_version();
            $info->curl->version = 'cURL ' . $version['version'];
            $info->openssl->version = $version['ssl_version'];
        }

        // DOMDocument info
        $info->dom->result = false;
        $info->dom->version = G::LoadTranslation('ID_NOT_ENABLED');
        if (class_exists('DOMDocument')) {
            $info->dom->result = true;
            $info->dom->version = G::LoadTranslation('ID_ENABLED');
        }

        // GD info
        $info->gd->result = false;
        $info->gd->version = G::LoadTranslation('ID_NOT_ENABLED');
        if (function_exists('gd_info')) {
            $info->gd->result = true;
            $gdinfo = gd_info();
            $info->gd->version = $gdinfo['GD Version'];
        }

        // Multibyte info
        $info->multibyte->result = false;
        $info->multibyte->version = G::LoadTranslation('ID_NOT_ENABLED');
        if (function_exists('mb_check_encoding')) {
            $info->multibyte->result = true;
            $info->multibyte->version = G::LoadTranslation('ID_ENABLED');
        }

        // soap info
        $info->soap->result = false;
        $info->soap->version = G::LoadTranslation('ID_NOT_ENABLED');
        if (class_exists('SoapClient')) {
            $info->soap->result = true;
            $info->soap->version = G::LoadTranslation('ID_ENABLED');
        }

        //mcrypt  info
        $info->mcrypt->result = extension_loaded('mcrypt');
        $info->mcrypt->version = $info->mcrypt->result ? G::LoadTranslation('ID_ENABLED') : G::LoadTranslation('ID_NOT_ENABLED');

        // ldap info
        $info->ldap->result = false;
        $info->ldap->version = G::LoadTranslation('ID_NOT_ENABLED');
        if (function_exists('ldap_connect')) {
            $info->ldap->result = true;
            $info->ldap->version = G::LoadTranslation('ID_ENABLED');
        }

        // memory limit verification
        $memory = (int)ini_get('memory_limit');
        $info->memory->version = $memory . 'M';
        $info->memory->result = $memory > 255;

        return $info;
    }

    public function is_dir_writable($path)
    {
        return G::is_writable_r($path);
    }

    public function getPermissionInfo()
    {
        $this->setResponseType('json');
        $info = new StdClass();
        $info->success = true;
        $noWritableFiles = [];
        $noWritable = G::LoadTranslation('ID_INDEX_NOT_WRITEABLE');
        $writable = G::LoadTranslation('ID_WRITEABLE');

        // pathConfig
        $info->pathConfig = new stdclass();
        $info->pathConfig->message = $noWritable;
        $info->pathConfig->result = G::is_writable_r($_REQUEST['pathConfig'], $noWritableFiles);
        if ($info->pathConfig->result) {
            $info->pathConfig->message = $writable;
        } else {
            $info->success = false;
        }

        $info->pathLanguages = new stdclass();
        $info->pathLanguages->message = $noWritable;
        $info->pathLanguages->result = G::is_writable_r($_REQUEST['pathLanguages'], $noWritableFiles);
        if ($info->pathLanguages->result) {
            $info->pathLanguages->message = $writable;
        } else {
            $info->success = false;
        }

        $info->pathPlugins = new stdclass();
        $info->pathPlugins->message = $noWritable;
        $info->pathPlugins->result = G::is_writable_r($_REQUEST['pathPlugins'], $noWritableFiles);
        if ($info->pathPlugins->result) {
            $info->pathPlugins->message = $writable;
        } else {
            $info->success = false;
        }

        $info->pathXmlforms = new stdclass();
        $info->pathXmlforms->message = $noWritable;
        $info->pathXmlforms->result = G::is_writable_r($_REQUEST['pathXmlforms'], $noWritableFiles);
        if ($info->pathXmlforms->result) {
            $info->pathXmlforms->message = $writable;
        } else {
            $info->success = false;
        }

        $info->pathTranslations = new stdclass();
        $info->pathTranslations->message = G::LoadTranslation('ID_TRANSLATION_NOT_WRITEABLE');
        $info->pathTranslations->result = G::is_writable_r($_REQUEST['pathTranslations'], $noWritableFiles);
        if ($info->pathTranslations->result) {
            $info->pathTranslations->message = $writable;
        } else {
            $info->success = false;
        }

        $info->pathTranslationsMafe = new stdclass();
        $info->pathTranslationsMafe->message = G::LoadTranslation('ID_MAFE_TRANSLATION_NOT_WRITEABLE');
        $info->pathTranslationsMafe->result = G::is_writable_r($_REQUEST['pathTranslationsMafe'], $noWritableFiles);
        if ($info->pathTranslationsMafe->result) {
            $info->pathTranslationsMafe->message = $writable;
        } else {
            $info->success = false;
        }

        $info->pathPublic = new stdclass();

        $info->pathShared = new stdclass();
        $info->pathPublic->message = $noWritable;
        $info->pathPublic->result = G::is_writable_r($_REQUEST['pathPublic'], $noWritableFiles);
        if ($info->pathPublic->result) {
            $info->pathShared->message = $writable;
        } else {
            $info->success = false;
        }

        $info->pathShared->message = $noWritable;
        $info->pathShared->result = G::is_writable_r($_REQUEST['pathShared'], $noWritableFiles);
        if ($info->pathShared->result) {
            $info->pathShared->message = $writable;
        } else {
            //Verify and create the shared path
            G::verifyPath($_REQUEST['pathShared'], true);
            $info->pathShared->result = G::is_writable_r($_REQUEST['pathShared'], $noWritableFiles);
            if ($info->pathShared->result) {
                $info->pathShared->message = $writable;
                $info->success = $this->verifySharedFrameworkPaths($_REQUEST['pathShared']);
            } else {
                $info->success = false;
            }
        }

        $filter = new InputFilter();
        $pathShared = $filter->validateInput($_REQUEST['pathShared'], 'path');

        if ($info->pathShared->result) {
            $aux = pathinfo($_REQUEST['pathLogFile']);
            G::verifyPath($aux['dirname'], true);
            if (is_dir($aux['dirname'])) {
                if (!file_exists($_REQUEST['pathLogFile'])) {
                    @file_put_contents($_REQUEST['pathLogFile'], '');
                    @chmod($pathShared, 0770);
                }
            }
        }

        $info->pathLogFile = new stdclass();
        $info->pathLogFile->message = G::LoadTranslation('ID_CREATE_LOG_INSTALLATION');
        $info->pathLogFile->result = file_exists($_REQUEST['pathLogFile']);

        if ($info->pathLogFile->result) {
            $info->pathLogFile->message = G::LoadTranslation('ID_INSTALLATION_FILE_LOG');
        }

        $info->notify = $info->success ? G::LoadTranslation('ID_SUCCESS_DIRECTORIES_WRITABLE') : G::LoadTranslation('ID_DIRECTORIES_NOT_WRITABLE');

        $info->noWritableFiles = $noWritableFiles;

        return $info;
    }

    /**
     * Test db connection
     *
     * @return StdClass
     */
    public function testConnection()
    {
        $this->setResponseType('json');
        $info = new StdClass();
        try {
            switch ($_REQUEST['db_engine']) {
                case 'mysql':
                case 'mysqli':
                    $info = $this->testMySQLConnection();
                    break;
                case 'mssql':
                    $info = $this->testMSSQLConnection();
                    break;
            }
        } catch (Exception $e) {
            $info->result = false;
            $info->message = G::LoadTranslation('DBCONNECTIONS_MSGA');
        }
        return $info;
    }

    /**
     * log the queries and other information to install.log,
     * the install.log files should be placed in shared/logs
     * for that reason we are using the $_REQUEST of pathShared
     */
    private function installLog($text)
    {
        //if this function is called outside the createWorkspace, just returns and do nothing
        if (!isset($_REQUEST['pathShared'])) {
            return;
        }
        //log file is in shared/logs
        $pathShared = trim($_REQUEST['pathShared']);
        if (substr($pathShared, -1) !== '/') {
            $pathShared .= '/';
        }
        $pathSharedLog = $pathShared . 'log/';
        G::verifyPath($pathSharedLog, true);
        $logFile = $pathSharedLog . 'install.log';

        if (!is_file($logFile)) {
            G::mk_dir(dirname($pathShared));
            $fpt = fopen($logFile, 'w');
            if ($fpt !== null) {
                fwrite($fpt, sprintf("%s %s\n", date('Y:m:d H:i:s'), '----- ' . G::LoadTranslation('ID_STARTING_LOG_FILE') . ' ------'));
                fclose($fpt);
            } else {
                throw new Exception(G::LoadTranslation('ID_FILE_NOT_WRITEABLE', SYS_LANG, [$logFile]));
                return $false;
            }
        }

        $filter = new InputFilter();
        $logFile = $filter->validateInput($logFile, 'path');

        $fpt = fopen($logFile, 'a');
        fwrite($fpt, sprintf("%s %s\n", date('Y:m:d H:i:s'), trim($text)));
        fclose($fpt);
        return true;
    }

    /**
     * function to create a workspace
     * in fact this function is calling appropriate functions for mysql and mssql
     * need permission PM_SETUP_ADVANCE for this action
     * @return stdClass information create a workspace.
     */
    public function createWorkspace()
    {
        if (!$this->pmIsInstalled()) {
            $pathSharedPartner = trim($_REQUEST['pathShared']);
            if (file_exists(trim($pathSharedPartner, PATH_SEP) . PATH_SEP . 'partner.info')) {
                $this->systemName = $this->getSystemName($pathSharedPartner);
                $_REQUEST['PARTNER_FLAG'] = true;
            }
            $this->setResponseType('json');
            $info = new StdClass();
            try {
                switch ($_REQUEST['db_engine']) {
                    case 'mysql':
                        $info = $this->createMySQLWorkspace();
                        break;
                    case 'mssql':
                        $info = $this->createMSSQLWorkspace();
                        break;
                }
            } catch (Exception $e) {
                $info->result = false;
                $info->message = G::LoadTranslation('DBCONNECTIONS_MSGA');
            }

            return $info;
        } else {
            $this->displayError();
        }
    }

    /**
     * We check if processMaker is not installed
     *
     * @return boolean
     */
    private function pmIsInstalled()
    {
        return file_exists(FILE_PATHS_INSTALLED);
    }

    /**
     * Display an error when processMaker is already installed
     *
     * @return void
     */
    private function displayError()
    {
        $this->setJSVar('messageError', G::LoadTranslation('ID_PROCESSMAKER_ALREADY_INSTALLED'));
        $this->includeExtJS('installer/stopInstall');
        $this->setView('installer/mainStopInstall');
        G::RenderPage('publish', 'extJs');
    }

    public function forceTogenerateTranslationsFiles($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, G::browserCacheFilesUrl((G::is_https() ? "https://" : "http://") . $_SERVER["HTTP_HOST"] . "/js/ext/translation.en.js?r=" . rand(1, 10000)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Send a query to MSSQL and log the query
     *
     * @param string $sql
     *
     * @return boolean
     * @throws Exception
     */
    public function mssqlQuery($sql)
    {
        $filter = new InputFilter();
        $sql = $filter->preventSqlInjection($sql, array());
        $this->installLog($sql);
        $query = @mssql_query($sql, $this->link);
        if (!$query) {
            $errorMessage = mssql_get_last_message();
            $this->installLog(G::LoadTranslation('ID_MYSQL_ERROR', SYS_LANG, array($errorMessage)));
            throw (new Exception($errorMessage));
            return false;
        }
        @mssql_free_result($query);
        return true;
    }

    /**
     * query_sql_file send many statements to server
     *
     * @param string $file
     * @param string $connection
     * @return array $report
     */
    public function mysqlFileQuery($file)
    {
        if (!is_file($file)) {
            throw new Exception(G::LoadTranslation('ID_SQL_FILE_INVALID', SYS_LANG, [$file]));
            return $false;
        }
        $this->installLog(G::LoadTranslation('ID_PROCESING', SYS_LANG, [$file]));
        $startTime = microtime(true);
        //New Update, to support more complex queries

        $lines = file($file);
        $previous = null;
        DB::connection(self::CONNECTION_INSTALL)
            ->statement("SET NAMES 'utf8'");
        foreach ($lines as $j => $line) {
            $line = trim($line); // Remove comments from the script

            if (strpos($line, '--') === 0) {
                $line = substr($line, 0, strpos($line, '--'));
            }

            if (empty($line)) {
                continue;
            }

            if (strpos($line, '#') === 0) {
                $line = substr($line, 0, strpos($line, '#'));
            }

            if (empty($line)) {
                continue;
            }

            // Concatenate the previous line, if any, with the current
            if ($previous) {
                $line = $previous . ' ' . $line;
            }
            $previous = null;

            // If the current line doesnt end with ; then put this line together
            // with the next one, thus supporting multi-line statements.
            if (strrpos($line, ';') !== strlen($line) - 1) {
                $previous = $line;
                continue;
            }

            $line = substr($line, 0, strrpos($line, ';'));
            DB::connection(self::CONNECTION_INSTALL)
                ->statement($line);
        }

        $endTime = microtime(true);
        $this->installLog(G::LoadTranslation('ID_FILE_PROCESSED', SYS_LANG, [basename($file), $endTime - $startTime]));
        return true;
    }

    /**
     * query_sql_file send many statements to server
     *
     * @param string $file
     * @param string $connection
     * @return array $report
     */
    public function mssqlFileQuery($file)
    {
        if (!is_file($file)) {
            throw (new Exception(G::LoadTranslation('ID_SQL_FILE_INVALID', SYS_LANG, array($file))));
            return $false;
        }
        $this->installLog(G::LoadTranslation('ID_PROCESING', SYS_LANG, array($file)));
        $startTime = microtime(true);
        $content = file_get_contents($file);
        $queries = explode(';', $content);

        foreach ($queries as $sql) {
            $query = @mssql_query($sql, $this->link);
            if (!$query) {
                $errorMessage = mssql_get_last_message();
                $this->installLog(G::LoadTranslation('ID_MYSQL_ERROR', SYS_LANG, array($errorMessage . G::LoadTranslation('ID_QUERY') . ": " . $sql)));
                throw (new Exception($errorMessage));
                return false;
            }
        }
        $endTime = microtime(true);
        $this->installLog(G::LoadTranslation('ID_FILE_PROCESSED', SYS_LANG, array(basename($file), $endTime - $startTime)));
        return true;
    }

    /**
     * set Grant Privileges for MySQL
     *
     * @param string $psUser
     * @param string $psPassword
     * @param string $psDatabase
     * @param string $host
     *
     * @throws Exception
     */
    private function setGrantPrivilegesMySQL($psUser, $psPassword, $psDatabase, $host)
    {
        try {
            $host = $host === 'localhost' || $host === '127.0.0.1' ? 'localhost' : '%';

            $query = "GRANT ALL PRIVILEGES ON `$psDatabase`.* TO $psUser@'$host' IDENTIFIED BY '$psPassword' WITH GRANT OPTION";
            DB::connection(self::CONNECTION_INSTALL)
                ->statement($query);

            $this->installLog($query);

        } catch (QueryException $e) {
            $this->installLog(G::LoadTranslation('ID_MYSQL_ERROR', SYS_LANG, [$e->getMessage()]));
            throw new Exception($e->getMessage());
        }
    }

    /**
     * set Grant Privileges for SQLServer
     *
     * @param string $psUser
     * @param string $psPassword
     * @param string $psDatabase
     * @return void
     */
    public function setGrantPrivilegesMSSQL($psUser, $psPassword, $psDatabase)
    {
        $query = sprintf("IF  EXISTS (SELECT * FROM sys.server_principals WHERE name = N'%s') DROP LOGIN [%s]", $psUser, $psUser);
        $this->mssqlQuery($query);

        $query = sprintf("CREATE LOGIN [%s] WITH PASSWORD=N'%s', DEFAULT_DATABASE=[%s], CHECK_EXPIRATION=OFF, CHECK_POLICY=OFF ", $psUser, $psPassword, $psDatabase);
        $this->mssqlQuery($query);

        $query = sprintf("USE %s;", $psDatabase);
        $this->mssqlQuery($query);

        $query = sprintf("IF  EXISTS (SELECT * FROM sys.database_principals WHERE name = N'%s') DROP USER [%s]", $psUser, $psUser);
        $this->mssqlQuery($query);

        $query = sprintf("CREATE USER %s FOR LOGIN %s;", $psUser, $psUser);
        $this->mssqlQuery($query);

        $query = sprintf("sp_addrolemember 'db_owner', '%s' ", $psUser);
        $this->mssqlQuery($query);

        $query = sprintf("sp_addrolemember 'db_ddladmin', '%s' ", $psUser);
        $this->mssqlQuery($query);

        $query = sprintf("sp_addrolemember 'db_accessadmin', '%s' ", $psUser);
        $this->mssqlQuery($query);

        $query = sprintf("use master ");
        $this->mssqlQuery($query);

        return true;
    }

    /**
     * Create a workspace in a MySQL database
     *
     * @return StdClass object
     */
    public function createMySQLWorkspace()
    {
        $filter = new InputFilter();
        ini_set('max_execution_time', '0');
        $info = new StdClass();
        $info->result = false;
        $info->message = '';
        $info->canRedirect = true;

        $db_hostname = trim($_REQUEST['db_hostname']);
        $db_port = trim($_REQUEST['db_port']);
        $db_port = $filter->validateInput($db_port);
        $db_username = trim($_REQUEST['db_username']);
        $db_username = $filter->validateInput($db_username);
        $db_password = urlencode(trim($_REQUEST['db_password']));
        $db_password = urldecode($filter->validateInput($db_password));
        $wf = trim($_REQUEST['wfDatabase']);
        $workspace = trim($_REQUEST['workspace']);
        $pathShared = trim($_REQUEST['pathShared']);
        $adminPassword = trim($_REQUEST['adminPassword']);
        $adminPassword = $filter->validateInput($adminPassword);
        $adminUsername = trim($_REQUEST['adminUsername']);
        $adminUsername = $filter->validateInput($adminUsername);
        $deleteDB = $_REQUEST['deleteDB'] === 'true';
        $userLogged = isset($_REQUEST['userLogged']) ? $_REQUEST['userLogged'] === 'true' : false;
        $userLogged = $filter->validateInput($userLogged);

        if (substr($pathShared, -1) !== '/') {
            $pathShared .= '/';
        }

        $this->installLog('-------------------------------------------');
        $this->installLog(G::LoadTranslation('ID_CREATING_WORKSPACE', SYS_LANG, [$workspace]));

        try {
            self::setNewConnection(self::CONNECTION_TEST_INSTALL, $db_hostname, $db_username, $db_password, '', $db_port);
            $db_host = ($db_port != '' && $db_port != 3306) ? $db_hostname . ':' . $db_port : $db_hostname;

            $this->installLog(G::LoadTranslation('ID_CONNECT_TO_SERVER', SYS_LANG, [$db_hostname, $db_port, $db_username]));

            if ($deleteDB) {
                $query = sprintf('DROP DATABASE IF EXISTS %s', $wf);
                DB::connection(self::CONNECTION_TEST_INSTALL)->statement($query);
            }

            // CREATE databases wf_workflow
            DB::connection(self::CONNECTION_TEST_INSTALL)
                ->statement("CREATE DATABASE IF NOT EXISTS $wf DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

            self::setNewConnection(self::CONNECTION_INSTALL, $db_hostname, $db_username, $db_password, $wf, $db_port);

            // CREATE users and GRANT Privileges
            $wf_workspace = $wf;
            $wfGrantUser = uniqid('wf_');
            if (!$userLogged) {
                $wfPass = G::generate_password(15);
                $this->setGrantPrivilegesMySQL($wfGrantUser, $wfPass, $wf, $db_hostname);
            } else {
                $wfPass = $db_password;
                $wf = $db_username;
                $wfGrantUser = $db_username;
            }

            // Generate the db.php file and folders
            $path_site = $pathShared . '/sites/' . $workspace . '/';

            @mkdir($path_site, 0777, true);
            @mkdir($path_site . 'files/', 0777, true);
            @mkdir($path_site . 'mailTemplates/', 0777, true);
            @mkdir($path_site . 'public/', 0777, true);
            @mkdir($path_site . 'reports/', 0777, true);
            @mkdir($path_site . 'xmlForms', 0777, true);

            $db_file = $path_site . 'db.php';
            $dbText = "<?php\n";
            $dbText .= sprintf("// Processmaker configuration\n");
            $dbText .= sprintf("  define ('DB_ADAPTER',     '%s' );\n", 'mysql');
            $dbText .= sprintf("  define ('DB_HOST',        '%s' );\n", $db_host);
            $dbText .= sprintf("  define ('DB_NAME',        '%s' );\n", $wf_workspace);
            $dbText .= sprintf("  define ('DB_USER',        '%s' );\n", $wfGrantUser);
            $dbText .= sprintf("  define ('DB_PASS',        '%s' );\n", $wfPass);
            $dbText .= sprintf("  define ('DB_RBAC_HOST',   '%s' );\n", $db_host);
            $dbText .= sprintf("  define ('DB_RBAC_NAME',   '%s' );\n", $wf_workspace);
            $dbText .= sprintf("  define ('DB_RBAC_USER',   '%s' );\n", $wfGrantUser);
            $dbText .= sprintf("  define ('DB_RBAC_PASS',   '%s' );\n", $wfPass);
            $dbText .= sprintf("  define ('DB_REPORT_HOST', '%s' );\n", $db_host);
            $dbText .= sprintf("  define ('DB_REPORT_NAME', '%s' );\n", $wf_workspace);
            $dbText .= sprintf("  define ('DB_REPORT_USER', '%s' );\n", $wfGrantUser);
            $dbText .= sprintf("  define ('DB_REPORT_PASS', '%s' );\n", $wfPass);

            if (defined('PARTNER_FLAG') || isset($_REQUEST['PARTNER_FLAG'])) {
                $dbText .= "\n";
                $dbText .= "  define ('PARTNER_FLAG', " . (defined('PARTNER_FLAG') ? PARTNER_FLAG : isset($_REQUEST['PARTNER_FLAG']) ? $_REQUEST['PARTNER_FLAG'] : 'false') . ");\n";
                if (!empty($this->systemName)) {
                    $dbText .= "  define ('SYSTEM_NAME', '" . $this->systemName . "');\n";
                }
            }

            $this->installLog(G::LoadTranslation('ID_CREATING', SYS_LANG, [$db_file]));
            file_put_contents($db_file, $dbText);

            /*----------------------------------********---------------------------------*/
            //Generate the env.ini file
            $envIniFile = $path_site . 'env.ini';
            $content = 'system_utc_time_zone = 1' . "\n";

            $this->installLog(G::LoadTranslation('ID_CREATING', SYS_LANG, [$envIniFile]));
            file_put_contents($envIniFile, $content);
            /*----------------------------------********---------------------------------*/

            //Generate the databases.php file
            $databases_file = $path_site . 'databases.php';
            $dbData = sprintf("\$dbAdapter    = '%s';\n", 'mysql');
            $dbData .= sprintf("\$dbHost       = '%s';\n", $db_host);
            $dbData .= sprintf("\$dbName       = '%s';\n", $wf_workspace);
            $dbData .= sprintf("\$dbUser       = '%s';\n", $wf);
            $dbData .= sprintf("\$dbPass       = '%s';\n", $wfPass);
            $dbData .= sprintf("\$dbRbacHost   = '%s';\n", $db_host);
            $dbData .= sprintf("\$dbRbacName   = '%s';\n", $wf_workspace);
            $dbData .= sprintf("\$dbRbacUser   = '%s';\n", $wf);
            $dbData .= sprintf("\$dbRbacPass   = '%s';\n", $wfPass);
            $dbData .= sprintf("\$dbReportHost = '%s';\n", $db_host);
            $dbData .= sprintf("\$dbReportName = '%s';\n", $wf_workspace);
            $dbData .= sprintf("\$dbReportUser = '%s';\n", $wf);
            $dbData .= sprintf("\$dbReportPass = '%s';\n", $wfPass);
            $databasesText = str_replace('{dbData}', $dbData, @file_get_contents(PATH_HOME . 'engine/templates/installer/databases.tpl'));

            $this->installLog(G::LoadTranslation('ID_CREATING', SYS_LANG, array($databases_file)));
            file_put_contents($databases_file, $databasesText);

            $this->mysqlFileQuery(PATH_RBAC_HOME . 'engine/data/mysql/schema.sql');
            $this->mysqlFileQuery(PATH_RBAC_HOME . 'engine/data/mysql/insert.sql');
            $this->mysqlFileQuery(PATH_HOME . 'engine/data/mysql/schema.sql');
            $this->mysqlFileQuery(PATH_HOME . 'engine/data/mysql/insert.sql');


            if (defined('PARTNER_FLAG') || isset($_REQUEST['PARTNER_FLAG'])) {
                $this->setPartner();
            }

            // Create the triggers
            if (file_exists(PATH_HOME . 'engine/methods/setup/setupSchemas/triggerAppDelegationInsert.sql') &&
                file_exists(PATH_HOME . 'engine/methods/setup/setupSchemas/triggerAppDelegationUpdate.sql') &&
                file_exists(PATH_HOME . 'engine/methods/setup/setupSchemas/triggerApplicationUpdate.sql') &&
                file_exists(PATH_HOME . 'engine/methods/setup/setupSchemas/triggerApplicationDelete.sql') &&
                file_exists(PATH_HOME . 'engine/methods/setup/setupSchemas/triggerSubApplicationInsert.sql') &&
                file_exists(PATH_HOME . 'engine/methods/setup/setupSchemas/triggerContentUpdate.sql')) {
                DB::connection(self::CONNECTION_INSTALL)->unprepared(file_get_contents(PATH_HOME . 'engine/methods/setup/setupSchemas/triggerAppDelegationInsert.sql'));
                DB::connection(self::CONNECTION_INSTALL)->unprepared(file_get_contents(PATH_HOME . 'engine/methods/setup/setupSchemas/triggerAppDelegationUpdate.sql'));
                DB::connection(self::CONNECTION_INSTALL)->unprepared(file_get_contents(PATH_HOME . 'engine/methods/setup/setupSchemas/triggerApplicationUpdate.sql'));
                DB::connection(self::CONNECTION_INSTALL)->unprepared(file_get_contents(PATH_HOME . 'engine/methods/setup/setupSchemas/triggerApplicationDelete.sql'));
                DB::connection(self::CONNECTION_INSTALL)->unprepared(file_get_contents(PATH_HOME . 'engine/methods/setup/setupSchemas/triggerSubApplicationInsert.sql'));
                DB::connection(self::CONNECTION_INSTALL)->unprepared(file_get_contents(PATH_HOME . 'engine/methods/setup/setupSchemas/triggerContentUpdate.sql'));

                DB::connection(self::CONNECTION_INSTALL)
                    ->table('CONFIGURATION')
                    ->insert([
                        'CFG_UID' => 'APP_CACHE_VIEW_ENGINE',
                        'CFG_VALUE' => serialize(['LANG' => 'en', 'STATUS' => 'active'])
                    ]);

                DB::connection(self::CONNECTION_INSTALL)
                    ->table('EMAIL_SERVER')
                    ->insert([
                        'MESS_UID' => Common::generateUID(),
                        'MESS_ENGINE' => 'MAIL'
                    ]);
            }

            // Change admin user
            DB::connection(self::CONNECTION_INSTALL)
                ->table('USERS')
                ->where('USR_UID', '00000000000000000000000000000001')
                ->update([
                    'USR_USERNAME' => $adminUsername,
                    'USR_LASTNAME' => $adminUsername,
                    'USR_PASSWORD' => G::encryptHash($adminPassword)
                ]);

            DB::connection(self::CONNECTION_INSTALL)
                ->table('RBAC_USERS')
                ->where('USR_UID', '00000000000000000000000000000001')
                ->update([
                    'USR_USERNAME' => $adminUsername,
                    'USR_LASTNAME' => $adminUsername,
                    'USR_PASSWORD' => G::encryptHash($adminPassword)
                ]);
            // Write the paths_installed.php file (contains all the information configured so far)
            if (!file_exists(FILE_PATHS_INSTALLED)) {
                $sh = G::encryptOld(filemtime(PATH_GULLIVER . '/class.g.php'));
                $h = G::encrypt($db_host . $sh . $db_username . $sh . $db_password, $sh);
                $dbText = "<?php\n";
                $dbText .= sprintf("  define('PATH_DATA',         '%s');\n", $pathShared);
                $dbText .= sprintf("  define('PATH_C',            '%s');\n", $pathShared . 'compiled/');
                $dbText .= sprintf("  define('HASH_INSTALLATION', '%s');\n", $h);
                $dbText .= sprintf("  define('SYSTEM_HASH',       '%s');\n", $sh);
                $this->installLog(G::LoadTranslation('ID_CREATING', SYS_LANG, [FILE_PATHS_INSTALLED]));
                file_put_contents(FILE_PATHS_INSTALLED, $dbText);
            }

            // for new env conf handling
            $envFile = PATH_CONFIG . 'env.ini';

            // getting configuration from env.ini
            $sysConf = System::getSystemConfiguration($envFile);

            $langUri = 'en';
            if (isset($sysConf['default_lang'])) {
                $langUri = $sysConf['default_lang'];
            }

            $skinUri = 'neoclassic';
            if (isset($sysConf['default_skin'])) {
                $skinUri = $sysConf['default_skin'];
            }

            $updatedConf['default_lang'] = $langUri;
            $updatedConf['default_skin'] = $skinUri;
            $info->uri = PATH_SEP . 'sys' . $_REQUEST['workspace'] . PATH_SEP . $langUri . PATH_SEP . $skinUri . PATH_SEP . 'login' . PATH_SEP . 'login';

            //register PMDesigner Client
            $http = G::is_https() ? 'https' : 'http';
            $host = $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] !== '80' ? ':' . $_SERVER['SERVER_PORT'] : '');

            $endpoint = sprintf(
                '%s://%s/sys%s/%s/%s/oauth2/grant',
                $http,
                $host,
                $workspace,
                $langUri,
                $skinUri
            );

            // inserting the outh_client
            DB::connection(self::CONNECTION_INSTALL)
                ->table('OAUTH_CLIENTS')
                ->insert([
                    'CLIENT_ID' => 'x-pm-local-client',
                    'CLIENT_SECRET' => '179ad45c6ce2cb97cf1029e212046e81',
                    'CLIENT_NAME' => 'PM Web Designer',
                    'CLIENT_DESCRIPTION' => 'ProcessMaker Web Designer App',
                    'CLIENT_WEBSITE' => 'www.processmaker.com',
                    'REDIRECT_URI' => $endpoint,
                    'USR_UID' => '00000000000000000000000000000001'
                ]);
            
            if (!empty(config('oauthClients.mobile.clientId'))) {
                DB::connection(self::CONNECTION_INSTALL)
                    ->table('OAUTH_CLIENTS')
                    ->insert([
                        'CLIENT_ID' => config('oauthClients.mobile.clientId'),
                        'CLIENT_SECRET' => config('oauthClients.mobile.clientSecret'),
                        'CLIENT_NAME' => config('oauthClients.mobile.clientName'),
                        'CLIENT_DESCRIPTION' => config('oauthClients.mobile.clientDescription'),
                        'CLIENT_WEBSITE' => config('oauthClients.mobile.clientWebsite'),
                        'REDIRECT_URI' => $endpoint,
                        'USR_UID' => '00000000000000000000000000000001'
                ]);
            }

            $indexFileUpdated = true;
            if (defined('PARTNER_FLAG') || isset($_REQUEST['PARTNER_FLAG'])) {
                $this->buildParternExtras($adminUsername, $adminPassword, $_REQUEST['workspace'], $langUri, $skinUri);
            } else {
                try {
                    G::update_php_ini($envFile, $updatedConf);
                } catch (Exception $e) {
                    $info->result = false;
                    $info->message = G::LoadTranslation('ID_PROCESSMAKER_WRITE_CONFIG_INDEX', SYS_LANG, [$envFile]);
                    $info->message .= G::LoadTranslation('ID_PROCESSMAKER_UI_NOT_INSTALL');
                    $this->installLog(G::LoadTranslation('ID_INSTALL_BUT_ERROR', SYS_LANG, ['env.ini']));
                    return $info;
                }

                try {
                    // update the main index file
                    $indexFileUpdated = System::updateIndexFile(['lang' => 'en', 'skin' => $updatedConf['default_skin']]);
                } catch (Exception $e) {
                    $info->result = false;
                    $info->message = G::LoadTranslation('ID_PROCESSMAKER_WRITE_CONFIG_INDEX', SYS_LANG, [PATH_HTML . "index.html."]);
                    $info->message .= G::LoadTranslation('ID_PROCESSMAKER_UI_NOT_INSTALL');
                    $this->installLog(G::LoadTranslation('ID_INSTALL_BUT_ERROR', SYS_LANG, ['index.html']));
                    return $info;
                }
            }

            $this->installLog(G::LoadTranslation('ID_INDEX_FILE_UPDATED', SYS_LANG, [$indexFileUpdated, $sysConf['default_lang'], $sysConf['default_skin']]));
            $this->installLog(G::LoadTranslation('ID_INSTALL_SUCESS'));

            $info->result = true;
            $info->message = G::LoadTranslation('ID_INSTALL_SUCESS');
            $info->messageFinish = G::LoadTranslation('ID_PROCESSMAKER_SUCCESS_INSTALLED', SYS_LANG, [$workspace]);
        } catch (Exception $e) {
            $info->canRedirect = false;
            $info->result = false;
            $info->message = $e->getMessage();
        }
        return $info;
    }

    public function createMSSQLWorkspace()
    {
        $filter = new InputFilter();
        ini_set('max_execution_time', '0');

        $info = new stdClass();
        $info->result = false;
        $info->message = '';

        $db_hostname = trim($_REQUEST['db_hostname']);
        $db_hostname = $filter->validateInput($db_hostname);
        $db_port = trim($_REQUEST['db_port']);
        $db_port = $filter->validateInput($db_port);
        $db_username = trim($_REQUEST['db_username']);
        $db_username = $filter->validateInput($db_username);
        $db_password = urlencode(trim($_REQUEST['db_password']));
        $db_password = urldecode($filter->validateInput($db_password));
        $wf = trim($_REQUEST['wfDatabase']);
        $rb = trim($_REQUEST['wfDatabase']);
        $rp = trim($_REQUEST['wfDatabase']);
        $workspace = trim($_REQUEST['workspace']);
        $pathConfig = trim($_REQUEST['pathConfig']);
        $pathLanguages = trim($_REQUEST['pathLanguages']);
        $pathPlugins = trim($_REQUEST['pathPlugins']);
        $pathShared = trim($_REQUEST['pathShared']);
        $pathXmlforms = trim($_REQUEST['pathXmlforms']);
        $adminPassword = trim($_REQUEST['adminPassword']);
        $adminUsername = trim($_REQUEST['adminUsername']);
        $deleteDB = ($_REQUEST['deleteDB'] == 'true');

        if (substr($pathShared, -1) != '/') {
            $pathShared .= '/';
        }

        $this->installLog('-------------------------------------------');
        $this->installLog(G::LoadTranslation('ID_CREATING_WORKSPACE', SYS_LANG, array($workspace)));

        try {
            $db_host = ($db_port != '' && $db_port != 1433) ? $db_hostname . ':' . $db_port : $db_hostname;
            $this->link = @mssql_connect($db_host, $db_username, $db_password);
            $this->installLog(G::LoadTranslation('ID_CONNECT_TO_SERVER', SYS_LANG, array($db_hostname, $db_port, $db_username)));

            $this->mssqlQuery('USE [master]');

            // DROP databases wf_workflow, rb_workflow and rp_workflow
            if ($deleteDB) {
                $q = sprintf("IF EXISTS (SELECT name FROM sys.databases WHERE name='%s' ) DROP DATABASE %s", $wf, $wf);
                $this->mssqlQuery($q);
            }

            // CREATE databases wf_workflow, rb_workflow and rp_workflow
            $q = sprintf("IF NOT EXISTS (SELECT * FROM sys.databases WHERE name='%s' ) CREATE DATABASE %s", $wf, $wf);
            $this->mssqlQuery($q);

            //CREATE users and GRANT Privileges
            $wfPass = G::generate_password(15);
            $this->setGrantPrivilegesMSSQL($wf, $wfPass, $wf);

            //Generate the db.php file and folders
            $path_site = $pathShared . "/sites/" . $workspace . "/";
            $db_file = $path_site . "db.php";
            mkdir($path_site, 0777, true);
            @mkdir($path_site . "files/", 0777, true);
            @mkdir($path_site . "mailTemplates/", 0777, true);
            @mkdir($path_site . "public/", 0777, true);
            @mkdir($path_site . "reports/", 0777, true);
            @mkdir($path_site . "xmlForms", 0777, true);

            $dbText = "<?php\n";
            $dbText .= sprintf("// Processmaker configuration\n");
            $dbText .= sprintf("  define ('DB_ADAPTER',     '%s' );\n", 'mssql');
            $dbText .= sprintf("  define ('DB_HOST',        '%s' );\n", $db_host);
            $dbText .= sprintf("  define ('DB_NAME',        '%s' );\n", $wf);
            $dbText .= sprintf("  define ('DB_USER',        '%s' );\n", $wf);
            $dbText .= sprintf("  define ('DB_PASS',        '%s' );\n", $wfPass);
            $dbText .= sprintf("  define ('DB_RBAC_HOST',   '%s' );\n", $db_host);
            $dbText .= sprintf("  define ('DB_RBAC_NAME',   '%s' );\n", $wf);
            $dbText .= sprintf("  define ('DB_RBAC_USER',   '%s' );\n", $wf);
            $dbText .= sprintf("  define ('DB_RBAC_PASS',   '%s' );\n", $wfPass);
            $dbText .= sprintf("  define ('DB_REPORT_HOST', '%s' );\n", $db_host);
            $dbText .= sprintf("  define ('DB_REPORT_NAME', '%s' );\n", $wf);
            $dbText .= sprintf("  define ('DB_REPORT_USER', '%s' );\n", $wf);
            $dbText .= sprintf("  define ('DB_REPORT_PASS', '%s' );\n", $wfPass);
            if (defined('PARTNER_FLAG') || isset($_REQUEST['PARTNER_FLAG'])) {
                $dbText .= "\n";
                $dbText .= "  define ('PARTNER_FLAG', " . ((defined('PARTNER_FLAG')) ? PARTNER_FLAG : ((isset($_REQUEST['PARTNER_FLAG'])) ? $_REQUEST['PARTNER_FLAG'] : 'false')) . ");\n";
                if ($this->systemName != '') {
                    $dbText .= "  define ('SYSTEM_NAME', '" . $this->systemName . "');\n";
                }
            }

            $this->installLog(G::LoadTranslation('ID_CREATING', SYS_LANG, array($db_file)));
            file_put_contents($db_file, $dbText);

            // Generate the databases.php file
            $databases_file = $path_site . 'databases.php';
            $dbData = sprintf("\$dbAdapter    = '%s';\n", 'mssql');
            $dbData .= sprintf("\$dbHost       = '%s';\n", $db_host);
            $dbData .= sprintf("\$dbName       = '%s';\n", $wf);
            $dbData .= sprintf("\$dbUser       = '%s';\n", $wf);
            $dbData .= sprintf("\$dbPass       = '%s';\n", $wfPass);
            $dbData .= sprintf("\$dbRbacHost   = '%s';\n", $db_host);
            $dbData .= sprintf("\$dbRbacName   = '%s';\n", $wf);
            $dbData .= sprintf("\$dbRbacUser   = '%s';\n", $wf);
            $dbData .= sprintf("\$dbRbacPass   = '%s';\n", $wfPass);
            $dbData .= sprintf("\$dbReportHost = '%s';\n", $db_host);
            $dbData .= sprintf("\$dbReportName = '%s';\n", $wf);
            $dbData .= sprintf("\$dbReportUser = '%s';\n", $wf);
            $dbData .= sprintf("\$dbReportPass = '%s';\n", $wfPass);
            $databasesText = str_replace('{dbData}', $dbData, @file_get_contents(PATH_HOME . 'engine/templates/installer/databases.tpl'));

            $this->installLog(G::LoadTranslation('ID_CREATING', SYS_LANG, array($databases_file)));
            file_put_contents($databases_file, $databasesText);

            //execute scripts to create and populates databases
            $query = sprintf("USE %s;", $wf);
            $this->mssqlQuery($query);

            $this->mssqlFileQuery(PATH_RBAC_HOME . 'engine/data/mssql/schema.sql');
            $this->mssqlFileQuery(PATH_RBAC_HOME . 'engine/data/mssql/insert.sql');

            $query = sprintf("USE %s;", $wf);
            $this->mssqlQuery($query);
            $this->mssqlFileQuery(PATH_HOME . 'engine/data/mssql/schema.sql');
            $this->mssqlFileQuery(PATH_HOME . 'engine/data/mssql/insert.sql');

            // Create the triggers
            if (file_exists(PATH_HOME . 'engine/plugins/enterprise/data/triggerAppDelegationInsert.sql') && file_exists(PATH_HOME . 'engine/plugins/enterprise/data/triggerAppDelegationUpdate.sql') && file_exists(PATH_HOME . 'engine/plugins/enterprise/data/triggerApplicationUpdate.sql') && file_exists(PATH_HOME . 'engine/plugins/enterprise/data/triggerApplicationDelete.sql') && file_exists(PATH_HOME . 'engine/plugins/enterprise/data/triggerContentUpdate.sql')) {
                $this->mssqlQuery(@file_get_contents(PATH_HOME . 'engine/plugins/enterprise/data/triggerAppDelegationInsert.sql'));
                $this->mssqlQuery(@file_get_contents(PATH_HOME . 'engine/plugins/enterprise/data/triggerAppDelegationUpdate.sql'));
                $this->mssqlQuery(@file_get_contents(PATH_HOME . 'engine/plugins/enterprise/data/triggerApplicationUpdate.sql'));
                $this->mssqlQuery(@file_get_contents(PATH_HOME . 'engine/plugins/enterprise/data/triggerApplicationDelete.sql'));
                $this->mssqlQuery(@file_get_contents(PATH_HOME . "engine/methods/setup/setupSchemas/triggerSubApplicationInsert.sql"));
                $this->mssqlQuery(@file_get_contents(PATH_HOME . 'engine/plugins/enterprise/data/triggerContentUpdate.sql'));
                $this->mssqlQuery("INSERT INTO CONFIGURATION (
                            CFG_UID,
                            CFG_VALUE
                           )
                           VALUES (
                             'APP_CACHE_VIEW_ENGINE',
                             '" . addslashes(serialize(array('LANG' => 'en', 'STATUS' => 'active'
                    ))) . "'
                           )");

                $this->mssqlQuery("INSERT INTO EMAIL_SERVER(MESS_UID, MESS_ENGINE) VALUES('" . Common::generateUID() . "','MAIL')");
            }

            //change admin user
            $query = sprintf("USE %s;", $wf);
            $this->mssqlQuery($query);

            $query = sprintf("UPDATE USERS SET USR_USERNAME = '%s', USR_PASSWORD = '%s' WHERE USR_UID = '00000000000000000000000000000001' ", $adminUsername, G::encryptHash($adminPassword));
            $this->mssqlQuery($query);

            $query = sprintf("USE %s;", $wf);
            $this->mssqlQuery($query);

            $query = sprintf("UPDATE RBAC_USERS SET USR_USERNAME = '%s', USR_PASSWORD = '%s' WHERE USR_UID = '00000000000000000000000000000001' ", $adminUsername, G::encryptHash($adminPassword));
            $this->mssqlQuery($query);

            // Write the paths_installed.php file (contains all the information configured so far)
            if (!file_exists(FILE_PATHS_INSTALLED)) {
                $sh = G::encryptOld(filemtime(PATH_GULLIVER . '/class.g.php'));
                $h = G::encrypt($db_hostname . $sh . $db_username . $sh . $db_password . '1', $sh);
                $dbText = "<?php\n";
                $dbText .= sprintf("  define ('PATH_DATA',        '%s' );\n", $pathShared);
                $dbText .= sprintf("  define ('PATH_C',           '%s' );\n", $pathShared . 'compiled/');
                $dbText .= sprintf("  define ('HASH_INSTALLATION', '%s' );\n", $h);
                $dbText .= sprintf("  define ('SYSTEM_HASH',       '%s' );\n", $sh);
                $this->installLog(G::LoadTranslation('ID_CREATING', SYS_LANG, array(FILE_PATHS_INSTALLED)));
                file_put_contents(FILE_PATHS_INSTALLED, $dbText);
            }
            $this->installLog(G::LoadTranslation('ID_INSTALL_SUCESS'));
            $info->result = true;
            $info->message = G::LoadTranslation('ID_INSTALL_SUCESS');
            $info->url = '/sys' . $_REQUEST['workspace'] . '/en/neoclassic/login/login';
            $info->messageFinish = G::LoadTranslation('ID_PROCESSMAKER_SUCCESS_INSTALLED', SYS_LANG, array($workspace));
        } catch (Exception $e) {
            $info->result = false;
            $info->message = $e->getMessage();
        }
        return $info;
    }

    public function getSystemName($siteShared)
    {
        $systemName = '';
        if (substr($siteShared, -1) != '/') {
            $siteShared .= '/';
        }

        if (file_exists($siteShared . 'partner.info')) {
            $dataInfo = parse_ini_file($siteShared . 'partner.info');
            if (isset($dataInfo['system_name'])) {
                $systemName = trim($dataInfo['system_name']);
            }
        }
        return $systemName;
    }

    /**
     * Get the Database engines list
     *
     * @return object
     */
    public function getEngines()
    {
        $this->setResponseType('json');
        $engines = [];
        if (function_exists('mysqli_query')) {
            $engine = new stdclass();
            $engine->id = 'mysql';
            $engine->label = 'MySQL';
            $engines[] = $engine;
        }
        /**
         * DISABLED TEMPORARELY
         * if (function_exists('mssql_query')) {
         * $engine = new stdclass();
         * $engine->id = 'mssql';
         * $engine->label = 'Microsoft SQL Server';
         * $engines[] = $engine;
         * }
         */
        return $engines;
    }

    public function checkDatabases()
    {
        $filter = new InputFilter();
        $this->setResponseType('json');
        $info = new stdclass();

        $db_hostname = $filter->validateInput($_REQUEST['db_hostname']);
        $db_username = $filter->validateInput($_REQUEST['db_username']);
        $db_password = urlencode($_REQUEST['db_password']);
        $db_password = urldecode($filter->validateInput($db_password));
        $db_port = $filter->validateInput($_REQUEST['db_port']);

        switch ($_REQUEST['db_engine']) {
            case 'mysql':
                $wfDatabase = $filter->validateInput($_REQUEST['wfDatabase'], 'nosql');

                self::setNewConnection(self::CONNECTION_TEST_INSTALL, $db_hostname, $db_username, $db_password, '', $db_port);
                $response = DB::connection(self::CONNECTION_TEST_INSTALL)
                    ->select("show databases like '$wfDatabase'");

                $info->wfDatabaseExists = count($response) > 0;
                break;
            case 'mssql':
                $link = @mssql_connect($db_hostname, $db_username, $db_password);
                $wfDatabase = $filter->validateInput($_REQUEST['wfDatabase'], 'nosql');
                $query = "select * from sys.databases where name = '%s' ";
                $query = $filter->preventSqlInjection($query, array($wfDatabase));
                $dataSet = @mssql_query($query, $link);
                $info->wfDatabaseExists = (@mssql_num_rows($dataSet) > 0);
                break;
            case 'sqlsrv':
                $arguments = array("UID" => $db_username, "PWD" => $db_password);
                $link = @sqlsrv_connect($db_hostname, $arguments);
                $wfDatabase = $filter->validateInput($_REQUEST['wfDatabase'], 'nosql');
                $query = "select * from sys.databases where name = '%s' ";
                $query = $filter->preventSqlInjection($query, array($wfDatabase));
                $dataSet = @sqlsrv_query($link, $query);
                $info->wfDatabaseExists = (@sqlsrv_num_rows($dataSet) > 0);
                break;
            default:
                break;
        }

        $info->errMessage = G::LoadTranslation('ID_DATABASE_EXISTS_OVERWRITE');

        return $info;
    }

    /**
     * Privates functions section, non callable by http request
     */

    private function testMySQLConnection()
    {
        try {
            $filter = new InputFilter();
            $info = new StdClass();
            $info->result = false;
            $info->message = '';
            if (!function_exists('mysqli_connect')) {
                $info->message = G::LoadTranslation('ID_PHP_MYSQLI_NOT_INSTALL');
                return $info;
            }
            $dataRequest = $_REQUEST;
            $db_hostname = $filter->validateInput($dataRequest['db_hostname']);
            $db_port = $filter->validateInput($dataRequest['db_port']);
            $db_username = $filter->validateInput($dataRequest['db_username']);
            $db_password = urlencode($dataRequest['db_password']);
            $db_password = urldecode($filter->validateInput($db_password));
            $fp = @fsockopen($db_hostname, $db_port, $errno, $errstr, 30);
            if (!$fp) {
                $info->message .= G::LoadTranslation('ID_CONNECTION_ERROR', SYS_LANG, ["$errstr ($errno)"]);
                return $info;
            }

            $db_username = $filter->validateInput($db_username, 'nosql');
            $db_hostname = $filter->validateInput($db_hostname, 'nosql');

            self::setNewConnection(self::CONNECTION_TEST_INSTALL, $db_hostname, $db_username, $db_password, '', $db_port);
            $query = "SELECT * FROM `information_schema`.`USER_PRIVILEGES` where (GRANTEE = \"'$db_username'@'$db_hostname'\" OR GRANTEE = \"'$db_username'@'%%'\") ";

            $response = DB::connection(self::CONNECTION_TEST_INSTALL)->select($query);

            if (!is_array($response)) {
                $info->message .= G::LoadTranslation('ID_CONNECTION_ERROR_PRIVILEGE', SYS_LANG, [$db_username]);
                return $info;
            }
            $info->message .= G::LoadTranslation('ID_MYSQL_SUCCESS_CONNECT');
            $info->result = true;
        } catch (Exception $e) {
            $info->result = false;
            $info->message = G::LoadTranslation('ID_MYSQL_CREDENTIALS_WRONG');
        }
        return $info;
    }

    /**
     * This function test a SQL Server connection
     *
     * @return object
     */
    private function testMSSQLConnection()
    {
        $filter = new InputFilter();
        $info = new stdClass();
        $info->result = false;
        $info->message = '';

        if (!function_exists("mssql_connect")) {
            $info->message = G::LoadTranslation('ID_PHP_MSSQL_NOT_INSTALLED');
            return $info;
        }
        $dataRequest = $_REQUEST;
        $db_hostname = $filter->validateInput($dataRequest['db_hostname']);
        $db_port = $filter->validateInput($dataRequest['db_port']);
        $db_username = $filter->validateInput($dataRequest['db_username']);
        $db_password = urlencode($dataRequest['db_password']);
        $db_password = urldecode($filter->validateInput($db_password));

        $fp = @fsockopen($db_hostname, $db_port, $errno, $errstr, 30);
        if (!$fp) {
            $info->message .= G::LoadTranslation('ID_CONNECTION_ERROR', SYS_LANG, array("$errstr ($errno)"));
            return $info;
        }
        \Illuminate\Support\Facades\DB::connection();

        $db_host = ($db_port != '' && $db_port != 1433) ? $db_hostname . ':' . $db_port : $db_hostname;

        $link = @mssql_connect($db_host, $db_username, $db_password);
        if (!$link) {
            $info->message .= G::LoadTranslation('ID_MYSQL_CREDENTIALS_WRONG');
            return $info;
        }

        //checking if user has the dbcreator role
        $hasDbCreator = false;
        $hasSecurityAdmin = false;
        $hasSysAdmin = false;

        $res = @mssql_query("EXEC sp_helpsrvrolemember 'dbcreator' ", $link);
        $row = mssql_fetch_array($res);
        while (is_array($row)) {
            if ($row['MemberName'] == $db_username) {
                $hasDbCreator = true;
            }
            $row = mssql_fetch_array($res);
        }
        mssql_free_result($res);

        $res = @mssql_query("EXEC sp_helpsrvrolemember 'sysadmin' ", $link);
        $row = mssql_fetch_array($res);
        while (is_array($row)) {
            if ($row['MemberName'] == $db_username) {
                $hasSysAdmin = true;
            }
            $row = mssql_fetch_array($res);
        }
        mssql_free_result($res);

        $res = @mssql_query("EXEC sp_helpsrvrolemember 'SecurityAdmin' ", $link);
        $row = mssql_fetch_array($res);
        while (is_array($row)) {
            if ($row['MemberName'] == $db_username) {
                $hasSecurityAdmin = true;
            }
            $row = mssql_fetch_array($res);
        }
        mssql_free_result($res);

        if (!($hasSysAdmin || ($hasSecurityAdmin && $hasDbCreator))) {
            $info->message .= G::LoadTranslation('ID_CONNECTION_ERROR_SECURITYADMIN', SYS_LANG, array($db_username));
            return $info;
        }

        $info->message .= G::LoadTranslation('ID_MSSQL_SUCCESS_CONNECT');
        $info->result = true;
        return $info;
    }

    /**
     * This function define the partner behaviour when the PARTNER_FLAG is defined
     * Execute to change of skin
     *
     * @return void
     */
    private function setPartner()
    {
        if (defined('PARTNER_FLAG') || isset($_REQUEST['PARTNER_FLAG'])) {
            // Execute sql for partner
            $pathMysqlPartner = PATH_CORE . 'data' . PATH_SEP . 'partner' . PATH_SEP . 'mysql' . PATH_SEP;
            if (G::verifyPath($pathMysqlPartner)) {
                $filesSlq = glob($pathMysqlPartner . '*.sql');
                foreach ($filesSlq as $value) {
                    $this->mysqlFileQuery($value);
                }
            }

            // Execute to change of skin
            $pathSkinPartner = PATH_CORE . 'data' . PATH_SEP . 'partner' . PATH_SEP . 'skin' . PATH_SEP;
            if (G::verifyPath($pathSkinPartner)) {
                $fileTar = glob($pathSkinPartner . '*.tar');
                foreach ($fileTar as $value) {
                    $dataFile = pathinfo($value);
                    $nameSkinTmp = $dataFile['filename'];

                    $tar = new Archive_Tar($value);

                    $pathSkinTmp = $pathSkinPartner . 'tmp' . PATH_SEP;
                    G::rm_dir($pathSkinTmp);
                    G::verifyPath($pathSkinTmp, true);
                    chmod($pathSkinTmp, 0777);
                    $tar->extract($pathSkinTmp);

                    $pathSkinName = $pathSkinTmp . $nameSkinTmp . PATH_SEP;
                    chmod($pathSkinName, 0777);
                    G::verifyPath(PATH_CORE . 'skinEngine' . PATH_SEP . 'tmp', true);
                    $skinClassic = PATH_CORE . 'skinEngine' . PATH_SEP . 'tmp' . PATH_SEP;

                    if (is_dir($pathSkinName)) {
                        $this->copyFile($pathSkinName, $skinClassic);
                    }

                    G::rm_dir(PATH_CORE . 'skinEngine' . PATH_SEP . 'base');
                    rename(PATH_CORE . 'skinEngine' . PATH_SEP . 'tmp', PATH_CORE . 'skinEngine' . PATH_SEP . 'base');
                    G::rm_dir(PATH_CORE . 'skinEngine' . PATH_SEP . 'tmp');

                    break;
                }
            }
        }
    }

    /**
     * Copy a directory or file
     *
     * @param string $fromDir
     * @param string $toDir
     * @param integer $chmod
     *
     * @return void
     */
    public function copyFile($fromDir, $toDir, $chmod = 0777)
    {
        $errors = [];
        $messages = [];

        if (!is_writable($toDir)) {
            $errors[] = 'target ' . $toDir . ' is not writable';
        }
        if (!is_dir($toDir)) {
            $errors[] = 'target ' . $toDir . ' is not a directory';
        }
        if (!is_dir($fromDir)) {
            $errors[] = 'source ' . $fromDir . ' is not a directory';
        }
        if (!empty($errors)) {
            return false;
        }

        $exceptions = array('.', '..');
        $handle = opendir($fromDir);
        while (false !== ($item = readdir($handle))) {
            if (!in_array($item, $exceptions)) {
                $from = str_replace('//', '/', $fromDir . '/' . $item);
                $to = str_replace('//', '/', $toDir . '/' . $item);
                if (is_file($from)) {
                    if (@copy($from, $to)) {
                        chmod($to, $chmod);
                        touch($to, filemtime($from));
                    }
                }

                if (is_dir($from)) {
                    if (@mkdir($to)) {
                        chmod($to, $chmod);
                    }
                    $this->copyFile($from, $to, $chmod);
                }
            }
        }

        closedir($handle);
    }

    /**
     * Define build Pattern Extras related to:
     * Upload translation .po file
     * Upload skin file
     * Upload plugin file
     * Active plugins to enterprise
     *
     * @param string $username
     * @param string $password
     * @param string $workspace
     * @param string $lang
     * @param string $skinName
     *
     * @return void
     */
    private function buildParternExtras($username, $password, $workspace, $lang, $skinName)
    {
        $filter = new InputFilter();
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '256M');

        $serv = 'http://';
        if (isset($_SERVER['HTTPS']) && !empty(trim($_SERVER['HTTPS']))) {
            $serv = 'https://';
        }
        $serv .= $_SERVER['SERVER_NAME'];
        if (isset($_SERVER['SERVER_PORT']) && !empty(trim($_SERVER['SERVER_PORT']))) {
            $serv .= ':' . $_SERVER['SERVER_PORT'];
        }

        // create session
        $cookiefile = sys_get_temp_dir() . PATH_SEP . 'curl-session';

        $fp = fopen($cookiefile, "w");
        fclose($fp);
        chmod($cookiefile, 0777);

        $user = urlencode($username);
        $user = $filter->validateInput($user);
        $pass = urlencode($password);
        $pass = $filter->validateInput($pass);
        $lang = urlencode($lang);
        $lang = $filter->validateInput($lang);

        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, "$serv/sys{$workspace}/{$lang}/{$skinName}/login/authentication");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "form[USR_USERNAME]=$user&form[USR_PASSWORD]=$pass&form[USER_LANG]=$lang");
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);

        $output = curl_exec($ch);
        curl_close($ch);

        /**
         * Upload translation .po file
         */

        $ch = curl_init();
        $postData = [];
        // File to upload/post

        $postData['form[LANGUAGE_FILENAME]'] = "@" . PATH_CORE . "content/translations/processmaker.$lang.po";
        curl_setopt($ch, CURLOPT_URL, "$serv/sys{$workspace}/{$lang}/{$skinName}/setup/languages_Import");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);

        $output = curl_exec($ch);
        curl_close($ch);

        /**
         * Upload skin file
         */

        $ch = curl_init();
        $postData = [];

        $skins = glob(PATH_CORE . "data/partner/*.tar");
        if (count($skins) > 0) {
            $skin = $skins[0];

            $postData['overwrite_files'] = "on";
            $postData['workspace'] = "global";
            $postData['option'] = "standardupload";
            $postData['action'] = "importSkin";
            // File to upload/post
            $postData['uploadedFile'] = "@" . $skin;

            curl_setopt($ch, CURLOPT_URL, "$serv/sys{$workspace}/{$lang}/{$skinName}/setup/skin_Ajax");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_TIMEOUT, 90);

            $output = curl_exec($ch);
            curl_close($ch);
        }

        /**
         * Upload plugin file
         */

        $ch = curl_init();
        $postData = [];
        // resolv the plugin name
        $plugins = glob(PATH_CORE . "plugins/*.tar");
        if (count($plugins) > 0) {
            $pluginName = $plugins[0];

            // File to upload/post
            $postData['form[PLUGIN_FILENAME]'] = "@{$pluginName}";
            curl_setopt($ch, CURLOPT_URL, "$serv/sys{$workspace}/{$lang}/{$skinName}/setup/pluginsImportFile");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_TIMEOUT, 90);

            $output = curl_exec($ch);
            curl_close($ch);
        }

        /**
         * Active plugins to enterprise
         */

        if (!defined("PATH_PM_ENTERPRISE")) {
            define("PATH_PM_ENTERPRISE", PATH_CORE . "/plugins/enterprise/");
        }
        set_include_path(PATH_PM_ENTERPRISE . PATH_SEPARATOR . get_include_path());
        require_once('classes/model/AddonsManager.php');

        $plugins = glob(PATH_CORE . "plugins/*.php");
        foreach ($plugins as $value) {
            $dataPlugin = pathinfo($value);
            $namePlugin = $dataPlugin['filename'];
            if ($value !== 'enterprise') {
                $db_hostname = trim($_REQUEST['db_hostname']);
                $db_hostname = $filter->validateInput($db_hostname);
                $db_port = trim($_REQUEST['db_port']);
                $db_port = $filter->validateInput($db_port);
                $db_username = trim($_REQUEST['db_username']);
                $db_username = $filter->validateInput($db_username);
                $db_password = urlencode(trim($_REQUEST['db_password']));
                $db_password = urldecode($filter->validateInput($db_password));
                $wf = trim($_REQUEST['wfDatabase']);
                $wf = $filter->validateInput($wf);

                $db_host = ($db_port != '' && $db_port != 3306) ? $db_hostname . ':' . $db_port : $db_hostname;

                $row = DB::connection(self::CONNECTION_INSTALL)
                    ->table('ADDONS_MANAGER')
                    ->select('STORE_ID')
                    ->where('ADDON_NAME', $namePlugin)
                    ->toArray();

                if ($row) {
                    $ch = curl_init();
                    $postData = [];
                    $postData['action'] = "enable";
                    $postData['addon'] = $namePlugin;
                    $postData['store'] = $row['STORE_ID'];

                    curl_setopt($ch, CURLOPT_URL, "$serv/sys{$workspace}/{$lang}/{$skinName}/enterprise/addonsStoreAction");
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_VERBOSE, 0);
                    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
                    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 90);

                    $output = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }
    }

    /**
     * Verify/create framework shared directory structure
     *
     */
    private function verifySharedFrameworkPaths($sharedPath)
    {
        $paths = [
            $sharedPath . 'framework' => 0770,
            $sharedPath . 'framework' . DIRECTORY_SEPARATOR . 'cache' => 0770,
        ];
        foreach ($paths as $path => $permission) {
            if (!file_exists($path)) {
                G::mk_dir($path, $permission);
            }
            if (!file_exists($path)) {
                return false;
            }
        }
        return true;
    }
}
