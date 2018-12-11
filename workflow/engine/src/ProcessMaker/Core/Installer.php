<?php

namespace ProcessMaker\Core;

use AppCacheView;
use Archive_Tar;
use Bootstrap;
use Configuration;
use Exception;
use G;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use InstallerModule;
use ProcessMaker\Util\Common;

class Installer
{
    public $options = [];
    public $result = [];
    public $error = [];
    public $report = [];
    private $connection_database;

    const CONNECTION_INSTALL = 'install';
    const CONNECTION_TEST_INSTALL = 'testInstall';

    /**
     * construct of insert
     *
     * @param string $pPRO_UID
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * create_site
     *
     * @param array $config
     * @param boolean $confirmed
     * @return array
     */
    public function create_site($config = [], $confirmed = false)
    {
        $this->options = G::array_concat([
            'isset' => false,
            'password' => G::generate_password(15),
            'path_data' => @PATH_DATA,
            'path_compiled' => @PATH_C,
            'name' => $config['name'],
            'database' => [],
            'admin' => ['username' => 'admin', 'password' => 'admin'],
            'advanced' => [
                'ao_db_wf' => 'wf_' . $config['name'],
                'ao_db_rb' => 'rb_' . $config['name'],
                'ao_db_rp' => 'rp_' . $config['name'],
                'ao_db_drop' => false
            ]
        ], $config);
        $configuration = @explode(SYSTEM_HASH, G::decrypt(HASH_INSTALLATION, SYSTEM_HASH));

        $host = explode(':', $configuration[0]);
        if (count($host) < 2) {
            $host[1] = 3306;
        }
        $configuration[0] = $host[0];

        $this->options['database'] = G::array_concat([
            'username' => @$configuration[1],
            'password' => @$configuration[2],
            'hostname' => @$configuration[0],
            'port' => $host[1]
        ], $this->options['database']);

        return $confirmed ? $this->make_site() : $this->create_site_test();
    }

    /**
     * isset_site
     *
     * @param string $name Default value "workflow"
     * @return string file_exists(PATH_DATA."sites/".$name);
     */
    public static function isset_site($name = "workflow")
    {
        $pathSites = PATH_DATA . 'sites/' . '*';
        $directories = glob($pathSites, GLOB_ONLYDIR);
        foreach ($directories as $directory) {
            $site = basename($directory);
            if (strtolower($site) === strtolower($name)) {
                return true;
            }
        }
        return false;
    }

    /**
     * create_site_test
     *
     * @return array
     */
    private function create_site_test()
    {
        $name = preg_match('/^[\w]+$/i', trim($this->options['name'])) ? true : false;
        $result = [
            'path_data' => $this->is_dir_writable($this->options['path_data']),
            'path_compiled' => $this->is_dir_writable($this->options['path_compiled']),
            'database' => $this->check_connection(self::CONNECTION_TEST_INSTALL),
            'access_level' => $this->cc_status,
            'isset' => $this->options['isset'] === true ? $this->isset_site($this->options['name']) : false,
            'microtime' => microtime(),
            'workspace' => $this->options['name'],
            'name' => [
                'status' => $name,
                'message' => $name ? 'PASSED' : 'Workspace name invalid'
            ],
            'admin' => [
                'username' => preg_match('/^[\w@\.-]+$/i', trim($this->options['admin']['username'])) ? true : false,
                'password' => empty(trim($this->options['admin']['password'])) ? false : true
            ]
        ];
        $result['name']['message'] = $result['isset'] ? 'Workspace already exist' : $result['name']['message'];
        $result['name']['status'] = $result['isset'] ? false : $result['name']['status'];
        return [
            'created' => G::var_compare(
                true,
                $result['path_data'],
                $result['database']['connection'],
                $result['name']['status'],
                $result['database']['version'],
                $result['database']['ao']['ao_db_wf']['status'],
                $result['admin']['username'],
                $result['isset'] ? false : true,
                $result['admin']['password']
            ),
            'result' => $result
        ];
    }

