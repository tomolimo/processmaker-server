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

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($proUid, $ctMapType, $ctDerivationHistory, $ctMessageHistory)
    {
        try {
            $result = array();
            $obj = new CaseTracker();

            $obj->setProUid($proUid);
            $obj->setCtMapType($ctMapType);
            $obj->setCtDerivationHistory($ctDerivationHistory);
            $obj->setCtMessageHistory($ctMessageHistory);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($proUid, $ctMapType, $ctDerivationHistory, $ctMessageHistory)
    {
        try {
            $obj = CaseTrackerPeer::retrieveByPK($proUid);

            $obj->setCtMapType($ctMapType);
            $obj->setCtDerivationHistory($ctDerivationHistory);
            $obj->setCtMessageHistory($ctMessageHistory);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($proUid)
    {
        $conn = Propel::getConnection(CaseTrackerPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = CaseTrackerPeer::retrieveByPK($proUid);
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
