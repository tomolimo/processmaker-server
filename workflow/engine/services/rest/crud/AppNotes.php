<?php

class Services_Rest_AppNotes
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed  Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get()
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(AppNotesPeer::APP_UID);
                $criteria->addSelectColumn(AppNotesPeer::USR_UID);
                $criteria->addSelectColumn(AppNotesPeer::NOTE_DATE);
                $criteria->addSelectColumn(AppNotesPeer::NOTE_CONTENT);
                $criteria->addSelectColumn(AppNotesPeer::NOTE_TYPE);
                $criteria->addSelectColumn(AppNotesPeer::NOTE_AVAILABILITY);
                $criteria->addSelectColumn(AppNotesPeer::NOTE_ORIGIN_OBJ);
                $criteria->addSelectColumn(AppNotesPeer::NOTE_AFFECTED_OBJ1);
                $criteria->addSelectColumn(AppNotesPeer::NOTE_AFFECTED_OBJ2);
                $criteria->addSelectColumn(AppNotesPeer::NOTE_RECIPIENTS);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = AppNotesPeer::retrieveByPK();
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
