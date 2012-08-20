<?php

class Services_Rest_TaskUser
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $tasUid, $usrUid, $tuType, $tuRelation Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($tasUid=null, $usrUid=null, $tuType=null, $tuRelation=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(TaskUserPeer::TAS_UID);
                $criteria->addSelectColumn(TaskUserPeer::USR_UID);
                $criteria->addSelectColumn(TaskUserPeer::TU_TYPE);
                $criteria->addSelectColumn(TaskUserPeer::TU_RELATION);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = TaskUserPeer::retrieveByPK($tasUid, $usrUid, $tuType, $tuRelation);
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
     * @param  mixed $tasUid, $usrUid, $tuType, $tuRelation Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($tasUid, $usrUid, $tuType, $tuRelation)
    {
        try {
            $result = array();
            $obj = new TaskUser();

            $obj->setTasUid($tasUid);
            $obj->setUsrUid($usrUid);
            $obj->setTuType($tuType);
            $obj->setTuRelation($tuRelation);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $tasUid, $usrUid, $tuType, $tuRelation Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($tasUid, $usrUid, $tuType, $tuRelation)
    {
        try {
            $obj = TaskUserPeer::retrieveByPK($tasUid, $usrUid, $tuType, $tuRelation);

            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $tasUid, $usrUid, $tuType, $tuRelation Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($tasUid, $usrUid, $tuType, $tuRelation)
    {
        $conn = Propel::getConnection(TaskUserPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = TaskUserPeer::retrieveByPK($tasUid, $usrUid, $tuType, $tuRelation);
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
