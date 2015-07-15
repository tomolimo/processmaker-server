<?php
namespace ProcessMaker\BusinessModel\Step;

use \ProcessMaker\BusinessModel\Step;

class Trigger
{
    /**
     * Verify if exists the record in table STEP_TRIGGER
     *
     * @param string $stepUid           Unique id of Step
     * @param string $type              Type (BEFORE, AFTER)
     * @param string $taskUid           Unique id of Task
     * @param string $triggerUid        Unique id of Trigger
     * @param int    $position          Position
     * @param string $triggerUidExclude Unique id of Trigger to exclude
     *
     * return bool Return true if exists the record in table STEP_TRIGGER, false otherwise
     */
    public function existsRecord($stepUid, $type, $taskUid, $triggerUid, $position = 0, $triggerUidExclude = "")
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\StepTriggerPeer::STEP_UID);
            $criteria->add(\StepTriggerPeer::STEP_UID, $stepUid, \Criteria::EQUAL);
            $criteria->add(\StepTriggerPeer::ST_TYPE, $type, \Criteria::EQUAL);
            $criteria->add(\StepTriggerPeer::TAS_UID, $taskUid, \Criteria::EQUAL);

            if ($triggerUid != "") {
                $criteria->add(\StepTriggerPeer::TRI_UID, $triggerUid, \Criteria::EQUAL);
            }

            if ($position > 0) {
                $criteria->add(\StepTriggerPeer::ST_POSITION, $position, \Criteria::EQUAL);
            }

            if ($triggerUidExclude != "") {
                $criteria->add(\StepTriggerPeer::TRI_UID, $triggerUidExclude, \Criteria::NOT_EQUAL);
            }

            $rsCriteria = \StepTriggerPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            if ($rsCriteria->next()) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Assign Trigger to a Step
     *
     * @param string $stepUid    Unique id of Step
     * @param string $type       Type (BEFORE, AFTER, BEFORE_ASSIGNMENT, BEFORE_ROUTING, AFTER_ROUTING)
     * @param string $taskUid    Unique id of Task
     * @param string $triggerUid Unique id of Trigger
     * @param array  $arrayData  Data
     *
     * return array Data of the Trigger assigned to a Step
     */
    public function create($stepUid, $type, $taskUid, $triggerUid, $arrayData)
    {
        try {
            $stepUidIni = $stepUid;
            $typeIni = $type;

            $flagStepAssignTask = 0;

            if ($stepUid == "") {
                $flagStepAssignTask = 1;

                switch ($type) {
                    case "BEFORE_ASSIGNMENT":
                        $stepUid = "-1";
                        $type = "BEFORE";
                        break;
                    case "BEFORE_ROUTING":
                        $stepUid = "-2";
                        $type = "BEFORE";
                        break;
                    case "AFTER_ROUTING":
                        $stepUid = "-2";
                        $type = "AFTER";
                        break;
                }
            }

            //Verify data
            if ($flagStepAssignTask == 0) {
                $step = new \Step();

                if (!$step->StepExists($stepUid)) {
                    throw new \Exception(\G::LoadTranslation("ID_STEP_DOES_NOT_EXIST", array("step_uid", $stepUid)));
                }
            }

            $task = new \Task();

            if (!$task->taskExists($taskUid)) {
                throw new \Exception(\G::LoadTranslation("ID_ACTIVITY_DOES_NOT_EXIST", array("act_uid", $taskUid)));
            }

            $trigger = new \Triggers();

            if (!$trigger->TriggerExists($triggerUid)) {
                throw new \Exception(\G::LoadTranslation("ID_TRIGGER_DOES_NOT_EXIST", array("tri_uid", $triggerUid)));
            }

            if ($this->existsRecord($stepUid, $type, $taskUid, $triggerUid)) {
                throw new \Exception(\G::LoadTranslation("ID_RECORD_EXISTS_IN_TABLE", array($stepUid . ", " . $type . ", " . $taskUid . ", " . $triggerUid, "STEP_TRIGGER")));
            }

            //Create
            $stepTrigger = new \StepTrigger();
            $posIni = $stepTrigger->getNextPosition($stepUid, $type, $taskUid);
            $stepTrigger->createRow(array(
                "STEP_UID" => $stepUid,
                "TAS_UID" => $taskUid,
                "TRI_UID" => $triggerUid,
                "ST_TYPE" => $type,
                "ST_CONDITION" => (isset($arrayData['st_condition'])) ? $arrayData['st_condition'] : '',
                "ST_POSITION" => $posIni
            ));

            $arrayData = $this->update($stepUid, $typeIni, $taskUid, $triggerUid, $arrayData);

            return $arrayData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update Trigger of a Step
     *
     * @param string $stepUid    Unique id of Step
     * @param string $type       Type (BEFORE, AFTER, BEFORE_ASSIGNMENT, BEFORE_ROUTING, AFTER_ROUTING)
     * @param string $taskUid    Unique id of Task
     * @param string $triggerUid Unique id of Trigger
     * @param array  $arrayData  Data
     *
     * return array Data updated of the Trigger assigned to a Step
     */
    public function update($stepUid, $type, $taskUid, $triggerUid, $arrayData)
    {
        try {
            $flagStepAssignTask = 0;

            if (($stepUid == "") || ($stepUid == "-1") || ($stepUid == "-2")) {
                $flagStepAssignTask = 1;

                switch ($type) {
                    case "BEFORE_ASSIGNMENT":
                        $stepUid = "-1";
                        $type = "BEFORE";
                        break;
                    case "BEFORE_ROUTING":
                        $stepUid = "-2";
                        $type = "BEFORE";
                        break;
                    case "AFTER_ROUTING":
                        $stepUid = "-2";
                        $type = "AFTER";
                        break;
                }
            }

            //Verify data
            if ($flagStepAssignTask == 0) {
                $step = new \Step();

                if (!$step->StepExists($stepUid)) {
                    throw new \Exception(\G::LoadTranslation("ID_STEP_DOES_NOT_EXIST", array("step_uid", $stepUid)));
                }
            }

            $trigger = new \Triggers();

            if (!$trigger->TriggerExists($triggerUid)) {
                throw new \Exception(\G::LoadTranslation("ID_TRIGGER_DOES_NOT_EXIST", array("tri_uid", $triggerUid)));
            }

            //Update
            $stepTrigger = new \StepTrigger();

            $arrayUpdateData = array();

            $arrayUpdateData["STEP_UID"] = $stepUid;
            $arrayUpdateData["TAS_UID"] = $taskUid;
            $arrayUpdateData["TRI_UID"] = $triggerUid;
            $arrayUpdateData["ST_TYPE"] = $type;

            if (isset($arrayData["st_condition"])) {
                $arrayUpdateData["ST_CONDITION"] = $arrayData["st_condition"];
            }

            if (isset($arrayData["st_position"]) && $arrayData["st_position"] != "") {
                $tempPos = (int)($arrayData["st_position"]);
            }

            $stepTrigger->update($arrayUpdateData);
            if (isset($tempPos)) {
                $this->moveStepTriggers($taskUid, $stepUid, $triggerUid, $type, $tempPos);
            }
            return array_change_key_case($arrayUpdateData, CASE_LOWER);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete Trigger of a Step
     *
     * @param string $stepUid    Unique id of Step
     * @param string $type       Type (BEFORE, AFTER, BEFORE_ASSIGNMENT, BEFORE_ROUTING, AFTER_ROUTING)
     * @param string $taskUid    Unique id of Task
     * @param string $triggerUid Unique id of Trigger
     *
     * return void
     */
    public function delete($stepUid, $type, $taskUid, $triggerUid)
    {
        try {
            if ($stepUid == "") {
                switch ($type) {
                    case "BEFORE_ASSIGNMENT":
                        $stepUid = "-1";
                        $type = "BEFORE";
                        break;
                    case "BEFORE_ROUTING":
                        $stepUid = "-2";
                        $type = "BEFORE";
                        break;
                    case "AFTER_ROUTING":
                        $stepUid = "-2";
                        $type = "AFTER";
                        break;
                }
            }

            //Verify data
            if (!$this->existsRecord($stepUid, $type, $taskUid, $triggerUid)) {
                throw new \Exception(\G::LoadTranslation("ID_RECORD_DOES_NOT_EXIST_IN_TABLE", array($stepUid . ", " . $type . ", " . $taskUid . ", " . $triggerUid, "STEP_TRIGGER")));
            }

            //Get position
            $stepTrigger = new \StepTrigger();

            $arrayData = $stepTrigger->load($stepUid, $taskUid, $triggerUid, $type);

            $position = (int)($arrayData["ST_POSITION"]);

            //Delete
            $stepTrigger = new \StepTrigger();

            $stepTrigger->reOrder($stepUid, $taskUid, $type, $position);
            $stepTrigger->remove($stepUid, $taskUid, $triggerUid, $type);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Trigger from a record
     *
     * @param array $record Record
     *
     * return array Return an array with data of a Trigger
     */
    public function getTriggerDataFromRecord($record)
    {
        try {
            return array(
                "tri_uid"         => $record["TRI_UID"],
                "tri_title"       => $record["TRI_TITLE"],
                "tri_description" => $record["TRI_DESCRIPTION"],
                "st_type"         => $record["ST_TYPE"],
                "st_condition"    => $record["ST_CONDITION"],
                "st_position"     => (int)($record["ST_POSITION"])
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Trigger
     *
     * @param string $stepUid    Unique id of Step
     * @param string $type       Type (BEFORE, AFTER, BEFORE_ASSIGNMENT, BEFORE_ROUTING, AFTER_ROUTING)
     * @param string $taskUid    Unique id of Task
     * @param string $triggerUid Unique id of Trigger
     *
     * return array Return an array with data of a Trigger
     */
    public function getTrigger($stepUid, $type, $taskUid, $triggerUid)
    {
        try {
            $typeIni = $type;

            $flagStepAssignTask = 0;

            if ($stepUid == "") {
                $flagStepAssignTask = 1;

                switch ($type) {
                    case "BEFORE_ASSIGNMENT":
                        $stepUid = "-1";
                        $type = "BEFORE";
                        break;
                    case "BEFORE_ROUTING":
                        $stepUid = "-2";
                        $type = "BEFORE";
                        break;
                    case "AFTER_ROUTING":
                        $stepUid = "-2";
                        $type = "AFTER";
                        break;
                }
            }

            //Verify data
            if (!$this->existsRecord($stepUid, $type, $taskUid, $triggerUid)) {
                throw new \Exception(\G::LoadTranslation("ID_RECORD_DOES_NOT_EXIST_IN_TABLE", array($stepUid . ", " . $type . ", " . $taskUid . ", " . $triggerUid, "STEP_TRIGGER")));
            }

            //Get data
            $trigger = new \ProcessMaker\BusinessModel\Trigger();

            $criteria = $trigger->getTriggerCriteria();

            $criteria->addSelectColumn(\StepTriggerPeer::ST_TYPE);
            $criteria->addSelectColumn(\StepTriggerPeer::ST_CONDITION);
            $criteria->addSelectColumn(\StepTriggerPeer::ST_POSITION);
            $criteria->addJoin(\StepTriggerPeer::TRI_UID, \TriggersPeer::TRI_UID, \Criteria::LEFT_JOIN);
            $criteria->add(\TriggersPeer::TRI_UID, $triggerUid, \Criteria::EQUAL);
            $criteria->add(\StepTriggerPeer::STEP_UID, $stepUid, \Criteria::EQUAL);
            $criteria->add(\StepTriggerPeer::TAS_UID, $taskUid, \Criteria::EQUAL);
            $criteria->add(\StepTriggerPeer::ST_TYPE, $type, \Criteria::EQUAL);

            $rsCriteria = \StepTriggerPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            $rsCriteria->next();

            $row = $rsCriteria->getRow();

            if ($flagStepAssignTask == 1) {
                $row["ST_TYPE"] = $typeIni;
            }

            return $this->getTriggerDataFromRecord($row);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate Process Uid
     * @var string $pro_uid. Uid for Process
     * @var string $tas_uid. Uid for Task
     * @var string $step_uid. Uid for Step
     * @var string $step_pos. Position for Step
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    public function moveStepTriggers($tasUid, $stepUid, $triUid, $type, $newPos)
    {
        $stepTrigger = new \ProcessMaker\BusinessModel\Step();
        $tempStep = $stepUid;
        $typeCompare = $type;
        if ($tempStep == '-1' || $tempStep == '-2') {
            $tempStep = '';
            if (($stepUid == '-1') && ($type == 'BEFORE')) {
                $typeCompare = "BEFORE_ASSIGNMENT";
            } elseif (($stepUid == '-2') && ($type == 'BEFORE')) {
                $typeCompare = "BEFORE_ROUTING";
            } elseif (($stepUid == '-2') && ($type == 'AFTER')) {
                $typeCompare = "AFTER_ROUTING";
            }
        }
        $aStepTriggers = $stepTrigger->getTriggers($tempStep, $tasUid);
        foreach ($aStepTriggers as $dataStep) {
            if (($dataStep['st_type'] == $typeCompare) && ($dataStep['tri_uid'] == $triUid)) {
                $prStepPos = (int)$dataStep['st_position'];
            }
        }
        $seStepPos = $newPos;

        //Principal Step is up
        if ($prStepPos == $seStepPos) {
            return true;
        } elseif ($prStepPos < $seStepPos) {
            $modPos = 'UP';
            $newPos = $seStepPos;
            $iniPos = $prStepPos+1;
            $finPos = $seStepPos;
        } else {
            $modPos = 'DOWN';
            $newPos = $seStepPos;
            $iniPos = $seStepPos;
            $finPos = $prStepPos-1;
        }

        $range = range($iniPos, $finPos);
        $stepChangeIds = array();
        $stepChangePos = array();
        foreach ($aStepTriggers as $dataStep) {
            if (($dataStep['st_type'] == $typeCompare) && (in_array($dataStep['st_position'], $range)) && ($dataStep['tri_uid'] != $triUid)) {
                $stepChangeIds[] = $dataStep['tri_uid'];
                $stepChangePos[] = $dataStep['st_position'];
            }
        }

        foreach ($stepChangeIds as $key => $value) {
            if ($modPos == 'UP') {
                $tempPos = ((int)$stepChangePos[$key])-1;
                $this->changePosStep($stepUid, $tasUid, $value, $type, $tempPos);
            } else {
                $tempPos = ((int)$stepChangePos[$key])+1;
                $this->changePosStep($stepUid, $tasUid, $value, $type, $tempPos);
            }
        }
        $this->changePosStep($stepUid, $tasUid, $triUid, $type, $newPos);
    }

    /**
     * Validate Process Uid
     * @var string $pro_uid. Uid for process
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    public function changePosStep ($stepUid, $tasUid, $triUid, $type, $pos)
    {
        $data = array(
            'STEP_UID' => $stepUid,
            'TAS_UID'  => $tasUid,
            'TRI_UID'  => $triUid,
            'ST_TYPE'  => $type,
            'ST_POSITION' => $pos
        );
        $StepTrigger = new \StepTrigger();
        $StepTrigger->update($data);
    }

    /**
     * Assign Trigger to a Step
     *
     * @param string $stepUid    Unique id of Step
     * @param string $type       Type (BEFORE, AFTER, BEFORE_ASSIGNMENT, BEFORE_ROUTING, AFTER_ROUTING)
     * @param string $taskUid    Unique id of Task
     * @param string $triggerUid Unique id of Trigger
     * @param array  $arrayData  Data
     *
     * return array Data of the Trigger assigned to a Step
     */
    public function createAll($stepUid, $type, $taskUid, $triggerUid, $arrayData)
    {
        try {
            $stepUidIni = $stepUid;
            $typeIni = $type;

            $flagStepAssignTask = 0;

            if ($stepUid == "") {
                $flagStepAssignTask = 1;

                switch ($type) {
                    case "BEFORE_ASSIGNMENT":
                        $stepUid = "-1";
                        $type = "BEFORE";
                        break;
                    case "BEFORE_ROUTING":
                        $stepUid = "-2";
                        $type = "BEFORE";
                        break;
                    case "AFTER_ROUTING":
                        $stepUid = "-2";
                        $type = "AFTER";
                        break;
                }
            }

            //Verify data
            if ($flagStepAssignTask == 0) {
                $step = new \Step();

                if (!$step->StepExists($stepUid)) {
                    throw new \Exception(\G::LoadTranslation("ID_STEP_DOES_NOT_EXIST", array("step_uid", $stepUid)));
                }
            }

            $task = new \Task();

            if (!$task->taskExists($taskUid)) {
                throw new \Exception(\G::LoadTranslation("ID_ACTIVITY_DOES_NOT_EXIST", array("act_uid", $taskUid)));
            }

            $trigger = new \Triggers();

            if (!$trigger->TriggerExists($triggerUid)) {
                throw new \Exception(\G::LoadTranslation("ID_TRIGGER_DOES_NOT_EXIST", array("tri_uid", $triggerUid)));
            }

            if ($this->existsRecord($stepUid, $type, $taskUid, $triggerUid)) {
                throw new \Exception(\G::LoadTranslation("ID_RECORD_EXISTS_IN_TABLE", array($stepUid . ", " . $type . ", " . $taskUid . ", " . $triggerUid, "STEP_TRIGGER")));
            }

            //Create
            $stepTrigger = new \StepTrigger();
//            $posIni = $stepTrigger->getNextPosition($stepUid, $type, $taskUid);

            $stepTrigger->createRow(array(
                "STEP_UID" => $stepUid,
                "TAS_UID" => $taskUid,
                "TRI_UID" => $triggerUid,
                "ST_TYPE" => $type,
                "ST_CONDITION" => (isset($arrayData['st_condition'])) ? $arrayData['st_condition'] : '',
                "ST_POSITION" => $arrayData['st_position']
            ));

            $arrayData = $this->updateAll($stepUid, $typeIni, $taskUid, $triggerUid, $arrayData);

            return $arrayData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update Trigger of a Step
     *
     * @param string $stepUid    Unique id of Step
     * @param string $type       Type (BEFORE, AFTER, BEFORE_ASSIGNMENT, BEFORE_ROUTING, AFTER_ROUTING)
     * @param string $taskUid    Unique id of Task
     * @param string $triggerUid Unique id of Trigger
     * @param array  $arrayData  Data
     *
     * return array Data updated of the Trigger assigned to a Step
     */
    public function updateAll($stepUid, $type, $taskUid, $triggerUid, $arrayData)
    {
        try {
            $flagStepAssignTask = 0;

            if (($stepUid == "") || ($stepUid == "-1") || ($stepUid == "-2")) {
                $flagStepAssignTask = 1;

                switch ($type) {
                    case "BEFORE_ASSIGNMENT":
                        $stepUid = "-1";
                        $type = "BEFORE";
                        break;
                    case "BEFORE_ROUTING":
                        $stepUid = "-2";
                        $type = "BEFORE";
                        break;
                    case "AFTER_ROUTING":
                        $stepUid = "-2";
                        $type = "AFTER";
                        break;
                }
            }

            //Verify data
            if ($flagStepAssignTask == 0) {
                $step = new \Step();

                if (!$step->StepExists($stepUid)) {
                    throw new \Exception(\G::LoadTranslation("ID_STEP_DOES_NOT_EXIST", array("step_uid", $stepUid)));
                }
            }

            $trigger = new \Triggers();

            if (!$trigger->TriggerExists($triggerUid)) {
                throw new \Exception(\G::LoadTranslation("ID_TRIGGER_DOES_NOT_EXIST", array("tri_uid", $triggerUid)));
            }

            //Update
            $stepTrigger = new \StepTrigger();

            $arrayUpdateData = array();

            $arrayUpdateData["STEP_UID"] = $stepUid;
            $arrayUpdateData["TAS_UID"] = $taskUid;
            $arrayUpdateData["TRI_UID"] = $triggerUid;
            $arrayUpdateData["ST_TYPE"] = $type;

            if (isset($arrayData["st_condition"])) {
                $arrayUpdateData["ST_CONDITION"] = $arrayData["st_condition"];
            }

            $stepTrigger->update($arrayUpdateData);

            return array_change_key_case($arrayUpdateData, CASE_LOWER);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

