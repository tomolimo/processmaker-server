<?php

$filter = new InputFilter();
$_POST = $filter->xssFilterHard($_POST);
$_GET = $filter->xssFilterHard($_GET);
$request = isset($_POST['request']) ? $_POST['request'] : (isset($_GET['request']) ? $_GET['request'] : null);

function testConnection($type, $server, $user, $passwd, $port = 'none', $dbName = "")
{
    if (($port == 'none') || ($port == '') || ($port == 0)) {
        //setting defaults ports
        switch ($type) {
            case 'mysql':
                $port = 3306;
                break;
            case 'pgsql':
                $port = 5432;
                break;
            case 'mssql':
                $port = 1433;
                break;
            case 'oracle':
                $port = 1521;
                break;
        }
    }

    $Server = new Net($server);
    $filter = new InputFilter();

    if ($Server->getErrno() == 0) {
        $Server->scannPort($port);
        if ($Server->getErrno() == 0) {
            $Server->loginDbServer($user, $passwd);
            $Server->setDataBase($dbName, $port);
            if ($Server->errno == 0) {
                $response = $Server->tryConnectServer($type);
                if ($response->status == 'SUCCESS') {
                    if ($Server->errno == 0) {
                        $message = "";
                        $response = $Server->tryConnectServer($type);
                        $server = $filter->validateInput($server);
                        $user = $filter->validateInput($user);
                        $passwd = $filter->validateInput($passwd);
                        $connDatabase = mysqli_connect($server, $user, $passwd);
                        $dbNameTest = "PROCESSMAKERTESTDC";
                        $dbNameTest = $filter->validateInput($dbNameTest, 'nosql');
                        $query = "CREATE DATABASE %s";
                        $query = $filter->preventSqlInjection($query, array($dbNameTest), $connDatabase);
                        $db = mysqli_query($connDatabase, $query);
                        $success = false;
                        if (!$db) {
                            $message = mysqli_error($connDatabase);
                        } else {
                            $usrTest = "wfrbtest";
                            $chkG = "GRANT ALL PRIVILEGES ON `%s`.* TO %s@'%%' IDENTIFIED BY 'sample' WITH GRANT OPTION";
                            $chkG = $filter->preventSqlInjection($chkG, array($dbNameTest, $usrTest), $connDatabase);
                            $ch = mysqli_query($connDatabase, $chkG);
                            if (!$ch) {
                                $message = mysqli_error($connDatabase);
                            } else {
                                $sqlCreateUser = "CREATE USER '%s'@'%%' IDENTIFIED BY '%s'";
                                $user = $filter->validateInput($user, 'nosql');
                                $sqlCreateUser = $filter->preventSqlInjection($sqlCreateUser, array($user . "_usertest", "sample"), $connDatabase);
                                $result = mysqli_query($connDatabase, $sqlCreateUser);
                                if (!$result) {
                                    $message = mysqli_error($connDatabase);
                                } else {
                                    $success = true;
                                    $message = G::LoadTranslation('ID_SUCCESSFUL_CONNECTION');
                                }
                                $sqlDropUser = "DROP USER '%s'@'%%'";
                                $user = $filter->validateInput($user, 'nosql');
                                $sqlDropUser = $filter->preventSqlInjection($sqlDropUser, array($user . "_usertest"), $connDatabase);
                                mysqli_query($connDatabase, $sqlDropUser);

                                $sqlDropUser = "DROP USER %s@'%%'";
                                $usrTest = $filter->validateInput($usrTest, 'nosql');
                                $sqlDropUser = $filter->preventSqlInjection($sqlDropUser, array($usrTest), $connDatabase);
                                mysqli_query($connDatabase, $sqlDropUser);
                            }
                            $sqlDropDb = "DROP DATABASE %s";
                            $dbNameTest = $filter->validateInput($dbNameTest, 'nosql');
                            $sqlDropDb = $filter->preventSqlInjection($sqlDropDb, array($dbNameTest), $connDatabase);
                            mysqli_query($connDatabase, $sqlDropDb);
                        }
                        return array($success, ($message != "") ? $message : $Server->error);
                    } else {
                        return array(false, $Server->error);
                    }
                } else {
                    return array(false, $Server->error);
                }
            } else {
                return array(false, $Server->error);
            }
        } else {
            return array(false, $Server->error);
        }
    } else {
        return array(false, $Server->error);
    }
}

