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

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed $conCategory, $conParent, $conId, $conLang Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($conCategory, $conParent, $conId, $conLang, $conValue)
    {
        try {
            $result = array();
            $obj = new Content();

            $obj->setConCategory($conCategory);
            $obj->setConParent($conParent);
            $obj->setConId($conId);
            $obj->setConLang($conLang);
            $obj->setConValue($conValue);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $conCategory, $conParent, $conId, $conLang Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($conCategory, $conParent, $conId, $conLang, $conValue)
    {
        try {
            $obj = ContentPeer::retrieveByPK($conCategory, $conParent, $conId, $conLang);

            $obj->setConValue($conValue);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $conCategory, $conParent, $conId, $conLang Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($conCategory, $conParent, $conId, $conLang)
    {
        $conn = Propel::getConnection(ContentPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = ContentPeer::retrieveByPK($conCategory, $conParent, $conId, $conLang);
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
