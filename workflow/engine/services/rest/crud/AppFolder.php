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


}
