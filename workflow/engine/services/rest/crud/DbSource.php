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

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed $dbsUid, $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($dbsUid, $proUid, $dbsType, $dbsServer, $dbsDatabaseName, $dbsUsername, $dbsPassword, $dbsPort, $dbsEncode)
    {
        try {
            $result = array();
            $obj = new DbSource();

            $obj->setDbsUid($dbsUid);
            $obj->setProUid($proUid);
            $obj->setDbsType($dbsType);
            $obj->setDbsServer($dbsServer);
            $obj->setDbsDatabaseName($dbsDatabaseName);
            $obj->setDbsUsername($dbsUsername);
            $obj->setDbsPassword($dbsPassword);
            $obj->setDbsPort($dbsPort);
            $obj->setDbsEncode($dbsEncode);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $dbsUid, $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($dbsUid, $proUid, $dbsType, $dbsServer, $dbsDatabaseName, $dbsUsername, $dbsPassword, $dbsPort, $dbsEncode)
    {
        try {
            $obj = DbSourcePeer::retrieveByPK($dbsUid, $proUid);

            $obj->setDbsType($dbsType);
            $obj->setDbsServer($dbsServer);
            $obj->setDbsDatabaseName($dbsDatabaseName);
            $obj->setDbsUsername($dbsUsername);
            $obj->setDbsPassword($dbsPassword);
            $obj->setDbsPort($dbsPort);
            $obj->setDbsEncode($dbsEncode);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $dbsUid, $proUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($dbsUid, $proUid)
    {
        $conn = Propel::getConnection(DbSourcePeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = DbSourcePeer::retrieveByPK($dbsUid, $proUid);
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
