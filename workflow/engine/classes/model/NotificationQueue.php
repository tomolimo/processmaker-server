<?php

require_once 'classes/model/om/BaseNotificationQueue.php';


/**
 * Skeleton subclass for representing a row from the 'NOTIFICATION_QUEUE' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class NotificationQueue extends BaseNotificationQueue
{
    public function create(array $arrayData)
    {
        $cnn = Propel::getConnection(NotificationDevicePeer::DATABASE_NAME);
        try {
            $this->setNotUid(G::generateUniqueID());
            $this->setDevType($arrayData['DEV_TYPE']);
            $this->setDevUid($arrayData['DEV_UID']);
            $this->setNotMsg($arrayData['NOT_MSG']);
            $this->setNotData($arrayData['NOT_DATA']);
            $this->setNotStatus($arrayData['NOT_STATUS']);
            $this->setNotSendDate('now');
            $this->setAppUid($arrayData['APP_UID']);
            $this->setDelIndex($arrayData['DEL_INDEX']);

            if ($this->validate()) {
                $cnn->begin();
                $result = $this->save();
                $cnn->commit();
            } else {
                throw new Exception(G::LoadTranslation("ID_RECORD_CANNOT_BE_CREATED"));
            }
        } catch (Exception $e) {
            $cnn->rollback();
            throw $e;
        }
        return $result;
    }

    public function loadStatus($status)
    {
        try {
            $criteria = new Criteria();
            $criteria->clearSelectColumns();
            $criteria->add(NotificationQueuePeer::NOT_STATUS, $status, Criteria::EQUAL);

            $rs = NotificationQueuePeer::doSelectRS($criteria);
            $rs->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $notifications = array();
            while ($rs->next()) {
                $row = $rs->getRow();
                $notifications[] = $row;
            }
        } catch (Exception $error) {
            throw $error;
        }
        return $notifications;
    }

    /**
     * This method changes the state of a notification when the case ended before running the cron.php
     */
    public function checkIfCasesOpenForResendingNotification()
    {
        $arrayCondition = array();
        $criteria = new Criteria();
        $criteria->clearSelectColumns();
        $criteria->addSelectColumn(NotificationQueuePeer::APP_UID);
        $criteria->addSelectColumn(NotificationQueuePeer::DEL_INDEX);
        $criteria->addSelectColumn(NotificationQueuePeer::NOT_UID);
        $criteria->add(AppDelegationPeer::DEL_FINISH_DATE, null, Criteria::ISNOTNULL);
        $arrayCondition[] = array(NotificationQueuePeer::APP_UID, AppDelegationPeer::APP_UID, Criteria::EQUAL);
        $arrayCondition[] = array(NotificationQueuePeer::DEL_INDEX, AppDelegationPeer::DEL_INDEX, Criteria::EQUAL);
        $criteria->addJoinMC($arrayCondition, Criteria::LEFT_JOIN);
        $rs = NotificationQueuePeer::doSelectRS($criteria);
        $rs->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $notUID = array();
        while ($rs->next()) {
            $row = $rs->getRow();
            if ($row['DEL_INDEX'] != 0 && $row['APP_UID'] != '') {
                array_push($notUID, $row['NOT_UID']);
            }
        }

        $criteriaSet = new Criteria("workflow");
        $criteriaSet->add(NotificationQueuePeer::NOT_STATUS, 'sent');
        $criteriaSet->add(NotificationQueuePeer::NOT_SEND_DATE, date('Y-m-d H:i:s'));
        $criteriaWhere = new Criteria("workflow");
        $criteriaWhere->add(NotificationQueuePeer::NOT_UID, $notUID, Criteria::IN);

        \BasePeer::doUpdate($criteriaWhere, $criteriaSet, Propel::getConnection("workflow"));
    }

    public function loadStatusDeviceType($status, $devType)
    {
        try {
            $criteria = new Criteria();
            $criteria->clearSelectColumns();
            $criteria->add(NotificationQueuePeer::NOT_STATUS, $status, Criteria::EQUAL);
            $criteria->add(NotificationQueuePeer::DEV_TYPE, $devType, Criteria::EQUAL);

            $rs = NotificationQueuePeer::doSelectRS($criteria);
            $rs->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $notifications = array();
            while ($rs->next()) {
                $row = $rs->getRow();
                $notifications[] = $row;
            }
        } catch (Exception $error) {
            throw $error;
        }
        return $notifications;
    }
    
    public function changeStatusSent($not_uid)
    {
        $cnn = Propel::getConnection(NotificationDevicePeer::DATABASE_NAME);
        $rs = NotificationQueuePeer::retrieveByPK($not_uid);
        try {
            $arrayData['NOT_STATUS'] = "sent";
            $arrayData['NOT_SEND_DATE'] = date('Y-m-d H:i:s');
            $rs->fromArray($arrayData, BasePeer::TYPE_FIELDNAME);
            if ($this->validate()) {
                $cnn->begin();
                $result = $rs->save();
                $cnn->commit();
            } else {
                throw new Exception(G::LoadTranslation("ID_RECORD_CANNOT_BE_CREATED"));
            }
        } catch (Exception $e) {
            $cnn->rollback();
            throw $e;
        }
        return $result;
    }
} // NotificationQueue
