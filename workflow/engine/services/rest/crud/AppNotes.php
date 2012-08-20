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

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed  Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($appUid, $usrUid, $noteDate, $noteContent, $noteType, $noteAvailability, $noteOriginObj, $noteAffectedObj1, $noteAffectedObj2, $noteRecipients)
    {
        try {
            $result = array();
            $obj = new AppNotes();

            $obj->setAppUid($appUid);
            $obj->setUsrUid($usrUid);
            $obj->setNoteDate($noteDate);
            $obj->setNoteContent($noteContent);
            $obj->setNoteType($noteType);
            $obj->setNoteAvailability($noteAvailability);
            $obj->setNoteOriginObj($noteOriginObj);
            $obj->setNoteAffectedObj1($noteAffectedObj1);
            $obj->setNoteAffectedObj2($noteAffectedObj2);
            $obj->setNoteRecipients($noteRecipients);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed  Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($appUid, $usrUid, $noteDate, $noteContent, $noteType, $noteAvailability, $noteOriginObj, $noteAffectedObj1, $noteAffectedObj2, $noteRecipients)
    {
        try {
            $obj = AppNotesPeer::retrieveByPK();

            $obj->setAppUid($appUid);
            $obj->setUsrUid($usrUid);
            $obj->setNoteDate($noteDate);
            $obj->setNoteContent($noteContent);
            $obj->setNoteType($noteType);
            $obj->setNoteAvailability($noteAvailability);
            $obj->setNoteOriginObj($noteOriginObj);
            $obj->setNoteAffectedObj1($noteAffectedObj1);
            $obj->setNoteAffectedObj2($noteAffectedObj2);
            $obj->setNoteRecipients($noteRecipients);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed  Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete()
    {
        $conn = Propel::getConnection(AppNotesPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = AppNotesPeer::retrieveByPK();
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
