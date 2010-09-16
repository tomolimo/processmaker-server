<?php
/**
 * departments_Ajax.php
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

require_once 'classes/model/Department.php';

switch ($_POST['action'])
{
	case 'showUsers':
	  global $G_PUBLISH;
	  $oDept  = new Department();
	  $aFields = $oDept->load($_POST['sDptoUID']);
  	$G_PUBLISH = new Publisher();
  	$G_PUBLISH->AddContent('xmlform', 'xmlform', 'departments/departments_Edit', '', $aFields , 'departments_Save');

    $criteria = $oDept->getUsersFromDepartment( $_POST['sDptoUID'], $aFields ['DEP_MANAGER'] );
    
  	$G_PUBLISH->AddContent('propeltable', 'departments/paged-table2', 'departments/departments_UsersList', $criteria, $aFields );
  	//$G_PUBLISH->AddContent('propeltable', 'paged-table', 'departments/departments_UsersList', $criteria, $aFields);
    
    $oHeadPublisher =& headPublisher::getSingleton();
    $oHeadPublisher->addScriptCode("groupname='{$aFields["DEPO_TITLE"]}';");
    $oHeadPublisher->addScriptCode("depUid='{$aFields["DEP_UID"]}';");
    
    G::RenderPage('publish', 'raw');
	break;

 	case 'assignAllUsers':
	  $aUsers = explode(',', $_POST['aUsers']);
	  $oDept  = new Department();	  
	  $depUid = $_POST['DEP_UID'];
    $cant = $oDept->cantUsersInDepartment( $depUid);

    if ( $cant == 0 ) $manager = true;

	  for( $i=0; $i<count($aUsers); $i++) { 
  	  $oDept->addUserToDepartment( $depUid, $aUsers[$i], $manager, false );
  	  $manager = false;
	  }
	  $oDept->updateDepartmentManager( $depUid );

	break;

	case 'removeUserFromDepartment':
	  $oDept = new Department();
	  $oDept->removeUserFromDepartment($_POST['DEP_UID'], $_POST['USR_UID']);
	break;
	
	case 'verifyDptoname':
  	  $_POST['sOriginalGroupname'] = get_ajax_value('sOriginalGroupname');
  	  $_POST['sGroupname']         = get_ajax_value('sGroupname');
  	  if ($_POST['sOriginalGroupname'] == $_POST['sGroupname'])
  	  {
  	    echo '0';
  	  }
  	  else
  	  {
	      $oDpto = new Department();
	      $oCriteria=$oDpto->loadByGroupname($_POST['sGroupname']);
  	  	$oDataset = DepartmentPeer::doSelectRS($oCriteria);
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
  
  
  case 'showUnAssignedUsers':
    $_POST['UID'] = get_ajax_value('UID');
    require_once ( 'classes/class.xmlfield_InputPM.php' );
    
    if (($RBAC_Response=$RBAC->userCanAccess("PM_USERS"))!=1) return $RBAC_Response;
    G::LoadClass ( 'departments');
    $oDept  = new Department();
    
    $G_PUBLISH = new Publisher();
    $G_PUBLISH->AddContent('propeltable', 'departments/paged-table3', 'departments/departments_AddUnAssignedUsers', $oDept->getAvailableUsersCriteria(''));
    G::RenderPage('publish', 'raw');
    
    break;
  
}
