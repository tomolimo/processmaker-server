<?php
/**
 * classGroupUserTest.php
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
	require_once(PATH_THIRDPARTY . '/lime/lime.php');
	require_once(PATH_THIRDPARTY.'lime/yaml.class.php');
	
	require_once(PATH_CORE . 'config/databases.php');
	require_once('propel/Propel.php');
	Propel::init(PATH_CORE . 'config/databases.php');
	
	G::LoadThirdParty('smarty/libs', 'Smarty.class');
	G::LoadSystem('error');
	G::LoadSystem('xmlform');
	G::LoadSystem('xmlDocument');
	G::LoadSystem('form');
	G::LoadSystem('dbtable');
	G::LoadSystem('testTools');
	require_once(PATH_CORE . 'classes/model/GroupUser.php');
	
	$obj = new GroupUser();
	$t   = new lime_test(3, new lime_output_color());
	
	$t->diag('Class GroupUser');
	
	//class GroupUser
	$t->isa_ok($obj, 'GroupUser', 'Class GroupUser created!');
	
	//method create
	$t->can_ok($obj, 'create', 'create() is callable!');
	
	//method remove
	$t->can_ok($obj, 'remove', 'remove() is callable!');
	
	/*****   TEST CLASS GROUPUSER   *****/
	///////// INITIAL VALUES /////////
	define('SYS_LANG', 'en');
	//Test class
	class GroupUserTest extends unitTest
	{
	  function createTest($aTestData, $aFields)
	  {
	    $oGroupUser = new GroupUser();
	    try {
	      return $oGroupUser->create($aFields);
	    }
	    catch (Exception $oError) {
	    	return $oError;
	    }
	  }
	  function removeTest($aTestData, $aFields)
	  {
	    $oGroupUser = new GroupUser();
	    try {
	      return $oGroupUser->remove($aFields['GRP_UID'], $aFields['USR_UID']);
	    }
	    catch (Exception $oError) {
	    	return $oError;
	    }
	  }
	}
	//Initialize the test class (ymlTestDefinitionFile, limeTestObject, testDomain)
	$oGroupUserTest = new GroupUserTest('groupUser.yml', $t, new ymlDomain());
	$oGroupUserTest->load('create1');
	$vAux = $oGroupUserTest->runSingle();
	//var_dump($vAux);echo "\n\n";
	$oGroupUserTest->load('create2');
	$vAux = $oGroupUserTest->runSingle();
	//var_dump($vAux);echo "\n\n";
	$oGroupUserTest->load('remove1');
	$vAux = $oGroupUserTest->runSingle();
	//var_dump($vAux);echo "\n\n";
	$oGroupUserTest->load('remove2');
	$vAux = $oGroupUserTest->runSingle();
	//var_dump($vAux);echo "\n\n";
	?>