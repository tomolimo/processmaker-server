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

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed $stepUid, $tasUid, $triUid, $stType Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($stepUid, $tasUid, $triUid, $stType, $stCondition, $stPosition)
    {
        try {
            $result = array();
            $obj = new StepTrigger();

            $obj->setStepUid($stepUid);
            $obj->setTasUid($tasUid);
            $obj->setTriUid($triUid);
            $obj->setStType($stType);
            $obj->setStCondition($stCondition);
            $obj->setStPosition($stPosition);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $stepUid, $tasUid, $triUid, $stType Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($stepUid, $tasUid, $triUid, $stType, $stCondition, $stPosition)
    {
        try {
            $obj = StepTriggerPeer::retrieveByPK($stepUid, $tasUid, $triUid, $stType);

            $obj->setStCondition($stCondition);
            $obj->setStPosition($stPosition);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $stepUid, $tasUid, $triUid, $stType Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($stepUid, $tasUid, $triUid, $stType)
    {
        $conn = Propel::getConnection(StepTriggerPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = StepTriggerPeer::retrieveByPK($stepUid, $tasUid, $triUid, $stType);
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
