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
  
  $t = new lime_test( 39, new lime_output_color());
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
  $t->diag('Initial Subprocess Test' );
  $t->diag('Process Guid: ' . PROCESS_UID_SELECTION );
  $t->diag('Starting Task: ' . START_CYCLICAL_TASK );
  for($i=0;$i<1;$i++)
  {
  $result = ws_newCase ( PROCESS_UID_SELECTION, START_CYCLICAL_TASK, array());
  
  //se crea el proceso padre
  $t->isa_ok( $result, 'stdClass',  'executed ws_newCase');
  $t->is( $result->status_code == 0 , true,  'ws_newCase status_code = 0');
	if ( $result->status_code == 0 ) 
	   {
		     $caseId = $result->caseId;
		     $caseNumber = $result->caseNumber;
		     $msg = sprintf ( "New Case created: \033[0;31;32m%s %s\033[0m", $caseNumber, $caseId  );
		     $t->diag($msg );
		     
		     //se envia valor para el dynaform de la primera tarea
		     $variables = array ( 'name' => 'Pepes' );
		  	 $result = ws_sendVariables ($caseId, $variables );		 		  	 
		  	 $t->isa_ok( $result, 'stdClass',  'executed ws_sendVariables');
		  	 $t->is( $result->status_code == 0 , true,  'ws_sendVariables status_code = 0');
		  	 $msg1 = "1 variables received.";
		  	 $t->is( $result->message , $msg1, $msg1 );
		    		     
		     //se deriva y esto ocasiona que se el proceso HIJO sea creado,  		     		     
		     $result = ws_derivateCase ($caseId, 1 );		     
		  	 $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase');
		     $t->is( $result->status_code, 0,  'ws_derivateCase status_code = 0 '); //verificar tipo de derivaciopn s/ws         
         $t->diag( 'Case number--> ' . $result->derivation['caseNumber'] );
		     //deberiamos obtener de alguna forma el UID del proceso HIJO
		     $caseIdHijo = $result->derivation['caseId'];
		     $t->diag( 'child caseid ' . $caseIdHijo );
		     
		     //verificar que el proceso hijo tiene la variable names, con el valor que nosotros enviamos al proceso PADRE
		     $variables = array ( 'names', 'SYS_LANG', 'SYS_APPLICATION');
		     $result = ws_getVariables ( $caseIdHijo, $variables );
		     
		  	 $t->is( $result->variables['names'] , 'Pepes',  'variables[names] should be constant');
		  	 $t->is( $result->variables['SYS_LANG'] , 'en',  'variables[SYS_LANG] should be constant en');
		     
		     //derivar a la siguiente tarea del proceso hijo
		     $result = ws_derivateCase ($caseIdHijo, 1 );
		  	 $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase : from first to second task in child Case');
		     $t->is( $result->status_code, 0,  'ws_derivateCase status_code = 0 '); //verificar tipo de derivaciopn s/ws         
		     
		     
		     // enviar valores para las variables A y B, para realizar las operaciones aritmeticas.
		     $variables = array ( 'NUMX' => '4', 'NUMY' => '2' );
		  	 $result = ws_sendVariables ($caseIdHijo, $variables );		 		  	 		  	
		  	 $t->isa_ok( $result, 'stdClass',  'executed ws_sendVariables in child process');
		  	 $t->is( $result->status_code == 0 , true,  'ws_sendVariables status_code = 0');
		  	 $msg2 = "2 variables received.";
		  	 $t->is( $result->message , $msg2, $msg2 );
		     $t->diag('Execute Triggers.....');
		     
		     //execute triggers
         $result = ws_executeTrigger ( $caseIdHijo, '613346661490b60f7b7e4f3014763320', 2 );
         $t->is( $result->status_code == 0 , true,  'ws_sendVariables status_code = 0, execute trigrer succesfull ');
         $t->diag($result->message);         	        		  	        		
         $t->diag('Execute Triggers end.....');
		                       		      
		     //verificar que el proceso hijo ha terminado
         $variables = array ( 'SUM', 'RES', 'MUL', 'DIV');
		     $result = ws_getVariables ( $caseIdHijo, $variables );
		     $t->isa_ok( $result, 'stdClass',  'executed ws_getVariables in child process');	
		     $t->is( $result->variables['SUM'] , 6,  'variables[SUM] should be 6');  
		     $t->is( $result->variables['RES'] , 2,  'variables[RES] should be 2');  
		     $t->is( $result->variables['MUL'] , 8,  'variables[MUL] should be 8');  //derivar el proceso HIJO al padre,
         $t->is( $result->variables['DIV'] , 2,  'variables[DIV] should be 2');  $result = ws_derivateCase ($caseIdHijo, 2 );
		  	 $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase : from second to third(end) task in child Case');
		     $t->is( $result->status_code, 0,  'ws_derivateCase status_code = 0 from second to third(end) task in child Case');                       
         		     
         //verificar en el proceso PADRE que llegaron las variables resultado de las operaciones matematicas
         $variables = array ( 'sum', 'res', 'mul', 'div');
		     $result = ws_getVariables ( $caseId, $variables );
		     $t->is( $result->variables['sum'] , 6,  'variables[SUM] should be 6');
		  	 $t->is( $result->variables['res'] , 2,  'variables[RES] should be 2');
         $t->is( $result->variables['mul'] , 8,  'variables[MUL] should be 8');
		  	 $t->is( $result->variables['div'] , 2,  'variables[DIV] should be 2');  
		     
         //derivar el proceso PADRE a su ultima tarea
         $result = ws_derivateCase ($caseId, 3 );
		  	 $t->isa_ok( $result, 'stdClass',  'executed ws_derivateCase : from second to third(end) task in child Case');
		     $t->is( $result->status_code, 0,  'ws_derivateCase status_code = 0 from second to third(end) task in child Case');                       
         		     
		     //verificar que el proceso PADRE ha terminado
		     $result = ws_getCaseInfo ($caseId, 0); print_r($result);
         $t->isa_ok( $result, 'stdClass',  'Finish process to testing');
		     $t->is( $result->status_code, 0,  'ws_getCaseInfo status_code = 0 ');                                 
         $t->is( $result->message, 'case found',  'ws_getCaseInfo message case found');   
         $t->is( $result->caseId, $caseId,  'ws_getCaseInfo caseId ox');   
         $t->is( $result->caseNumber, $caseNumber,  'ws_getCaseInfo caseNumber ox');   
         $t->is( $result->caseStatus, 'COMPLETED',  'ws_getCaseInfo caseStatus ox');        
         $t->is( $result->caseParalell, 'N',  'ws_getCaseInfo caseParalell ox');        
		     $msg = sprintf ( "Check the case \033[0;31;32m%s\033[0m in the Draft List", $caseNumber);
		     $t->diag($msg );
	
		  }
 
 } 
  
  
*/