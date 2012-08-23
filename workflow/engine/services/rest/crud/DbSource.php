<?php

class Services_Rest_DbSource
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $dbsUid, $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($dbsUid=null, $proUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(DbSourcePeer::DBS_UID);
                $criteria->addSelectColumn(DbSourcePeer::PRO_UID);
                $criteria->addSelectColumn(DbSourcePeer::DBS_TYPE);
                $criteria->addSelectColumn(DbSourcePeer::DBS_SERVER);
                $criteria->addSelectColumn(DbSourcePeer::DBS_DATABASE_NAME);
                $criteria->addSelectColumn(DbSourcePeer::DBS_USERNAME);
                $criteria->addSelectColumn(DbSourcePeer::DBS_PASSWORD);
                $criteria->addSelectColumn(DbSourcePeer::DBS_PORT);
                $criteria->addSelectColumn(DbSourcePeer::DBS_ENCODE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = DbSourcePeer::retrieveByPK($dbsUid, $proUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
