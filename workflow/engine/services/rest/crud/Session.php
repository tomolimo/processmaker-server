<?php

class Services_Rest_Session
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $sesUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($sesUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(SessionPeer::SES_UID);
                $criteria->addSelectColumn(SessionPeer::SES_STATUS);
                $criteria->addSelectColumn(SessionPeer::USR_UID);
                $criteria->addSelectColumn(SessionPeer::SES_REMOTE_IP);
                $criteria->addSelectColumn(SessionPeer::SES_INIT_DATE);
                $criteria->addSelectColumn(SessionPeer::SES_DUE_DATE);
                $criteria->addSelectColumn(SessionPeer::SES_END_DATE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = SessionPeer::retrieveByPK($sesUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
