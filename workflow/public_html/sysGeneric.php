<?php

use Illuminate\Foundation\Http\Kernel;
use ProcessMaker\Core\AppEvent;
/*----------------------------------********---------------------------------*/
use ProcessMaker\Plugins\PluginRegistry;

/**
 * bootstrap - ProcessMaker Bootstrap
 * this file is used initialize main variables, redirect and dispatch all requests
 */

function transactionLog($transactionName)
{
    if (extension_loaded('newrelic')) {
        $baseName = "ProcessMaker";

        //Application base name
        newrelic_set_appname($baseName);

        //Initialize Laravel app if is not booted
        if (!app()->isBooted()) {
            app()->make(Kernel::class)->bootstrap();
            restore_error_handler();
        }

        //Custom parameters
        if (!empty(config("system.workspace"))) {
            newrelic_add_custom_parameter("workspace", config("system.workspace"));
        }
        if (defined("SYS_LANG")) {
            newrelic_add_custom_parameter("lang", SYS_LANG);
        }
        if (defined("SYS_SKIN")) {
            newrelic_add_custom_parameter("skin", SYS_SKIN);
        }
        if (defined("SYS_COLLECTION")) {
            newrelic_add_custom_parameter("collection", SYS_COLLECTION);
        }
        if (defined("SYS_TARGET")) {
            newrelic_add_custom_parameter("target", SYS_TARGET);
        }
        if (defined("SYS_URI")) {
            newrelic_add_custom_parameter("uri", SYS_URI);
        }
        if (defined("PATH_CORE")) {
            newrelic_add_custom_parameter("path_core", PATH_CORE);
        }
        if (defined("PATH_DATA_SITE")) {
            newrelic_add_custom_parameter("path_site", PATH_DATA_SITE);
        }

        //Show correct transaction name
        if (!empty(config("system.workspace"))) {
            newrelic_set_appname("PM-" . config("system.workspace") . ";$baseName");
        }
        if (defined("PATH_CORE")) {
            $transactionName = str_replace(PATH_CORE, "", $transactionName);
        }
        newrelic_name_transaction($transactionName);
    }
}

// Validating if exists 'HTTP_USER_AGENT' key in $_SERVER array
if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    $_SERVER['HTTP_USER_AGENT'] = '';
}

// Defining the PATH_SEP constant, he we are defining if the the path separator symbol will be '\\' or '/'
define('PATH_SEP', '/');

// Defining the Home Directory
$realdocuroot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$docuroot = explode(PATH_SEP, $realdocuroot);

array_pop($docuroot);
$pathhome = implode(PATH_SEP, $docuroot) . PATH_SEP;

// try to find automatically the trunk directory where are placed the RBAC and Gulliver directories
// in a normal installation you don't need to change it.
array_pop($docuroot);
$pathTrunk = implode(PATH_SEP, $docuroot) . PATH_SEP;

array_pop($docuroot);
$pathOutTrunk = implode(PATH_SEP, $docuroot) . PATH_SEP;

define('PATH_HOME', $pathhome);
define('PATH_TRUNK', $pathTrunk);
define('PATH_OUTTRUNK', $pathOutTrunk);

//we are focusing in have this behaivour
//1. if the uri is a existing file return the file inmediately
//2. if the uri point to png, jpg, js, or css mapped in other place, return it inmediately
//3. process the uri,

//here we are putting approved CONSTANTS, I mean constants be sure we need,
define('PATH_HTML', PATH_HOME . 'public_html' . PATH_SEP);

//this is the first path, if the file exists...
$request = substr($_SERVER['REQUEST_URI'], 1, strlen($_SERVER['REQUEST_URI'])); //removes the first '/'
$fileWithoutParam = explode("?", $request); // split the URI by '?'
$request = $fileWithoutParam[0];            // get the first element of the split URI
$requestFile = PATH_HTML . $request;        // temporary assemble a path for the file embedded in the URI
if (file_exists($requestFile)) {
    if (!is_file($requestFile)) {
        header("location: /errors/error404.php?url=" . urlencode($_SERVER['REQUEST_URI']));
        die;
    }
    if ($request === "app.php" || $request === "sysGeneric.php") {
        //HTTP/1.0 403 Forbidden
        http_response_code(403);
    }
    $pos = strripos($request, ".") + 1;
    $size = strlen($request);
    if ($pos < $size) {
        //if this file got an extension then assign the content
        $ext_file = substr($request, $pos, $size);
        if ($ext_file == "gif" || $ext_file == "png") {
            $ext_file = 'image/' . $ext_file;
        } elseif ($ext_file == "jpg" || $ext_file == "jpeg") {
            $ext_file = 'image/jpeg';
        } elseif ($ext_file == "swf") {
            $ext_file = "application/x-shockwave-flash";
        } elseif ($ext_file == "json" || $ext_file == "htc") {
            $ext_file = "text/plain";
        } elseif ($ext_file == "htm" || $ext_file == "html" || $ext_file == "txt") {
            $ext_file = "text/html";
        } elseif ($ext_file == "doc" || $ext_file == "pdf" || $ext_file == "pm" || $ext_file == "po") {
            $ext_file = "application/octet-stream";
        } elseif ($ext_file == "tar") {
            $ext_file = "application/x-tar";
        } elseif ($ext_file == "woff") {
            $ext_file = "application/font-woff";
        } elseif ($ext_file == "js") {
            $ext_file = "text/javascript";
        } elseif ($ext_file == "css") {
            //may this line be innecesary, all the .css are been generated at run time
            $ext_file = 'text/css';
        } else {
            $ext_file = "application/octet-stream";
        }
        header('Content-Type: ' . $ext_file);
    }
    header('Pragma: cache');
    $mtime = filemtime($requestFile);
    $gmt_mtime = gmdate("D, d M Y H:i:s", $mtime) . " GMT";
    header('ETag: "' . Bootstrap::encryptOld($mtime . $requestFile) . '"');
    header("Last-Modified: " . $gmt_mtime);
    header('Cache-Control: public');
    $userAgent = strtolower($_SERVER ['HTTP_USER_AGENT']);
    if (preg_match("/msie/i", $userAgent)) {
        header("Expires: " . gmdate("D, d M Y H:i:s", time() + 60 * 10) . " GMT");
    } else {
        header("Expires: " . gmdate("D, d M Y H:i:s", time() + 90 * 60 * 60 * 24) . " GMT");
        if (isset($_SERVER ['HTTP_IF_MODIFIED_SINCE'])) {
            if ($_SERVER ['HTTP_IF_MODIFIED_SINCE'] == $gmt_mtime) {
                header('HTTP/1.1 304 Not Modified');
            }
        }
        if (isset($_SERVER ['HTTP_IF_NONE_MATCH'])) {
            if (str_replace('"', '',
                    stripslashes($_SERVER ['HTTP_IF_NONE_MATCH'])) == Bootstrap::encryptOld($mtime . $requestFile)) {
                header("HTTP/1.1 304 Not Modified");
            }
        }
    }
    readfile($requestFile);
    die;
}