switch ($request) {
    //check if the APP_CACHE VIEW table and their triggers are installed
    case 'info':
        $result = new stdClass();
        $result->info = [];

        //check the language, if no info in config about language, the default is 'en'
        $oConf = new Configurations();
        $oConf->loadConfig($x, 'APP_CACHE_VIEW_ENGINE', '', '', '', '');
        $appCacheViewEngine = $oConf->aConfig;

        if (isset($appCacheViewEngine['LANG'])) {
            $lang = (defined('SYS_LANG')) ? SYS_LANG : $appCacheViewEngine['LANG'];
            $status = strtoupper($appCacheViewEngine['STATUS']);
        } else {
            $confParams = array('LANG' => (defined('SYS_LANG')) ? SYS_LANG : 'en', 'STATUS' => '');
            $oConf->aConfig = $confParams;
            $oConf->saveConfig('APP_CACHE_VIEW_ENGINE', '', '', '');
            $lang = (defined('SYS_LANG')) ? SYS_LANG : 'en';
            $status = '';
        }

        //get user Root from hash
        $result->info = [];
        $result->error = false;

        //setup the appcacheview object, and the path for the sql files
        $appCache = new AppCacheView();
        $appCache->setPathToAppCacheFiles(PATH_METHODS . 'setup' . PATH_SEP . 'setupSchemas' . PATH_SEP);

        $res = $appCache->getMySQLVersion();
        //load translations  G::LoadTranslation
        $result->info[] = array('name' => G::LoadTranslation('ID_CACHE_BUILDER_MYSQL_VERSION'), 'value' => $res);

        $res = $appCache->checkGrantsForUser(false);
        $currentUser = $res['user'];
        $currentUserIsSuper = $res['super'];
        $result->info[] = array('name' => G::LoadTranslation('ID_CACHE_BUILDER_CURRENT_USER'), 'value' => $currentUser);
        $result->info[] = array('name' => G::LoadTranslation('ID_CACHE_BUILDER_USER_SUPER_PRIVILEGE'), 'value' => $currentUserIsSuper);

        try {
            PROPEL::Init(PATH_METHODS . 'dbConnections/rootDbConnections.php');
            $con = Propel::getConnection("root");
        } catch (Exception $e) {
            $result->info[] = array('name' => 'Checking MySql Root user', 'value' => 'failed');
            $result->error = true;
            $result->errorMsg = $e->getMessage();
        }

        //if user does not have the SUPER privilege we need to use the root user and grant the SUPER priv. to normal user.
        if (!$currentUserIsSuper && !$result->error) {
            $res = $appCache->checkGrantsForUser(true);
            if (!isset($res['error'])) {
                $result->info[] = array('name' => G::LoadTranslation('ID_ROOT_USER'), 'value' => $res['user']);
                $result->info[] = array('name' => G::LoadTranslation('ID_ROOT_USER_SUPER'), 'value' => $res['super']);
            } else {
                $result->info[] = array('name' => 'Error', 'value' => $res['msg']);
            }
        }

        //now check if table APPCACHEVIEW exists, and it have correct number of fields, etc.
        $res = $appCache->checkAppCacheView();
        $result->info[] = array('name' => G::LoadTranslation('ID_CACHE_BUILDER_TABLE'), 'value' => $res['found']);

        $result->info[] = array('name' => G::LoadTranslation('ID_CACHE_BUILDER_ROWS'), 'value' => $res['count']);

        //now check if we have the triggers installed
        //APP_DELEGATION INSERT
        $res = $appCache->triggerAppDelegationInsert($lang, false);
        $result->info[] = array('name' => G::LoadTranslation('ID_CACHE_BUILDER_TRIGGER_INSERT'), 'value' => $res);

        //APP_DELEGATION Update
        $res = $appCache->triggerAppDelegationUpdate($lang, false);
        $result->info[] = array('name' => G::LoadTranslation('ID_CACHE_BUILDER_TRIGGER_UPDATE'), 'value' => $res);

        //APPLICATION UPDATE
        $res = $appCache->triggerApplicationUpdate($lang, false);
        $result->info[] = array('name' => G::LoadTranslation('ID_CACHE_BUILDER_TRIGGER_APPLICATION_UPDATE'), 'value' => $res);

        //APPLICATION DELETE
        $res = $appCache->triggerApplicationDelete($lang, false);
        $result->info[] = array('name' => G::LoadTranslation('ID_CACHE_BUILDER_TRIGGER_APPLICATION_DELETE'), 'value' => $res);

        //SUB_APPLICATION INSERT
        $res = $appCache->triggerSubApplicationInsert($lang, false);

        //CONTENT UPDATE
        $res = $appCache->triggerContentUpdate($lang, false);
        $result->info[] = array("name" => G::LoadTranslation('ID_CACHE_BUILDER_TRIGGER_CONTENT_UPDATE'), "value" => $res);

        //show language
        $result->info[] = array('name' => G::LoadTranslation('ID_CACHE_BUILDER_LANGUAGE'), 'value' => $lang);

        echo G::json_encode($result);
        break;
    case 'getLangList':
        $Translations = G::getModel('Translation');
        $result = new stdClass();
        $result->rows = [];

        $langs = $Translations->getTranslationEnvironments();
        foreach ($langs as $lang) {
            $result->rows[] = array('LAN_ID' => $lang['LOCALE'], 'LAN_NAME' => $lang['LANGUAGE']);
        }

        print(G::json_encode($result));
        break;
    case 'build':
        $sqlToExe = [];
        $conf = new Configurations();

        //DEPRECATED $lang = $_POST['lang'];
        //there is no more support for other languages that english
        $lang = (defined('SYS_LANG')) ? SYS_LANG : 'en';

        try {
            //setup the appcacheview object, and the path for the sql files
            $appCache = new AppCacheView();
            $appCache->setPathToAppCacheFiles(PATH_METHODS . 'setup' . PATH_SEP . 'setupSchemas' . PATH_SEP);

            //Update APP_DELEGATION.DEL_LAST_INDEX data
            $res = $appCache->updateAppDelegationDelLastIndex($lang, true);

            //APP_DELEGATION INSERT
            $res = $appCache->triggerAppDelegationInsert($lang, true);


            //APP_DELEGATION Update
            $res = $appCache->triggerAppDelegationUpdate($lang, true);


            //APPLICATION UPDATE
            $res = $appCache->triggerApplicationUpdate($lang, true);


            //APPLICATION DELETE
            $res = $appCache->triggerApplicationDelete($lang, true);

            //SUB_APPLICATION INSERT
            $res = $appCache->triggerSubApplicationInsert($lang, false);

            //CONTENT UPDATE
            $res = $appCache->triggerContentUpdate($lang, true);

            //build using the method in AppCacheView Class
            $res = $appCache->fillAppCacheView($lang);

            //set status in config table
            $confParams = array('LANG' => $lang, 'STATUS' => 'active');
            $conf->aConfig = $confParams;
            $conf->saveConfig('APP_CACHE_VIEW_ENGINE', '', '', '');

            $result = new StdClass();
            $result->success = true;
            $result->msg = G::LoadTranslation('ID_TITLE_COMPLETED');
            G::auditLog("BuildCache");
            echo G::json_encode($result);
        } catch (Exception $e) {
            $confParams = array('lang' => $lang, 'status' => 'failed');
            $appCacheViewEngine = $oServerConf->setProperty('APP_CACHE_VIEW_ENGINE', $confParams);

            $token = strtotime("now");
            PMException::registerErrorLog($e, $token);
            $varRes = '{success: false, msg:"' . G::LoadTranslation("ID_EXCEPTION_LOG_INTERFAZ", array($token)) . '"}';
            G::outRes($varRes);
        }
        break;
    case 'recreate-root':
        $user = $_POST['user'];
        $passwd = $_POST['password'];
        $server = $_POST['host'];
        $code = $_POST['codeCaptcha'];
        $aServer = explode(':', $server);
        $serverName = $aServer[0];
        $port = (count($aServer) > 1) ? $aServer[1] : "none";

        if ($code !== $_SESSION['securimage_code_disp']['default']) {
            echo G::loadTranslation('ID_CAPTCHA_CODE_INCORRECT');
            break;
        }

        list($sucess, $msgErr) = testConnection(DB_ADAPTER, $serverName, $user, $passwd, $port);

        if ($sucess) {
            $sh = G::encryptOld(filemtime(PATH_GULLIVER . "/class.g.php"));
            $h = G::encrypt($_POST['host'] . $sh . $_POST['user'] . $sh . $_POST['password'] . $sh . (1), $sh);
            $insertStatements = "define ( 'HASH_INSTALLATION','{$h}' );  \ndefine ( 'SYSTEM_HASH', '{$sh}' ); \n";
            $lines = [];
            $content = '';
            $filename = PATH_HOME . 'engine' . PATH_SEP . 'config' . PATH_SEP . 'paths_installed.php';
            $lines = file($filename);

            $count = 1;
            foreach ($lines as $line_num => $line) {
                $pos = strpos($line, "define");
                if ($pos !== false && $count < 3) {
                    $content = $content . $line;
                    $count++;
                }
            }
            $content = "<?php \n" . $content . "\n" . $insertStatements . "\n";
            if (file_put_contents($filename, $content) != false) {
                echo G::loadTranslation('ID_MESSAGE_ROOT_CHANGE_SUCESS');
            } else {
                echo G::loadTranslation('ID_MESSAGE_ROOT_CHANGE_FAILURE');
            }
        } else {
            echo $msgErr;
        }
        break;
    case 'captcha':
        require_once PATH_TRUNK . 'vendor/dapphp/securimage/securimage.php';
        $img = new Securimage();
        $img->show();
        break;
}
