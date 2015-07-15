<?php
require_once 'classes/model/om/BaseAbeRequests.php';


/**
 * Skeleton subclass for representing a row from the 'ABE_REQUESTS' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class AbeRequests extends BaseAbeRequests
{
    private $filterThisFields = array('ABE_REQ_UID', 'ABE_UID', 'APP_UID', 'DEL_INDEX',
                                      'ABE_REQ_SENT_TO', 'ABE_REQ_SUBJECT', 'ABE_REQ_BODY',
                                      'ABE_REQ_DATE', 'ABE_REQ_STATUS', 'ABE_REQ_ANSWERED');

    public function load($abeRequestUid)
    {
        try {
            $abeRequestInstance = AbeRequestsPeer::retrieveByPK($abeRequestUid);
            $fields = $abeRequestInstance->toArray(BasePeer::TYPE_FIELDNAME);

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

        $connection = Propel::getConnection(AbeRequestsPeer::DATABASE_NAME);

        try {
            if (!isset($data['ABE_REQ_UID'])) {
                $data['ABE_REQ_UID'] = '';
            }

            if ($data['ABE_REQ_UID'] == '') {
                $data['ABE_REQ_UID'] = G::generateUniqueID();
                $data['ABE_REQ_DATE'] = date('Y-m-d H:i:s');
                $AbeRequestsInstance = new AbeRequests();
            } else {
                $AbeRequestsInstance = AbeRequestsPeer::retrieveByPK($data['ABE_REQ_UID']);
            }

            $AbeRequestsInstance->fromArray($data, BasePeer::TYPE_FIELDNAME);

            if ($AbeRequestsInstance->validate()) {
                $connection->begin();
                $result = $AbeRequestsInstance->save();
                $connection->commit();

                return $data['ABE_REQ_UID'];
            } else {
                $message = '';
                $validationFailures = $AbeRequestsInstance->getValidationFailures();

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

// AbeRequests