// Defining RBAC Paths constants
define('PATH_RBAC_HOME', PATH_TRUNK . 'rbac' . PATH_SEP);

// Defining Gulliver framework paths constants
define('PATH_GULLIVER_HOME', PATH_TRUNK . 'gulliver' . PATH_SEP);
define('PATH_GULLIVER', PATH_GULLIVER_HOME . 'system' . PATH_SEP); //gulliver system classes
define('PATH_GULLIVER_BIN', PATH_GULLIVER_HOME . 'bin' . PATH_SEP); //gulliver bin classes
define('PATH_TEMPLATE', PATH_GULLIVER_HOME . 'templates' . PATH_SEP);
define('PATH_THIRDPARTY', PATH_TRUNK . 'thirdparty' . PATH_SEP);
define('PATH_RBAC', PATH_RBAC_HOME . 'engine' . PATH_SEP . 'classes' . PATH_SEP); //to enable rbac version 2
define('PATH_RBAC_CORE', PATH_RBAC_HOME . 'engine' . PATH_SEP);

// Defining PMCore Path constants
define('PATH_CORE', PATH_HOME . 'engine' . PATH_SEP);
define('PATH_CLASSES', PATH_HOME . "engine" . PATH_SEP . "classes" . PATH_SEP);
define('PATH_SKINS', PATH_CORE . 'skins' . PATH_SEP);
define('PATH_SKIN_ENGINE', PATH_CORE . 'skinEngine' . PATH_SEP);
define('PATH_METHODS', PATH_CORE . 'methods' . PATH_SEP);
define('PATH_XMLFORM', PATH_CORE . 'xmlform' . PATH_SEP);
define('PATH_CONFIG', PATH_CORE . 'config' . PATH_SEP);
define('PATH_PLUGINS', PATH_CORE . 'plugins' . PATH_SEP);
define('PATH_HTMLMAIL', PATH_CORE . 'html_templates' . PATH_SEP);
define('PATH_TPL', PATH_CORE . 'templates' . PATH_SEP);
define('PATH_TEST', PATH_CORE . 'test' . PATH_SEP);
define('PATH_FIXTURES', PATH_TEST . 'fixtures' . PATH_SEP);
define('PATH_RTFDOCS', PATH_CORE . 'rtf_templates' . PATH_SEP);
define('PATH_DYNACONT', PATH_CORE . 'content' . PATH_SEP . 'dynaform' . PATH_SEP);
//define( 'PATH_LANGUAGECONT',PATH_CORE . 'content' . PATH_SEP . 'languages' . PATH_SEP );
define('SYS_UPLOAD_PATH', PATH_HOME . "public_html/files/");
define('PATH_UPLOAD', PATH_HTML . 'files' . PATH_SEP);

define('PATH_WORKFLOW_MYSQL_DATA', PATH_CORE . 'data' . PATH_SEP . 'mysql' . PATH_SEP);
define('PATH_RBAC_MYSQL_DATA', PATH_RBAC_CORE . 'data' . PATH_SEP . 'mysql' . PATH_SEP);
define('FILE_PATHS_INSTALLED', PATH_CORE . 'config' . PATH_SEP . 'paths_installed.php');
define('PATH_WORKFLOW_MSSQL_DATA', PATH_CORE . 'data' . PATH_SEP . 'mssql' . PATH_SEP);
define('PATH_RBAC_MSSQL_DATA', PATH_RBAC_CORE . 'data' . PATH_SEP . 'mssql' . PATH_SEP);
define('PATH_CONTROLLERS', PATH_CORE . 'controllers' . PATH_SEP);

// include Gulliver Class

if (file_exists(FILE_PATHS_INSTALLED)) {

    // include the server installed configuration
    require_once FILE_PATHS_INSTALLED;

    // defining system constant when a valid server environment exists
    define('PATH_LANGUAGECONT', PATH_DATA . "META-INF" . PATH_SEP);
    define('PATH_CUSTOM_SKINS', PATH_DATA . 'skins' . PATH_SEP);
    define('PATH_TEMPORAL', PATH_C . 'dynEditor/');
    define('PATH_DB', PATH_DATA . 'sites' . PATH_SEP);

    // smarty constants
    define('PATH_SMARTY_C', PATH_C . 'smarty' . PATH_SEP . 'c');
    define('PATH_SMARTY_CACHE', PATH_C . 'smarty' . PATH_SEP . 'cache');

    /* TO DO: put these line in other part of code*/
    Bootstrap::verifyPath(PATH_SMARTY_C, true);
    Bootstrap::verifyPath(PATH_SMARTY_CACHE, true);
}

// set include path
set_include_path(
    PATH_CORE . PATH_SEPARATOR .
    PATH_THIRDPARTY . PATH_SEPARATOR .
    PATH_THIRDPARTY . 'pear' . PATH_SEPARATOR .
    PATH_RBAC_CORE . PATH_SEPARATOR .
    get_include_path()
);

/**
 * Global definitions, before it was the defines.php file
 */

// URL Key
define("URL_KEY", 'c0l0s40pt1mu59r1m3');

// Other definitions
define('TIMEOUT_RESPONSE', 100); //web service timeout
define('APPLICATION_CODE', 'ProcessMaker'); //to login like workflow system
define('MAIN_POFILE', 'processmaker');
define('PO_SYSTEM_VERSION', 'PM 4.0.1');

$G_CONTENT = null;
$G_MESSAGE = "";
$G_MESSAGE_TYPE = "info";
$G_MENU_SELECTED = -1;
$G_MAIN_MENU = "default";

