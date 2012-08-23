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


}
