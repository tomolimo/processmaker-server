<?php

class Services_Rest_ProcessUser
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $puUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($puUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(ProcessUserPeer::PU_UID);
                $criteria->addSelectColumn(ProcessUserPeer::PRO_UID);
                $criteria->addSelectColumn(ProcessUserPeer::USR_UID);
                $criteria->addSelectColumn(ProcessUserPeer::PU_TYPE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = ProcessUserPeer::retrieveByPK($puUid);
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
     * @param  mixed $puUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($puUid, $proUid, $usrUid, $puType)
    {
        try {
            $result = array();
            $obj = new ProcessUser();

            $obj->setPuUid($puUid);
            $obj->setProUid($proUid);
            $obj->setUsrUid($usrUid);
            $obj->setPuType($puType);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $puUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($puUid, $proUid, $usrUid, $puType)
    {
        try {
            $obj = ProcessUserPeer::retrieveByPK($puUid);

            $obj->setProUid($proUid);
            $obj->setUsrUid($usrUid);
            $obj->setPuType($puType);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $puUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($puUid)
    {
        $conn = Propel::getConnection(ProcessUserPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = ProcessUserPeer::retrieveByPK($puUid);
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
