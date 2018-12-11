<?php

if (($RBAC_Response = $RBAC->userCanAccess("PM_USERS")) != 1) {
    return $RBAC_Response;
}
$_POST['action'] = get_ajax_value('action');

$groups = new Groups();
$groupWf = new Groupwf();

switch ($_POST['action']) {
    case 'showUsers':
        $fields = $groupWf->load($_POST['sGroupUID']);
        global $G_PUBLISH;
        $G_PUBLISH = new Publisher();
        $G_PUBLISH->AddContent('propeltable', 'groups/paged-table2', 'groups/groups_UsersList', $groups->getUsersGroupCriteria($_POST['sGroupUID']), array('GRP_UID' => $_POST['sGroupUID'], 'GRP_NAME' => $fields['GRP_TITLE']));
        $oHeadPublisher = headPublisher::getSingleton();
        $oHeadPublisher->addScriptCode("groupname=\"{$fields["GRP_TITLE"]}\";");
        G::RenderPage('publish', 'raw');
        break;
    case 'assignUser':
        $groups->addUserToGroup($_POST['GRP_UID'], $_POST['USR_UID']);
        break;
    case 'assignAllUsers':
        foreach (explode(',', $_POST['aUsers']) as $user) {
            $groups->addUserToGroup($_POST['GRP_UID'], $user);
        }
        break;
    case 'ofToAssignUser':
        $groups->removeUserOfGroup($_POST['GRP_UID'], $_POST['USR_UID']);
        break;
    case 'verifyGroupname':
        $_POST['sOriginalGroupname'] = get_ajax_value('sOriginalGroupname');
        $_POST['sGroupname'] = get_ajax_value('sGroupname');
        if ($_POST['sOriginalGroupname'] == $_POST['sGroupname']) {
            echo '0';
        } else {
            $oCriteria = $groupWf->loadByGroupname($_POST['sGroupname']);
            $oDataset = GroupwfPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            $aRow = $oDataset->getRow();
            if (!$aRow) {
                echo '0';
            } else {
                echo '1';
            }
        }
        break;
    case 'groupsList':
        $config = new Configurations();
        $config = $config->getConfiguration('groupList', 'pageSize', '', $_SESSION['USER_LOGGED']);
        $limit_size = isset($config['pageSize']) ? $config['pageSize'] : 20;
        $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : $limit_size;
        $filter = isset($_REQUEST['textFilter']) ? $_REQUEST['textFilter'] : '';

        $sortField = isset($_REQUEST["sort"]) ? $_REQUEST["sort"] : "";
        $sortDir = isset($_REQUEST["dir"]) ? $_REQUEST["dir"] : "";

        global $RBAC;
        if ($limit == $start) {
            $limit = $limit + $limit;
        }
        $tasks = new TaskUser();
        $aTask = $tasks->getCountAllTaksByGroups();

        require_once PATH_CONTROLLERS . 'adminProxy.php';
        $uxList = adminProxy::getUxTypesList();

        $data = $groupWf->getAllGroup($start, $limit, $filter, $sortField, $sortDir, true);
        $result = $data['rows'];

        $totalRows = 0;
        $arrData = array();
        foreach ($result as $results) {
            $totalRows++;
            $results['CON_VALUE'] = str_replace(array("<", ">"
            ), array("&lt;", "&gt;"
            ), $results['GRP_TITLE']);
            $results['GRP_TASKS'] = isset($aTask[$results['GRP_UID']]) ? $aTask[$results['GRP_UID']] : 0;
            $arrData[] = $results;
        }

        $result = new StdClass();
        $result->success = true;
        $result->groups = $arrData;
        $result->total_groups = $data['totalCount'];
        G::header('Content-Type: application/json');
        echo G::json_encode($result);
        break;
    case 'exitsGroupName':
        $oCriteria = $groupWf->loadByGroupname($_POST['GRP_NAME']);
        $oDataset = GroupwfPeer::doSelectRS($oCriteria);
        $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $oDataset->next();
        $aRow = $oDataset->getRow();
        $response = ($aRow) ? 'true' : 'false';
        echo $response;
        break;
    case 'saveNewGroup':
        $newGroup['GRP_UID'] = '';
        $newGroup['GRP_STATUS'] = ($_POST['status'] == '1') ? 'ACTIVE' : 'INACTIVE';
        $newGroup['GRP_TITLE'] = trim($_POST['name']);
        unset($newGroup['GRP_UID']);
        $groupWf->create($newGroup);
        G::auditLog("CreateGroup", "Group Name: " . $newGroup['GRP_TITLE'] . " - Group Status: " . $newGroup['GRP_STATUS']);

        echo '{success: true}';

        break;
    case 'saveEditGroup':
        $editGroup['GRP_UID'] = $_POST['grp_uid'];
        $editGroup['GRP_STATUS'] = ($_POST['status'] == '1') ? 'ACTIVE' : 'INACTIVE';
        $editGroup['GRP_TITLE'] = trim($_POST['name']);
        $groupWf->update($editGroup);
        G::auditLog("UpdateGroup", "Group Name: " . $editGroup['GRP_TITLE'] . " - Group ID: (" . $_POST['grp_uid'] . ") - Group Status: " . $editGroup['GRP_STATUS']);
        echo '{success: true}';
        break;
    case 'deleteGroup':
        if (!isset($_POST['GRP_UID'])) {
            return;
        }
        $groupWf->remove(urldecode($_POST['GRP_UID']));
        G::auditLog("DeleteGroup", "Group Name: " . $_POST['GRP_NAME'] . " Group ID: (" . $_POST['GRP_UID'] . ") ");
        require_once 'classes/model/TaskUser.php';
        $oProcess = new TaskUser();
        $oCriteria = new Criteria('workflow');
        $oCriteria->add(TaskUserPeer::USR_UID, $_POST['GRP_UID']);
        TaskUserPeer::doDelete($oCriteria);

        //Delete permissions
        require_once 'classes/model/ObjectPermission.php';
        $criteria = new Criteria('workflow');
        $criteria->add(ObjectPermissionPeer::USR_UID, $_POST['GRP_UID']);
        ObjectPermissionPeer::doDelete($criteria);

        //Delete supervisors assignments
        require_once 'classes/model/ProcessUser.php';
        $criteria = new Criteria('workflow');
        $criteria->add(ProcessUserPeer::USR_UID, $_POST['GRP_UID']);
        $criteria->add(ProcessUserPeer::PU_TYPE, 'GROUP_SUPERVISOR');
        ProcessUserPeer::doDelete($criteria);

        //Delete group users
        require_once 'classes/model/GroupUser.php';
        $criteria = new Criteria('workflow');
        $criteria->add(GroupUserPeer::GRP_UID, $_POST['GRP_UID']);
        GroupUserPeer::doDelete($criteria);

        echo '{success: true}';
        break;
    case 'assignedMembers':
    case 'availableMembers':
        $config = new Configurations();
        $inputFilter = new InputFilter();

        $config = $config->getConfiguration('groupList', 'pageSize', '', $_SESSION['USER_LOGGED']);
        $limit_size = isset($config['pageSize']) ? $config['pageSize'] : 20;
        $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : $limit_size;
        $filter = isset($_REQUEST['textFilter']) ? $_REQUEST['textFilter'] : '';
        $connection = Propel::getConnection("workflow")->getResource();
        $groupUid = $inputFilter->quoteSmart($_REQUEST['gUID'], $connection);

        $groupUsers = new GroupUser();
        $type = $_POST['action'] === 'assignedMembers' ? 'USERS' : 'AVAILABLE-USERS';
        $data = $groupUsers->getUsersbyGroup($groupUid, $type, $filter, 'USR_USERNAME', 'ASC', $start, $limit);

        G::header('Content-Type: application/json');
        echo '{success: true, members: ' . G::json_encode($data["data"]) . ', total_users: ' . $data["total"] . '}';
        break;
    case 'assignUsersToGroupsMultiple':
        $GRP_UID = $_POST['GRP_UID'];
        $uUIDs = explode(',', $_POST['USR_UID']);
        foreach ($uUIDs as $USR_UID) {
            $groups->addUserToGroup($GRP_UID, $USR_UID);
        }
        break;
    case 'deleteUsersToGroupsMultiple':
        $GRP_UID = $_POST['GRP_UID'];
        $uUIDs = explode(',', $_POST['USR_UID']);
        foreach ($uUIDs as $USR_UID) {
            $groups->removeUserOfGroup($GRP_UID, $USR_UID);
        }
        break;
    case 'updatePageSize':
        $c = new Configurations();
        $arr['pageSize'] = $_REQUEST['size'];
        $arr['dateSave'] = date('Y-m-d H:i:s');
        $config = array();
        $config[] = $arr;
        $c->aConfig = $config;
        $c->saveConfig('groupList', 'pageSize', '', $_SESSION['USER_LOGGED']);
        echo '{success: true}';
        break;
    case "verifyIfAssigned":
        $groupUid = $_POST["groupUid"];
        $message = "OK";

        $criteria = new Criteria("workflow");
        $criteria->addSelectColumn(TaskUserPeer::TAS_UID);
        $criteria->add(TaskUserPeer::USR_UID, $groupUid, Criteria::EQUAL);
        $criteria->add(TaskUserPeer::TU_RELATION, "2", Criteria::EQUAL);

        $rsCriteria = TaskUserPeer::doSelectRS($criteria);
        $rsCriteria->setFetchmode(ResultSet::FETCHMODE_ASSOC);

        if ($rsCriteria->next()) {
            $message = "ERROR";
        }

        $response = array();
        $response["result"] = $message;
        echo G::json_encode($response);
        break;
}
