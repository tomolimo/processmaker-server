<?php
/**
 * classProcessMapTest.php
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
require_once(PATH_THIRDPARTY . 'lime/lime.php');
require_once(PATH_THIRDPARTY . 'lime/yaml.class.php');

require_once(PATH_CORE . 'config/databases.php');
require_once('propel/Propel.php');
Propel::init(PATH_CORE . 'config/databases.php');

G::LoadThirdParty('smarty/libs', 'Smarty.class');
G::LoadSystem('error');
G::LoadSystem('xmlform');
G::LoadSystem('xmlDocument');
G::LoadSystem('form');
G::LoadSystem('dbtable');
G::LoadClass('plugin');
G::LoadSystem('dbconnection');
G::LoadSystem('dbsession');
G::LoadSystem('dbrecordset');
G::LoadSystem('templatePower');
G::LoadSystem('publisher');
G::LoadSystem('headPublisher');
G::LoadSystem('pagedTable');
  $oHeadPublisher =& headPublisher::getSingleton();
G::LoadSystem('testTools');
G::LoadClass('processMap');

$obj = new processMap();
$t   = new lime_test(30, new lime_output_color());

$t->diag('Class processMap');

//class processMap
$t->isa_ok($obj, 'processMap', 'Class processMap created!');

//method load
$t->can_ok($obj, 'load', 'load() is callable!');

//method createProcess
$t->can_ok($obj, 'createProcess', 'createProcess() is callable!');

//method updateProcess
$t->can_ok($obj, 'updateProcess', 'updateProcess() is callable!');

//method editProcess
$t->can_ok($obj, 'editProcess', 'editProcess() is callable!');

//method saveTitlePosition
$t->can_ok($obj, 'saveTitlePosition', 'saveTitlePosition() is callable!');

//method steps
$t->can_ok($obj, 'steps', 'steps() is callable!');

//method users
$t->can_ok($obj, 'users', 'users() is callable!');

//method stepsConditions
$t->can_ok($obj, 'stepsConditions', 'stepsConditions() is callable!');

//method stepsTriggers
$t->can_ok($obj, 'stepsTriggers', 'stepsTriggers() is callable!');

//method addTask
$t->can_ok($obj, 'addTask', 'addTask() is callable!');

//method editTaskProperties
$t->can_ok($obj, 'editTaskProperties', 'editTaskProperties() is callable!');

//method saveTaskPosition
$t->can_ok($obj, 'saveTaskPosition', 'saveTaskPosition() is callable!');

//method deleteTask
$t->can_ok($obj, 'deleteTask', 'deleteTask() is callable!');

//method addGuide
$t->can_ok($obj, 'addGuide', 'addGuide() is callable!');

//method saveGuidePosition
$t->can_ok($obj, 'saveGuidePosition', 'saveGuidePosition() is callable!');

//method deleteGuide
$t->can_ok($obj, 'deleteGuide', 'deleteGuide() is callable!');

//method deleteGuides
$t->can_ok($obj, 'deleteGuides', 'deleteGuides() is callable!');

//method addText
$t->can_ok($obj, 'addText', 'addText() is callable!');

//method updateText
$t->can_ok($obj, 'updateText', 'updateText() is callable!');

//method saveTextPosition
$t->can_ok($obj, 'saveTextPosition', 'saveTextPosition() is callable!');

//method deleteText
$t->can_ok($obj, 'deleteText', 'deleteText() is callable!');

//method dynaformsList
$t->can_ok($obj, 'dynaformsList', 'dynaformsList() is callable!');

//method outputdocsList
$t->can_ok($obj, 'outputdocsList', 'outputdocsList() is callable!');

//method inputdocsList
$t->can_ok($obj, 'inputdocsList', 'inputdocsList() is callable!');

//method triggersList
$t->can_ok($obj, 'triggersList', 'triggersList() is callable!');

//method messagesList
$t->can_ok($obj, 'messagesList', 'messagesList() is callable!');

//method currentPattern
$t->can_ok($obj, 'currentPattern', 'currentPattern() is callable!');

//method newPattern
$t->can_ok($obj, 'newPattern', 'newPattern() is callable!');

//method deleteDerivation
$t->can_ok($obj, 'deleteDerivation', 'deleteDerivation() is callable!');

/*****   TEST CLASS PROCESSMAP   *****/
///////// INITIAL VALUES /////////
define('SYS_LANG', 'en');
//Test class
class processMapTest extends unitTest {
  function loadTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->load($aFields['PRO_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function createProcessTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->createProcess($aFields);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function updateProcessTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->updateProcess($aFields);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function editProcessTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->editProcess($aFields['PRO_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function saveTitlePositionTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->saveTitlePosition($aFields['PRO_UID'], $aFields['PRO_X'], $aFields['PRO_Y']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function stepsTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->steps($aFields['PRO_UID'], $aFields['TAS_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function usersTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->users($aFields['PRO_UID'], $aFields['TAS_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function stepsConditionsTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->stepsConditions($aFields['PRO_UID'], $aFields['TAS_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function stepsTriggersTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->stepsTriggers($aFields['PRO_UID'], $aFields['TAS_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function addTaskTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->addTask($aFields['PRO_UID'], $aFields['TAS_X'], $aFields['TAS_Y']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function editTaskPropertiesTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->editTaskProperties($aFields['TAS_UID'], $aFields['iForm'], $aFields['iIndex']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function saveTaskPositionTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->saveTaskPosition($aFields['TAS_UID'], $aFields['TAS_X'], $aFields['TAS_Y']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function deleteTaskTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->deleteTask($aFields['TAS_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function addGuideTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->addGuide($aFields['PRO_UID'], $aFields['iPosition'], $aFields['sDirection']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function saveGuidePositionTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->saveGuidePosition($aFields['SWI_UID'], $aFields['iPosition'], $aFields['sDirection']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function deleteGuideTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->deleteGuide($aFields['SWI_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function deleteGuidesTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->deleteGuides($aFields['PRO_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function addTextTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->addText($aFields['PRO_UID'], $aFields['SWI_TEXT'], $aFields['SWI_X'], $aFields['SWI_Y']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function updateTextTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->updateText($aFields['SWI_UID'], $aFields['SWI_TEXT']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function saveTextPositionTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->saveTextPosition($aFields['SWI_UID'], $aFields['SWI_X'], $aFields['SWI_X']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function deleteTextTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->deleteText($aFields['SWI_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function dynaformsListTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->dynaformsList($aFields['PRO_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function outputdocsListTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->outputdocsList($aFields['PRO_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function inputdocsListTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->inputdocsList($aFields['PRO_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function triggersListTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->triggersList($aFields['PRO_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function messagesListTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->messagesList($aFields['PRO_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function currentPatternTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->currentPattern($aFields['PRO_UID'], $aFields['TAS_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function newPatternTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->newPattern($aFields['PRO_UID'], $aFields['TAS_UID'], $aFields['ROU_NEXT_TASK'], $aRow['ROU_TYPE']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function deleteDerivationTest($aTestData, $aFields) {
    $oProcessMap = new processMap();
    try {
      return $oProcessMap->deleteDerivation($aFields['TAS_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
}
//Initialize the test class (ymlTestDefinitionFile, limeTestObject, testDomain)
$oProcessMapTest = new processMapTest('processMap.yml', $t, new ymlDomain());
/*$oProcessMapTest->load('load1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('load2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oProcessMapTest->load('createProcess1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oProcessMapTest->load('updateProcess1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oProcessMapTest->load('updateProcess2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oProcessMapTest->load('editProcess1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('editProcess2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('saveTitlePosition1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('saveTitlePosition2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('steps1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('steps2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('users1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('users2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('stepsConditions1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('stepsConditions2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('stepsTriggers1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('stepsTriggers2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('addTask1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('addTask2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('editTaskProperties1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('editTaskProperties2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('saveTaskPosition1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('saveTaskPosition2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('deleteTask1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('deleteTask2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('addGuide1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('addGuide2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('saveGuidePosition1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('saveGuidePosition2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('deleteGuide1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('deleteGuide2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('deleteGuides1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('deleteGuides2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('addText1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('addText2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('updateText1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('updateText2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('saveTextPosition1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('saveTextPosition2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('deleteText1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('deleteText2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('dynaformsList1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('dynaformsList2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('outputdocsList1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('outputdocsList2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('inputdocsList1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('inputdocsList2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('triggersList1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('triggersList2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('messagesList1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('messagesList2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('currentPattern1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('currentPattern2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('newPattern1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('newPattern2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('deleteDerivation1');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;
$oProcessMapTest->load('deleteDerivation2');
$vAux = $oProcessMapTest->runSingle();
//var_dump($vAux);echo "\n\n";die;*/
?>