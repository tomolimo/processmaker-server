<?php

class Services_Rest_Event
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $evnUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($evnUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(EventPeer::EVN_UID);
                $criteria->addSelectColumn(EventPeer::PRO_UID);
                $criteria->addSelectColumn(EventPeer::EVN_STATUS);
                $criteria->addSelectColumn(EventPeer::EVN_WHEN_OCCURS);
                $criteria->addSelectColumn(EventPeer::EVN_RELATED_TO);
                $criteria->addSelectColumn(EventPeer::TAS_UID);
                $criteria->addSelectColumn(EventPeer::EVN_TAS_UID_FROM);
                $criteria->addSelectColumn(EventPeer::EVN_TAS_UID_TO);
                $criteria->addSelectColumn(EventPeer::EVN_TAS_ESTIMATED_DURATION);
                $criteria->addSelectColumn(EventPeer::EVN_WHEN);
                $criteria->addSelectColumn(EventPeer::EVN_MAX_ATTEMPTS);
                $criteria->addSelectColumn(EventPeer::EVN_ACTION);
                $criteria->addSelectColumn(EventPeer::EVN_CONDITIONS);
                $criteria->addSelectColumn(EventPeer::EVN_ACTION_PARAMETERS);
                $criteria->addSelectColumn(EventPeer::TRI_UID);
                $criteria->addSelectColumn(EventPeer::EVN_POSX);
                $criteria->addSelectColumn(EventPeer::EVN_POSY);
                $criteria->addSelectColumn(EventPeer::EVN_TYPE);
                $criteria->addSelectColumn(EventPeer::TAS_EVN_UID);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = EventPeer::retrieveByPK($evnUid);
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
     * @param  mixed $evnUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($evnUid, $proUid, $evnStatus, $evnWhenOccurs, $evnRelatedTo, $tasUid, $evnTasUidFrom, $evnTasUidTo, $evnTasEstimatedDuration, $evnWhen, $evnMaxAttempts, $evnAction, $evnConditions, $evnActionParameters, $triUid, $evnPosx, $evnPosy, $evnType, $tasEvnUid)
    {
        try {
            $result = array();
            $obj = new Event();

            $obj->setEvnUid($evnUid);
            $obj->setProUid($proUid);
            $obj->setEvnStatus($evnStatus);
            $obj->setEvnWhenOccurs($evnWhenOccurs);
            $obj->setEvnRelatedTo($evnRelatedTo);
            $obj->setTasUid($tasUid);
            $obj->setEvnTasUidFrom($evnTasUidFrom);
            $obj->setEvnTasUidTo($evnTasUidTo);
            $obj->setEvnTasEstimatedDuration($evnTasEstimatedDuration);
            $obj->setEvnWhen($evnWhen);
            $obj->setEvnMaxAttempts($evnMaxAttempts);
            $obj->setEvnAction($evnAction);
            $obj->setEvnConditions($evnConditions);
            $obj->setEvnActionParameters($evnActionParameters);
            $obj->setTriUid($triUid);
            $obj->setEvnPosx($evnPosx);
            $obj->setEvnPosy($evnPosy);
            $obj->setEvnType($evnType);
            $obj->setTasEvnUid($tasEvnUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $evnUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($evnUid, $proUid, $evnStatus, $evnWhenOccurs, $evnRelatedTo, $tasUid, $evnTasUidFrom, $evnTasUidTo, $evnTasEstimatedDuration, $evnWhen, $evnMaxAttempts, $evnAction, $evnConditions, $evnActionParameters, $triUid, $evnPosx, $evnPosy, $evnType, $tasEvnUid)
    {
        try {
            $obj = EventPeer::retrieveByPK($evnUid);

            $obj->setProUid($proUid);
            $obj->setEvnStatus($evnStatus);
            $obj->setEvnWhenOccurs($evnWhenOccurs);
            $obj->setEvnRelatedTo($evnRelatedTo);
            $obj->setTasUid($tasUid);
            $obj->setEvnTasUidFrom($evnTasUidFrom);
            $obj->setEvnTasUidTo($evnTasUidTo);
            $obj->setEvnTasEstimatedDuration($evnTasEstimatedDuration);
            $obj->setEvnWhen($evnWhen);
            $obj->setEvnMaxAttempts($evnMaxAttempts);
            $obj->setEvnAction($evnAction);
            $obj->setEvnConditions($evnConditions);
            $obj->setEvnActionParameters($evnActionParameters);
            $obj->setTriUid($triUid);
            $obj->setEvnPosx($evnPosx);
            $obj->setEvnPosy($evnPosy);
            $obj->setEvnType($evnType);
            $obj->setTasEvnUid($tasEvnUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $evnUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($evnUid)
    {
        $conn = Propel::getConnection(EventPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = EventPeer::retrieveByPK($evnUid);
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
