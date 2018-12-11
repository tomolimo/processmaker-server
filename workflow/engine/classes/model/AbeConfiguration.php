<?php
require_once 'classes/model/om/BaseAbeConfiguration.php';


/**
 * Skeleton subclass for representing a row from the 'ABE_CONFIGURATION' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class AbeConfiguration extends BaseAbeConfiguration
{

    private $filterThisFields = [
        'ABE_UID',
        'PRO_UID',
        'TAS_UID',
        'ABE_TYPE',
        'ABE_TEMPLATE',
        'ABE_DYN_TYPE',
        'DYN_UID',
        'ABE_EMAIL_FIELD',
        'ABE_ACTION_FIELD',
        'ABE_CASE_NOTE_IN_RESPONSE',
        'ABE_FORCE_LOGIN',
        'ABE_CREATE_DATE',
        'ABE_UPDATE_DATE',
        'ABE_SUBJECT_FIELD',
        'ABE_MAILSERVER_OR_MAILCURRENT',
        'ABE_CUSTOM_GRID',
        'ABE_EMAIL_SERVER_UID'
    ];

    public function load($abeUid)
    {
        try {
            $abeConfigurationInstance = AbeConfigurationPeer::retrieveByPK($abeUid);
            $fields = $abeConfigurationInstance->toArray(BasePeer::TYPE_FIELDNAME);

            return $fields;
        } catch (Exception $error) {
            throw $error;
        }
    }

    public function createOrUpdate($data)
    {
        foreach ($data as $field => $value) {
            if (!in_array($field, $this->filterThisFields)) {
                unset($data[$field]);
            }
        }

        $connection = Propel::getConnection(AbeConfigurationPeer::DATABASE_NAME);

        try {
            if (!isset($data['ABE_UID'])) {
                $data['ABE_UID'] = '';
            }

            if ($data['ABE_UID'] == '') {
                $data['ABE_UID'] = G::generateUniqueID();
                $data['ABE_CREATE_DATE'] = date('Y-m-d H:i:s');
                $abeConfigurationInstance = new AbeConfiguration();
            } else {
                $abeConfigurationInstance = AbeConfigurationPeer::retrieveByPK($data['ABE_UID']);
            }

            if (isset($data['ABE_CUSTOM_GRID'])) {
                $data['ABE_CUSTOM_GRID'] = serialize($data['ABE_CUSTOM_GRID']);
            } else {
                $data['ABE_CUSTOM_GRID'] = "";
            }

            $data['ABE_UPDATE_DATE'] = date('Y-m-d H:i:s');
            $abeConfigurationInstance->fromArray($data, BasePeer::TYPE_FIELDNAME);

            if ($abeConfigurationInstance->validate()) {
                $connection->begin();
                $result = $abeConfigurationInstance->save();
                $connection->commit();

                return $data['ABE_UID'];
            } else {
                $message = '';
                $validationFailures = $abeConfigurationInstance->getValidationFailures();

                foreach ($validationFailures as $validationFailure) {
                    $message .= $validationFailure->getMessage() . '. ';
                }

                throw (new Exception('Error trying to update: ' . $message));
            }
        } catch (Exception $error) {
            $connection->rollback();

            throw $error;
        }
    }

    public function deleteByTasUid($tasUid)
    {
        try {
            $criteria = new Criteria('workflow');
            $criteria->add(AbeConfigurationPeer::TAS_UID, $tasUid);
            AbeConfigurationPeer::doDelete($criteria);
        } catch (Exception $error) {
            throw $error;
        }
    }

    /**
     * Get configuration from ABE related to the task
     *
     * @param string $proUid
     * @param string $tasUid
     *
     * @return array
    */
    public function getTaskConfiguration ($proUid, $tasUid)
    {
        $criteria = new Criteria();
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::PRO_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_TYPE);
        $criteria->addSelectColumn(AbeConfigurationPeer::TAS_UID);
        $criteria->addSelectColumn(TaskPeer::TAS_ID);
        $criteria->addSelectColumn(ProcessPeer::PRO_ID);
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_TEMPLATE);
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_DYN_TYPE);
        $criteria->addSelectColumn(AbeConfigurationPeer::DYN_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_EMAIL_FIELD);
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_ACTION_FIELD);
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_SUBJECT_FIELD);
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_MAILSERVER_OR_MAILCURRENT);
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_CUSTOM_GRID);
        $criteria->addSelectColumn(DynaformPeer::DYN_CONTENT);
        $criteria->addJoin( AbeConfigurationPeer::DYN_UID, DynaformPeer::DYN_UID, Criteria::LEFT_JOIN );
        $criteria->addJoin(AbeConfigurationPeer::TAS_UID, TaskPeer::TAS_UID, Criteria::LEFT_JOIN);
        $criteria->addJoin(AbeConfigurationPeer::PRO_UID, ProcessPeer::PRO_UID, Criteria::LEFT_JOIN);
        $criteria->add(AbeConfigurationPeer::PRO_UID, $proUid);
        $criteria->add(AbeConfigurationPeer::TAS_UID, $tasUid);
        $criteria->setLimit(1);
        $result = AbeConfigurationPeer::doSelectRS($criteria);
        $result->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $result->next();
        $configuration = $result->getRow();

        return $configuration;
    }
}

// AbeConfiguration