// Environment definitions
define('G_PRO_ENV', 'PRODUCTION');
define('G_DEV_ENV', 'DEVELOPMENT');
define('G_TEST_ENV', 'TEST');

// Number of files per folder at PATH_UPLOAD (cases documents)
define('APPLICATION_DOCUMENTS_PER_FOLDER', 1000);

// Server of ProcessMaker Library
define('PML_SERVER', 'http://library.processmaker.com');
define('PML_WSDL_URL', PML_SERVER . '/syspmLibrary/en/green/services/wsdl');
define('PML_UPLOAD_URL', PML_SERVER . '/syspmLibrary/en/green/services/uploadProcess');
define('PML_DOWNLOAD_URL', PML_SERVER . '/syspmLibrary/en/green/services/download');

G::defineConstants();

$config = Bootstrap::getSystemConfiguration();

// starting session
if (isset($config['session.gc_maxlifetime'])) {
    $timelife = $config['session.gc_maxlifetime'];
} else {
    $timelife = ini_get('session.gc_maxlifetime');
}
if (is_null($timelife)) {
    $timelife = 1440;
}
ini_set('session.gc_maxlifetime', $timelife);
if ((preg_match("/msie/i", $_SERVER ['HTTP_USER_AGENT']) != 1 ||
        $config['ie_cookie_lifetime'] == 1) &&
    (!(preg_match("/safari/i", $_SERVER ['HTTP_USER_AGENT']) == 1 && preg_match("/chrome/i",
                $_SERVER ['HTTP_USER_AGENT']) == 0) ||
        $config['safari_cookie_lifetime'] == 1)) {
    ini_set('session.cookie_lifetime', $timelife);
}
session_start();

if (!(array_key_exists('REMOTE_USER', $_SERVER) && (string)($_SERVER['REMOTE_USER']) != '')) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
}
//$e_all = defined( 'E_DEPRECATED' ) ? E_ALL & ~ E_DEPRECATED : E_ALL;
//$e_all = defined( 'E_STRICT' ) ? $e_all & ~ E_STRICT : $e_all;
//$e_all = $config['debug'] ? $e_all : $e_all & ~ E_NOTICE;
//$e_all = E_ALL & ~ E_DEPRECATED & ~ E_STRICT & ~ E_NOTICE  & ~E_WARNING;

// Do not change any of these settings directly, use env.ini instead
ini_set('display_errors', $config['display_errors']);
ini_set('error_reporting', $config['error_reporting']);
ini_set('short_open_tag', 'On');
ini_set('default_charset', "UTF-8");
ini_set('memory_limit', $config['memory_limit']);
ini_set('soap.wsdl_cache_enabled', $config['wsdl_cache']);
ini_set('date.timezone', $config['time_zone']); //Set Time Zone

define('DEBUG_SQL_LOG', $config['debug_sql']);
define('DEBUG_SQL', $config['debug']);
define('DEBUG_TIME_LOG', $config['debug_time']);
define('DEBUG_CALENDAR_LOG', $config['debug_calendar']);
define('MEMCACHED_ENABLED', $config['memcached']);
define('MEMCACHED_SERVER', $config['memcached_server']);

define('WS_IN_LOGIN', isset($config['WS_IN_LOGIN']) ? $config['WS_IN_LOGIN'] : 'serverconf');

define('LOAD_HEADERS_IE', $config['load_headers_ie']);
define('LEAVE_CASE_WARNING', $config['leave_case_warning']);
define('REDIRECT_TO_MOBILE', $config['redirect_to_mobile']);
define('DISABLE_PHP_UPLOAD_EXECUTION', $config['disable_php_upload_execution']);
define('DISABLE_DOWNLOAD_DOCUMENTS_SESSION_VALIDATION', $config['disable_download_documents_session_validation']);
define('LOGS_MAX_FILES', $config['logs_max_files']);
define('LOGS_LOCATION', $config['logs_location']);
define('LOGGING_LEVEL', $config['logging_level']);

// IIS Compatibility, SERVER_ADDR doesn't exist on that env, so we need to define it.
$_SERVER['SERVER_ADDR'] = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['SERVER_NAME'];

//to do: make different environments.  sys

//check if it is a installation instance
if (!defined('PATH_C')) {
    // is a intallation instance, so we need to define PATH_C and PATH_LANGUAGECONT constants temporarily
    define('PATH_C', (rtrim(Bootstrap::sys_get_temp_dir(), PATH_SEP) . PATH_SEP));
    define('PATH_LANGUAGECONT', PATH_HOME . 'engine/content/languages/');
}

//Load filter class
$skinPathErrors = G::skinGetPathToSrcByVirtualUri("errors", $config);
$skinPathUpdate = G::skinGetPathToSrcByVirtualUri("update", $config);

// defining Virtual URLs
$virtualURITable = [];
$virtualURITable['/plugin/(*)'] = 'plugin';
$virtualURITable['/(sys\w{0,})/(\w{0,}.js)'] = 'jsMethod';
$virtualURITable['/js/(*)'] = PATH_GULLIVER_HOME . 'js/';
$virtualURITable['/jscore/(*)'] = PATH_CORE . 'js/';

if (defined('PATH_C')) {
    $virtualURITable['/jsform/(*.js)'] = PATH_C . 'xmlform/';
    $virtualURITable['/extjs/(*)'] = PATH_C . 'ExtJs/';
}

$virtualURITable['/htmlarea/(*)'] = PATH_THIRDPARTY . 'htmlarea/';
//$virtualURITable['/sys[a-zA-Z][a-zA-Z0-9]{0,}()/'] = 'sysNamed';
$virtualURITable['/(sys*)'] = false;
$virtualURITable["/errors/(*)"] = ($skinPathErrors != "") ? $skinPathErrors : PATH_GULLIVER_HOME . "methods" . PATH_SEP . "errors" . PATH_SEP;
$virtualURITable['/gulliver/(*)'] = PATH_GULLIVER_HOME . 'methods/';
$virtualURITable['/controls/(*)'] = PATH_GULLIVER_HOME . 'methods/controls/';
$virtualURITable['/html2ps_pdf/(*)'] = PATH_THIRDPARTY . 'html2ps_pdf/';
//$virtualURITable['/images/'] = 'errorFile';
//$virtualURITable['/skins/'] = 'errorFile';
//$virtualURITable['/files/'] = 'errorFile';
$virtualURITable["/update/(*)"] = ($skinPathUpdate != "") ? $skinPathUpdate : PATH_GULLIVER_HOME . "methods" . PATH_SEP . "update" . PATH_SEP;
//$virtualURITable['/(*)'] = PATH_HTML;
$virtualURITable['/css/(*)'] = PATH_HTML . 'css/'; //ugly
$virtualURITable['/skin/(*)'] = PATH_HTML;
$virtualURITable['/skins/(*)'] = PATH_HTML . 'skins/'; //ugly
$virtualURITable['/images/(*)'] = PATH_HTML . 'images/'; //ugly
$virtualURITable['/[a-zA-Z][a-zA-Z0-9]{0,}/'] = 'errorFile';

