<?php
/**
 * authSources_Edit.php
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
  if ($RBAC->userCanAccess('PM_SETUP_ADVANCE') != 1) {
    G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	G::header('location: ../login/login');
  	die;
  }
  
  if (!isset($_GET['sUID'])) {
    G::SendTemporalMessage('ID_ERROR_OBJECT_NOT_EXISTS', 'error', 'labels');
  	G::header('location: authSources_List');
  	die;
  }
  
  if ($_GET['sUID'] == '') {
    G::SendTemporalMessage('ID_ERROR_OBJECT_NOT_EXISTS', 'error', 'labels');
  	G::header('location: authSources_List');
  	die;
  }
  
  $G_MAIN_MENU            = 'processmaker';
  $G_SUB_MENU             = 'users';
  $G_ID_MENU_SELECTED     = 'USERS';
  $G_ID_SUB_MENU_SELECTED = 'AUTH_SOURCES';
  
  $aFields = $RBAC->getAuthSource($_GET['sUID']);
  if (is_array($aFields['AUTH_SOURCE_DATA'])) {
    foreach($aFields['AUTH_SOURCE_DATA'] as $sField => $sValue) {
      $aFields[$sField] = $sValue;
    }
  }
  unset($aFields['AUTH_SOURCE_DATA']);
  
 //fixing a problem with dropdown with int values, 
 //the problem : the value was integer, but the dropdown was expecting a string value, and they returns always the first item of dropdown
 if ( isset($aFields['AUTH_SOURCE_ENABLED_TLS']))  
   $aFields['AUTH_SOURCE_ENABLED_TLS'] = sprintf('%d', $aFields['AUTH_SOURCE_ENABLED_TLS'] );
 if ( isset($aFields['AUTH_ANONYMOUS']))  
   $aFields['AUTH_ANONYMOUS'] = sprintf('%d', $aFields['AUTH_ANONYMOUS'] );
   
  $G_PUBLISH = new Publisher();
  if ($aFields['AUTH_SOURCE_PROVIDER'] == 'ldap') {
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'authSources/ldapEdit', '', $aFields, '../authSources/authSources_Save');
  }
  else {
    if (file_exists(PATH_XMLFORM . 'authSources/' . $aFields['AUTH_SOURCE_PROVIDER'] . 'Edit.xml')) {
      $G_PUBLISH->AddContent('xmlform', 'xmlform', 'authSources/' . $aFields['AUTH_SOURCE_PROVIDER'] . 'Edit', '', $aFields, '../authSources/authSources_Save');
    }
    else {
      $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', array('MESSAGE' => 'File: ' . $aFields['AUTH_SOURCE_PROVIDER'] . 'Edit.xml' . ' doesn\'t exist.'));
    }
  }
  G::RenderPage('publish','blank');