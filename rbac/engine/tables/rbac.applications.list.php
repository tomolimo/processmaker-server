<?php
/**
 * rbac.applications.list.php
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

global $G_TMP_TABLE;
global $G_TMP_TARGET;
global $HTTP_SESSION_VARS;
global $canCreateApp; //verificar si tiene permiso para editar una APLICACION


$lang = SYS_LANG;

switch( $lang )
{
case 'po':
  $Labels = array(
    "ID",
    "Código",
    "Descripcion",
    "Roles",
    "Permisos",
  );
  break;
case 'es':
  $Labels = array(
    "ID",
    "Código",
    "Descripción",
    "Roles",
    "Permisos",
  );
  break;
default:
  $Labels = array(
    "ID",
    "Code",
    "Description",
    "Roles",
    "Permissions",
  );
  break;
}

$canEdit = ( $canCreateApp == 1? "link" : "text" );

$stQry = "SELECT *, '$Labels[3]' as ROL_UID, '$Labels[4]' AS PRM_UID " .
         "FROM APPLICATION AS A ORDER BY UID";

$G_TMP_TABLE->SetSource( $stQry, "" );
$G_TMP_TABLE->WhereClause = "";

$G_TMP_TABLE->AddRawColumn( "text",   "UID",             "center",  35, $G_TMP_TARGET,  "&UID" );
$G_TMP_TABLE->AddRawColumn( $canEdit, "APP_CODE",        "left",   110, "appEdit",      "&UID" );
$G_TMP_TABLE->AddRawColumn( "text",   "APP_DESCRIPTION", "left",   270 );
$G_TMP_TABLE->AddRawColumn( "link",   "ROL_UID",         "center",  80, "loadRoleView", "&UID" );
$G_TMP_TABLE->AddRawColumn( "link",   "PRM_UID",         "center",  90, "loadPermView", "&UID" );

$G_TMP_TABLE->Labels = $Labels;


?>