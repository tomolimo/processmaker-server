<?php

class Services_Rest_Language
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $lanId Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($lanId=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(LanguagePeer::LAN_ID);
                $criteria->addSelectColumn(LanguagePeer::LAN_NAME);
                $criteria->addSelectColumn(LanguagePeer::LAN_NATIVE_NAME);
                $criteria->addSelectColumn(LanguagePeer::LAN_DIRECTION);
                $criteria->addSelectColumn(LanguagePeer::LAN_WEIGHT);
                $criteria->addSelectColumn(LanguagePeer::LAN_ENABLED);
                $criteria->addSelectColumn(LanguagePeer::LAN_CALENDAR);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = LanguagePeer::retrieveByPK($lanId);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
