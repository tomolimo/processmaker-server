<?php

class Services_Rest_AppOwner
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $appUid, $ownUid, $usrUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($appUid=null, $ownUid=null, $usrUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(AppOwnerPeer::APP_UID);
                $criteria->addSelectColumn(AppOwnerPeer::OWN_UID);
                $criteria->addSelectColumn(AppOwnerPeer::USR_UID);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = AppOwnerPeer::retrieveByPK($appUid, $ownUid, $usrUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
