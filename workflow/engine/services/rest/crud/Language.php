<?php

class Services_Rest_Language
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $lanId Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($lanId=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(LanguagePeer::LAN_ID);
                $criteria->addSelectColumn(LanguagePeer::LAN_NAME);
                $criteria->addSelectColumn(LanguagePeer::LAN_NATIVE_NAME);
                $criteria->addSelectColumn(LanguagePeer::LAN_DIRECTION);
                $criteria->addSelectColumn(LanguagePeer::LAN_WEIGHT);
                $criteria->addSelectColumn(LanguagePeer::LAN_ENABLED);
                $criteria->addSelectColumn(LanguagePeer::LAN_CALENDAR);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = LanguagePeer::retrieveByPK($lanId);
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
     * @param  mixed $lanId Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($lanId, $lanName, $lanNativeName, $lanDirection, $lanWeight, $lanEnabled, $lanCalendar)
    {
        try {
            $result = array();
            $obj = new Language();

            $obj->setLanId($lanId);
            $obj->setLanName($lanName);
            $obj->setLanNativeName($lanNativeName);
            $obj->setLanDirection($lanDirection);
            $obj->setLanWeight($lanWeight);
            $obj->setLanEnabled($lanEnabled);
            $obj->setLanCalendar($lanCalendar);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $lanId Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($lanId, $lanName, $lanNativeName, $lanDirection, $lanWeight, $lanEnabled, $lanCalendar)
    {
        try {
            $obj = LanguagePeer::retrieveByPK($lanId);

            $obj->setLanName($lanName);
            $obj->setLanNativeName($lanNativeName);
            $obj->setLanDirection($lanDirection);
            $obj->setLanWeight($lanWeight);
            $obj->setLanEnabled($lanEnabled);
            $obj->setLanCalendar($lanCalendar);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $lanId Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($lanId)
    {
        $conn = Propel::getConnection(LanguagePeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = LanguagePeer::retrieveByPK($lanId);
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
