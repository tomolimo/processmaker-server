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


}
