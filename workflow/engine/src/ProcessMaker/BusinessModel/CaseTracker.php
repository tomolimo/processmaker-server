<?php
namespace ProcessMaker\BusinessModel;

class CaseTracker
{
    /**
     * Update Case Tracker data of a Process
     *
     * @param string $processUid Unique id of Process
     * @param array  $arrayData  Data
     *
     * return array Return data of the Case Tracker updated
     */
    public function update($processUid, $arrayData)
    {
        try {
            $arrayDataIni = $arrayData;

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfNotExistsProcess($processUid, "prj_uid");

            //Update
            $caseTracker = new \CaseTracker();

            $arrayData = array("PRO_UID" => $processUid);

            if (isset($arrayDataIni["map_type"])) {
                $arrayData["CT_MAP_TYPE"] = $arrayDataIni["map_type"];
            }

            if (isset($arrayDataIni["routing_history"])) {
                $arrayData["CT_DERIVATION_HISTORY"] = (int)($arrayDataIni["routing_history"]);
            }

            if (isset($arrayDataIni["message_history"])) {
                $arrayData["CT_MESSAGE_HISTORY"] = (int)($arrayDataIni["message_history"]);
            }

            $result = $caseTracker->update($arrayData);

            $arrayData = $arrayDataIni;

            //Return
            return $arrayData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get Case Tracker data of a Process
     *
     * @param string $processUid Unique id of Process
     *
     * return array Return an array with data of Case Tracker of a Process
     */
    public function getCaseTracker($processUid)
    {
        try {
            $arrayCaseTracker = array();

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfNotExistsProcess($processUid, "prj_uid");

            //Get data
            $criteria = new \Criteria();

            $criteria->add(\CaseTrackerPeer::PRO_UID, $processUid, \Criteria::EQUAL);

            $rsCriteria = \CaseTrackerPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            if ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                $arrayCaseTracker = $row;
            } else {
                $caseTracker = new \CaseTracker();

                $arrayCaseTracker = array(
                    "PRO_UID"     => $processUid,
                    "CT_MAP_TYPE" => "PROCESSMAP",
                    "CT_DERIVATION_HISTORY" => 1,
                    "CT_MESSAGE_HISTORY"    => 1
                );

                $caseTracker->create($arrayCaseTracker);
            }

            return array(
                "map_type" => $arrayCaseTracker["CT_MAP_TYPE"],
                "routing_history" => (int)($arrayCaseTracker["CT_DERIVATION_HISTORY"]),
                "message_history" => (int)($arrayCaseTracker["CT_MESSAGE_HISTORY"])
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get available Case Tracker Objects of a Process
     *
     * @param string $processUid Unique id of Process
     *
     * return array Return an array with the Case Tracker Objects available of a Process
     */
    public function getAvailableCaseTrackerObjects($processUid)
    {
        try {
            $arrayAvailableCaseTrackerObject = array();

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfNotExistsProcess($processUid, "prj_uid");

            //Get Uids
            $arrayDynaFormUid = array();
            $arrayInputDocumentUid = array();
            $arrayOutputDocumentUid = array();

            $criteria = new \Criteria("workflow");

            $criteria->add(\CaseTrackerObjectPeer::PRO_UID, $processUid, \Criteria::EQUAL);

            $rsCriteria = \CaseTrackerObjectPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                switch ($row["CTO_TYPE_OBJ"]) {
                    case "DYNAFORM":
                        $arrayDynaFormUid[] = $row["CTO_UID_OBJ"];
                        break;
                    case "INPUT_DOCUMENT":
                        $arrayInputDocumentUid[] = $row["CTO_UID_OBJ"];
                        break;
                    case "OUTPUT_DOCUMENT":
                        $arrayOutputDocumentUid[] = $row["CTO_UID_OBJ"];
                        break;
                }
            }

            //Array DB
            $arrayCaseTrackerObject = array();

            $delimiter = \DBAdapter::getStringDelimiter();

            //DynaForms
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\DynaformPeer::DYN_UID);
            $criteria->addSelectColumn(\DynaformPeer::DYN_TITLE);
            $criteria->addSelectColumn(\DynaformPeer::DYN_DESCRIPTION);
            $criteria->add(\DynaformPeer::PRO_UID, $processUid, \Criteria::EQUAL);
            $criteria->add(\DynaformPeer::DYN_UID, $arrayDynaFormUid, \Criteria::NOT_IN);
            $criteria->add(\DynaformPeer::DYN_TYPE, "xmlform", \Criteria::EQUAL);

            $rsCriteria = \DynaformPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();
                $arrayCaseTrackerObject[] = array(
                    "obj_uid"         => $row["DYN_UID"],
                    "obj_title"       => $row["DYN_TITLE"],
                    "obj_description" => $row["DYN_DESCRIPTION"],
                    "obj_type"        => "DYNAFORM"
                );
            }

            //InputDocuments
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\InputDocumentPeer::INP_DOC_UID);
            $criteria->addSelectColumn(\InputDocumentPeer::INP_DOC_TITLE);
            $criteria->addSelectColumn(\InputDocumentPeer::INP_DOC_DESCRIPTION);
            $criteria->add(\InputDocumentPeer::PRO_UID, $processUid, \Criteria::EQUAL);
            $criteria->add(\InputDocumentPeer::INP_DOC_UID, $arrayInputDocumentUid, \Criteria::NOT_IN);

            $rsCriteria = \InputDocumentPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                if ($row["INP_DOC_TITLE"] . "" == "") {
                    //There is no transaltion for this Document name, try to get/regenerate the label
                    $inputDocument = new \InputDocument();
                    $inputDocumentObj = $inputDocument->load($row['INP_DOC_UID']);
                    $row["INP_DOC_TITLE"] = $inputDocumentObj['INP_DOC_TITLE'];
                }

                $arrayCaseTrackerObject[] = array(
                    "obj_uid"         => $row["INP_DOC_UID"],
                    "obj_title"       => $row["INP_DOC_TITLE"],
                    "obj_description" => $row["INP_DOC_DESCRIPTION"],
                    "obj_type"        => "INPUT_DOCUMENT"
                );
            }

            //OutputDocuments
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\OutputDocumentPeer::OUT_DOC_UID);
            $criteria->addSelectColumn(\OutputDocumentPeer::OUT_DOC_TITLE);
            $criteria->addSelectColumn(\OutputDocumentPeer::OUT_DOC_DESCRIPTION);
            $criteria->add(\OutputDocumentPeer::PRO_UID, $processUid, \Criteria::EQUAL);
            $criteria->add(\OutputDocumentPeer::OUT_DOC_UID, $arrayOutputDocumentUid, \Criteria::NOT_IN);

