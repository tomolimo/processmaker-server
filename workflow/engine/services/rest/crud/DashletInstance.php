<?php

class Services_Rest_DashletInstance
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $dasInsUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($dasInsUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_UID);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_UID);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_OWNER_TYPE);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_OWNER_UID);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_ADDITIONAL_PROPERTIES);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_CREATE_DATE);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_UPDATE_DATE);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_STATUS);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = DashletInstancePeer::retrieveByPK($dasInsUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
