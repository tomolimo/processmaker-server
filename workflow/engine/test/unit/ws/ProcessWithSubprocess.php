<?php  
/**
 * SelectionDerivationTest.php
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
  $t = new lime_test( 25, new lime_output_color());
  $t->diag('Selection Derivation Test' );

  include_once ( "wsConfig.php" );
  include_once ( "wsClient.php" );

  define ( 'PROCESS_UID_SELECTION',  '324304616490b213ae38690016293793');  
  define ( 'START_CYCLICAL_TASK',  '917060771490b214838edb9072930947');  

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

  $t->diag('-----------------------------------' );
  $t->diag('Cyclical Selection Derivation Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_SELECTION );
  $t->diag('Starting Task: ' . START_CYCLICAL_TASK );
  for($i=0;$i<1;$i++)
  {
  $result = ws_newCase ( PROCESS_UID_SELECTION, START_CYCLICAL_TASK, array());
  $t->isa_ok( $result, 'stdClass',  'executed ws_newCase');
  $t->is( $result->status_code == 0 , true,  'ws_newCase status_code = 0');
  if ( $result->status_code == 0 ) {
     $caseId = $result->caseId;
     $caseNumber = $result->caseNumber;
     $msg = sprintf ( "New Case created: \033[0;31;32m%s %s\033[0m", $caseNumber, $caseId  );
     $t->diag($msg );
     $result = ws_derivateCase ($caseId, 1 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
     $t->is( $result->status_code, 21,  'ws_derivateCase status_code = 21');
     $msg1 = "Cannot derivate a \"Manual\" derivation using webservices.";
     $t->is( $result->message , $msg1, $msg1 );
     $assign = $result->message;
     $msg = sprintf ( "Check the case \033[0;31;32m%s\033[0m in the Draft List", $caseNumber);
     $t->diag($msg );
  }

  $t->diag('-----------------------------------' );
  $t->diag('Manual Selection Derivation Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_SELECTION );
  $t->diag('Starting Task: ' . START_MANUAL_TASK );
 
  $result = ws_newCase ( PROCESS_UID_SELECTION, START_MANUAL_TASK, array());
  $t->isa_ok( $result, 'stdClass',  'executed ws_newCase');
  $t->is( $result->status_code == 0 , true,  'ws_newCase status_code = 0');
  if ( $result->status_code == 0 ) {
     $caseId = $result->caseId;
     $caseNumber = $result->caseNumber;
     $msg = sprintf ( "New Case created: \033[0;31;32m%s %s\033[0m", $caseNumber, $caseId  );
     $t->diag($msg );
     $result = ws_derivateCase ($caseId, 1 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
     $t->is( $result->status_code, 21,  'ws_derivateCase status_code = 21');
     $msg1 = "Cannot derivate a \"Manual\" derivation using webservices.";
     $t->is( $result->message , $msg1, $msg1 );
     $assign = $result->message;
     $msg = sprintf ( "Check the case \033[0;31;32m%s\033[0m in the Draft List", $caseNumber);
     $t->diag($msg );
  }

*/