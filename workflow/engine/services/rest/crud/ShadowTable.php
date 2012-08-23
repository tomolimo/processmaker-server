<?php

class Services_Rest_ShadowTable
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $shdUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($shdUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(ShadowTablePeer::SHD_UID);
                $criteria->addSelectColumn(ShadowTablePeer::ADD_TAB_UID);
                $criteria->addSelectColumn(ShadowTablePeer::SHD_ACTION);
                $criteria->addSelectColumn(ShadowTablePeer::SHD_DETAILS);
                $criteria->addSelectColumn(ShadowTablePeer::USR_UID);
                $criteria->addSelectColumn(ShadowTablePeer::APP_UID);
                $criteria->addSelectColumn(ShadowTablePeer::SHD_DATE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = ShadowTablePeer::retrieveByPK($shdUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
