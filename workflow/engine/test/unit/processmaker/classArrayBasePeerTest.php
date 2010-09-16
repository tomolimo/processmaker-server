<?php
/**
 * classArrayBasePeerTest.php
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

  G::LoadClass ( 'ArrayPeer');

  $t   = new lime_test( 44, new lime_output_color() );
  $className = "ArrayBasePeer";
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
  $t->diag("class $className" );
  //$t->isa_ok( $obj  , $className,  "class $className created");

  $t->is( count($methods) , 21,  "class $className have " . 21 . ' methods.' );
  $aMethods = array_keys ( $methods );

  //checking method 'getMapBuilder'
  $t->is ( $aMethods[0],      'getMapBuilder',   'getMapBuilder() is callable' );

  //$result = $obj->getMapBuilder ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getMapBuilder ');
  $t->todo( "call to method getMapBuilder using  ");


  //checking method 'getPhpNameMap'
  $t->is ( $aMethods[1],      'getPhpNameMap',   'getPhpNameMap() is callable' );

  //$result = $obj->getPhpNameMap ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getPhpNameMap ');
  $t->todo( "call to method getPhpNameMap using  ");

  //checking method 'translateFieldName'
  $t->is ( $aMethods[2],      'translateFieldName',   'translateFieldName() is callable' );

  //$result = $obj->translateFieldName ( $name, $fromType, $toType);
  //$t->isa_ok( $result,      'NULL',   'call to method translateFieldName ');
  $t->todo( "call to method translateFieldName using $name, $fromType, $toType ");


  //checking method 'getFieldNames'
  $t->is ( $aMethods[3],      'getFieldNames',   'getFieldNames() is callable' );

  //$result = $obj->getFieldNames ( $type);
  //$t->isa_ok( $result,      'NULL',   'call to method getFieldNames ');
  $t->todo( "call to method getFieldNames using $type ");


  //checking method 'alias'
  $t->is ( $aMethods[4],      'alias',   'alias() is callable' );

  //$result = $obj->alias ( $alias, $column);
  //$t->isa_ok( $result,      'NULL',   'call to method alias ');
  $t->todo( "call to method alias using $alias, $column ");

  //checking method 'addSelectColumns'
  $t->is ( $aMethods[5],      'addSelectColumns',   'addSelectColumns() is callable' );

  //$result = $obj->addSelectColumns ( $criteria);
  //$t->isa_ok( $result,      'NULL',   'call to method addSelectColumns ');
  $t->todo( "call to method addSelectColumns using $criteria ");


  //checking method 'doCount'
  $t->is ( $aMethods[6],      'doCount',   'doCount() is callable' );

  //$result = $obj->doCount ( $criteria, $distinct, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doCount ');
  $t->todo( "call to method doCount using $criteria, $distinct, $con ");


  //checking method 'doSelectOne'
  $t->is ( $aMethods[7],      'doSelectOne',   'doSelectOne() is callable' );

  //$result = $obj->doSelectOne ( $criteria, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doSelectOne ');
  $t->todo( "call to method doSelectOne using $criteria, $con ");


  //checking method 'createSelectSql'
  $t->is ( $aMethods[8],      'createSelectSql',   'createSelectSql() is callable' );

  //$result = $obj->createSelectSql ( $criteria, $tableName, $params);
  //$t->isa_ok( $result,      'NULL',   'call to method createSelectSql ');
  $t->todo( "call to method createSelectSql using $criteria, $tableName, $params ");


  //checking method 'doSelect'
  $t->is ( $aMethods[9],      'doSelect',   'doSelect() is callable' );

  //$result = $obj->doSelect ( $criteria, $tableName, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doSelect ');
  $t->todo( "call to method doSelect using $criteria, $tableName, $con ");


  //checking method 'doSelectRS'
  $t->is ( $aMethods[10],      'doSelectRS',   'doSelectRS() is callable' );

  //$result = $obj->doSelectRS ( $criteria, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doSelectRS ');
  $t->todo( "call to method doSelectRS using $criteria, $con ");


  //checking method 'populateObjects'
  $t->is ( $aMethods[11],      'populateObjects',   'populateObjects() is callable' );

  //$result = $obj->populateObjects ( $rs);
  //$t->isa_ok( $result,      'NULL',   'call to method populateObjects ');
  $t->todo( "call to method populateObjects using $rs ");


  //checking method 'getTableMap'
  $t->is ( $aMethods[12],      'getTableMap',   'getTableMap() is callable' );

  //$result = $obj->getTableMap ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getTableMap ');
  $t->todo( "call to method getTableMap using  ");


  //checking method 'getOMClass'
  $t->is ( $aMethods[13],      'getOMClass',   'getOMClass() is callable' );

  //$result = $obj->getOMClass ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getOMClass ');
  $t->todo( "call to method getOMClass using  ");


  //checking method 'doInsert'
  $t->is ( $aMethods[14],      'doInsert',   'doInsert() is callable' );

  //$result = $obj->doInsert ( $values, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doInsert ');
  $t->todo( "call to method doInsert using $values, $con ");


  //checking method 'doUpdate'
  $t->is ( $aMethods[15],      'doUpdate',   'doUpdate() is callable' );

  //$result = $obj->doUpdate ( $values, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doUpdate ');
  $t->todo( "call to method doUpdate using $values, $con ");


  //checking method 'doDeleteAll'
  $t->is ( $aMethods[16],      'doDeleteAll',   'doDeleteAll() is callable' );

  //$result = $obj->doDeleteAll ( $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doDeleteAll ');
  $t->todo( "call to method doDeleteAll using $con ");


  //checking method 'doDelete'
  $t->is ( $aMethods[17],      'doDelete',   'doDelete() is callable' );

  //$result = $obj->doDelete ( $values, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method doDelete ');
  $t->todo( "call to method doDelete using $values, $con ");


  //checking method 'doValidate'
  $t->is ( $aMethods[18],      'doValidate',   'doValidate() is callable' );

  //$result = $obj->doValidate ( $obj, $cols);
  //$t->isa_ok( $result,      'NULL',   'call to method doValidate ');
  $t->todo( "call to method doValidate using $obj, $cols ");


  //checking method 'retrieveByPK'
  $t->is ( $aMethods[19],      'retrieveByPK',   'retrieveByPK() is callable' );

  //$result = $obj->retrieveByPK ( $pk, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method retrieveByPK ');
  $t->todo( "call to method retrieveByPK using $pk, $con ");


  //checking method 'retrieveByPKs'
  $t->is ( $aMethods[20],      'retrieveByPKs',   'retrieveByPKs() is callable' );

  //$result = $obj->retrieveByPKs ( $pks, $con);
  //$t->isa_ok( $result,      'NULL',   'call to method retrieveByPKs ');
  $t->todo( "call to method retrieveByPKs using $pks, $con ");



  $t->todo (  'review all pendings methods in this class');
  
