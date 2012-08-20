<?php

class Services_Rest_ReportTable
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $repTabUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($repTabUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_UID);
                $criteria->addSelectColumn(ReportTablePeer::PRO_UID);
                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_NAME);
                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_TYPE);
                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_GRID);
                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_CONNECTION);
                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_CREATE_DATE);
                $criteria->addSelectColumn(ReportTablePeer::REP_TAB_STATUS);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = ReportTablePeer::retrieveByPK($repTabUid);
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
     * @param  mixed $repTabUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($repTabUid, $proUid, $repTabName, $repTabType, $repTabGrid, $repTabConnection, $repTabCreateDate, $repTabStatus)
    {
        try {
            $result = array();
            $obj = new ReportTable();

            $obj->setRepTabUid($repTabUid);
            $obj->setProUid($proUid);
            $obj->setRepTabName($repTabName);
            $obj->setRepTabType($repTabType);
            $obj->setRepTabGrid($repTabGrid);
            $obj->setRepTabConnection($repTabConnection);
            $obj->setRepTabCreateDate($repTabCreateDate);
            $obj->setRepTabStatus($repTabStatus);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $repTabUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($repTabUid, $proUid, $repTabName, $repTabType, $repTabGrid, $repTabConnection, $repTabCreateDate, $repTabStatus)
    {
        try {
            $obj = ReportTablePeer::retrieveByPK($repTabUid);

            $obj->setProUid($proUid);
            $obj->setRepTabName($repTabName);
            $obj->setRepTabType($repTabType);
            $obj->setRepTabGrid($repTabGrid);
            $obj->setRepTabConnection($repTabConnection);
            $obj->setRepTabCreateDate($repTabCreateDate);
            $obj->setRepTabStatus($repTabStatus);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $repTabUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($repTabUid)
    {
        $conn = Propel::getConnection(ReportTablePeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = ReportTablePeer::retrieveByPK($repTabUid);
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
