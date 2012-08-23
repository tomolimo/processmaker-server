<?php

class Services_Rest_Stage
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $stgUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($stgUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(StagePeer::STG_UID);
                $criteria->addSelectColumn(StagePeer::PRO_UID);
                $criteria->addSelectColumn(StagePeer::STG_POSX);
                $criteria->addSelectColumn(StagePeer::STG_POSY);
                $criteria->addSelectColumn(StagePeer::STG_INDEX);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = StagePeer::retrieveByPK($stgUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
