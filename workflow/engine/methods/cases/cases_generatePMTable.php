<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once ( "classes/model/AdditionalTables.php" );
require_once ( "classes/model/Fields.php" );
// passing the parameters
$pmTableName   = (isset($_POST['tableName'])) ? $_POST['tableName'] : 'listTable';
$pmTableFields = (isset($_POST['tableFields'])) ? json_decode($_POST['tableFields']) : array();

// default parameters
//$pmTableName   = 'Sender';
$pmTableFields = array(array('FLD_NAME'=>'APP_UID'),array('FLD_NAME'=>'_cedula'));

// setting the data to assemble the table
$aData = array();
$aData['ADD_TAB_NAME'] = $pmTableName;
// creating the objects to create the table and records
$oFields = new Fields();
$oAdditionalTables = new AdditionalTables();

$sAddTabUid = $oAdditionalTables->create($aData, $pmTableFields);

foreach ($pmTableFields as $iRow => $aRow) {
  $pmTableFields[$iRow]['FLD_NAME'] = strtoupper($aRow['FLD_NAME']);
  $pmTableFields[$iRow]['FLD_DESCRIPTION'] = isset($aRow['FLD_DESCRIPTION']) ? $aRow['FLD_DESCRIPTION'] : $aRow['FLD_NAME'];
  $pmTableFields[$iRow]['FLD_TYPE'] = isset($aRow['FLD_TYPE']) ? $aRow['FLD_TYPE'] : 'VARCHAR';
  $pmTableFields[$iRow]['FLD_SIZE'] = isset($aRow['FLD_SIZE']) ? $aRow['FLD_SIZE'] : '32';
  $pmTableFields[$iRow]['FLD_NULL'] = isset($aRow['FLD_NULL']) ? $aRow['FLD_NULL'] : 'off';
  $pmTableFields[$iRow]['FLD_AUTO_INCREMENT'] = isset($aRow['FLD_AUTO_INCREMENT']) ? $aRow['FLD_AUTO_INCREMENT'] : 'off';
  $pmTableFields[$iRow]['FLD_KEY']            = isset($aRow['FLD_KEY']) ? $aRow['FLD_KEY'] : 'off';
  $pmTableFields[$iRow]['FLD_FOREIGN_KEY']    = isset($aRow['FLD_FOREIGN_KEY']) ? $aRow['FLD_FOREIGN_KEY'] : 'off';
  $pmTableFields[$iRow]['FLD_FOREIGN_KEY_TABLE'] = isset($aRow['FLD_FOREIGN_KEY_TABLE']) ? $aRow['FLD_FOREIGN_KEY_TABLE'] : '';
}

foreach ($pmTableFields as $iRow => $aRow) {

  $oFields->create(array('FLD_INDEX'             => $iRow+1,
                         'ADD_TAB_UID'           => $sAddTabUid,
                         'FLD_NAME'              => $aRow['FLD_NAME'],
                         'FLD_DESCRIPTION'       => isset($aRow['FLD_DESCRIPTION']) ? $aRow['FLD_DESCRIPTION'] : '',
                         'FLD_TYPE'              => isset($aRow['FLD_TYPE']) ? $aRow['FLD_TYPE'] : 'VARCHAR',
                         'FLD_SIZE'              => isset($aRow['FLD_SIZE']) ? $aRow['FLD_SIZE'] : '32',
                         'FLD_NULL'              => ($aRow['FLD_NULL'] == 'on' ? 1 : 0),
                         'FLD_AUTO_INCREMENT'    => ($aRow['FLD_AUTO_INCREMENT'] == 'on' ? 1 : 0),
                         'FLD_KEY'               => ($aRow['FLD_KEY'] == 'on' ? 1 : 0),
                         'FLD_FOREIGN_KEY'       => ($aRow['FLD_FOREIGN_KEY'] == 'on' ? 1 : 0),
                         'FLD_FOREIGN_KEY_TABLE' => isset($aRow['FLD_FOREIGN_KEY_TABLE']) ? $aRow['FLD_FOREIGN_KEY_TABLE'] : ''));

  $aFields[] = array('sType'       => isset($aRow['FLD_TYPE']) ? $aRow['FLD_TYPE'] : 'VARCHAR',
                     'iSize'       => isset($aRow['FLD_SIZE']) ? $aRow['FLD_SIZE'] : '32',
                     'sFieldName'  => $aRow['FLD_NAME'],
                     'bNull'       => ($aRow['FLD_NULL'] == 'on' ? 1 : 0),
                     'bAI'         => ($aRow['FLD_AUTO_INCREMENT'] == 'on' ? 1 : 0),
                     'bPrimaryKey' => ($aRow['FLD_KEY'] == 'on' ? 1 : 0));
}

$oAdditionalTables->createTable(strtoupper($pmTableName), 'wf', $aFields);
$oAdditionalTables->createPropelClasses(strtoupper($pmTableName), $pmTableName, $pmTableFields, $sAddTabUid);

?>
