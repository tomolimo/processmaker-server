<?php

class Services_Rest_DashletInstance
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $dasInsUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($dasInsUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_UID);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_UID);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_OWNER_TYPE);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_OWNER_UID);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_ADDITIONAL_PROPERTIES);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_CREATE_DATE);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_UPDATE_DATE);
                $criteria->addSelectColumn(DashletInstancePeer::DAS_INS_STATUS);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = DashletInstancePeer::retrieveByPK($dasInsUid);
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
     * @param  mixed $dasInsUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($dasInsUid, $dasUid, $dasInsOwnerType, $dasInsOwnerUid, $dasInsAdditionalProperties, $dasInsCreateDate, $dasInsUpdateDate, $dasInsStatus)
    {
        try {
            $result = array();
            $obj = new DashletInstance();

            $obj->setDasInsUid($dasInsUid);
            $obj->setDasUid($dasUid);
            $obj->setDasInsOwnerType($dasInsOwnerType);
            $obj->setDasInsOwnerUid($dasInsOwnerUid);
            $obj->setDasInsAdditionalProperties($dasInsAdditionalProperties);
            $obj->setDasInsCreateDate($dasInsCreateDate);
            $obj->setDasInsUpdateDate($dasInsUpdateDate);
            $obj->setDasInsStatus($dasInsStatus);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $dasInsUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($dasInsUid, $dasUid, $dasInsOwnerType, $dasInsOwnerUid, $dasInsAdditionalProperties, $dasInsCreateDate, $dasInsUpdateDate, $dasInsStatus)
    {
        try {
            $obj = DashletInstancePeer::retrieveByPK($dasInsUid);

            $obj->setDasUid($dasUid);
            $obj->setDasInsOwnerType($dasInsOwnerType);
            $obj->setDasInsOwnerUid($dasInsOwnerUid);
            $obj->setDasInsAdditionalProperties($dasInsAdditionalProperties);
            $obj->setDasInsCreateDate($dasInsCreateDate);
            $obj->setDasInsUpdateDate($dasInsUpdateDate);
            $obj->setDasInsStatus($dasInsStatus);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $dasInsUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($dasInsUid)
    {
        $conn = Propel::getConnection(DashletInstancePeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = DashletInstancePeer::retrieveByPK($dasInsUid);
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
