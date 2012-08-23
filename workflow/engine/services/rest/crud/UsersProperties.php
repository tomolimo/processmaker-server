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


}
