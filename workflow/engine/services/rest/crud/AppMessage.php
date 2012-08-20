<?php

class Services_Rest_AppMessage
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $appMsgUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($appMsgUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_UID);
                $criteria->addSelectColumn(AppMessagePeer::MSG_UID);
                $criteria->addSelectColumn(AppMessagePeer::APP_UID);
                $criteria->addSelectColumn(AppMessagePeer::DEL_INDEX);
                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_TYPE);
                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_SUBJECT);
                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_FROM);
                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_TO);
                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_BODY);
                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_DATE);
                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_CC);
                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_BCC);
                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_TEMPLATE);
                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_STATUS);
                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_ATTACH);
                $criteria->addSelectColumn(AppMessagePeer::APP_MSG_SEND_DATE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = AppMessagePeer::retrieveByPK($appMsgUid);
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
     * @param  mixed $appMsgUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($appMsgUid, $msgUid, $appUid, $delIndex, $appMsgType, $appMsgSubject, $appMsgFrom, $appMsgTo, $appMsgBody, $appMsgDate, $appMsgCc, $appMsgBcc, $appMsgTemplate, $appMsgStatus, $appMsgAttach, $appMsgSendDate)
    {
        try {
            $result = array();
            $obj = new AppMessage();

            $obj->setAppMsgUid($appMsgUid);
            $obj->setMsgUid($msgUid);
            $obj->setAppUid($appUid);
            $obj->setDelIndex($delIndex);
            $obj->setAppMsgType($appMsgType);
            $obj->setAppMsgSubject($appMsgSubject);
            $obj->setAppMsgFrom($appMsgFrom);
            $obj->setAppMsgTo($appMsgTo);
            $obj->setAppMsgBody($appMsgBody);
            $obj->setAppMsgDate($appMsgDate);
            $obj->setAppMsgCc($appMsgCc);
            $obj->setAppMsgBcc($appMsgBcc);
            $obj->setAppMsgTemplate($appMsgTemplate);
            $obj->setAppMsgStatus($appMsgStatus);
            $obj->setAppMsgAttach($appMsgAttach);
            $obj->setAppMsgSendDate($appMsgSendDate);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $appMsgUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($appMsgUid, $msgUid, $appUid, $delIndex, $appMsgType, $appMsgSubject, $appMsgFrom, $appMsgTo, $appMsgBody, $appMsgDate, $appMsgCc, $appMsgBcc, $appMsgTemplate, $appMsgStatus, $appMsgAttach, $appMsgSendDate)
    {
        try {
            $obj = AppMessagePeer::retrieveByPK($appMsgUid);

            $obj->setMsgUid($msgUid);
            $obj->setAppUid($appUid);
            $obj->setDelIndex($delIndex);
            $obj->setAppMsgType($appMsgType);
            $obj->setAppMsgSubject($appMsgSubject);
            $obj->setAppMsgFrom($appMsgFrom);
            $obj->setAppMsgTo($appMsgTo);
            $obj->setAppMsgBody($appMsgBody);
            $obj->setAppMsgDate($appMsgDate);
            $obj->setAppMsgCc($appMsgCc);
            $obj->setAppMsgBcc($appMsgBcc);
            $obj->setAppMsgTemplate($appMsgTemplate);
            $obj->setAppMsgStatus($appMsgStatus);
            $obj->setAppMsgAttach($appMsgAttach);
            $obj->setAppMsgSendDate($appMsgSendDate);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $appMsgUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($appMsgUid)
    {
        $conn = Propel::getConnection(AppMessagePeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = AppMessagePeer::retrieveByPK($appMsgUid);
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
