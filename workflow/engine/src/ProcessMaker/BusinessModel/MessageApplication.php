<?php
namespace ProcessMaker\BusinessModel;

class MessageApplication
{
    private $arrayFieldNameForException = array(
        "start" => "START",
        "limit" => "LIMIT"
    );

    private $frontEnd = false;

    /**
     * Verify if exists the Message-Application
     *
     * @param string $messageApplicationUid Unique id of Message-Application
     *
     * return bool Return true if exists the Message-Application, false otherwise
     */
    public function exists($messageApplicationUid)
    {
        try {
            $obj = \MessageApplicationPeer::retrieveByPK($messageApplicationUid);

            return (!is_null($obj))? true : false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create Message-Application for the Case
     *
     * @param string $applicationUid       Unique id of Case
     * @param string $projectUid           Unique id of Project
     * @param string $eventUidThrow        Unique id of Event (throw)
     * @param array  $arrayApplicationData Case data
     *
     * return bool Return true if been created, false otherwise
     */
    public function create($applicationUid, $projectUid, $eventUidThrow, array $arrayApplicationData)
    {
        try {
            $flagCreate = true;

            //Set data
            //Message-Event-Relation - Get unique id of Event (catch)
            $messageEventRelation = new \ProcessMaker\BusinessModel\MessageEventRelation();

            $arrayMessageEventRelationData = $messageEventRelation->getMessageEventRelationWhere(
                array(
                    \MessageEventRelationPeer::PRJ_UID       => $projectUid,
                    \MessageEventRelationPeer::EVN_UID_THROW => $eventUidThrow
                ),
                true
            );

            if (!is_null($arrayMessageEventRelationData)) {
                $eventUidCatch = $arrayMessageEventRelationData["EVN_UID_CATCH"];
            } else {
                $flagCreate = false;
            }

            //Message-Application - Get data ($eventUidThrow)
            $messageEventDefinition = new \ProcessMaker\BusinessModel\MessageEventDefinition();

            if ($messageEventDefinition->existsEvent($projectUid, $eventUidThrow)) {
                $arrayMessageEventDefinitionData = $messageEventDefinition->getMessageEventDefinitionByEvent($projectUid, $eventUidThrow, true);

                $arrayMessageApplicationVariables = $arrayMessageEventDefinitionData["MSGED_VARIABLES"];
                $messageApplicationCorrelation = \G::replaceDataField($arrayMessageEventDefinitionData["MSGED_CORRELATION"], $arrayApplicationData["APP_DATA"]);

                foreach ($arrayMessageApplicationVariables as $key => $value) {
                    $arrayMessageApplicationVariables[$key] = \G::replaceDataField($value, $arrayApplicationData["APP_DATA"]);
                }
            } else {
                $flagCreate = false;
            }

            if (!$flagCreate) {
                //Return
                return false;
            }

            //Create
            $cnn = \Propel::getConnection("workflow");

            try {
                $messageApplication = new \MessageApplication();

                $messageApplicationUid = \ProcessMaker\Util\Common::generateUID();

                $messageApplication->setMsgappUid($messageApplicationUid);
                $messageApplication->setAppUid($applicationUid);
                $messageApplication->setPrjUid($projectUid);
                $messageApplication->setEvnUidThrow($eventUidThrow);
                $messageApplication->setEvnUidCatch($eventUidCatch);
                $messageApplication->setMsgappVariables(serialize($arrayMessageApplicationVariables));
                $messageApplication->setMsgappCorrelation($messageApplicationCorrelation);
                $messageApplication->setMsgappThrowDate("now");

                if ($messageApplication->validate()) {
                    $cnn->begin();

                    $result = $messageApplication->save();

                    $cnn->commit();

                    //Return
                    return true;
                } else {
                    $msg = "";

                    foreach ($messageApplication->getValidationFailures() as $validationFailure) {
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
     * Update Message-Application
     *
     * @param string $messageApplicationUid Unique id of Message-Application
     * @param array  $arrayData             Data
     *
     * return bool Return true if been updated, false otherwise
     */
    public function update($messageApplicationUid, array $arrayData)
    {
        try {
            //Verify data
            if (!$this->exists($messageApplicationUid)) {
                //Return
                return false;
            }

            //Update
            $cnn = \Propel::getConnection("workflow");

            try {
                $messageApplication = \MessageApplicationPeer::retrieveByPK($messageApplicationUid);

                $messageApplication->fromArray($arrayData, \BasePeer::TYPE_FIELDNAME);

                $messageApplication->setMsgappCatchDate("now");

                if ($messageApplication->validate()) {
                    $cnn->begin();

                    $result = $messageApplication->save();

                    $cnn->commit();

                    //Return
                    return true;
                } else {
                    $msg = "";

                    foreach ($messageApplication->getValidationFailures() as $validationFailure) {
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
     * Get criteria for Message-Application
     *
     * return object
     */
    public function getMessageApplicationCriteria()
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\MessageApplicationPeer::MSGAPP_UID);
            $criteria->addSelectColumn(\MessageApplicationPeer::APP_UID);
            $criteria->addSelectColumn(\MessageApplicationPeer::PRJ_UID);
            $criteria->addSelectColumn(\MessageApplicationPeer::EVN_UID_THROW);
            $criteria->addSelectColumn(\MessageApplicationPeer::EVN_UID_CATCH);
            $criteria->addSelectColumn(\MessageApplicationPeer::MSGAPP_VARIABLES);
            $criteria->addSelectColumn(\MessageApplicationPeer::MSGAPP_CORRELATION);
            $criteria->addSelectColumn(\MessageApplicationPeer::MSGAPP_THROW_DATE);
            $criteria->addSelectColumn(\MessageApplicationPeer::MSGAPP_CATCH_DATE);
            $criteria->addSelectColumn(\MessageApplicationPeer::MSGAPP_STATUS);

            return $criteria;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set front end flag
     *
     * @param bool $flag Flag
     *
     * return void
     */
    public function setFrontEnd($flag)
    {
        try {
            $this->frontEnd = $flag;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Progress bar
     *
     * @param int $total Total
     * @param int $count Count
     *
     * return string Return a string that represent progress bar
     */
    public function progressBar($total, $count)
    {
        try {
            $p = (int)(($count * 100) / $total);
            $n = (int)($p / 2);

            return "[" . str_repeat("|", $n) . str_repeat(" ", 50 - $n) . "] $p%";
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Show front end
     *
     * @param string $option Option
     * @param string $data   Data string
     *
     * return void
     */
    public function frontEndShow($option, $data = "")
    {
        try {
            if (!$this->frontEnd) {
                return;
            }

            $numc = 100;

            switch ($option) {
                case "BAR":
                    echo "\r" . "| " . $data . str_repeat(" ", $numc - 2 - strlen($data));
                    break;
                case "TEXT":
                    echo "\r" . "| " . $data . str_repeat(" ", $numc - 2 - strlen($data)) . "\n";
                    break;
                default:
                    //START, END
                    echo "\r" . "+" . str_repeat("-", $numc - 2) . "+" . "\n";
                    break;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Merge and get variables
     *
     * @param array $arrayVariableName  Variables
     * @param array $arrayVariableValue Values
     *
     * return array Return an array
     */
    public function mergeVariables(array $arrayVariableName, array $arrayVariableValue)
    {
        try {
            $arrayVariable = array();

            foreach ($arrayVariableName as $key => $value) {
                if (preg_match("/^@[@%#\?\x24\=]([A-Za-z_]\w*)$/", $value, $arrayMatch) && isset($arrayVariableValue[$key])) {
                    $arrayVariable[$arrayMatch[1]] = $arrayVariableValue[$key];
                }
            }

            //Return
            return $arrayVariable;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all Message-Applications
     *
     * @param array  $arrayFilterData Data of the filters
     * @param string $sortField       Field name to sort
     * @param string $sortDir         Direction of sorting (ASC, DESC)
     * @param int    $start           Start
     * @param int    $limit           Limit
     *
     * return array Return an array with all Message-Applications
     */
    public function getMessageApplications($arrayFilterData = null, $sortField = null, $sortDir = null, $start = null, $limit = null)
    {
        try {
            $arrayMessageApplication = array();

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfDataNotMetPagerVarDefinition(array("start" => $start, "limit" => $limit), $this->arrayFieldNameForException);

            //Get data
            if (!is_null($limit) && $limit . "" == "0") {
                return $arrayMessageApplication;
            }

            //SQL
            $criteria = $this->getMessageApplicationCriteria();

            $criteria->addSelectColumn(\BpmnEventPeer::EVN_UID);
            $criteria->addSelectColumn(\BpmnEventPeer::EVN_TYPE);
            $criteria->addSelectColumn(\BpmnEventPeer::EVN_MARKER);
            $criteria->addSelectColumn(\MessageEventDefinitionPeer::MSGED_USR_UID);
            $criteria->addSelectColumn(\MessageEventDefinitionPeer::MSGED_VARIABLES);
            $criteria->addSelectColumn(\MessageEventDefinitionPeer::MSGED_CORRELATION);
            $criteria->addSelectColumn(\ElementTaskRelationPeer::TAS_UID);

            $arrayEventType   = array("START", "INTERMEDIATE");
            $arrayEventMarker = array("MESSAGECATCH");

            $criteria->addJoin(\MessageApplicationPeer::EVN_UID_CATCH, \BpmnEventPeer::EVN_UID, \Criteria::INNER_JOIN);
            $criteria->add(\BpmnEventPeer::EVN_TYPE, $arrayEventType, \Criteria::IN);
            $criteria->add(\BpmnEventPeer::EVN_MARKER, $arrayEventMarker, \Criteria::IN);

            $criteria->addJoin(\MessageApplicationPeer::EVN_UID_CATCH, \MessageEventDefinitionPeer::EVN_UID, \Criteria::INNER_JOIN);

            $criteria->addJoin(\MessageApplicationPeer::EVN_UID_CATCH, \ElementTaskRelationPeer::ELEMENT_UID, \Criteria::INNER_JOIN);
            $criteria->add(\ElementTaskRelationPeer::ELEMENT_TYPE, "bpmnEvent", \Criteria::EQUAL);

            if (!is_null($arrayFilterData) && is_array($arrayFilterData) && isset($arrayFilterData["messageApplicationStatus"]) && trim($arrayFilterData["messageApplicationStatus"]) != "") {
                $criteria->add(\MessageApplicationPeer::MSGAPP_STATUS, $arrayFilterData["messageApplicationStatus"], \Criteria::EQUAL);
            }

            //Number records total
            $criteriaCount = clone $criteria;

            $criteriaCount->clearSelectColumns();
            $criteriaCount->addSelectColumn("COUNT(" . \MessageApplicationPeer::MSGAPP_UID . ") AS NUM_REC");

            $rsCriteriaCount = \MessageApplicationPeer::doSelectRS($criteriaCount);
            $rsCriteriaCount->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            $rsCriteriaCount->next();
            $row = $rsCriteriaCount->getRow();

            $numRecTotal = $row["NUM_REC"];

            //SQL
            if (!is_null($sortField) && trim($sortField) != "") {
                $sortField = strtoupper($sortField);

                if (in_array($sortField, array("MSGAPP_THROW_DATE", "MSGAPP_CATCH_DATE", "MSGAPP_STATUS"))) {
                    $sortField = \MessageApplicationPeer::TABLE_NAME . "." . $sortField;
                } else {
                    $sortField = \MessageApplicationPeer::MSGAPP_THROW_DATE;
                }
            } else {
                $sortField = \MessageApplicationPeer::MSGAPP_THROW_DATE;
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

            $rsCriteria = \MessageApplicationPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                $row["MSGAPP_VARIABLES"] = unserialize($row["MSGAPP_VARIABLES"]);
                $row["MSGED_VARIABLES"]  = unserialize($row["MSGED_VARIABLES"]);

                $arrayMessageApplication[] = $row;
            }

            //Return
            return array(
                "total"  => $numRecTotal,
                "start"  => (int)((!is_null($start))? $start : 0),
                "limit"  => (int)((!is_null($limit))? $limit : 0),
                "filter" => (!is_null($arrayFilterData) && is_array($arrayFilterData) && isset($arrayFilterData["messageApplicationStatus"]))? $arrayFilterData["messageApplicationStatus"] : "",
                "data"   => $arrayMessageApplication
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

