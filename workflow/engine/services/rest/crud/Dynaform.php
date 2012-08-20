<?php

class Services_Rest_Dynaform
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $dynUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($dynUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(DynaformPeer::DYN_UID);
                $criteria->addSelectColumn(DynaformPeer::PRO_UID);
                $criteria->addSelectColumn(DynaformPeer::DYN_TYPE);
                $criteria->addSelectColumn(DynaformPeer::DYN_FILENAME);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = DynaformPeer::retrieveByPK($dynUid);
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
     * @param  mixed $dynUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($dynUid, $proUid, $dynType, $dynFilename)
    {
        try {
            $result = array();
            $obj = new Dynaform();

            $obj->setDynUid($dynUid);
            $obj->setProUid($proUid);
            $obj->setDynType($dynType);
            $obj->setDynFilename($dynFilename);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $dynUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($dynUid, $proUid, $dynType, $dynFilename)
    {
        try {
            $obj = DynaformPeer::retrieveByPK($dynUid);

            $obj->setProUid($proUid);
            $obj->setDynType($dynType);
            $obj->setDynFilename($dynFilename);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $dynUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($dynUid)
    {
        $conn = Propel::getConnection(DynaformPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = DynaformPeer::retrieveByPK($dynUid);
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
