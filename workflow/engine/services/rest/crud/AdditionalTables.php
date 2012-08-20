<?php

class Services_Rest_AdditionalTables
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $addTabUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($addTabUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_UID);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_NAME);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_CLASS_NAME);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_DESCRIPTION);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_SDW_LOG_INSERT);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_SDW_LOG_UPDATE);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_SDW_LOG_DELETE);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_SDW_LOG_SELECT);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_SDW_MAX_LENGTH);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_SDW_AUTO_DELETE);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_PLG_UID);
                $criteria->addSelectColumn(AdditionalTablesPeer::DBS_UID);
                $criteria->addSelectColumn(AdditionalTablesPeer::PRO_UID);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_TYPE);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_GRID);
                $criteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_TAG);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = AdditionalTablesPeer::retrieveByPK($addTabUid);
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
     * @param  mixed $addTabUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($addTabUid, $addTabName, $addTabClassName, $addTabDescription, $addTabSdwLogInsert, $addTabSdwLogUpdate, $addTabSdwLogDelete, $addTabSdwLogSelect, $addTabSdwMaxLength, $addTabSdwAutoDelete, $addTabPlgUid, $dbsUid, $proUid, $addTabType, $addTabGrid, $addTabTag)
    {
        try {
            $result = array();
            $obj = new AdditionalTables();

            $obj->setAddTabUid($addTabUid);
            $obj->setAddTabName($addTabName);
            $obj->setAddTabClassName($addTabClassName);
            $obj->setAddTabDescription($addTabDescription);
            $obj->setAddTabSdwLogInsert($addTabSdwLogInsert);
            $obj->setAddTabSdwLogUpdate($addTabSdwLogUpdate);
            $obj->setAddTabSdwLogDelete($addTabSdwLogDelete);
            $obj->setAddTabSdwLogSelect($addTabSdwLogSelect);
            $obj->setAddTabSdwMaxLength($addTabSdwMaxLength);
            $obj->setAddTabSdwAutoDelete($addTabSdwAutoDelete);
            $obj->setAddTabPlgUid($addTabPlgUid);
            $obj->setDbsUid($dbsUid);
            $obj->setProUid($proUid);
            $obj->setAddTabType($addTabType);
            $obj->setAddTabGrid($addTabGrid);
            $obj->setAddTabTag($addTabTag);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $addTabUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($addTabUid, $addTabName, $addTabClassName, $addTabDescription, $addTabSdwLogInsert, $addTabSdwLogUpdate, $addTabSdwLogDelete, $addTabSdwLogSelect, $addTabSdwMaxLength, $addTabSdwAutoDelete, $addTabPlgUid, $dbsUid, $proUid, $addTabType, $addTabGrid, $addTabTag)
    {
        try {
            $obj = AdditionalTablesPeer::retrieveByPK($addTabUid);

            $obj->setAddTabName($addTabName);
            $obj->setAddTabClassName($addTabClassName);
            $obj->setAddTabDescription($addTabDescription);
            $obj->setAddTabSdwLogInsert($addTabSdwLogInsert);
            $obj->setAddTabSdwLogUpdate($addTabSdwLogUpdate);
            $obj->setAddTabSdwLogDelete($addTabSdwLogDelete);
            $obj->setAddTabSdwLogSelect($addTabSdwLogSelect);
            $obj->setAddTabSdwMaxLength($addTabSdwMaxLength);
            $obj->setAddTabSdwAutoDelete($addTabSdwAutoDelete);
            $obj->setAddTabPlgUid($addTabPlgUid);
            $obj->setDbsUid($dbsUid);
            $obj->setProUid($proUid);
            $obj->setAddTabType($addTabType);
            $obj->setAddTabGrid($addTabGrid);
            $obj->setAddTabTag($addTabTag);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $addTabUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($addTabUid)
    {
        $conn = Propel::getConnection(AdditionalTablesPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = AdditionalTablesPeer::retrieveByPK($addTabUid);
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
