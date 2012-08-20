<?php

class Services_Rest_AppFolder
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $folderUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($folderUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(AppFolderPeer::FOLDER_UID);
                $criteria->addSelectColumn(AppFolderPeer::FOLDER_PARENT_UID);
                $criteria->addSelectColumn(AppFolderPeer::FOLDER_NAME);
                $criteria->addSelectColumn(AppFolderPeer::FOLDER_CREATE_DATE);
                $criteria->addSelectColumn(AppFolderPeer::FOLDER_UPDATE_DATE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = AppFolderPeer::retrieveByPK($folderUid);
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
     * @param  mixed $folderUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($folderUid, $folderParentUid, $folderName, $folderCreateDate, $folderUpdateDate)
    {
        try {
            $result = array();
            $obj = new AppFolder();

            $obj->setFolderUid($folderUid);
            $obj->setFolderParentUid($folderParentUid);
            $obj->setFolderName($folderName);
            $obj->setFolderCreateDate($folderCreateDate);
            $obj->setFolderUpdateDate($folderUpdateDate);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $folderUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($folderUid, $folderParentUid, $folderName, $folderCreateDate, $folderUpdateDate)
    {
        try {
            $obj = AppFolderPeer::retrieveByPK($folderUid);

            $obj->setFolderParentUid($folderParentUid);
            $obj->setFolderName($folderName);
            $obj->setFolderCreateDate($folderCreateDate);
            $obj->setFolderUpdateDate($folderUpdateDate);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $folderUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($folderUid)
    {
        $conn = Propel::getConnection(AppFolderPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = AppFolderPeer::retrieveByPK($folderUid);
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
