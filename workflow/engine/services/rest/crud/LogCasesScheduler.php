<?php

class Services_Rest_LogCasesScheduler
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $logCaseUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($logCaseUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(LogCasesSchedulerPeer::LOG_CASE_UID);
                $criteria->addSelectColumn(LogCasesSchedulerPeer::PRO_UID);
                $criteria->addSelectColumn(LogCasesSchedulerPeer::TAS_UID);
                $criteria->addSelectColumn(LogCasesSchedulerPeer::USR_NAME);
                $criteria->addSelectColumn(LogCasesSchedulerPeer::EXEC_DATE);
                $criteria->addSelectColumn(LogCasesSchedulerPeer::EXEC_HOUR);
                $criteria->addSelectColumn(LogCasesSchedulerPeer::RESULT);
                $criteria->addSelectColumn(LogCasesSchedulerPeer::SCH_UID);
                $criteria->addSelectColumn(LogCasesSchedulerPeer::WS_CREATE_CASE_STATUS);
                $criteria->addSelectColumn(LogCasesSchedulerPeer::WS_ROUTE_CASE_STATUS);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = LogCasesSchedulerPeer::retrieveByPK($logCaseUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
