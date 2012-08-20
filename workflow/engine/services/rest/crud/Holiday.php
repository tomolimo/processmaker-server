<?php

class Services_Rest_Holiday
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $hldUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($hldUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(HolidayPeer::HLD_UID);
                $criteria->addSelectColumn(HolidayPeer::HLD_DATE);
                $criteria->addSelectColumn(HolidayPeer::HLD_DESCRIPTION);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = HolidayPeer::retrieveByPK($hldUid);
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
     * @param  mixed $hldUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($hldUid, $hldDate, $hldDescription)
    {
        try {
            $result = array();
            $obj = new Holiday();

            $obj->setHldUid($hldUid);
            $obj->setHldDate($hldDate);
            $obj->setHldDescription($hldDescription);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $hldUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($hldUid, $hldDate, $hldDescription)
    {
        try {
            $obj = HolidayPeer::retrieveByPK($hldUid);

            $obj->setHldDate($hldDate);
            $obj->setHldDescription($hldDescription);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $hldUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($hldUid)
    {
        $conn = Propel::getConnection(HolidayPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = HolidayPeer::retrieveByPK($hldUid);
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
