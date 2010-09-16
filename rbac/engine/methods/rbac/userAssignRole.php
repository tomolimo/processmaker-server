<?php
/**
 * userAssignRole.php
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
//$G_MAIN_MENU         = 'rbac';
//$G_SUB_MENU          = 'rbac.userView';
//$G_MENU_SELECTED     = 0;
//$G_SUB_MENU_SELECTED = 3;

unset($_SESSION['CURRENT_APPLICATION']);
G::LoadClassRBAC('user');
$dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME);

$obj = new RBAC_user;
$obj->SetTo($dbc);
$access = $RBAC->userCanAccess('RBAC_CREATE_USERS');

$obj->SetTo($dbc);
$obj->Load($_SESSION['CURRENT_USER']);
$useLdap = $obj->Fields['USR_USE_LDAP'] == 'Y';

$ses = new DBSession;
$ses->SetTo ($dbc);

$stQry  = 'SELECT ROL_APPLICATION FROM USER_ROLE LEFT JOIN ROLE AS R ON (ROL_UID = R.UID) WHERE USR_UID = ' . $_SESSION['CURRENT_USER'];
$dset   = $ses->Execute($stQry);
$row    = $dset->Read();
$inApps = '(0';
while (is_array($row))
{
  $inApps .= ', ' . (int)$row['ROL_APPLICATION'];
  $row = $dset->Read();
}
$inApps .= ')';
$obj->Fields['INAPPS'] = $inApps;

$stQry = 'SELECT COUNT(*) AS CANT FROM APPLICATION WHERE UID NOT IN ' . $inApps;
$dset  = $ses->Execute($stQry);
$row   = $dset->Read();

$G_PUBLISH = new Publisher;
$G_PUBLISH->SetTo ($dbc);

if ( $row['CANT'] > 0 )
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'rbac/userAssignRole', '', $obj->Fields, 'userAssignRole2');
else
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'rbac/noMoreRolesAvailable', '', $obj->Fields, 'userViewRole');

G::RenderPage( 'publish', 'blank');
?>