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


}
