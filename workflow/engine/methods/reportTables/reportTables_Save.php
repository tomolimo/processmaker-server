<?php
/**
 * triggers_Save.php
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
if (($RBAC_Response=$RBAC->userCanAccess("PM_FACTORY"))!=1) return $RBAC_Response;

G::LoadClass('reportTables');

$oReportTable = new ReportTable();
if (!isset($_POST['form']['REP_TAB_CONNECTION'])) {
  $_POST['form']['REP_TAB_CONNECTION'] = 'report';
}
if ($_POST['form']['REP_TAB_UID'] != '') {
  $aReportTable   = $oReportTable->load($_POST['form']['REP_TAB_UID']);
  $sOldTableName  = $aReportTable['REP_TAB_NAME'];
  $sOldConnection = $aReportTable['REP_TAB_CONNECTION'];
}
else {
  $sOldTableName  = $_POST['form']['REP_TAB_NAME'];
  $sOldConnection = $_POST['form']['REP_TAB_CONNECTION'];
  $oReportTable->create($_POST['form']);
  $_POST['form']['REP_TAB_UID'] = $oReportTable->getRepTabUid();
}

$oReportTable->update($_POST['form']);
$oReportVar = new ReportVar();
$oReportTables = new ReportTables();
$oReportTables->deleteAllReportVars($_POST['form']['REP_TAB_UID']);
$aFields = array();
if ($_POST['form']['REP_TAB_TYPE'] == 'GRID') {
  $aAux = explode('-', $_POST['form']['REP_TAB_GRID']);
  global $G_FORM;
  $G_FORM = new Form($_POST['form']['PRO_UID'] . '/' . $aAux[1], PATH_DYNAFORM, SYS_LANG, false);
  $aAux = $G_FORM->getVars(false);
  foreach ($aAux as $aField) {
    $_POST['form']['FIELDS'][] = $aField['sName'] . '-' . $aField['sType'];
  }
}
foreach ($_POST['form']['FIELDS'] as $sField) {
  $aField = explode('-', $sField);
  switch ($aField[1]) {
    case 'currency':
    case 'percentage':
      $sType = 'number';
    break;
    case 'text':
    case 'password':
    case 'dropdown':
    case 'yesno':
    case 'checkbox':
    case 'radiogroup':
    case 'hidden':
      $sType = 'char';
    break;
    case 'textarea':
      $sType = 'text';
    break;
    case 'date':
      $sType = 'date';
    break;
    default:
      $sType = 'char';
    break;
  }
  $oReportVar->create(array('REP_TAB_UID'  => $_POST['form']['REP_TAB_UID'],
                            'PRO_UID'      => $_POST['form']['PRO_UID'],
                            'REP_VAR_NAME' => $aField[0],
                            'REP_VAR_TYPE' => $sType));
  $aFields[] = array('sFieldName' => $aField[0], 'sType' => $sType);
}
$oReportTables->dropTable($sOldTableName, $sOldConnection);
$oReportTables->createTable($_POST['form']['REP_TAB_NAME'], $_POST['form']['REP_TAB_CONNECTION'], $_POST['form']['REP_TAB_TYPE'], $aFields);
$oReportTables->populateTable($_POST['form']['REP_TAB_NAME'], $_POST['form']['REP_TAB_CONNECTION'], $_POST['form']['REP_TAB_TYPE'], $aFields, $_POST['form']['PRO_UID'], $_POST['form']['REP_TAB_GRID']);
?>