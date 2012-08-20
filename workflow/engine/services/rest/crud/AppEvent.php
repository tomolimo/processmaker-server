<?php

class Services_Rest_AppEvent
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $appUid, $delIndex, $evnUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($appUid=null, $delIndex=null, $evnUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(AppEventPeer::APP_UID);
                $criteria->addSelectColumn(AppEventPeer::DEL_INDEX);
                $criteria->addSelectColumn(AppEventPeer::EVN_UID);
                $criteria->addSelectColumn(AppEventPeer::APP_EVN_ACTION_DATE);
                $criteria->addSelectColumn(AppEventPeer::APP_EVN_ATTEMPTS);
                $criteria->addSelectColumn(AppEventPeer::APP_EVN_LAST_EXECUTION_DATE);
                $criteria->addSelectColumn(AppEventPeer::APP_EVN_STATUS);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = AppEventPeer::retrieveByPK($appUid, $delIndex, $evnUid);
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
     * @param  mixed $appUid, $delIndex, $evnUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($appUid, $delIndex, $evnUid, $appEvnActionDate, $appEvnAttempts, $appEvnLastExecutionDate, $appEvnStatus)
    {
        try {
            $result = array();
            $obj = new AppEvent();

            $obj->setAppUid($appUid);
            $obj->setDelIndex($delIndex);
            $obj->setEvnUid($evnUid);
            $obj->setAppEvnActionDate($appEvnActionDate);
            $obj->setAppEvnAttempts($appEvnAttempts);
            $obj->setAppEvnLastExecutionDate($appEvnLastExecutionDate);
            $obj->setAppEvnStatus($appEvnStatus);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $appUid, $delIndex, $evnUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($appUid, $delIndex, $evnUid, $appEvnActionDate, $appEvnAttempts, $appEvnLastExecutionDate, $appEvnStatus)
    {
        try {
            $obj = AppEventPeer::retrieveByPK($appUid, $delIndex, $evnUid);

            $obj->setAppEvnActionDate($appEvnActionDate);
            $obj->setAppEvnAttempts($appEvnAttempts);
            $obj->setAppEvnLastExecutionDate($appEvnLastExecutionDate);
            $obj->setAppEvnStatus($appEvnStatus);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $appUid, $delIndex, $evnUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($appUid, $delIndex, $evnUid)
    {
        $conn = Propel::getConnection(AppEventPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = AppEventPeer::retrieveByPK($appUid, $delIndex, $evnUid);
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
