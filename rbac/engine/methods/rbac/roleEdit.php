<?php
/**
 * roleEdit.php
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

$G_MAIN_MENU = "rbac";
$G_BACK_PAGE = "rbac/roleList";
$G_SUB_MENU  = "cancel";
$G_MENU_SELECTED = 1;

$uid = isset($_GET[0])?$_GET[0]:'';//$URI_VARS[0];
$_SESSION['CURRENT_ROLE_PARENT'] = $uid;
$dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );


G::LoadClassRBAC ("roles");
$obj = new RBAC_Role;
$obj->SetTo ($dbc);
$obj->Load($uid);

$obj->Fields['EDIT_ROLES'] = G::LoadMessageXml ('ID_ROLES');
$obj->Fields['EDIT_PERMISSIONS'] = G::LoadMessageXml ('ID_PERMISSIONS');

$G_PUBLISH = new Publisher;
$G_PUBLISH->SetTo ($dbc);
$fields = $obj->Fields;
$fields['CURRENT_APPLICATION'] = $_SESSION['CURRENT_APPLICATION'];
$G_PUBLISH->AddContent ( "xmlform", "xmlform", "rbac/roleEdit", "", $fields, "roleEdit2");
$content = '';//G::LoadContent( "rbac/myApp" );
G::RenderPage( "publish" );

?>