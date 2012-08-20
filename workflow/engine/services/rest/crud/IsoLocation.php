<?php

class Services_Rest_IsoLocation
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $icUid, $ilUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($icUid=null, $ilUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(IsoLocationPeer::IC_UID);
                $criteria->addSelectColumn(IsoLocationPeer::IL_UID);
                $criteria->addSelectColumn(IsoLocationPeer::IL_NAME);
                $criteria->addSelectColumn(IsoLocationPeer::IL_NORMAL_NAME);
                $criteria->addSelectColumn(IsoLocationPeer::IS_UID);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = IsoLocationPeer::retrieveByPK($icUid, $ilUid);
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
     * @param  mixed $icUid, $ilUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($icUid, $ilUid, $ilName, $ilNormalName, $isUid)
    {
        try {
            $result = array();
            $obj = new IsoLocation();

            $obj->setIcUid($icUid);
            $obj->setIlUid($ilUid);
            $obj->setIlName($ilName);
            $obj->setIlNormalName($ilNormalName);
            $obj->setIsUid($isUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $icUid, $ilUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($icUid, $ilUid, $ilName, $ilNormalName, $isUid)
    {
        try {
            $obj = IsoLocationPeer::retrieveByPK($icUid, $ilUid);

            $obj->setIlName($ilName);
            $obj->setIlNormalName($ilNormalName);
            $obj->setIsUid($isUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $icUid, $ilUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($icUid, $ilUid)
    {
        $conn = Propel::getConnection(IsoLocationPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = IsoLocationPeer::retrieveByPK($icUid, $ilUid);
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
