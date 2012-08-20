<?php

class Services_Rest_AppDocument
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $appDocUid, $docVersion Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($appDocUid=null, $docVersion=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(AppDocumentPeer::APP_DOC_UID);
                $criteria->addSelectColumn(AppDocumentPeer::DOC_VERSION);
                $criteria->addSelectColumn(AppDocumentPeer::APP_UID);
                $criteria->addSelectColumn(AppDocumentPeer::DEL_INDEX);
                $criteria->addSelectColumn(AppDocumentPeer::DOC_UID);
                $criteria->addSelectColumn(AppDocumentPeer::USR_UID);
                $criteria->addSelectColumn(AppDocumentPeer::APP_DOC_TYPE);
                $criteria->addSelectColumn(AppDocumentPeer::APP_DOC_CREATE_DATE);
                $criteria->addSelectColumn(AppDocumentPeer::APP_DOC_INDEX);
                $criteria->addSelectColumn(AppDocumentPeer::FOLDER_UID);
                $criteria->addSelectColumn(AppDocumentPeer::APP_DOC_PLUGIN);
                $criteria->addSelectColumn(AppDocumentPeer::APP_DOC_TAGS);
                $criteria->addSelectColumn(AppDocumentPeer::APP_DOC_STATUS);
                $criteria->addSelectColumn(AppDocumentPeer::APP_DOC_STATUS_DATE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = AppDocumentPeer::retrieveByPK($appDocUid, $docVersion);
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
     * @param  mixed $appDocUid, $docVersion Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($appDocUid, $docVersion, $appUid, $delIndex, $docUid, $usrUid, $appDocType, $appDocCreateDate, $appDocIndex, $folderUid, $appDocPlugin, $appDocTags, $appDocStatus, $appDocStatusDate)
    {
        try {
            $result = array();
            $obj = new AppDocument();

            $obj->setAppDocUid($appDocUid);
            $obj->setDocVersion($docVersion);
            $obj->setAppUid($appUid);
            $obj->setDelIndex($delIndex);
            $obj->setDocUid($docUid);
            $obj->setUsrUid($usrUid);
            $obj->setAppDocType($appDocType);
            $obj->setAppDocCreateDate($appDocCreateDate);
            $obj->setAppDocIndex($appDocIndex);
            $obj->setFolderUid($folderUid);
            $obj->setAppDocPlugin($appDocPlugin);
            $obj->setAppDocTags($appDocTags);
            $obj->setAppDocStatus($appDocStatus);
            $obj->setAppDocStatusDate($appDocStatusDate);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $appDocUid, $docVersion Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($appDocUid, $docVersion, $appUid, $delIndex, $docUid, $usrUid, $appDocType, $appDocCreateDate, $appDocIndex, $folderUid, $appDocPlugin, $appDocTags, $appDocStatus, $appDocStatusDate)
    {
        try {
            $obj = AppDocumentPeer::retrieveByPK($appDocUid, $docVersion);

            $obj->setAppUid($appUid);
            $obj->setDelIndex($delIndex);
            $obj->setDocUid($docUid);
            $obj->setUsrUid($usrUid);
            $obj->setAppDocType($appDocType);
            $obj->setAppDocCreateDate($appDocCreateDate);
            $obj->setAppDocIndex($appDocIndex);
            $obj->setFolderUid($folderUid);
            $obj->setAppDocPlugin($appDocPlugin);
            $obj->setAppDocTags($appDocTags);
            $obj->setAppDocStatus($appDocStatus);
            $obj->setAppDocStatusDate($appDocStatusDate);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $appDocUid, $docVersion Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($appDocUid, $docVersion)
    {
        $conn = Propel::getConnection(AppDocumentPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = AppDocumentPeer::retrieveByPK($appDocUid, $docVersion);
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
