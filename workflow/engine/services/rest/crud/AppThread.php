<?php

class Services_Rest_AppThread
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $appUid, $appThreadIndex Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($appUid=null, $appThreadIndex=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(AppThreadPeer::APP_UID);
                $criteria->addSelectColumn(AppThreadPeer::APP_THREAD_INDEX);
                $criteria->addSelectColumn(AppThreadPeer::APP_THREAD_PARENT);
                $criteria->addSelectColumn(AppThreadPeer::APP_THREAD_STATUS);
                $criteria->addSelectColumn(AppThreadPeer::DEL_INDEX);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = AppThreadPeer::retrieveByPK($appUid, $appThreadIndex);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
