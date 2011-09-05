<?php
/**
 * classSpoolRunTest.php
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

  G::LoadClass ( 'spool');


//  $obj = new SpoolRun ($dbc);
  $t   = new lime_test( 24, new lime_output_color() );

  $className = SpoolRun;
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

  $t->is( count($methods) , 12,  "class $className have " . 12 . ' methods.' );
   // Methods
  $aMethods = array_keys ( $methods );
   //checking method '__construct'
  $t->is ( $aMethods[0],      '__construct',   '__construct() is callable' );

  //$result = $obj->__construct ( );
  //$t->isa_ok( $result,      'NULL',   'call to method __construct ');
  $t->todo( "call to method __construct using  ");


  //checking method 'getSpoolFilesList'
  $t->is ( $aMethods[1],      'getSpoolFilesList',   'getSpoolFilesList() is callable' );

  //$result = $obj->getSpoolFilesList ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getSpoolFilesList ');
  $t->todo( "call to method getSpoolFilesList using  ");


  //checking method 'create'
  $t->is ( $aMethods[2],      'create',   'create() is callable' );

  //$result = $obj->create ( $aData);
  //$t->isa_ok( $result,      'NULL',   'call to method create ');
  $t->todo( "call to method create using $aData ");


  //checking method 'setConfig'
  $t->is ( $aMethods[3],      'setConfig',   'setConfig() is callable' );

  //$result = $obj->setConfig ( $aConfig);
  //$t->isa_ok( $result,      'NULL',   'call to method setConfig ');
  $t->todo( "call to method setConfig using $aConfig ");


  //checking method 'setData'
  $t->is ( $aMethods[4],      'setData',   'setData() is callable' );

  //$result = $obj->setData ( $sAppMsgUid, $sSubject, $sFrom, $sTo, $sBody, $sDate, $sCC, $sBCC, $sTemplate);
  //$t->isa_ok( $result,      'NULL',   'call to method setData ');
  $t->todo( "call to method setData using $sAppMsgUid, $sSubject, $sFrom, $sTo, $sBody, $sDate, $sCC, $sBCC, $sTemplate ");


  //checking method 'sendMail'
  $t->is ( $aMethods[5],      'sendMail',   'sendMail() is callable' );

  //$result = $obj->sendMail ( );
  //$t->isa_ok( $result,      'NULL',   'call to method sendMail ');
  $t->todo( "call to method sendMail using  ");


  //checking method 'updateSpoolStatus'
  $t->is ( $aMethods[6],      'updateSpoolStatus',   'updateSpoolStatus() is callable' );

  //$result = $obj->updateSpoolStatus ( );
  //$t->isa_ok( $result,      'NULL',   'call to method updateSpoolStatus ');
  $t->todo( "call to method updateSpoolStatus using  ");


  //checking method 'handleFrom'
  $t->is ( $aMethods[7],      'handleFrom',   'handleFrom() is callable' );

  //$result = $obj->handleFrom ( );
  //$t->isa_ok( $result,      'NULL',   'call to method handleFrom ');
  $t->todo( "call to method handleFrom using  ");


  //checking method 'handleEnvelopeTo'
  $t->is ( $aMethods[8],      'handleEnvelopeTo',   'handleEnvelopeTo() is callable' );

  //$result = $obj->handleEnvelopeTo ( );
  //$t->isa_ok( $result,      'NULL',   'call to method handleEnvelopeTo ');
  $t->todo( "call to method handleEnvelopeTo using  ");


  //checking method 'handleMail'
  $t->is ( $aMethods[9],      'handleMail',   'handleMail() is callable' );

  //$result = $obj->handleMail ( );
  //$t->isa_ok( $result,      'NULL',   'call to method handleMail ');
  $t->todo( "call to method handleMail using  ");


  //checking method 'resendEmails'
  $t->is ( $aMethods[10],      'resendEmails',   'resendEmails() is callable' );

  //$result = $obj->resendEmails ( );
  //$t->isa_ok( $result,      'NULL',   'call to method resendEmails ');
  $t->todo( "call to method resendEmails using  ");



  $t->todo (  'review all pendings methods in this class');
