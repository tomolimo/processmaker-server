<?php

class Services_Rest_DimTimeDelegate
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $timeId Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($timeId=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(DimTimeDelegatePeer::TIME_ID);
                $criteria->addSelectColumn(DimTimeDelegatePeer::MONTH_ID);
                $criteria->addSelectColumn(DimTimeDelegatePeer::QTR_ID);
                $criteria->addSelectColumn(DimTimeDelegatePeer::YEAR_ID);
                $criteria->addSelectColumn(DimTimeDelegatePeer::MONTH_NAME);
                $criteria->addSelectColumn(DimTimeDelegatePeer::MONTH_DESC);
                $criteria->addSelectColumn(DimTimeDelegatePeer::QTR_NAME);
                $criteria->addSelectColumn(DimTimeDelegatePeer::QTR_DESC);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = DimTimeDelegatePeer::retrieveByPK($timeId);
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
     * @param  mixed $timeId Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($timeId, $monthId, $qtrId, $yearId, $monthName, $monthDesc, $qtrName, $qtrDesc)
    {
        try {
            $result = array();
            $obj = new DimTimeDelegate();

            $obj->setTimeId($timeId);
            $obj->setMonthId($monthId);
            $obj->setQtrId($qtrId);
            $obj->setYearId($yearId);
            $obj->setMonthName($monthName);
            $obj->setMonthDesc($monthDesc);
            $obj->setQtrName($qtrName);
            $obj->setQtrDesc($qtrDesc);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $timeId Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($timeId, $monthId, $qtrId, $yearId, $monthName, $monthDesc, $qtrName, $qtrDesc)
    {
        try {
            $obj = DimTimeDelegatePeer::retrieveByPK($timeId);

            $obj->setMonthId($monthId);
            $obj->setQtrId($qtrId);
            $obj->setYearId($yearId);
            $obj->setMonthName($monthName);
            $obj->setMonthDesc($monthDesc);
            $obj->setQtrName($qtrName);
            $obj->setQtrDesc($qtrDesc);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $timeId Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($timeId)
    {
        $conn = Propel::getConnection(DimTimeDelegatePeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = DimTimeDelegatePeer::retrieveByPK($timeId);
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
