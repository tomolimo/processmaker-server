<?php

class Services_Rest_IsoCountry
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $icUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($icUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(IsoCountryPeer::IC_UID);
                $criteria->addSelectColumn(IsoCountryPeer::IC_NAME);
                $criteria->addSelectColumn(IsoCountryPeer::IC_SORT_ORDER);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = IsoCountryPeer::retrieveByPK($icUid);
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
     * @param  mixed $icUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($icUid, $icName, $icSortOrder)
    {
        try {
            $result = array();
            $obj = new IsoCountry();

            $obj->setIcUid($icUid);
            $obj->setIcName($icName);
            $obj->setIcSortOrder($icSortOrder);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $icUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($icUid, $icName, $icSortOrder)
    {
        try {
            $obj = IsoCountryPeer::retrieveByPK($icUid);

            $obj->setIcName($icName);
            $obj->setIcSortOrder($icSortOrder);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $icUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($icUid)
    {
        $conn = Propel::getConnection(IsoCountryPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = IsoCountryPeer::retrieveByPK($icUid);
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
