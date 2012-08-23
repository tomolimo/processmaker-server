<?php

class Services_Rest_StepTrigger
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $stepUid, $tasUid, $triUid, $stType Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($stepUid=null, $tasUid=null, $triUid=null, $stType=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(StepTriggerPeer::STEP_UID);
                $criteria->addSelectColumn(StepTriggerPeer::TAS_UID);
                $criteria->addSelectColumn(StepTriggerPeer::TRI_UID);
                $criteria->addSelectColumn(StepTriggerPeer::ST_TYPE);
                $criteria->addSelectColumn(StepTriggerPeer::ST_CONDITION);
                $criteria->addSelectColumn(StepTriggerPeer::ST_POSITION);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = StepTriggerPeer::retrieveByPK($stepUid, $tasUid, $triUid, $stType);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
