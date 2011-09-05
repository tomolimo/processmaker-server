<?php
/**
 * UsersRoles.php
 * @package  rbac-classes-model
 *  
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2011 Colosa Inc.
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

  /**
  * @access public
  */
require_once 'classes/model/om/BaseUsersRoles.php';


/**
 * Skeleton subclass for representing a row from the 'USERS_ROLES' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package  rbac-classes-model
 */
class UsersRoles extends BaseUsersRoles {

   /**
   * Function getRolesBySystem
   * access public
   */
  function getRolesBySystem ( $SysUid, $UsrUid ) {
    $con = Propel::getConnection(UsersRolesPeer::DATABASE_NAME);
    try {
      $c = new Criteria( 'rbac' );
      $c->clearSelectColumns();
      $c->addSelectColumn ( RolesPeer::ROL_UID );
      $c->addSelectColumn ( RolesPeer::ROL_CODE );
      $c->addJoin ( UsersRolesPeer::ROL_UID, RolesPeer::ROL_UID );
      $c->add ( UsersRolesPeer::USR_UID, $UsrUid );
      $c->add ( RolesPeer::ROL_SYSTEM, $SysUid );
      $rs = UsersRolesPeer::doSelectRs( $c );
      $rs->setFetchmode (ResultSet::FETCHMODE_ASSOC);
      $rs->next();
      $row = $rs->getRow();
      /*  return only the first row, no other rows can be permitted
      while ( is_array ( $row ) ) {
        $rows[] = $row;
        $rs->next();
        $row = $rs->getRow();
      }
      */
      return $row;
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }


  function getAllPermissions  ( $sRolUid, $sUsrUid ) {
    $con = Propel::getConnection(RolesPermissionsPeer::DATABASE_NAME);
    try {
      $c = new Criteria( 'rbac' );
//      $c->clearSelectColumns();
      $c->addSelectColumn ( RolesPermissionsPeer::PER_UID );
      $c->addSelectColumn ( PermissionsPeer::PER_CODE );
      $c->addJoin ( RolesPermissionsPeer::PER_UID, PermissionsPeer::PER_UID );
      $c->add ( RolesPermissionsPeer::ROL_UID, $sRolUid);
      $rs = RolesPermissionsPeer::doSelectRs( $c );
      $rs->setFetchmode (ResultSet::FETCHMODE_ASSOC);
      $rs->next();
      $row = $rs->getRow();
      $rows = array();
      while ( is_array ( $row ) ) {
        $rows[] = $row;
        $rs->next();
        $row = $rs->getRow();
      }
      return $rows;
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  function create($sUserUID = '', $sRolUID = '') {
    $oRole  = new UsersRoles();
    $oRole->setUsrUid($sUserUID);
    $oRole->setRolUid($sRolUID);
    $oRole->save();
  }

  function remove($sUserUID = '', $sRolUID = '') {
    $this->setUsrUid($sUserUID);
    $this->setRolUid($sRolUID);
    $this->delete();
  }

} // UsersRoles
