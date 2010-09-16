<?php
/**
 * classGroupsTest.php
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

  G::LoadClass ( 'groups');


  $obj = new Groups ($dbc); 
  $t   = new lime_test( 29, new lime_output_color() );

  $className = Groups;
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

   //checking method 'getUsersOfGroup'
  $t->can_ok( $obj,      'getUsersOfGroup',   'getUsersOfGroup() is callable' );

  //$result = $obj->getUsersOfGroup ( $sGroupUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getUsersOfGroup ');
  $t->todo( "call to method getUsersOfGroup using $sGroupUID ");


  //checking method 'getActiveGroupsForAnUser'
  $t->can_ok( $obj,      'getActiveGroupsForAnUser',   'getActiveGroupsForAnUser() is callable' );

  //$result = $obj->getActiveGroupsForAnUser ( $sUserUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getActiveGroupsForAnUser ');
  $t->todo( "call to method getActiveGroupsForAnUser using $sUserUID ");


  //checking method 'addUserToGroup'
  $t->can_ok( $obj,      'addUserToGroup',   'addUserToGroup() is callable' );

  //$result = $obj->addUserToGroup ( $GrpUid, $UsrUid);
  //$t->isa_ok( $result,      'NULL',   'call to method addUserToGroup ');
  $t->todo( "call to method addUserToGroup using $GrpUid, $UsrUid ");


  //checking method 'removeUserOfGroup'
  $t->can_ok( $obj,      'removeUserOfGroup',   'removeUserOfGroup() is callable' );

  //$result = $obj->removeUserOfGroup ( $GrpUid, $UsrUid);
  //$t->isa_ok( $result,      'NULL',   'call to method removeUserOfGroup ');
  $t->todo( "call to method removeUserOfGroup using $GrpUid, $UsrUid ");


  //checking method 'getAllGroups'
  $t->can_ok( $obj,      'getAllGroups',   'getAllGroups() is callable' );

  //$result = $obj->getAllGroups ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getAllGroups ');
  $t->todo( "call to method getAllGroups using  ");


  //checking method 'getUserGroups'
  $t->can_ok( $obj,      'getUserGroups',   'getUserGroups() is callable' );

  //$result = $obj->getUserGroups ( $sUserUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getUserGroups ');
  $t->todo( "call to method getUserGroups using $sUserUID ");


  //checking method 'removeUserOfAllGroups'
  $t->can_ok( $obj,      'removeUserOfAllGroups',   'removeUserOfAllGroups() is callable' );

  //$result = $obj->removeUserOfAllGroups ( $sUserUID);
  //$t->isa_ok( $result,      'NULL',   'call to method removeUserOfAllGroups ');
  $t->todo( "call to method removeUserOfAllGroups using $sUserUID ");


  //checking method 'getUsersGroupCriteria'
  $t->can_ok( $obj,      'getUsersGroupCriteria',   'getUsersGroupCriteria() is callable' );

  //$result = $obj->getUsersGroupCriteria ( $sGroupUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getUsersGroupCriteria ');
  $t->todo( "call to method getUsersGroupCriteria using $sGroupUID ");


  //checking method 'getUserGroupsCriteria'
  $t->can_ok( $obj,      'getUserGroupsCriteria',   'getUserGroupsCriteria() is callable' );

  //$result = $obj->getUserGroupsCriteria ( $sUserUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getUserGroupsCriteria ');
  $t->todo( "call to method getUserGroupsCriteria using $sUserUID ");


  //checking method 'getNumberGroups'
  $t->can_ok( $obj,      'getNumberGroups',   'getNumberGroups() is callable' );

  //$result = $obj->getNumberGroups ( $sUserUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getNumberGroups ');
  $t->todo( "call to method getNumberGroups using $sUserUID ");


  //checking method 'getAvailableUsersCriteria'
  $t->can_ok( $obj,      'getAvailableUsersCriteria',   'getAvailableUsersCriteria() is callable' );

  //$result = $obj->getAvailableUsersCriteria ( $sGroupUID);
  //$t->isa_ok( $result,      'NULL',   'call to method getAvailableUsersCriteria ');
  $t->todo( "call to method getAvailableUsersCriteria using $sGroupUID ");


  //checking method 'verifyUsertoGroup'
  $t->can_ok( $obj,      'verifyUsertoGroup',   'verifyUsertoGroup() is callable' );

  //$result = $obj->verifyUsertoGroup ( $GrpUid, $UsrUid);
  //$t->isa_ok( $result,      'NULL',   'call to method verifyUsertoGroup ');
  $t->todo( "call to method verifyUsertoGroup using $GrpUid, $UsrUid ");


  //checking method 'verifyGroup'
  $t->can_ok( $obj,      'verifyGroup',   'verifyGroup() is callable' );

  //$result = $obj->verifyGroup ( $sGroupUID);
  //$t->isa_ok( $result,      'NULL',   'call to method verifyGroup ');
  $t->todo( "call to method verifyGroup using $sGroupUID ");



  $t->todo (  'review all pendings methods in this class');
