<?php
/**
 * verify-login.php
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
if (defined('SYS_SYS')) $_SESSION['ENVIRONMENT']= SYS_SYS;
else $_SESSION['ENVIRONMENT']= 'vacio';

$frm = $_POST['form'];
$usr = strtolower(trim($frm['USER_NAME']));
$pwd = trim($frm['USER_PASS']);

$dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );

$_SESSION['USER_LOGGED']   = 0;
$_SESSION['VALID_SESSION'] = session_id();
$_SESSION['USER']          = $usr;

$res = $RBAC->VerifyLogin($usr, $pwd);

switch ($res)
{
  case -1://don't exist
    G::SendMessageXml('ID_USER_NOT_REGISTERED', 'warning');
    break;
  case -2://password incorrect
    G::SendMessageXml('ID_WRONG_PASS', 'warning');
    break;
  case -3: //inactive
  case -4: //due
    G::SendMessageXml('ID_USER_INACTIVE', 'warning');
    break;
}
if ($res < 0 )
{
  header('location: login.html');
  die;
}

$uid = $res;
$_SESSION['USER_LOGGED'] = $uid;
$res = $RBAC->userCanAccess('RBAC_LOGIN');
if ($res != 1 )
{
  G::SendMessageXml('ID_USER_HAVENT_RIGHTS_PAGE', 'error');
  header('location: login.html');
  die;
}

$_SESSION['USER_NAME'] = $usr;

$file = PATH_RBAC . PATH_SEP . 'class.authentication.php';
require_once($file);
$obj = new authenticationSource;
$obj->SetTo($dbc);
$res = $obj->verifyStructures();

if ($RBAC->userCanAccess("RBAC_READONLY") == 1)
{
  header('location: ../rbac/userList');
}
else
{
  header('location: ../rbac/appList');
}
?>