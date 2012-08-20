<?php

class Services_Rest_Fields
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $fldUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($fldUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(FieldsPeer::FLD_UID);
                $criteria->addSelectColumn(FieldsPeer::ADD_TAB_UID);
                $criteria->addSelectColumn(FieldsPeer::FLD_INDEX);
                $criteria->addSelectColumn(FieldsPeer::FLD_NAME);
                $criteria->addSelectColumn(FieldsPeer::FLD_DESCRIPTION);
                $criteria->addSelectColumn(FieldsPeer::FLD_TYPE);
                $criteria->addSelectColumn(FieldsPeer::FLD_SIZE);
                $criteria->addSelectColumn(FieldsPeer::FLD_NULL);
                $criteria->addSelectColumn(FieldsPeer::FLD_AUTO_INCREMENT);
                $criteria->addSelectColumn(FieldsPeer::FLD_KEY);
                $criteria->addSelectColumn(FieldsPeer::FLD_FOREIGN_KEY);
                $criteria->addSelectColumn(FieldsPeer::FLD_FOREIGN_KEY_TABLE);
                $criteria->addSelectColumn(FieldsPeer::FLD_DYN_NAME);
                $criteria->addSelectColumn(FieldsPeer::FLD_DYN_UID);
                $criteria->addSelectColumn(FieldsPeer::FLD_FILTER);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = FieldsPeer::retrieveByPK($fldUid);
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
     * @param  mixed $fldUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($fldUid, $addTabUid, $fldIndex, $fldName, $fldDescription, $fldType, $fldSize, $fldNull, $fldAutoIncrement, $fldKey, $fldForeignKey, $fldForeignKeyTable, $fldDynName, $fldDynUid, $fldFilter)
    {
        try {
            $result = array();
            $obj = new Fields();

            $obj->setFldUid($fldUid);
            $obj->setAddTabUid($addTabUid);
            $obj->setFldIndex($fldIndex);
            $obj->setFldName($fldName);
            $obj->setFldDescription($fldDescription);
            $obj->setFldType($fldType);
            $obj->setFldSize($fldSize);
            $obj->setFldNull($fldNull);
            $obj->setFldAutoIncrement($fldAutoIncrement);
            $obj->setFldKey($fldKey);
            $obj->setFldForeignKey($fldForeignKey);
            $obj->setFldForeignKeyTable($fldForeignKeyTable);
            $obj->setFldDynName($fldDynName);
            $obj->setFldDynUid($fldDynUid);
            $obj->setFldFilter($fldFilter);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $fldUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($fldUid, $addTabUid, $fldIndex, $fldName, $fldDescription, $fldType, $fldSize, $fldNull, $fldAutoIncrement, $fldKey, $fldForeignKey, $fldForeignKeyTable, $fldDynName, $fldDynUid, $fldFilter)
    {
        try {
            $obj = FieldsPeer::retrieveByPK($fldUid);

            $obj->setAddTabUid($addTabUid);
            $obj->setFldIndex($fldIndex);
            $obj->setFldName($fldName);
            $obj->setFldDescription($fldDescription);
            $obj->setFldType($fldType);
            $obj->setFldSize($fldSize);
            $obj->setFldNull($fldNull);
            $obj->setFldAutoIncrement($fldAutoIncrement);
            $obj->setFldKey($fldKey);
            $obj->setFldForeignKey($fldForeignKey);
            $obj->setFldForeignKeyTable($fldForeignKeyTable);
            $obj->setFldDynName($fldDynName);
            $obj->setFldDynUid($fldDynUid);
            $obj->setFldFilter($fldFilter);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $fldUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($fldUid)
    {
        $conn = Propel::getConnection(FieldsPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = FieldsPeer::retrieveByPK($fldUid);
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
