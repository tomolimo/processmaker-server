<?php

class Services_Rest_CalendarDefinition
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $calendarUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($calendarUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(CalendarDefinitionPeer::CALENDAR_UID);
                $criteria->addSelectColumn(CalendarDefinitionPeer::CALENDAR_NAME);
                $criteria->addSelectColumn(CalendarDefinitionPeer::CALENDAR_CREATE_DATE);
                $criteria->addSelectColumn(CalendarDefinitionPeer::CALENDAR_UPDATE_DATE);
                $criteria->addSelectColumn(CalendarDefinitionPeer::CALENDAR_WORK_DAYS);
                $criteria->addSelectColumn(CalendarDefinitionPeer::CALENDAR_DESCRIPTION);
                $criteria->addSelectColumn(CalendarDefinitionPeer::CALENDAR_STATUS);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = CalendarDefinitionPeer::retrieveByPK($calendarUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