// Verify if we need to redirect or stream the file, if G:VirtualURI returns true means we are going to redirect the page
if (Bootstrap::virtualURI($_SERVER['REQUEST_URI'], $virtualURITable, $realPath)) {
    // review if the file requested belongs to public_html plugin
    if (substr($realPath, 0, 6) == 'plugin') {
        // Another way to get the path of Plugin public_html and stream the correspondent file, By JHL Jul 14, 08
        // TODO: $pathsQuery will be used?
        $pathsQuery = '';
        // Get the query side
        // Did we use this variable $pathsQuery for something??
        $forQuery = explode("?", $realPath);
        if (isset($forQuery[1])) {
            $pathsQuery = $forQuery[1];
        }

        //Get that path in array
        $paths = explode(PATH_SEP, $forQuery[0]);
        //remove the "plugin" word from
        $paths[0] = substr($paths[0], 6);
        //Get the Plugin Folder, always the first element
        $pluginFolder = array_shift($paths);
        //The other parts are the realpath into public_html (no matter how many elements)
        $filePath = implode(PATH_SEP, $paths);
        $pluginFilename = PATH_PLUGINS . $pluginFolder . PATH_SEP . 'public_html' . PATH_SEP . $filePath;

        if (file_exists($pluginFilename)) {
            //NewRelic Snippet - By JHL
            transactionLog($pluginFilename);

            Bootstrap::streamFile($pluginFilename, false, '', true);
        }
        die();
    }

    $requestUriArray = explode("/", $_SERVER['REQUEST_URI']);

    if ((isset($requestUriArray[1])) && ($requestUriArray[1] == 'skin')) {
        // This will allow to public images of Custom Skins, By JHL Feb 28, 11
        $pathsQuery = "";
        // Get the query side
        // This way we remove garbage
        $forQuery = explode("?", $realPath);
        if (isset($forQuery[1])) {
            $pathsQuery = $forQuery[1];
        }

        //Get that path in array
        $paths = explode(PATH_SEP, $forQuery[0]);
        $url = (preg_match("/^(.*)\?.*$/", $_SERVER["REQUEST_URI"],
            $arrayMatch)) ? $arrayMatch[1] : $_SERVER["REQUEST_URI"];

        $fileToBeStreamed = str_replace("/skin/", PATH_CUSTOM_SKINS, $url);

        if (file_exists($fileToBeStreamed)) {
            //NewRelic Snippet - By JHL
            transactionLog($fileToBeStreamed);

            Bootstrap::streamFile($fileToBeStreamed);
        }
        die();
    }

    switch ($realPath) {
        case 'jsMethod':
            Bootstrap::parseURI(getenv("REQUEST_URI"));
            $filename = PATH_METHODS . SYS_COLLECTION . '/' . SYS_TARGET . '.js';
            //NewRelic Snippet - By JHL
            transactionLog($filename);
            Bootstrap::streamFile($filename);
            die();
            break;
        case 'errorFile':
            header('Status: 404');
            header("location: /errors/error404.php?url=" . urlencode($_SERVER['REQUEST_URI']));
            if (DEBUG_TIME_LOG) {
                Bootstrap::logTimeByPage();
            } //log this page
            die();
            break;
        default:
            //Process files loaded with tag head in HTML
            $realPath = explode('?', $realPath);
            $realPath[0] .= strpos(basename($realPath[0]), '.') === false ? '.php' : '';
            //NewRelic Snippet - By JHL
            transactionLog($realPath[0]);

            Bootstrap::streamFile($realPath[0]);
            die();
    }
} //virtual URI parser

// the request correspond to valid php page, now parse the URI
$arrayFriendlyUri = [];
$arrayFriendlyUri['cases/opencase'] = '/^[\w\-]{32}$/';

Bootstrap::parseURI(getenv('REQUEST_URI'), $arrayFriendlyUri);

if (SYS_TARGET === false) {
    header('Status: 404');
    header('Location: /errors/error404.php?url=' . urlencode($_SERVER['REQUEST_URI']));
    exit(0);
}

// Bootstrap::mylog("sys_temp: ".SYS_TEMP);
$arrayUpdating = Bootstrap::isPMUnderUpdating();
if ($arrayUpdating['action']) {
    if ($arrayUpdating['workspace'] == "true" || $arrayUpdating['workspace'] == SYS_TEMP) {
        header("location: /update/updating.php");
        if (DEBUG_TIME_LOG) {
            Bootstrap::logTimeByPage();
        }
        die();
    }
}

// verify if index.html exists
if (!file_exists(PATH_HTML . 'index.html')) { // if not, create it from template
    file_put_contents(PATH_HTML . 'index.html', Bootstrap::parseTemplate(PATH_TPL . 'index.html', array(
        'lang' => ((defined('SYS_LANG') && SYS_LANG != '') ? SYS_LANG : 'en'),
        'skin' => SYS_SKIN
    )));
}

define('SYS_URI', '/sys' . SYS_TEMP . '/' . SYS_LANG . '/' . SYS_SKIN . '/');

// defining the serverConf singleton
if (defined('PATH_DATA') && file_exists(PATH_DATA)) {
    //Instance Server Configuration Singleton
    $oServerConf = ServerConf::getSingleton();
}

// Create headPublisher singleton

$oHeadPublisher = headPublisher::getSingleton();

