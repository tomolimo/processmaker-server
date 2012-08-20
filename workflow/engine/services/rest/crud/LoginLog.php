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

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed $logUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($logUid, $logStatus, $logIp, $logSid, $logInitDate, $logEndDate, $logClientHostname, $usrUid)
    {
        try {
            $result = array();
            $obj = new LoginLog();

            $obj->setLogUid($logUid);
            $obj->setLogStatus($logStatus);
            $obj->setLogIp($logIp);
            $obj->setLogSid($logSid);
            $obj->setLogInitDate($logInitDate);
            $obj->setLogEndDate($logEndDate);
            $obj->setLogClientHostname($logClientHostname);
            $obj->setUsrUid($usrUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $logUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($logUid, $logStatus, $logIp, $logSid, $logInitDate, $logEndDate, $logClientHostname, $usrUid)
    {
        try {
            $obj = LoginLogPeer::retrieveByPK($logUid);

            $obj->setLogStatus($logStatus);
            $obj->setLogIp($logIp);
            $obj->setLogSid($logSid);
            $obj->setLogInitDate($logInitDate);
            $obj->setLogEndDate($logEndDate);
            $obj->setLogClientHostname($logClientHostname);
            $obj->setUsrUid($usrUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $logUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($logUid)
    {
        $conn = Propel::getConnection(LoginLogPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = LoginLogPeer::retrieveByPK($logUid);
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
