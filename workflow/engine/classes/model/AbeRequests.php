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

    /**
     * Get information about the notification sent
     *
     * @param string $abeRequestUid
     *
     * @return array
     */
    public function getAbeRequest ($abeRequestUid)
    {
        $criteria = new Criteria();
        $criteria->addSelectColumn(AbeConfigurationPeer::ABE_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::PRO_UID);
        $criteria->addSelectColumn(AbeConfigurationPeer::TAS_UID);
        $criteria->addSelectColumn(TaskPeer::TAS_ID);
        $criteria->addSelectColumn(ProcessPeer::PRO_ID);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_UID);
        $criteria->addSelectColumn(AbeRequestsPeer::APP_UID);
        $criteria->addSelectColumn(AbeRequestsPeer::DEL_INDEX);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_SENT_TO);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_SUBJECT);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_BODY);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_ANSWERED);
        $criteria->addSelectColumn(AbeRequestsPeer::ABE_REQ_STATUS);
        $criteria->addSelectColumn(AppDelegationPeer::DEL_FINISH_DATE);
        $criteria->addSelectColumn(AppDelegationPeer::APP_NUMBER);
        $criteria->addJoin(AbeConfigurationPeer::TAS_UID, TaskPeer::TAS_UID, Criteria::LEFT_JOIN);
        $criteria->addJoin(AbeConfigurationPeer::PRO_UID, ProcessPeer::PRO_UID, Criteria::LEFT_JOIN);
        $criteria->addJoin(AbeConfigurationPeer::ABE_UID, AbeRequestsPeer::ABE_UID, Criteria::LEFT_JOIN);
        $conditions[] = [AbeRequestsPeer::APP_UID, AppDelegationPeer::APP_UID];
        $conditions[] = [AbeRequestsPeer::DEL_INDEX, AppDelegationPeer::DEL_INDEX];
        $criteria->addJoinMC($conditions, Criteria::LEFT_JOIN);
        $criteria->add(AbeRequestsPeer::ABE_REQ_UID, $abeRequestUid);
        $criteria->setLimit(1);
        $resultRes = AbeRequestsPeer::doSelectRS($criteria);
        $resultRes->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $resultRes->next();

        $infoRequest = $resultRes->getRow();

        return $infoRequest;
    }
}

// AbeRequests

