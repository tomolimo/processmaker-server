<?php

class Services_Rest_DimTimeComplete
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

                $criteria->addSelectColumn(DimTimeCompletePeer::TIME_ID);
                $criteria->addSelectColumn(DimTimeCompletePeer::MONTH_ID);
                $criteria->addSelectColumn(DimTimeCompletePeer::QTR_ID);
                $criteria->addSelectColumn(DimTimeCompletePeer::YEAR_ID);
                $criteria->addSelectColumn(DimTimeCompletePeer::MONTH_NAME);
                $criteria->addSelectColumn(DimTimeCompletePeer::MONTH_DESC);
                $criteria->addSelectColumn(DimTimeCompletePeer::QTR_NAME);
                $criteria->addSelectColumn(DimTimeCompletePeer::QTR_DESC);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = DimTimeCompletePeer::retrieveByPK($timeId);
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
            $obj = new DimTimeComplete();

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
            $obj = DimTimeCompletePeer::retrieveByPK($timeId);

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
        $conn = Propel::getConnection(DimTimeCompletePeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = DimTimeCompletePeer::retrieveByPK($timeId);
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
