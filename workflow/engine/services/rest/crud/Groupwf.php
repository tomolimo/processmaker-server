<?php

class Services_Rest_Groupwf
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $grpUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($grpUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(GroupwfPeer::GRP_UID);
                $criteria->addSelectColumn(GroupwfPeer::GRP_STATUS);
                $criteria->addSelectColumn(GroupwfPeer::GRP_LDAP_DN);
                $criteria->addSelectColumn(GroupwfPeer::GRP_UX);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = GroupwfPeer::retrieveByPK($grpUid);
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
     * @param  mixed $grpUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($grpUid, $grpStatus, $grpLdapDn, $grpUx)
    {
        try {
            $result = array();
            $obj = new Groupwf();

            $obj->setGrpUid($grpUid);
            $obj->setGrpStatus($grpStatus);
            $obj->setGrpLdapDn($grpLdapDn);
            $obj->setGrpUx($grpUx);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $grpUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($grpUid, $grpStatus, $grpLdapDn, $grpUx)
    {
        try {
            $obj = GroupwfPeer::retrieveByPK($grpUid);

            $obj->setGrpStatus($grpStatus);
            $obj->setGrpLdapDn($grpLdapDn);
            $obj->setGrpUx($grpUx);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $grpUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($grpUid)
    {
        $conn = Propel::getConnection(GroupwfPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = GroupwfPeer::retrieveByPK($grpUid);
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
