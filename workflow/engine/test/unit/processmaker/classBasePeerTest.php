<?php
/**
 * classBasePeerTest.php
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

  G::LoadClass ( 'BasePeer');


  $obj = new BasePeer ($dbc); 
  $t   = new lime_test( 31, new lime_output_color() );

  $className = BasePeer;
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

  $t->isa_ok( $obj  , $className,  "class $className created");

  $t->is( count($methods) , 14,  "class $className have " . 14 . ' methods.' );

   //checking method 'getFieldnames'
  $t->can_ok( $obj,      'getFieldnames',   'getFieldnames() is callable' );

  //$result = $obj->getFieldnames ( $classname, $type);
  //$t->isa_ok( $result,      'NULL',   'call to method getFieldnames ');
  $t->todo( "call to method getFieldnames using $classname, $type ");


  //checking method 'translateFieldname'
  $t->can_ok( $obj,      'translateFieldname',   'translateFieldname() is callable' );

  //$result = $obj->translateFieldname ( $classname, $fieldname, $fromType, $toType);
  //$t->isa_ok( $result,      'NULL',   'call to method translateFieldname ');
  $t->todo( "call to method translateFieldname using $classname, $fieldname, $fromType, $toType ");


  //checking method 'doDelete'
  $t->can_ok( $obj,      'doDelete',   'doDelete() is callable' );

  //$result = $obj->doDelete ( $criteria, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doDelete ');
  $t->todo( "call to method doDelete using $criteria, $con ");


  //checking method 'doDeleteAll'
  $t->can_ok( $obj,      'doDeleteAll',   'doDeleteAll() is callable' );

  //$result = $obj->doDeleteAll ( $tableName, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doDeleteAll ');
  $t->todo( "call to method doDeleteAll using $tableName, $con ");


  //checking method 'doInsert'
  $t->can_ok( $obj,      'doInsert',   'doInsert() is callable' );

  //$result = $obj->doInsert ( $criteria, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doInsert ');
  $t->todo( "call to method doInsert using $criteria, $con ");


  //checking method 'doUpdate'
  $t->can_ok( $obj,      'doUpdate',   'doUpdate() is callable' );

  //$result = $obj->doUpdate ( $selectCriteria, $updateValues, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doUpdate ');
  $t->todo( "call to method doUpdate using $selectCriteria, $updateValues, $con ");


  //checking method 'doSelect'
  $t->can_ok( $obj,      'doSelect',   'doSelect() is callable' );

  //$result = $obj->doSelect ( $criteria, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doSelect ');
  $t->todo( "call to method doSelect using $criteria, $con ");


  //checking method 'doValidate'
  $t->can_ok( $obj,      'doValidate',   'doValidate() is callable' );

  //$result = $obj->doValidate ( $dbName, $tableName, $columns);
  //$t->isa_ok( $result,      'NULL',   'call to method doValidate ');
  $t->todo( "call to method doValidate using $dbName, $tableName, $columns ");


  //checking method 'getPrimaryKey'
  $t->can_ok( $obj,      'getPrimaryKey',   'getPrimaryKey() is callable' );

  //$result = $obj->getPrimaryKey ( $criteria);
  //$t->isa_ok( $result,      'NULL',   'call to method getPrimaryKey ');
  $t->todo( "call to method getPrimaryKey using $criteria ");


  //checking method 'createSelectSql'
  $t->can_ok( $obj,      'createSelectSql',   'createSelectSql() is callable' );

  //$result = $obj->createSelectSql ( $criteria, $params);
  //$t->isa_ok( $result,      'NULL',   'call to method createSelectSql ');
  $t->todo( "call to method createSelectSql using $criteria, $params ");


  //checking method 'buildParams'
  $t->can_ok( $obj,      'buildParams',   'buildParams() is callable' );

  //$result = $obj->buildParams ( $columns, $values);
  //$t->isa_ok( $result,      'NULL',   'call to method buildParams ');
  $t->todo( "call to method buildParams using $columns, $values ");


  //checking method 'populateStmtValues'
  $t->can_ok( $obj,      'populateStmtValues',   'populateStmtValues() is callable' );

  //$result = $obj->populateStmtValues ( $stmt, $params, $dbMap);
  //$t->isa_ok( $result,      'NULL',   'call to method populateStmtValues ');
  $t->todo( "call to method populateStmtValues using $stmt, $params, $dbMap ");


  //checking method 'getValidator'
  $t->can_ok( $obj,      'getValidator',   'getValidator() is callable' );

  //$result = $obj->getValidator ( $classname);
  //$t->isa_ok( $result,      'NULL',   'call to method getValidator ');
  $t->todo( "call to method getValidator using $classname ");


  //checking method 'getMapBuilder'
  $t->can_ok( $obj,      'getMapBuilder',   'getMapBuilder() is callable' );

  //$result = $obj->getMapBuilder ( $classname);
  //$t->isa_ok( $result,      'NULL',   'call to method getMapBuilder ');
  $t->todo( "call to method getMapBuilder using $classname ");



  $t->todo (  'review all pendings methods in this class');