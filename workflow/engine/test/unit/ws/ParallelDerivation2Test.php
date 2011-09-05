<?php
/**
 * ParallelDerivationTest.php
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
  $t = new lime_test( 39, new lime_output_color());
  $t->diag('Parallel Derivation Test (Variation 1)' );

  include_once ( "wsConfig.php" );
  include_once ( "wsClient.php" );

  define('PROCESS_UID', '4494018554909d147000020046858161');
  define('START_TASK',  '8112792964909d1579896b1090244999');

  global $sessionId;
  global $client;

  $t->is( strlen (WS_WSDL_URL) > 10 , true,  'include wsConfig.php');
  $t->is( function_exists ('ws_open')  , true,  'include wsClient.php');

  $t->diag('WS WSDL URL ' . WS_WSDL_URL );
  $t->diag('WS_USER_ID ' . WS_USER_ID );
  $t->diag('WS_USER_PASS ' . WS_USER_PASS );

  ws_open ();
  $t->isa_ok( $client , 'SoapClient',  'class SoapClient created');

  $t->is( strlen ($sessionId) > 10 , true,  'get a valid SessionId');
  $t->diag('Session Id: ' . $sessionId );

  $users = ws_userList ();

  $t->diag('-----------------------------------' );
  $t->diag('Parallel Derivation Test (Variation 1)' );
  $t->diag('Process Guid: ' . PROCESS_UID);
  $t->diag('Starting Task: ' . START_TASK );

  //First variant
  $result = ws_newCase ( PROCESS_UID, START_TASK, array());
  $t->isa_ok( $result, 'stdClass',  'executed ws_newCase');
  $t->is( $result->status_code == 0 , true,  'ws_newCase status_code = 0');

  if ( $result->status_code == 0 ) {
     $caseId = $result->caseId;
     $caseNumber = $result->caseNumber;
     $msg = sprintf ( "New Case created: \033[0;31;32m%s %s\033[0m", $caseNumber, $caseId  );
     $t->diag($msg );

     $result = ws_derivateCase ($caseId, 1 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
     $t->is( $result->status_code, 0,  'ws_derivateCase status_code = 0');
     $msg = trim ( sprintf ( "Case Derivated to: \033[0;31;32m%s\033[0m", $result->message));
     $t->diag($msg );

     $variables = array('TASK4' => '0');
     $result = ws_sendVariables ($caseId, $variables );
     $result = ws_derivateCase ($caseId, 2 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $variables = array('TASK6' => '1');
     $result = ws_sendVariables ($caseId, $variables );
     $result = ws_derivateCase ($caseId, 3 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 4 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 5 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 6 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 7 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 8 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
  }

  //Second variant
  $result = ws_newCase ( PROCESS_UID, START_TASK, array());
  $t->isa_ok( $result, 'stdClass',  'executed ws_newCase');
  $t->is( $result->status_code == 0 , true,  'ws_newCase status_code = 0');

  if ( $result->status_code == 0 ) {
     $caseId = $result->caseId;
     $caseNumber = $result->caseNumber;
     $msg = sprintf ( "New Case created: \033[0;31;32m%s %s\033[0m", $caseNumber, $caseId  );
     $t->diag($msg );

     $result = ws_derivateCase ($caseId, 1 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
     $t->is( $result->status_code, 0,  'ws_derivateCase status_code = 0');
     $msg = trim ( sprintf ( "Case Derivated to: \033[0;31;32m%s\033[0m", $result->message));
     $t->diag($msg );

     $variables = array('TASK4' => '0');
     $result = ws_sendVariables ($caseId, $variables );
     $result = ws_derivateCase ($caseId, 2 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $variables = array('TASK6' => '0');
     $result = ws_sendVariables ($caseId, $variables );
     $result = ws_derivateCase ($caseId, 3 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 4 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 5 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 6 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 7 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 8 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
  }

  //Third variant
  $result = ws_newCase ( PROCESS_UID, START_TASK, array());
  $t->isa_ok( $result, 'stdClass',  'executed ws_newCase');
  $t->is( $result->status_code == 0 , true,  'ws_newCase status_code = 0');

  if ( $result->status_code == 0 ) {
     $caseId = $result->caseId;
     $caseNumber = $result->caseNumber;
     $msg = sprintf ( "New Case created: \033[0;31;32m%s %s\033[0m", $caseNumber, $caseId  );
     $t->diag($msg );

     $result = ws_derivateCase ($caseId, 1 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
     $t->is( $result->status_code, 0,  'ws_derivateCase status_code = 0');
     $msg = trim ( sprintf ( "Case Derivated to: \033[0;31;32m%s\033[0m", $result->message));
     $t->diag($msg );

     $variables = array('TASK4' => '1');
     $result = ws_sendVariables ($caseId, $variables );
     $result = ws_derivateCase ($caseId, 2 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 4 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $variables = array('TASK4' => '0');
     $result = ws_sendVariables ($caseId, $variables );
     $result = ws_derivateCase ($caseId, 5 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $variables = array('TASK6' => '1');
     $result = ws_sendVariables ($caseId, $variables );
     $result = ws_derivateCase ($caseId, 3 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 6 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 7 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 8 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 9 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');

     $result = ws_derivateCase ($caseId, 10 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
  }
  
  
  */