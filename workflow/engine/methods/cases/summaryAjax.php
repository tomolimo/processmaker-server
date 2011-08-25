<?php
/**
 * summaryAjax.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2011 Colosa Inc.
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

if (!isset($_REQUEST['action'])) {
  $_REQUEST['action'] = 'getDataSummary';
}
if ($_REQUEST['action'] == '') {
  $_REQUEST['action'] = 'getDataSummary';
}

try {
  global $RBAC;
  switch ($RBAC->userCanAccess('PM_CASES')) {
  	case -2:
  	  throw new Exception(G::LoadTranslation('ID_USER_HAVENT_RIGHTS_SYSTEM'));
  	break;
  	case -1:
  	  throw new Exception(G::LoadTranslation('ID_USER_HAVENT_RIGHTS_PAGE'));
  	break;
  }

  if (!isset($_REQUEST['APP_UID']) || !isset($_REQUEST['DEL_INDEX'])) {
    throw new Exception(G::LoadTranslation('ID_REQUIRED_FIELDS_ERROR') . ' (APP_UID, DEL_INDEX)');
  }

  G::LoadClass('case');
  $case = new Cases();
  if ($RBAC->userCanAccess('PM_ALLCASES') < 0 && $case->userParticipatedInCase($_REQUEST['APP_UID'], $_SESSION['USER_LOGGED']) == 0) {
    throw new Exception(G::LoadTranslation('ID_NO_PERMISSION_NO_PARTICIPATED'));
  }

  $json = new Services_JSON();
  $response = new stdclass();
  $response->type = 'OK';
  switch ($_REQUEST['action']) {
    case 'getDataSummary':
      $response->labels = new stdclass();
      $form = new Form('cases/cases_Resume', PATH_XMLFORM, SYS_LANG);
      foreach($form->fields as $fieldName => $field) {
        $response->labels->{$fieldName} = $field->label;
      }
      $applicationFields = $case->loadCase($_REQUEST['APP_UID'], $_REQUEST['DEL_INDEX']);
      $process = new Process();
      $processData = $process->load($applicationFields['PRO_UID']);
      if (!isset($processData['PRO_DYNAFORMS']['PROCESS'])) {
        $processData['PRO_DYNAFORMS']['PROCESS'] = '';
      }
      $task = new Task();
      $taskData = $task->load($applicationFields['TAS_UID']);
      $response->values = new stdclass();
      $response->values->PRO_TITLE = $processData['PRO_TITLE'];
      $response->values->TITLE = $applicationFields['TITLE'];
      $response->values->APP_NUMBER = $applicationFields['APP_NUMBER'];
      $response->values->STATUS = $applicationFields['STATUS'];
      $response->values->APP_UID = $applicationFields['APP_UID'];
      $response->values->CREATOR = $applicationFields['CREATOR'];
      $response->values->CREATE_DATE = $applicationFields['CREATE_DATE'];
      $response->values->UPDATE_DATE = $applicationFields['UPDATE_DATE'];
      $response->values->TAS_TITLE = $taskData['TAS_TITLE'];
      $response->values->CURRENT_USER = $applicationFields['CURRENT_USER'];
      $response->values->DEL_DELEGATE_DATE = $applicationFields['DEL_DELEGATE_DATE'];
      $response->values->DEL_INIT_DATE = $applicationFields['DEL_INIT_DATE'];
      $response->values->DEL_TASK_DUE_DATE = $applicationFields['DEL_TASK_DUE_DATE'];
      $response->values->DEL_FINISH_DATE = $applicationFields['DEL_FINISH_DATE'];
      $response->values->DYN_UID = $processData['PRO_DYNAFORMS']['PROCESS'];
    break;
  }
  die($json->encode($response));
}
catch (Exception $error) {
  $response = new stdclass();
  $response->type = 'ERROR';
  $response->message = $error->getMessage();
  $json = new Services_JSON();
  die($json->encode($response));
}