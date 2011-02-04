<?php
//sysGeneric, this file is used initialize main variables and redirect to each and all pages
$startingTime =  array_sum(explode(' ',microtime()));

//*** ini setting, enable display_error On to caught even fatal errors
  ini_set('display_errors','On');
  ini_set('error_reporting', E_ALL  );
  ini_set('short_open_tag', 'on');
  ini_set('asp_tags', 'on');
  ini_set('memory_limit', '80M');
  ini_set('register_globals', 'off');
  ini_set("default_charset", "UTF-8");
  ini_set("soap.wsdl_cache_enabled", "0");
  
  define ('DEBUG_SQL_LOG',  1 );
  define ('DEBUG_TIME_LOG', 1 );
  define ('DEBUG_CALENDAR_LOG', 1 );

//*** process the $_POST with magic_quotes enabled
  function strip_slashes(&$vVar) {
    if (is_array($vVar)) {
      foreach($vVar as $sKey => $vValue) {
        if (is_array($vValue)) {
          strip_slashes($vVar[$sKey]);
        }
        else {
          $vVar[$sKey] = stripslashes($vVar[$sKey]);
        }
      }
    }
    else {
      $vVar = stripslashes($vVar);
    }
  }
  
  if (ini_get('magic_quotes_gpc') == '1') {
    strip_slashes($_POST);
  }

//******** function to calculate the time used to render this page  *****
  function logTimeByPage() {
  	$serverAddr = $_SERVER['SERVER_ADDR'];
  	global $startingTime;
  	$endTime =  array_sum(explode(' ',microtime()));
  	$time = $endTime - $startingTime;
    $fpt= fopen ( PATH_DATA . 'log/time.log', 'a' );
    fwrite( $fpt, sprintf ( "%s.%03d %15s %s %5.3f %s\n", date('H:i:s'), $time, getenv('REMOTE_ADDR'), substr($serverAddr,-4), $time, $_SERVER['REQUEST_URI'] ));
    fclose( $fpt);
  }

//******** defining the PATH_SEP constant, he we are defining if the the path separator symbol will be '\\' or '/' **************************
  if ( PHP_OS == 'WINNT' && !strpos ( $_SERVER['DOCUMENT_ROOT'], '/' ) )
   define('PATH_SEP','\\');
  else
   define('PATH_SEP', '/');

//***************** Defining the Home Directory *********************************
  $docuroot = explode ( PATH_SEP , $_SERVER['DOCUMENT_ROOT'] );
  array_pop($docuroot);
  $pathhome = implode( PATH_SEP, $docuroot )  . PATH_SEP;


  //try to find automatically the trunk directory where are placed the RBAC and Gulliver directories
  //in a normal installation you don't need to change it.
  array_pop($docuroot);
  $pathTrunk = implode( PATH_SEP, $docuroot ) . PATH_SEP ;

  array_pop($docuroot);
  $pathOutTrunk = implode( PATH_SEP, $docuroot ) . PATH_SEP ;
  // to do: check previous algorith for Windows  $pathTrunk = "c:/home/";

  define('PATH_HOME',     $pathhome );
  define('PATH_TRUNK',    $pathTrunk  );
  define('PATH_OUTTRUNK', $pathOutTrunk );


//************* Including these files we get the PM paths and definitions (that should be just one file ***********
  require_once ( $pathhome . PATH_SEP . 'engine' . PATH_SEP . 'config' . PATH_SEP . 'paths.php' );
//******************* Error handler and log error *******************
  //to do: make different environments.  sys
  //G::setErrorHandler ( );
  //G::setFatalErrorHandler ( );
  define ('ERROR_SHOW_SOURCE_CODE', true);  // enable ERROR_SHOW_SOURCE_CODE to display the source code for any WARNING OR NOTICE
  //define ( 'ERROR_LOG_NOTICE_ERROR', true );  //enable ERROR_LOG_NOTICE_ERROR to log Notices messages in default apache log

//  ***** create headPublisher singleton *****************
  G::LoadSystem('headPublisher');
  $oHeadPublisher =& headPublisher::getSingleton();

