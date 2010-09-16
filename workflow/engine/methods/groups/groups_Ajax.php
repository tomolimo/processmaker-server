<?php
/**
 * groups_Ajax.php
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
if (($RBAC_Response=$RBAC->userCanAccess("PM_USERS"))!=1) return $RBAC_Response;
G::LoadInclude('ajax');
$_POST['action'] = get_ajax_value('action');

switch ($_POST['action'])
{
	case 'showUsers':
	  G::LoadClass('groups');
	  $oGroups = new Groups();
	  $oGroup  = new Groupwf();
	  $aFields = $oGroup->load($_POST['sGroupUID']);
	  global $G_PUBLISH;
  	$G_PUBLISH = new Publisher();
  	//$G_PUBLISH->AddContent('xmlform', 'xmlform', 'groups/groups_UsersListTitle', '', array('GRP_NAME' => $aFields['GRP_TITLE']));
  	$G_PUBLISH->AddContent('propeltable', 'groups/paged-table2', 'groups/groups_UsersList', $oGroups->getUsersGroupCriteria($_POST['sGroupUID']), array('GRP_UID' => $_POST['sGroupUID'], 'GRP_NAME' => $aFields['GRP_TITLE']));
    
    $oHeadPublisher =& headPublisher::getSingleton();
    $oHeadPublisher->addScriptCode("groupname=\"{$aFields["GRP_TITLE"]}\";");
    
    G::RenderPage('publish', 'raw');
	break;

	case 'assignUser':
	  G::LoadClass('groups');
	  $oGroup = new Groups();
	  $oGroup->addUserToGroup($_POST['GRP_UID'], $_POST['USR_UID']);
	break;

	case 'assignAllUsers':
	  G::LoadClass('groups');
	  $oGroup = new Groups();
	  $aUsers=explode(',', $_POST['aUsers']);
	  
	  for($i=0; $i<count($aUsers); $i++)
	  {
	  	$oGroup->addUserToGroup($_POST['GRP_UID'], $aUsers[$i]);
	  }
	break;

	case 'ofToAssignUser':
	  G::LoadClass('groups');
	  $oGroup = new Groups();
	  $oGroup->removeUserOfGroup($_POST['GRP_UID'], $_POST['USR_UID']);
	break;

	case 'verifyGroupname':
  	  $_POST['sOriginalGroupname'] = get_ajax_value('sOriginalGroupname');
  	  $_POST['sGroupname']         = get_ajax_value('sGroupname');
  	  if ($_POST['sOriginalGroupname'] == $_POST['sGroupname'])
  	  {
  	    echo '0';
  	  }
  	  else
  	  {
  	  	require_once 'classes/model/Groupwf.php';
  	  	G::LoadClass('Groupswf');
	      $oGroup = new Groupwf();
	      $oCriteria=$oGroup->loadByGroupname($_POST['sGroupname']);
  	  	$oDataset = GroupwfPeer::doSelectRS($oCriteria);
        $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $oDataset->next();
        $aRow = $oDataset->getRow();
        if (!$aRow)
  	  	{
  	  		echo '0';
  	  	}
  	  	else
  	  	{
  	  		echo '1';
  	  	}
  	  }
  	break;
}
