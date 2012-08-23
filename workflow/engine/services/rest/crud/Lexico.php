<?php

class Services_Rest_Lexico
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $lexTopic, $lexKey Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($lexTopic=null, $lexKey=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(LexicoPeer::LEX_TOPIC);
                $criteria->addSelectColumn(LexicoPeer::LEX_KEY);
                $criteria->addSelectColumn(LexicoPeer::LEX_VALUE);
                $criteria->addSelectColumn(LexicoPeer::LEX_CAPTION);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = LexicoPeer::retrieveByPK($lexTopic, $lexKey);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }


}
