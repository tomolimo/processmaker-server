<?php

class Services_Rest_Translation
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $trnCategory, $trnId, $trnLang Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($trnCategory=null, $trnId=null, $trnLang=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(TranslationPeer::TRN_CATEGORY);
                $criteria->addSelectColumn(TranslationPeer::TRN_ID);
                $criteria->addSelectColumn(TranslationPeer::TRN_LANG);
                $criteria->addSelectColumn(TranslationPeer::TRN_VALUE);
                $criteria->addSelectColumn(TranslationPeer::TRN_UPDATE_DATE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = TranslationPeer::retrieveByPK($trnCategory, $trnId, $trnLang);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
