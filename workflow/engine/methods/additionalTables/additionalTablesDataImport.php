<?php
/**
 * additionalTablesDataImport.php
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
if (preg_match('/[\x00-\x08\x0b-\x0c\x0e\x1f]/', file_get_contents($_FILES['form']['tmp_name']['CSV_FILE'])) === 0) {
  if ($oFile = fopen($_FILES['form']['tmp_name']['CSV_FILE'], 'r')) {
    require_once 'classes/model/AdditionalTables.php';
    $oAdditionalTables = new AdditionalTables();
    $aAdditionalTables = $oAdditionalTables->load($_POST['form']['ADD_TAB_UID'], true);
    $sErrorMessages    = '';
    $i = 1;
    while (($aAux = fgetcsv($oFile, 4096, $_POST['form']['CSV_DELIMITER'])) !== false) {
      if ($i > 1) {
        $aData = array();
        $j     = 0;
        foreach ($aAdditionalTables['FIELDS'] as $aField) {
          $aData[$aField['FLD_NAME']] = (isset($aAux[$j]) ? $aAux[$j] : '');
          $j++;
        }
        try {
          if (!$oAdditionalTables->saveDataInTable($_POST['form']['ADD_TAB_UID'], $aData)) {
            $sErrorMessages .= G::LoadTranslation('ID_DUPLICATE_ENTRY_PRIMARY_KEY') . ', ' . G::LoadTranslation('ID_LINE') . ' ' . $i . '<br />';
          }
        }
  	    catch (Exception $oError) {
  	      $sErrorMessages .= G::LoadTranslation('ID_ERROR_INSERT_LINE') . ': ' . G::LoadTranslation('ID_LINE') . ' ' . $i . '<br />';
  	    }
      }
      $i++;
    }
    fclose($oFile);
  }
  if ($sErrorMessages != '') {
    G::SendMessageText($sErrorMessages, 'warning');
  }
  G::header('Location: additionalTablesData?sUID=' . $_POST['form']['ADD_TAB_UID']);
  die;
}
else {
  G::SendTemporalMessage('ID_UPLOAD_VALID_CSV_FILE', 'error', 'labels');
  G::header('Location: additionalTablesData?sUID=' . $_POST['form']['ADD_TAB_UID']);
  die;
}
