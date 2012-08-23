<?php

class Services_Rest_IsoSubdivision
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $icUid, $isUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($icUid=null, $isUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(IsoSubdivisionPeer::IC_UID);
                $criteria->addSelectColumn(IsoSubdivisionPeer::IS_UID);
                $criteria->addSelectColumn(IsoSubdivisionPeer::IS_NAME);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = IsoSubdivisionPeer::retrieveByPK($icUid, $isUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
