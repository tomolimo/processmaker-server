<?php

class Services_Rest_StepSupervisor
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $stepUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($stepUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(StepSupervisorPeer::STEP_UID);
                $criteria->addSelectColumn(StepSupervisorPeer::PRO_UID);
                $criteria->addSelectColumn(StepSupervisorPeer::STEP_TYPE_OBJ);
                $criteria->addSelectColumn(StepSupervisorPeer::STEP_UID_OBJ);
                $criteria->addSelectColumn(StepSupervisorPeer::STEP_POSITION);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = StepSupervisorPeer::retrieveByPK($stepUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
