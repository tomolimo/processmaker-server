<?php
/**
 * rbac.permissions.list.php
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

$lang = SYS_LANG;
$appid = $HTTP_SESSION_VARS['CURRENT_APPLICATION'];

$stQry = "SELECT * " .
         "FROM PERMISSION AS P WHERE PRM_APPLICATION = $appid";

$G_TMP_TABLE->SetSource( $stQry, "" );
$G_TMP_TABLE->WhereClause = "";

$G_TMP_TABLE->AddRawColumn( "link", "UID", "center", 60, $G_TMP_TARGET, "&UID" );
$G_TMP_TABLE->AddRawColumn( "link", "PRM_CODE", "left", 200, $G_TMP_TARGET, "&UID" );
$G_TMP_TABLE->AddRawColumn( "text", "PRM_DESCRIPTION", "left", 200 );

switch( $lang )
{
case 'po':
  $G_TMP_TABLE->Labels = array(
    "ID",
    "Código",
    "Descripcion"
  );
  break;
case 'es':
  $G_TMP_TABLE->Labels = array(
    "ID",
    "Código",
    "Descripción"
  );
  break;
default:
  $G_TMP_TABLE->Labels = array(
    "ID",
    "Code",
    "Description"
  );
  break;
}

?>