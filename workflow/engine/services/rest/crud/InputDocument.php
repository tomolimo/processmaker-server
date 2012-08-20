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

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed $inpDocUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($inpDocUid, $proUid, $inpDocFormNeeded, $inpDocOriginal, $inpDocPublished, $inpDocVersioning, $inpDocDestinationPath, $inpDocTags)
    {
        try {
            $result = array();
            $obj = new InputDocument();

            $obj->setInpDocUid($inpDocUid);
            $obj->setProUid($proUid);
            $obj->setInpDocFormNeeded($inpDocFormNeeded);
            $obj->setInpDocOriginal($inpDocOriginal);
            $obj->setInpDocPublished($inpDocPublished);
            $obj->setInpDocVersioning($inpDocVersioning);
            $obj->setInpDocDestinationPath($inpDocDestinationPath);
            $obj->setInpDocTags($inpDocTags);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $inpDocUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($inpDocUid, $proUid, $inpDocFormNeeded, $inpDocOriginal, $inpDocPublished, $inpDocVersioning, $inpDocDestinationPath, $inpDocTags)
    {
        try {
            $obj = InputDocumentPeer::retrieveByPK($inpDocUid);

            $obj->setProUid($proUid);
            $obj->setInpDocFormNeeded($inpDocFormNeeded);
            $obj->setInpDocOriginal($inpDocOriginal);
            $obj->setInpDocPublished($inpDocPublished);
            $obj->setInpDocVersioning($inpDocVersioning);
            $obj->setInpDocDestinationPath($inpDocDestinationPath);
            $obj->setInpDocTags($inpDocTags);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $inpDocUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($inpDocUid)
    {
        $conn = Propel::getConnection(InputDocumentPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = InputDocumentPeer::retrieveByPK($inpDocUid);
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
