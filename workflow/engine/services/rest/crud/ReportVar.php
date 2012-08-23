<?php

class Services_Rest_ReportVar
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $repVarUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($repVarUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(ReportVarPeer::REP_VAR_UID);
                $criteria->addSelectColumn(ReportVarPeer::PRO_UID);
                $criteria->addSelectColumn(ReportVarPeer::REP_TAB_UID);
                $criteria->addSelectColumn(ReportVarPeer::REP_VAR_NAME);
                $criteria->addSelectColumn(ReportVarPeer::REP_VAR_TYPE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = ReportVarPeer::retrieveByPK($repVarUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
