<?php

class Services_Rest_ProcessCategory
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $categoryUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($categoryUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(ProcessCategoryPeer::CATEGORY_UID);
                $criteria->addSelectColumn(ProcessCategoryPeer::CATEGORY_PARENT);
                $criteria->addSelectColumn(ProcessCategoryPeer::CATEGORY_NAME);
                $criteria->addSelectColumn(ProcessCategoryPeer::CATEGORY_ICON);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = ProcessCategoryPeer::retrieveByPK($categoryUid);
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
     * @param  mixed $categoryUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($categoryUid, $categoryParent, $categoryName, $categoryIcon)
    {
        try {
            $result = array();
            $obj = new ProcessCategory();

            $obj->setCategoryUid($categoryUid);
            $obj->setCategoryParent($categoryParent);
            $obj->setCategoryName($categoryName);
            $obj->setCategoryIcon($categoryIcon);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $categoryUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($categoryUid, $categoryParent, $categoryName, $categoryIcon)
    {
        try {
            $obj = ProcessCategoryPeer::retrieveByPK($categoryUid);

            $obj->setCategoryParent($categoryParent);
            $obj->setCategoryName($categoryName);
            $obj->setCategoryIcon($categoryIcon);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $categoryUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($categoryUid)
    {
        $conn = Propel::getConnection(ProcessCategoryPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = ProcessCategoryPeer::retrieveByPK($categoryUid);
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
