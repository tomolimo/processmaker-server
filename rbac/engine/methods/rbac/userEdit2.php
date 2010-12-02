<?php
/**
 * userEdit2.php
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
  $frm   = $_POST['form'];
  $first = strtoupper ($frm['USR_LASTNAME']);
  $mid   = strtoupper ($frm['USR_MIDNAME']);
  $names = strtoupper ($frm['USR_FIRSTNAME']);
  if (!isset($frm['USR_EMAIL']))
  {
    $frm['USR_EMAIL'] = '';
  }
  if (!isset($frm['USR_PHONE']))
  {
    $frm['USR_PHONE'] = '';
  }
  if (!isset($frm['USR_CELLULAR']))
  {
    $frm['USR_CELLULAR'] = '';
  }
  if (!isset($frm['USR_FAX']))
  {
    $frm['USR_FAX'] = '';
  }
  if (!isset($frm['USR_POBOX']))
  {
    $frm['USR_POBOX'] = '';
  }
  if (!isset($frm['USR_USERNAME']))
  {
    $frm['USR_USERNAME'] = '';
  }
  if (!isset($frm['USR_STATUS']))
  {
    $frm['USR_STATUS'] = '';
  }
  if (!isset($frm['USR_DUE_DATE']))
  {
    $frm['USR_DUE_DATE'] = '';
  }
  if (!isset($frm['USR_USE_LDAP']))
  {
    $frm['USR_USE_LDAP'] = '';
  }
  $email   = $frm['USR_EMAIL'];
  $phone   = $frm['USR_PHONE'];
  $cell    = $frm['USR_CELLULAR'];
  $fax     = $frm['USR_FAX'];
  $pobox   = $frm['USR_POBOX'];
  $userID  = $frm['USR_USERNAME'];
  $status  = $frm['USR_STATUS'];
  $due     = $frm['USR_DUE_DATE'];
  $useLdap = $frm['USR_USE_LDAP'];
  $uid     = $_SESSION['CURRENT_USER'];
  $dbc     = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME);
  
  G::LoadClassRBAC ('user');
  $obj = new RBAC_User;
  $obj->SetTo($dbc);
  $repId = $obj->UserNameRepetido($uid, $userID);
  if ($repId != 0 )
  {
    G::SendMessage (6, 'error');
    header('location: userEdit.php');
    die;
  }
  
  $obj->SetTo($dbc);
  $obj->SetToRBAC(DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME);
  $uid = $obj->editUser($uid, $first, $mid, $names, $email, '', '', '', '', $userID, $status, $due, '', '', '');
  $obj->Load( $uid );
  if ( $obj->Fields['USR_USE_LDAP'] != $useLdap )
  {
  	$obj->updateLDAP( $uid, $obj->Fields['USR_LDAP_SOURCE'] , $obj->Fields['USR_LDAP_DN'] , $useLdap );
  }
  header('location: userEdit.html');
  //header('location: userViewRole.html');
?>