<?php

class Services_Rest_SubProcess
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $spUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($spUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(SubProcessPeer::SP_UID);
                $criteria->addSelectColumn(SubProcessPeer::PRO_UID);
                $criteria->addSelectColumn(SubProcessPeer::TAS_UID);
                $criteria->addSelectColumn(SubProcessPeer::PRO_PARENT);
                $criteria->addSelectColumn(SubProcessPeer::TAS_PARENT);
                $criteria->addSelectColumn(SubProcessPeer::SP_TYPE);
                $criteria->addSelectColumn(SubProcessPeer::SP_SYNCHRONOUS);
                $criteria->addSelectColumn(SubProcessPeer::SP_SYNCHRONOUS_TYPE);
                $criteria->addSelectColumn(SubProcessPeer::SP_SYNCHRONOUS_WAIT);
                $criteria->addSelectColumn(SubProcessPeer::SP_VARIABLES_OUT);
                $criteria->addSelectColumn(SubProcessPeer::SP_VARIABLES_IN);
                $criteria->addSelectColumn(SubProcessPeer::SP_GRID_IN);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = SubProcessPeer::retrieveByPK($spUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
