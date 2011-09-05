<?php
/**
 * classESMTPTest.php
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
  $unitFilename = $_SERVER['PWD'] . '/test/bootstrap/unit.php' ;
  require_once( $unitFilename );

  require_once( PATH_THIRDPARTY . '/lime/lime.php');
  require_once( PATH_THIRDPARTY.'lime/yaml.class.php');
 
  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');
  require_once( 'propel/Propel.php' );
  require_once ( "creole/Creole.php" );
  require_once (  PATH_CORE . "config/databases.php");  

  G::LoadClass ( 'smtp.rfc-821');


  //$obj = new ESMTP ($dbc);
  $t   = new lime_test( 44, new lime_output_color() );

  $className = ESMTP;
  $className = strtolower ( substr ($className, 0,1) ) . substr ($className, 1 );
  
  $reflect = new ReflectionClass( $className );
	$method = array ( );
	$testItems = 0;
 
  foreach ( $reflect->getMethods() as $reflectmethod )  {  
  	$params = '';
  	foreach ( $reflectmethod->getParameters() as $key => $row )   {  
  	  if ( $params != '' ) $params .= ', ';
  	  $params .= '$' . $row->name;  
  	}

 		$testItems++;
  	$methods[ $reflectmethod->getName() ] = $params;
  }
  //To change the case only the first letter of each word, TIA
  $className = ucwords($className);
  $t->diag("class $className" );

  //$t->isa_ok( $obj  , $className,  "class $className created");

  $t->is( count($methods) , 21,  "class $className have " . 21 . ' methods.' );

  // Methods     $t->is ( $aMethods[0],
  $aMethods = array_keys ( $methods );

  //checking method 'ESMTP'
  $t->is ( $aMethods[0],      'ESMTP',   'ESMTP() is callable' );

  //$result = $obj->ESMTP ( );
  //$t->isa_ok( $result,      'NULL',   'call to method ESMTP ');
  $t->todo( "call to method ESMTP using  ");


  //checking method 'Connect'
  $t->is ( $aMethods[1],      'Connect',   'Connect() is callable' );

  //$result = $obj->Connect ( $host, $port, $tval);
  //$t->isa_ok( $result,      'NULL',   'call to method Connect ');
  $t->todo( "call to method Connect using $host, $port, $tval ");


  //checking method 'Authenticate'
  $t->is ( $aMethods[2],      'Authenticate',   'Authenticate() is callable' );

  //$result = $obj->Authenticate ( $username, $password);
  //$t->isa_ok( $result,      'NULL',   'call to method Authenticate ');
  $t->todo( "call to method Authenticate using $username, $password ");


  //checking method 'Connected'
  $t->is ( $aMethods[3],      'Connected',   'Connected() is callable' );

  //$result = $obj->Connected ( );
  //$t->isa_ok( $result,      'NULL',   'call to method Connected ');
  $t->todo( "call to method Connected using  ");


  //checking method 'Close'
  $t->is ( $aMethods[4],      'Close',   'Close() is callable' );

  //$result = $obj->Close ( );
  //$t->isa_ok( $result,      'NULL',   'call to method Close ');
  $t->todo( "call to method Close using  ");


  //checking method 'Data'
  $t->is ( $aMethods[5],      'Data',   'Data() is callable' );

  //$result = $obj->Data ( $msg_data);
  //$t->isa_ok( $result,      'NULL',   'call to method Data ');
  $t->todo( "call to method Data using $msg_data ");


  //checking method 'Expand'
  $t->is ( $aMethods[6],      'Expand',   'Expand() is callable' );

  //$result = $obj->Expand ( $name);
  //$t->isa_ok( $result,      'NULL',   'call to method Expand ');
  $t->todo( "call to method Expand using $name ");


  //checking method 'Hello'
  $t->is ( $aMethods[7],      'Hello',   'Hello() is callable' );

  //$result = $obj->Hello ( $host);
  //$t->isa_ok( $result,      'NULL',   'call to method Hello ');
  $t->todo( "call to method Hello using $host ");


  //checking method 'SendHello'
  $t->is ( $aMethods[8],      'SendHello',   'SendHello() is callable' );

  //$result = $obj->SendHello ( $hello, $host);
  //$t->isa_ok( $result,      'NULL',   'call to method SendHello ');
  $t->todo( "call to method SendHello using $hello, $host ");


  //checking method 'Help'
  $t->is ( $aMethods[9],      'Help',   'Help() is callable' );

  //$result = $obj->Help ( $keyword);
  //$t->isa_ok( $result,      'NULL',   'call to method Help ');
  $t->todo( "call to method Help using $keyword ");


  //checking method 'Mail'
  $t->is ( $aMethods[10],      'Mail',   'Mail() is callable' );

  //$result = $obj->Mail ( $from);
  //$t->isa_ok( $result,      'NULL',   'call to method Mail ');
  $t->todo( "call to method Mail using $from ");


  //checking method 'Noop'
  $t->is ( $aMethods[11],      'Noop',   'Noop() is callable' );

  //$result = $obj->Noop ( );
  //$t->isa_ok( $result,      'NULL',   'call to method Noop ');
  $t->todo( "call to method Noop using  ");


  //checking method 'Quit'
  $t->is ( $aMethods[12],      'Quit',   'Quit() is callable' );

  //$result = $obj->Quit ( $close_on_error);
  //$t->isa_ok( $result,      'NULL',   'call to method Quit ');
  $t->todo( "call to method Quit using $close_on_error ");


  //checking method 'Recipient'
  $t->is ( $aMethods[13],      'Recipient',   'Recipient() is callable' );

  //$result = $obj->Recipient ( $to);
  //$t->isa_ok( $result,      'NULL',   'call to method Recipient ');
  $t->todo( "call to method Recipient using $to ");


  //checking method 'Reset'
  $t->is ( $aMethods[14],      'Reset',   'Reset() is callable' );

  //$result = $obj->Reset ( );
  //$t->isa_ok( $result,      'NULL',   'call to method Reset ');
  $t->todo( "call to method Reset using  ");


  //checking method 'Send'
  $t->is ( $aMethods[15],      'Send',   'Send() is callable' );

  //$result = $obj->Send ( $from);
  //$t->isa_ok( $result,      'NULL',   'call to method Send ');
  $t->todo( "call to method Send using $from ");


  //checking method 'SendAndMail'
  $t->is ( $aMethods[16],      'SendAndMail',   'SendAndMail() is callable' );

  //$result = $obj->SendAndMail ( $from);
  //$t->isa_ok( $result,      'NULL',   'call to method SendAndMail ');
  $t->todo( "call to method SendAndMail using $from ");


  //checking method 'SendOrMail'
  $t->is ( $aMethods[17],      'SendOrMail',   'SendOrMail() is callable' );

  //$result = $obj->SendOrMail ( $from);
  //$t->isa_ok( $result,      'NULL',   'call to method SendOrMail ');
  $t->todo( "call to method SendOrMail using $from ");


  //checking method 'Turn'
  $t->is ( $aMethods[18],      'Turn',   'Turn() is callable' );

  //$result = $obj->Turn ( );
  //$t->isa_ok( $result,      'NULL',   'call to method Turn ');
  $t->todo( "call to method Turn using  ");


  //checking method 'Verify'
  $t->is ( $aMethods[19],      'Verify',   'Verify() is callable' );

  //$result = $obj->Verify ( $name);
  //$t->isa_ok( $result,      'NULL',   'call to method Verify ');
  $t->todo( "call to method Verify using $name ");


  //checking method 'get_lines'
  $t->is ( $aMethods[20],      'get_lines',   'get_lines() is callable' );

  //$result = $obj->get_lines ( );
  //$t->isa_ok( $result,      'NULL',   'call to method get_lines ');
  $t->todo( "call to method get_lines using  ");



  $t->todo (  'review all pendings methods in this class');
