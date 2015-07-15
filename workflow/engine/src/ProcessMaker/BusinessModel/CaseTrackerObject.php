<?php
namespace ProcessMaker\BusinessModel;

class CaseTrackerObject
{
    /**
     * Verify if exists the record in table CASE_TRACKER_OBJECT
     *
     * @param string $processUid Unique id of Process
     * @param string $type       Type of Step (DYNAFORM, INPUT_DOCUMENT, OUTPUT_DOCUMENT)
     * @param string $objectUid  Unique id of Object
     * @param int    $position   Position
     * @param string $caseTrackerObjectUidExclude Unique id of Case Tracker Object to exclude
     *
     * return bool Return true if exists the record in table CASE_TRACKER_OBJECT, false otherwise
     */
    public function existsRecord($processUid, $type, $objectUid, $position = 0, $caseTrackerObjectUidExclude = "")
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\CaseTrackerObjectPeer::CTO_UID);
            $criteria->add(\CaseTrackerObjectPeer::PRO_UID, $processUid, \Criteria::EQUAL);

            if ($caseTrackerObjectUidExclude != "") {
                $criteria->add(\CaseTrackerObjectPeer::CTO_UID, $caseTrackerObjectUidExclude, \Criteria::NOT_EQUAL);
            }

            if ($type != "") {
                $criteria->add(\CaseTrackerObjectPeer::CTO_TYPE_OBJ, $type, \Criteria::EQUAL);
            }

            if ($objectUid != "") {
                $criteria->add(\CaseTrackerObjectPeer::CTO_UID_OBJ, $objectUid, \Criteria::EQUAL);
            }

            if ($position > 0) {
                $criteria->add(\CaseTrackerObjectPeer::CTO_POSITION, $position, \Criteria::EQUAL);
            }

            $rsCriteria = \CaseTrackerObjectPeer::doSelectRS($criteria);
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
     * Create Case Tracker Object for a Process
     *
     * @param string $processUid Unique id of Process
     * @param array  $arrayData  Data
     *
     * return array Return data of the new Case Tracker Object created
     */
    public function create($processUid, $arrayData)
    {
        try {
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);

            unset($arrayData["CTO_UID"]);

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfNotExistsProcess($processUid, "prj_uid");

            if (!isset($arrayData["CTO_TYPE_OBJ"])) {
                throw new \Exception(\G::LoadTranslation("ID_UNDEFINED_VALUE_IS_REQUIRED", array(strtolower("CTO_TYPE_OBJ"))));
            }

            if (!isset($arrayData["CTO_UID_OBJ"])) {
                throw new \Exception(\G::LoadTranslation("ID_UNDEFINED_VALUE_IS_REQUIRED", array(strtolower("CTO_UID_OBJ"))));
            }

            $step = new \ProcessMaker\BusinessModel\Step();

            $msg = $step->existsObjectUid($arrayData["CTO_TYPE_OBJ"], $arrayData["CTO_UID_OBJ"], strtolower("CTO_UID_OBJ"));

            if ($msg != "") {
                throw new \Exception($msg);
            }

            if ($this->existsRecord($processUid, $arrayData["CTO_TYPE_OBJ"], $arrayData["CTO_UID_OBJ"])) {
                throw new \Exception(\G::LoadTranslation("ID_RECORD_EXISTS_IN_TABLE", array($processUid . ", " . $arrayData["CTO_TYPE_OBJ"] . ", " . $arrayData["CTO_UID_OBJ"], "CASE_TRACKER_OBJECT")));
            }

            $ctoPosition = $arrayData["CTO_POSITION"];
            $criteria = new \Criteria("workflow");
            $criteria->add(\CaseTrackerObjectPeer::PRO_UID, $processUid);
            $arrayData["CTO_POSITION"] = \CaseTrackerObjectPeer::doCount($criteria) + 1;

            //Create
            $caseTrackerObject = new \CaseTrackerObject();

            $arrayData["PRO_UID"] = $processUid;
            $caseTrackerObjectUid = $caseTrackerObject->create($arrayData);

            $arrayData["CTO_POSITION"] = $ctoPosition;
            $arrayData["CTO_UID"] = $caseTrackerObjectUid;
            $arrayDataUpdate = array_change_key_case($arrayData, CASE_LOWER);
            $this->update($caseTrackerObjectUid, $arrayDataUpdate);

            //Return
            unset($arrayData["PRO_UID"]);

            $arrayData = array_change_key_case($arrayData, CASE_LOWER);

            unset($arrayData["cto_uid"]);

            return array_merge(array("cto_uid" => $caseTrackerObjectUid), $arrayData);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update Case Tracker Object
     *
     * @param string $caseTrackerObjectUid Unique id of Case Tracker Object
     * @param array  $arrayData Data
     *
     * return array Return data of the Case Tracker Object updated
     */
    public function update($caseTrackerObjectUid, $arrayData)
    {
        try {
            $arrayData = array_change_key_case($arrayData, CASE_UPPER);

            $caseTrackerObject = new \CaseTrackerObject();

            $arrayCaseTrackerObjectData = $caseTrackerObject->load($caseTrackerObjectUid);

            //Uids
            $processUid = $arrayCaseTrackerObjectData["PRO_UID"];

            //Verify data
            if (!$caseTrackerObject->caseTrackerObjectExists($caseTrackerObjectUid)) {
                throw new \Exception(\G::LoadTranslation("ID_CASE_TRACKER_OBJECT_DOES_NOT_EXIST", array(strtolower("CTO_UID"), $caseTrackerObjectUid)));
            }

            if (isset($arrayData["CTO_TYPE_OBJ"]) && !isset($arrayData["CTO_UID_OBJ"])) {
                throw new \Exception(\G::LoadTranslation("ID_UNDEFINED_VALUE_IS_REQUIRED", array(strtolower("CTO_UID_OBJ"))));
            }

            if (!isset($arrayData["CTO_TYPE_OBJ"]) && isset($arrayData["CTO_UID_OBJ"])) {
                throw new \Exception(\G::LoadTranslation("ID_UNDEFINED_VALUE_IS_REQUIRED", array(strtolower("CTO_TYPE_OBJ"))));
            }

            if (isset($arrayData["CTO_TYPE_OBJ"]) && isset($arrayData["CTO_UID_OBJ"])) {
                $step = new \ProcessMaker\BusinessModel\Step();

                $msg = $step->existsObjectUid($arrayData["CTO_TYPE_OBJ"], $arrayData["CTO_UID_OBJ"], strtolower("CTO_UID_OBJ"));

                if ($msg != "") {
                    throw new \Exception($msg);
                }

                if ($this->existsRecord($processUid, $arrayData["CTO_TYPE_OBJ"], $arrayData["CTO_UID_OBJ"], 0, $caseTrackerObjectUid)) {
                    throw new \Exception(\G::LoadTranslation("ID_RECORD_EXISTS_IN_TABLE", array($processUid . ", " . $arrayData["CTO_TYPE_OBJ"] . ", " . $arrayData["CTO_UID_OBJ"], "CASE_TRACKER_OBJECT")));
                }
            }

            //Flags
            $flagDataOject     = (isset($arrayData["CTO_TYPE_OBJ"]) && isset($arrayData["CTO_UID_OBJ"]))? 1 : 0;
            $flagDataCondition = (isset($arrayData["CTO_CONDITION"]))? 1 : 0;
            $flagDataPosition  = (isset($arrayData["CTO_POSITION"]))? 1 : 0;

            //Update
            $tempPosition = (isset($arrayData["CTO_POSITION"])) ? $arrayData["CTO_POSITION"] : $arrayCaseTrackerObjectData["CTO_POSITION"];
            $arrayData["CTO_POSITION"] = $arrayCaseTrackerObjectData["CTO_POSITION"];
            $arrayData["CTO_UID"] = $caseTrackerObjectUid;
            $arrayData = array_merge($arrayCaseTrackerObjectData, $arrayData);
            $caseTrackerObject->update($arrayData);

            if ($tempPosition != $arrayCaseTrackerObjectData["CTO_POSITION"]) {
                $this->moveCaseTrackerObject($caseTrackerObjectUid, $arrayData['PRO_UID'], $tempPosition);
            }



            //Return
            unset($arrayData["CTO_UID"]);

            if ($flagDataOject == 0) {
                unset($arrayData["CTO_TYPE_OBJ"]);
                unset($arrayData["CTO_UID_OBJ"]);
            }

            if ($flagDataCondition == 0) {
                unset($arrayData["CTO_CONDITION"]);
            }

            if ($flagDataPosition == 0) {
                unset($arrayData["CTO_POSITION"]);
            }

            unset($arrayData["PRO_UID"]);

            return array_change_key_case($arrayData, CASE_LOWER);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete Case Tracker Object
     *
     * @param string $caseTrackerObjectUid Unique id of Case Tracker Object
     *
     * return void
     */
    public function delete($caseTrackerObjectUid)
    {
        try {
            $caseTrackerObject = new \CaseTrackerObject();

            $arrayCaseTrackerObjectData = $caseTrackerObject->load($caseTrackerObjectUid);

            //Uids
            $processUid = $arrayCaseTrackerObjectData["PRO_UID"];

            //Verify data
            if (!$caseTrackerObject->caseTrackerObjectExists($caseTrackerObjectUid)) {
                throw new \Exception(\G::LoadTranslation("ID_CASE_TRACKER_OBJECT_DOES_NOT_EXIST", array(strtolower("CTO_UID"), $caseTrackerObjectUid)));
            }

            //Delete
            $result = $caseTrackerObject->remove($caseTrackerObjectUid);

            $caseTrackerObject->reorderPositions($processUid, $arrayCaseTrackerObjectData["CTO_POSITION"]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of a Case Tracker Object
     *
     * @param string $caseTrackerObjectUid Unique id of Case Tracker Object
     *
     * return array Return an array with data of a Case Tracker Object
     */
    public function getCaseTrackerObject($caseTrackerObjectUid)
    {
        try {
            //Verify data
            $caseTrackerObject = new \CaseTrackerObject();

            if (!$caseTrackerObject->caseTrackerObjectExists($caseTrackerObjectUid)) {
                throw new \Exception(\G::LoadTranslation("ID_CASE_TRACKER_OBJECT_DOES_NOT_EXIST", array(strtolower("CTO_UID"), $caseTrackerObjectUid)));
            }

            //Get data
            $dynaform = new \Dynaform();
            $inputDocument = new \InputDocument();
            $outputDocument = new \OutputDocument();

            $criteria = new \Criteria("workflow");

            $criteria->add(\CaseTrackerObjectPeer::CTO_UID, $caseTrackerObjectUid, \Criteria::EQUAL);

            $rsCriteria = \CaseTrackerObjectPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            $rsCriteria->next();

            $row = $rsCriteria->getRow();

            $titleObj = "";
            $descriptionObj = "";

            switch ($row["CTO_TYPE_OBJ"]) {
                case "DYNAFORM":
                    $arrayData = $dynaform->load($row["CTO_UID_OBJ"]);

                    $titleObj = $arrayData["DYN_TITLE"];
                    $descriptionObj = $arrayData["DYN_DESCRIPTION"];
                    break;
                case "INPUT_DOCUMENT":
                    $arrayData = $inputDocument->getByUid($row["CTO_UID_OBJ"]);

                    $titleObj = $arrayData["INP_DOC_TITLE"];
                    $descriptionObj = $arrayData["INP_DOC_DESCRIPTION"];
                    break;
                case "OUTPUT_DOCUMENT":
                    $arrayData = $outputDocument->getByUid($row["CTO_UID_OBJ"]);

                    $titleObj = $arrayData["OUT_DOC_TITLE"];
                    $descriptionObj = $arrayData["OUT_DOC_DESCRIPTION"];
                    break;
            }

            return array(
                "cto_uid"         => $row["CTO_UID"],
                "cto_type_obj"    => $row["CTO_TYPE_OBJ"],
                "cto_uid_obj"     => $row["CTO_UID_OBJ"],
                "cto_condition"   => $row["CTO_CONDITION"],
                "cto_position"    => (int)($row["CTO_POSITION"]),
                "obj_title"       => $titleObj,
                "obj_description" => $descriptionObj
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate Process Uid
     * @var string $cto_uid. Uid for Process
     * @var string $pro_uid. Uid for Task
     * @var string $cto_pos. Position for Step
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    public function moveCaseTrackerObject($cto_uid, $pro_uid, $cto_pos)
    {
        $aCaseTrackerObject = CaseTracker::getCaseTrackerObjects($pro_uid);

        foreach ($aCaseTrackerObject as $dataCaseTracker) {
            if ($dataCaseTracker['cto_uid'] == $cto_uid) {
                $prStepPos = (int)$dataCaseTracker['cto_position'];
            }
        }
        $seStepPos = $cto_pos;

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
        foreach ($aCaseTrackerObject as $dataCaseTracker) {
            if ((in_array($dataCaseTracker['cto_position'], $range)) && ($dataCaseTracker['cto_uid'] != $cto_uid)) {
                $caseTrackerObjectIds[] = $dataCaseTracker['cto_uid'];
                $caseTrackerObjectPos[] = $dataCaseTracker['cto_position'];
            }
        }

        foreach ($caseTrackerObjectIds as $key => $value) {
            if ($modPos == 'UP') {
                $tempPos = ((int)$caseTrackerObjectPos[$key])-1;
                $this->changePosCaseTrackerObject($value, $tempPos);
            } else {
                $tempPos = ((int)$caseTrackerObjectPos[$key])+1;
                $this->changePosCaseTrackerObject($value, $tempPos);
            }
        }
        $this->changePosCaseTrackerObject($cto_uid, $newPos);
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
    public function changePosCaseTrackerObject ($cto_uid, $pos)
    {
        $data = array(
            'CTO_UID' => $cto_uid,
            'CTO_POSITION' => $pos
        );
        $oCaseTrackerObject = new \CaseTrackerObject();
        $oCaseTrackerObject->update($data);
    }
}

