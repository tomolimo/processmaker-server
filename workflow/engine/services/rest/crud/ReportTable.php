<?php

class Services_Rest_ReportTable
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $repTabUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($repTabUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_UID);
                $criteria->addSelectColumn(ReportTablePeer::PRO_UID);
                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_NAME);
                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_TYPE);
                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_GRID);
                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_CONNECTION);
                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_CREATE_DATE);
                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_STATUS);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = ReportTablePeer::retrieveByPK($repTabUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
