<?php
/**
 * gulliverConstants.php
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

//***************** URL KEY *********************************************
  define("URL_KEY", 'c0l0s40pt1mu59r1m3' );

//***************** System Directories & Paths **************************
  define('PATH_SEP', '/');

  define( 'PATH_HOME', '/opt/processmaker/trunk/workflow/' );
  define( 'PATH_GULLIVER_HOME', '/opt/processmaker/trunk/gulliver'  . PATH_SEP );
  //define( 'PATH_GULLIVER_HOME', $pathTrunk . 'gulliver' . PATH_SEP );
  define( 'PATH_RBAC_HOME',     $pathTrunk . 'rbac' . PATH_SEP );
  define( 'PATH_DATA',          '/shared/workflow_data/');
    
// the other directories
  define( 'PATH_GULLIVER',   PATH_GULLIVER_HOME . 'system' . PATH_SEP );   //gulliver system classes
  define( 'PATH_TEMPLATE',   PATH_GULLIVER_HOME . 'templates' . PATH_SEP );
  define( 'PATH_THIRDPARTY', PATH_GULLIVER_HOME . 'thirdparty' . PATH_SEP );
  define( 'PATH_RBAC',       PATH_RBAC_HOME .     'engine/classes' . PATH_SEP );  //to enable rbac version 2
  define( 'PATH_HTML',       PATH_HOME .          'public_html' . PATH_SEP );

  // Application's General Directories
  define( 'PATH_CORE',      PATH_HOME . 'engine'      . PATH_SEP );
  define( 'PATH_SKINS',     PATH_CORE . 'skins'       . PATH_SEP );
  define( 'PATH_METHODS',   PATH_CORE . 'methods'     . PATH_SEP );
  define( 'PATH_XMLFORM',   PATH_CORE . 'xmlform'     . PATH_SEP );

//************ include Gulliver Class **************
  require_once( PATH_GULLIVER . PATH_SEP . 'class.g.php');

// the Compiled Directories
  define( 'PATH_C',          $pathOutTrunk . 'compiled/');
      
// the Smarty Directories
  if ( strstr ( getenv ( 'OS' ), 'Windows' ) ) {
    define( 'PATH_SMARTY_C',       'c:/tmp/smarty/c' );
    define( 'PATH_SMARTY_CACHE',   'c:/tmp/smarty/cache' );
  }
  else {
    define( 'PATH_SMARTY_C',       PATH_C . 'smarty/c' );
    define( 'PATH_SMARTY_CACHE',   PATH_C . 'smarty/cache' );
  }

  if (!is_dir(PATH_SMARTY_C)) G::mk_dir(PATH_SMARTY_C);
  if (!is_dir(PATH_SMARTY_CACHE)) G::mk_dir(PATH_SMARTY_CACHE);


  // Other Paths
  //define( 'PATH_DB'    ,     PATH_HOME . 'engine' . PATH_SEP . 'db' . PATH_SEP);
  define( 'PATH_DB'    ,     PATH_DATA . 'sites' . PATH_SEP );
  define( 'PATH_RTFDOCS' ,   PATH_CORE . 'rtf_templates' . PATH_SEP );
  define( 'PATH_HTMLMAIL',   PATH_CORE . 'html_templates' . PATH_SEP );
  define( 'PATH_TPL'     ,   PATH_CORE . 'templates' . PATH_SEP );
  define( 'PATH_DYNACONT',   PATH_CORE . 'content' . PATH_SEP . 'dynaform' . PATH_SEP );
  define( 'PATH_LANGUAGECONT',  PATH_CORE . 'content' . PATH_SEP . 'languages' . PATH_SEP );
  define( 'SYS_UPLOAD_PATH', PATH_HOME . "public_html/files/" );
  define( 'PATH_UPLOAD',     PATH_HTML . 'files/');
  
  
define ('DB_HOST', '192.168.0.10' );
define ('DB_NAME', 'wf_opensource' );
define ('DB_USER', 'fluid' );
define ('DB_PASS', 'fluid2000' );
define ('DB_RBAC_NAME', 'rbac_os' );
define ('DB_RBAC_USER', 'rbac_os' );
define ('DB_RBAC_PASS', '873821w3n2u719tx' );
define ('DB_WIZARD_REPORT_SYS', 'report_os' );
define ('DB_WIZARD_REPORT_USER', 'rep_os' );
define ('DB_WIZARD_REPORT_PASS', '3r357ichy6b95s88' );

define ( 'SF_ROOT_DIR', PATH_CORE );
define ( 'SF_APP', 'app' );
define ( 'SF_ENVIRONMENT', 'env' );
  
  set_include_path(
    PATH_THIRDPARTY . PATH_SEPARATOR . 
    PATH_THIRDPARTY . 'pear' . PATH_SEPARATOR . 
    get_include_path()
  );  
  
