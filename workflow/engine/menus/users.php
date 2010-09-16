<?php
/**
 * users.php
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
global $G_TMP_MENU;

$G_TMP_MENU->AddIdRawOption('USERS', 'users/users_List');
//$G_TMP_MENU->AddIdRawOption('NEW_USER', 'users/users_New');
//$G_TMP_MENU->AddIdRawOption('DEPARTMENTS', 'departments/departments_List');
$G_TMP_MENU->AddIdRawOption('GROUPS', 'groups/groups');
$G_TMP_MENU->AddIdRawOption('DEPARTMENTS', 'departments/departments');
$G_TMP_MENU->AddIdRawOption('ROLES', 'roles/roles_List');
global $RBAC;
if ($RBAC->userCanAccess('PM_SETUP_ADVANCE') == 1) {
  $G_TMP_MENU->AddIdRawOption('AUTH_SOURCES', 'authSources/authSources_List');
}

$G_TMP_MENU->Labels = array(
  G::LoadTranslation('ID_USERS_LIST'),
  //G::LoadTranslation('ID_NEW'),
  //G::LoadTranslation('ID_ORGANIZATIONAL_CHART'),
  G::LoadTranslation('ID_GROUP_USERS'),
  G::LoadTranslation('ID_DEPARTMENTS_USERS'),
  G::LoadTranslation('ID_ROLES'),
  G::LoadTranslation('ID_AUTH_SOURCES')
);