<?php

class Services_Rest_CaseTrackerObject
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $ctoUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($ctoUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(CaseTrackerObjectPeer::CTO_UID);
                $criteria->addSelectColumn(CaseTrackerObjectPeer::PRO_UID);
                $criteria->addSelectColumn(CaseTrackerObjectPeer::CTO_TYPE_OBJ);
                $criteria->addSelectColumn(CaseTrackerObjectPeer::CTO_UID_OBJ);
                $criteria->addSelectColumn(CaseTrackerObjectPeer::CTO_CONDITION);
                $criteria->addSelectColumn(CaseTrackerObjectPeer::CTO_POSITION);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = CaseTrackerObjectPeer::retrieveByPK($ctoUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
