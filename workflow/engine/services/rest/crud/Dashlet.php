<?php

class Services_Rest_Dashlet
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $dasUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($dasUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(DashletPeer::DAS_UID);
                $criteria->addSelectColumn(DashletPeer::DAS_CLASS);
                $criteria->addSelectColumn(DashletPeer::DAS_TITLE);
                $criteria->addSelectColumn(DashletPeer::DAS_DESCRIPTION);
                $criteria->addSelectColumn(DashletPeer::DAS_VERSION);
                $criteria->addSelectColumn(DashletPeer::DAS_CREATE_DATE);
                $criteria->addSelectColumn(DashletPeer::DAS_UPDATE_DATE);
                $criteria->addSelectColumn(DashletPeer::DAS_STATUS);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = DashletPeer::retrieveByPK($dasUid);
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
     * @param  mixed $dasUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($dasUid, $dasClass, $dasTitle, $dasDescription, $dasVersion, $dasCreateDate, $dasUpdateDate, $dasStatus)
    {
        try {
            $result = array();
            $obj = new Dashlet();

            $obj->setDasUid($dasUid);
            $obj->setDasClass($dasClass);
            $obj->setDasTitle($dasTitle);
            $obj->setDasDescription($dasDescription);
            $obj->setDasVersion($dasVersion);
            $obj->setDasCreateDate($dasCreateDate);
            $obj->setDasUpdateDate($dasUpdateDate);
            $obj->setDasStatus($dasStatus);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $dasUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($dasUid, $dasClass, $dasTitle, $dasDescription, $dasVersion, $dasCreateDate, $dasUpdateDate, $dasStatus)
    {
        try {
            $obj = DashletPeer::retrieveByPK($dasUid);

            $obj->setDasClass($dasClass);
            $obj->setDasTitle($dasTitle);
            $obj->setDasDescription($dasDescription);
            $obj->setDasVersion($dasVersion);
            $obj->setDasCreateDate($dasCreateDate);
            $obj->setDasUpdateDate($dasUpdateDate);
            $obj->setDasStatus($dasStatus);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $dasUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($dasUid)
    {
        $conn = Propel::getConnection(DashletPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = DashletPeer::retrieveByPK($dasUid);
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
