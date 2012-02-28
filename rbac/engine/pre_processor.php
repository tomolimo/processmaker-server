<?php
/**
 * pre_processor.php
 *  
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd., 
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 * 
 */

define ( 'PEAR_DB', '1' );
define ( 'PEAR_DATABASE', 'mysql');


//***************** Application specific Directories & Paths **************************
  // This is defined in sysGeneric:
  //define('PATH_DATA',          '/shared/rbac/');
  //define('PATH_DATA_SITE',     PATH_DATA . 'sites/' . SYS_SYS . '/');

  // Application's General Paths
  define( 'PATH_CORE',      PATH_HOME . 'engine'      . PATH_SEP );
  define( 'PATH_SKINS',     PATH_CORE . 'skins'       . PATH_SEP );
  define( 'PATH_METHODS',   PATH_CORE . 'methods'     . PATH_SEP );
  define( 'PATH_XMLFORM',   PATH_CORE . 'xmlform'     . PATH_SEP );

// Other Paths
define( 'PATH_DB'    ,     PATH_HOME . 'engine' . PATH_SEP . 'db' . PATH_SEP);
define( 'PATH_RTFDOCS' ,   PATH_CORE . 'rtf_templates' . PATH_SEP );
define( 'PATH_HTMLMAIL',   PATH_CORE . 'html_templates' . PATH_SEP );
define( 'PATH_TPL'     ,   PATH_CORE . 'templates' . PATH_SEP );
define( 'PATH_DYNAFORM',   PATH_XMLFORM . 'dynaform' . PATH_SEP );
define( 'PATH_DYNACONT',   PATH_CORE . 'content' . PATH_SEP . 'dynaform' . PATH_SEP );
define( 'SYS_UPLOAD_PATH', PATH_HOME . "public_html/files/" );
define( 'PATH_UPLOAD',     PATH_HTML . "/" . "files" );

//***************** Call Gulliver Classes **************************

  G::LoadSystem('dbconnection');
  G::LoadSystem('dbsession');
  G::LoadSystem('dbrecordset');
  G::LoadSystem('dbtable');

  require_once(PATH_THIRDPARTY . 'pear/json/class.json.php');
  require_once(PATH_THIRDPARTY . 'smarty/libs/Smarty.class.php');
  G::LoadSystem('xmlDocument');
  G::LoadSystem('xmlform');
  G::LoadSystem('form');
  G::LoadSystem('menu');
//  G::LoadSystem('content');
  G::LoadSystem('table');
  G::LoadSystem('publisher');
  G::LoadSystem('templatePower');

  G::LoadSystem('headPublisher');
  $oHeadPublisher =& headPublisher::getSingleton();

/* Setup ... */
  //global $G_STRINGS;
//  $languageDir = glob( G::ExpandPath('content') . 'languages/*.xml' );
//  foreach($languageDir as $languageFile) {
//    G::loadLanguageFile ($languageFile);
//  }

  define('TIMEOUT_RESPONSE', 100 );

  define( 'APPLICATION_CODE', 'ProcessMaker' );  //to login like workflow system

if ( defined('SYS_TEMP') && SYS_TEMP != '') {
  if ( file_exists( PATH_DB . 'db_' . SYS_TEMP . '.php' )) {
    require_once( PATH_DB . 'db_' . SYS_TEMP . '.php' );
    define ( SYS_SYS, SYS_TEMP );
  }
  else {
    $sysParts = explode('-',SYS_TEMP);
    if ( count($sysParts) == 3) {
    	$fileName = 'dbmodule_'.$sysParts[1].'.php';
    	$DB_INDEX = 0;
      $DB_MODULE = array();

    	if ( !file_exists( PATH_DB . $fileName)) {
        header ("location: /errors/error701.php");
        die;
      }
      require_once ( PATH_DB . $fileName );
      $moduleName = $DB_MODULE[$DB_INDEX]['name'];
      $modulePath = $DB_MODULE[$DB_INDEX]['path'];
      $moduleType = $DB_MODULE[$DB_INDEX]['type'];
    	if ( !file_exists( $modulePath )) {
        header ("location: /errors/error704.php"); die;
      }
      if ( $moduleType == 'single-file' ) {
        $workspaceDB = $modulePath. 'db_'. $sysParts[2] . '.php';
      }
      else {
        $workspaceDB = $modulePath.  $sysParts[2] . '/db.php';
      }
    	if ( !file_exists( $workspaceDB )) {
          header ("location: /errors/error704.php"); die;
      }
      require_once( $workspaceDB ) ;
      define ( 'SYS_SYS', $sysParts[2]);
    }
    else {
      header ("location: /errors/error701.php");
      die;
    }
  }
}
else {
  require_once( PATH_METHODS . "login/showDBFiles.php" ) ;
  die();
}

