<?php

class Services_Rest_Dynaform
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $dynUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($dynUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(DynaformPeer::DYN_UID);
                $criteria->addSelectColumn(DynaformPeer::PRO_UID);
                $criteria->addSelectColumn(DynaformPeer::DYN_TYPE);
                $criteria->addSelectColumn(DynaformPeer::DYN_FILENAME);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = DynaformPeer::retrieveByPK($dynUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
