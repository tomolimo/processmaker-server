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


}
