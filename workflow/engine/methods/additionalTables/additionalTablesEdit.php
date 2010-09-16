<?php
/**
 * additionalTablesEdit.php
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
global $RBAC;
if ($RBAC->userCanAccess('PM_SETUP') != 1) {
  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
	G::header('location: ../login/login');
	die;
}

if (!isset($_GET['sUID'])) {
  G::header('Location: additionalTablesList');
  die;
}

if ($_GET['sUID'] == '') {
  G::header('Location: additionalTablesList');
  die;
}

$G_MAIN_MENU            = 'processmaker';
//$G_SUB_MENU             = 'setup';
$G_ID_MENU_SELECTED     = 'SETUP';
//$G_ID_SUB_MENU_SELECTED = 'ADDITIONAL_TABLES';

require_once 'classes/model/AdditionalTables.php';
$oAdditionalTables = new AdditionalTables();
$aData = $oAdditionalTables->load($_GET['sUID'], true);
if ($aData['ADD_TAB_SDW_LOG_INSERT'] == 1) {
  $aData['ADD_TAB_SDW_LOG_INSERT'] = 'on';
}
else {
  $aData['ADD_TAB_SDW_LOG_INSERT'] = '';
}
if ($aData['ADD_TAB_SDW_LOG_UPDATE'] == 1) {
  $aData['ADD_TAB_SDW_LOG_UPDATE'] = 'on';
}
else {
  $aData['ADD_TAB_SDW_LOG_UPDATE'] = '';
}
if ($aData['ADD_TAB_SDW_LOG_DELETE'] == 1) {
  $aData['ADD_TAB_SDW_LOG_DELETE'] = 'on';
}
else {
  $aData['ADD_TAB_SDW_LOG_DELETE'] = '';
}
if ($aData['ADD_TAB_SDW_LOG_SELECT'] == 1) {
  $aData['ADD_TAB_SDW_LOG_SELECT'] = 'on';
}
else {
  $aData['ADD_TAB_SDW_LOG_SELECT'] = '';
}
if ($aData['ADD_TAB_SDW_AUTO_DELETE'] == 1) {
  $aData['ADD_TAB_SDW_AUTO_DELETE'] = 'on';
}
else {
  $aData['ADD_TAB_SDW_AUTO_DELETE'] = '';
}
foreach ($aData['FIELDS'] as $iRow => $aRow) {
  if ($aRow['FLD_NULL'] == 1) {
    $aData['FIELDS'][$iRow]['FLD_NULL'] = 'on';
  }
  else {
    $aData['FIELDS'][$iRow]['FLD_NULL'] = '';
  }
  if ($aRow['FLD_AUTO_INCREMENT'] == 1) {
    $aData['FIELDS'][$iRow]['FLD_AUTO_INCREMENT'] = 'on';
  }
  else {
    $aData['FIELDS'][$iRow]['FLD_AUTO_INCREMENT'] = '';
  }
  if ($aRow['FLD_KEY'] == 1) {
    $aData['FIELDS'][$iRow]['FLD_KEY'] = 'on';
  }
  else {
    $aData['FIELDS'][$iRow]['FLD_KEY'] = '';
  }
  if ($aRow['FLD_FOREIGN_KEY'] == 1) {
    $aData['FIELDS'][$iRow]['FLD_FOREIGN_KEY'] = 'on';
  }
  else {
    $aData['FIELDS'][$iRow]['FLD_FOREIGN_KEY'] = '';
  }
}

$G_PUBLISH = new Publisher();
$G_PUBLISH->AddContent('xmlform', 'xmlform', 'additionalTables/additionalTablesEdit', '', $aData, '../additionalTables/additionalTablesSave');
G::RenderPage('publishBlank', 'blank');