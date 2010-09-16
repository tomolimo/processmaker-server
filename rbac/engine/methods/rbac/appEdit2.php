<?php
/**
 * appEdit2.php
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

$frm = $HTTP_POST_VARS['form'];
$frm = G::PrepareFormArray( $frm );

$dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );

$appid   = $HTTP_SESSION_VARS['CURRENT_APPLICATION'];
$code    = strtoupper( $frm['APP_CODE']);
$descrip = $frm['APP_DESCRIPTION'];

//crear nueva applicacion
G::LoadClassRBAC ( "applications");
$obj = new RBAC_Application;
$obj->SetTo( $dbc );

print "xx $res";
$res = $obj->applicationCodeRepetido ( $code );
if ($res != 0 && $res != $appid ) {
  G::SendMessage ( 15, "error");
  header ("location: appList.php");
  die;
}
print "xx $res";
$uid = $obj->editApplication( $appid, $code , $descrip );
header( "location: appList.html" );
?>
