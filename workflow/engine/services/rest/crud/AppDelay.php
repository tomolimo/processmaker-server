<?php

class Services_Rest_AppDelay
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $appDelayUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($appDelayUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(AppDelayPeer::APP_DELAY_UID);
                $criteria->addSelectColumn(AppDelayPeer::PRO_UID);
                $criteria->addSelectColumn(AppDelayPeer::APP_UID);
                $criteria->addSelectColumn(AppDelayPeer::APP_THREAD_INDEX);
                $criteria->addSelectColumn(AppDelayPeer::APP_DEL_INDEX);
                $criteria->addSelectColumn(AppDelayPeer::APP_TYPE);
                $criteria->addSelectColumn(AppDelayPeer::APP_STATUS);
                $criteria->addSelectColumn(AppDelayPeer::APP_NEXT_TASK);
                $criteria->addSelectColumn(AppDelayPeer::APP_DELEGATION_USER);
                $criteria->addSelectColumn(AppDelayPeer::APP_ENABLE_ACTION_USER);
                $criteria->addSelectColumn(AppDelayPeer::APP_ENABLE_ACTION_DATE);
                $criteria->addSelectColumn(AppDelayPeer::APP_DISABLE_ACTION_USER);
                $criteria->addSelectColumn(AppDelayPeer::APP_DISABLE_ACTION_DATE);
                $criteria->addSelectColumn(AppDelayPeer::APP_AUTOMATIC_DISABLED_DATE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = AppDelayPeer::retrieveByPK($appDelayUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed $appDelayUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($appDelayUid, $proUid, $appUid, $appThreadIndex, $appDelIndex, $appType, $appStatus, $appNextTask, $appDelegationUser, $appEnableActionUser, $appEnableActionDate, $appDisableActionUser, $appDisableActionDate, $appAutomaticDisabledDate)
    {
        try {
            $result = array();
            $obj = new AppDelay();

            $obj->setAppDelayUid($appDelayUid);
            $obj->setProUid($proUid);
            $obj->setAppUid($appUid);
            $obj->setAppThreadIndex($appThreadIndex);
            $obj->setAppDelIndex($appDelIndex);
            $obj->setAppType($appType);
            $obj->setAppStatus($appStatus);
            $obj->setAppNextTask($appNextTask);
            $obj->setAppDelegationUser($appDelegationUser);
            $obj->setAppEnableActionUser($appEnableActionUser);
            $obj->setAppEnableActionDate($appEnableActionDate);
            $obj->setAppDisableActionUser($appDisableActionUser);
            $obj->setAppDisableActionDate($appDisableActionDate);
            $obj->setAppAutomaticDisabledDate($appAutomaticDisabledDate);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $appDelayUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($appDelayUid, $proUid, $appUid, $appThreadIndex, $appDelIndex, $appType, $appStatus, $appNextTask, $appDelegationUser, $appEnableActionUser, $appEnableActionDate, $appDisableActionUser, $appDisableActionDate, $appAutomaticDisabledDate)
    {
        try {
            $obj = AppDelayPeer::retrieveByPK($appDelayUid);

            $obj->setProUid($proUid);
            $obj->setAppUid($appUid);
            $obj->setAppThreadIndex($appThreadIndex);
            $obj->setAppDelIndex($appDelIndex);
            $obj->setAppType($appType);
            $obj->setAppStatus($appStatus);
            $obj->setAppNextTask($appNextTask);
            $obj->setAppDelegationUser($appDelegationUser);
            $obj->setAppEnableActionUser($appEnableActionUser);
            $obj->setAppEnableActionDate($appEnableActionDate);
            $obj->setAppDisableActionUser($appDisableActionUser);
            $obj->setAppDisableActionDate($appDisableActionDate);
            $obj->setAppAutomaticDisabledDate($appAutomaticDisabledDate);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $appDelayUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($appDelayUid)
    {
        $conn = Propel::getConnection(AppDelayPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = AppDelayPeer::retrieveByPK($appDelayUid);
            if (! is_object($obj)) {
                throw new RestException(412, 'Record does not exist.');
            }
            $obj->delete();
        
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            throw new RestException(412, $e->getMessage());
        }
    }


}