//  ***** defining the maborak js file, this file is the concat of many js files and here we are including all of them ****
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'maborak/core/maborak.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'common/core/common.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'common/core/effects.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'common/core/webResource.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'dveditor/core/dveditor.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'common/tree/tree.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'json/core/json.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'form/core/form.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'form/core/pagedTable.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'grid/core/grid.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'maborak/core/module.panel.js'    , true );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'maborak/core/module.validator.js', true );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'maborak/core/module.app.js'      , true );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'maborak/core/module.rpc.js'      , true );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'maborak/core/module.fx.js'       , true );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'maborak/core/module.drag.js'     , true );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'maborak/core/module.drop.js'     , true );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'maborak/core/module.dom.js'      , true );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'maborak/core/module.abbr.js'     , true );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'maborak/core/module.dashboard.js', true );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'widgets/js-calendar/js-calendar.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'widgets/suggest/bsn.AutoSuggest_2.1.3.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'widgets/tooltip/pmtooltip.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'thirdparty/krumo/krumo.js' );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'widgets/calendar/pmcalendar.js' , true );
  $oHeadPublisher->addMaborakFile( PATH_CORE          . 'js' . PATH_SEP . 'cases/core/cases.js' , true );
  $oHeadPublisher->addMaborakFile( PATH_CORE          . 'js' . PATH_SEP . 'cases/core/cases_Step.js', true );
  $oHeadPublisher->addMaborakFile( PATH_CORE          . 'js' . PATH_SEP . 'processmap/core/processmap.js', true );
  $oHeadPublisher->addMaborakFile( PATH_CORE          . 'js' . PATH_SEP . 'appFolder/core/appFolderList.js', true );
  $oHeadPublisher->addMaborakFile( PATH_THIRDPARTY    . 'htmlarea/editor.js', true );

//************ defining Virtual URLs ****************/
  $virtualURITable = array();
  $virtualURITable['/plugin/(*)']                    = 'plugin';
  $virtualURITable['/(sys*)/(*.js)']                 = 'jsMethod';
  $virtualURITable['/js/(*)']                        = PATH_GULLIVER_HOME . 'js/';
  $virtualURITable['/jscore/(*)']                    = PATH_CORE . 'js/';
  if ( defined('PATH_C') ) {
    $virtualURITable['/jsform/(*.js)']               = PATH_C . 'xmlform/';
    $virtualURITable['/extjs/(*)']                   = PATH_C . 'ExtJs/';
  }
  $virtualURITable['/htmlarea/(*)']                  = PATH_THIRDPARTY . 'htmlarea/';
  $virtualURITable['/sys[a-zA-Z][a-zA-Z0-9]{0,}()/'] = 'sysNamed';
  $virtualURITable['/(sys*)']                        = FALSE;
  $virtualURITable['/errors/(*)']                    = PATH_GULLIVER_HOME . 'methods/errors/';
  $virtualURITable['/gulliver/(*)']                  = PATH_GULLIVER_HOME . 'methods/';
  $virtualURITable['/controls/(*)']                  = PATH_GULLIVER_HOME . 'methods/controls/';
  $virtualURITable['/html2ps_pdf/(*)']               = PATH_THIRDPARTY . 'html2ps_pdf/';
  $virtualURITable['/Krumo/(*)']                     = PATH_THIRDPARTY . 'krumo/';
  $virtualURITable['/codepress/(*)']                 = PATH_THIRDPARTY . 'codepress/';
  $virtualURITable['/images/']                       = 'errorFile';
  $virtualURITable['/skins/']                        = 'errorFile';
  $virtualURITable['/files/']                        = 'errorFile';

  $virtualURITable['/[a-zA-Z][a-zA-Z0-9]{0,}()'] = 'sysUnnamed';
  $virtualURITable['/(*)'] = PATH_HTML;

