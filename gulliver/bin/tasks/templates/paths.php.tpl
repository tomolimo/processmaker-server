<?php
/**
 * paths.php
 */

//****** Basic defines **** //
//  define('PATH_HOME',     $pathhome . PATH_SEP );
//  define('PATH_TRUNK',    $pathTrunk . PATH_SEP );
// define('PATH_OUTTRUNK', $pathOutTrunk . PATH_SEP );

//***************** System Directories & Paths **************************

  //***************** RBAC Paths **************************
  define( 'PATH_RBAC_HOME',     PATH_TRUNK . 'rbac' . PATH_SEP );
//***************** GULLIVER Paths **************************
  define( 'PATH_GULLIVER_HOME', PATH_TRUNK . 'gulliver'  . PATH_SEP );
  define( 'PATH_GULLIVER',      PATH_GULLIVER_HOME . 'system' . PATH_SEP );   //gulliver system classes
  define( 'PATH_GULLIVER_BIN',  PATH_GULLIVER_HOME . 'bin' . PATH_SEP );   //gulliver bin classes
  define( 'PATH_TEMPLATE',      PATH_GULLIVER_HOME . 'templates' . PATH_SEP );
  define( 'PATH_THIRDPARTY',    PATH_GULLIVER_HOME . 'thirdparty' . PATH_SEP );

  define( 'PATH_RBAC',          PATH_RBAC_HOME .     'engine'  . PATH_SEP . 'classes' . PATH_SEP );  //to enable rbac version 2
  define( 'PATH_RBAC_CORE',     PATH_RBAC_HOME .     'engine'  . PATH_SEP );  

  define( 'PATH_HTML',          PATH_HOME .          'public_html' . PATH_SEP );

//***************** PM Paths CORE **************************
  define( 'PATH_CORE',        PATH_HOME . 'engine'       . PATH_SEP );
  define( 'PATH_SKINS',       PATH_CORE . 'skins'        . PATH_SEP );
  define( 'PATH_METHODS',     PATH_CORE . 'methods'      . PATH_SEP );
  define( 'PATH_XMLFORM',     PATH_CORE . 'xmlform'      . PATH_SEP );
  define( 'PATH_PLUGINS',     PATH_CORE . 'plugins'      . PATH_SEP  );
  define( 'PATH_HTMLMAIL',    PATH_CORE . 'html_templates' . PATH_SEP );
  define( 'PATH_TPL',         PATH_CORE . 'templates'    . PATH_SEP );
  define( 'PATH_TEST',        PATH_CORE . 'test'         . PATH_SEP );
  define( 'PATH_FIXTURES',    PATH_TEST . 'fixtures'     . PATH_SEP );
  define( 'PATH_RTFDOCS' ,    PATH_CORE . 'rtf_templates' . PATH_SEP );
  define( 'PATH_DYNACONT',    PATH_CORE . 'content' . PATH_SEP . 'dynaform' . PATH_SEP );
  define( 'PATH_LANGUAGECONT',PATH_CORE . 'content' . PATH_SEP . 'languages' . PATH_SEP );
  define( 'SYS_UPLOAD_PATH',  PATH_HOME . "public_html/files/" );
  define( 'PATH_UPLOAD',      PATH_HTML . 'files' . PATH_SEP);
  define( 'PATH_WORKFLOW_MYSQL_DATA',  PATH_CORE . 'data' . PATH_SEP.'mysql'.PATH_SEP);
  define( 'PATH_RBAC_MYSQL_DATA',  PATH_RBAC_CORE . 'data' . PATH_SEP.'mysql'.PATH_SEP);
  
  define( 'FILE_PATHS_INSTALLED', PATH_CORE . 'config' . PATH_SEP . 'paths_installed.php' );
//************ include Gulliver Class **************
 require_once( PATH_GULLIVER . PATH_SEP . 'class.g.php');

//************ Install definitions  **************
  define( 'PATH_DATA', '/shared/{projectName}_data/' );
  define( 'PATH_C',    PATH_OUTTRUNK.'compiled/' );

//************ the Smarty Directories **************
  // TODO: This path defines where to save temporal data, similar to $_SESSION.
  define( 'PATH_TEMPORAL', PATH_C . 'dynEditor/');

	define( 'PATH_DB', PATH_DATA . 'sites' . PATH_SEP );
	define( 'PATH_SMARTY_C',       PATH_C . 'smarty' . PATH_SEP . 'c' );
	define( 'PATH_SMARTY_CACHE',   PATH_C . 'smarty' . PATH_SEP . 'cache' );
	if (!is_dir(PATH_SMARTY_C)) G::mk_dir(PATH_SMARTY_C);
	if (!is_dir(PATH_SMARTY_CACHE)) G::mk_dir(PATH_SMARTY_CACHE);

//***************** set include path  ***********************
  set_include_path(
    PATH_CORE . PATH_SEPARATOR .
    PATH_THIRDPARTY . PATH_SEPARATOR .
    PATH_THIRDPARTY . 'pear'. PATH_SEPARATOR .
    PATH_RBAC_CORE . PATH_SEPARATOR .
    get_include_path()
  );
