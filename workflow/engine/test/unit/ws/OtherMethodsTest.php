<?php
/**
 * OtherMethodsTest.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.
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
  if ( !defined ('PATH_THIRDPARTY') ) {
    require_once(  $_SERVER['PWD']. '/test/bootstrap/unit.php');
  }

  require_once( PATH_THIRDPARTY . '/lime/lime.php');
  require_once( PATH_THIRDPARTY.'lime/yaml.class.php');

  $t = new lime_test( 2, new lime_output_color());
  $t->is( strlen (WS_WSDL_URL) > 10 , true,  'include wsConfig.php');
  $t->todo( 'complete this test');
  
/*  

  $t = new lime_test( 12, new lime_output_color());
  $t->diag('Sequential Derivation Test' );

  include_once ( "wsConfig.php" );
  include_once ( "wsClient.php" );

  define ( 'PROCESS_UID_SEQUENTIAL',  '34537126948bc50f6dc0ad8058165755');
  define ( 'START_SEQ_CYCLICAL_TASK',  '31132134648bc50fb7afa90020304752');
  define ( 'START_SEQ_MANUAL_TASK',  '58845888048bc514a9ca3f6076028720');
  define ( 'START_REQ_VALUE_TASK',  '42952443148bc515a7aae98069470300');

  global $sessionId;
  global $client;

  $t->is( strlen (WS_WSDL_URL) > 10 , true, 'include wsConfig.php');
  $t->is( function_exists ('ws_open')  , true, 'include wsClient.php');
  $t->is( function_exists('ws_open'),true, 'ws_open()');
  $t->is( function_exists('ws_newCase'),true, 'ws_newCase()');
  $t->is( function_exists('ws_getCaseInfo'),true, 'ws_getCaseInfo()');
  $t->is( function_exists('ws_derivateCase'),true, 'ws_derivateCase()');

  $t->diag('WS WSDL URL ' . WS_WSDL_URL );
  $t->diag('WS_USER_ID ' . WS_USER_ID );
  $t->diag('WS_USER_PASS ' . WS_USER_PASS );

  ws_open ();
  $t->isa_ok( $client , 'SoapClient',  'class SoapClient created');

  $t->is( strlen ($sessionId) > 10 , true,  'get a valid SessionId');
  $t->diag('Session Id: ' . $sessionId );

  $t->diag('-----------------------------------' );
  $t->diag('Other Methods Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_SEQUENTIAL );
  $t->diag('Starting Task: ' . START_SEQ_CYCLICAL_TASK );

  $result = ws_newCase ( PROCESS_UID_SEQUENTIAL, START_SEQ_CYCLICAL_TASK, array('A'=>'aaa','B'=>'bbb','C'=>'ccc'));
  $t->isa_ok( $result, 'stdClass',  'executed ws_newCase');
  $t->is( $result->status_code == 0 , true,  'ws_newCase status_code = 0');
  if ( $result->status_code == 0 ) {
    $result = ws_getCaseInfo($result->caseId, 1);
    $t->isa_ok( $result, 'stdClass',  'executed ws_getCaseInfo');
    $t->is( $result->caseStatus, 'DRAFT',  'status is DRAFT');
  }

*/