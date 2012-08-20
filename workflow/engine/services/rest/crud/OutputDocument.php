<?php

class Services_Rest_OutputDocument
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $outDocUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($outDocUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_UID);
                $criteria->addSelectColumn(OutputDocumentPeer::PRO_UID);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_LANDSCAPE);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_MEDIA);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_LEFT_MARGIN);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_RIGHT_MARGIN);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_TOP_MARGIN);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_BOTTOM_MARGIN);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_GENERATE);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_TYPE);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_CURRENT_REVISION);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_FIELD_MAPPING);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_VERSIONING);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_DESTINATION_PATH);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_TAGS);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_PDF_SECURITY_ENABLED);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_PDF_SECURITY_OPEN_PASSWORD);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_PDF_SECURITY_OWNER_PASSWORD);
                $criteria->addSelectColumn(OutputDocumentPeer::OUT_DOC_PDF_SECURITY_PERMISSIONS);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = OutputDocumentPeer::retrieveByPK($outDocUid);
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
     * @param  mixed $outDocUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($outDocUid, $proUid, $outDocLandscape, $outDocMedia, $outDocLeftMargin, $outDocRightMargin, $outDocTopMargin, $outDocBottomMargin, $outDocGenerate, $outDocType, $outDocCurrentRevision, $outDocFieldMapping, $outDocVersioning, $outDocDestinationPath, $outDocTags, $outDocPdfSecurityEnabled, $outDocPdfSecurityOpenPassword, $outDocPdfSecurityOwnerPassword, $outDocPdfSecurityPermissions)
    {
        try {
            $result = array();
            $obj = new OutputDocument();

            $obj->setOutDocUid($outDocUid);
            $obj->setProUid($proUid);
            $obj->setOutDocLandscape($outDocLandscape);
            $obj->setOutDocMedia($outDocMedia);
            $obj->setOutDocLeftMargin($outDocLeftMargin);
            $obj->setOutDocRightMargin($outDocRightMargin);
            $obj->setOutDocTopMargin($outDocTopMargin);
            $obj->setOutDocBottomMargin($outDocBottomMargin);
            $obj->setOutDocGenerate($outDocGenerate);
            $obj->setOutDocType($outDocType);
            $obj->setOutDocCurrentRevision($outDocCurrentRevision);
            $obj->setOutDocFieldMapping($outDocFieldMapping);
            $obj->setOutDocVersioning($outDocVersioning);
            $obj->setOutDocDestinationPath($outDocDestinationPath);
            $obj->setOutDocTags($outDocTags);
            $obj->setOutDocPdfSecurityEnabled($outDocPdfSecurityEnabled);
            $obj->setOutDocPdfSecurityOpenPassword($outDocPdfSecurityOpenPassword);
            $obj->setOutDocPdfSecurityOwnerPassword($outDocPdfSecurityOwnerPassword);
            $obj->setOutDocPdfSecurityPermissions($outDocPdfSecurityPermissions);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $outDocUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($outDocUid, $proUid, $outDocLandscape, $outDocMedia, $outDocLeftMargin, $outDocRightMargin, $outDocTopMargin, $outDocBottomMargin, $outDocGenerate, $outDocType, $outDocCurrentRevision, $outDocFieldMapping, $outDocVersioning, $outDocDestinationPath, $outDocTags, $outDocPdfSecurityEnabled, $outDocPdfSecurityOpenPassword, $outDocPdfSecurityOwnerPassword, $outDocPdfSecurityPermissions)
    {
        try {
            $obj = OutputDocumentPeer::retrieveByPK($outDocUid);

            $obj->setProUid($proUid);
            $obj->setOutDocLandscape($outDocLandscape);
            $obj->setOutDocMedia($outDocMedia);
            $obj->setOutDocLeftMargin($outDocLeftMargin);
            $obj->setOutDocRightMargin($outDocRightMargin);
            $obj->setOutDocTopMargin($outDocTopMargin);
            $obj->setOutDocBottomMargin($outDocBottomMargin);
            $obj->setOutDocGenerate($outDocGenerate);
            $obj->setOutDocType($outDocType);
            $obj->setOutDocCurrentRevision($outDocCurrentRevision);
            $obj->setOutDocFieldMapping($outDocFieldMapping);
            $obj->setOutDocVersioning($outDocVersioning);
            $obj->setOutDocDestinationPath($outDocDestinationPath);
            $obj->setOutDocTags($outDocTags);
            $obj->setOutDocPdfSecurityEnabled($outDocPdfSecurityEnabled);
            $obj->setOutDocPdfSecurityOpenPassword($outDocPdfSecurityOpenPassword);
            $obj->setOutDocPdfSecurityOwnerPassword($outDocPdfSecurityOwnerPassword);
            $obj->setOutDocPdfSecurityPermissions($outDocPdfSecurityPermissions);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $outDocUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($outDocUid)
    {
        $conn = Propel::getConnection(OutputDocumentPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = OutputDocumentPeer::retrieveByPK($outDocUid);
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
