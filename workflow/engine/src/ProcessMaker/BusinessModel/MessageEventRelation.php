<?php
namespace ProcessMaker\BusinessModel;

class MessageEventRelation
{
    private $arrayFieldDefinition = array(
        "MSGER_UID"     => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "messageEventRelationUid"),

        "PRJ_UID"       => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "projectUid"),
        "EVN_UID_THROW" => array("type" => "string", "required" => true,  "empty" => false, "defaultValues" => array(), "fieldNameAux" => "eventUidThrow"),
        "EVN_UID_CATCH" => array("type" => "string", "required" => true,  "empty" => false, "defaultValues" => array(), "fieldNameAux" => "eventUidCatch")
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
     * Verify if exists the Message-Event-Relation
     *
     * @param string $messageEventRelationUid Unique id of Message-Event-Relation
     *
     * return bool Return true if exists the Message-Event-Relation, false otherwise
     */
    public function exists($messageEventRelationUid)
    {
        try {
            $obj = \MessageEventRelationPeer::retrieveByPK($messageEventRelationUid);

            return (!is_null($obj))? true : false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the Event-Relation of a Message-Event-Relation
     *
     * @param string $projectUid                       Unique id of Project
     * @param string $eventUidThrow                    Unique id of Event (throw)
     * @param string $eventUidCatch                    Unique id of Event (catch)
     * @param string $messageEventRelationUidToExclude Unique id of Message-Event-Relation to exclude
     *
     * return bool Return true if exists the Event-Relation of a Message-Event-Relation, false otherwise
     */
    public function existsEventRelation($projectUid, $eventUidThrow, $eventUidCatch, $messageEventRelationUidToExclude = "")
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\MessageEventRelationPeer::MSGER_UID);
            $criteria->add(\MessageEventRelationPeer::PRJ_UID, $projectUid, \Criteria::EQUAL);

            if ($messageEventRelationUidToExclude != "") {
                $criteria->add(\MessageEventRelationPeer::MSGER_UID, $messageEventRelationUidToExclude, \Criteria::NOT_EQUAL);
            }

            $criteria->add(\MessageEventRelationPeer::EVN_UID_THROW, $eventUidThrow, \Criteria::EQUAL);
            $criteria->add(\MessageEventRelationPeer::EVN_UID_CATCH, $eventUidCatch, \Criteria::EQUAL);

            $rsCriteria = \MessageEventRelationPeer::doSelectRS($criteria);

            return ($rsCriteria->next())? true : false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if does not exists the Message-Event-Relation
     *
     * @param string $messageEventRelationUid Unique id of Message-Event-Relation
     * @param string $fieldNameForException   Field name for the exception
     *
     * return void Throw exception if does not exists the Message-Event-Relation
     */
    public function throwExceptionIfNotExistsMessageEventRelation($messageEventRelationUid, $fieldNameForException)
    {
        try {
            if (!$this->exists($messageEventRelationUid)) {
                throw new \Exception(\G::LoadTranslation("ID_MESSAGE_EVENT_RELATION_DOES_NOT_EXIST", array($fieldNameForException, $messageEventRelationUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if is registered the Event-Relation
     *
     * @param string $projectUid                       Unique id of Project
     * @param string $eventUidThrow                    Unique id of Event (throw)
     * @param string $eventUidCatch                    Unique id of Event (catch)
     * @param string $messageEventRelationUidToExclude Unique id of Message-Event-Relation to exclude
     *
     * return void Throw exception if is registered the Event-Relation
     */
    public function throwExceptionIfEventRelationIsRegistered($projectUid, $eventUidThrow, $eventUidCatch, $messageEventRelationUidToExclude = "")
    {
        try {
            if ($this->existsEventRelation($projectUid, $eventUidThrow, $eventUidCatch, $messageEventRelationUidToExclude)) {
                throw new \Exception(\G::LoadTranslation("ID_MESSAGE_EVENT_RELATION_ALREADY_REGISTERED"));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate the data if they are invalid (INSERT and UPDATE)
     *
     * @param string $messageEventRelationUid Unique id of Message-Event-Relation
     * @param string $projectUid              Unique id of Project
     * @param array  $arrayData               Data
     *
     * return void Throw exception if data has an invalid value
     */
    public function throwExceptionIfDataIsInvalid($messageEventRelationUid, $projectUid, array $arrayData)
    {
        try {
            //Set variables
            $arrayMessageEventRelationData = ($messageEventRelationUid == "")? array() : $this->getMessageEventRelation($messageEventRelationUid, true);
            $flagInsert = ($messageEventRelationUid == "")? true : false;

            $arrayFinalData = array_merge($arrayMessageEventRelationData, $arrayData);

            //Verify data - Field definition
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfDataNotMetFieldDefinition($arrayData, $this->arrayFieldDefinition, $this->arrayFieldNameForException, $flagInsert);

            //Verify data
            if (isset($arrayData["EVN_UID_THROW"]) || isset($arrayData["EVN_UID_CATCH"])) {
                $this->throwExceptionIfEventRelationIsRegistered($projectUid, $arrayFinalData["EVN_UID_THROW"], $arrayFinalData["EVN_UID_CATCH"], $messageEventRelationUid);
            }

            if (isset($arrayData["EVN_UID_THROW"]) || isset($arrayData["EVN_UID_CATCH"])) {
                //Flow
                $bpmnFlow = \BpmnFlow::findOneBy(array(
                    \BpmnFlowPeer::FLO_TYPE                => "MESSAGE",
                    \BpmnFlowPeer::FLO_ELEMENT_ORIGIN      => $arrayFinalData["EVN_UID_THROW"],
                    \BpmnFlowPeer::FLO_ELEMENT_ORIGIN_TYPE => "bpmnEvent",
                    \BpmnFlowPeer::FLO_ELEMENT_DEST        => $arrayFinalData["EVN_UID_CATCH"],
                    \BpmnFlowPeer::FLO_ELEMENT_DEST_TYPE   => "bpmnEvent"
                ));

                if (is_null($bpmnFlow)) {
                    throw new \Exception(\G::LoadTranslation(
                        "ID_MESSAGE_EVENT_RELATION_DOES_NOT_EXIST_MESSAGE_FLOW",
                        array(
                            $this->arrayFieldNameForException["eventUidThrow"], $arrayFinalData["EVN_UID_THROW"],
                            $this->arrayFieldNameForException["eventUidCatch"], $arrayFinalData["EVN_UID_CATCH"]
                        )
                    ));
                }

                //Check and validate Message Flow
                $bpmn = new \ProcessMaker\Project\Bpmn();

                $bpmn->throwExceptionFlowIfIsAnInvalidMessageFlow(array(
                    "FLO_TYPE"                => "MESSAGE",
                    "FLO_ELEMENT_ORIGIN"      => $arrayFinalData["EVN_UID_THROW"],
                    "FLO_ELEMENT_ORIGIN_TYPE" => "bpmnEvent",
                    "FLO_ELEMENT_DEST"        => $arrayFinalData["EVN_UID_CATCH"],
                    "FLO_ELEMENT_DEST_TYPE"   => "bpmnEvent"
                ));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create Message-Event-Relation for a Project
     *
     * @param string $projectUid Unique id of Project
     * @param array  $arrayData  Data
     *
     * return array Return data of the new Message-Event-Relation created
     */
    public function create($projectUid, array $arrayData)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $validator = new \ProcessMaker\BusinessModel\Validator();

            $validator->throwExceptionIfDataIsNotArray($arrayData, "\$arrayData");
            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);

            unset($arrayData["MSGER_UID"]);
            unset($arrayData["PRJ_UID"]);

            //Verify data
            $process->throwExceptionIfNotExistsProcess($projectUid, $this->arrayFieldNameForException["projectUid"]);

            $this->throwExceptionIfDataIsInvalid("", $projectUid, $arrayData);

            //Create
            $cnn = \Propel::getConnection("workflow");

            try {
                $messageEventRelation = new \MessageEventRelation();

                $messageEventRelationUid = \ProcessMaker\Util\Common::generateUID();

                $messageEventRelation->fromArray($arrayData, \BasePeer::TYPE_FIELDNAME);

                $messageEventRelation->setMsgerUid($messageEventRelationUid);
                $messageEventRelation->setPrjUid($projectUid);

                if ($messageEventRelation->validate()) {
                    $cnn->begin();

                    $result = $messageEventRelation->save();

                    $cnn->commit();

                    //Return
                    return $this->getMessageEventRelation($messageEventRelationUid);
                } else {
                    $msg = "";

                    foreach ($messageEventRelation->getValidationFailures() as $validationFailure) {
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
     * Delete Message-Event-Relation
     *
     * @param array $arrayCondition Conditions
     *
     * return void
     */
    public function deleteWhere(array $arrayCondition)
    {
        try {
            //Delete
            $criteria = new \Criteria("workflow");

            foreach ($arrayCondition as $key => $value) {
                if (is_array($value)) {
                    $criteria->add($key, $value[0], $value[1]);
                } else {
                    $criteria->add($key, $value, \Criteria::EQUAL);
                }
            }

            $result = \MessageEventRelationPeer::doDelete($criteria);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get criteria for Message-Event-Relation
     *
     * return object
     */
    public function getMessageEventRelationCriteria()
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\MessageEventRelationPeer::MSGER_UID);
            $criteria->addSelectColumn(\MessageEventRelationPeer::PRJ_UID);
            $criteria->addSelectColumn(\MessageEventRelationPeer::EVN_UID_THROW);
            $criteria->addSelectColumn(\MessageEventRelationPeer::EVN_UID_CATCH);

            return $criteria;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Message-Event-Relation from a record
     *
     * @param array $record Record
     *
     * return array Return an array with data Message-Event-Relation
     */
    public function getMessageEventRelationDataFromRecord(array $record)
    {
        try {
            return array(
                $this->getFieldNameByFormatFieldName("MSGER_UID")     => $record["MSGER_UID"],
                $this->getFieldNameByFormatFieldName("EVN_UID_THROW") => $record["EVN_UID_THROW"],
                $this->getFieldNameByFormatFieldName("EVN_UID_CATCH") => $record["EVN_UID_CATCH"]
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Message-Event-Relation
     *
     * @param string $messageEventRelationUid Unique id of Message-Event-Relation
     * @param bool   $flagGetRecord           Value that set the getting
     *
     * return array Return an array with data of a Message-Event-Relation
     */
    public function getMessageEventRelation($messageEventRelationUid, $flagGetRecord = false)
    {
        try {
            //Verify data
            $this->throwExceptionIfNotExistsMessageEventRelation($messageEventRelationUid, $this->arrayFieldNameForException["messageEventRelationUid"]);

            //Get data
            $criteria = $this->getMessageEventRelationCriteria();

            $criteria->add(\MessageEventRelationPeer::MSGER_UID, $messageEventRelationUid, \Criteria::EQUAL);

            $rsCriteria = \MessageEventRelationPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            $rsCriteria->next();

            $row = $rsCriteria->getRow();

            //Return
            return (!$flagGetRecord)? $this->getMessageEventRelationDataFromRecord($row) : $row;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Message-Event-Relation
     *
     * @param array $arrayCondition Conditions
     * @param bool  $flagGetRecord  Value that set the getting
     *
     * return array Return an array with data of a Message-Event-Relation, otherwise null
     */
    public function getMessageEventRelationWhere(array $arrayCondition, $flagGetRecord = false)
    {
        try {
            //Get data
            $criteria = $this->getMessageEventRelationCriteria();

            foreach ($arrayCondition as $key => $value) {
                if (is_array($value)) {
                    $criteria->add($key, $value[0], $value[1]);
                } else {
                    $criteria->add($key, $value, \Criteria::EQUAL);
                }
            }

            $rsCriteria = \MessageEventRelationPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            if ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                //Return
                return (!$flagGetRecord)? $this->getMessageEventRelationDataFromRecord($row) : $row;
            } else {
                //Return
                return null;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

