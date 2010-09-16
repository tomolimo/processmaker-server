<?php
/**
 * upgrade.php
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

 
$REQUEST = (isset($_GET['request']))?$_GET['request']:$_POST['request'];
 
switch ($REQUEST) {

    case 'newRole':
        $G_PUBLISH = new Publisher();
        $G_PUBLISH->AddContent('xmlform', 'xmlform', 'roles/roles_New', '', '');
        G::RenderPage('publish', 'raw');
        break;

    case 'saveNewRole':
    	
    	$newid = md5($_POST['code'].date("d-M-Y_H:i:s"));
    	g::pr($_POST);
    	$aData['ROL_UID'] = $newid;
    	//$aData['ROL_PARENT'] = $_POST['parent'];
    	$aData['ROL_SYSTEM'] = '00000000000000000000000000000002';
    	$aData['ROL_CODE'] = $_POST['code'];
    	$aData['ROL_NAME'] = $_POST['name'];
    	$aData['ROL_CREATE_DATE'] = date("Y-M-d H:i:s");
    	$aData['ROL_UPDATE_DATE'] = date("Y-M-d H:i:s");
    	$aData['ROL_STATUS'] = $_POST['status'];
    	$oCriteria = $RBAC->createRole($aData);
        break;
        
    case 'editRole':
    	
    	$ROL_UID = $_GET['ROL_UID'];
    	$aFields = $RBAC->loadById($ROL_UID);
    	
    	$G_PUBLISH = new Publisher();
        $G_PUBLISH->AddContent('xmlform', 'xmlform', 'roles/roles_Edit', '', $aFields);
        G::RenderPage('publish', 'raw');
        break;
		    
    case 'updateRole':
    	
    	$aData['ROL_UID'] = $_POST['rol_uid'];
    	//$aData['ROL_PARENT'] = $_POST['parent'];
    	$aData['ROL_CODE'] = $_POST['code'];
    	$aData['ROL_NAME'] = $_POST['name'];
    	$aData['ROL_UPDATE_DATE'] = date("Y-M-d H:i:s");
    	$aData['ROL_STATUS'] = $_POST['status'];
    	$oCriteria = $RBAC->updateRole($aData);
        break;

	case 'show':
	    G::LoadClass('ArrayPeer');
		$aRoles = $RBAC->getAllRoles();
		       
        $fields = Array(        
        	'ROL_UID'=>'char', 
        	'ROL_PARENT'=>'char', 
        	'ROL_SYSTEM'=>'char', 
        	'ROL_CREATE_DATE'=>'char', 
        	'ROL_UPDATE_DATE'=>'char',
        	'ROL_STATUS'=>'char'
        );
        
        $rows = array_merge(Array($fields), $aRoles);
        
        global $_DBArray;
        $_DBArray['virtual_roles'] = $rows;
        $oCriteria = new Criteria('dbarray');
        $oCriteria->setDBArrayTable('virtual_roles');
    
		$G_PUBLISH = new Publisher;
		$G_PUBLISH->AddContent('propeltable', 'paged-table', 'roles/roles_List', $oCriteria);
		G::RenderPage('publish', 'raw');
	break;
	
	case 'deleteRole':
		$oCriteria = $RBAC->removeRole($_POST['ROL_UID']);
		break;
		
	case 'canDeleteRole':
		
		if($RBAC->numUsersWithRole($_POST['ROL_UID']) == 0){
			echo 'true';
		} else {
			echo 'false';
		}
		
		break;

    case 'verifyNewRole':
		$response = ($RBAC->verifyNewRole($_POST['code']))?'true':'false';
		print($response);
        break;

    case 'usersIntoRole':

		$_GET['ROL_UID'] = (isset($_GET['ROL_UID']))?$_GET['ROL_UID']:$_POST['ROL_UID'];
		$G_PUBLISH = new Publisher;
		$G_PUBLISH->AddContent('view', 'roles/roles_Tree' );
		G::RenderPage('publish', 'raw');
        break;
        
        
     
    case 'deleteUserRole':
    	$USR_UID = $_POST['USR_UID'];
    	$ROL_UID = $_POST['ROL_UID'];
    	$RBAC->deleteUserRole($ROL_UID, $USR_UID);
    	
    	$_GET['ROL_UID'] = $ROL_UID;
		$G_PUBLISH = new Publisher;
		$G_PUBLISH->AddContent('view', 'roles/roles_Tree' );
		G::RenderPage('publish', 'raw');
    	break;
		   
	case 'showUsers':
	    $ROL_UID = $_POST['ROL_UID'];
		$_GET['ROL_UID'] = $ROL_UID;
		$G_PUBLISH = new Publisher;
		$G_PUBLISH->AddContent('view', 'roles/roles_AssignRole' );
		G::RenderPage('publish', 'raw');
		break;	
		
	case 'showPermissions':
	    $ROL_UID = $_POST['ROL_UID'];
		$_GET['ROL_UID'] = $ROL_UID;
		$G_PUBLISH = new Publisher;
		$G_PUBLISH->AddContent('view', 'roles/roles_AssignPermissions' );
		G::RenderPage('publish', 'raw');
		break;   
    
    case 'assignUserToRole':
    	$USR_UID = $_POST['USR_UID']; 
    	$ROL_UID = $_POST['ROL_UID']; 
    	$sData['USR_UID'] = $USR_UID;
    	$sData['ROL_UID'] = $ROL_UID;
    	$RBAC->assignUserToRole($sData);
    	
    	$_GET['ROL_UID'] = $ROL_UID;
		$G_PUBLISH = new Publisher;
		$G_PUBLISH->AddContent('view', 'roles/roles_Tree' );
		G::RenderPage('publish', 'raw');
    	break;
    	
     case 'assignPermissionToRole':
    	$USR_UID = $_POST['PER_UID']; 
    	$ROL_UID = $_POST['ROL_UID']; 
    	$sData['PER_UID'] = $USR_UID;
    	$sData['ROL_UID'] = $ROL_UID;
    	$RBAC->assignPermissionRole($sData);
    	
    	$_GET['ROL_UID'] = $ROL_UID;
		$G_PUBLISH = new Publisher;
		$G_PUBLISH->AddContent('view', 'roles/roles_permissionsTree' );
		G::RenderPage('publish', 'raw');
    	break;
    
	case 'viewPermitions':
		
		$_GET['ROL_UID'] = (isset($_GET['ROL_UID']))?$_GET['ROL_UID']:$_POST['ROL_UID'];
		$G_PUBLISH = new Publisher;
		$G_PUBLISH->AddContent('view', 'roles/roles_permissionsTree' );
		G::RenderPage('publish', 'raw');
	break;	
	
	case 'deletePermissionRole':
    	$PER_UID = $_POST['PER_UID'];
    	$ROL_UID = $_POST['ROL_UID'];
    	$RBAC->deletePermissionRole($ROL_UID, $PER_UID);
    	
    	$_GET['ROL_UID'] = $ROL_UID;
		$G_PUBLISH = new Publisher;
		$G_PUBLISH->AddContent('view', 'roles/roles_permissionsTree');
		G::RenderPage('publish', 'raw');
    	break;
	         
	default: echo 'default';
}






















