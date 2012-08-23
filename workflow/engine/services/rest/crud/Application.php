<?php

class Services_Rest_Application
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $appUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($appUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(ApplicationPeer::APP_UID);
                $criteria->addSelectColumn(ApplicationPeer::APP_NUMBER);
                $criteria->addSelectColumn(ApplicationPeer::APP_PARENT);
                $criteria->addSelectColumn(ApplicationPeer::APP_STATUS);
                $criteria->addSelectColumn(ApplicationPeer::PRO_UID);
                $criteria->addSelectColumn(ApplicationPeer::APP_PROC_STATUS);
                $criteria->addSelectColumn(ApplicationPeer::APP_PROC_CODE);
                $criteria->addSelectColumn(ApplicationPeer::APP_PARALLEL);
                $criteria->addSelectColumn(ApplicationPeer::APP_INIT_USER);
                $criteria->addSelectColumn(ApplicationPeer::APP_CUR_USER);
                $criteria->addSelectColumn(ApplicationPeer::APP_CREATE_DATE);
                $criteria->addSelectColumn(ApplicationPeer::APP_INIT_DATE);
                $criteria->addSelectColumn(ApplicationPeer::APP_FINISH_DATE);
                $criteria->addSelectColumn(ApplicationPeer::APP_UPDATE_DATE);
                $criteria->addSelectColumn(ApplicationPeer::APP_DATA);
                $criteria->addSelectColumn(ApplicationPeer::APP_PIN);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = ApplicationPeer::retrieveByPK($appUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
