<?php

class Services_Rest_Stage
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $stgUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($stgUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(StagePeer::STG_UID);
                $criteria->addSelectColumn(StagePeer::PRO_UID);
                $criteria->addSelectColumn(StagePeer::STG_POSX);
                $criteria->addSelectColumn(StagePeer::STG_POSY);
                $criteria->addSelectColumn(StagePeer::STG_INDEX);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = StagePeer::retrieveByPK($stgUid);
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
     * @param  mixed $stgUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($stgUid, $proUid, $stgPosx, $stgPosy, $stgIndex)
    {
        try {
            $result = array();
            $obj = new Stage();

            $obj->setStgUid($stgUid);
            $obj->setProUid($proUid);
            $obj->setStgPosx($stgPosx);
            $obj->setStgPosy($stgPosy);
            $obj->setStgIndex($stgIndex);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $stgUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($stgUid, $proUid, $stgPosx, $stgPosy, $stgIndex)
    {
        try {
            $obj = StagePeer::retrieveByPK($stgUid);

            $obj->setProUid($proUid);
            $obj->setStgPosx($stgPosx);
            $obj->setStgPosy($stgPosy);
            $obj->setStgIndex($stgIndex);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $stgUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($stgUid)
    {
        $conn = Propel::getConnection(StagePeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = StagePeer::retrieveByPK($stgUid);
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
