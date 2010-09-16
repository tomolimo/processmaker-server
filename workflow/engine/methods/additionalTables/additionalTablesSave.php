<?php
/**
 * additionalTablesSave.php
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
unset($_POST['form']['ADD_TAB_NAME_OLD']);
unset($_POST['form']['ADD_TAB_CLASS_NAME_OLD']);
if (!isset($_POST['form']['ADD_TAB_SDW_LOG_INSERT'])) {
  $_POST['form']['ADD_TAB_SDW_LOG_INSERT'] = '';
}
if (!isset($_POST['form']['ADD_TAB_SDW_LOG_UPDATE'])) {
  $_POST['form']['ADD_TAB_SDW_LOG_UPDATE'] = '';
}
if (!isset($_POST['form']['ADD_TAB_SDW_LOG_DELETE'])) {
  $_POST['form']['ADD_TAB_SDW_LOG_DELETE'] = '';
}
if (!isset($_POST['form']['ADD_TAB_SDW_LOG_SELECT'])) {
  $_POST['form']['ADD_TAB_SDW_LOG_SELECT'] = '';
}
if (!isset($_POST['form']['ADD_TAB_SDW_MAX_LENGTH'])) {
  $_POST['form']['ADD_TAB_SDW_MAX_LENGTH'] = 0;
}
if (!isset($_POST['form']['ADD_TAB_SDW_AUTO_DELETE'])) {
  $_POST['form']['ADD_TAB_SDW_AUTO_DELETE'] = '';
}
foreach ($_POST['form']['FIELDS'] as $iRow => $aRow) {
  if (!isset($_POST['form']['FIELDS'][$iRow]['FLD_NULL'])) {
    $_POST['form']['FIELDS'][$iRow]['FLD_NULL'] = '';
  }
  if (!isset($_POST['form']['FIELDS'][$iRow]['FLD_AUTO_INCREMENT'])) {
    $_POST['form']['FIELDS'][$iRow]['FLD_AUTO_INCREMENT'] = '';
  }
  if (!isset($_POST['form']['FIELDS'][$iRow]['FLD_KEY'])) {
    $_POST['form']['FIELDS'][$iRow]['FLD_KEY'] = '';
  }
  if (!isset($_POST['form']['FIELDS'][$iRow]['FLD_FOREIGN_KEY'])) {
    $_POST['form']['FIELDS'][$iRow]['FLD_FOREIGN_KEY'] = '';
  }
  if (!isset($_POST['form']['FIELDS'][$iRow]['FLD_FOREIGN_KEY_TABLE'])) {
    $_POST['form']['FIELDS'][$iRow]['FLD_FOREIGN_KEY_TABLE'] = '';
  }
}
$aKeys   = array();
$aDynavars   = array();
$aNoKeys = array();
foreach ($_POST['form']['FIELDS'] as $aRow) {
  if ($aRow['FLD_KEY'] == 'on') {
    $aKeys[] = $aRow;
    $aDynavars[] = array('FLD_UID'=>$aRow['FLD_UID'],'CASE_VARIABLE'=>$aRow['CASE_VARIABLE']);
  }
  else {
    $aNoKeys[] = $aRow;
  }
}
//print_r($_POST);
$aDynavars = serialize($aDynavars);
//var_dump($aKeys);
//print_r($aDynavars);
//die;
$_POST['form']['FIELDS'] = array();
$i = 1;
foreach ($aKeys as $aRow) {
  $_POST['form']['FIELDS'][$i] = $aRow;
  $i++;
}
foreach ($aNoKeys as $aRow) {
  $_POST['form']['FIELDS'][$i] = $aRow;
  $i++;
}

require_once 'classes/model/AdditionalTables.php';
$oAdditionalTables = new AdditionalTables();
require_once 'classes/model/Fields.php';
$oFields = new Fields();

if ($_POST['form']['ADD_TAB_UID'] == '') {
    
    // We verified that the table does not exist.
    $aNameTable = $oAdditionalTables->loadByName($_POST['form']['ADD_TAB_NAME']);
    if(is_array($aNameTable)) {
        G::SendMessageText('There is already a table named "' . $_POST['form']['ADD_TAB_NAME'] . '" in the database. Table creation canceled.', 'warning');
        G::header('Location: additionalTablesList');
        die;
    }
    // Reserver Words
    $aReservedWords = array ('ALTER', 'CLOSE', 'COMMIT', 'CREATE', 'DECLARE',
                           'DELETE', 'DROP', 'FETCH', 'FUNCTION', 'GRANT', 
                           'INDEX', 'INSERT', 'OPEN', 'REVOKE', 'ROLLBACK', 
                           'SELECT', 'SYNONYM', 'TABLE', 'UPDATE', 'VIEW' );
    if (in_array(strtoupper($_POST['form']['ADD_TAB_NAME']), $aReservedWords) ) {
        G::SendMessageText('Could not create the table with the name "' . $_POST['form']['ADD_TAB_NAME'] . '" because it is a reserved word.', 'warning');
        G::header('Location: additionalTablesList');
        die;
    }  
    
  $sAddTabUid = $oAdditionalTables->create(array('ADD_TAB_NAME'            => $_POST['form']['ADD_TAB_NAME'],
                                                 'ADD_TAB_CLASS_NAME'      => $_POST['form']['ADD_TAB_CLASS_NAME'],
                                                 'ADD_TAB_DESCRIPTION'     => $_POST['form']['ADD_TAB_DESCRIPTION'],
                                                 'ADD_TAB_SDW_LOG_INSERT'  => ($_POST['form']['ADD_TAB_SDW_LOG_INSERT'] == 'on' ? 1 : 0),
                                                 'ADD_TAB_SDW_LOG_UPDATE'  => ($_POST['form']['ADD_TAB_SDW_LOG_UPDATE'] == 'on' ? 1 : 0),
                                                 'ADD_TAB_SDW_LOG_DELETE'  => ($_POST['form']['ADD_TAB_SDW_LOG_DELETE'] == 'on' ? 1 : 0),
                                                 'ADD_TAB_SDW_LOG_SELECT'  => ($_POST['form']['ADD_TAB_SDW_LOG_SELECT'] == 'on' ? 1 : 0),
                                                 'ADD_TAB_SDW_MAX_LENGTH'  => $_POST['form']['ADD_TAB_SDW_MAX_LENGTH'],
                                                 'ADD_TAB_SDW_AUTO_DELETE' => ($_POST['form']['ADD_TAB_SDW_AUTO_DELETE'] == 'on' ? 1 : 0),
                                                 'ADD_TAB_DYNAVARS' => $aDynavars,
                                                 'ADD_TAB_PLG_UID'         => ''), $_POST['form']['FIELDS']);
  $aFields   = array();
  /*$aFields[] = array('sType'       => 'INT',
                     'iSize'       => '11',
                     'sFieldName'  => 'PM_UNIQUE_ID',
                     'bNull'       => 0,
                     'bAI'         => 1,
                     'bPrimaryKey' => 1);*/
  foreach ($_POST['form']['FIELDS'] as $iRow => $aRow) {
    $oFields->create(array('FLD_INDEX'             => $iRow,
                           'ADD_TAB_UID'           => $sAddTabUid,
                           'FLD_NAME'              => $_POST['form']['FIELDS'][$iRow]['FLD_NAME'],
                           'FLD_DESCRIPTION'       => $_POST['form']['FIELDS'][$iRow]['FLD_DESCRIPTION'],
                           'FLD_TYPE'              => $_POST['form']['FIELDS'][$iRow]['FLD_TYPE'],
                           'FLD_SIZE'              => $_POST['form']['FIELDS'][$iRow]['FLD_SIZE'],
                           'FLD_NULL'              => ($_POST['form']['FIELDS'][$iRow]['FLD_NULL'] == 'on' ? 1 : 0),
                           'FLD_AUTO_INCREMENT'    => ($_POST['form']['FIELDS'][$iRow]['FLD_AUTO_INCREMENT'] == 'on' ? 1 : 0),
                           'FLD_KEY'               => ($_POST['form']['FIELDS'][$iRow]['FLD_KEY'] == 'on' ? 1 : 0),
                           'FLD_FOREIGN_KEY'       => ($_POST['form']['FIELDS'][$iRow]['FLD_FOREIGN_KEY'] == 'on' ? 1 : 0),
                           'FLD_FOREIGN_KEY_TABLE' => $_POST['form']['FIELDS'][$iRow]['FLD_FOREIGN_KEY_TABLE']));
    $aFields[] = array('sType'       => $_POST['form']['FIELDS'][$iRow]['FLD_TYPE'],
                       'iSize'       => $_POST['form']['FIELDS'][$iRow]['FLD_SIZE'],
                       'sFieldName'  => $_POST['form']['FIELDS'][$iRow]['FLD_NAME'],
                       'bNull'       => ($_POST['form']['FIELDS'][$iRow]['FLD_NULL'] == 'on' ? 1 : 0),
                       'bAI'         => ($_POST['form']['FIELDS'][$iRow]['FLD_AUTO_INCREMENT'] == 'on' ? 1 : 0),
                       'bPrimaryKey' => ($_POST['form']['FIELDS'][$iRow]['FLD_KEY'] == 'on' ? 1 : 0));
  }
  $oAdditionalTables->createTable($_POST['form']['ADD_TAB_NAME'], 'wf', $aFields);
  $oAdditionalTables->createPropelClasses($_POST['form']['ADD_TAB_NAME'], $_POST['form']['ADD_TAB_CLASS_NAME'], $_POST['form']['FIELDS'], $sAddTabUid);
}
else {
  $aData = $oAdditionalTables->load($_POST['form']['ADD_TAB_UID'], true);
  $oAdditionalTables->update(array('ADD_TAB_UID'             => $_POST['form']['ADD_TAB_UID'],
                                   'ADD_TAB_NAME'            => $_POST['form']['ADD_TAB_NAME'],
                                   'ADD_TAB_CLASS_NAME'      => $_POST['form']['ADD_TAB_CLASS_NAME'],
                                   'ADD_TAB_DESCRIPTION'     => $_POST['form']['ADD_TAB_DESCRIPTION'],
                                   'ADD_TAB_SDW_LOG_INSERT'  => ($_POST['form']['ADD_TAB_SDW_LOG_INSERT'] == 'on' ? 1 : 0),
                                   'ADD_TAB_SDW_LOG_UPDATE'  => ($_POST['form']['ADD_TAB_SDW_LOG_UPDATE'] == 'on' ? 1 : 0),
                                   'ADD_TAB_SDW_LOG_DELETE'  => ($_POST['form']['ADD_TAB_SDW_LOG_DELETE'] == 'on' ? 1 : 0),
                                   'ADD_TAB_SDW_LOG_SELECT'  => ($_POST['form']['ADD_TAB_SDW_LOG_SELECT'] == 'on' ? 1 : 0),
                                   'ADD_TAB_SDW_MAX_LENGTH'  => $_POST['form']['ADD_TAB_SDW_MAX_LENGTH'],
                                   'ADD_TAB_SDW_AUTO_DELETE' => ($_POST['form']['ADD_TAB_SDW_AUTO_DELETE'] == 'on' ? 1 : 0),
                                   'ADD_TAB_DYNAVARS' => $aDynavars,
                                   'ADD_TAB_PLG_UID'         => ''), $_POST['form']['FIELDS']);
  $oCriteria = new Criteria('workflow');
  $oCriteria->add(FieldsPeer::ADD_TAB_UID, $_POST['form']['ADD_TAB_UID']);
  FieldsPeer::doDelete($oCriteria);
  $aNewFields = array();  
  foreach ($_POST['form']['FIELDS'] as $iRow => $aField) {
    $sUID = $oFields->create(array('FLD_UID'               => $_POST['form']['FIELDS'][$iRow]['FLD_UID'],
                                   'ADD_TAB_UID'           => $_POST['form']['ADD_TAB_UID'],
                                   'FLD_INDEX'             => $iRow,
                                   'FLD_NAME'              => $_POST['form']['FIELDS'][$iRow]['FLD_NAME'],
                                   'FLD_DESCRIPTION'       => $_POST['form']['FIELDS'][$iRow]['FLD_DESCRIPTION'],
                                   'FLD_TYPE'              => $_POST['form']['FIELDS'][$iRow]['FLD_TYPE'],
                                   'FLD_SIZE'              => $_POST['form']['FIELDS'][$iRow]['FLD_SIZE'],
                                   'FLD_NULL'              => ($_POST['form']['FIELDS'][$iRow]['FLD_NULL'] == 'on' ? 1 : 0),
                                   'FLD_AUTO_INCREMENT'    => ($_POST['form']['FIELDS'][$iRow]['FLD_AUTO_INCREMENT'] == 'on' ? 1 : 0),
                                   'FLD_KEY'               => ($_POST['form']['FIELDS'][$iRow]['FLD_KEY'] == 'on' ? 1 : 0),
                                   'FLD_FOREIGN_KEY'       => ($_POST['form']['FIELDS'][$iRow]['FLD_FOREIGN_KEY'] == 'on' ? 1 : 0),
                                   'FLD_FOREIGN_KEY_TABLE' => $_POST['form']['FIELDS'][$iRow]['FLD_FOREIGN_KEY_TABLE']));
    $aNewFields[$sUID] = $aField;
  }
  $aOldFields = array();
  foreach ($aData['FIELDS'] as $aField) {
    $aOldFields[$aField['FLD_UID']] = $aField;
  }
  $oAdditionalTables->updateTable($_POST['form']['ADD_TAB_NAME'], 'wf', $aNewFields, $aOldFields);
  $oAdditionalTables->createPropelClasses($_POST['form']['ADD_TAB_NAME'], $_POST['form']['ADD_TAB_CLASS_NAME'], $_POST['form']['FIELDS'], $aData['ADD_TAB_UID']);
}
G::header('Location: additionalTablesList');