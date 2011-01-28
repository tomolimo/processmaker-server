<?php
/**
 * rolesUsersPermission.php
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
 **/

global $RBAC;
switch ($RBAC->userCanAccess('PM_USERS')) {
        case - 2:
            G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
            G::header('location: ../login/login');
            die;
            break;
        case - 1:
            G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
            G::header('location: ../login/login');
            die;
            break;
            case -3:
              G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
              G::header('location: ../login/login');
              die;
            break;
}
	
$G_MAIN_MENU = 'processmaker';
$G_SUB_MENU = 'users';
$G_ID_MENU_SELECTED = 'USERS';
$G_ID_SUB_MENU_SELECTED = 'ROLES';
	

$G_PUBLISH = new Publisher;

$oHeadPublisher =& headPublisher::getSingleton();

$oHeadPublisher->addExtJsScript('roles/rolesUsersPermission', false);    //adding a javascript file .js
$oHeadPublisher->addContent('roles/rolesUsersPermission'); //adding a html file  .html.

$labels = G::getTranslations(Array('ID_PRO_CREATE_DATE','ID_CODE','ID_NAME','ID_LAN_UPDATE_DATE', 'ID_ROLES',
  'ID_USERS','ID_PERMISSIONS','ID_EDIT','ID_DELETE','ID_NEW','ID_STATUS','ID_SAVE','ID_CLOSE',
  'ID_ACTIVE','ID_INACTIVE','ID_ROLES_MSG','ID_ROLES_CAN_NOT_DELETE','ID_ROLES_SUCCESS_NEW','ID_ROLES_SUCCESS_UPDATE',
  'ID_ROLES_SUCCESS_DELETE','ID_REMOVE_ROLE','ID_ASSIGN','ID_REMOVE','ID_BACK','ID_PROCESSING',
  'ID_REMOVE_ALL_PERMISSIONS','ID_ASSIGN_ALL_PERMISSIONS','ID_ASSIGN_ALL_USERS','ID_REMOVE_ALL_USERS',
  'ID_USER_NAME','ID_PERMISSION_CODE','ID_AVAILABLE_PERMISSIONS','ID_ASSIGNED_PERMISSIONS','ID_ENTER_SEARCH_TERM',
  'ID_FIRST_NAME','ID_LAST_NAME','ID_AVAILABLE_USERS','ID_ASSIGNED_USERS','ID_MSG_CONFIRM_ASSIGN_ALL_USERS','ID_MSG_AJAX_FAILURE'));

$roles = Array();
$roles['ROL_UID'] = $_GET['rUID'];
$roles['ROL_CODE'] = $RBAC->getRoleCode($_GET['rUID']);
$roles['CURRENT_TAB'] = ($_GET['tab']=='permissions') ? 1 : 0;

$oHeadPublisher->assign('TRANSLATIONS', $labels);
$oHeadPublisher->assign('ROLES', $roles);
G::RenderPage('publish', 'extJs');
	
?>