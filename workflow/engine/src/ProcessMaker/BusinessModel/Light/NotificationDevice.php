<?php

namespace ProcessMaker\BusinessModel\Light;

use \ProcessMaker\Services\Api;
use G;

class NotificationDevice
{
    
    
    public function checkMobileNotifications()
    {
        $conf = \System::getSystemConfiguration('', '', SYS_SYS);
        $activeNotifications = true;
        if (isset($conf['mobileNotifications'])) {
            $activeNotifications = $conf['mobileNotifications'] == 1 ? true : false;
        }
        return $activeNotifications;
    }
    
    /**
     * Post Create register device with userUid
     *
     * @param array $request_data
     * @param string $use_uid
     * @author Ronald Quenta <ronald.quenta@processmaker.com>
     *
     */
    public function saveDevice($use_uid, $request_data)
    {
        $arrayData = array();
        $arrayData['USR_UID'] = $use_uid;
        $arrayData['DEV_REG_ID'] = $request_data['deviceIdToken'];
        $arrayData['SYS_LANG'] = $request_data['sysLanguage'];
        $arrayData['DEV_TYPE'] = $request_data['deviceType'];
        $arrayData['DEV_STATUS'] = 'active';

        $oNoti = new \NotificationDevice();
        $devices = $oNoti->loadByDeviceId($request_data['deviceIdToken']);
        $response = array();
        if (!$devices){
            if($oNoti->create($arrayData)){
                $response["devUid"] = $oNoti->getDevUid();
                $response["message"] = G:: LoadTranslation("ID_RECORD_SAVED_SUCCESFULLY");
                G::auditLog("Create", "Device Save: Device ID (".$oNoti->getDevUid().") ");
            }
        } else {
            if($oNoti->remove($devices[0]['DEV_UID'],$devices[0]['USR_UID'])){
                $arrayData['USR_UID'] = $use_uid;
                $arrayData['DEV_REG_ID'] = $devices[0]['DEV_REG_ID'];
                $arrayData['SYS_LANG'] = $devices[0]['SYS_LANG'];
                $arrayData['DEV_TYPE'] = $devices[0]['DEV_TYPE'];
                $arrayData['DEV_STATUS'] = 'active';

                if($devUid = $oNoti->createOrUpdate($arrayData)){
                    $response["devUid"] = $devUid;
                    $response["message"] = G:: LoadTranslation("ID_RECORD_SAVED_SUCCESFULLY");
                    G::auditLog("Create", "Device Save: Device ID (".$oNoti->getDevUid().") ");
                } else {
                    throw new \Exception(G::LoadTranslation("ID_RECORD_CANNOT_BE_CREATED"));
                }
            } else {
                throw new \Exception(G::LoadTranslation("ID_RECORD_DOES_NOT_EXIST"));
            }
        }
        return $response;
    }

    /**
     * Update register device with userUid
     *
     * @param array $request_data
     * @param string $dev_uid
     * @param string $use_uid
     * @author Ronald Quenta <ronald.quenta@processmaker.com>
     *
     */
    public function updateDevice($dev_uid, $use_uid, $request_data)
    {

        $arrayData = array();
        $arrayData['USR_UID'] = $use_uid;
        $arrayData['DEV_UID'] = $dev_uid;
        if(isset($request_data['deviceIdToken'])){
            $arrayData['DEV_REG_ID'] = $request_data['deviceIdToken'];
        }
        if(isset($request_data['sysLanguage'])) {
            $arrayData['SYS_LANG'] = $request_data['sysLanguage'];
        }
        if(isset($request_data['deviceType'])) {
            $arrayData['DEV_TYPE'] = $request_data['deviceType'];
        }
        $oNoti = new \NotificationDevice();
        $response = array();
        if($oNoti->update($arrayData)){
            $response["message"] = G:: LoadTranslation("ID_RECORD_SAVED_SUCCESFULLY");
            G::auditLog("Update", "Device Save: Device ID (".$oNoti->getDevUid().") ");
        }
        return $response;

    }

    /**
     * Send Message each user id
     *
     * @param array $request_data
     * @author Ronald Quenta <ronald.quenta@processmaker.com>
     *
     */
    public function sendMessage($userIds, $message, $data = null)
    {
        try {
            $oNoti = new \NotificationDevice();
            $devices = array();
            if (is_array($userIds)){
                foreach ($userIds as $id) {
                    $deviceUser = $oNoti->loadByUsersId($id);
                    $devices = array_merge($devices, $deviceUser);
                }
            } else {
                $devices = $oNoti->loadByUsersId($userIds);
            }

            $devicesAndroidIds = array();
            $devicesAppleIds = array();
            foreach ($devices as $dev) {
                switch ($dev['DEV_TYPE']) {
                    case "apple":
                        $devicesAppleIds[] = $dev['DEV_REG_ID'];
                        break;
                    case "android":
                        $devicesAndroidIds[] = $dev['DEV_REG_ID'];
                        break;
                }
            }
            if (count($devicesAppleIds) > 0) {
                $oNotification = new PushMessageIOS();
                $oNotification->setSettingNotification();
                $oNotification->setDevices($devicesAppleIds);
                $response['android'] = $oNotification->send($message, $data);
            }
            if (count($devicesAndroidIds) > 0) {
                $oNotification = new PushMessageAndroid();
                $oNotification->setSettingNotification();
                $oNotification->setDevices($devicesAndroidIds);
                $response['apple'] = $oNotification->send($message, $data);
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Api::STAT_APP_EXCEPTION);
        }
        return $response;
    }

