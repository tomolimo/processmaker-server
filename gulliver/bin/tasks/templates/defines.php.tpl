<?php
/**
 * defines.php
 *
 */

//***************** URL KEY *********************************************
  define("URL_KEY", '0pt1mu59r1m3' );

//************ Other definitions  **************
  //web service timeout
  define( 'TIMEOUT_RESPONSE', 100 );
  //to login like {projectName} system
  define( 'APPLICATION_CODE', '{projectName}' );

  define ( 'MAIN_POFILE', '{projectName}');
  define ( 'PO_SYSTEM_VERSION',  '0.0.1');


  $G_CONTENT = NULL;
  $G_MESSAGE = "";
  $G_MESSAGE_TYPE = "info";
  $G_MENU_SELECTED = -1;
  $G_MAIN_MENU = "default";

  define ( 'ENABLE_ENCRYPT', 'no' );
  define('DB_ERROR_BACKTRACE', TRUE);

//************ Environment definitions  **************
  define ( 'G_PRO_ENV',  'PRODUCTION' );
  define ( 'G_DEV_ENV',  'DEVELOPMENT' );
  define ( 'G_TEST_ENV', 'TEST' );

