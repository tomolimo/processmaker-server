<?php
/**
 * Skeleton subclass for representing a row from the 'APP_DELAY' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 * /**
 * @package    workflow.engine.classes.model
 */
class AppDelay extends BaseAppDelay
{
    const APP_TYPE_CANCEL = 'CANCEL';
    const APP_TYPE_UNCANCEL = 'UNCANCEL';
    const APP_TYPE_PAUSE = 'PAUSE';

    /**
     * Create the application delay registry
     * 
     * @param array $data
     * 
     * @return string
     * @throws Exception
    **/
    public function create($data)
    {
        $connection = Propel::getConnection(AppDelayPeer::DATABASE_NAME);
        try {
            if (isset ($data['APP_DELAY_UID']) && $data['APP_DELAY_UID'] == '') {
                unset ($data['APP_DELAY_UID']);
            }
            if (!isset ($data['APP_DELAY_UID'])) {
                $data['APP_DELAY_UID'] = G::generateUniqueID();
            }
            $appDelay = new AppDelay();
            $appDelay->fromArray($data, BasePeer::TYPE_FIELDNAME);
            if ($appDelay->validate()) {
                $connection->begin();
                $result = $appDelay->save();
                $connection->commit();

                return $data['APP_DELAY_UID'];
            } else {
                $message = '';
                $validationFailures = $appDelay->getValidationFailures();
                foreach ($validationFailures as $validationFailure) {
                    $message .= $validationFailure->getMessage() . '<br />';
                }
                throw(new Exception('The registry cannot be created!<br />' . $message));
            }
        } catch (Exception $error) {
            $connection->rollback();
            throw($error);
        }
    }

    /**
     * Update the application delay registry
     *
     * @param array $data
     *
     * @return string
     * @throws Exception
    **/
    public function update($data)
    {
        $connection = Propel::getConnection(AppDelayPeer::DATABASE_NAME);
        try {
            $appDelay = AppDelayPeer::retrieveByPK($data['APP_DELAY_UID']);
            if (!is_null($appDelay)) {
                $appDelay->fromArray($data, BasePeer::TYPE_FIELDNAME);
                if ($appDelay->validate()) {
                    $connection->begin();
                    $result = $appDelay->save();
                    $connection->commit();
                    return $result;
                } else {
                    $message = '';
                    $validationFailures = $appDelay->getValidationFailures();
                    foreach ($validationFailures as $validationFailure) {
                        $message .= $validationFailure->getMessage() . '<br />';
                    }
                    throw(new Exception('The registry cannot be updated!<br />'.$message));
                }
            } else {
                throw(new Exception('This row doesn\'t exist!'));
            }
        } catch (Exception $error) {
            $connection->rollback();
            throw($error);
        }
    }

    /**
     * Review if the application in a specific index is paused
     *
     * @param string $appUid
     * @param integer $delIndex
     *
     * @return boolean
    */
    public function isPaused($appUid, $delIndex)
    {
        $criteria = new Criteria('workflow');
        $criteria->add(AppDelayPeer::APP_UID, $appUid);
        $criteria->add(AppDelayPeer::APP_DEL_INDEX, $delIndex);
        $criteria->add(AppDelayPeer::APP_TYPE, AppDelay::APP_TYPE_PAUSE);
        $criteria->add(
            $criteria->getNewCriterion(AppDelayPeer::APP_DISABLE_ACTION_USER, 0, Criteria::EQUAL)->addOr(
                $criteria->getNewCriterion(AppDelayPeer::APP_DISABLE_ACTION_USER, null, Criteria::ISNULL))
        );

        $dataset = AppDelayPeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();
        $row = $dataset->getRow();

        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verify if the case is Paused or cancelled
     *
     * @param $appUid string
     *
     * @return array|null
     */
    public function getCasesCancelOrPaused($appUid)
    {
        $criteria = new Criteria('workflow');
        $criteria->addSelectColumn(AppDelayPeer::APP_UID);
        $criteria->addSelectColumn(AppDelayPeer::APP_DEL_INDEX);
        $criteria->add(AppDelayPeer::APP_UID, $appUid);
        $criteria->add(
            $criteria->getNewCriterion(AppDelayPeer::APP_TYPE, AppDelay::APP_TYPE_PAUSE)->addOr(
                $criteria->getNewCriterion(AppDelayPeer::APP_TYPE, AppDelay::APP_TYPE_CANCEL)
            )
        );
        $criteria->addAscendingOrderByColumn(AppDelayPeer::APP_ENABLE_ACTION_DATE);
        $dataset = AppDelayPeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();

        return $dataset->getRow();
    }

    /**
     * Build the row for the appDelay to be inserted
     *
     * @param string $proUid
     * @param integer $proId
     * @param string $appUid
     * @param integer $appNumber
     * @param integer $appThreadIndex
     * @param integer $delIndex
     * @param string $appType
     * @param string $appStatus
     * @param string $usrUid
     * @param integer $usrId
     *
     * @return array
    */
    public static function buildAppDelayRow(
        $proUid = '',
        $proId = 0,
        $appUid = '',
        $appNumber = 0,
        $appThreadIndex = 0,
        $delIndex = 0,
        $appType = 'CANCEL',
        $appStatus = 'CANCELLED',
        $usrUid = '',
        $usrId = 0
    ) {
        $row = [];
        $row['PRO_UID'] = $proUid;
        $row['APP_UID'] = $appUid;
        $row['APP_NUMBER'] = $appNumber;
        $row['APP_THREAD_INDEX'] = $appThreadIndex;
        $row['APP_DEL_INDEX'] = $delIndex;
        $row['APP_TYPE'] = $appType;
        $row['APP_STATUS'] = $appStatus;
        $row['APP_ENABLE_ACTION_DATE'] = date('Y-m-d H:i:s');

        //Load the PRO_ID if does not exit
        if (empty($proId) || $proId === 0) {
            $u = new Process();
            $proId = $u->load($proUid)['PRO_ID'];
        }

        $row['PRO_ID'] = $proId;
        //Define the user that execute the insert
        if (empty($usrUid)) {
            global $RBAC;
            $usrUid = $RBAC->aUserInfo['USER_INFO']['USR_UID'];
            $u = new Users();
            $usrId = $u->load($usrUid)['USR_ID'];
        }
        $row['APP_DELEGATION_USER'] = $usrUid;
        $row['APP_ENABLE_ACTION_USER'] = $usrUid;
        $row['APP_DELEGATION_USER_ID'] = $usrId;

        return $row;
    }

    /**
     * Return all threads with the status canceled
     *
     * @param string $appUid
     * @param string $status
     *
     * @return array
     * @throws Exception
    */
    public function getThreadByStatus($appUid, $status)
    {
        try {
            $criteria = new Criteria('workflow');
            $criteria->add(AppDelayPeer::APP_UID, $appUid);
            $criteria->add(AppDelayPeer::APP_STATUS, $status);
            $criteria->addDescendingOrderByColumn(AppDelayPeer::APP_ENABLE_ACTION_DATE);
            $dataset = AppDelayPeer::doSelectRS($criteria);
            $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $dataset->next();
            $result = [];
            while ($row = $dataset->getRow()) {
                $result[] = $row;
                $dataset->next();
            }

            return $result;
        } catch (Exception $error) {
            throw $error;
        }
    }
}