    /**
     * Send Message each user id
     *
     * @param array $request_data
     * @author Ronald Quenta <ronald.quenta@processmaker.com>
     *
     */
    public function routeCaseNotification($currentUserId, $processId, $currentTaskId, $appFields, $aTasks,
                                          $nextIndex, $currentDelIndex)
    {
        try {
            $response = array();
            $typeList = 'todo';
            foreach ($aTasks as $aTask) {
                $arrayTaskUser = array();
                switch ($aTask["TAS_ASSIGN_TYPE"]) {
                    case "SELF_SERVICE":
                        $arrayTaskUser = $this->getTaskUserSelfService($aTask["TAS_UID"], $appFields);
                        $typeList = 'unassigned';
                        break;
                    default:
                        if (isset($aTask["USR_UID"]) && !empty($aTask["USR_UID"])) {
                            $arrayTaskUser = $aTask["USR_UID"];
                        }
                        break;
                }

                $delIndex = null;
                foreach ($nextIndex as $nIndex) {
                    if ($aTask['TAS_UID'] == $nIndex['TAS_UID']) {
                        $delIndex = $nIndex['DEL_INDEX'];
                        break;
                    }
                }

                $userIds = $arrayTaskUser;
                $message = '#'. $appFields['APP_NUMBER'] . ' : '.$appFields['APP_TITLE'];
                $data = array(
                    'processId' => $processId,
                    'taskId' => $aTask["TAS_UID"],
                    'caseId' => $appFields['APP_UID'],
                    'caseTitle' => $appFields['APP_TITLE'],
                    'delIndex' => $delIndex,
                    'typeList' => $typeList
                );

                if ($userIds) {
                    $oNoti = new \NotificationDevice();
                    $devices = array();
                    if (is_array($userIds)) {
                        $devices = $oNoti->loadUsersArrayId($userIds);
                    } else {
                        $devices = $oNoti->loadByUsersId($userIds);
                        $lists   = new \ProcessMaker\BusinessModel\Lists();
                        $counter = $lists->getCounters($userIds);
                        $light   = new \ProcessMaker\Services\Api\Light();
                        $result  = $light->parserCountersCases($counter);
                        $data['counters'] = $result;
                    }

                    $devicesAndroidIds = array();
                    $devicesAppleIds = array();
                    foreach ($devices as $dev) {
                        switch ($dev['DEV_TYPE']) {
                            case "apple":
                                $devicesAppleIds[] = $dev['DEV_REG_ID'];
                                break;
                            case "android":
                                $devicesAndroidIds[] = $dev['DEV_REG_ID'];
                                break;
                        }
                    }
                    $isExistNextNotifications = $oNoti->isExistNextNotification($appFields['APP_UID'],
                        $currentDelIndex);
                    if (count($devicesAppleIds) > 0 && $isExistNextNotifications) {
                        $oNotification = new PushMessageIOS();
                        $oNotification->setSettingNotification();
                        $oNotification->setDevices($devicesAppleIds);
                        $response['apple'] = $oNotification->send($message, $data);
                    }
                    if (count($devicesAndroidIds) > 0 && $isExistNextNotifications) {
                        $oNotification = new PushMessageAndroid();
                        $oNotification->setSettingNotification();
                        $oNotification->setDevices($devicesAndroidIds);
                        $response['android'] = $oNotification->send($message, $data);
                    }
                }
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Api::STAT_APP_EXCEPTION);
        }
        
        return $response;
    }

    public function getTaskUserSelfService($tas_uid, $appFields)
    {
        $oTask = new \Tasks();
        $oGroup = new \Groups();
        $taskNextDel = \TaskPeer::retrieveByPK($tas_uid);
        $arrayTaskUser = array();

        if ($taskNextDel->getTasAssignType() == "SELF_SERVICE" && trim($taskNextDel->getTasGroupVariable()) != "") {
            // Self Service Value Based Assignment
            $nextTaskGroupVariable = trim($taskNextDel->getTasGroupVariable(), " @#");
            if (isset($appFields["APP_DATA"][$nextTaskGroupVariable])) {
                $dataGroupVariable = $appFields["APP_DATA"][$nextTaskGroupVariable];
                $dataGroupVariable = (is_array($dataGroupVariable))? $dataGroupVariable : trim($dataGroupVariable);
                if (!empty($dataGroupVariable) && is_array($dataGroupVariable)){
                    $arrayTaskUser[] = $dataGroupVariable;
                } elseif(!empty($dataGroupVariable)) {
                    $arrayUsersOfGroup = $oGroup->getUsersOfGroup($dataGroupVariable);
                    foreach ($arrayUsersOfGroup as $arrayUser) {
                        $arrayTaskUser[] = $arrayUser["USR_UID"];
                    }
                }
            }
        } else { // Self Service
            $arrayGroupsOfTask = $oTask->getGroupsOfTask($tas_uid, 1);
            foreach ($arrayGroupsOfTask as $arrayGroup) {
                $arrayUsersOfGroup = $oGroup->getUsersOfGroup($arrayGroup["GRP_UID"]);
                foreach ($arrayUsersOfGroup as $arrayUser) {
                    $arrayTaskUser[] = $arrayUser["USR_UID"];
                }
            }
            $arrayUsersOfTask = $oTask->getUsersOfTask($tas_uid, 1);
            foreach ($arrayUsersOfTask as $arrayUser) {
                $arrayTaskUser[] = $arrayUser["USR_UID"];
            }
        }

        return $arrayTaskUser;
    }

}
