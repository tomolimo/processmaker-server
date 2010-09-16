<?php
/**
 * defines.php
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

//************ Other definitions  **************
  //web service timeout
  define( 'TIMEOUT_RESPONSE', 100 );
  //to login like workflow system
  define( 'APPLICATION_CODE', 'ProcessMaker' );

  define ( 'MAIN_POFILE', 'processmaker');
  define ( 'PO_SYSTEM_VERSION',  'PM 4.0.1');

  ///************TimeZone Set***************//
  if(!defined('TIME_ZONE')) {
    define('TIME_ZONE', 'America/La_Paz');
  }
  if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set(TIME_ZONE);
  }

   $G_CONTENT = NULL;
  $G_MESSAGE = "";
  $G_MESSAGE_TYPE = "info";
  $G_MENU_SELECTED = -1;
  $G_MAIN_MENU = "default";

  //remove this, when migrate to Propel
  define ( 'PEAR_DATABASE', 'mysql');
  define ( 'ENABLE_ENCRYPT', 'no' );
  define('DB_ERROR_BACKTRACE', TRUE);

//************ Environment definitions  **************
  define ( 'G_PRO_ENV',  'PRODUCTION' );
  define ( 'G_DEV_ENV',  'DEVELOPMENT' );
  define ( 'G_TEST_ENV', 'TEST' );

///************TimeZone Set***************// 
/*
  if (version_compare(phpversion(), "5.1.0", ">=")) {
    date_default_timezone_set("America/New York");
  }
  else {
    // you're not
  }
*/
/*
 * Number of files per folder at PATH_UPLOAD (cases documents)
 */
 define( 'APPLICATION_DOCUMENTS_PER_FOLDER', 1000 );

/*
 * Server of ProcessMaker Library
 */
  define ( 'PML_SERVER' ,  'http://library.processmaker.com' );
  //define ( 'PML_SERVER' ,  'http://pmlibrary.opensource.colosa.net' );
  define ( 'PML_WSDL_URL' ,    PML_SERVER . '/syspmLibrary/en/green/services/wsdl');
  define ( 'PML_UPLOAD_URL',   PML_SERVER . "/syspmLibrary/en/green/services/uploadProcess");
  define ( 'PML_DOWNLOAD_URL', PML_SERVER . '/syspmLibrary/en/green/services/download');





