<?php
/**
 * groups.php
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

if (($RBAC_Response=$RBAC->userCanAccess("PM_USERS"))!=1) return $RBAC_Response;

  $G_MAIN_MENU            = 'processmaker';
  $G_SUB_MENU             = 'users';
  $G_ID_MENU_SELECTED     = 'USERS';
  $G_ID_SUB_MENU_SELECTED = 'GROUPS';
  
  
$G_PUBLISH = new Publisher;

$oHeadPublisher =& headPublisher::getSingleton();

//$oHeadPublisher->usingExtJs('ux/Ext.ux.fileUploadField');
$oHeadPublisher->addExtJsScript('groups/groupsList', false);    //adding a javascript file .js
$oHeadPublisher->addContent('groups/groupsList'); //adding a html file  .html.

$labels = G::getTranslations(Array('ID_GROUPS','ID_EDIT','ID_DELETE','ID_NEW','ID_SEARCH','ID_ENTER_SEARCH_TERM','ID_GROUP_NAME','ID_SAVE','ID_CLOSE',
  'ID_STATUS','ID_SELECT_STATUS','ID_MEMBERS','ID_MSG_GROUP_NAME_EXISTS','ID_GROUPS_SUCCESS_NEW','ID_GROUPS_SUCCESS_UPDATE',
  'ID_MSG_CONFIRM_DELETE_GROUP','ID_GROUPS_SUCCESS_DELETE','ID_CREATE_GROUP_TITLE','ID_EDIT_GROUP_TITLE'));

$oHeadPublisher->assign('TRANSLATIONS', $labels);
G::RenderPage('publish', 'extJs');
  
//  
//
//  $dbc = new DBConnection();
//  $ses = new DBSession($dbc);
//
//  $Fields['WHERE'] = '';
//
//  $G_PUBLISH = new Publisher;
//  $oHeadPublisher =& headPublisher::getSingleton();
//  $oHeadPublisher->addScriptFile('/jscore/groups/groups.js');
//  
//  $G_PUBLISH->AddContent('view', 'groups/groups_Tree' );
//  $G_PUBLISH->AddContent('smarty', 'groups/groups_usersList', '', '', array());
//
//  G::RenderPage( "publish-treeview",'blank' );
//
//  $groups_Edit = G::encryptlink('groups_Edit');
//  $groups_Delete = G::encryptlink('groups_Delete');
//  $groups_List = G::encryptlink('groups_List');
//  $groups_AddUser = G::encryptlink('groups_AddUser');
?>
