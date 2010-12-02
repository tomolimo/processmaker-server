<?php
/**
 * rbac.authentication.list.php
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
global $HTTP_SESSION_VARS;

$stQry = "SELECT *  " .
         "FROM AUTHENTICATION_SOURCES ORDER BY AUT_UID";

$G_TMP_TABLE->SetSource( $stQry, "" );
$G_TMP_TABLE->WhereClause = "";

$G_TMP_TABLE->AddColumn( G::LoadMessageXml ("ID_UID"),        "link", "AUT_UID",         "center",  35, 'loadAuthSource',  "&AUT_UID" );
$G_TMP_TABLE->AddColumn( G::LoadMessageXml ("ID_NAME"),       "link", "AUT_NAME",        "left",   200, 'loadAuthSource',  "&AUT_UID" );
$G_TMP_TABLE->AddColumn( G::LoadMessageXml ("ID_SERVER_NAME"),"text", "AUT_SERVER_NAME", "left",   100 );
$G_TMP_TABLE->AddColumn( G::LoadMessageXml ("ID_PROVIDER"),   "text", "AUT_PROVIDER",    "center",  90 );
$G_TMP_TABLE->AddRawColumn( "image", "/images/trash.gif","center",  90, "authDel", "&AUT_UID" );
//$G_TMP_TABLE->AddColumn( G::LoadMessageXml ("ID_UID"),        "link", "PRM_UID",         "center",  90 , "loadPermView", "&UID" );

?>