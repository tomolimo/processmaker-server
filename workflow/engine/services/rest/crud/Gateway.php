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


}
