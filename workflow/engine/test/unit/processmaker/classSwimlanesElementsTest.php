<?php
/**
 * classSwimlanesElementsTest.php
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
require_once(PATH_CORE . 'classes/model/SwimlanesElements.php');

$obj = new SwimlanesElements();
$t   = new lime_test(7, new lime_output_color());

$t->diag('Class SwimlanesElements');

//class SwimlanesElements
$t->isa_ok($obj, 'SwimlanesElements', 'Class SwimlanesElements created!');

//method load
$t->can_ok($obj, 'load', 'load() is callable!');

//method create
$t->can_ok($obj, 'create', 'create() is callable!');

//method update
$t->can_ok($obj, 'update', 'update() is callable!');

//method remove
$t->can_ok($obj, 'remove', 'remove() is callable!');

//method getSwiEleText
$t->can_ok($obj, 'getSwiEleText', 'getSwiEleText() is callable!');

//method setSwiEleText
$t->can_ok($obj, 'setSwiEleText', 'setSwiEleText() is callable!');

/*****   TEST CLASS SWIMLANESELEMENTS   *****/
///////// INITIAL VALUES /////////
define('SYS_LANG', 'en');
//Test class
class SwimlanesElementsTest extends unitTest
{
  function loadTest($aTestData, $aFields)
  {
    $oSwimlanesElements = new SwimlanesElements();
    try {
      return $oSwimlanesElements->load($aFields['SWI_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function createTest($aTestData, $aFields)
  {
    $oSwimlanesElements = new SwimlanesElements();
    try {
      return $oSwimlanesElements->create($aFields);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function updateTest($aTestData, $aFields)
  {
    $oSwimlanesElements = new SwimlanesElements();
    try {
      return $oSwimlanesElements->update($aFields);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function removeTest($aTestData, $aFields)
  {
    $oSwimlanesElements = new SwimlanesElements();
    try {
      return $oSwimlanesElements->remove($aFields['SWI_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
}
//Initialize the test class (ymlTestDefinitionFile, limeTestObject, testDomain)
$oSwimlanesElementsTest = new SwimlanesElementsTest('swimlanesElements.yml', $t, new ymlDomain());
$oSwimlanesElementsTest->load('load1');
$vAux = $oSwimlanesElementsTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oSwimlanesElementsTest->load('load2');
$vAux = $oSwimlanesElementsTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oSwimlanesElementsTest->load('create1');
$vAux = $oSwimlanesElementsTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oSwimlanesElementsTest->load('create2');
$vAux = $oSwimlanesElementsTest->runSingle();
//var_dump($vAux);echo "\n\n";*/
$oSwimlanesElementsTest->load('update1');
$vAux = $oSwimlanesElementsTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oSwimlanesElementsTest->load('update2');
$vAux = $oSwimlanesElementsTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oSwimlanesElementsTest->load('remove1');
$vAux = $oSwimlanesElementsTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oSwimlanesElementsTest->load('remove2');
$vAux = $oSwimlanesElementsTest->runSingle();
//var_dump($vAux);echo "\n\n";
?>