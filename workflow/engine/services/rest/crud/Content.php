<?php

class Services_Rest_Content
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $conCategory, $conParent, $conId, $conLang Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($conCategory=null, $conParent=null, $conId=null, $conLang=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(ContentPeer::CON_CATEGORY);
                $criteria->addSelectColumn(ContentPeer::CON_PARENT);
                $criteria->addSelectColumn(ContentPeer::CON_ID);
                $criteria->addSelectColumn(ContentPeer::CON_LANG);
                $criteria->addSelectColumn(ContentPeer::CON_VALUE);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = ContentPeer::retrieveByPK($conCategory, $conParent, $conId, $conLang);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
