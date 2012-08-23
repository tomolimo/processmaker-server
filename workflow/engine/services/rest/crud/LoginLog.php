<?php

class Services_Rest_LoginLog
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $logUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($logUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(LoginLogPeer::LOG_UID);
                $criteria->addSelectColumn(LoginLogPeer::LOG_STATUS);
                $criteria->addSelectColumn(LoginLogPeer::LOG_IP);
                $criteria->addSelectColumn(LoginLogPeer::LOG_SID);
                $criteria->addSelectColumn(LoginLogPeer::LOG_INIT_DATE);
                $criteria->addSelectColumn(LoginLogPeer::LOG_END_DATE);
                $criteria->addSelectColumn(LoginLogPeer::LOG_CLIENT_HOSTNAME);
                $criteria->addSelectColumn(LoginLogPeer::USR_UID);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = LoginLogPeer::retrieveByPK($logUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