// Installer, redirect to install if we don't have a valid shared data folder
if (!defined('PATH_DATA') || !file_exists(PATH_DATA)) {
    // new installer, extjs based
    define( 'PATH_DATA', PATH_C );
    
    //important to start laravel classes
    app()->useStoragePath(realpath(PATH_DATA));
    app()->make(Kernel::class)->bootstrap();
    restore_error_handler();

    //NewRelic Snippet - By JHL
    transactionLog(PATH_CONTROLLERS . 'InstallerModule.php');
    $pathFile = PATH_CONTROLLERS . 'InstallerModule.php';
    require_once ($pathFile);
    $controller = InstallerModule::class;

    // if the method name is empty set default to index method
    if (strpos(SYS_TARGET, '/') !== false) {
        list($controller, $controllerAction) = explode('/', SYS_TARGET);
    } else {
        $controllerAction = SYS_TARGET;
    }

    $controllerAction = ($controllerAction != '' && $controllerAction != 'login') ? $controllerAction : 'index';

    // create the installer controller and call its method
    if (is_callable([InstallerModule::class, $controllerAction])) {
        $installer = new $controller();
        $installer->setHttpRequestData($_REQUEST);
        //NewRelic Snippet - By JHL
        transactionLog($controllerAction);

        $installer->call($controllerAction);
    } else {
        $_SESSION['phpFileNotFound'] = $_SERVER['REQUEST_URI'];
        header('Status: 404');
        header("location: /errors/error404.php?url=" . urlencode($_SERVER['REQUEST_URI']));
    }
    die();
}

app()->useStoragePath(realpath(PATH_DATA));
app()->make(Kernel::class)->bootstrap();
restore_error_handler();
//Overwrite with the Processmaker env.ini configuration used in production environments
//@todo: move env.ini configuration to .env
ini_set('display_errors', $config['display_errors']);
ini_set('error_reporting', $config['error_reporting']);
ini_set('short_open_tag', 'On');
ini_set('default_charset', "UTF-8");
ini_set('memory_limit', $config['memory_limit']);
ini_set('soap.wsdl_cache_enabled', $config['wsdl_cache']);
ini_set('date.timezone', $config['time_zone']); //Set Time Zone

// Load Language Translation
Bootstrap::LoadTranslationObject(defined('SYS_LANG') ? SYS_LANG : "en");

// look for a disabled workspace
if ($oServerConf->isWSDisabled(SYS_TEMP)) {
    $aMessage['MESSAGE'] = Bootstrap::LoadTranslation('ID_DISB_WORKSPACE');
    $G_PUBLISH = new Publisher();
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage);
    Bootstrap::RenderPage('publish');
    die();
}

// database and workspace definition
// if SYS_TEMP exists, the URL has a workspace, now we need to verify if exists their db.php file
if (defined('SYS_TEMP') && SYS_TEMP != '') {
    //this is the default, the workspace db.php file is in /shared/workflow/sites/SYS_SYS
    $pathFile = PATH_DB . SYS_TEMP . '/db.php';
    if (file_exists($pathFile)) {
        require_once($pathFile);
        define('SYS_SYS', SYS_TEMP);
        config(["system.workspace" => SYS_TEMP]);

        // defining constant for workspace shared directory
        define('PATH_WORKSPACE', PATH_DB . config("system.workspace") . PATH_SEP);

        // including workspace shared classes -> particularlly for pmTables
        set_include_path(get_include_path() . PATH_SEPARATOR . PATH_WORKSPACE);
    } else {
        if (SYS_LANG != '' && SYS_SKIN != '') {
            Bootstrap::SendTemporalMessage('ID_NOT_WORKSPACE', "error");
            Bootstrap::header('location: /sys/' . SYS_LANG . '/' . SYS_SKIN . '/main/sysLogin?errno=2');
        } else {
            header('Status: 404');
            header('location: /errors/error404.php?url=' . urlencode($_SERVER['REQUEST_URI']));
        }
        die();
    }
} else { //when we are in global pages, outside any valid workspace
    if (SYS_TARGET === 'newSite') {
        $phpFile = G::ExpandPath('methods') . SYS_COLLECTION . "/" . SYS_TARGET . '.php';
        //NewRelic Snippet - By JHL
        transactionLog($phpFile);
        require_once($phpFile);
        die();
    } else {
        if (SYS_TARGET == "dbInfo") { //Show dbInfo when no SYS_SYS
            $pathFile = PATH_METHODS . "login/dbInfo.php";
            require_once($pathFile);
        } else {
            if (substr(SYS_SKIN, 0,
                    2) === 'ux' && SYS_TARGET != 'sysLoginVerify') { // new ux sysLogin - extjs based form
                $pathFile = PATH_CONTROLLERS . 'main.php';
                require_once $pathFile;
                $controllerClass = 'Main';
                $controllerAction = SYS_TARGET == 'sysLoginVerify' ? SYS_TARGET : 'sysLogin';
                //if the method exists
                if (is_callable(array(
                    $controllerClass,
                    $controllerAction
                ))) {
                    $controller = new $controllerClass();
                    $controller->setHttpRequestData($_REQUEST);
                    $controller->call($controllerAction);
                }
            } else { // classic sysLogin interface
                $pathFile = PATH_METHODS . "login/sysLogin.php";
                require_once($pathFile);
                die();
            }
        }
        if (DEBUG_TIME_LOG) {
            Bootstrap::logTimeByPage();
        } //log this page
        die();
    }
}

// PM Paths DATA
define('PATH_DATA_SITE', PATH_DATA . 'sites/' . config("system.workspace") . '/');
define('PATH_DOCUMENT', PATH_DATA_SITE . 'files/');
define('PATH_DATA_MAILTEMPLATES', PATH_DATA_SITE . 'mailTemplates/');
define('PATH_DATA_PUBLIC', PATH_DATA_SITE . 'public/');
define('PATH_DATA_REPORTS', PATH_DATA_SITE . 'reports/');
define('PATH_DYNAFORM', PATH_DATA_SITE . 'xmlForms/');
define('PATH_IMAGES_ENVIRONMENT_FILES', PATH_DATA_SITE . 'usersFiles' . PATH_SEP);
define('PATH_IMAGES_ENVIRONMENT_USERS', PATH_DATA_SITE . 'usersPhotographies' . PATH_SEP);
define('SERVER_NAME', $_SERVER['SERVER_NAME']);
define('SERVER_PORT', $_SERVER['SERVER_PORT']);

