<?php
namespace ProcessMaker\BusinessModel;

class ElementTaskRelation
{
    private $arrayFieldDefinition = array(
        "ETR_UID"      => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "elementTaskRelationUid"),

        "PRJ_UID"      => array("type" => "string", "required" => false, "empty" => false, "defaultValues" => array(), "fieldNameAux" => "projectUid"),
        "ELEMENT_UID"  => array("type" => "string", "required" => true,  "empty" => false, "defaultValues" => array(), "fieldNameAux" => "elementUid"),
        "ELEMENT_TYPE" => array("type" => "string", "required" => true,  "empty" => false, "defaultValues" => array(), "fieldNameAux" => "elementType"),
        "TAS_UID"      => array("type" => "string", "required" => true,  "empty" => false, "defaultValues" => array(), "fieldNameAux" => "taskUid")
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
     * Verify if exists the Element-Task-Relation
     *
     * @param string $elementTaskRelationUid Unique id of Element-Task-Relation
     *
     * return bool Return true if exists the Element-Task-Relation, false otherwise
     */
    public function exists($elementTaskRelationUid)
    {
        try {
            $obj = \ElementTaskRelationPeer::retrieveByPK($elementTaskRelationUid);

            return (!is_null($obj))? true : false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if does not exists the Element-Task-Relation
     *
     * @param string $elementTaskRelationUid Unique id of Element-Task-Relation
     * @param string $fieldNameForException  Field name for the exception
     *
     * return void Throw exception if does not exists the Element-Task-Relation
     */
    public function throwExceptionIfNotExistsElementTaskRelation($elementTaskRelationUid, $fieldNameForException)
    {
        try {
            if (!$this->exists($elementTaskRelationUid)) {
                throw new \Exception(\G::LoadTranslation("ID_MESSAGE_EVENT_TASK_RELATION_DOES_NOT_EXIST", array($fieldNameForException, $elementTaskRelationUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate the data if they are invalid (INSERT and UPDATE)
     *
     * @param string $elementTaskRelationUid Unique id of Element-Task-Relation
     * @param string $projectUid             Unique id of Project
     * @param array  $arrayData              Data
     *
     * return void Throw exception if data has an invalid value
     */
    public function throwExceptionIfDataIsInvalid($elementTaskRelationUid, $projectUid, array $arrayData)
    {
        try {
            //Set variables
            $arrayElementTaskRelationData = ($elementTaskRelationUid == "")? array() : $this->getElementTaskRelation($elementTaskRelationUid, true);
            $flagInsert = ($elementTaskRelationUid == "")? true : false;

            $arrayFinalData = array_merge($arrayElementTaskRelationData, $arrayData);

            //Verify data - Field definition
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfDataNotMetFieldDefinition($arrayData, $this->arrayFieldDefinition, $this->arrayFieldNameForException, $flagInsert);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create Element-Task-Relation for a Project
     *
     * @param string $projectUid Unique id of Project
     * @param array  $arrayData  Data
     *
     * return array Return data of the new Element-Task-Relation created
     */
    public function create($projectUid, array $arrayData, $verifyPrjUid = true)
    {
        try {
            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();
            $validator = new \ProcessMaker\BusinessModel\Validator();

            $validator->throwExceptionIfDataIsNotArray($arrayData, "\$arrayData");
            $validator->throwExceptionIfDataIsEmpty($arrayData, "\$arrayData");

            //Set data
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);

            unset($arrayData["ETR_UID"]);
            unset($arrayData["PRJ_UID"]);

            //Verify data
            if($verifyPrjUid){
                $process->throwExceptionIfNotExistsProcess($projectUid, $this->arrayFieldNameForException["projectUid"]);
            }

            $this->throwExceptionIfDataIsInvalid("", $projectUid, $arrayData);

            //Create
            $cnn = \Propel::getConnection("workflow");

            try {
                $elementTaskRelation = new \ElementTaskRelation();

                $elementTaskRelationUid = \ProcessMaker\Util\Common::generateUID();

                $elementTaskRelation->fromArray($arrayData, \BasePeer::TYPE_FIELDNAME);

                $elementTaskRelation->setEtrUid($elementTaskRelationUid);
                $elementTaskRelation->setPrjUid($projectUid);

                if ($elementTaskRelation->validate()) {
                    $cnn->begin();

                    $result = $elementTaskRelation->save();

                    $cnn->commit();

                    //Return
                    return $this->getElementTaskRelation($elementTaskRelationUid);
                } else {
                    $msg = "";

                    foreach ($elementTaskRelation->getValidationFailures() as $validationFailure) {
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

    public function existsElementUid($elementUid)
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\ElementTaskRelationPeer::ETR_UID);
            $criteria->addSelectColumn(\ElementTaskRelationPeer::PRJ_UID);
            $criteria->addSelectColumn(\ElementTaskRelationPeer::ELEMENT_UID);
            $criteria->addSelectColumn(\ElementTaskRelationPeer::ELEMENT_TYPE);
            $criteria->addSelectColumn(\ElementTaskRelationPeer::TAS_UID);
            $criteria->add( \ElementTaskRelationPeer::ELEMENT_UID, $elementUid );
            $rs = \ElementTaskRelationPeer::doSelectRS( $criteria );
            $rs->setFetchmode( \ResultSet::FETCHMODE_ASSOC );
            if ($rs->next()) {
                return true;
            } else {
                return false;
            }

            return $criteria;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete Element-Task-Relation
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

            $result = \ElementTaskRelationPeer::doDelete($criteria);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get criteria for Element-Task-Relation
     *
     * return object
     */
    public function getElementTaskRelationCriteria()
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\ElementTaskRelationPeer::ETR_UID);
            $criteria->addSelectColumn(\ElementTaskRelationPeer::PRJ_UID);
            $criteria->addSelectColumn(\ElementTaskRelationPeer::ELEMENT_UID);
            $criteria->addSelectColumn(\ElementTaskRelationPeer::ELEMENT_TYPE);
            $criteria->addSelectColumn(\ElementTaskRelationPeer::TAS_UID);

            return $criteria;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Element-Task-Relation from a record
     *
     * @param array $record Record
     *
     * return array Return an array with data Element-Task-Relation
     */
    public function getElementTaskRelationDataFromRecord(array $record)
    {
        try {
            return array(
                $this->getFieldNameByFormatFieldName("ETR_UID")      => $record["ETR_UID"],
                $this->getFieldNameByFormatFieldName("ELEMENT_UID")  => $record["ELEMENT_UID"],
                $this->getFieldNameByFormatFieldName("ELEMENT_TYPE") => $record["ELEMENT_TYPE"],
                $this->getFieldNameByFormatFieldName("TAS_UID")      => $record["TAS_UID"]
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Element-Task-Relation
     *
     * @param string $elementTaskRelationUid Unique id of Element-Task-Relation
     * @param bool   $flagGetRecord          Value that set the getting
     *
     * return array Return an array with data of a Element-Task-Relation
     */
    public function getElementTaskRelation($elementTaskRelationUid, $flagGetRecord = false)
    {
        try {
            //Verify data
            $this->throwExceptionIfNotExistsElementTaskRelation($elementTaskRelationUid, $this->arrayFieldNameForException["elementTaskRelationUid"]);

            //Get data
            $criteria = $this->getElementTaskRelationCriteria();

            $criteria->add(\ElementTaskRelationPeer::ETR_UID, $elementTaskRelationUid, \Criteria::EQUAL);

            $rsCriteria = \ElementTaskRelationPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            $rsCriteria->next();

            $row = $rsCriteria->getRow();

            //Return
            return (!$flagGetRecord)? $this->getElementTaskRelationDataFromRecord($row) : $row;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Element-Task-Relation
     *
     * @param array $arrayCondition Conditions
     * @param bool  $flagGetRecord  Value that set the getting
     *
     * return array Return an array with data of a Element-Task-Relation, otherwise null
     */
    public function getElementTaskRelationWhere(array $arrayCondition, $flagGetRecord = false)
    {
        try {
            //Get data
            $criteria = $this->getElementTaskRelationCriteria();

            foreach ($arrayCondition as $key => $value) {
                if (is_array($value)) {
                    $criteria->add($key, $value[0], $value[1]);
                } else {
                    $criteria->add($key, $value, \Criteria::EQUAL);
                }
            }

            $rsCriteria = \ElementTaskRelationPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            if ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                //Return
                return (!$flagGetRecord)? $this->getElementTaskRelationDataFromRecord($row) : $row;
            } else {
                //Return
                return null;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

