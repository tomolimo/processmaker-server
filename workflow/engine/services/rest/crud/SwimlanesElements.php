<?php

class Services_Rest_SwimlanesElements
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $swiUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($swiUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(SwimlanesElementsPeer::SWI_UID);
                $criteria->addSelectColumn(SwimlanesElementsPeer::PRO_UID);
                $criteria->addSelectColumn(SwimlanesElementsPeer::SWI_TYPE);
                $criteria->addSelectColumn(SwimlanesElementsPeer::SWI_X);
                $criteria->addSelectColumn(SwimlanesElementsPeer::SWI_Y);
                $criteria->addSelectColumn(SwimlanesElementsPeer::SWI_WIDTH);
                $criteria->addSelectColumn(SwimlanesElementsPeer::SWI_HEIGHT);
                $criteria->addSelectColumn(SwimlanesElementsPeer::SWI_NEXT_UID);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = SwimlanesElementsPeer::retrieveByPK($swiUid);
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
     * @param  mixed $swiUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($swiUid, $proUid, $swiType, $swiX, $swiY, $swiWidth, $swiHeight, $swiNextUid)
    {
        try {
            $result = array();
            $obj = new SwimlanesElements();

            $obj->setSwiUid($swiUid);
            $obj->setProUid($proUid);
            $obj->setSwiType($swiType);
            $obj->setSwiX($swiX);
            $obj->setSwiY($swiY);
            $obj->setSwiWidth($swiWidth);
            $obj->setSwiHeight($swiHeight);
            $obj->setSwiNextUid($swiNextUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $swiUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($swiUid, $proUid, $swiType, $swiX, $swiY, $swiWidth, $swiHeight, $swiNextUid)
    {
        try {
            $obj = SwimlanesElementsPeer::retrieveByPK($swiUid);

            $obj->setProUid($proUid);
            $obj->setSwiType($swiType);
            $obj->setSwiX($swiX);
            $obj->setSwiY($swiY);
            $obj->setSwiWidth($swiWidth);
            $obj->setSwiHeight($swiHeight);
            $obj->setSwiNextUid($swiNextUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $swiUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($swiUid)
    {
        $conn = Propel::getConnection(SwimlanesElementsPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = SwimlanesElementsPeer::retrieveByPK($swiUid);
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
