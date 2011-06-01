<?php
/**
 * paths.php
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
  define( 'PATH_SKIN_ENGINE', PATH_CORE . 'skinEngine'   . PATH_SEP );
  define( 'PATH_METHODS',     PATH_CORE . 'methods'      . PATH_SEP );
  define( 'PATH_XMLFORM',     PATH_CORE . 'xmlform'      . PATH_SEP );
  define( 'PATH_PLUGINS',     PATH_CORE . 'plugins'      . PATH_SEP  );
  define( 'PATH_HTMLMAIL',    PATH_CORE . 'html_templates' . PATH_SEP );
  define( 'PATH_TPL',         PATH_CORE . 'templates'    . PATH_SEP );
  define( 'PATH_TEST',        PATH_CORE . 'test'         . PATH_SEP );
  define( 'PATH_FIXTURES',    PATH_TEST . 'fixtures'     . PATH_SEP );
  define( 'PATH_RTFDOCS' ,    PATH_CORE . 'rtf_templates' . PATH_SEP );
  define( 'PATH_DYNACONT',    PATH_CORE . 'content' . PATH_SEP . 'dynaform' . PATH_SEP );
  //define( 'PATH_LANGUAGECONT',PATH_CORE . 'content' . PATH_SEP . 'languages' . PATH_SEP );
  define( 'SYS_UPLOAD_PATH',  PATH_HOME . "public_html/files/" );
  define( 'PATH_UPLOAD',      PATH_HTML . 'files' . PATH_SEP);
  define( 'PATH_WORKFLOW_MYSQL_DATA',  PATH_CORE . 'data' . PATH_SEP.'mysql'.PATH_SEP);
  define( 'PATH_RBAC_MYSQL_DATA',  PATH_RBAC_CORE . 'data' . PATH_SEP.'mysql'.PATH_SEP);
  
  define( 'FILE_PATHS_INSTALLED', PATH_CORE . 'config' . PATH_SEP . 'paths_installed.php' );

  define( 'PATH_WORKFLOW_MSSQL_DATA',  PATH_CORE . 'data' . PATH_SEP.'mssql'.PATH_SEP);
  define( 'PATH_RBAC_MSSQL_DATA',  PATH_RBAC_CORE . 'data' . PATH_SEP.'mssql'.PATH_SEP);

  define( 'PATH_CONTROLLERS',  PATH_CORE . 'controllers' . PATH_SEP );

//************ include Gulliver Class **************
  require_once( PATH_GULLIVER . PATH_SEP . 'class.g.php');
  
//************ the Smarty Directories **************
 
  if(file_exists(FILE_PATHS_INSTALLED)) {
    //parsing for old definitions in the compiled path constant
    $tmp = file_get_contents(FILE_PATHS_INSTALLED);
    if( strpos($tmp, 'PATH_OUTTRUNK') !== false ){
      @file_put_contents(FILE_PATHS_INSTALLED, str_replace('PATH_OUTTRUNK', 'PATH_DATA', $tmp));
    }
    
    require_once ( FILE_PATHS_INSTALLED );
    
    define( 'PATH_LANGUAGECONT', PATH_DATA . "META-INF" . PATH_SEP );
    
    define( 'PATH_CUSTOM_SKINS',PATH_DATA . 'skins'   . PATH_SEP );

    // TODO: This path defines where to save temporal data, similar to $_SESSION.
    define( 'PATH_TEMPORAL', PATH_C . 'dynEditor/');

    define( 'PATH_DB', PATH_DATA . 'sites' . PATH_SEP );
    define( 'PATH_SMARTY_C',       PATH_C . 'smarty' . PATH_SEP . 'c' );
    define( 'PATH_SMARTY_CACHE',   PATH_C . 'smarty' . PATH_SEP . 'cache' );
    if (!is_dir(PATH_SMARTY_C)) G::mk_dir(PATH_SMARTY_C);
    if (!is_dir(PATH_SMARTY_CACHE)) G::mk_dir(PATH_SMARTY_CACHE);
  }
//***************** set include path  ***********************
  set_include_path(
    PATH_CORE . PATH_SEPARATOR .
    PATH_THIRDPARTY . PATH_SEPARATOR .
    PATH_THIRDPARTY . 'pear'. PATH_SEPARATOR .
    PATH_RBAC_CORE . PATH_SEPARATOR .
    get_include_path()
  );

//******************* some global definitions, before it was the defines.php file ********

//***************** URL KEY *********************************************
  define("URL_KEY", 'c0l0s40pt1mu59r1m3' );

//************ Other definitions  **************
  //web service timeout
  define( 'TIMEOUT_RESPONSE', 100 );
  //to login like workflow system
  define( 'APPLICATION_CODE', 'ProcessMaker' );

  define ( 'MAIN_POFILE', 'processmaker');
  define ( 'PO_SYSTEM_VERSION',  'PM 4.0.1');

///************TimeZone Set***************//
  if (defined('TIME_ZONE') && function_exists('date_default_timezone_set')) {
    date_default_timezone_set(TIME_ZONE);
  }

  $G_CONTENT = NULL;
  $G_MESSAGE = "";
  $G_MESSAGE_TYPE = "info";
  $G_MENU_SELECTED = -1;
  $G_MAIN_MENU = "default";

  //remove this, when migrate to Propel
//  define ( 'PEAR_DATABASE', 'mysql');
//  define ( 'ENABLE_ENCRYPT', 'no' );
//  define('DB_ERROR_BACKTRACE', TRUE);

//************ Environment definitions  **************
  define ( 'G_PRO_ENV',  'PRODUCTION' );
  define ( 'G_DEV_ENV',  'DEVELOPMENT' );
  define ( 'G_TEST_ENV', 'TEST' );

//********* Number of files per folder at PATH_UPLOAD (cases documents) *****
 define( 'APPLICATION_DOCUMENTS_PER_FOLDER', 1000 );

//********* Server of ProcessMaker Library *****
  define ( 'PML_SERVER' ,  'http://library.processmaker.com' );
  define ( 'PML_WSDL_URL' ,    PML_SERVER . '/syspmLibrary/en/green/services/wsdl');
  define ( 'PML_UPLOAD_URL',   PML_SERVER . '/syspmLibrary/en/green/services/uploadProcess');
  define ( 'PML_DOWNLOAD_URL', PML_SERVER . '/syspmLibrary/en/green/services/download');

