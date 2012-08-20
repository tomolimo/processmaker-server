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

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed $stepUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($stepUid, $proUid, $stepTypeObj, $stepUidObj, $stepPosition)
    {
        try {
            $result = array();
            $obj = new StepSupervisor();

            $obj->setStepUid($stepUid);
            $obj->setProUid($proUid);
            $obj->setStepTypeObj($stepTypeObj);
            $obj->setStepUidObj($stepUidObj);
            $obj->setStepPosition($stepPosition);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $stepUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($stepUid, $proUid, $stepTypeObj, $stepUidObj, $stepPosition)
    {
        try {
            $obj = StepSupervisorPeer::retrieveByPK($stepUid);

            $obj->setProUid($proUid);
            $obj->setStepTypeObj($stepTypeObj);
            $obj->setStepUidObj($stepUidObj);
            $obj->setStepPosition($stepPosition);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $stepUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($stepUid)
    {
        $conn = Propel::getConnection(StepSupervisorPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = StepSupervisorPeer::retrieveByPK($stepUid);
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
