<?php

class Services_Rest_FieldCondition
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $fcdUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($fcdUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(FieldConditionPeer::FCD_UID);
                $criteria->addSelectColumn(FieldConditionPeer::FCD_FUNCTION);
                $criteria->addSelectColumn(FieldConditionPeer::FCD_FIELDS);
                $criteria->addSelectColumn(FieldConditionPeer::FCD_CONDITION);
                $criteria->addSelectColumn(FieldConditionPeer::FCD_EVENTS);
                $criteria->addSelectColumn(FieldConditionPeer::FCD_EVENT_OWNERS);
                $criteria->addSelectColumn(FieldConditionPeer::FCD_STATUS);
                $criteria->addSelectColumn(FieldConditionPeer::FCD_DYN_UID);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = FieldConditionPeer::retrieveByPK($fcdUid);
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
     * @param  mixed $fcdUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($fcdUid, $fcdFunction, $fcdFields, $fcdCondition, $fcdEvents, $fcdEventOwners, $fcdStatus, $fcdDynUid)
    {
        try {
            $result = array();
            $obj = new FieldCondition();

            $obj->setFcdUid($fcdUid);
            $obj->setFcdFunction($fcdFunction);
            $obj->setFcdFields($fcdFields);
            $obj->setFcdCondition($fcdCondition);
            $obj->setFcdEvents($fcdEvents);
            $obj->setFcdEventOwners($fcdEventOwners);
            $obj->setFcdStatus($fcdStatus);
            $obj->setFcdDynUid($fcdDynUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $fcdUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($fcdUid, $fcdFunction, $fcdFields, $fcdCondition, $fcdEvents, $fcdEventOwners, $fcdStatus, $fcdDynUid)
    {
        try {
            $obj = FieldConditionPeer::retrieveByPK($fcdUid);

            $obj->setFcdFunction($fcdFunction);
            $obj->setFcdFields($fcdFields);
            $obj->setFcdCondition($fcdCondition);
            $obj->setFcdEvents($fcdEvents);
            $obj->setFcdEventOwners($fcdEventOwners);
            $obj->setFcdStatus($fcdStatus);
            $obj->setFcdDynUid($fcdDynUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $fcdUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($fcdUid)
    {
        $conn = Propel::getConnection(FieldConditionPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = FieldConditionPeer::retrieveByPK($fcdUid);
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