//****** verify if we need to redirect or stream the file, if G:VirtualURI returns true means we are going to redirect the page *****
  if ( G::virtualURI($_SERVER['REQUEST_URI'], $virtualURITable , $realPath )) {
    // review if the file requested belongs to public_html plugin
    if ( substr ( $realPath, 0,6) == 'plugin' ) {
      /*
       * By JHL Jul 14, 08
       * Another way to get the path of Plugin public_html and stream the correspondent file
       * TODO: $pathsQuery will be used?
       */
      $pathsQuery="";
      //Get the query side
      /*
       * Did we use this variable $pathsQuery for something??
       */
      $forQuery=explode("?",$realPath);
      if(isset($forQuery[1])) {
        $pathsQuery=$forQuery[1];
      }

      //Get that path in array
      $paths = explode ( PATH_SEP, $forQuery[0] );
      //remove the "plugin" word from
      $paths[0] = substr ( $paths[0],6);
      //Get the Plugin Folder, always the first element
      $pluginFolder=array_shift($paths);
      //The other parts are the realpath into public_html (no matter how many elements)
      $filePath=implode(PATH_SEP,$paths);
      $pluginFilename = PATH_PLUGINS . $pluginFolder . PATH_SEP . 'public_html'. PATH_SEP . $filePath;
      if ( file_exists ( $pluginFilename ) ) {
          G::streamFile ( $pluginFilename );
      }
      die;
    }
    switch ( $realPath  ) {
      case 'sysUnnamed' :
        require_once('sysUnnamed.php'); die;
        break;
      case 'sysNamed' :
        header('location : ' . $_SERVER['REQUEST_URI'] . 'en/green/login/login' );
        die;
        break;
      case 'jsMethod' :
        G::parseURI ( getenv( "REQUEST_URI" ) );
        $filename = PATH_METHODS . SYS_COLLECTION . '/' . SYS_TARGET . '.js';
        G::streamFile ( $filename );
        die;
        break;
      case 'errorFile':
        header ("location: /errors/error404.php?url=" . urlencode($_SERVER['REQUEST_URI']));
        if ( DEBUG_TIME_LOG ) logTimeByPage(); //log this page
        die;
        break;
      default :
        $realPath = explode('?', $realPath);
        $realPath[0] .= strpos($realPath[0], '.') === false ? '.php' : '';
        G::streamFile ( $realPath[0] );
        die;
      }
  }

//************** the request correspond to valid php page, now parse the URI  **************
  
  G::parseURI ( getenv( "REQUEST_URI" ) );
  $oHeadPublisher->addMaborakFile( PATH_GULLIVER_HOME . 'js' . PATH_SEP . "widgets/jscalendar/lang/calendar-" . SYS_LANG . ".js");
  define( 'SYS_URI' , '/sys' .  SYS_TEMP . '/' . SYS_LANG . '/' . SYS_SKIN . '/' );

//************** defining the serverConf singleton **************
  if(defined('PATH_DATA') && file_exists(PATH_DATA)){
    //Instance Server Configuration Singleton
    G::LoadClass('serverConfiguration');
    $oServerConf =& serverConf::getSingleton();
  }
//***************** Call Gulliver Classes **************************

  G::LoadThirdParty('pear/json','class.json');
  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem('error');
  G::LoadSystem('dbconnection');
  G::LoadSystem('dbsession');
  G::LoadSystem('dbrecordset');
  G::LoadSystem('dbtable');
  G::LoadSystem('rbac' );
  G::LoadSystem('publisher');
  G::LoadSystem('templatePower');
  G::LoadSystem('xmlDocument');
  G::LoadSystem('xmlform');
  G::LoadSystem('xmlformExtension');
  G::LoadSystem('form');
  G::LoadSystem('menu');
  G::LoadSystem("xmlMenu");
  G::LoadSystem('dvEditor');
  G::LoadSystem('table');
  //G::LoadSystem('pagedTable');

//************** Installer, redirect to install if we don't have a valid shared data folder ***************/
  if ( !defined('PATH_DATA') || !file_exists(PATH_DATA)) {
    if ( (SYS_TARGET==='installServer')) {
      $phpFile = G::ExpandPath('methods') ."install/installServer.php";
      require_once($phpFile);
      die();
    }
    else {
      $phpFile = G::ExpandPath('methods') ."install/install.php";
      require_once($phpFile);
      die();
    }
  }

//  ************* Load Language Translation *****************
  G::LoadTranslationObject(defined('SYS_LANG')?SYS_LANG:"en");

//******** look for a disabled workspace ****
  if($oServerConf->isWSDisabled(SYS_TEMP)){
    $aMessage['MESSAGE'] = G::LoadTranslation('ID_DISB_WORKSPACE');
    $G_PUBLISH           = new Publisher;
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
    G::RenderPage( 'publish' );
    die;
  }

