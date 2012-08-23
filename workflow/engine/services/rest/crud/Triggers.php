<?php

class Services_Rest_Triggers
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $triUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($triUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(TriggersPeer::TRI_UID);
                $criteria->addSelectColumn(TriggersPeer::PRO_UID);
                $criteria->addSelectColumn(TriggersPeer::TRI_TYPE);
                $criteria->addSelectColumn(TriggersPeer::TRI_WEBBOT);
                $criteria->addSelectColumn(TriggersPeer::TRI_PARAM);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = TriggersPeer::retrieveByPK($triUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
