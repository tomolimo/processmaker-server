<?php

class Services_Rest_ObjectPermission
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $opUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($opUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(ObjectPermissionPeer::OP_UID);
                $criteria->addSelectColumn(ObjectPermissionPeer::PRO_UID);
                $criteria->addSelectColumn(ObjectPermissionPeer::TAS_UID);
                $criteria->addSelectColumn(ObjectPermissionPeer::USR_UID);
                $criteria->addSelectColumn(ObjectPermissionPeer::OP_USER_RELATION);
                $criteria->addSelectColumn(ObjectPermissionPeer::OP_TASK_SOURCE);
                $criteria->addSelectColumn(ObjectPermissionPeer::OP_PARTICIPATE);
                $criteria->addSelectColumn(ObjectPermissionPeer::OP_OBJ_TYPE);
                $criteria->addSelectColumn(ObjectPermissionPeer::OP_OBJ_UID);
                $criteria->addSelectColumn(ObjectPermissionPeer::OP_ACTION);
                $criteria->addSelectColumn(ObjectPermissionPeer::OP_CASE_STATUS);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = ObjectPermissionPeer::retrieveByPK($opUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