//********** database and workspace definition  ************************
  //if SYS_TEMP exists, the URL has a workspace, now we need to verify if exists their db.php file
  if ( defined('SYS_TEMP') && SYS_TEMP != '')  {
    //this is the default, the workspace db.php file is in /shared/workflow/sites/SYS_SYS
    if ( file_exists( PATH_DB .  SYS_TEMP . '/db.php' ) ) {
      require_once( PATH_DB .  SYS_TEMP . '/db.php' );
      define ( 'SYS_SYS' , SYS_TEMP );
    }
    else {
      $aMessage['MESSAGE'] = G::LoadTranslation ('ID_NOT_WORKSPACE');
      $G_PUBLISH          = new Publisher;
      $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
      G::RenderPage( 'publish' );
      die;
    }
  }
  else {  //when we are in global pages, outside any valid workspace
    if ((SYS_TARGET==='sysLoginVerify') || (SYS_TARGET==='sysLogin') || (SYS_TARGET==='newSite')) {
      $phpFile = G::ExpandPath('methods') . SYS_COLLECTION . "/" . SYS_TARGET.'.php';
      require_once($phpFile);
      die();
    }
    else {
      if(SYS_TARGET=="dbInfo"){ //Show dbInfo when no SYS_SYS
          require_once( PATH_METHODS . "login/dbInfo.php" ) ;
      }
      else{
        require_once( PATH_METHODS . "login/sysLogin.php" ) ;
      }
      if ( DEBUG_TIME_LOG ) logTimeByPage(); //log this page
      die();
    }
  }

//***************** PM Paths DATA **************************
  define( 'PATH_DATA_SITE',                 PATH_DATA      . 'sites/' . SYS_SYS . '/');
  define( 'PATH_DOCUMENT',                  PATH_DATA_SITE . 'files/' );
  define( 'PATH_DATA_MAILTEMPLATES',        PATH_DATA_SITE . 'mailTemplates/' );
  define( 'PATH_DATA_PUBLIC',               PATH_DATA_SITE . 'public/' );
  define( 'PATH_DATA_REPORTS',              PATH_DATA_SITE . 'reports/' );
  define( 'PATH_DYNAFORM',                  PATH_DATA_SITE . 'xmlForms/' );
  define( 'PATH_IMAGES_ENVIRONMENT_FILES',  PATH_DATA_SITE . 'usersFiles'.PATH_SEP);
  define( 'PATH_IMAGES_ENVIRONMENT_USERS',  PATH_DATA_SITE . 'usersPhotographies'.PATH_SEP);
  define( 'SERVER_NAME',  $_SERVER ['SERVER_NAME']);
  define( 'SERVER_PORT',  $_SERVER ['SERVER_PORT']);

//***************** Plugins **************************
  G::LoadClass('plugin');
  //here we are loading all plugins registered
  //the singleton has a list of enabled plugins

  $sSerializedFile = PATH_DATA_SITE . 'plugin.singleton';
  $oPluginRegistry =& PMPluginRegistry::getSingleton();
  if ( file_exists ($sSerializedFile) )
    $oPluginRegistry->unSerializeInstance( file_get_contents  ( $sSerializedFile ) );


//***************** create $G_ENVIRONMENTS dependent of SYS_SYS **************************
  define ( 'G_ENVIRONMENT', G_DEV_ENV );
  $G_ENVIRONMENTS = array (
    G_PRO_ENV => array (
      'dbfile' => PATH_DB . 'production' . PATH_SEP . 'db.php' ,
      'cache' => 1,
      'debug' => 0,
    ) ,
    G_DEV_ENV => array (
      'dbfile' => PATH_DB . SYS_SYS . PATH_SEP . 'db.php',
      'datasource' => 'workflow',
      'cache' => 0,
      'debug' => DEBUG_SQL_LOG,  //<--- change the value of this Constant to to 1, to have a detailed sql log in PATH_DATA . 'log' . PATH_SEP . 'workflow.log'
    ) ,
    G_TEST_ENV => array (
      'dbfile' => PATH_DB . 'test' . PATH_SEP . 'db.php' ,
      'cache' => 0,
      'debug' => 0,
    )
  );

