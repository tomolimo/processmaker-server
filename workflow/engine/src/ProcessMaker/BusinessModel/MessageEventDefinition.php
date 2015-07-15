<?php
namespace ProcessMaker\BusinessModel;

class MessageEventDefinition
{
    private $arrayFieldDefinition = array(
        "MSGED_UID"         => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "messageEventDefinitionUid"),

        "PRJ_UID"           => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "projectUid"),
        "EVN_UID"           => array("type" => "string", "required" => true,  "empty" => false, "defaultValues" => array(), "fieldNameAux" => "eventUid"),
        "MSGT_UID"          => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "messageTypeUid"),
        "MSGED_USR_UID"     => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "messageEventDefinitionUserUid"),
        "MSGED_VARIABLES"   => array("type" => "array",  "required" => false, "empty" => true,  "defaultValues" => array(), "fieldNameAux" => "messageEventDefinitionVariables"),
        "MSGED_CORRELATION" => array("type" => "string", "required" => false, "empty" => true,  "defaultValues" => array(), "fieldNameAux" => "messageEventDefinitionCorrelation")
    );

    private $formatFieldNameInUppercase = true;

    private $arrayFieldNameForException = array();

    /**
     * Constructor of the class
     *
     * return void
     */
    public function __construct()
    {
        try {
            foreach ($this->arrayFieldDefinition as $key => $value) {
                $this->arrayFieldNameForException[$value["fieldNameAux"]] = $key;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set the format of the fields name (uppercase, lowercase)
     *
     * @param bool $flag Value that set the format
     *
     * return void
     */
    public function setFormatFieldNameInUppercase($flag)
    {
        try {
            $this->formatFieldNameInUppercase = $flag;

            $this->setArrayFieldNameForException($this->arrayFieldNameForException);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set exception messages for fields
     *
     * @param array $arrayData Data with the fields
     *
     * return void
     */
    public function setArrayFieldNameForException(array $arrayData)
    {
        try {
            foreach ($arrayData as $key => $value) {
                $this->arrayFieldNameForException[$key] = $this->getFieldNameByFormatFieldName($value);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the name of the field according to the format
     *
     * @param string $fieldName Field name
     *
     * return string Return the field name according the format
     */
    public function getFieldNameByFormatFieldName($fieldName)
    {
        try {
            return ($this->formatFieldNameInUppercase)? strtoupper($fieldName) : strtolower($fieldName);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the Message-Event-Definition
     *
     * @param string $messageEventDefinitionUid Unique id of Message-Event-Definition
     *
     * return bool Return true if exists the Message-Event-Definition, false otherwise
     */
    public function exists($messageEventDefinitionUid)
    {
        try {
            $obj = \MessageEventDefinitionPeer::retrieveByPK($messageEventDefinitionUid);

            return (!is_null($obj))? true : false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the Event of a Message-Event-Definition
     *
     * @param string $projectUid                         Unique id of Project
     * @param string $eventUid                           Unique id of Event
     * @param string $messageEventDefinitionUidToExclude Unique id of Message-Event-Definition to exclude
     *
     * return bool Return true if exists the Event of a Message-Event-Definition, false otherwise
     */
    public function existsEvent($projectUid, $eventUid, $messageEventDefinitionUidToExclude = "")
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\MessageEventDefinitionPeer::MSGED_UID);
            $criteria->add(\MessageEventDefinitionPeer::PRJ_UID, $projectUid, \Criteria::EQUAL);

            if ($messageEventDefinitionUidToExclude != "") {
                $criteria->add(\MessageEventDefinitionPeer::MSGED_UID, $messageEventDefinitionUidToExclude, \Criteria::NOT_EQUAL);
            }

            $criteria->add(\MessageEventDefinitionPeer::EVN_UID, $eventUid, \Criteria::EQUAL);

            $rsCriteria = \MessageEventDefinitionPeer::doSelectRS($criteria);

            return ($rsCriteria->next())? true : false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if does not exists the Message-Event-Definition
     *
     * @param string $messageEventDefinitionUid Unique id of Message-Event-Definition
     * @param string $fieldNameForException     Field name for the exception
     *
     * return void Throw exception if does not exists the Message-Event-Definition
     */
    public function throwExceptionIfNotExistsMessageEventDefinition($messageEventDefinitionUid, $fieldNameForException)
    {
        try {
            if (!$this->exists($messageEventDefinitionUid)) {
                throw new \Exception(\G::LoadTranslation("ID_MESSAGE_EVENT_DEFINITION_DOES_NOT_EXIST", array($fieldNameForException, $messageEventDefinitionUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if is registered the Event
     *
     * @param string $projectUid                         Unique id of Project
     * @param string $eventUid                           Unique id of Event
     * @param string $fieldNameForException              Field name for the exception
     * @param string $messageEventDefinitionUidToExclude Unique id of Message-Event-Definition to exclude
     *
     * return void Throw exception if is registered the Event
     */
    public function throwExceptionIfEventIsRegistered($projectUid, $eventUid, $fieldNameForException, $messageEventDefinitionUidToExclude = "")
    {
        try {
            if ($this->existsEvent($projectUid, $eventUid, $messageEventDefinitionUidToExclude)) {
                throw new \Exception(\G::LoadTranslation("ID_MESSAGE_EVENT_DEFINITION_ALREADY_REGISTERED", array($fieldNameForException, $eventUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate the data if they are invalid (INSERT and UPDATE)
     *
     * @param string $messageEventDefinitionUid Unique id of Message-Event-Definition
     * @param string $projectUid                Unique id of Project
     * @param array  $arrayData                 Data
     *
     * return void Throw exception if data has an invalid value
     */
    public function throwExceptionIfDataIsInvalid($messageEventDefinitionUid, $projectUid, array $arrayData)
    {
        try {
            //Set variables
            $arrayMessageEventDefinitionData = ($messageEventDefinitionUid == "")? array() : $this->getMessageEventDefinition($messageEventDefinitionUid, true);
            $flagInsert = ($messageEventDefinitionUid == "")? true : false;

            $arrayFinalData = array_merge($arrayMessageEventDefinitionData, $arrayData);

            //Verify data - Field definition
            $process = new \ProcessMaker\BusinessModel\Process();
            $messageType = new \ProcessMaker\BusinessModel\MessageType();

            $process->throwExceptionIfDataNotMetFieldDefinition($arrayData, $this->arrayFieldDefinition, $this->arrayFieldNameForException, $flagInsert);

            //Verify data
            if (isset($arrayData["EVN_UID"])) {
                $this->throwExceptionIfEventIsRegistered($projectUid, $arrayData["EVN_UID"], $this->arrayFieldNameForException["eventUid"], $messageEventDefinitionUid);
            }

            if (isset($arrayData["EVN_UID"])) {
                $arrayEventType   = array("START", "END", "INTERMEDIATE");
                $arrayEventMarker = array("MESSAGETHROW", "MESSAGECATCH");

                $bpmnEvent = \BpmnEventPeer::retrieveByPK($arrayData["EVN_UID"]);

                if (is_null($bpmnEvent)) {
                    throw new \Exception(\G::LoadTranslation("ID_EVENT_NOT_EXIST", array($this->arrayFieldNameForException["eventUid"], $arrayData["EVN_UID"])));
                }

                if (!in_array($bpmnEvent->getEvnType(), $arrayEventType) || !in_array($bpmnEvent->getEvnMarker(), $arrayEventMarker)) {
                    throw new \Exception(\G::LoadTranslation("ID_EVENT_NOT_IS_MESSAGE_EVENT", array($this->arrayFieldNameForException["eventUid"], $arrayData["EVN_UID"])));
                }
            }

            if (isset($arrayData["MSGT_UID"]) && $arrayData["MSGT_UID"] . "" != "") {
                $messageType->throwExceptionIfNotExistsMessageType($arrayData["MSGT_UID"], $this->arrayFieldNameForException["messageTypeUid"]);
            }

            $flagCheckData = false;
            $flagCheckData = (isset($arrayData["MSGT_UID"]) && $arrayData["MSGT_UID"] . "" != "")? true : $flagCheckData;
            $flagCheckData = (isset($arrayData["MSGED_VARIABLES"]))? true : $flagCheckData;

            if (isset($arrayFinalData["MSGT_UID"]) && $arrayFinalData["MSGT_UID"] . "" != "" && $flagCheckData) {
                $arrayMessageTypeVariable = array();

                $arrayMessageTypeData = $messageType->getMessageType($arrayFinalData["MSGT_UID"], true);

                foreach ($arrayMessageTypeData["MSGT_VARIABLES"] as $value) {
                    $arrayMessageTypeVariable[$value["MSGTV_NAME"]] = $value["MSGTV_DEFAULT_VALUE"];
                }

                if (count($arrayMessageTypeVariable) != count($arrayFinalData["MSGED_VARIABLES"]) || count(array_diff_key($arrayMessageTypeVariable, $arrayFinalData["MSGED_VARIABLES"])) > 0) {
                    throw new \Exception(\G::LoadTranslation("ID_MESSAGE_EVENT_DEFINITION_VARIABLES_DO_NOT_MEET_DEFINITION"));
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create Message-Event-Definition for a Project
     *
     * @param string $projectUid Unique id of Project
     * @param array  $arrayData  Data
     *
     * return array Return data of the new Message-Event-Definition created
     */
    public function create($projectUid, array $arrayData, $flagValidateArrayData = true)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $validator = new \ProcessMaker\BusinessModel\Validator();

            $validator->throwExceptionIfDataIsNotArray($arrayData, "\$arrayData");
            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);

            unset($arrayData["MSGED_UID"]);
            unset($arrayData["PRJ_UID"]);

            //Verify data
            $process->throwExceptionIfNotExistsProcess($projectUid, $this->arrayFieldNameForException["projectUid"]);

            if ($flagValidateArrayData) {
                $this->throwExceptionIfDataIsInvalid("", $projectUid, $arrayData);
            }

            //Create
            $cnn = \Propel::getConnection("workflow");

            try {
                $messageEventDefinition = new \MessageEventDefinition();

                if (!isset($arrayData["MSGT_UID"]) || $arrayData["MSGT_UID"] . "" == "") {
                    $arrayData["MSGT_UID"] = "";
                    $arrayData["MSGED_VARIABLES"] = array();
                }

                if (!isset($arrayData["MSGED_VARIABLES"])) {
                    $arrayData["MSGED_VARIABLES"] = array();
                }

                $messageEventDefinitionUid = \ProcessMaker\Util\Common::generateUID();

                if (isset($arrayData["MSGED_VARIABLES"])) {
                    $arrayData["MSGED_VARIABLES"] = serialize($arrayData["MSGED_VARIABLES"]);
                }

                $messageEventDefinition->fromArray($arrayData, \BasePeer::TYPE_FIELDNAME);

                $messageEventDefinition->setMsgedUid($messageEventDefinitionUid);
                $messageEventDefinition->setPrjUid($projectUid);
                $messageEventDefinition->setMsgedUsrUid("00000000000000000000000000000001"); //admin

                if ($messageEventDefinition->validate()) {
                    $cnn->begin();

                    $result = $messageEventDefinition->save();

                    $cnn->commit();

                    //Return
                    return $this->getMessageEventDefinition($messageEventDefinitionUid);
                } else {
                    $msg = "";

                    foreach ($messageEventDefinition->getValidationFailures() as $validationFailure) {
                        $msg = $msg . (($msg != "")? "\n" : "") . $validationFailure->getMessage();
                    }

                    throw new \Exception(\G::LoadTranslation("ID_RECORD_CANNOT_BE_CREATED") . (($msg != "")? "\n" . $msg : ""));
                }
            } catch (\Exception $e) {
                $cnn->rollback();

                throw $e;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update Message-Event-Definition
     *
     * @param string $messageEventDefinitionUid Unique id of Message-Event-Definition
     * @param array  $arrayData                 Data
     *
     * return array Return data of the Message-Event-Definition updated
     */
    public function update($messageEventDefinitionUid, array $arrayData)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $validator = new \ProcessMaker\BusinessModel\Validator();

            $validator->throwExceptionIfDataIsNotArray($arrayData, "\$arrayData");
            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);
            $arrayDataBackup = $arrayData;

            unset($arrayData["MSGED_UID"]);
            unset($arrayData["PRJ_UID"]);

            //Set variables
            $arrayMessageEventDefinitionData = $this->getMessageEventDefinition($messageEventDefinitionUid, true);

            //Verify data
            $this->throwExceptionIfNotExistsMessageEventDefinition($messageEventDefinitionUid, $this->arrayFieldNameForException["messageEventDefinitionUid"]);

            $this->throwExceptionIfDataIsInvalid($messageEventDefinitionUid, $arrayMessageEventDefinitionData["PRJ_UID"], $arrayData);

            //Update
            $cnn = \Propel::getConnection("workflow");

            try {
                $messageEventDefinition = \MessageEventDefinitionPeer::retrieveByPK($messageEventDefinitionUid);

                if (isset($arrayData["MSGT_UID"]) && $arrayData["MSGT_UID"] . "" == "") {
                    $arrayData["MSGED_VARIABLES"] = array();
                }

                if (isset($arrayData["MSGED_VARIABLES"])) {
                    $arrayData["MSGED_VARIABLES"] = serialize($arrayData["MSGED_VARIABLES"]);
                }

                $messageEventDefinition->fromArray($arrayData, \BasePeer::TYPE_FIELDNAME);

                if ($messageEventDefinition->validate()) {
                    $cnn->begin();

                    $result = $messageEventDefinition->save();

                    $cnn->commit();

                    //Return
                    $arrayData = $arrayDataBackup;

                    if (!$this->formatFieldNameInUppercase) {
                        $arrayData = array_change_key_case($arrayData, CASE_LOWER);
                    }

                    return $arrayData;
                } else {
                    $msg = "";

                    foreach ($messageEventDefinition->getValidationFailures() as $validationFailure) {
                        $msg = $msg . (($msg != "")? "\n" : "") . $validationFailure->getMessage();
                    }

                    throw new \Exception(\G::LoadTranslation("ID_REGISTRY_CANNOT_BE_UPDATED") . (($msg != "")? "\n" . $msg : ""));
                }
            } catch (\Exception $e) {
                $cnn->rollback();

                throw $e;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete Message-Event-Definition
     *
     * @param string $messageEventDefinitionUid Unique id of Message-Event-Definition
     *
     * return void
     */
    public function delete($messageEventDefinitionUid)
    {
        try {
            //Verify data
            $this->throwExceptionIfNotExistsMessageEventDefinition($messageEventDefinitionUid, $this->arrayFieldNameForException["messageEventDefinitionUid"]);

            //Delete
            $criteria = new \Criteria("workflow");

            $criteria->add(\MessageEventDefinitionPeer::MSGED_UID, $messageEventDefinitionUid, \Criteria::EQUAL);

            $result = \MessageEventDefinitionPeer::doDelete($criteria);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set Message-Event-Definition-Variables by Message-Type for a record
     *
     * @param array $record Record
     *
     * return array Return the record
     */
    public function setMessageEventDefinitionVariablesForRecordByMessageType(array $record)
    {
        try {
            $record["MSGED_VARIABLES"] = ($record["MSGED_VARIABLES"] . "" != "")? unserialize($record["MSGED_VARIABLES"]) : array();

            if ($record["MSGT_UID"] . "" != "") {
                $arrayMessageTypeVariable = array();

                $messageType = new \ProcessMaker\BusinessModel\MessageType();

                if ($messageType->exists($record["MSGT_UID"])) {
                    $arrayMessageTypeData = $messageType->getMessageType($record["MSGT_UID"], true);

                    foreach ($arrayMessageTypeData["MSGT_VARIABLES"] as $value) {
                        $arrayMessageTypeVariable[$value["MSGTV_NAME"]] = (isset($record["MSGED_VARIABLES"][$value["MSGTV_NAME"]]))? $record["MSGED_VARIABLES"][$value["MSGTV_NAME"]] : $value["MSGTV_DEFAULT_VALUE"];
                    }
                }

                $record["MSGED_VARIABLES"] = $arrayMessageTypeVariable;
            }

            //Return
            return $record;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get criteria for Message-Event-Definition
     *
     * return object
     */
    public function getMessageEventDefinitionCriteria()
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\MessageEventDefinitionPeer::MSGED_UID);
            $criteria->addSelectColumn(\MessageEventDefinitionPeer::PRJ_UID);
            $criteria->addSelectColumn(\MessageEventDefinitionPeer::EVN_UID);
            $criteria->addSelectColumn(\MessageEventDefinitionPeer::MSGT_UID);
            $criteria->addSelectColumn(\MessageEventDefinitionPeer::MSGED_USR_UID);
            $criteria->addSelectColumn(\MessageEventDefinitionPeer::MSGED_VARIABLES);
            $criteria->addSelectColumn(\MessageEventDefinitionPeer::MSGED_CORRELATION);

            return $criteria;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Message-Event-Definition from a record
     *
     * @param array $record Record
     *
     * return array Return an array with data Message-Event-Definition
     */
    public function getMessageEventDefinitionDataFromRecord(array $record)
    {
        try {
            return array(
                $this->getFieldNameByFormatFieldName("MSGED_UID")         => $record["MSGED_UID"],
                $this->getFieldNameByFormatFieldName("EVN_UID")           => $record["EVN_UID"],
                $this->getFieldNameByFormatFieldName("MSGT_UID")          => $record["MSGT_UID"] . "",
                $this->getFieldNameByFormatFieldName("MSGED_USR_UID")     => $record["MSGED_USR_UID"] . "",
                $this->getFieldNameByFormatFieldName("MSGED_VARIABLES")   => $record["MSGED_VARIABLES"],
                $this->getFieldNameByFormatFieldName("MSGED_CORRELATION") => $record["MSGED_CORRELATION"] . ""
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all Message-Event-Definitions
     *
     * @param string $projectUid Unique id of Project
     *
     * return array Return an array with all Message-Event-Definitions
     */
    public function getMessageEventDefinitions($projectUid)
    {
        try {
            $arrayMessageEventDefinition = array();

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfNotExistsProcess($projectUid, $this->arrayFieldNameForException["projectUid"]);

            //Get data
            $criteria = $this->getMessageEventDefinitionCriteria();

            $criteria->add(\MessageEventDefinitionPeer::PRJ_UID, $projectUid, \Criteria::EQUAL);

            $rsCriteria = \MessageEventDefinitionPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $this->setMessageEventDefinitionVariablesForRecordByMessageType($rsCriteria->getRow());

                $arrayMessageEventDefinition[] = $this->getMessageEventDefinitionDataFromRecord($row);
            }

            //Return
            return $arrayMessageEventDefinition;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Message-Event-Definition
     *
     * @param string $messageEventDefinitionUid Unique id of Message-Event-Definition
     * @param bool   $flagGetRecord             Value that set the getting
     *
     * return array Return an array with data of a Message-Event-Definition
     */
    public function getMessageEventDefinition($messageEventDefinitionUid, $flagGetRecord = false)
    {
        try {
            //Verify data
            $this->throwExceptionIfNotExistsMessageEventDefinition($messageEventDefinitionUid, $this->arrayFieldNameForException["messageEventDefinitionUid"]);

            //Get data
            $criteria = $this->getMessageEventDefinitionCriteria();

            $criteria->add(\MessageEventDefinitionPeer::MSGED_UID, $messageEventDefinitionUid, \Criteria::EQUAL);

            $rsCriteria = \MessageEventDefinitionPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            $rsCriteria->next();

            $row = $this->setMessageEventDefinitionVariablesForRecordByMessageType($rsCriteria->getRow());

            //Return
            return (!$flagGetRecord)? $this->getMessageEventDefinitionDataFromRecord($row) : $row;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Message-Event-Definition by unique id of Event
     *
     * @param string $projectUid    Unique id of Project
     * @param string $eventUid      Unique id of Event
     * @param bool   $flagGetRecord Value that set the getting
     *
     * return array Return an array with data of a Message-Event-Definition by unique id of Event
     */
    public function getMessageEventDefinitionByEvent($projectUid, $eventUid, $flagGetRecord = false)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfNotExistsProcess($projectUid, $this->arrayFieldNameForException["projectUid"]);

            if (!$this->existsEvent($projectUid, $eventUid)) {
                throw new \Exception(\G::LoadTranslation("ID_MESSAGE_EVENT_DEFINITION_DOES_NOT_IS_REGISTERED", array($this->arrayFieldNameForException["eventUid"], $eventUid)));
            }

            //Get data
            $criteria = $this->getMessageEventDefinitionCriteria();

            $criteria->add(\MessageEventDefinitionPeer::PRJ_UID, $projectUid, \Criteria::EQUAL);
            $criteria->add(\MessageEventDefinitionPeer::EVN_UID, $eventUid, \Criteria::EQUAL);

            $rsCriteria = \MessageEventDefinitionPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            $rsCriteria->next();

            $row = $this->setMessageEventDefinitionVariablesForRecordByMessageType($rsCriteria->getRow());

            //Return
            return (!$flagGetRecord)? $this->getMessageEventDefinitionDataFromRecord($row) : $row;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

