<?php

class Services_Rest_CalendarBusinessHours
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $calendarUid, $calendarBusinessDay, $calendarBusinessStart, $calendarBusinessEnd Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($calendarUid=null, $calendarBusinessDay=null, $calendarBusinessStart=null, $calendarBusinessEnd=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(CalendarBusinessHoursPeer::CALENDAR_UID);
                $criteria->addSelectColumn(CalendarBusinessHoursPeer::CALENDAR_BUSINESS_DAY);
                $criteria->addSelectColumn(CalendarBusinessHoursPeer::CALENDAR_BUSINESS_START);
                $criteria->addSelectColumn(CalendarBusinessHoursPeer::CALENDAR_BUSINESS_END);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = CalendarBusinessHoursPeer::retrieveByPK($calendarUid, $calendarBusinessDay, $calendarBusinessStart, $calendarBusinessEnd);
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
     * @param  mixed $calendarUid, $calendarBusinessDay, $calendarBusinessStart, $calendarBusinessEnd Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($calendarUid, $calendarBusinessDay, $calendarBusinessStart, $calendarBusinessEnd)
    {
        try {
            $result = array();
            $obj = new CalendarBusinessHours();

            $obj->setCalendarUid($calendarUid);
            $obj->setCalendarBusinessDay($calendarBusinessDay);
            $obj->setCalendarBusinessStart($calendarBusinessStart);
            $obj->setCalendarBusinessEnd($calendarBusinessEnd);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $calendarUid, $calendarBusinessDay, $calendarBusinessStart, $calendarBusinessEnd Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($calendarUid, $calendarBusinessDay, $calendarBusinessStart, $calendarBusinessEnd)
    {
        try {
            $obj = CalendarBusinessHoursPeer::retrieveByPK($calendarUid, $calendarBusinessDay, $calendarBusinessStart, $calendarBusinessEnd);

            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $calendarUid, $calendarBusinessDay, $calendarBusinessStart, $calendarBusinessEnd Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($calendarUid, $calendarBusinessDay, $calendarBusinessStart, $calendarBusinessEnd)
    {
        $conn = Propel::getConnection(CalendarBusinessHoursPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = CalendarBusinessHoursPeer::retrieveByPK($calendarUid, $calendarBusinessDay, $calendarBusinessStart, $calendarBusinessEnd);
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
