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
  
  $t = new lime_test( 28, new lime_output_color());
  $t->diag('Parallel Derivation Test' );

  include_once ( "wsConfig.php" );
  include_once ( "wsClient.php" );

  define ( 'PROCESS_UID_PARALLEL',     '44326695648bd573b100c74003649014');  
  define ( 'START_PAR_CYCLICAL_TASK',  '34868254848bd57718bb733027805100');
  define ( 'START_PAR_MANUAL_TASK',    '74379238448bd5785506e34066123807');
  define ( 'START_PAR_VALUE_TASK',     '28967746348bd57906ff834047943521');

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
  $t->diag('Cyclical Parallel Derivation Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_PARALLEL );
  $t->diag('Starting Task: ' . START_PAR_CYCLICAL_TASK );
 
  $result = ws_newCase ( PROCESS_UID_PARALLEL, START_PAR_CYCLICAL_TASK, array());
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
     $t->todo(  'Check if the above user is changing according the Group in cyclical order');

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
  $t->diag('Manual Parallel Derivation Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_PARALLEL );
  $t->diag('Starting Task: ' . START_PAR_MANUAL_TASK );
 
  $result = ws_newCase ( PROCESS_UID_PARALLEL, START_PAR_MANUAL_TASK, array());
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
  }
     
  $t->diag('-----------------------------------' );
  $t->diag('Value Parallel Derivation Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_PARALLEL );
  $t->diag('Starting Task: ' . START_PAR_VALUE_TASK );
 
  $result = ws_newCase ( PROCESS_UID_PARALLEL, START_PAR_VALUE_TASK, array());
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
     $msg1 = "Task doesn't have a valid user in variable leftUser or this variable doesn't exist.";
     $t->is( $result->message , $msg1, $msg1 );

     $user1 = rand(0, count($users)-1 ); 
     $user2 = rand(0, count($users)-1 ); 
     $userGuid1 = $users[$user1]['guid'];
     $userName1 = $users[$user1]['name'];
     $userGuid2 = $users[$user2]['guid'];
     $userName2 = $users[$user2]['name'];
     $variables = array ( 'leftUser' => $userGuid1, 'rightUser' => $userGuid2 );
     print_r ($variables);
     $result = ws_sendVariables ($caseId, $variables );
     $t->isa_ok( $result, 'stdClass',  'executed ws_sendVariables');
     $t->is( $result->status_code == 0 , true,  'ws_sendVariables status_code = 0');
     $msg1 = "2 variables received.";
     $t->is( $result->message , $msg1, $msg1 );

     $result = ws_derivateCase ($caseId, 1 );
     $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
     $t->is( $result->status_code, 0,  'ws_derivateCase status_code = 0');
     $msg = trim ( sprintf ( "Case Derivated to: \033[0;31;32m%s\033[0m", $result->message));
     $t->diag($msg );
     $msg1 = "Left Task - Value Assign($userName1),Right Task - Value Assign($userName2)";
     $t->is( trim( $result->message) , $msg1, $msg1 );

  }

*/     
