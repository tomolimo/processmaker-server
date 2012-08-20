<?php

class Services_Rest_AppHistory
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed  Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get()
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(AppHistoryPeer::APP_UID);
                $criteria->addSelectColumn(AppHistoryPeer::DEL_INDEX);
                $criteria->addSelectColumn(AppHistoryPeer::PRO_UID);
                $criteria->addSelectColumn(AppHistoryPeer::TAS_UID);
                $criteria->addSelectColumn(AppHistoryPeer::DYN_UID);
                $criteria->addSelectColumn(AppHistoryPeer::USR_UID);
                $criteria->addSelectColumn(AppHistoryPeer::APP_STATUS);
                $criteria->addSelectColumn(AppHistoryPeer::HISTORY_DATE);
                $criteria->addSelectColumn(AppHistoryPeer::HISTORY_DATA);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = AppHistoryPeer::retrieveByPK();
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
     * @param  mixed  Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($appUid, $delIndex, $proUid, $tasUid, $dynUid, $usrUid, $appStatus, $historyDate, $historyData)
    {
        try {
            $result = array();
            $obj = new AppHistory();

            $obj->setAppUid($appUid);
            $obj->setDelIndex($delIndex);
            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setDynUid($dynUid);
            $obj->setUsrUid($usrUid);
            $obj->setAppStatus($appStatus);
            $obj->setHistoryDate($historyDate);
            $obj->setHistoryData($historyData);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed  Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($appUid, $delIndex, $proUid, $tasUid, $dynUid, $usrUid, $appStatus, $historyDate, $historyData)
    {
        try {
            $obj = AppHistoryPeer::retrieveByPK();

            $obj->setAppUid($appUid);
            $obj->setDelIndex($delIndex);
            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setDynUid($dynUid);
            $obj->setUsrUid($usrUid);
            $obj->setAppStatus($appStatus);
            $obj->setHistoryDate($historyDate);
            $obj->setHistoryData($historyData);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed  Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete()
    {
        $conn = Propel::getConnection(AppHistoryPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = AppHistoryPeer::retrieveByPK();
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
