<?php
/**
 * userAssignRole2.php
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
$frm = $_POST['form'];
$rolid = $frm['USR_ROLE'];
$dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );
G::LoadClassRBAC('user');
$obj = new RBAC_User;
$obj->SetTo($dbc);
$obj->assignUserRole($_SESSION['CURRENT_USER'], $rolid);
//header('location: userViewRole.html');
?>
<script language='Javascript'>
  //parent.myPanel.remove();
  parent.window.location = 'userEdit.html';  
</script>