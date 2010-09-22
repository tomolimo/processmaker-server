<?php
/**
 * languages_Import.php
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
$access = $RBAC->userCanAccess('PM_SETUP_ADVANCE');
if( $access != 1 ) {
  switch( $access ) {
    case -1:
      G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
      G::header('location: ../login/login');
      die;
    break;
    case -2:
      G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
      G::header('location: ../login/login');
      die;
    break;
    default:
      G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
      G::header('location: ../login/login');
      die;
    break;
  }
}
$result = new stdClass();

try {
  if(!is_writable(PATH_XMLFORM))
    throw new Exception(G::LoadTranslation('IMPORT_LANGUAGE_ERR_NO_WRITABLE'));
  
  $sMaxExecutionTime = ini_get('max_execution_time');
  ini_set('max_execution_time', '0');
  G::LoadClass('languages');
  G::LoadClass('configuration');
  
  $languages     = new languages();
  $configuration = new Configurations;
  $importResults = $languages->importLanguage($_FILES['form']['tmp_name']['LANGUAGE_FILENAME']);

  //G::SendTemporalMessage('IMPORT_LANGUAGE_SUCCESS', 'info', 'labels');
  //G::header('location: languages');
  
  $result->msg = G::LoadTranslation('IMPORT_LANGUAGE_SUCCESS') . "\n";
  $result->msg .= "PO File num. records: " . $importResults->recordsCount . "\n";
  $result->msg .= "Records registered successfully : " . $importResults->recordsCountSuccess . "\n";
  //$result->msg = htmlentities($result->msg);
  $result->success = true;
  
  //saving metadata
  
  $configuration->aConfig  = Array(
    'headers'     => $importResults->headers,
    'language'    => $importResults->lang,
    'import-date' => date('Y-m-d H:i:s'),
    'user'        => '',
    'version'     => '1.0'
  );
  $configuration->saveConfig('LANGUAGE_META', $importResults->lang);
  
  ini_set('max_execution_time', $sMaxExecutionTime);
  
} catch (Exception $oError) {
  $result->msg = $oError->getMessage();
  //print_r($oError->getTrace());
  $result->success = false;
  //G::SendTemporalMessage($oError->getMessage(), 'error', 'string');
  //G::header('location: languages_ImportForm');
}
echo G::json_encode($result);