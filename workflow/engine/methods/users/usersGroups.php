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

$G_PUBLISH = new Publisher;

$oHeadPublisher =& headPublisher::getSingleton();

//$oHeadPublisher->usingExtJs('ux/Ext.ux.fileUploadField');
$oHeadPublisher->addExtJsScript('users/usersGroups', false);    //adding a javascript file .js
$oHeadPublisher->addContent('users/usersGroups'); //adding a html file  .html.

$labels = G::getTranslations(Array('ID_USERS','ID_ASSIGN','ID_ASSIGN_ALL_GROUPS','ID_REMOVE','ID_REMOVE_ALL_GROUPS',
					'ID_BACK','ID_GROUP_NAME','ID_AVAILABLE_GROUPS','ID_ASSIGNED_GROUPS','ID_GROUPS','ID_USERS',
					'ID_MSG_AJAX_FAILURE','ID_PROCESSING','ID_AUTHENTICATION','ID_CLOSE','ID_SAVE','ID_AUTHENTICATION_SOURCE',
					'ID_AUTHENTICATION_DN','ID_AUTHENTICATION_FORM_TITLE','ID_SELECT_AUTH_SOURCE','ID_SAVE_CHANGES','ID_DISCARD_CHANGES'));

require_once 'classes/model/Users.php';

$oCriteria = new Criteria();
$oCriteria->addSelectColumn(UsersPeer::USR_FIRSTNAME);
$oCriteria->addSelectColumn(UsersPeer::USR_LASTNAME);
$oCriteria->addSelectColumn(UsersPeer::USR_USERNAME);
$oCriteria->add(UsersPeer::USR_UID, $_GET['uUID']);
$oDataset = UsersPeer::doSelectRS($oCriteria);
$oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
$oDataset->next();
$aRow = $oDataset->getRow();

$users = Array();
$users['USR_UID'] = $_GET['uUID'];
$users['USR_COMPLETENAME'] = $aRow['USR_LASTNAME'].' '.$aRow['USR_FIRSTNAME'];
$users['USR_USERNAME'] = $aRow['USR_USERNAME'];
$users['CURRENT_TAB'] = ($_REQUEST['type']=='group') ? 0 : 1;


$oHeadPublisher->assign('TRANSLATIONS', $labels);
$oHeadPublisher->assign('USERS', $users);
G::RenderPage('publish', 'extJs');