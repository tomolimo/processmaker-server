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

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed $opUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($opUid, $proUid, $tasUid, $usrUid, $opUserRelation, $opTaskSource, $opParticipate, $opObjType, $opObjUid, $opAction, $opCaseStatus)
    {
        try {
            $result = array();
            $obj = new ObjectPermission();

            $obj->setOpUid($opUid);
            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setUsrUid($usrUid);
            $obj->setOpUserRelation($opUserRelation);
            $obj->setOpTaskSource($opTaskSource);
            $obj->setOpParticipate($opParticipate);
            $obj->setOpObjType($opObjType);
            $obj->setOpObjUid($opObjUid);
            $obj->setOpAction($opAction);
            $obj->setOpCaseStatus($opCaseStatus);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $opUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($opUid, $proUid, $tasUid, $usrUid, $opUserRelation, $opTaskSource, $opParticipate, $opObjType, $opObjUid, $opAction, $opCaseStatus)
    {
        try {
            $obj = ObjectPermissionPeer::retrieveByPK($opUid);

            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setUsrUid($usrUid);
            $obj->setOpUserRelation($opUserRelation);
            $obj->setOpTaskSource($opTaskSource);
            $obj->setOpParticipate($opParticipate);
            $obj->setOpObjType($opObjType);
            $obj->setOpObjUid($opObjUid);
            $obj->setOpAction($opAction);
            $obj->setOpCaseStatus($opCaseStatus);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $opUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($opUid)
    {
        $conn = Propel::getConnection(ObjectPermissionPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = ObjectPermissionPeer::retrieveByPK($opUid);
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
