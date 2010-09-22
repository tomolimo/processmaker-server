<?php
/**
 * users_List.php
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
if (($RBAC_Response=$RBAC->userCanAccess("PM_LOGIN"))!=1) return $RBAC_Response;
global $RBAC;

$access = $RBAC->userCanAccess('PM_USERS');
if( $access != 1 ){
  switch ($access)
  {
  	case -1:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	case -2:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	default:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;  	
  }
}
$G_MAIN_MENU            = 'processmaker';
$G_SUB_MENU             = 'users';
$G_ID_MENU_SELECTED     = 'USERS';
$G_ID_SUB_MENU_SELECTED = 'USERS';

$sDelimiter = DBAdapter::getStringDelimiter();
require_once 'classes/model/Users.php';
$oCriteria = new Criteria('workflow');
$oCriteria->addSelectColumn(UsersPeer::USR_UID);

$sDataBase = 'database_' . strtolower(DB_ADAPTER);
if(G::LoadSystemExist($sDataBase)){
  G::LoadSystem($sDataBase);
  $oDataBase = new database();
  $oCriteria->addAsColumn('USR_COMPLETENAME', $oDataBase->concatString("USR_LASTNAME", "' '", "USR_FIRSTNAME"));
}

$oCriteria->addSelectColumn(UsersPeer::USR_USERNAME);
$oCriteria->addSelectColumn(UsersPeer::USR_EMAIL);
$oCriteria->addSelectColumn(UsersPeer::USR_ROLE);
$oCriteria->addSelectColumn(UsersPeer::USR_DUE_DATE);
$oCriteria->addAsColumn('USR_VIEW', $sDelimiter . G::LoadTranslation('ID_DETAIL') . $sDelimiter);
$oCriteria->addAsColumn('USR_EDIT', $sDelimiter . G::LoadTranslation('ID_EDIT') . $sDelimiter);
$oCriteria->addAsColumn('USR_DELETE', $sDelimiter . G::LoadTranslation('ID_DELETE') . $sDelimiter);
$oCriteria->addAsColumn('USR_AUTH', $sDelimiter . G::LoadTranslation('ID_AUTHENTICATION') . $sDelimiter);
$oCriteria->addAsColumn('USR_REASSIGN', $sDelimiter . G::LoadTranslation('ID_REASSIGN_CASES') . $sDelimiter);
$oCriteria->add(UsersPeer::USR_STATUS, array('CLOSED'), Criteria::NOT_IN);

$G_PUBLISH = new Publisher;
$G_PUBLISH->AddContent('propeltable', 'paged-table', 'users/users_List', $oCriteria, array('CONFIRM' => G::LoadTranslation('ID_MSG_CONFIRM_DELETE_USER')));
G::RenderPage('publish');
