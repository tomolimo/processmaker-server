<?php

class Services_Rest_Gateway
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $gatUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($gatUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(GatewayPeer::GAT_UID);
                $criteria->addSelectColumn(GatewayPeer::PRO_UID);
                $criteria->addSelectColumn(GatewayPeer::TAS_UID);
                $criteria->addSelectColumn(GatewayPeer::GAT_NEXT_TASK);
                $criteria->addSelectColumn(GatewayPeer::GAT_X);
                $criteria->addSelectColumn(GatewayPeer::GAT_Y);
                $criteria->addSelectColumn(GatewayPeer::GAT_TYPE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = GatewayPeer::retrieveByPK($gatUid);
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
     * @param  mixed $gatUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($gatUid, $proUid, $tasUid, $gatNextTask, $gatX, $gatY, $gatType)
    {
        try {
            $result = array();
            $obj = new Gateway();

            $obj->setGatUid($gatUid);
            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setGatNextTask($gatNextTask);
            $obj->setGatX($gatX);
            $obj->setGatY($gatY);
            $obj->setGatType($gatType);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $gatUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($gatUid, $proUid, $tasUid, $gatNextTask, $gatX, $gatY, $gatType)
    {
        try {
            $obj = GatewayPeer::retrieveByPK($gatUid);

            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setGatNextTask($gatNextTask);
            $obj->setGatX($gatX);
            $obj->setGatY($gatY);
            $obj->setGatType($gatType);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $gatUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($gatUid)
    {
        $conn = Propel::getConnection(GatewayPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = GatewayPeer::retrieveByPK($gatUid);
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
