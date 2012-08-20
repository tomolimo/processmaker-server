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

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed $ctoUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($ctoUid, $proUid, $ctoTypeObj, $ctoUidObj, $ctoCondition, $ctoPosition)
    {
        try {
            $result = array();
            $obj = new CaseTrackerObject();

            $obj->setCtoUid($ctoUid);
            $obj->setProUid($proUid);
            $obj->setCtoTypeObj($ctoTypeObj);
            $obj->setCtoUidObj($ctoUidObj);
            $obj->setCtoCondition($ctoCondition);
            $obj->setCtoPosition($ctoPosition);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $ctoUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($ctoUid, $proUid, $ctoTypeObj, $ctoUidObj, $ctoCondition, $ctoPosition)
    {
        try {
            $obj = CaseTrackerObjectPeer::retrieveByPK($ctoUid);

            $obj->setProUid($proUid);
            $obj->setCtoTypeObj($ctoTypeObj);
            $obj->setCtoUidObj($ctoUidObj);
            $obj->setCtoCondition($ctoCondition);
            $obj->setCtoPosition($ctoPosition);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $ctoUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($ctoUid)
    {
        $conn = Propel::getConnection(CaseTrackerObjectPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = CaseTrackerObjectPeer::retrieveByPK($ctoUid);
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