G::LoadSystem( 'rbac' );   //to enable rbac

//***************** Other global definitions **************************
$G_CONTENT = NULL;
$G_MESSAGE = "";
$G_MESSAGE_TYPE = "info";
$G_MENU_SELECTED = -1;
$G_MAIN_MENU = "default";
$G_APPLICATION_CODE = "RBAC";  //to enable rbac
$RBAC = new rbac;              //to enable rbac

/*
if (defined('SYS_SYS')){
	if ( $HTTP_SESSION_VARS['ENVIRONMENT'] != '' &&
//	     $HTTP_SESSION_VARS['ENVIRONMENT'] != 'vacio' &&
	     SYS_SYS != 'vacio'  &&
	     SYS_SYS != $HTTP_SESSION_VARS['ENVIRONMENT'] )	{
 	     $HTTP_SESSION_VARS['ENVIRONMENT'] = SYS_SYS;
	     header ("location: /sys".SYS_SYS."/" . SYS_LANG . "/" . SYS_SKIN . "/login/login.html");
			die;
    }
}
else{  //cuando no esta definido
	if ( $HTTP_SESSION_VARS['ENVIRONMENT'] != '' &&
	     $HTTP_SESSION_VARS['ENVIRONMENT'] != 'vacio' &&
		   SYS_SYS != 'vacio' &&
		   SYS_SYS != $HTTP_SESSION_VARS['ENVIRONMENT'] )	{
       $HTTP_SESSION_VARS['ENVIRONMENT'] = SYS_SYS;
			 header ("location: /sys/" . SYS_LANG . "/" . SYS_SKIN . "/login/login.html");
			 die;
		}
}
*/


//***************** Session Initializations **************************/
    ini_alter( 'session.auto_start', '1' );
    // This feature has been DEPRECATED as of PHP 5.3.0. default value Off
    // ini_alter( 'register_globals', 'Off' );
    session_start();
    ob_start();

//*********Log Handler*************
//  logPage ( $URL , SYS_CURRENT_PARMS);


//*********jump to php file in methods directory *************
	$phpFile = G::ExpandPath('methods') . SYS_COLLECTION . "/" . SYS_TARGET.'.php';
  if ( substr(SYS_COLLECTION , 0,8) === 'gulliver' ) {
    $phpFile = PATH_GULLIVER_HOME . 'methods/' . substr( SYS_COLLECTION , 8) .
                SYS_TARGET.'.php';
  } else {
    if ( ! file_exists( $phpFile ) ) {
        header ("location: /errors/error404.php");
        die;
    }
  }

//***************** Headers **************************
if ( ! defined('EXECUTE_BY_CRON') ) {
	  header("Expires: Tue, 19 Jan 1999 04:30:00 GMT");
	  header("Last-Modified: Tue, 19 Jan 1999 04:30:00 GMT");
	  header('Cache-Control: no-cache, must-revalidate, post-check=0,pre-check=0 ');
	  //header("Cache-Control: max-age=0");
    header('P3P: CP="CAO PSA OUR"');
	require_once( $phpFile );
  if ( defined('SKIP_HEADERS') ) {
	  header("Expires: " . gmdate("D, d M Y H:i:s", mktime( 0,0,0,date('m'),date('d'),date('Y') + 1) ) . " GMT");
	  header('Cache-Control: public');
	  header('Pragma: ');
  }
	ob_end_flush();
}
?>