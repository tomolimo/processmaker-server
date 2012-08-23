<?php

class Services_Rest_Process
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($proUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(ProcessPeer::PRO_UID);
                $criteria->addSelectColumn(ProcessPeer::PRO_PARENT);
                $criteria->addSelectColumn(ProcessPeer::PRO_TIME);
                $criteria->addSelectColumn(ProcessPeer::PRO_TIMEUNIT);
                $criteria->addSelectColumn(ProcessPeer::PRO_STATUS);
                $criteria->addSelectColumn(ProcessPeer::PRO_TYPE_DAY);
                $criteria->addSelectColumn(ProcessPeer::PRO_TYPE);
                $criteria->addSelectColumn(ProcessPeer::PRO_ASSIGNMENT);
                $criteria->addSelectColumn(ProcessPeer::PRO_SHOW_MAP);
                $criteria->addSelectColumn(ProcessPeer::PRO_SHOW_MESSAGE);
                $criteria->addSelectColumn(ProcessPeer::PRO_SHOW_DELEGATE);
                $criteria->addSelectColumn(ProcessPeer::PRO_SHOW_DYNAFORM);
                $criteria->addSelectColumn(ProcessPeer::PRO_CATEGORY);
                $criteria->addSelectColumn(ProcessPeer::PRO_SUB_CATEGORY);
                $criteria->addSelectColumn(ProcessPeer::PRO_INDUSTRY);
                $criteria->addSelectColumn(ProcessPeer::PRO_UPDATE_DATE);
                $criteria->addSelectColumn(ProcessPeer::PRO_CREATE_DATE);
                $criteria->addSelectColumn(ProcessPeer::PRO_CREATE_USER);
                $criteria->addSelectColumn(ProcessPeer::PRO_HEIGHT);
                $criteria->addSelectColumn(ProcessPeer::PRO_WIDTH);
                $criteria->addSelectColumn(ProcessPeer::PRO_TITLE_X);
                $criteria->addSelectColumn(ProcessPeer::PRO_TITLE_Y);
                $criteria->addSelectColumn(ProcessPeer::PRO_DEBUG);
                $criteria->addSelectColumn(ProcessPeer::PRO_DYNAFORMS);
                $criteria->addSelectColumn(ProcessPeer::PRO_DERIVATION_SCREEN_TPL);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = ProcessPeer::retrieveByPK($proUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
