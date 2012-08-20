<?php

class Services_Rest_UsersProperties
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $usrUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($usrUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(UsersPropertiesPeer::USR_UID);
                $criteria->addSelectColumn(UsersPropertiesPeer::USR_LAST_UPDATE_DATE);
                $criteria->addSelectColumn(UsersPropertiesPeer::USR_LOGGED_NEXT_TIME);
                $criteria->addSelectColumn(UsersPropertiesPeer::USR_PASSWORD_HISTORY);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = UsersPropertiesPeer::retrieveByPK($usrUid);
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
     * @param  mixed $usrUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($usrUid, $usrLastUpdateDate, $usrLoggedNextTime, $usrPasswordHistory)
    {
        try {
            $result = array();
            $obj = new UsersProperties();

            $obj->setUsrUid($usrUid);
            $obj->setUsrLastUpdateDate($usrLastUpdateDate);
            $obj->setUsrLoggedNextTime($usrLoggedNextTime);
            $obj->setUsrPasswordHistory($usrPasswordHistory);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $usrUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($usrUid, $usrLastUpdateDate, $usrLoggedNextTime, $usrPasswordHistory)
    {
        try {
            $obj = UsersPropertiesPeer::retrieveByPK($usrUid);

            $obj->setUsrLastUpdateDate($usrLastUpdateDate);
            $obj->setUsrLoggedNextTime($usrLoggedNextTime);
            $obj->setUsrPasswordHistory($usrPasswordHistory);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $usrUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($usrUid)
    {
        $conn = Propel::getConnection(UsersPropertiesPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = UsersPropertiesPeer::retrieveByPK($usrUid);
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