    /**
     * make_site
     *
     * @return array $test
     */
    private function make_site()
    {
        $test = $this->create_site_test();

        if ($test["created"] === true || $this->options["advanced"]["ao_db_drop"] === true) {
            /* Check if the hostname is local (localhost or 127.0.0.1) */
            $islocal = (strcmp(substr($this->options['database']['hostname'], 0, strlen('localhost')), 'localhost') === 0) ||
                (strcmp(substr($this->options['database']['hostname'], 0, strlen('127.0.0.1')), '127.0.0.1') === 0);

            $this->wf_site_name = $wf = $this->options['advanced']['ao_db_wf'];
            $this->wf_user_db = isset($this->options['advanced']['ao_user_wf']) ? $this->options['advanced']['ao_user_wf'] : uniqid('wf_');

            $this->rbac_site_name = $rb = $this->options['advanced']['ao_db_rb'];
            $this->report_site_name = $rp = $this->options['advanced']['ao_db_rp'];

            $schema = "schema.sql";
            $values = "insert.sql";

            if ($this->options['advanced']['ao_db_drop'] === true) {
                //Delete workspace directory if exists
                //Drop databases
                $this->run_query('DROP DATABASE IF EXISTS ' . $wf, 'Drop database $wf', self::CONNECTION_TEST_INSTALL);
            }

            $this->run_query('CREATE DATABASE IF NOT EXISTS ' . $wf . ' DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci', "Create database $wf", self::CONNECTION_TEST_INSTALL);


            if ($this->cc_status == 1) {
                $host = ($islocal) ? "localhost" : "%";
                $this->run_query("GRANT ALL PRIVILEGES ON `$wf`.* TO {$this->wf_user_db}@'$host' IDENTIFIED BY '{$this->options['password']}' WITH GRANT OPTION", "Grant privileges for user {$this->wf_user_db} on database $wf", self::CONNECTION_TEST_INSTALL);
            }


            /* Dump schema workflow && data  */

            $this->log("Import database schema:\n");

            InstallerModule::setNewConnection(
                self::CONNECTION_INSTALL,
                $this->options['database']['hostname'],
                $this->options['database']['username'],
                $this->options['database']['password'],
                $this->wf_site_name,
                $this->options['database']['port']);

            $pws = PATH_WORKFLOW_MYSQL_DATA . $schema;
            $qws = $this->query_sql_file(PATH_WORKFLOW_MYSQL_DATA . $schema);
            $this->log($qws, isset($qws['errors']));
            $qwv = $this->query_sql_file(PATH_WORKFLOW_MYSQL_DATA . $values);
            $this->log($qwv, isset($qwv['errors']));

            $http = G::is_https() ? 'https' : 'http';
            $lang = defined('SYS_LANG') ? SYS_LANG : 'en';
            $host = $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] !== '80' ? ':' . $_SERVER['SERVER_PORT'] : '');
            $workspace = $this->options['name'];

            $endpoint = sprintf(
                '%s://%s/sys%s/%s/%s/oauth2/grant',
                $http,
                $host,
                $workspace,
                $lang,
                SYS_SKIN
            );

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

            /* Dump schema rbac && data  */
            $pws = PATH_RBAC_MYSQL_DATA . $schema;
            $qrs = $this->query_sql_file(PATH_RBAC_MYSQL_DATA . $schema);
            $this->log($qrs, isset($qrs['errors']));
            $qrv = $this->query_sql_file(PATH_RBAC_MYSQL_DATA . $values);
            $this->log($qrv, isset($qrv['errors']));

            require_once("propel/Propel.php");
            require_once('classes/model/AppCacheView.php');

            $appCache = new AppCacheView();
            $appCache->setPathToAppCacheFiles(PATH_METHODS . 'setup/setupSchemas/');
            $triggers = $appCache->getTriggers('en');
            $this->log("Create 'cases list cache' triggers");
            foreach ($triggers as $triggerName => $trigger) {
                $this->runTrigger($trigger, "-> Trigger $triggerName");
            }

            $path_site = $this->options['path_data'] . '/sites/' . $this->options['name'] . '/';

            @mkdir($path_site, 0777, true);
            @mkdir($path_site . "files/", 0777, true);
            @mkdir($path_site . "mailTemplates/", 0777, true);
            @mkdir($path_site . "public/", 0777, true);
            @mkdir($path_site . "reports/", 0777, true);
            @mkdir($path_site . "xmlForms", 0777, true);

