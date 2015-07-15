<?php
require_once 'classes/model/om/BaseAbeResponses.php';


/**
 * Skeleton subclass for representing a row from the 'ABE_RESPONSES' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class AbeResponses extends BaseAbeResponses
{

    private $filterThisFields = array('ABE_RES_UID', 'ABE_REQ_UID', 'ABE_RES_CLIENT_IP', 'ABE_RES_DATA',
                                      'ABE_RES_DATE', 'ABE_RES_STATUS', 'ABE_RES_MESSAGE');

    public function load($abeResponsesUid)
    {
        try {
            $abeResponsesInstance = AbeResponsesPeer::retrieveByPK($abeResponsesUid);
            $fields = $abeResponsesInstance->toArray(BasePeer::TYPE_FIELDNAME);

            return $fields;
        } catch (Exception $error) {
            throw $error;
        }
    }

    public function createOrUpdate($data)
    {
        $additionalFields = array();

        foreach ($data as $field => $value) {
            if (!in_array($field, $this->filterThisFields)) {
                $additionalFields[$field] = $value;
                unset($data[$field]);
            }
        }

        $connection = Propel::getConnection(AbeResponsesPeer::DATABASE_NAME);

        try {
            if (!isset($data['ABE_RES_UID'])) {
                $data['ABE_RES_UID'] = '';
            }

            if ($data['ABE_RES_UID'] == '') {
                $data['ABE_RES_UID'] = G::generateUniqueID();
                $data['ABE_RES_DATE'] = date('Y-m-d H:i:s');
                $AbeResponsesInstance = new AbeResponses();
            } else {
                $AbeResponsesInstance = AbeResponsesPeer::retrieveByPK($data['ABE_RES_UID']);
            }

            //$data['ABE_RES_UPDATE'] = date('Y-m-d H:i:s');
            $AbeResponsesInstance->fromArray($data, BasePeer::TYPE_FIELDNAME);

            if ($AbeResponsesInstance->validate()) {
                $connection->begin();
                $result = $AbeResponsesInstance->save();
                $connection->commit();

                return $data['ABE_RES_UID'];
            } else {
                $message = '';
                $validationFailures = $AbeResponsesInstance->getValidationFailures();

                foreach ($validationFailures as $validationFailure) {
                    $message .= $validationFailure->getMessage() . '. ';
                }

                throw(new Exception('Error trying to update: ' . $message));
            }
        } catch (Exception $error) {
            $connection->rollback();

            throw $error;
        }
    }
}

// AbeResponses

