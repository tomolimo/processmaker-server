<?php

class Services_Rest_AppDelegation
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $appUid, $delIndex Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($appUid=null, $delIndex=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(AppDelegationPeer::APP_UID);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_INDEX);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_PREVIOUS);
                $criteria->addSelectColumn(AppDelegationPeer::PRO_UID);
                $criteria->addSelectColumn(AppDelegationPeer::TAS_UID);
                $criteria->addSelectColumn(AppDelegationPeer::USR_UID);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_TYPE);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_THREAD);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_THREAD_STATUS);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_PRIORITY);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_DELEGATE_DATE);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_INIT_DATE);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_TASK_DUE_DATE);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_FINISH_DATE);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_DURATION);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_QUEUE_DURATION);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_DELAY_DURATION);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_STARTED);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_FINISHED);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_DELAYED);
                $criteria->addSelectColumn(AppDelegationPeer::DEL_DATA);
                $criteria->addSelectColumn(AppDelegationPeer::APP_OVERDUE_PERCENTAGE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = AppDelegationPeer::retrieveByPK($appUid, $delIndex);
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
     * @param  mixed $appUid, $delIndex Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($appUid, $delIndex, $delPrevious, $proUid, $tasUid, $usrUid, $delType, $delThread, $delThreadStatus, $delPriority, $delDelegateDate, $delInitDate, $delTaskDueDate, $delFinishDate, $delDuration, $delQueueDuration, $delDelayDuration, $delStarted, $delFinished, $delDelayed, $delData, $appOverduePercentage)
    {
        try {
            $result = array();
            $obj = new AppDelegation();

            $obj->setAppUid($appUid);
            $obj->setDelIndex($delIndex);
            $obj->setDelPrevious($delPrevious);
            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setUsrUid($usrUid);
            $obj->setDelType($delType);
            $obj->setDelThread($delThread);
            $obj->setDelThreadStatus($delThreadStatus);
            $obj->setDelPriority($delPriority);
            $obj->setDelDelegateDate($delDelegateDate);
            $obj->setDelInitDate($delInitDate);
            $obj->setDelTaskDueDate($delTaskDueDate);
            $obj->setDelFinishDate($delFinishDate);
            $obj->setDelDuration($delDuration);
            $obj->setDelQueueDuration($delQueueDuration);
            $obj->setDelDelayDuration($delDelayDuration);
            $obj->setDelStarted($delStarted);
            $obj->setDelFinished($delFinished);
            $obj->setDelDelayed($delDelayed);
            $obj->setDelData($delData);
            $obj->setAppOverduePercentage($appOverduePercentage);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $appUid, $delIndex Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($appUid, $delIndex, $delPrevious, $proUid, $tasUid, $usrUid, $delType, $delThread, $delThreadStatus, $delPriority, $delDelegateDate, $delInitDate, $delTaskDueDate, $delFinishDate, $delDuration, $delQueueDuration, $delDelayDuration, $delStarted, $delFinished, $delDelayed, $delData, $appOverduePercentage)
    {
        try {
            $obj = AppDelegationPeer::retrieveByPK($appUid, $delIndex);

            $obj->setDelPrevious($delPrevious);
            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setUsrUid($usrUid);
            $obj->setDelType($delType);
            $obj->setDelThread($delThread);
            $obj->setDelThreadStatus($delThreadStatus);
            $obj->setDelPriority($delPriority);
            $obj->setDelDelegateDate($delDelegateDate);
            $obj->setDelInitDate($delInitDate);
            $obj->setDelTaskDueDate($delTaskDueDate);
            $obj->setDelFinishDate($delFinishDate);
            $obj->setDelDuration($delDuration);
            $obj->setDelQueueDuration($delQueueDuration);
            $obj->setDelDelayDuration($delDelayDuration);
            $obj->setDelStarted($delStarted);
            $obj->setDelFinished($delFinished);
            $obj->setDelDelayed($delDelayed);
            $obj->setDelData($delData);
            $obj->setAppOverduePercentage($appOverduePercentage);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $appUid, $delIndex Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($appUid, $delIndex)
    {
        $conn = Propel::getConnection(AppDelegationPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = AppDelegationPeer::retrieveByPK($appUid, $delIndex);
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
