<?php
/**
 * roleProp.php
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
$G_SUB_MENU  = "rbac.appView";
$G_MENU_SELECTED = 1;

$permid = isset($_GET[0])?$_GET[0]:'';//$URI_VARS[0];
$rolid  = $_SESSION['CURRENT_ROLE'];

G::LoadClassRBAC ( "roles" );
G::LoadClassRBAC ( "user" );
$dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );


$canCreateRole = $RBAC->userCanAccess("RBAC_CREATE_PERMISSION" );
$obj = New RBAC_role;
$obj->SetTo ($dbc);
$parents = $obj->GetAllParents($rolid);
$_SESSION['CURRENT_ROLE_PARENTS'] = $parents;

if ( $permid != "" ) {
  $obj->flipFlopRole($rolid, $permid);
}

$dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );

$G_PUBLISH = new Publisher;
$G_PUBLISH->SetTo ($dbc);
$G_PUBLISH->AddContent ( "view", "treePermRole");
$content = '';//G::LoadContent( "rbac/myApp" );
G::RenderPage( "publish" );

?>