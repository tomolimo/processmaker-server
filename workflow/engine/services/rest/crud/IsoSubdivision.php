<?php

class Services_Rest_IsoSubdivision
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $icUid, $isUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($icUid=null, $isUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(IsoSubdivisionPeer::IC_UID);
                $criteria->addSelectColumn(IsoSubdivisionPeer::IS_UID);
                $criteria->addSelectColumn(IsoSubdivisionPeer::IS_NAME);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = IsoSubdivisionPeer::retrieveByPK($icUid, $isUid);
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
     * @param  mixed $icUid, $isUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($icUid, $isUid, $isName)
    {
        try {
            $result = array();
            $obj = new IsoSubdivision();

            $obj->setIcUid($icUid);
            $obj->setIsUid($isUid);
            $obj->setIsName($isName);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $icUid, $isUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($icUid, $isUid, $isName)
    {
        try {
            $obj = IsoSubdivisionPeer::retrieveByPK($icUid, $isUid);

            $obj->setIsName($isName);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $icUid, $isUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($icUid, $isUid)
    {
        $conn = Propel::getConnection(IsoSubdivisionPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = IsoSubdivisionPeer::retrieveByPK($icUid, $isUid);
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
