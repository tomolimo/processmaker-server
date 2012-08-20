<?php

class Services_Rest_CaseScheduler
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $schUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($schUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_UID);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_DEL_USER_NAME);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_DEL_USER_PASS);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_DEL_USER_UID);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_NAME);
                $criteria->addSelectColumn(CaseSchedulerPeer::PRO_UID);
                $criteria->addSelectColumn(CaseSchedulerPeer::TAS_UID);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_TIME_NEXT_RUN);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_LAST_RUN_TIME);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_STATE);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_LAST_STATE);
                $criteria->addSelectColumn(CaseSchedulerPeer::USR_UID);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_OPTION);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_START_TIME);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_START_DATE);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_DAYS_PERFORM_TASK);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_EVERY_DAYS);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_WEEK_DAYS);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_START_DAY);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_MONTHS);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_END_DATE);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_REPEAT_EVERY);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_REPEAT_UNTIL);
                $criteria->addSelectColumn(CaseSchedulerPeer::SCH_REPEAT_STOP_IF_RUNNING);
                $criteria->addSelectColumn(CaseSchedulerPeer::CASE_SH_PLUGIN_UID);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = CaseSchedulerPeer::retrieveByPK($schUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed $schUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($schUid, $schDelUserName, $schDelUserPass, $schDelUserUid, $schName, $proUid, $tasUid, $schTimeNextRun, $schLastRunTime, $schState, $schLastState, $usrUid, $schOption, $schStartTime, $schStartDate, $schDaysPerformTask, $schEveryDays, $schWeekDays, $schStartDay, $schMonths, $schEndDate, $schRepeatEvery, $schRepeatUntil, $schRepeatStopIfRunning, $caseShPluginUid)
    {
        try {
            $result = array();
            $obj = new CaseScheduler();

            $obj->setSchUid($schUid);
            $obj->setSchDelUserName($schDelUserName);
            $obj->setSchDelUserPass($schDelUserPass);
            $obj->setSchDelUserUid($schDelUserUid);
            $obj->setSchName($schName);
            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setSchTimeNextRun($schTimeNextRun);
            $obj->setSchLastRunTime($schLastRunTime);
            $obj->setSchState($schState);
            $obj->setSchLastState($schLastState);
            $obj->setUsrUid($usrUid);
            $obj->setSchOption($schOption);
            $obj->setSchStartTime($schStartTime);
            $obj->setSchStartDate($schStartDate);
            $obj->setSchDaysPerformTask($schDaysPerformTask);
            $obj->setSchEveryDays($schEveryDays);
            $obj->setSchWeekDays($schWeekDays);
            $obj->setSchStartDay($schStartDay);
            $obj->setSchMonths($schMonths);
            $obj->setSchEndDate($schEndDate);
            $obj->setSchRepeatEvery($schRepeatEvery);
            $obj->setSchRepeatUntil($schRepeatUntil);
            $obj->setSchRepeatStopIfRunning($schRepeatStopIfRunning);
            $obj->setCaseShPluginUid($caseShPluginUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $schUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($schUid, $schDelUserName, $schDelUserPass, $schDelUserUid, $schName, $proUid, $tasUid, $schTimeNextRun, $schLastRunTime, $schState, $schLastState, $usrUid, $schOption, $schStartTime, $schStartDate, $schDaysPerformTask, $schEveryDays, $schWeekDays, $schStartDay, $schMonths, $schEndDate, $schRepeatEvery, $schRepeatUntil, $schRepeatStopIfRunning, $caseShPluginUid)
    {
        try {
            $obj = CaseSchedulerPeer::retrieveByPK($schUid);

            $obj->setSchDelUserName($schDelUserName);
            $obj->setSchDelUserPass($schDelUserPass);
            $obj->setSchDelUserUid($schDelUserUid);
            $obj->setSchName($schName);
            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setSchTimeNextRun($schTimeNextRun);
            $obj->setSchLastRunTime($schLastRunTime);
            $obj->setSchState($schState);
            $obj->setSchLastState($schLastState);
            $obj->setUsrUid($usrUid);
            $obj->setSchOption($schOption);
            $obj->setSchStartTime($schStartTime);
            $obj->setSchStartDate($schStartDate);
            $obj->setSchDaysPerformTask($schDaysPerformTask);
            $obj->setSchEveryDays($schEveryDays);
            $obj->setSchWeekDays($schWeekDays);
            $obj->setSchStartDay($schStartDay);
            $obj->setSchMonths($schMonths);
            $obj->setSchEndDate($schEndDate);
            $obj->setSchRepeatEvery($schRepeatEvery);
            $obj->setSchRepeatUntil($schRepeatUntil);
            $obj->setSchRepeatStopIfRunning($schRepeatStopIfRunning);
            $obj->setCaseShPluginUid($caseShPluginUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $schUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($schUid)
    {
        $conn = Propel::getConnection(CaseSchedulerPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = CaseSchedulerPeer::retrieveByPK($schUid);
            if (! is_object($obj)) {
                throw new RestException(412, 'Record does not exist.');
            }
            $obj->delete();
        
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            throw new RestException(412, $e->getMessage());
        }
    }


}
