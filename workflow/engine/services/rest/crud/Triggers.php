<?php

class Services_Rest_Triggers
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $triUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($triUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(TriggersPeer::TRI_UID);
                $criteria->addSelectColumn(TriggersPeer::PRO_UID);
                $criteria->addSelectColumn(TriggersPeer::TRI_TYPE);
                $criteria->addSelectColumn(TriggersPeer::TRI_WEBBOT);
                $criteria->addSelectColumn(TriggersPeer::TRI_PARAM);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = TriggersPeer::retrieveByPK($triUid);
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
     * @param  mixed $triUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($triUid, $proUid, $triType, $triWebbot, $triParam)
    {
        try {
            $result = array();
            $obj = new Triggers();

            $obj->setTriUid($triUid);
            $obj->setProUid($proUid);
            $obj->setTriType($triType);
            $obj->setTriWebbot($triWebbot);
            $obj->setTriParam($triParam);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $triUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($triUid, $proUid, $triType, $triWebbot, $triParam)
    {
        try {
            $obj = TriggersPeer::retrieveByPK($triUid);

            $obj->setProUid($proUid);
            $obj->setTriType($triType);
            $obj->setTriWebbot($triWebbot);
            $obj->setTriParam($triParam);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $triUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($triUid)
    {
        $conn = Propel::getConnection(TriggersPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = TriggersPeer::retrieveByPK($triUid);
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