// create memcached singleton
$memcache = PMmemcached::getSingleton(config("system.workspace"));

// load Plugins base class

// setup propel definitions and logging
//changed to autoloader

if (defined('DEBUG_SQL_LOG') && DEBUG_SQL_LOG) {
    define('PM_PID', mt_rand(1, 999999));
    require_once 'Log.php';

    // register debug connection decorator driver
    Creole::registerDriver('*', 'creole.contrib.DebugConnection');

    // initialize Propel with converted config file
    Propel::init(PATH_CORE . "config/databases.php");

    // unified log file for all databases
    $logFile = PATH_DATA . 'log' . PATH_SEP . 'propel.log';
    $logger = Log::singleton('file', $logFile, 'wf ' . config("system.workspace"), null, PEAR_LOG_INFO);
    Propel::setLogger($logger);
    // log file for workflow database
    $con = Propel::getConnection('workflow');
    if ($con instanceof DebugConnection) {
        $con->setLogger($logger);
    }
    // log file for rbac database
    $con = Propel::getConnection('rbac');

    if ($con instanceof DebugConnection) {
        $con->setLogger($logger);
    }

    // log file for report database
    $con = Propel::getConnection('rp');
    if ($con instanceof DebugConnection) {
        $con->setLogger($logger);
    }
} else {
    Propel::init(PATH_CORE . "config/databases.php");
}

//here we are loading all plugins registered
//the singleton has a list of enabled plugins
$oPluginRegistry = PluginRegistry::loadSingleton();
$attributes = $oPluginRegistry->getAttributes();
Bootstrap::LoadTranslationPlugins(defined('SYS_LANG') ? SYS_LANG : "en", $attributes);
// Initialization functions plugins
$oPluginRegistry->init();

//Set Time Zone
/*----------------------------------********---------------------------------*/

ini_set('date.timezone',
    (isset($_SESSION['__SYSTEM_UTC_TIME_ZONE__']) && $_SESSION['__SYSTEM_UTC_TIME_ZONE__']) ? 'UTC' : $config['time_zone']); //Set Time Zone

define('TIME_ZONE', ini_get('date.timezone'));

/*----------------------------------********---------------------------------*/

Creole::registerDriver('dbarray', 'creole.contrib.DBArrayConnection');

// Session Initializations
ini_set('session.auto_start', '1');

// The register_globals feature has been DEPRECATED as of PHP 5.3.0. default value Off.
// ini_set( 'register_globals', 'Off' );
//session_start();
ob_start();

// Rebuild the base Workflow translations if not exists
if (!is_file(PATH_LANGUAGECONT . 'translation.en')) {
    $pathFile = PATH_CLASSES . "model" . PATH_SEP . "Translation.php";

    require_once($pathFile);

    $pmTranslation = new Translation();
    $fields = $pmTranslation->generateFileTranslation("en");

    // Load Language Translation
    Bootstrap::LoadTranslationObject("en");
}

// TODO: Verify if the language set into url is defined in translations env.
if (SYS_LANG != 'en' && !is_file(PATH_LANGUAGECONT . 'translation.' . SYS_LANG)) {
    $pathFile = PATH_CLASSES . "model" . PATH_SEP . "Translation.php";

    require_once($pathFile);

    $pmTranslation = new Translation();
    $fields = $pmTranslation->generateFileTranslation(SYS_LANG);

    // Load Language Translation
    Bootstrap::LoadTranslationObject(SYS_LANG);
}

// Setup plugins
$oPluginRegistry->setupPlugins(); //get and setup enabled plugins
$avoidChangedWorkspaceValidation = false;

// Load custom Classes and Model from Plugins.
Bootstrap::LoadAllPluginModelClasses();

// jump to php file in methods directory
$collectionPlugin = '';
if ($oPluginRegistry->isRegisteredFolder(SYS_COLLECTION)) {
    $phpFile = PATH_PLUGINS . SYS_COLLECTION . PATH_SEP . SYS_TARGET . '.php';
    $targetPlugin = explode('/', SYS_TARGET);
    $collectionPlugin = $targetPlugin[0];
    $avoidChangedWorkspaceValidation = true;
} else {
    $phpFile = Bootstrap::ExpandPath('methods') . SYS_COLLECTION . PATH_SEP . SYS_TARGET . '.php';
}

// services is a special folder,
if (SYS_COLLECTION == 'services') {
    $avoidChangedWorkspaceValidation = true;
    $targetPlugin = explode('/', SYS_TARGET);

    if ($targetPlugin[0] == 'webdav') {
        $phpFile = Bootstrap::ExpandPath('methods') . SYS_COLLECTION . PATH_SEP . 'webdav.php';
    }
}

if (SYS_COLLECTION == 'login' && SYS_TARGET == 'login') {
    $avoidChangedWorkspaceValidation = true;
}

$bWE = false;
$isControllerCall = false;
$isPluginController = false;

