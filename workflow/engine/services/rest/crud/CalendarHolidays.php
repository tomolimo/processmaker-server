<?php

class Services_Rest_CalendarHolidays
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $calendarUid, $calendarHolidayName Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($calendarUid=null, $calendarHolidayName=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(CalendarHolidaysPeer::CALENDAR_UID);
                $criteria->addSelectColumn(CalendarHolidaysPeer::CALENDAR_HOLIDAY_NAME);
                $criteria->addSelectColumn(CalendarHolidaysPeer::CALENDAR_HOLIDAY_START);
                $criteria->addSelectColumn(CalendarHolidaysPeer::CALENDAR_HOLIDAY_END);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = CalendarHolidaysPeer::retrieveByPK($calendarUid, $calendarHolidayName);
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
     * @param  mixed $calendarUid, $calendarHolidayName Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($calendarUid, $calendarHolidayName, $calendarHolidayStart, $calendarHolidayEnd)
    {
        try {
            $result = array();
            $obj = new CalendarHolidays();

            $obj->setCalendarUid($calendarUid);
            $obj->setCalendarHolidayName($calendarHolidayName);
            $obj->setCalendarHolidayStart($calendarHolidayStart);
            $obj->setCalendarHolidayEnd($calendarHolidayEnd);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $calendarUid, $calendarHolidayName Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($calendarUid, $calendarHolidayName, $calendarHolidayStart, $calendarHolidayEnd)
    {
        try {
            $obj = CalendarHolidaysPeer::retrieveByPK($calendarUid, $calendarHolidayName);

            $obj->setCalendarHolidayStart($calendarHolidayStart);
            $obj->setCalendarHolidayEnd($calendarHolidayEnd);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $calendarUid, $calendarHolidayName Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($calendarUid, $calendarHolidayName)
    {
        $conn = Propel::getConnection(CalendarHolidaysPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = CalendarHolidaysPeer::retrieveByPK($calendarUid, $calendarHolidayName);
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
