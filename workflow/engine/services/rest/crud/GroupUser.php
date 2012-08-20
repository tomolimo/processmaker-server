<?php

class Services_Rest_GroupUser
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $grpUid, $usrUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($grpUid=null, $usrUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(GroupUserPeer::GRP_UID);
                $criteria->addSelectColumn(GroupUserPeer::USR_UID);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = GroupUserPeer::retrieveByPK($grpUid, $usrUid);
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
     * @param  mixed $grpUid, $usrUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($grpUid, $usrUid)
    {
        try {
            $result = array();
            $obj = new GroupUser();

            $obj->setGrpUid($grpUid);
            $obj->setUsrUid($usrUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $grpUid, $usrUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($grpUid, $usrUid)
    {
        try {
            $obj = GroupUserPeer::retrieveByPK($grpUid, $usrUid);

            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $grpUid, $usrUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($grpUid, $usrUid)
    {
        $conn = Propel::getConnection(GroupUserPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = GroupUserPeer::retrieveByPK($grpUid, $usrUid);
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
