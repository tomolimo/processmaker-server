<?php
/**
 * appNew2.php
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
header( "location: appList.html" );die;
/*Falta revisar la clase RBAC_Application*/
$frm = $_POST['form'];

$code        = strtoupper ( $frm['APP_CODE']);
$description = $frm['APP_DESCRIPTION'];
$dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );

//crear nueva aplicacion
G::LoadClassRBAC ('applications');
$obj = new RBAC_Application;
$obj->SetTo( $dbc );
$res = $obj->applicationCodeRepetido ( $code );

if ($res != 0 ) {
  G::SendMessage ( 15, "error");
  header ("location: appNew.php");
  die;
}

$appid = $obj->createApplication ($code, $description );
$_SESSION['CURRENT_APPLICATION'] = $appid;

header( "location: appList.html" );
?>