            //Generate the db.php file
            $hostname = $this->options['database']['hostname'] . ':' . $this->options['database']['port'];
            $username = $this->cc_status === 1 ? $this->wf_user_db : $this->options['database']['username'];
            $password = $this->cc_status === 1 ? $this->options['password'] : $this->options['database']['password'];
            $db_file = $path_site . 'db.php';
            $db_text = "<?php\n"
                    . "// Processmaker configuration\n" 
                    . "  define ('DB_ADAPTER', 'mysql' );\n" 
                    . "  define ('DB_HOST', '" . $hostname . "' );\n" 
                    . "  define ('DB_NAME', '" . $wf . "' );\n" 
                    . "  define ('DB_USER', '" . $username . "' );\n" 
                    . "  define ('DB_PASS', '" . $password . "' );\n" 
                    . "  define ('DB_RBAC_HOST', '" . $hostname . "' );\n" 
                    . "  define ('DB_RBAC_NAME', '" . $rb . "' );\n" 
                    . "  define ('DB_RBAC_USER', '" . $username . "' );\n" 
                    . "  define ('DB_RBAC_PASS', '" . $password . "' );\n" 
                    . "  define ('DB_REPORT_HOST', '" . $hostname . "' );\n" 
                    . "  define ('DB_REPORT_NAME', '" . $rp . "' );\n" 
                    . "  define ('DB_REPORT_USER', '" . $username . "' );\n" 
                    . "  define ('DB_REPORT_PASS', '" . $password . "' );\n"
                    . "";

            if (defined('PARTNER_FLAG') || isset($_REQUEST['PARTNER_FLAG'])) {
                $db_text .= "define ('PARTNER_FLAG', " . ((defined('PARTNER_FLAG') && PARTNER_FLAG != '') ? PARTNER_FLAG : ((isset($_REQUEST['PARTNER_FLAG'])) ? $_REQUEST['PARTNER_FLAG'] : 'false')) . ");\n";
                if (defined('SYSTEM_NAME')) {
                    $db_text .= "  define ('SYSTEM_NAME', '" . SYSTEM_NAME . "');\n";
                }
            }
            $db_text .= "?>";

            $fp = @fopen($db_file, "w");
            $this->log("Create: " . $db_file . "  => " . ((!$fp) ? $fp : "OK") . "\n", $fp === false);
            $ff = @fwrite($fp, $db_text, strlen($db_text));
            $this->log("Write: " . $db_file . "  => " . ((!$ff) ? $ff : "OK") . "\n", $ff === false);
            fclose($fp);

            /*----------------------------------********---------------------------------*/

            //Set data
            $this->setPartner();
            $this->setAdmin();