            $rsCriteria = \OutputDocumentPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();
                $arrayCaseTrackerObject[] = array(
                    "obj_uid"         => $row["OUT_DOC_UID"],
                    "obj_title"       => $row["OUT_DOC_TITLE"],
                    "obj_description" => $row["OUT_DOC_DESCRIPTION"],
                    "obj_type"        => "OUTPUT_DOCUMENT"
                );
            }

            $arrayCaseTrackerObject = \ProcessMaker\Util\ArrayUtil::sort(
                $arrayCaseTrackerObject,
                array("obj_type", "obj_title"),
                SORT_ASC
            );

            return $arrayCaseTrackerObject;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all Case Tracker Objects of a Process
     *
     * @param string $processUid Unique id of Process
     *
     * return array Return an array with all Case Tracker Objects of a Process
     */
    public function getCaseTrackerObjects($processUid)
    {
        try {
            $arrayCaseTrackerObject = array();

            //Verify data
            $process = new \ProcessMaker\BusinessModel\Process();

            $process->throwExceptionIfNotExistsProcess($processUid, "prj_uid");

            $dynaform = new \Dynaform();
            $inputDocument = new \InputDocument();
            $outputDocument = new \OutputDocument();

            $arrayCaseTrackerObject = array();

            $criteria = new \Criteria("workflow");
            $criteria->add(\CaseTrackerObjectPeer::PRO_UID, $processUid, \Criteria::EQUAL);

            $rsCriteria = \CaseTrackerObjectPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

            while ($rsCriteria->next()) {
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

                $arrayCaseTrackerObject[] = array(
                    "cto_uid"         => $row["CTO_UID"],
                    "cto_type_obj"    => $row["CTO_TYPE_OBJ"],
                    "cto_uid_obj"     => $row["CTO_UID_OBJ"],
                    "cto_condition"   => $row["CTO_CONDITION"],
                    "cto_position"    => (int)($row["CTO_POSITION"]),
                    "obj_title"       => $titleObj,
                    "obj_description" => $descriptionObj
                );
            }

            $arrayCaseTrackerObject = \ProcessMaker\Util\ArrayUtil::sort($arrayCaseTrackerObject, array("cto_position"), SORT_ASC);

            return $arrayCaseTrackerObject;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

