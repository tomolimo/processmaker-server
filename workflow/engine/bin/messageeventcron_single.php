<?php
register_shutdown_function(
    create_function(
        "",
        "
        if (class_exists(\"Propel\")) {
            Propel::close();
        }
        "
    )
);

ini_set("memory_limit", "512M");

try {
    //Verify data
    if (count($argv) != 5) {
        throw new Exception("Error: Invalid number of arguments");
    }

    for ($i = 2; $i <= count($argv) - 1; $i++) {
        $argv[$i] = base64_decode($argv[$i]);

        if (!is_dir($argv[$i])) {
            throw new Exception("Error: The path \"" . $argv[$i] . "\" is invalid");
        }
    }

    //Set variables
    $osIsLinux = strtoupper(substr(PHP_OS, 0, 3)) != "WIN";

    $pathHome = $argv[2];
    $pathTrunk = $argv[3];
    $pathOutTrunk = $argv[4];

    //Defines constants
    define("PATH_SEP", ($osIsLinux)? "/" : "\\");

    define("PATH_HOME",     $pathHome);
    define("PATH_TRUNK",    $pathTrunk);
    define("PATH_OUTTRUNK", $pathOutTrunk);

    define("PATH_CLASSES", PATH_HOME . "engine" . PATH_SEP . "classes" . PATH_SEP);

    define("SYS_LANG", "en");

    require_once(PATH_HOME . "engine" . PATH_SEP . "config" . PATH_SEP . "paths.php");
    require_once(PATH_TRUNK . "framework" . PATH_SEP . "src" . PATH_SEP . "Maveriks" . PATH_SEP . "Util" . PATH_SEP . "ClassLoader.php");

    //Class Loader - /ProcessMaker/BusinessModel
    $classLoader = \Maveriks\Util\ClassLoader::getInstance();
    $classLoader->add(PATH_TRUNK . "framework" . PATH_SEP . "src" . PATH_SEP, "Maveriks");
    $classLoader->add(PATH_TRUNK . "workflow" . PATH_SEP . "engine" . PATH_SEP . "src" . PATH_SEP, "ProcessMaker");
    $classLoader->add(PATH_TRUNK . "workflow" . PATH_SEP . "engine" . PATH_SEP . "src" . PATH_SEP);

    //Add vendors to autoloader
    //$classLoader->add(PATH_TRUNK . "vendor" . PATH_SEP . "luracast" . PATH_SEP . "restler" . PATH_SEP . "vendor", "Luracast");
    //$classLoader->add(PATH_TRUNK . "vendor" . PATH_SEP . "bshaffer" . PATH_SEP . "oauth2-server-php" . PATH_SEP . "src" . PATH_SEP, "OAuth2");
    $classLoader->addClass("Bootstrap", PATH_TRUNK . "gulliver" . PATH_SEP . "system" . PATH_SEP . "class.bootstrap.php");

    $classLoader->addModelClassPath(PATH_TRUNK . "workflow" . PATH_SEP . "engine" . PATH_SEP . "classes" . PATH_SEP . "model" . PATH_SEP);

    //Load classes
    G::LoadThirdParty("pear/json", "class.json");
    G::LoadThirdParty("smarty/libs", "Smarty.class");
    G::LoadSystem("error");
    G::LoadSystem("dbconnection");
    G::LoadSystem("dbsession");
    G::LoadSystem("dbrecordset");
    G::LoadSystem("dbtable");
    G::LoadSystem("rbac" );
    G::LoadSystem("publisher");
    G::LoadSystem("templatePower");
    G::LoadSystem("xmlDocument");
    G::LoadSystem("xmlform");
    G::LoadSystem("xmlformExtension");
    G::LoadSystem("form");
    G::LoadSystem("menu");
    G::LoadSystem("xmlMenu");
    G::LoadSystem("dvEditor");
    G::LoadSystem("table");
    G::LoadSystem("pagedTable");
    G::LoadClass("system");

    require_once("propel/Propel.php");
    require_once("creole/Creole.php");

    $config = System::getSystemConfiguration();

    $e_all = (defined("E_DEPRECATED"))? E_ALL  & ~E_DEPRECATED : E_ALL;
    $e_all = (defined("E_STRICT"))?     $e_all & ~E_STRICT     : $e_all;
    $e_all = ($config["debug"])?        $e_all                 : $e_all & ~E_NOTICE;

    G::LoadSystem('inputfilter');
    $filter = new InputFilter();  
    $config['debug'] = $filter->validateInput($config['debug']);
    $config['wsdl_cache'] = $filter->validateInput($config['wsdl_cache'],'int');
    $config['time_zone'] = $filter->validateInput($config['time_zone']);
    //Do not change any of these settings directly, use env.ini instead
    ini_set("display_errors",  $config["debug"]);
    ini_set("error_reporting", $e_all);
    ini_set("short_open_tag",  "On");
    ini_set("default_charset", "UTF-8");
    //ini_set("memory_limit",    $config["memory_limit"]);
    ini_set("soap.wsdl_cache_enabled", $config["wsdl_cache"]);
    ini_set("date.timezone",           $config["time_zone"]);

    define("DEBUG_SQL_LOG",  $config["debug_sql"]);
    define("DEBUG_TIME_LOG", $config["debug_time"]);
    define("DEBUG_CALENDAR_LOG", $config["debug_calendar"]);
    define("MEMCACHED_ENABLED",  $config["memcached"]);
    define("MEMCACHED_SERVER",   $config["memcached_server"]);
    define("TIME_ZONE",          $config["time_zone"]);

    //require_once(PATH_GULLIVER . PATH_SEP . "class.bootstrap.php");
    //define("PATH_GULLIVER_HOME", PATH_TRUNK . "gulliver" . PATH_SEP);

    spl_autoload_register(array("Bootstrap", "autoloadClass"));

    //DATABASE propel classes used in "Cases" Options
    Bootstrap::registerClass("PMLicensedFeatures", PATH_CLASSES . "class.licensedFeatures.php");
    Bootstrap::registerClass("calendar",           PATH_CLASSES . "class.calendar.php");

    Bootstrap::registerClass("wsResponse", PATH_HOME . "engine" . PATH_SEP . "classes" . PATH_SEP . "class.wsResponse.php");

    G::LoadClass("processes");
    G::LoadClass("derivation");
    G::LoadClass("dates"); //Load Criteria

    //Workflow
    $workflow = $argv[1];

    if (is_dir(PATH_DB . $workflow) && file_exists(PATH_DB . $workflow . PATH_SEP . "db.php")) {
        define("SYS_SYS", $workflow);

        include_once(PATH_HOME . "engine" . PATH_SEP . "config" . PATH_SEP . "paths_installed.php");
        include_once(PATH_HOME . "engine" . PATH_SEP . "config" . PATH_SEP . "paths.php");

        //PM Paths DATA
        define("PATH_DATA_SITE",                PATH_DATA      . "sites/" . SYS_SYS . "/");
        define("PATH_DOCUMENT",                 PATH_DATA_SITE . "files/");
        define("PATH_DATA_MAILTEMPLATES",       PATH_DATA_SITE . "mailTemplates/");
        define("PATH_DATA_PUBLIC",              PATH_DATA_SITE . "public/");
        define("PATH_DATA_REPORTS",             PATH_DATA_SITE . "reports/");
        define("PATH_DYNAFORM",                 PATH_DATA_SITE . "xmlForms/");
        define("PATH_IMAGES_ENVIRONMENT_FILES", PATH_DATA_SITE . "usersFiles" . PATH_SEP);
        define("PATH_IMAGES_ENVIRONMENT_USERS", PATH_DATA_SITE . "usersPhotographies" . PATH_SEP);

        if (is_file(PATH_DATA_SITE.PATH_SEP . ".server_info")) {
            $SERVER_INFO = file_get_contents(PATH_DATA_SITE.PATH_SEP.".server_info");
            $SERVER_INFO = unserialize($SERVER_INFO);

            define("SERVER_NAME", $SERVER_INFO ["SERVER_NAME"]);
            define("SERVER_PORT", $SERVER_INFO ["SERVER_PORT"]);
        } else {
            eprintln("WARNING! No server info found!", "red");
        }

        //DB
        $phpCode = "";

        $fileDb = fopen(PATH_DB . $workflow . PATH_SEP . "db.php", "r");

        if ($fileDb) {
            while (!feof($fileDb)) {
                $buffer = fgets($fileDb, 4096); //Read a line

                $phpCode .= preg_replace("/define\s*\(\s*[\x22\x27](.*)[\x22\x27]\s*,\s*(\x22.*\x22|\x27.*\x27)\s*\)\s*;/i", "\$$1 = $2;", $buffer);
            }

            fclose($fileDb);
        }

        $phpCode = str_replace(array("<?php", "<?", "?>"), array("", "", ""), $phpCode);

        eval($phpCode);

        $dsn     = $DB_ADAPTER . "://" . $DB_USER . ":" . $DB_PASS . "@" . $DB_HOST . "/" . $DB_NAME;
        $dsnRbac = $DB_ADAPTER . "://" . $DB_RBAC_USER . ":" . $DB_RBAC_PASS . "@" . $DB_RBAC_HOST . "/" . $DB_RBAC_NAME;
        $dsnRp   = $DB_ADAPTER . "://" . $DB_REPORT_USER . ":" . $DB_REPORT_PASS . "@" . $DB_REPORT_HOST . "/" . $DB_REPORT_NAME;

        switch ($DB_ADAPTER) {
            case "mysql":
                $dsn .= "?encoding=utf8";
                $dsnRbac .= "?encoding=utf8";
                break;
            case "mssql":
                //$dsn .= "?sendStringAsUnicode=false";
                //$dsnRbac .= "?sendStringAsUnicode=false";
                break;
            default:
                break;
        }

        $pro = array();
        $pro["datasources"]["workflow"]["connection"] = $dsn;
        $pro["datasources"]["workflow"]["adapter"] = $DB_ADAPTER;
        $pro["datasources"]["rbac"]["connection"] = $dsnRbac;
        $pro["datasources"]["rbac"]["adapter"] = $DB_ADAPTER;
        $pro["datasources"]["rp"]["connection"] = $dsnRp;
        $pro["datasources"]["rp"]["adapter"] = $DB_ADAPTER;
        //$pro["datasources"]["dbarray"]["connection"] = "dbarray://user:pass@localhost/pm_os";
        //$pro["datasources"]["dbarray"]["adapter"]    = "dbarray";

        $oFile = fopen(PATH_CORE . "config" . PATH_SEP . "_databases_.php", "w");
        fwrite($oFile, "<?php global \$pro; return \$pro; ?>");
        fclose($oFile);

        Propel::init(PATH_CORE . "config" . PATH_SEP . "_databases_.php");
        //Creole::registerDriver("dbarray", "creole.contrib.DBArrayConnection");

        //Enable RBAC
        Bootstrap::LoadSystem("rbac");

        $rbac = &RBAC::getSingleton(PATH_DATA, session_id());
        $rbac->sSystem = "PROCESSMAKER";

        if (!defined("DB_ADAPTER")) {
            define("DB_ADAPTER", $DB_ADAPTER);
        }

        eprintln("Processing workspace: " . $workflow, "green");

        try {
            $case = new \ProcessMaker\BusinessModel\Cases();

            $case->catchMessageEvent(true);
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";

            eprintln("Problem in workspace: " . $workflow . " it was omitted.", "red");
        }

        eprintln();
    }

    if (file_exists(PATH_CORE . "config" . PATH_SEP . "_databases_.php")) {
        unlink(PATH_CORE . "config" . PATH_SEP . "_databases_.php");
    }
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

