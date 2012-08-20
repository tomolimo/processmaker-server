<?php

class Services_Rest_ProcessOwner
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $ownUid, $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($ownUid=null, $proUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(ProcessOwnerPeer::OWN_UID);
                $criteria->addSelectColumn(ProcessOwnerPeer::PRO_UID);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = ProcessOwnerPeer::retrieveByPK($ownUid, $proUid);
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
     * @param  mixed $ownUid, $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($ownUid, $proUid)
    {
        try {
            $result = array();
            $obj = new ProcessOwner();

            $obj->setOwnUid($ownUid);
            $obj->setProUid($proUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $ownUid, $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($ownUid, $proUid)
    {
        try {
            $obj = ProcessOwnerPeer::retrieveByPK($ownUid, $proUid);

            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $ownUid, $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($ownUid, $proUid)
    {
        $conn = Propel::getConnection(ProcessOwnerPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = ProcessOwnerPeer::retrieveByPK($ownUid, $proUid);
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