//******* setup propel definitions and logging ****
  require_once ( "propel/Propel.php" );
  require_once ( "creole/Creole.php" );

  if ( $G_ENVIRONMENTS[ G_ENVIRONMENT ]['debug'] ) {
    require_once ( "Log.php" );

    // register debug connection decorator driver
    Creole::registerDriver('*', 'creole.contrib.DebugConnection');

    // itialize Propel with converted config file
    Propel::init( PATH_CORE . "config/databases.php" );

    //log file for workflow database
    $logFile = PATH_DATA . 'log' . PATH_SEP . 'workflow.log';
    $logger = Log::singleton('file', $logFile, 'wf ' . SYS_SYS, null, PEAR_LOG_INFO);
    Propel::setLogger($logger);
    $con = Propel::getConnection('workflow');
    if ($con instanceof DebugConnection) $con->setLogger($logger);

    //log file for rbac database
    $logFile = PATH_DATA . 'log' . PATH_SEP . 'rbac.log';
    $logger = Log::singleton('file', $logFile, 'rbac ' . SYS_SYS, null, PEAR_LOG_INFO);
    Propel::setLogger($logger);
    $con = Propel::getConnection('rbac');
    if ($con instanceof DebugConnection) $con->setLogger($logger);

    //log file for report database
    $logFile = PATH_DATA . 'log' . PATH_SEP . 'report.log';
    $logger = Log::singleton('file', $logFile, 'rp ' . SYS_SYS, null, PEAR_LOG_INFO);
    Propel::setLogger($logger);
    $con = Propel::getConnection('rp');
    if ($con instanceof DebugConnection) $con->setLogger($logger);
  }
  else
    Propel::init( PATH_CORE . "config/databases.php" );

  Creole::registerDriver('dbarray', 'creole.contrib.DBArrayConnection');

//***************** Session Initializations **************************/
  ini_set( 'session.auto_start', '1' );
  ini_set( 'register_globals',   'Off' );
  session_start();
  ob_start();

//********* Setup plugins *************
  $oPluginRegistry->setupPlugins(); //get and setup enabled plugins
  $avoidChangedWorkspaceValidation = false;
  
  //Load custom Classes and Model from Plugins.
  G::LoadAllPluginModelClasses();
  
//*********jump to php file in methods directory *************
  $collectionPlugin = '';
  if ( $oPluginRegistry->isRegisteredFolder( SYS_COLLECTION ) ) {
    $phpFile = PATH_PLUGINS . SYS_COLLECTION . PATH_SEP . SYS_TARGET.'.php';
    $targetPlugin = explode( '/', SYS_TARGET );
    $collectionPlugin = $targetPlugin[0];
    $avoidChangedWorkspaceValidation = true;
  }
  else
    $phpFile = G::ExpandPath('methods') . SYS_COLLECTION . PATH_SEP . SYS_TARGET.'.php';

  //services is a special folder,
  if ( SYS_COLLECTION == 'services' ) {
    $avoidChangedWorkspaceValidation = true;
    $targetPlugin = explode( '/', SYS_TARGET );
    if ( $targetPlugin[0] == 'webdav' ) {
      $phpFile = G::ExpandPath('methods') . SYS_COLLECTION . PATH_SEP . 'webdav.php';
    }
  }
  if(SYS_COLLECTION=='login' && SYS_TARGET=='login'){
    $avoidChangedWorkspaceValidation = true;
  }

  //the index.php file, this new feature will allow automatically redirects to valid php file inside any methods folder
  if ( SYS_TARGET == '' ) {
    $phpFile = str_replace ( '.php', 'index.php', $phpFile );
    $phpFile = include ( $phpFile );
  }
  $bWE = false;
  if ( substr(SYS_COLLECTION , 0,8) === 'gulliver' ) {
    $phpFile = PATH_GULLIVER_HOME . 'methods/' . substr( SYS_COLLECTION , 8) . SYS_TARGET.'.php';
  }
  else {
    //when the file is part of the public directory of any PROCESS, this a ProcessMaker feature
    if (preg_match('/^[0-9][[:alnum:]]+$/', SYS_COLLECTION) == 1)
    { //the pattern is /sysSYS/LANG/SKIN/PRO_UID/file
    $auxPart = explode ( '/' ,  $_SERVER['REQUEST_URI']);
    $aAux = explode('?', $auxPart[ count($auxPart)-1]);
      //$extPart = explode ( '.' , $auxPart[ count($auxPart)-1] );
      $extPart = explode ( '.' , $aAux[0] );
      $queryPart = isset($aAux[1])?$aAux[1]:"";
      $extension = $extPart[ count($extPart)-1 ];
      $phpFile = PATH_DATA_SITE . 'public' . PATH_SEP .  SYS_COLLECTION . PATH_SEP . urldecode ($auxPart[ count($auxPart)-1]);
      $aAux = explode('?', $phpFile);
      $phpFile = $aAux[0];
      if ( $extension != 'php' ) {
        G::streamFile ( $phpFile );
        die;
      }
      $avoidChangedWorkspaceValidation=true;
      $bWE = true;
      //$phpFile = PATH_DATA_SITE . 'public' . PATH_SEP .  SYS_COLLECTION . PATH_SEP . $auxPart[ count($auxPart)-1];
    }
    if ( ! file_exists( $phpFile ) ) {
        $_SESSION['phpFileNotFound'] = $phpFile;
        print $phpFile;
        header ("location: /errors/error404.php?url=" . urlencode($_SERVER['REQUEST_URI']));
        die;
    }
  }
