<?php
/**
 * SequentialDerivationTest.php
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
  $t = new lime_test( 32, new lime_output_color());
  $t->diag('Sequential Derivation Test' );

  include_once ( "wsConfig.php" );
  include_once ( "wsClient.php" );

  define ( 'PROCESS_UID_SEQUENTIAL',  '34537126948bc50f6dc0ad8058165755');  
  define ( 'START_SEQ_CYCLICAL_TASK',  '31132134648bc50fb7afa90020304752');
  define ( 'START_SEQ_MANUAL_TASK',  '58845888048bc514a9ca3f6076028720');
  define ( 'START_REQ_VALUE_TASK',  '42952443148bc515a7aae98069470300');

  global $sessionId;
  global $client;

  $t->is( strlen (WS_WSDL_URL) > 10 , true,  'include wsConfig.php');
  $t->is( function_exists ('ws_open')  , true,  'include wsClient.php');
  $t->is( function_exists('ws_open'),true,            'ws_open()');
  $t->is( function_exists('ws_newCase'),true,         'ws_newCase()');
  $t->is( function_exists('ws_sendEmailMessage'),true, 'ws_sendEmailMessage()');
  $t->is( function_exists('ws_getVariables'),true,     'ws_getVariables()');
  $t->is( function_exists('ws_sendVariables'),true,    'ws_sendVariables()');
  $t->is( function_exists('ws_executeTrigger'),true,   'ws_executeTrigger()');
  $t->is( function_exists('ws_derivateCase'),true,     'ws_derivateCase()');

  $t->diag('WS WSDL URL ' . WS_WSDL_URL );
  $t->diag('WS_USER_ID ' . WS_USER_ID );
  $t->diag('WS_USER_PASS ' . WS_USER_PASS );
        
  ws_open ();   
  $t->isa_ok( $client , 'SoapClient',  'class SoapClient created');

  $t->is( strlen ($sessionId) > 10 , true,  'get a valid SessionId');
  $t->diag('Session Id: ' . $sessionId );

  $t->diag('-----------------------------------' );
  $t->diag('Cyclical Sequential Derivation Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_SEQUENTIAL );
  $t->diag('Starting Task: ' . START_SEQ_CYCLICAL_TASK );   
	  
  $result = ws_newCase ( PROCESS_UID_SEQUENTIAL, START_SEQ_CYCLICAL_TASK, array('A'=>'aaa','B'=>'bbb','C'=>'ccc'));  
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
     $assign = $result->message;
     $msg = trim ( sprintf ( "Case Derivated to: \033[0;31;32m%s\033[0m", $result->message));
     $t->diag($msg );
     $t->todo(  'Check if the above user is changing according the Group in cyclical order');
  }
        

  $t->diag('---------------------------------' );
  $t->diag('Manual Sequential Derivation Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_SEQUENTIAL );
  $t->diag('Starting Task: ' . START_SEQ_MANUAL_TASK );
 
  $result = ws_newCase ( PROCESS_UID_SEQUENTIAL, START_SEQ_MANUAL_TASK, array());
  $t->isa_ok( $result, 'stdClass',  'executed ws_newCase');
  $t->is( $result->status_code , 0,  'ws_newCase status_code = 0');
  if ( $result->status_code == 0 ) {
     $caseId = $result->caseId;
     $caseNumber = $result->caseNumber;
     $msg = sprintf ( "New Case created: \033[0;31;32m%s %s\033[0m", $caseNumber, $caseId  );
     $t->diag($msg );
     $result = ws_derivateCase ($caseId, 1 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
     $t->is( $result->status_code , 15,  'ws_derivateCase status_code = 15');
     $t->is( $result->message , 'The task is defined for Manual assignment',  'Task defined for Manual Assignment');
     $assign = $result->message;
     $msg = sprintf ( "Check the case \033[0;31;32m%s\033[0m in the Draft List", $caseNumber);
     $t->diag($msg );
     //$t->todo(  'Check if the above user is changing according the Group in cyclical order');
  }

  $t->diag('-----------------------------------' );
  $t->diag('Value Sequential Derivation Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_SEQUENTIAL );
  $t->diag('Starting Task: ' . START_REQ_VALUE_TASK );
 
  $result = ws_newCase ( PROCESS_UID_SEQUENTIAL, START_REQ_VALUE_TASK, array());
  $t->isa_ok( $result, 'stdClass',  'executed ws_newCase');
  $t->is( $result->status_code == 0 , true,  'ws_newCase status_code = 0');
  if ( $result->status_code == 0 ) {
     $caseId = $result->caseId;
     $caseNumber = $result->caseNumber;
     $msg = sprintf ( "New Case created: \033[0;31;32m%s %s\033[0m", $caseNumber, $caseId  );
     $t->diag($msg );
     $result = ws_derivateCase ($caseId, 1 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
     $t->is( $result->status_code == 100 , true,  'ws_derivateCase status_code = 100');
     $msg1 = "Task doesn't have a valid user in variable SYS_NEXT_USER_TO_BE_ASSIGNED or this variable doesn't exists.";
     $t->is( $result->message , $msg1, $msg1 );

     $variables = array ( 'SYS_NEXT_USER_TO_BE_ASSIGNED' => '00000000000000000000000000000001' );
     $result = ws_sendVariables ($caseId, $variables );
     $t->isa_ok( $result, 'stdClass',  'executed ws_sendVariables');
     $t->is( $result->status_code == 0 , true,  'ws_sendVariables status_code = 0');
     $msg1 = "1 variables received.";
     $t->is( $result->message , $msg1, $msg1 );

     $result = ws_derivateCase ($caseId, 1 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
     $t->is( $result->status_code == 0 , true,  'ws_derivateCase status_code = 0');
     $assign = $result->message;
     $msg = trim ( sprintf ( "Case Derivated to: \033[0;31;32m%s\033[0m", $result->message));
     $t->diag($msg );
     $msg1 = "Task 2 - Value Assign(admin)";
     $t->is( trim( $result->message) , $msg1, $msg1 );

  }
        


*/