if (substr(SYS_COLLECTION, 0, 8) === 'gulliver') {
    $phpFile = PATH_GULLIVER_HOME . 'methods/' . substr(SYS_COLLECTION, 8) . SYS_TARGET . '.php';
} else {
    //when the file is part of the public directory of any PROCESS, this a ProcessMaker feature
    if (preg_match('/^[0-9][[:alnum:]]+$/', SYS_COLLECTION) == 1) { //the pattern is /sysSYS/LANG/SKIN/PRO_UID/file
        $auxPart = explode('/', $_SERVER['REQUEST_URI']);
        $aAux = explode('?', $auxPart[count($auxPart) - 1]);
        //$extPart = explode ( '.' , $auxPart[ count($auxPart)-1] );
        $extPart = explode('.', $aAux[0]);
        $queryPart = isset($aAux[1]) ? $aAux[1] : "";
        $extension = $extPart[count($extPart) - 1];
        $phpFile = PATH_DATA_SITE . 'public' . PATH_SEP . SYS_COLLECTION . PATH_SEP . urldecode($auxPart[count($auxPart) - 1]);
        $aAux = explode('?', $phpFile);
        $phpFile = $aAux[0];

        if ($extension != 'php') {
            //NewRelic Snippet - By JHL
            transactionLog($phpFile);
            Bootstrap::streamFile($phpFile);
            die();
        }

        $isWebEntry = \ProcessMaker\BusinessModel\WebEntry::isWebEntry(SYS_COLLECTION, $phpFile);
        if (\Bootstrap::getDisablePhpUploadExecution() === 1 && !$isWebEntry) {
            $message = \G::LoadTranslation('THE_PHP_FILES_EXECUTION_WAS_DISABLED');
            \Bootstrap::registerMonologPhpUploadExecution('phpExecution', 550, $message, $phpFile);
            echo $message;
            die();
        } else {
            //Backward compatibility: Preload PmDynaform for old generated webentry files.
            class_exists('PmDynaform');
            \Bootstrap::registerMonologPhpUploadExecution('phpExecution', 200, 'Php Execution', $phpFile);
        }

        $avoidChangedWorkspaceValidation = true;
        $bWE = true;
        //$phpFile = PATH_DATA_SITE . 'public' . PATH_SEP .  SYS_COLLECTION . PATH_SEP . $auxPart[ count($auxPart)-1];
    }

    //erik: verify if it is a Controller Class or httpProxyController Class
    if (is_file(PATH_CONTROLLERS . SYS_COLLECTION . '.php')) {
        $pathFile = PATH_CONTROLLERS . SYS_COLLECTION . '.php';
        require_once $pathFile;
        $controllerClass = SYS_COLLECTION;
        //if the method name is empty set default to index method
        $controllerAction = SYS_TARGET != '' ? SYS_TARGET : 'index';
        //if the method exists
        if (is_callable(array($controllerClass, $controllerAction))) {
            $isControllerCall = true;
        }

        if (substr(SYS_SKIN, 0, 2) != "ux" && $controllerClass == "main") {
            $isControllerCall = false;
        }
    }

    if (is_dir(PATH_PLUGINS . SYS_COLLECTION) && $oPluginRegistry->isRegisteredFolder(SYS_COLLECTION)) {
        $pluginName = SYS_COLLECTION;
        $pluginResourceRequest = explode('/', rtrim(SYS_TARGET, '/'));
        $isPluginController = true;

        if ($pluginResourceRequest > 0) {
            $controllerClass = $pluginResourceRequest[0];

            if (count($pluginResourceRequest) == 1) {
                $controllerAction = 'index';
            } else {
                $controllerAction = $pluginResourceRequest[1];
            }
        }

        $pluginControllerPath = PATH_PLUGINS . $pluginName . PATH_SEP . 'controllers' . PATH_SEP;

        $pathFile = $pluginControllerPath . $controllerClass . '.php';
        if (is_file($pathFile)) {
            require_once $pathFile;
        } elseif (is_file($pluginControllerPath . ucfirst($controllerClass) . '.php')) {
            $controllerClass = ucfirst($controllerClass);
            require_once $pathFile;
        } elseif (is_file($pluginControllerPath . ucfirst($controllerClass) . 'Controller.php')) {
            $controllerClass = ucfirst($controllerClass) . 'Controller';
            require_once $pathFile;
        }

        //if the method exists
        if (is_callable(array($controllerClass, $controllerAction))) {
            $isControllerCall = true;
        }
    }

    if (!$isControllerCall && !file_exists($phpFile)) {
        $_SESSION['phpFileNotFound'] = $_SERVER['REQUEST_URI'];
        header('Status: 404');
        header("location: /errors/error404.php?url=" . urlencode($_SERVER['REQUEST_URI']));
        die();
    }
}

//redirect to login, if user changed the workspace in the URL
if (!$avoidChangedWorkspaceValidation && isset($_SESSION['WORKSPACE']) && $_SESSION['WORKSPACE'] != config("system.workspace")) {
    $_SESSION['WORKSPACE'] = config("system.workspace");
    Bootstrap::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', "error");
    // verify if the current skin is a 'ux' variant
    $urlPart = substr(SYS_SKIN, 0, 2) == 'ux' && SYS_SKIN != 'uxs' ? '/main/login' : '/login/login';

    header('Location: /sys' . config("system.workspace") . '/' . SYS_LANG . '/' . SYS_SKIN . $urlPart);
    die();
}

// enable rbac

$RBAC = RBAC::getSingleton(PATH_DATA, session_id());
$RBAC->sSystem = 'PROCESSMAKER';