            DB::connection(self::CONNECTION_INSTALL)
                ->table('EMAIL_SERVER')
                ->insert([
                    'MESS_UID' => Common::generateUID(),
                    'MESS_ENGINE' => 'MAIL'
                ]);
        }
        return $test;
    }

    /**
     * set_partner
     *
     * @return void
     */
    public function setPartner()
    {
        $partnerFlag = (defined('PARTNER_FLAG')) ? PARTNER_FLAG : false;
        if ($partnerFlag) {
            // Execute sql for partner
            $pathMysqlPartner = PATH_CORE . 'data' . PATH_SEP . 'partner' . PATH_SEP . 'mysql' . PATH_SEP;
            if (G::verifyPath($pathMysqlPartner)) {
                $filesSlq = glob($pathMysqlPartner . '*.sql');
                foreach ($filesSlq as $value) {
                    $this->query_sql_file($value);
                }
            }

            // Execute to change of skin
            $pathSkinPartner = PATH_CORE . 'data' . PATH_SEP . 'partner' . PATH_SEP . 'skin' . PATH_SEP;
            if (G::verifyPath($pathSkinPartner)) {
                $res = [];
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

            //ACTIVE ENTERPRISE

            ini_set('max_execution_time', '0');
            ini_set('memory_limit', '256M');

            $serv = 'http://';
            if (isset($_SERVER['HTTPS']) && trim($_SERVER['HTTPS']) != '') {
                $serv = 'https://';
            }
            $serv .= $_SERVER['SERVER_NAME'];
            if (isset($_SERVER['SERVER_PORT']) && trim($_SERVER['SERVER_PORT']) != '') {
                $serv .= ':' . $_SERVER['SERVER_PORT'];
            }

            // create session
            $cookiefile = sys_get_temp_dir() . PATH_SEP . 'curl-session';

            $fp = fopen($cookiefile, "w");
            fclose($fp);
            chmod($cookiefile, 0777);

            $user = urlencode($this->options['admin']['username']);
            $pass = urlencode($this->options['admin']['password']);
            $workspace = $this->options['name'];
            $lang = SYS_LANG;
            $skinName = SYS_SKIN;

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
        }
    }

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
     * set_configuration
     *
     * @return void
     */
    public function setConfiguration()
    {
        $configuration = new Configuration();
        $dataConfig = $configuration->getAll();
        if (count($dataConfig)) {
            foreach ($dataConfig as $value) {
                if ($value['CFG_UID'] == 'ENVIRONMENT_SETTINGS') {
                    $query = 'INSERT INTO CONFIGURATION (CFG_UID, OBJ_UID, CFG_VALUE, PRO_UID, USR_UID, APP_UID) VALUES';
                    $query .= "('" .
                        $value['CFG_UID'] . "', '" .
                        $value['OBJ_UID'] . "', '" .
                        $value['CFG_VALUE'] . "', '" .
                        $value['PRO_UID'] . "', '" .
                        $value['USR_UID'] . "', '" .
                        $value['APP_UID'] . "')";
                    $this->run_query($query, "Copy configuracion environment");
                    break;
                }
            }
        }
    }

    /**
     * set_admin
     *
     * @return void
     */
    public function setAdmin()
    {
        // Change admin user
        DB::connection(self::CONNECTION_INSTALL)
            ->table('USERS')
            ->where('USR_UID', '00000000000000000000000000000001')
            ->update([
                'USR_USERNAME' => $this->options['admin']['username'],
                'USR_PASSWORD' => G::encryptHash($this->options['admin']['password'])
            ]);

        DB::connection(self::CONNECTION_INSTALL)
            ->table('RBAC_USERS')
            ->where('USR_UID', '00000000000000000000000000000001')
            ->update([
                'USR_USERNAME' => $this->options['admin']['username'],
                'USR_PASSWORD' => G::encryptHash($this->options['admin']['password'])
            ]);
    }

    /**
     *  Run a mysql script on the current database and take care of logging and
     * error handling.
     *
     * @param string $query SQL command
     * @param string $description Description to log instead of $query
     * @param string $connection default connection install
     * @throws Exception
     */
    private function runTrigger($query, $description = '', $connection = self::CONNECTION_INSTALL)
    {
        $this->run_query($query, $description, $connection, 'UNPREPARED');
    }

    /**
     *  Run a mysql query on the current database and take care of logging and
     * error handling.
     *
     * @param string $query SQL command
     * @param string $description Description to log instead of $query
     * @param string $connection default connection install
     * @param string $type STATEMENT|RAW
     */
    private function run_query($query, $description = '', $connection = self::CONNECTION_INSTALL, $type = 'STATEMENT')
    {
        try {
            $message = '';
            switch ($type) {
                case 'STATEMENT':
                    DB::connection($connection)->statement($query);
                    break;
                case 'RAW':
                    DB::connection($connection)->raw($query);
                    break;
                case 'UNPREPARED':
                    DB::connection($connection)->unprepared($query);
                    break;
            }

        } catch (QueryException $exception) {
            $message = $exception->getMessage();
        }
        $this->log(!empty($description) ? $description : $query . ' => ' . (!empty($message) ? $message : 'OK') . "\n", !empty($message));
    }

    /**
     * Query sql file
     *
     * @param $file
     * @param string $connection
     */
    public function query_sql_file($file, $connection = self::CONNECTION_INSTALL)
    {
        $lines = file($file);
        $previous = null;
        $errors = '';
        DB::connection($connection)
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
            if (strrpos($line, ';') != strlen($line) - 1) {
                $previous = $line;
                continue;
            }

            $line = substr($line, 0, strrpos($line, ';'));
            DB::connection($connection)
                ->statement($line);
        }
    }

    /**
     * function find_root_path
     *
     * @param string $path
     * @return string $path
     */
    private function find_root_path($path)
    {
        $i = 0; //prevent loop inifinity
        while (!is_dir($path) && ($path = dirname($path)) && ((strlen($path) > 1) && $i < 10)) {
            $i++;
        }
        return $path;
    }

    /**
     * file_permisions
     *
     * @param string $file
     * @param integer $def default value 777
     * @return integer $def
     */
    public function file_permisions($file, $def = 777)
    {
        if (PHP_OS == 'WINNT') {
            return $def;
        } else {
            return (int)substr(sprintf('%o', @fileperms($file)), -4);
        }
    }

    /**
     * is_dir_writable
     *
     * @param string $dir default value empty
     * @return string $path
     */
    public function is_dir_writable($dir = '')
    {
        if (PHP_OS == 'WINNT') {
            $dir = $this->find_root_path($dir);
            return file_exists($dir);
        } else {
            $dir = $this->find_root_path($dir);
            return (is_writable($dir) && is_readable($dir));
        }
    }

    /**
     * getDirectoryFiles
     *
     * @param string $dir default value empty
     * @return array
     */
    public function getDirectoryFiles($dir, $extension)
    {
        $filesArray = [];
        if (file_exists($dir)) {
            if ($handle = opendir($dir)) {
                while (false !== ($file = readdir($handle))) {
                    $fileParts = explode(".", $file);
                    if ($fileParts[count($fileParts) - 1] == $extension) {
                        $filesArray[] = $file;
                    }
                }
                closedir($handle);
            }
        }
        return $filesArray;
    }

    /**
     * check_db_empty
     *
     * @param string$dbName
     * @return boolean true or false
     * @throws Exception
     */
    public function check_db_empty($dbName)
    {
        try
        {
            $result = DB::connection(self::CONNECTION_TEST_INSTALL)->select("show databases like '$dbName'");
            if (!$result) {
                return true;
            } else {
                $result = DB::connection(self::CONNECTION_TEST_INSTALL)->select("show tables from $dbName");
                return !$result;
            }
        } catch (QueryException $exception) {
            throw new Exception('User without permissions. ' . $exception->getMessage());
        }
    }

    /**
     * check_db
     *
     * @param string $dbName
     * @return array Array('status' => true or false,'message' => string)
     */
    public function check_db($dbName)
    {
        $response = [];
        $response['status'] = false;
        $response['message'] = '';
        if (!$this->connection_database) {
            //new verification if the mysql extension is enabled
            $response['message'] = 'Mysql Module for PHP is not enabled!';
        } else {
            if ($this->cc_status != 1) {
                $result = DB::connection(self::CONNECTION_TEST_INSTALL)->select("show databases like '$dbName'");
            } else {
                if ($this->options['advanced']['ao_db_drop'] === true || $this->check_db_empty($dbName)) {
                    $response['status'] = true;
                    $response['message'] = 'PASSED';
                } else {
                    $response['message'] = 'Database is not empty';
                }
            }
        }
        return $response;
    }

    /**
     * check_connection
     *
     * @return array $rt
     */
    private function check_connection($nameConnection)
    {
        $this->cc_status = 0;
        $rt = [
            'connection' => false,
            'grant' => 0,
            'version' => false,
            'message' => 'ERROR: Mysql Module for PHP is not enabled, try install <b>php-mysqli</b> package.',
            'ao' => [
                'ao_db_wf' => false,
                'ao_db_rb' => false,
                'ao_db_rp' => false
            ]
        ];

        if (function_exists('mysqli_connect')) {
            try {
                InstallerModule::setNewConnection(
                    $nameConnection,
                    $this->options['database']['hostname'],
                    $this->options['database']['username'],
                    $this->options['database']['password'],
                    '',
                    $this->options['database']['port']);
                $rt = [
                    'version' => false,
                    'ao' => [
                        'ao_db_wf' => false,
                        'ao_db_rb' => false,
                        'ao_db_rp' => false
                    ]
                ];

                $results = DB::connection($nameConnection)->select(DB::raw('select version()'));

                preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $results[0]->{'version()'}, $version);
                $rt['version'] = version_compare($mysql_version = $version[0], '4.1.0', '>=');
                $rt['connection'] = true;

                $dbNameTest = 'PROCESSMAKERTESTDC';
                $db = DB::connection($nameConnection)->statement("CREATE DATABASE IF NOT EXISTS $dbNameTest");
                $this->connection_database = true;

                if (!$db) {
                    $this->cc_status = 3;
                    $rt['grant'] = 3;
                    $rt['message'] = 'Successful connection';
                } else {
                    $usrTest = 'wfrbtest';
                    $chkG = "GRANT ALL PRIVILEGES ON `" . $dbNameTest . "`.* TO " . $usrTest . "@'%' IDENTIFIED BY '!Sample123' WITH GRANT OPTION";
                    $ch = DB::connection($nameConnection)
                        ->statement($chkG);

                    if (!$ch) {
                        $this->cc_status = 2;
                        $rt['grant'] = 2;
                        $rt['message'] = 'Successful connection';
                    } else {
                        $this->cc_status = 1;
                        DB::connection($nameConnection)
                            ->statement("DROP USER " . $usrTest . "@'%'");
                        $rt['grant'] = 1;
                        $rt['message'] = 'Successful connection';
                    }
                    DB::connection($nameConnection)
                        ->statement('DROP DATABASE ' . $dbNameTest);
                }
            } catch (Exception $exception) {
                $rt['connection'] = false;
                $rt['grant'] = 0;
                $rt['message'] = 'Mysql error: ' . $exception->getMessage();
            }
        }
        $rt['ao']['ao_db_wf'] = $this->check_db($this->options['advanced']['ao_db_wf']);
        return $rt;
    }

    /**
     * Log
     *
     * @param string $text
     * @param boolean $failed
     * @throws Exception
     */
    public function log($text, $failed = false)
    {
        $this->report[] = $text;
        if ($failed) {
            throw new Exception(is_string($text) ? $text : var_export($text, true));
        }
    }
}
