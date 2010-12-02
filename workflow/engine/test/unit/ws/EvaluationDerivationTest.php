<?php
/**
 * EvaluationDerivationTest.php
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
  $t = new lime_test( 34, new lime_output_color());
  $t->diag('Evaluation Derivation Test' );

  include_once ( "wsConfig.php" );
  include_once ( "wsClient.php" );

  define ( 'PROCESS_UID_EVALUATION',   '62540106148bd5278cafec4025395237');  
  define ( 'START_EVA_CYCLICAL_TASK',  '61213500548bd52a7587928010684501');
  define ( 'START_EVA_MANUAL_TASK',    '11768216248bd52ae02ced4099046337');
  define ( 'START_EVA_VALUE_TASK',     '18044058448bd52f9b9a5e7001261804');

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
  $t->diag('Cyclical Evaluation Derivation Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_EVALUATION );
  $t->diag('Starting Task: ' . START_EVA_CYCLICAL_TASK );
 
  $result = ws_newCase ( PROCESS_UID_EVALUATION, START_EVA_CYCLICAL_TASK, array());
  $t->isa_ok( $result, 'stdClass',  'executed ws_newCase');
  $t->is( $result->status_code == 0 , true,  'ws_newCase status_code = 0');
  if ( $result->status_code == 0 ) {
     $caseId = $result->caseId;
     $caseNumber = $result->caseNumber;
     $msg = sprintf ( "New Case created: \033[0;31;32m%s %s\033[0m", $caseNumber, $caseId  );
     $t->diag($msg );
     
     $amount = rand( 1, 200 );
     $variables = array ( 'amount' => $amount );
     $result = ws_sendVariables ($caseId, $variables );
     $t->isa_ok( $result, 'stdClass',  'executed ws_sendVariables');
     $t->is( $result->status_code == 0 , true,  'ws_sendVariables status_code = 0');
     $msg1 = "1 variables received.";
     $t->is( $result->message , $msg1, $msg1 );

     $result = ws_derivateCase ($caseId, 1 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
     $t->is( $result->status_code, 0,  'ws_derivateCase status_code = 0');
     $msg = trim ( sprintf ( "Case Derivated to: \033[0;31;32m%s\033[0m", $result->message));
     $t->diag($msg );
     $t->todo(  'Check if the above user is changing according the Group in cyclical order');
     $t->diag(  "Check if the case was derivated according the amount = \033[2;31;32m" . $amount . "\033[0m ");

     $result = ws_derivateCase ($caseId, 2 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
     if ( $result->status_code == 17 ) {
       $t->is( $result->status_code, 17,  'ws_derivateCase status_code = 17');
       $t->diag($result->message );
       $msg = trim ( sprintf ( "Check the case: \033[0;31;32m%s\033[1;32m is in the user To Do List", $caseNumber));
       $t->todo( $msg);
     }
     else {
       $t->is( $result->status_code, 0,  'ws_derivateCase status_code = 0');
       $msg = trim ( sprintf ( "Case Derivated to: \033[0;31;32m%s\033[0m", $result->message));
       $t->diag($msg );
       $msg = trim ( sprintf ( "Check that case: \033[0;31;32m%s\033[1;32m is in your Completed List", $caseNumber));
       $t->todo( $msg);
     }
  }

  $t->diag('---------------------------------' );
  $t->diag('Manual Evaluation Derivation Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_EVALUATION );
  $t->diag('Starting Task: ' . START_EVA_MANUAL_TASK );
 
  $result = ws_newCase ( PROCESS_UID_EVALUATION, START_EVA_MANUAL_TASK, array());
  $t->isa_ok( $result, 'stdClass',  'executed ws_newCase');
  $t->is( $result->status_code , 0,  'ws_newCase status_code = 0');
  if ( $result->status_code == 0 ) {
     $caseId = $result->caseId;
     $caseNumber = $result->caseNumber;
     $msg = sprintf ( "New Case created: \033[0;31;32m%s %s\033[0m", $caseNumber, $caseId  );
     $t->diag($msg );
     
     $amount = rand( 1, 200 );
     $variables = array ( 'amount' => $amount );
     $result = ws_sendVariables ($caseId, $variables );
     $t->isa_ok( $result, 'stdClass',  'executed ws_sendVariables');
     $t->is( $result->status_code == 0 , true,  'ws_sendVariables status_code = 0');
     $msg1 = "1 variables received.";
     $t->is( $result->message , $msg1, $msg1 );

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
  $t->diag('Value Evaluation Derivation Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_EVALUATION );
  $t->diag('Starting Task: ' . START_EVA_VALUE_TASK );
 
  $result = ws_newCase ( PROCESS_UID_EVALUATION, START_EVA_VALUE_TASK, array());
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
     $msg1 = "Task doesn't have a valid user in variable SYS_NEXT_USER_TO_BE_ASSIGNED or this variable doesn't exist.";
     $t->is( $result->message , $msg1, $msg1 );

     $amount = rand( 1, 200 );
     $variables = array ( 'SYS_NEXT_USER_TO_BE_ASSIGNED' => '00000000000000000000000000000001', 'amount' => $amount );
     $result = ws_sendVariables ($caseId, $variables );
     $t->isa_ok( $result, 'stdClass',  'executed ws_sendVariables');
     $t->is( $result->status_code == 0 , true,  'ws_sendVariables status_code = 0');
     $msg1 = "2 variables received.";
     $t->is( $result->message , $msg1, $msg1 );

     $result = ws_derivateCase ($caseId, 1 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
     $t->is( $result->status_code == 0 , true,  'ws_derivateCase status_code = 0');
     $msg = trim ( sprintf ( "Case Derivated to: \033[0;31;32m%s\033[0m", $result->message));
     $t->diag($msg );
     if ( $amount > 100 ) 
       $msg1 = "Task 2 - Value Assign(admin)";
     else
       $msg1 = "Task 3 - Value Assign(admin)";
     $t->is( trim( $result->message) , $msg1, $msg1 );

  }
  */   