// define and send Headers for all pages
if (!defined('EXECUTE_BY_CRON')) {
    header("Expires: " . gmdate("D, d M Y H:i:s", mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))) . " GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // get the language direction from ServerConf
    define('SYS_LANG_DIRECTION', $oServerConf->getLanDirection());

    if ((isset($_SESSION['USER_LOGGED'])) && (!(isset($_GET['sid'])))) {
        if ((preg_match("/msie/i", $_SERVER ['HTTP_USER_AGENT']) != 1 ||
                $config['ie_cookie_lifetime'] == 1) &&
            (!(preg_match("/safari/i", $_SERVER ['HTTP_USER_AGENT']) == 1 && preg_match("/chrome/i",
                        $_SERVER ['HTTP_USER_AGENT']) == 0) ||
                $config['safari_cookie_lifetime'] == 1)) {
            if (PHP_VERSION < 5.2) {
                setcookie(session_name(), session_id(), time() + $timelife, '/', '; HttpOnly');
            } else {
                setcookie(session_name(), session_id(), time() + $timelife, '/', null, G::is_https(), true);
            }
        }
        $RBAC->initRBAC();
        //using optimization with memcache, the user data will be in memcache 8 hours, or until session id goes invalid
        $memKey = 'rbacSession' . session_id();
        if (($RBAC->aUserInfo = $memcache->get($memKey)) === false) {
            $RBAC->loadUserRolePermission($RBAC->sSystem, $_SESSION['USER_LOGGED']);
            $memcache->set($memKey, $RBAC->aUserInfo, PMmemcached::EIGHT_HOURS);
        }
    } else {
        // this is the blank list to allow execute scripts with no login (without session started)
        $noLoginFiles = $noLoginFolders = [];
        $noLoginFiles[] = 'login';
        $noLoginFiles[] = 'authentication';
        $noLoginFiles[] = 'authenticationSso';
        $noLoginFiles[] = 'login_Ajax';
        $noLoginFiles[] = 'dbInfo';
        $noLoginFiles[] = 'sysLoginVerify';
        $noLoginFiles[] = 'processes_Ajax';
        $noLoginFiles[] = 'showLogoFile';
        $noLoginFiles[] = 'forgotPassword';
        $noLoginFiles[] = 'retrivePassword';
        $noLoginFiles[] = 'steps_Ajax';
        $noLoginFiles[] = 'proxyCasesList';
        $noLoginFiles[] = 'proxyNewCasesList';
        $noLoginFiles[] = 'casesStartPage_Ajax';
        $noLoginFiles[] = 'cases_Ajax';
        $noLoginFiles[] = 'casesList_Ajax';
        $noLoginFiles[] = 'proxyReassignCasesList';
        if (!isset($_REQUEST['glpi_data'])) {
            $noLoginFiles[] = 'ajaxListener';
            $noLoginFiles[] = 'cases_Step';
            $noLoginFiles[] = 'cases_SaveData';
            $noLoginFiles[] = 'cases_CatchExecute';
            $noLoginFiles[] = 'cases_ShowDocument';
        }
        $noLoginFiles[] = 'cases_ShowOutputDocument';
        $noLoginFiles[] = 'cases_Derivate';
        $noLoginFiles[] = 'cases_NextStep';
        $noLoginFiles[] = 'genericAjax';
        $noLoginFiles[] = 'casesSaveDataView';
        $noLoginFiles[] = 'propelTableAjax';
        $noLoginFiles[] = 'licenseUpdate';
        $noLoginFiles[] = 'casesStreamingFile';
        $noLoginFiles[] = 'opencase';
        $noLoginFiles[] = 'defaultAjaxDynaform';

        $noLoginFolders[] = 'services';
        $noLoginFolders[] = 'tracker';
        $noLoginFolders[] = 'InstallerModule';
        
        $data = new stdClass();
        $data->noLoginFiles = &$noLoginFiles;
        $data->noLoginFolders = &$noLoginFolders;
        AppEvent::getAppEvent()->dispatch(AppEvent::SCRIPTS_WITH_NO_LOGIN, $data);

        // This sentence is used when you lost the Session
        if (!in_array(SYS_TARGET, $noLoginFiles) && !in_array(SYS_COLLECTION,
                $noLoginFolders) && $bWE != true && $collectionPlugin != 'services') {
            $bRedirect = true;
            if (isset($_GET['sid'])) {
                $oSessions = new Sessions();
                if ($aSession = $oSessions->verifySession($_GET['sid'])) {
                    require_once 'classes/model/Users.php';
                    $oUser = new Users();
                    $aUser = $oUser->load($aSession['USR_UID']);
                    initUserSession(
                        $aUser['USR_UID'],
                        $aUser['USR_USERNAME']
                    );
                    $bRedirect = false;
                    if ((preg_match("/msie/i", $_SERVER ['HTTP_USER_AGENT']) != 1 ||
                            $config['ie_cookie_lifetime'] == 1) &&
                        (!(preg_match("/safari/i", $_SERVER ['HTTP_USER_AGENT']) == 1 && preg_match("/chrome/i",
                                    $_SERVER ['HTTP_USER_AGENT']) == 0) ||
                            $config['safari_cookie_lifetime'] == 1)) {
                        if (PHP_VERSION < 5.2) {
                            setcookie(session_name(), session_id(), time() + $timelife, '/', '; HttpOnly');
                        } else {
                            setcookie(session_name(), session_id(), time() + $timelife, '/', null, G::is_https(), true);
                        }
                    }
                    $RBAC->initRBAC();
                    $RBAC->loadUserRolePermission($RBAC->sSystem, $_SESSION['USER_LOGGED']);
                    $memKey = 'rbacSession' . session_id();
                    $memcache->set($memKey, $RBAC->aUserInfo, PMmemcached::EIGHT_HOURS);
                }
            }

            if (isset($_GET['tracker_designer']) && intval($_GET['tracker_designer']) !== 1) {
                unset($_GET['tracker_designer']);
            }

            if ($bRedirect && (!isset($_GET['tracker_designer']) || (!isset($_SESSION['CASE']) && !isset($_SESSION['PIN'])))) {
                if (substr(SYS_SKIN, 0, 2) === 'ux' && SYS_SKIN !== 'uxs') { // verify if the current skin is a 'ux' variant
                    $loginUrl = 'main/login';
                } else if (strpos($_SERVER['REQUEST_URI'], '/home') !== false) { //verify is it is using the uxs skin for simplified interface
                    $loginUrl = 'home/login';
                } else {
                    $loginUrl = 'login/login'; // just set up the classic login
                }

                if (empty($_POST)) {
                    $headerString = 'location: ' . SYS_URI . $loginUrl . '?u=' . urlencode($_SERVER['REQUEST_URI']);
                    AppEvent::getAppEvent()->dispatch(AppEvent::LOGIN, $headerString);
                    header($headerString);
                } else {
                    if ($isControllerCall) {
                        header("HTTP/1.0 302 session lost in controller");
                    } else {
                        header('location: ' . SYS_URI . $loginUrl);
                    }
                }
                die();
            }
        }
    }
    $_SESSION['phpLastFileFound'] = $_SERVER['REQUEST_URI'];

    /*----------------------------------********---------------------------------*/

    // Initialization functions plugins
    $oPluginRegistry->init();

    if ($isControllerCall) { //Instance the Controller object and call the request method
        $controller = new $controllerClass();
        $controller->setHttpRequestData($_REQUEST);//NewRelic Snippet - By JHL
        transactionLog($controllerAction);

        if ($isPluginController) {
            $controller->setPluginName($pluginName);
            $controller->setPluginHomeDir(PATH_PLUGINS . $pluginName . PATH_SEP);
        }

        $controller->call($controllerAction);
    } else {
        //NewRelic Snippet - By JHL
        transactionLog($phpFile);
        /*----------------------------------********---------------------------------*/
        require_once $phpFile;
    }

    if (defined('SKIP_HEADERS')) {
        header("Expires: " . gmdate("D, d M Y H:i:s", mktime(0, 0, 0, date('m'), date('d'), date('Y') + 1)) . " GMT");
        header('Cache-Control: public');
        header('Pragma: ');
    }

    ob_end_flush();
    if (DEBUG_TIME_LOG) {
        bootstrap::logTimeByPage(); //log this page
    }
}
