<?php
/**
 * classDashboardsTest.php
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
  require_once( 'propel/Propel.php' );
  require_once ( "creole/Creole.php" );
  Propel::init(  PATH_CORE . "config/databases.php");


  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem ( 'error');
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');

  require_once (  PATH_CORE . "config/databases.php");  

  G::LoadClass ( 'dashboards');

  $obj = new Dashboards ($dbc); 
  $t   = new lime_test( 9, new lime_output_color() );

  $className = Dashboards;
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
 
  $t->diag('class $className' );
  $t->isa_ok( $obj  , 'Dashboards',  'class $className created');

  $t->is( count($methods) , 3,  "class $className have " . 3 . ' methods.' );

  //checking method 'getConfiguration'
  $t->can_ok( $obj,      'getConfiguration',   'getConfiguration() is callable' );

  //$result = $obj->getConfiguration ( $sUserUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getConfiguration ');
  $t->todo( "call to method getConfiguration using $sUserUID ");


  //checking method 'saveConfiguration'
  $t->can_ok( $obj,      'saveConfiguration',   'saveConfiguration() is callable' );

  //$result = $obj->saveConfiguration ( $sUserUID, $aConfiguration);
  //$t->isa_ok( $result,      'NULL',   'call to method saveConfiguration ');
  $t->todo( "call to method saveConfiguration using $sUserUID, $aConfiguration ");


  //checking method 'getDashboardsObject'
  $t->can_ok( $obj,      'getDashboardsObject',   'getDashboardsObject() is callable' );

  //$result = $obj->getDashboardsObject ( $sUserUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getDashboardsObject ');
  $t->todo( "call to method getDashboardsObject using $sUserUID ");



  $t->todo (  'review all pendings methods in this class');
