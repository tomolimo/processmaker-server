<?php

class Services_Rest_Department
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $depUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($depUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(DepartmentPeer::DEP_UID);
                $criteria->addSelectColumn(DepartmentPeer::DEP_PARENT);
                $criteria->addSelectColumn(DepartmentPeer::DEP_MANAGER);
                $criteria->addSelectColumn(DepartmentPeer::DEP_LOCATION);
                $criteria->addSelectColumn(DepartmentPeer::DEP_STATUS);
                $criteria->addSelectColumn(DepartmentPeer::DEP_REF_CODE);
                $criteria->addSelectColumn(DepartmentPeer::DEP_LDAP_DN);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = DepartmentPeer::retrieveByPK($depUid);
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
     * @param  mixed $depUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($depUid, $depParent, $depManager, $depLocation, $depStatus, $depRefCode, $depLdapDn)
    {
        try {
            $result = array();
            $obj = new Department();

            $obj->setDepUid($depUid);
            $obj->setDepParent($depParent);
            $obj->setDepManager($depManager);
            $obj->setDepLocation($depLocation);
            $obj->setDepStatus($depStatus);
            $obj->setDepRefCode($depRefCode);
            $obj->setDepLdapDn($depLdapDn);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $depUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($depUid, $depParent, $depManager, $depLocation, $depStatus, $depRefCode, $depLdapDn)
    {
        try {
            $obj = DepartmentPeer::retrieveByPK($depUid);

            $obj->setDepParent($depParent);
            $obj->setDepManager($depManager);
            $obj->setDepLocation($depLocation);
            $obj->setDepStatus($depStatus);
            $obj->setDepRefCode($depRefCode);
            $obj->setDepLdapDn($depLdapDn);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $depUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($depUid)
    {
        $conn = Propel::getConnection(DepartmentPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = DepartmentPeer::retrieveByPK($depUid);
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
