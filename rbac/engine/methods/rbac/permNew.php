<?php
/**
 * permNew.php
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
$G_BACK_PAGE = "rbac/permList";
$G_SUB_MENU  = "cancel";
$G_MENU_SELECTED = 1;

$permid = isset($_GET['UID'])?$_GET['UID']:'';//$URI_VARS[0];
$HTTP_SESSION_VARS['CURRENT_PERM_PARENT'] = $permid;

$dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );

$G_PUBLISH = new Publisher;
$G_PUBLISH->SetTo ($dbc);

$fields['APPID'] = $_SESSION['CURRENT_APPLICATION'];
if ( PEAR_DATABASE == 'pgsql' ) {
  $CONCAT1 = "APP_CODE || \" - \" || APP_DESCRIPTION ";
  $CONCAT2 = "PRM_CODE || \" - \" || PRM_DESCRIPTION ";
}
else {
  $CONCAT1 = "CONCAT(APP_CODE,\" - \",APP_DESCRIPTION) ";
  $CONCAT2 = "CONCAT(PRM_CODE,\" - \",PRM_DESCRIPTION) ";
}
$fields['CONCAT1'] = $CONCAT1;
$fields['CONCAT2'] = $CONCAT2;
$fields['PERMID'] = $permid;

$G_PUBLISH->AddContent ( "xmlform", "xmlform", "rbac/permNew", "", $fields, "permNew2");
$content = '';//G::LoadContent( "rbac/myApp" );
G::RenderPage( "publish" );

?>