<?php
/**
 * RolesPermissions.php
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
require_once 'classes/model/om/BaseRolesPermissions.php';


/**
 * Skeleton subclass for representing a row from the 'ROLES_PERMISSIONS' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package  rbac-classes-model
 */
class RolesPermissions extends BaseRolesPermissions {
  function create($aData) {
    try {
      $oCriteria = new Criteria('rbac');
      $oCriteria->add(RolesPermissionsPeer::ROL_UID, $aData['ROL_UID']);
      $oCriteria->add(RolesPermissionsPeer::PER_UID, $aData['PER_UID']);
      $oDataset = RolesPermissionsPeer::doSelectRS($oCriteria);
      $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
      $oDataset->next();
      $aRow = $oDataset->getRow();
      if (is_array($aRow)) return true;

      $oRolesPermissions = new RolesPermissions();
      $oRolesPermissions->fromArray($aData, BasePeer::TYPE_FIELDNAME);
      $iResult = $oRolesPermissions->save();
      return true;
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }
} // RolesPermissions
