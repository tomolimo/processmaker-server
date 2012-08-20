<?php

class Services_Rest_AppCacheView
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

                $criteria->addSelectColumn(AppCacheViewPeer::APP_UID);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_INDEX);
                $criteria->addSelectColumn(AppCacheViewPeer::APP_NUMBER);
                $criteria->addSelectColumn(AppCacheViewPeer::APP_STATUS);
                $criteria->addSelectColumn(AppCacheViewPeer::USR_UID);
                $criteria->addSelectColumn(AppCacheViewPeer::PREVIOUS_USR_UID);
                $criteria->addSelectColumn(AppCacheViewPeer::TAS_UID);
                $criteria->addSelectColumn(AppCacheViewPeer::PRO_UID);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_DELEGATE_DATE);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_INIT_DATE);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_TASK_DUE_DATE);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_FINISH_DATE);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_THREAD_STATUS);
                $criteria->addSelectColumn(AppCacheViewPeer::APP_THREAD_STATUS);
                $criteria->addSelectColumn(AppCacheViewPeer::APP_TITLE);
                $criteria->addSelectColumn(AppCacheViewPeer::APP_PRO_TITLE);
                $criteria->addSelectColumn(AppCacheViewPeer::APP_TAS_TITLE);
                $criteria->addSelectColumn(AppCacheViewPeer::APP_CURRENT_USER);
                $criteria->addSelectColumn(AppCacheViewPeer::APP_DEL_PREVIOUS_USER);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_PRIORITY);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_DURATION);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_QUEUE_DURATION);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_DELAY_DURATION);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_STARTED);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_FINISHED);
                $criteria->addSelectColumn(AppCacheViewPeer::DEL_DELAYED);
                $criteria->addSelectColumn(AppCacheViewPeer::APP_CREATE_DATE);
                $criteria->addSelectColumn(AppCacheViewPeer::APP_FINISH_DATE);
                $criteria->addSelectColumn(AppCacheViewPeer::APP_UPDATE_DATE);
                $criteria->addSelectColumn(AppCacheViewPeer::APP_OVERDUE_PERCENTAGE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = AppCacheViewPeer::retrieveByPK($appUid, $delIndex);
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
    protected function post($appUid, $delIndex, $appNumber, $appStatus, $usrUid, $previousUsrUid, $tasUid, $proUid, $delDelegateDate, $delInitDate, $delTaskDueDate, $delFinishDate, $delThreadStatus, $appThreadStatus, $appTitle, $appProTitle, $appTasTitle, $appCurrentUser, $appDelPreviousUser, $delPriority, $delDuration, $delQueueDuration, $delDelayDuration, $delStarted, $delFinished, $delDelayed, $appCreateDate, $appFinishDate, $appUpdateDate, $appOverduePercentage)
    {
        try {
            $result = array();
            $obj = new AppCacheView();

            $obj->setAppUid($appUid);
            $obj->setDelIndex($delIndex);
            $obj->setAppNumber($appNumber);
            $obj->setAppStatus($appStatus);
            $obj->setUsrUid($usrUid);
            $obj->setPreviousUsrUid($previousUsrUid);
            $obj->setTasUid($tasUid);
            $obj->setProUid($proUid);
            $obj->setDelDelegateDate($delDelegateDate);
            $obj->setDelInitDate($delInitDate);
            $obj->setDelTaskDueDate($delTaskDueDate);
            $obj->setDelFinishDate($delFinishDate);
            $obj->setDelThreadStatus($delThreadStatus);
            $obj->setAppThreadStatus($appThreadStatus);
            $obj->setAppTitle($appTitle);
            $obj->setAppProTitle($appProTitle);
            $obj->setAppTasTitle($appTasTitle);
            $obj->setAppCurrentUser($appCurrentUser);
            $obj->setAppDelPreviousUser($appDelPreviousUser);
            $obj->setDelPriority($delPriority);
            $obj->setDelDuration($delDuration);
            $obj->setDelQueueDuration($delQueueDuration);
            $obj->setDelDelayDuration($delDelayDuration);
            $obj->setDelStarted($delStarted);
            $obj->setDelFinished($delFinished);
            $obj->setDelDelayed($delDelayed);
            $obj->setAppCreateDate($appCreateDate);
            $obj->setAppFinishDate($appFinishDate);
            $obj->setAppUpdateDate($appUpdateDate);
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
    protected function put($appUid, $delIndex, $appNumber, $appStatus, $usrUid, $previousUsrUid, $tasUid, $proUid, $delDelegateDate, $delInitDate, $delTaskDueDate, $delFinishDate, $delThreadStatus, $appThreadStatus, $appTitle, $appProTitle, $appTasTitle, $appCurrentUser, $appDelPreviousUser, $delPriority, $delDuration, $delQueueDuration, $delDelayDuration, $delStarted, $delFinished, $delDelayed, $appCreateDate, $appFinishDate, $appUpdateDate, $appOverduePercentage)
    {
        try {
            $obj = AppCacheViewPeer::retrieveByPK($appUid, $delIndex);

            $obj->setAppNumber($appNumber);
            $obj->setAppStatus($appStatus);
            $obj->setUsrUid($usrUid);
            $obj->setPreviousUsrUid($previousUsrUid);
            $obj->setTasUid($tasUid);
            $obj->setProUid($proUid);
            $obj->setDelDelegateDate($delDelegateDate);
            $obj->setDelInitDate($delInitDate);
            $obj->setDelTaskDueDate($delTaskDueDate);
            $obj->setDelFinishDate($delFinishDate);
            $obj->setDelThreadStatus($delThreadStatus);
            $obj->setAppThreadStatus($appThreadStatus);
            $obj->setAppTitle($appTitle);
            $obj->setAppProTitle($appProTitle);
            $obj->setAppTasTitle($appTasTitle);
            $obj->setAppCurrentUser($appCurrentUser);
            $obj->setAppDelPreviousUser($appDelPreviousUser);
            $obj->setDelPriority($delPriority);
            $obj->setDelDuration($delDuration);
            $obj->setDelQueueDuration($delQueueDuration);
            $obj->setDelDelayDuration($delDelayDuration);
            $obj->setDelStarted($delStarted);
            $obj->setDelFinished($delFinished);
            $obj->setDelDelayed($delDelayed);
            $obj->setAppCreateDate($appCreateDate);
            $obj->setAppFinishDate($appFinishDate);
            $obj->setAppUpdateDate($appUpdateDate);
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
        $conn = Propel::getConnection(AppCacheViewPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = AppCacheViewPeer::retrieveByPK($appUid, $delIndex);
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
