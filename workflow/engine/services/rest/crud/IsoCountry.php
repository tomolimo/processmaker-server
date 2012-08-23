<?php

class Services_Rest_IsoCountry
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $icUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($icUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(IsoCountryPeer::IC_UID);
                $criteria->addSelectColumn(IsoCountryPeer::IC_NAME);
                $criteria->addSelectColumn(IsoCountryPeer::IC_SORT_ORDER);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = IsoCountryPeer::retrieveByPK($icUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
