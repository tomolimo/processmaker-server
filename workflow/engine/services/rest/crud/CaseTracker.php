<?php

class Services_Rest_CaseTracker
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($proUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(CaseTrackerPeer::PRO_UID);
                $criteria->addSelectColumn(CaseTrackerPeer::CT_MAP_TYPE);
                $criteria->addSelectColumn(CaseTrackerPeer::CT_DERIVATION_HISTORY);
                $criteria->addSelectColumn(CaseTrackerPeer::CT_MESSAGE_HISTORY);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = CaseTrackerPeer::retrieveByPK($proUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
