<?php

namespace ProcessMaker\BusinessModel;

use BasePeer;
use Bootstrap;
use Criteria;
use EmailEvent as ModelEmailEvent;
use EmailEventPeer;
use EmailServerPeer;
use Exception;
use G;
use ProcessMaker\Util\Common;
use Propel;
use ResultSet;
use UsersPeer;

class EmailEvent
{
    /**
     * Get the email accounts of the current workspace
     *
     * @return array
     * @throws Exception
     */
    public function getEmailEventAccounts()
    {
        try {
            $criteria = new Criteria('workflow');
            $criteria->clearSelectColumns();
            $criteria->addSelectColumn(UsersPeer::USR_UID);
            $criteria->addSelectColumn(UsersPeer::USR_EMAIL);
            $criteria->addAsColumn('UID', 'USR_UID');
            $criteria->addAsColumn('EMAIL', 'USR_EMAIL');
            $criteria->add(UsersPeer::USR_STATUS, 'ACTIVE');
            $result = UsersPeer::doSelectRS($criteria);
            $result->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $accounts = [];
            while ($result->next()) {
                $row = $result->getRow();
                if (!empty($row['USR_EMAIL'])) {
                    $accounts[] = array_change_key_case($row, CASE_LOWER);
                }
            }
            return $accounts;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the email server accounts of the current workspace
     *
     * @return array
     * @throws Exception
     */
    public function getEmailEventServerAccounts()
    {
        try {
            $criteria = new Criteria('workflow');
            $criteria->clearSelectColumns();
            $criteria->addSelectColumn(EmailServerPeer::MESS_UID);
            $criteria->addSelectColumn(EmailServerPeer::MESS_FROM_MAIL);
            $criteria->addSelectColumn(EmailServerPeer::MESS_FROM_NAME);
            $criteria->addSelectColumn(EmailServerPeer::MESS_ACCOUNT);
            $criteria->addSelectColumn(EmailServerPeer::MESS_ENGINE);
            $criteria->addAsColumn('UID', 'MESS_UID');
            $result = EmailServerPeer::doSelectRS($criteria);
            $result->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $accounts = [];
            while ($result->next()) {
                $row = $result->getRow();
                if (!empty($row['MESS_UID'])) {
                    $row['EMAIL'] = $row['MESS_ACCOUNT'];
                    $accounts[] = array_change_key_case($row, CASE_LOWER);
                }
            }
            return $accounts;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the Email-Event data
     *
     * @var string $evn_uid . uid for activity
     * @var string $pro_uid . uid for process
     *
     * @return array
     * @throws Exception
     */
    public function getEmailEventData($pro_uid, $evn_uid)
    {
        try {
            //Get data
            $criteria = $this->getEmailEventCriteria();
            $criteria->add(EmailEventPeer::EVN_UID, $evn_uid, Criteria::EQUAL);
            $criteria->add(EmailEventPeer::PRJ_UID, $pro_uid, Criteria::EQUAL);
            $rsCriteria = EmailEventPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $rsCriteria->next();
            $row = $rsCriteria->getRow();
            $emailServer = new EmailServer();
            if ($row) {
                // We need to initialize these values in empty, in order to return always the same structure
                $row['MESS_ENGINE'] = $row['MESS_ACCOUNT'] = $row['MESS_FROM_MAIL'] = '';
                if (!empty($row['EMAIL_SERVER_UID'])) {
                    $emailServerData = $emailServer->getEmailServer($row['EMAIL_SERVER_UID'], true);
                    $row['MESS_ENGINE'] = $emailServerData['MESS_ENGINE'];
                    $row['MESS_ACCOUNT'] = $emailServerData['MESS_ACCOUNT'];
                    $row['MESS_FROM_MAIL'] = $emailServerData['MESS_FROM_MAIL'];
                }
                $row = array_change_key_case($row, CASE_LOWER);
            }
            return $row;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the Email-Event data
     *
     * @var string $emailEventUid . uid for email event
     * @var string $pro_uid . uid for process
     *
     * @return array
     * @throws Exception
     */
    public function getEmailEventDataByUid($pro_uid, $emailEventUid)
    {
        try {
            //Get data
            $criteria = $this->getEmailEventCriteria();
            $criteria->add(EmailEventPeer::EMAIL_EVENT_UID, $emailEventUid, Criteria::EQUAL);
            $criteria->add(EmailEventPeer::PRJ_UID, $pro_uid, Criteria::EQUAL);
            $rsCriteria = EmailEventPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $rsCriteria->next();
            $row = $rsCriteria->getRow();
            if ($row) {
                $row = array_change_key_case($row, CASE_LOWER);
            }
            return $row;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Save Data for Email-Event
     *
     * @var string $prj_uid . Uid for Process
     * @var string $arrayData . Data for Trigger
     *
     * @return array
     * @throws Exception
     */
    public function save($prj_uid = '', $arrayData = [])
    {
        try {
            //Verify data
            $process = new Process();
            $validator = new Validator();

            $validator->throwExceptionIfDataIsNotArray($arrayData, "\$arrayData");
            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);

            //Verify data
            $process->throwExceptionIfNotExistsProcess($prj_uid, 'projectUid');

            //Create
            $db = Propel::getConnection('workflow');

            try {
                $emailEvent = new ModelEmailEvent();

                $emailEvent->fromArray($arrayData, BasePeer::TYPE_FIELDNAME);

                $emailEventUid = Common::generateUID();

                $emailEvent->setEmailEventUid($emailEventUid);
                $emailEvent->setPrjUid($prj_uid);

                $db->begin();
                $result = $emailEvent->save();
                $db->commit();

                return $this->getEmailEvent($emailEventUid);
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update Email-Event
     *
     * @param string $emailEventUid Unique id of Email-Event
     * @param array $arrayData Data
     *
     * @return array Return data of the Email-Event updated
     * @throws Exception
     */
    public function update($emailEventUid, array $arrayData)
    {
        try {
            //Verify data
            $validator = new Validator();

            $validator->throwExceptionIfDataIsNotArray($arrayData, "\$arrayData");
            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);
            $arrayDataBackup = $arrayData;

            //Set variables
            $arrayEmailEventData = $this->getEmailEvent($emailEventUid);

            //Verify data
            $this->verifyIfEmailEventExists($emailEventUid);

            //Update
            $db = Propel::getConnection('workflow');

            try {
                $emailEvent = EmailEventPeer::retrieveByPK($emailEventUid);
                $emailEvent->fromArray($arrayData, BasePeer::TYPE_FIELDNAME);

                $db->begin();
                $result = $emailEvent->save();
                $db->commit();

                $arrayData = $arrayDataBackup;
                $arrayData = array_change_key_case($arrayData, CASE_LOWER);
                return $arrayData;
            } catch (Exception $e) {
                $db->rollback();

                throw $e;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete Email-Event
     *
     * @param string $pro_uid
     * @param string $emailEventUid Unique id of Email-Event
     * @param boolean $passValidation
     * @param boolean $verifyRelation
     *
     * @throws Exception
     */
    public function delete($pro_uid, $emailEventUid, $passValidation = true, $verifyRelation = true)
    {
        try {
            //Verify data
            if ($passValidation) {
                $this->verifyIfEmailEventExists($emailEventUid);

                //Delete file
                $filesManager = new FilesManager();
                $arrayData = $this->getEmailEventDataByUid($pro_uid, $emailEventUid);
                $arrayData = array_change_key_case($arrayData, CASE_UPPER);
                if ($arrayData) {
                    $prfUid = $arrayData['PRF_UID'];
                    $filesManager->deleteProcessFilesManager($pro_uid, $prfUid, $verifyRelation);
                }
            }
            //Delete Email event
            $criteria = new Criteria('workflow');
            $criteria->add(EmailEventPeer::EMAIL_EVENT_UID, $emailEventUid, Criteria::EQUAL);
            $result = EmailEventPeer::doDelete($criteria);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete Email-Event by event uid
     *
     * @param string $prj_uid Unique id of Process
     * @param string $evn_uid Unique id of Email-Event
     *
     * @throws Exception
     */
    public function deleteByEvent($prj_uid, $evn_uid)
    {
        try {
            //Verify data
            if (!$this->existsEvent($prj_uid, $evn_uid)) {
                throw new Exception(G::LoadTranslation('ID_EMAIL_EVENT_DEFINITION_DOES_NOT_EXIST'));
            }
            $arrayData = $this->existsEvent($prj_uid, $evn_uid);
            $this->delete($prj_uid, $arrayData[0]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Email-Event
     *
     * @param string $emailEventUid Unique id of Email-Event
     *
     * @return array Return an array with data of a Email-Event
     * @throws Exception
     */
    public function getEmailEvent($emailEventUid)
    {
        try {
            //Verify data
            $this->verifyIfEmailEventExists($emailEventUid);

            //Get data
            $criteria = $this->getEmailEventCriteria();
            $criteria->add(EmailEventPeer::EMAIL_EVENT_UID, $emailEventUid, Criteria::EQUAL);
            $rsCriteria = EmailEventPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $rsCriteria->next();
            $row = $rsCriteria->getRow();
            return $row ? $row : [];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the Email-Event
     *
     * @param string $emailEventUid Unique id of Email-Event
     *
     * @return bool Return true if exists the Email-Event, false otherwise
     * @throws Exception
     */
    public function exists($emailEventUid)
    {
        try {
            $obj = EmailEventPeer::retrieveByPK($emailEventUid);

            return $obj ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get criteria for Email-Event
     *
     * @return Criteria
     * @throws Exception
     */
    public function getEmailEventCriteria()
    {
        try {
            $criteria = new Criteria('workflow');

            $criteria->addSelectColumn(EmailEventPeer::EMAIL_EVENT_UID);
            $criteria->addSelectColumn(EmailEventPeer::PRJ_UID);
            $criteria->addSelectColumn(EmailEventPeer::EVN_UID);
            $criteria->addSelectColumn(EmailEventPeer::EMAIL_EVENT_FROM);
            $criteria->addSelectColumn(EmailEventPeer::EMAIL_EVENT_TO);
            $criteria->addSelectColumn(EmailEventPeer::EMAIL_EVENT_SUBJECT);
            $criteria->addSelectColumn(EmailEventPeer::PRF_UID);
            $criteria->addSelectColumn(EmailEventPeer::EMAIL_SERVER_UID);

            return $criteria;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get Criteria for Email Event when Server Uid is empty
     *
     * @return Criteria
     * @throws Exception
     */
    public function getEmailEventCriteriaEmailServer()
    {
        try {
            $criteria = new Criteria('workflow');

            $criteria->addSelectColumn(EmailEventPeer::EMAIL_EVENT_UID);
            $criteria->addSelectColumn(EmailEventPeer::PRJ_UID);
            $criteria->addSelectColumn(EmailEventPeer::EVN_UID);
            $criteria->addSelectColumn(EmailEventPeer::EMAIL_EVENT_FROM);
            $criteria->addSelectColumn(EmailEventPeer::EMAIL_EVENT_TO);
            $criteria->addSelectColumn(EmailEventPeer::EMAIL_EVENT_SUBJECT);
            $criteria->addSelectColumn(EmailEventPeer::PRF_UID);
            $criteria->addSelectColumn(EmailEventPeer::EMAIL_SERVER_UID);
            $criteria->add(EmailEventPeer::EMAIL_SERVER_UID, '', Criteria::EQUAL);

            return $criteria;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if email Event exists
     *
     * @param $emailEventUid
     * @throws Exception
     */
    public function verifyIfEmailEventExists($emailEventUid)
    {
        if (!$this->exists($emailEventUid)) {
            throw new Exception(G::LoadTranslation('ID_EMAIL_EVENT_DEFINITION_DOES_NOT_EXIST', ['Email Event Uid', $emailEventUid]));
        }
    }

    /**
     * Verify if exists the Event of a Message-Event-Definition
     *
     * @param string $projectUid Unique id of Project
     * @param string $eventUid Unique id of Event
     *
     * @return bool Return true if exists the Event of a Message-Event-Definition, false otherwise
     * @throws Exception
     */
    public function existsEvent($projectUid, $eventUid)
    {
        try {
            $criteria = $this->getEmailEventCriteria();
            $criteria->add(EmailEventPeer::PRJ_UID, $projectUid, Criteria::EQUAL);
            $criteria->add(EmailEventPeer::EVN_UID, $eventUid, Criteria::EQUAL);
            $rsCriteria = EmailEventPeer::doSelectRS($criteria);
            $rsCriteria->next();
            $row = $rsCriteria->getRow();
            if ($row) {
                return array_change_key_case($row, CASE_LOWER);
            }
            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Email-event do function
     *
     * @param string $appUID Unique id of application
     * @param string $prj_uid Unique id of Project
     * @param string $eventUid Unique id of event
     * @param array $arrayApplicationData Case data
     *
     * @return void
     * @throws Exception
     */
    public function sendEmail($appUID, $prj_uid, $eventUid, $arrayApplicationData)
    {
        if (!$this->existsEvent($prj_uid, $eventUid)) {
            throw new Exception(G::LoadTranslation('ID_EMAIL_EVENT_DEFINITION_DOES_NOT_EXIST'));
        }
        $arrayData = $this->existsEvent($prj_uid, $eventUid);
        if (sizeof($arrayData)) {
            $emailServer = new EmailServer();
            if (empty($arrayData[7])){
                $configEmailData = $emailServer->getEmailServerDefault();
                //We will to show a message, if is not defined the email server default
                if(empty($configEmailData)){
                    $emailServer->throwExceptionIfNotExistsEmailServer('', 'MESS_UID');
                }
            } else {
                $configEmailData = $emailServer->getEmailServer($arrayData[7]);
            }

            $emailGroupTo = [];
            $emailTo = '';
            $prfUid = $arrayData[6];
            $filesManager = new FilesManager();
            $contentFile = $filesManager->getProcessFileManager($prj_uid, $prfUid);
            if (strpos($arrayData[4], ',')) {
                $emailsArray = explode(',', $arrayData[4]);
                foreach ($emailsArray as $email) {
                    if (substr($email, 0, 1) === '@') {
                        $email = substr($email, 2, strlen($email));
                        if (isset($arrayApplicationData['APP_DATA'])) {
                            if (is_array($arrayApplicationData['APP_DATA']) && isset($arrayApplicationData['APP_DATA'][$email])) {
                                $emailGroupTo[] = $arrayApplicationData['APP_DATA'][$email];
                            }
                        }
                    } else {
                        $emailGroupTo[] = $email;
                    }
                }
                $emailTo = implode(',', array_unique(array_filter($emailGroupTo)));
            } else {
                $email = $arrayData[4];
                if (substr($email, 0, 1) === '@') {
                    $email = substr($email, 2, strlen($email));
                    if (isset($arrayApplicationData['APP_DATA'])) {
                        if (is_array($arrayApplicationData['APP_DATA']) && isset($arrayApplicationData['APP_DATA'][$email])) {
                            $emailTo = $arrayApplicationData['APP_DATA'][$email];
                        }
                    }
                } else {
                    $emailTo = $email;
                }
            }
            if (!empty($emailTo)) {
                PMFSendMessage(
                    $appUID,
                    G::buildFrom($configEmailData),
                    $emailTo,
                    '',
                    '',
                    G::replaceDataField($arrayData[5], $arrayApplicationData['APP_DATA']),
                    $contentFile['prf_filename'],
                    [],
                    [],
                    true,
                    0,
                    $configEmailData
                );
            } else {
                Bootstrap::registerMonolog(
                    'EmailEventMailError',
                    200,
                    G::LoadTranslation('ID_EMAIL_EVENT_CONFIGURATION_EMAIL', [$eventUid, $prj_uid]),
                    ['eventUid' => $eventUid, 'prj_uid' => $prj_uid],
                    config('system.workspace'),
                    'processmaker.log');
            }
        }
    }

    /**
     * Update process file Uid
     *
     * @param string $oldUid Unique id of old process file
     * @param string $newUid Unique id of new process file
     * @param string $projectUid Unique id of Project
     *
     * @throws Exception
     */
    public function updatePrfUid($oldUid, $newUid, $projectUid)
    {
        try {
            $newValues = [];
            $rowData = $this->verifyIfEmailEventExistsByPrfUid($oldUid, $projectUid);
            if ($rowData) {
                $newValues['PRF_UID'] = $newUid;
                $this->update($rowData['EMAIL_EVENT_UID'], $newValues);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the Email Event of
     *
     * @param string $oldUid Unique id of old process file
     * @param string $projectUid Unique id of Project
     *
     * @return bool Return array if exists, false otherwise
     * @throws Exception
     */
    public function verifyIfEmailEventExistsByPrfUid($oldUid, $projectUid)
    {
        try {
            $criteria = $this->getEmailEventCriteria();
            $criteria->add(EmailEventPeer::PRJ_UID, $projectUid, Criteria::EQUAL);
            $criteria->add(EmailEventPeer::PRF_UID, $oldUid, Criteria::EQUAL);
            $rsCriteria = EmailEventPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $rsCriteria->next();
            $row = $rsCriteria->getRow();
            if ($row) {
                return array_change_key_case($row, CASE_UPPER);
            }
            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
