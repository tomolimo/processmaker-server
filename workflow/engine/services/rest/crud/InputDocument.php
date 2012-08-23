<?php

class Services_Rest_InputDocument
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $inpDocUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($inpDocUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(InputDocumentPeer::INP_DOC_UID);
                $criteria->addSelectColumn(InputDocumentPeer::PRO_UID);
                $criteria->addSelectColumn(InputDocumentPeer::INP_DOC_FORM_NEEDED);
                $criteria->addSelectColumn(InputDocumentPeer::INP_DOC_ORIGINAL);
                $criteria->addSelectColumn(InputDocumentPeer::INP_DOC_PUBLISHED);
                $criteria->addSelectColumn(InputDocumentPeer::INP_DOC_VERSIONING);
                $criteria->addSelectColumn(InputDocumentPeer::INP_DOC_DESTINATION_PATH);
                $criteria->addSelectColumn(InputDocumentPeer::INP_DOC_TAGS);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = InputDocumentPeer::retrieveByPK($inpDocUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
