<?php
namespace ProcessMaker\BusinessModel\MessageType;

class Variable
{
    private $arrayFieldDefinition = array(
        "MSGTV_UID"           => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "messageTypeVariableUid"),
        "MSGT_UID"            => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "messageTypeUid"),
        "MSGTV_NAME"          => array("type" => "string", "required" => true,  "empty" => false, "defaultValues" => array(), "fieldNameAux" => "messageTypeVariableName"),
        "MSGTV_DEFAULT_VALUE" => array("type" => "string", "required" => false, "empty" => true,  "defaultValues" => array(), "fieldNameAux" => "messageTypeVariableDefaultValue")
    );

    private $formatFieldNameInUppercase = true;

    private $arrayFieldNameForException = array(
        "start"  => "START",
        "limit"  => "LIMIT"
    );

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
     * Verify if exists the Message-Type-Variable
     *
     * @param string $messageTypeVariableUid Unique id of Message-Type-Variable
     *
     * return bool Return true if exists the Message-Type-Variable, false otherwise
     */
    public function exists($messageTypeVariableUid)
    {
        try {
            $obj = \MessageTypeVariablePeer::retrieveByPK($messageTypeVariableUid);

            return (!is_null($obj))? true : false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the Name of a Message-Type-Variable
     *
     * @param string $messageTypeUid                  Unique id of Project
     * @param string $messageTypeVariableName         Name
     * @param string $messageTypeVariableUidToExclude Unique id of Message-Type-Variable to exclude
     *
     * return bool Return true if exists the Name of a Message-Type-Variable, false otherwise
     */
    public function existsName($messageTypeUid, $messageTypeVariableName, $messageTypeVariableUidToExclude = "")
    {
        try {
            $criteria = $this->getMessageTypeVariableCriteria();

            if ($messageTypeVariableUidToExclude != "") {
                $criteria->add(\MessageTypeVariablePeer::MSGTV_UID, $messageTypeVariableUidToExclude, \Criteria::NOT_EQUAL);
            }

            $criteria->add(\MessageTypeVariablePeer::MSGT_UID, $messageTypeUid, \Criteria::EQUAL);
            $criteria->add(\MessageTypeVariablePeer::MSGTV_NAME, $messageTypeVariableName, \Criteria::EQUAL);

            //QUERY
            $rsCriteria = \MessageTypeVariablePeer::doSelectRS($criteria);

            return ($rsCriteria->next())? true : false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the Name of a Message-Type-Variable
     *
     * @param string $messageTypeUid                  Unique id of Project
     * @param string $messageTypeVariableName         Name
     * @param string $fieldNameForException           Field name for the exception
     * @param string $messageTypeVariableUidToExclude Unique id of Message to exclude
     *
     * return void Throw exception if exists the Name of a Message-Type-Variable
     */
    public function throwExceptionIfExistsName($messageTypeUid, $messageTypeVariableName, $fieldNameForException, $messageTypeVariableUidToExclude = "")
    {
        try {
            if ($this->existsName($messageTypeUid, $messageTypeVariableName, $messageTypeVariableUidToExclude)) {
                throw new \Exception(\G::LoadTranslation("ID_MESSAGE_TYPE_VARIABLE_NAME_ALREADY_EXISTS", array($fieldNameForException, $messageTypeVariableName)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate the data if they are invalid (INSERT and UPDATE)
     *
     * @param string $messageTypeVariableUid Unique id of Message-Type-Variable
     * @param string $messageTypeUid         Unique id of Project
     * @param array  $arrayData              Data
     *
     * return void Throw exception if data has an invalid value
     */
    public function throwExceptionIfDataIsInvalid($messageTypeVariableUid, $messageTypeUid, array $arrayData)
    {
        try {
            //Set variables
            $arrayMsgTypeVarData = ($messageTypeVariableUid == "")? array() : $this->getMessageTypeVariable($messageTypeVariableUid, true);
            $flagInsert      = ($messageTypeVariableUid == "")? true : false;

            $arrayFinalData = array_merge($arrayMsgTypeVarData, $arrayData);

            //Verify data - Field definition
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfDataNotMetFieldDefinition($arrayData, $this->arrayFieldDefinition, $this->arrayFieldNameForException, $flagInsert);

            //Verify data
            if (isset($arrayData["MSGTV_NAME"])) {
                $this->throwExceptionIfExistsName($messageTypeUid, $arrayData["MSGTV_NAME"], $this->arrayFieldNameForException["messageTypeVariableName"], $messageTypeVariableUid);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if does not exist the Message-Type-Variable
     *
     * @param string $messageTypeVariableUid Unique id of Message-Type-Variable
     * @param string $fieldNameForException  Field name for the exception
     *
     * return void Throw exception if does not exist the Message-Type-Variable
     */
    public function throwExceptionIfNotExistsMessageTypeVariable($messageTypeVariableUid, $fieldNameForException)
    {
        try {
            if (!$this->exists($messageTypeVariableUid)) {
                throw new \Exception(\G::LoadTranslation("ID_MESSAGE_TYPE_VARIABLE_DOES_NOT_EXIST", array($fieldNameForException, $messageTypeVariableUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create Variable
     *
     * @param string $messageUid Unique id of Project
     * @param array  $arrayData  Data
     *
     * return array Return data of the new Message created
     */
    public function create($messageTypeUid, array $arrayData)
    {
        try {
            //Verify data
            $validator = new \ProcessMaker\BusinessModel\Validator();
            $messageType = new \ProcessMaker\BusinessModel\MessageType();

            $validator->throwExceptionIfDataIsNotArray($arrayData, "\$arrayData");
            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);

            unset($arrayData["MSGTV_UID"]);
            unset($arrayData["MSGT_UID"]);

            //Verify data
            $messageType->throwExceptionIfNotExistsMessageType($messageTypeUid, $this->arrayFieldNameForException["messageTypeUid"]);

            $this->throwExceptionIfDataIsInvalid("", $messageTypeUid, $arrayData);

            //Create
            $cnn = \Propel::getConnection("workflow");

            try {
                $messageTypeVariable = new \MessageTypeVariable();

                $messageTypeVariable->fromArray($arrayData, \BasePeer::TYPE_FIELDNAME);

                $messageTypeVariableUid = \ProcessMaker\Util\Common::generateUID();

                $messageTypeVariable->setMsgtvUid($messageTypeVariableUid);
                $messageTypeVariable->setMsgtUid($messageTypeUid);

                if ($messageTypeVariable->validate()) {
                    $cnn->begin();

                    $result = $messageTypeVariable->save();

                    $cnn->commit();

                    //Return
                    return $this->getMessageTypeVariable($messageTypeVariableUid);
                } else {
                    $msg = "";

                    foreach ($messageTypeVariable->getValidationFailures() as $validationFailure) {
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
     * Single create Message-Type-Variable
     *
     * @param array $arrayData Data
     *
     * return int Return integer
     */
    public function singleCreate(array $arrayData)
    {
        try {
            $cnn = \Propel::getConnection("workflow");

            try {
                $messageTypeVariable = new \MessageTypeVariable();

                $messageTypeVariable->fromArray($arrayData, \BasePeer::TYPE_FIELDNAME);

                if ($messageTypeVariable->validate()) {
                    $cnn->begin();

                    $result = $messageTypeVariable->save();

                    $cnn->commit();

                    //Return
                    return $result;
                } else {
                    $msg = "";

                    foreach ($messageTypeVariable->getValidationFailures() as $validationFailure) {
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
     * Update Message-Type-Variable
     *
     * @param string $messageTypeVariable Uid Unique id of Message-Type-Variable
     * @param array  $arrayData           Data
     *
     * return array Return data of the Message-Type-Variable updated
     */
    public function update($messageTypeVariableUid, array $arrayData)
    {
        try {
            //Verify data
            $validator = new \ProcessMaker\BusinessModel\Validator();

            $validator->throwExceptionIfDataIsNotArray($arrayData, "\$arrayData");
            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);

            unset($arrayData["MSGTV_UID"]);
            unset($arrayData["MSGT_UID"]);

            //Set variables
            $arrayMessageTypeVariableData = $this->getMessageTypeVariable($messageTypeVariableUid, true);

            //Verify data
            $this->throwExceptionIfNotExistsMessageTypeVariable($messageTypeVariableUid, $this->arrayFieldNameForException["messageTypeVariableUid"]);

            $this->throwExceptionIfDataIsInvalid($messageTypeVariableUid, $arrayMessageTypeVariableData["MSGT_UID"], $arrayData);

            //Update
            $cnn = \Propel::getConnection("workflow");

            try {
                $messageTypeVariable = \MessageTypeVariablePeer::retrieveByPK($messageTypeVariableUid);
                $messageTypeVariable->fromArray($arrayData, \BasePeer::TYPE_FIELDNAME);

                if ($messageTypeVariable->validate()) {
                    $cnn->begin();

                    $result = $messageTypeVariable->save();

                    $cnn->commit();

                    //Return
                    if (!$this->formatFieldNameInUppercase) {
                        $arrayData = array_change_key_case($arrayData, CASE_LOWER);
                    }

                    return $arrayData;
                } else {
                    $msg = "";

                    foreach ($messageTypeVariable->getValidationFailures() as $validationFailure) {
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
     * Delete Message-Type-Variable
     *
     * @param string $messageTypeVariable Uid Unique id of Message-Type
     *
     * return void
     */
    public function delete($messageTypeVariableUid)
    {
        try {
            $this->throwExceptionIfNotExistsMessageTypeVariable($messageTypeVariableUid, $this->arrayFieldNameForException["messageTypeVariableUid"]);

            $criteria = $this->getMessageTypeVariableCriteria();

            $criteria->add(\MessageTypeVariablePeer::MSGTV_UID, $messageTypeVariableUid, \Criteria::EQUAL);

            \MessageTypeVariablePeer::doDelete($criteria);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get criteria for Message-Type-Variable
     *
     * return object
     */
    public function getMessageTypeVariableCriteria()
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\MessageTypeVariablePeer::MSGTV_UID);
            $criteria->addSelectColumn(\MessageTypeVariablePeer::MSGT_UID);
            $criteria->addSelectColumn(\MessageTypeVariablePeer::MSGTV_NAME);
            $criteria->addSelectColumn(\MessageTypeVariablePeer::MSGTV_DEFAULT_VALUE);

            return $criteria;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a from a record
     *
     * @param array $record Record
     *
     * return array Return an array with data Message-Type-Variable
     */
    public function getMessageTypeVariableDataFromRecord(array $record, $includeUid = true)
    {
        try {
            $arrayRecord = array();

            if ($includeUid) {
                $arrayRecord[$this->getFieldNameByFormatFieldName("MSGTV_UID")] = $record["MSGTV_UID"];
            }

            $arrayRecord[$this->getFieldNameByFormatFieldName("MSGTV_NAME")]          = $record["MSGTV_NAME"];
            $arrayRecord[$this->getFieldNameByFormatFieldName("MSGTV_DEFAULT_VALUE")] = $record["MSGTV_DEFAULT_VALUE"] . "";

            return $arrayRecord;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all Message-Type-Variable
     *
     * @param string $messageTypeUid  {@min 32}{@max 32}
     * @param array  $arrayFilterData Data of the filters
     * @param string $sortField       Field name to sort
     * @param string $sortDir         Direction of sorting (ASC, DESC)
     * @param int    $start           Start
     * @param int    $limit           Limit
     *
     * return array Return an array with all Message-Type-Variable
     */
    public function getMessageTypeVariables($messageTypeUid, $arrayFilterData = null, $sortField = null, $sortDir = null, $start = null, $limit = null)
    {
        try {
            $arrayMessage = array();

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $messageType = new \ProcessMaker\BusinessModel\MessageType();

            $messageType->throwExceptionIfNotExistsMessageType($messageTypeUid, $this->arrayFieldNameForException["messageTypeUid"]);

            $process->throwExceptionIfDataNotMetPagerVarDefinition(array("start" => $start, "limit" => $limit), $this->arrayFieldNameForException);

            //Get data
            if (!is_null($limit) && $limit . "" == "0") {
                return $arrayMessage;
            }

            //SQL
            $criteria = $this->getMessageTypeVariableCriteria();
            $criteria->add(\MessageTypeVariablePeer::MSGT_UID, $messageTypeUid, \Criteria::EQUAL);

            if (!is_null($arrayFilterData) && is_array($arrayFilterData) && isset($arrayFilterData["filter"]) && trim($arrayFilterData["filter"]) != "") {
                $criteria->add(
                    $criteria->getNewCriterion(\MessageTypeVariablePeer::MSGTV_NAME, "%" . $arrayFilterData["filter"] . "%", \Criteria::LIKE)
                );
            }

            //Number records total
            $criteriaCount = clone $criteria;

            $criteriaCount->clearSelectColumns();
            $criteriaCount->addSelectColumn("COUNT(" . \MessageTypeVariablePeer::MSGTV_UID . ") AS NUM_REC");

            $rsCriteriaCount = \MessageTypeVariablePeer::doSelectRS($criteriaCount);
            $rsCriteriaCount->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            $rsCriteriaCount->next();
            $row = $rsCriteriaCount->getRow();

            $numRecTotal = $row["NUM_REC"];

            //SQL
            if (!is_null($sortField) && trim($sortField) != "") {
                $sortField = strtoupper($sortField);

                if (in_array($sortField, array("MSGTV_NAME"))) {
                    $sortField = \MessageTypeVariablePeer::TABLE_NAME . "." . $sortField;
                } else {
                    $sortField = \MessageTypeVariablePeer::MSGTV_NAME;
                }
            } else {
                $sortField = \MessageTypeVariablePeer::MSGTV_NAME;
            }

            if (!is_null($sortDir) && trim($sortDir) != "" && strtoupper($sortDir) == "DESC") {
                $criteria->addDescendingOrderByColumn($sortField);
            } else {
                $criteria->addAscendingOrderByColumn($sortField);
            }

            if (!is_null($start)) {
                $criteria->setOffset((int)($start));
            }

            if (!is_null($limit)) {
                $criteria->setLimit((int)($limit));
            }

            $rsCriteria = \MessageTypeVariablePeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                $arrayMessage[] = $this->getMessageTypeVariableDataFromRecord($row);
            }

            //Return
            return array(
                "total"  => $numRecTotal,
                "start"  => (int)((!is_null($start))? $start : 0),
                "limit"  => (int)((!is_null($limit))? $limit : 0),
                "filter" => (!is_null($arrayFilterData) && is_array($arrayFilterData) && isset($arrayFilterData["filter"]))? $arrayFilterData["filter"] : "",
                "data"   => $arrayMessage
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Message-Type-Variable
     *
     * @param string $messageTypeVariableUid Unique id of Message-Type-Variable
     * @param bool   $flagGetRecord          Value that set the getting
     *
     * return array Return an array with data of a Message-Type-Variable
     */
    public function getMessageTypeVariable($messageTypeVariableUid, $flagGetRecord = false)
    {
        try {
            //Verify data
            $this->throwExceptionIfNotExistsMessageTypeVariable($messageTypeVariableUid, $this->arrayFieldNameForException["messageTypeVariableUid"]);

            //Get data
            //SQL
            $criteria = $this->getMessageTypeVariableCriteria();

            $criteria->add(\MessageTypeVariablePeer::MSGTV_UID, $messageTypeVariableUid, \Criteria::EQUAL);

            $rsCriteria = \MessageTypeVariablePeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            $rsCriteria->next();

            $row = $rsCriteria->getRow();

            //Return
            return (!$flagGetRecord)? $this->getMessageTypeVariableDataFromRecord($row) : $row;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

