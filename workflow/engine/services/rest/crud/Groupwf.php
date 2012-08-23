<?php

class Services_Rest_Groupwf
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $grpUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($grpUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(GroupwfPeer::GRP_UID);
                $criteria->addSelectColumn(GroupwfPeer::GRP_STATUS);
                $criteria->addSelectColumn(GroupwfPeer::GRP_LDAP_DN);
                $criteria->addSelectColumn(GroupwfPeer::GRP_UX);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = GroupwfPeer::retrieveByPK($grpUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
