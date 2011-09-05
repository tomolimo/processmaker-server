<?php
/**
 * classOutputDocumentTest.php
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
require_once(PATH_CORE . 'classes/model/OutputDocument.php');

$obj = new OutputDocument();
$t   = new lime_test(21, new lime_output_color());

$t->diag('Class OutputDocument');

//class OutputDocument
$t->isa_ok($obj, 'OutputDocument', 'Class OutputDocument created!');

//method load
$t->can_ok($obj, 'load', 'load() is callable!');

//method create
$t->can_ok($obj, 'create', 'create() is callable!');

//method update
$t->can_ok($obj, 'update', 'update() is callable!');

//method remove
$t->can_ok($obj, 'remove', 'remove() is callable!');

//method getOutDocTitle
$t->can_ok($obj, 'getOutDocTitle', 'getOutDocTitle() is callable!');

//method setOutDocTitle
$t->can_ok($obj, 'setOutDocTitle', 'setOutDocTitle() is callable!');

//method getOutDocComment
$t->can_ok($obj, 'getOutDocDescription', 'getOutDocDescription() is callable!');

//method setOutDocComment
$t->can_ok($obj, 'setOutDocDescription', 'setOutDocDescription() is callable!');

//method getOutDocFilename
$t->can_ok($obj, 'getOutDocFilename', 'getOutDocFilename() is callable!');

//method setOutDocFilename
$t->can_ok($obj, 'setOutDocFilename', 'setOutDocFilename() is callable!');

//method getOutDocTemplate
$t->can_ok($obj, 'getOutDocTemplate', 'getOutDocTemplate() is callable!');

//method setOutDocTemplate
$t->can_ok($obj, 'setOutDocTemplate', 'setOutDocTemplate() is callable!');

/*****   TEST CLASS OUTPUTDOCUMENT   *****/
///////// INITIAL VALUES /////////
define('SYS_LANG', 'en');
//Test class
class OutputDocumentTest extends unitTest
{
  function loadTest($aTestData, $aFields)
  {
    $oOutputDocument = new OutputDocument();
    try {
      return $oOutputDocument->load($aFields['OUT_DOC_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function createTest($aTestData, $aFields)
  {
    $oOutputDocument = new OutputDocument();
    try {
      return $oOutputDocument->create($aFields);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function updateTest($aTestData, $aFields)
  {
    $oOutputDocument = new OutputDocument();
    try {
      return $oOutputDocument->update($aFields);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
  function removeTest($aTestData, $aFields)
  {
    $oOutputDocument = new OutputDocument();
    try {
      return $oOutputDocument->remove($aFields['OUT_DOC_UID']);
    }
    catch (Exception $oError) {
    	return $oError;
    }
  }
}
//Initialize the test class (ymlTestDefinitionFile, limeTestObject, testDomain)
$oOutputDocumentTest = new OutputDocumentTest('outputDocument.yml', $t, new ymlDomain());
$oOutputDocumentTest->load('load1');
$vAux = $oOutputDocumentTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oOutputDocumentTest->load('load2');
$vAux = $oOutputDocumentTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oOutputDocumentTest->load('create1');
$vAux = $oOutputDocumentTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oOutputDocumentTest->load('create2');
$vAux = $oOutputDocumentTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oOutputDocumentTest->load('update1');
$vAux = $oOutputDocumentTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oOutputDocumentTest->load('update2');
$vAux = $oOutputDocumentTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oOutputDocumentTest->load('remove1');
$vAux = $oOutputDocumentTest->runSingle();
//var_dump($vAux);echo "\n\n";
$oOutputDocumentTest->load('remove2');
$vAux = $oOutputDocumentTest->runSingle();
//var_dump($vAux);echo "\n\n";
?>