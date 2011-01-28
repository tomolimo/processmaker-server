<?php
/**
 * users_Ajax.php
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
try {
  global $RBAC;
  switch ($RBAC->userCanAccess('PM_LOGIN'))
  {
    case -2:
      G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
      G::header('location: ../login/login');
      die;
      break;
    case -1:
      G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
      G::header('location: ../login/login');
      die;
      break;
  }
  G::LoadInclude('ajax');
  if (isset($_POST['form']))
  {
    $_POST = $_POST['form'];
  }
  if(isset($_REQUEST['function'])){
    //$value= $_POST['function'];
    $value = get_ajax_value('function');
  }else{
      //$value= $_POST['functions'];
      $value = get_ajax_value('functions');
  }
      switch ($value){
        case 'verifyUsername':
          //print_r($_POST); die;
          $_POST['sOriginalUsername'] = get_ajax_value('sOriginalUsername');
          $_POST['sUsername']         = get_ajax_value('sUsername');
          if ($_POST['sOriginalUsername'] == $_POST['sUsername'])
          {
            echo '0';
          }
          else
          {
            require_once 'classes/model/Users.php';
            G::LoadClass('Users');
            $oUser = new Users();
            $oCriteria=$oUser->loadByUsername($_POST['sUsername']);
            $oDataset = UsersPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            $aRow = $oDataset->getRow();
            //print_r($aRow); die;
            //if (!$aRow)
            if (!is_array($aRow))
            {
              echo '0';
            }
            else
            {
              echo '1';
            }
          }
          break;
        case 'availableUsers':
          G::LoadClass('processMap');
          $oProcessMap = new ProcessMap();
          global $G_PUBLISH;
          $G_PUBLISH = new Publisher();
          $G_PUBLISH->AddContent('propeltable', 'paged-table', 'users/users_AvailableUsers', $oProcessMap->getAvailableUsersCriteria($_GET['sTask'], $_GET['iType']));
          G::RenderPage('publish', 'raw');
          break;
        case 'assign':
          G::LoadClass('tasks');
          $oTasks = new Tasks();
          switch ((int)$_POST['TU_RELATION']) {
            case 1:
              echo $oTasks->assignUser($_POST['TAS_UID'], $_POST['USR_UID'], $_POST['TU_TYPE']);
              break;
            case 2:
              echo $oTasks->assignGroup($_POST['TAS_UID'], $_POST['USR_UID'], $_POST['TU_TYPE']);
              break;
          }
          break;
        case 'ofToAssign':
          G::LoadClass('tasks');
          $oTasks = new Tasks();
          switch ((int)$_POST['TU_RELATION']) {
            case 1:
              echo $oTasks->ofToAssignUser($_POST['TAS_UID'], $_POST['USR_UID'], $_POST['TU_TYPE']);
              break;
            case 2:
              echo $oTasks->ofToAssignGroup($_POST['TAS_UID'], $_POST['USR_UID'], $_POST['TU_TYPE']);
              break;
          }
          break;
        case 'changeView':
          $_SESSION['iType'] = $_POST['TU_TYPE'];
          break;

        case 'deleteGroup':
          G::LoadClass('groups');
          $oGroup = new Groups();
          $oGroup->removeUserOfGroup($_POST['GRP_UID'], $_POST['USR_UID']);
          $_GET['sUserUID'] = $_POST['USR_UID'];
          $G_PUBLISH = new Publisher;
          $G_PUBLISH->AddContent('view', 'users/users_Tree' );
          G::RenderPage('publish', 'raw');
          break;

        case 'showUserGroupInterface':
          $_GET['sUserUID'] = $_POST['sUserUID'];
          $G_PUBLISH = new Publisher;
          $G_PUBLISH->AddContent('view', 'users/users_AssignGroup' );
          G::RenderPage('publish', 'raw');
          break;

        case 'showUserGroups':
          $_GET['sUserUID'] = $_POST['sUserUID'];
          $G_PUBLISH = new Publisher;
          $G_PUBLISH->AddContent('view', 'users/users_Tree' );
          G::RenderPage('publish', 'raw');
          break;

        case 'assignUserToGroup':
          G::LoadClass('groups');
          $oGroup = new Groups();
          $oGroup->addUserToGroup($_POST['GRP_UID'], $_POST['USR_UID']);
          echo '<div align="center"><h2><font color="blue">'.G::LoadTranslation('ID_MSG_ASSIGN_DONE').'</font></h2></div>';
          break;

        case 'usersGroup':
          G::LoadClass('groups');
          $oGroup = new Groups();
          $aGroup = $oGroup->getUsersOfGroup($_POST['GRP_UID']);
          foreach ($aGroup as $iIndex => $aValues) {
            echo $aValues['USR_FIRSTNAME'] . ' ' . $aValues['USR_LASTNAME'] . '<br>';
          }
          break;
        case 'canDeleteUser':
        	G::LoadClass('case');
        	$oProcessMap = new Cases();
    		$USR_UID = $_POST['uUID'];
	       	$total = 0;
	       	$history = 0;
	       	
  			$c = $oProcessMap->getCriteriaUsersCases('TO_DO', $USR_UID);  
  			$total += ApplicationPeer::doCount($c);
            $c = $oProcessMap->getCriteriaUsersCases('DRAFT', $USR_UID);
  			$total += ApplicationPeer::doCount($c);
  			
  			$c = $oProcessMap->getCriteriaUsersCases('COMPLETED', $USR_UID);
  			$history += ApplicationPeer::doCount($c);
  			$c = $oProcessMap->getCriteriaUsersCases('CANCELLED', $USR_UID);
  			$history += ApplicationPeer::doCount($c);
  			
  			$response = '{success: true, candelete: ';
  			$response .= ($total > 0) ? 'false' : 'true';
  			$response .= ', hashistory: ';
  			$response .= ($history > 0) ? 'true' : 'false';
  			$response .= '}';
  			echo $response;
  			break;
        case 'deleteUser':
        	$UID = $_POST['USR_UID'];
        	G::LoadClass('tasks');
  			$oTasks = new Tasks();
  			$oTasks->ofToAssignUserOfAllTasks($UID);
  			G::LoadClass('groups');
  			$oGroups = new Groups();
  			$oGroups->removeUserOfAllGroups($UID);
  			$RBAC->changeUserStatus($UID, 'CLOSED');
  			$_GET['USR_USERNAME']='';
  			$RBAC->updateUser(array('USR_UID' => $UID, 'USR_USERNAME' => $_GET['USR_USERNAME']),'');

  			require_once 'classes/model/Users.php';
  			$oUser                 = new Users();
  			$aFields               = $oUser->load($UID);
  			$aFields['USR_STATUS'] = 'CLOSED';
  			$aFields['USR_USERNAME'] = '';
  			$oUser->update($aFields);
  			break;
        case 'availableGroups':
        	G::LoadClass('groups');
        	$filter = (isset($_POST['textFilter']))? $_POST['textFilter'] : '';
        	$groups = new Groups();
        	$criteria = $groups->getAvailableGroupsCriteria($_REQUEST['uUID'],$filter);
      		$objects  = GroupwfPeer::doSelectRS($criteria);
      		$objects->setFetchmode ( ResultSet::FETCHMODE_ASSOC );
      		$arr = Array();
      		while ($objects->next()){
      			$arr[] = $objects->getRow();
      		}
      		echo '{groups: '.G::json_encode($arr).'}';
        	break;
         case 'assignedGroups':
        	G::LoadClass('groups');
        	$filter = (isset($_POST['textFilter']))? $_POST['textFilter'] : '';
        	$groups = new Groups();
        	$criteria = $groups->getAssignedGroupsCriteria($_REQUEST['uUID'],$filter);
      		$objects  = GroupwfPeer::doSelectRS($criteria);
      		$objects->setFetchmode ( ResultSet::FETCHMODE_ASSOC );
      		$arr = Array();
      		while ($objects->next()){
      			$arr[] = $objects->getRow();
      		}
      		echo '{groups: '.G::json_encode($arr).'}';
        	break;
         case 'assignGroupsToUserMultiple':
         	$USR_UID = $_POST['USR_UID'];
         	$gUIDs = explode(',',$_POST['GRP_UID']);
         	G::LoadClass('groups');
            $oGroup = new Groups();
         	foreach ($gUIDs as $GRP_UID){
               $oGroup->addUserToGroup($GRP_UID, $USR_UID);		
         	}
         	break;
         case 'deleteGroupsToUserMultiple':
         	$USR_UID = $_POST['USR_UID'];
         	$gUIDs = explode(',',$_POST['GRP_UID']);
         	G::LoadClass('groups');
            $oGroup = new Groups();
         	foreach ($gUIDs as $GRP_UID){
               $oGroup->removeUserOfGroup($GRP_UID, $USR_UID);		
         	}
         	break;
         case 'authSources':
         	$criteria = $RBAC->getAllAuthSources();
         	$objects  = AuthenticationSourcePeer::doSelectRS($criteria);
      		$objects->setFetchmode ( ResultSet::FETCHMODE_ASSOC );
      		
      		$started = Array();
      		$started['AUTH_SOURCE_UID'] = '00000000000000000000000000000000';
      		$started['AUTH_SOURCE_NAME'] = 'ProcessMaker';
      		$started['AUTH_SOURCE_TYPE'] = 'MYSQL';
      		$arr = Array();
      		$arr[] = $started;
      		while ($objects->next()){
      			$arr[] = $objects->getRow();
      		}
      		echo '{sources: '.G::json_encode($arr).'}';
      		break;
         case 'loadAuthSourceByUID':
         	require_once 'classes/model/Users.php';
            $oCriteria=$RBAC->load($_POST['uUID']);
            $UID_AUTH = $oCriteria['UID_AUTH_SOURCE'];
            if (($UID_AUTH!='00000000000000000000000000000000')&&($UID_AUTH!='')){
              $aFields = $RBAC->getAuthSource($UID_AUTH);
            }else{
              $arr = Array();
              $arr['AUTH_SOURCE_NAME'] = 'ProcessMaker';
              $arr['AUTH_SOURCE_PROVIDER'] = 'MYSQL';
              $aFields = $arr;	
            }
            $res = Array();
            $res['data'] = $oCriteria;
            $res['auth'] = $aFields;
            echo G::json_encode($res);
            break;
         case 'updateAuthServices':
         	$aData = $RBAC->load($_POST['usr_uid']);
         	unset($aData['USR_ROLE']);
         	$auth_uid = $_POST['auth_source'];
         	$auth_uid2 =  $_POST['auth_source_uid'];
         	if ($auth_uid == $auth_uid2){
         		$auth_uid = $aData['UID_AUTH_SOURCE'];
         	}
         	if (($auth_uid=='00000000000000000000000000000000')||($auth_uid=='')){
         		$aData['USR_AUTH_TYPE']   = 'MYSQL';
  				$aData['UID_AUTH_SOURCE'] = '';
         	}else{
         		$aFields = $RBAC->getAuthSource($auth_uid);
  				$aData['USR_AUTH_TYPE']   = $aFields['AUTH_SOURCE_PROVIDER'];
  				$aData['UID_AUTH_SOURCE'] = $auth_uid;
         	}
         	if (isset($_POST['auth_dn'])){ 
         		$auth_dn = $_POST['auth_dn'];
         	}else{
         		$auth_dn = "";
         	}
      		$aData['USR_AUTH_USER_DN'] = $auth_dn;
      		$RBAC->updateUser($aData);
      		echo '{success: true}';
         	break;
      }
}
catch (Exception $oException) {
  die($oException->getMessage());
}
?>