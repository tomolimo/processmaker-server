<?php

class Services_Rest_Configuration
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $cfgUid, $objUid, $proUid, $usrUid, $appUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($cfgUid=null, $objUid=null, $proUid=null, $usrUid=null, $appUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(ConfigurationPeer::CFG_UID);
                $criteria->addSelectColumn(ConfigurationPeer::OBJ_UID);
                $criteria->addSelectColumn(ConfigurationPeer::CFG_VALUE);
                $criteria->addSelectColumn(ConfigurationPeer::PRO_UID);
                $criteria->addSelectColumn(ConfigurationPeer::USR_UID);
                $criteria->addSelectColumn(ConfigurationPeer::APP_UID);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = ConfigurationPeer::retrieveByPK($cfgUid, $objUid, $proUid, $usrUid, $appUid);
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
     * @param  mixed $cfgUid, $objUid, $proUid, $usrUid, $appUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($cfgUid, $objUid, $cfgValue, $proUid, $usrUid, $appUid)
    {
        try {
            $result = array();
            $obj = new Configuration();

            $obj->setCfgUid($cfgUid);
            $obj->setObjUid($objUid);
            $obj->setCfgValue($cfgValue);
            $obj->setProUid($proUid);
            $obj->setUsrUid($usrUid);
            $obj->setAppUid($appUid);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $cfgUid, $objUid, $proUid, $usrUid, $appUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($cfgUid, $objUid, $cfgValue, $proUid, $usrUid, $appUid)
    {
        try {
            $obj = ConfigurationPeer::retrieveByPK($cfgUid, $objUid, $proUid, $usrUid, $appUid);

            $obj->setCfgValue($cfgValue);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $cfgUid, $objUid, $proUid, $usrUid, $appUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($cfgUid, $objUid, $proUid, $usrUid, $appUid)
    {
        $conn = Propel::getConnection(ConfigurationPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = ConfigurationPeer::retrieveByPK($cfgUid, $objUid, $proUid, $usrUid, $appUid);
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