//G::pr($_SESSION);
//G::dump($avoidChangedWorkspaceValidation);die;
//redirect to login, if user changed the workspace in the URL
  if( ! $avoidChangedWorkspaceValidation && isset( $_SESSION['WORKSPACE'] ) && $_SESSION['WORKSPACE'] != SYS_SYS) {
    $_SESSION['WORKSPACE'] = SYS_SYS;
    G::SendTemporalMessage ('ID_USER_HAVENT_RIGHTS_SYSTEM', "error");
    header ( 'Location: /sys' . SYS_SYS . '/' . SYS_LANG . '/' . SYS_SKIN . '/login/login' );
    die;
  }

//***************** enable rbac **************************
  $RBAC =& RBAC::getSingleton( PATH_DATA, session_id() );
  $RBAC->sSystem = 'PROCESSMAKER';

//****** define and send Headers for all pages *******************
  if ( ! defined('EXECUTE_BY_CRON') ) {
    header("Expires: " . gmdate("D, d M Y H:i:s", mktime( 0,0,0,date('m'),date('d'),date('Y') + 1) ) . " GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    //get the language direction from ServerConf
    define('SYS_LANG_DIRECTION', $oServerConf->getLanDirection() );
    
    if((isset( $_SESSION['USER_LOGGED'] ))&&(!(isset($_GET['sid'])))) {
      $RBAC->initRBAC();
      $RBAC->loadUserRolePermission( $RBAC->sSystem, $_SESSION['USER_LOGGED'] , PATH_DATA, session_id());
    }
    else {
      //This sentence is used when you lost the Session
      if ( SYS_TARGET != 'authentication' and  SYS_TARGET != 'login' and  SYS_TARGET != 'login_Ajax'
      and  SYS_TARGET != 'dbInfo'         and  SYS_TARGET != 'sysLoginVerify' and SYS_TARGET != 'processes_Ajax'
      and  SYS_TARGET != 'updateTranslation'
      and  SYS_TARGET != 'autoinstallProcesses'
      and  SYS_TARGET != 'autoinstallPlugins'
      and  SYS_TARGET != 'heartbeatStatus'
      and  SYS_TARGET != 'showLogoFile'
      and  SYS_COLLECTION != 'services' and SYS_COLLECTION != 'tracker' and $collectionPlugin != 'services'
      and  $bWE != true and SYS_TARGET != 'defaultAjaxDynaform' and SYS_TARGET != 'dynaforms_checkDependentFields' and SYS_TARGET != 'cases_ShowDocument') {
        $bRedirect = true;
        if (isset($_GET['sid'])) {
          G::LoadClass('sessions');
          $oSessions = new Sessions();
          if ($aSession = $oSessions->verifySession($_GET['sid'])) {
            require_once 'classes/model/Users.php';
            $oUser = new Users();
            $aUser = $oUser->load($aSession['USR_UID']);
            $_SESSION['USER_LOGGED']  = $aUser['USR_UID'];
            $_SESSION['USR_USERNAME'] = $aUser['USR_USERNAME'];
            $bRedirect = false;
            $RBAC->initRBAC();
            $RBAC->loadUserRolePermission( $RBAC->sSystem, $_SESSION['USER_LOGGED'] );
          }
        }
        if ($bRedirect) {
          if (empty($_POST)) {
            header('location: ' . SYS_URI . 'login/login?u=' . urlencode($_SERVER['REQUEST_URI']));
          }
          else {
            header('location: ' . SYS_URI . 'login/login');
          }
          die();
        }
      }
    }
    require_once( $phpFile );
    if ( defined('SKIP_HEADERS') ) {
      header("Expires: " . gmdate("D, d M Y H:i:s", mktime( 0,0,0,date('m'),date('d'),date('Y') + 1) ) . " GMT");
      header('Cache-Control: public');
      header('Pragma: ');
    }
    ob_end_flush();
    if ( DEBUG_TIME_LOG ) logTimeByPage(); //log this page
  }
