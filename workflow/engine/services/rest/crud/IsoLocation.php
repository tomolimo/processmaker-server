<?php

class Services_Rest_IsoLocation
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $icUid, $ilUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($icUid=null, $ilUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(IsoLocationPeer::IC_UID);
                $criteria->addSelectColumn(IsoLocationPeer::IL_UID);
                $criteria->addSelectColumn(IsoLocationPeer::IL_NAME);
                $criteria->addSelectColumn(IsoLocationPeer::IL_NORMAL_NAME);
                $criteria->addSelectColumn(IsoLocationPeer::IS_UID);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = IsoLocationPeer::retrieveByPK($icUid, $ilUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
