<?php

class Services_Rest_Route
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $rouUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($rouUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(RoutePeer::ROU_UID);
                $criteria->addSelectColumn(RoutePeer::ROU_PARENT);
                $criteria->addSelectColumn(RoutePeer::PRO_UID);
                $criteria->addSelectColumn(RoutePeer::TAS_UID);
                $criteria->addSelectColumn(RoutePeer::ROU_NEXT_TASK);
                $criteria->addSelectColumn(RoutePeer::ROU_CASE);
                $criteria->addSelectColumn(RoutePeer::ROU_TYPE);
                $criteria->addSelectColumn(RoutePeer::ROU_CONDITION);
                $criteria->addSelectColumn(RoutePeer::ROU_TO_LAST_USER);
                $criteria->addSelectColumn(RoutePeer::ROU_OPTIONAL);
                $criteria->addSelectColumn(RoutePeer::ROU_SEND_EMAIL);
                $criteria->addSelectColumn(RoutePeer::ROU_SOURCEANCHOR);
                $criteria->addSelectColumn(RoutePeer::ROU_TARGETANCHOR);
                $criteria->addSelectColumn(RoutePeer::ROU_TO_PORT);
                $criteria->addSelectColumn(RoutePeer::ROU_FROM_PORT);
                $criteria->addSelectColumn(RoutePeer::ROU_EVN_UID);
                $criteria->addSelectColumn(RoutePeer::GAT_UID);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = RoutePeer::retrieveByPK($rouUid);
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
     * @param  mixed $rouUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($rouUid, $rouParent, $proUid, $tasUid, $rouNextTask, $rouCase, $rouType, $rouCondition, $rouToLastUser, $rouOptional, $rouSendEmail, $rouSourceanchor, $rouTargetanchor, $rouToPort, $rouFromPort, $rouEvnUid, $gatUid)
    {
        try {
            $result = array();
            $obj = new Route();

            $obj->setRouUid($rouUid);
            $obj->setRouParent($rouParent);
            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setRouNextTask($rouNextTask);
            $obj->setRouCase($rouCase);
            $obj->setRouType($rouType);
            $obj->setRouCondition($rouCondition);
            $obj->setRouToLastUser($rouToLastUser);
            $obj->setRouOptional($rouOptional);
            $obj->setRouSendEmail($rouSendEmail);
            $obj->setRouSourceanchor($rouSourceanchor);
            $obj->setRouTargetanchor($rouTargetanchor);
            $obj->setRouToPort($rouToPort);
            $obj->setRouFromPort($rouFromPort);
            $obj->setRouEvnUid($rouEvnUid);
            $obj->setGatUid($gatUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $rouUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($rouUid, $rouParent, $proUid, $tasUid, $rouNextTask, $rouCase, $rouType, $rouCondition, $rouToLastUser, $rouOptional, $rouSendEmail, $rouSourceanchor, $rouTargetanchor, $rouToPort, $rouFromPort, $rouEvnUid, $gatUid)
    {
        try {
            $obj = RoutePeer::retrieveByPK($rouUid);

            $obj->setRouParent($rouParent);
            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setRouNextTask($rouNextTask);
            $obj->setRouCase($rouCase);
            $obj->setRouType($rouType);
            $obj->setRouCondition($rouCondition);
            $obj->setRouToLastUser($rouToLastUser);
            $obj->setRouOptional($rouOptional);
            $obj->setRouSendEmail($rouSendEmail);
            $obj->setRouSourceanchor($rouSourceanchor);
            $obj->setRouTargetanchor($rouTargetanchor);
            $obj->setRouToPort($rouToPort);
            $obj->setRouFromPort($rouFromPort);
            $obj->setRouEvnUid($rouEvnUid);
            $obj->setGatUid($gatUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $rouUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($rouUid)
    {
        $conn = Propel::getConnection(RoutePeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = RoutePeer::retrieveByPK($rouUid);
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
