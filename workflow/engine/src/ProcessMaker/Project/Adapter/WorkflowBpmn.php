<?php

namespace ProcessMaker\Project\Adapter;

use ProcessMaker\Project;
use ProcessMaker\Util\Common;

/**
 * Class WorkflowBpmn
 *
 * @package ProcessMaker\Project\Adapter
 * @author Erik Amaru Ortiz <aortiz.erik@gmail.com, erik@colosa.com>
 */
class WorkflowBpmn extends Project\Workflow
{
    /**
     * @var \ProcessMaker\Project\Bpmn
     */
    protected $bp;

    /**
     * OVERRIDES
     */

    public static function load($prjUid)
    {
        $parent = parent::load($prjUid);

        $me = new self();

        $me->process = $parent->process;
        $me->proUid = $parent->proUid;
        $me->bp = Project\Bpmn::load($prjUid);

        return $me;
    }

    public function create($data)
    {
        try {
            parent::create($data);
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf("Can't create Workflow Project." . PHP_EOL . $e->getMessage()));
        }

        try {
            $bpData = array();
            $bpData["PRJ_UID"] = $this->getUid();

            if (array_key_exists("PRO_TITLE", $data)) {
                $bpData["PRJ_NAME"] = $data["PRO_TITLE"];
            }
            if (array_key_exists("PRO_DESCRIPTION", $data)) {
                $bpData["PRJ_DESCRIPTION"] = $data["PRO_DESCRIPTION"];
            }
            if (array_key_exists("PRO_CREATE_USER", $data)) {
                $bpData["PRJ_AUTHOR"] = $data["PRO_CREATE_USER"];
            } elseif (array_key_exists("USR_UID", $data)) {
                $bpData["PRJ_AUTHOR"] = $data["USR_UID"];
            }

            $this->bp = new Project\Bpmn();
            $this->bp->create($bpData);

            // At this time we will add a default diagram and process
            $this->bp->addDiagram();
            $this->bp->addProcess();

            //Add Audit Log
            $ogetProcess = new \Process();
            $getprocess = $ogetProcess->load($this->getUid());
            $nameProcess = $getprocess['PRO_TITLE'];
            \G::auditLog("ImportProcess", 'BPMN Imported ' . $nameProcess . ' (' . $this->getUid() . ')');
        } catch (\Exception $e) {
            $prjUid = $this->getUid();
            $this->remove();

            throw new \RuntimeException(sprintf(
                "Can't create Project with prj_uid: %s, workflow creation fails." . PHP_EOL . $e->getMessage(),
                $prjUid
            ));
        }
    }

    public static function getList($start = null, $limit = null, $filter = "", $changeCaseTo = CASE_UPPER)
    {
        return parent::getList($start, $limit, $filter, $changeCaseTo);
    }

    public function remove()
    {
        parent::remove();
        $this->bp->remove();
    }

    public function startTaskEndProcessToBpmnEvent(
        $objectBpmnType,
        $objectUid,
        $objectBouX,
        $objectBouY,
        $objectBouWidth,
        $objectBouHeight,
        $eventName,
        $eventType,
        $condition = ""
    )
    {
        try {
            $eventBouWidth = 35;
            $eventBouHeight = $eventBouWidth;

            $eventBouWidth2 = (int)($eventBouWidth / 2);
            $eventBouHeight2 = (int)($eventBouHeight / 2);

            $eventBouHeight12 = (int)($eventBouWidth / 12);

            //
            $objectBouWidth2 = (int)($objectBouWidth / 2);
            $objectBouWidth4 = (int)($objectBouWidth / 4);

            //Event
            if ($objectBpmnType == "bpmnGateway" && $eventType == "END") {
                //Gateway
                $eventBouX = $objectBouX + $objectBouWidth + $objectBouWidth4;
                $eventBouY = $objectBouY + (int)($objectBouHeight / 2) - $eventBouHeight2;
            } else {
                //Activity
                $eventBouX = $objectBouX + $objectBouWidth2 - $eventBouWidth2;
                $eventBouY = ($eventType == "START") ? $objectBouY - $eventBouHeight - $eventBouHeight2 : $objectBouY + $objectBouHeight + $eventBouHeight2 + $eventBouHeight12;
            }

            $arrayData = array(
                "EVN_NAME" => $eventName,
                "EVN_TYPE" => $eventType,
                "EVN_MARKER" => "EMPTY",
                "BOU_X" => $eventBouX,
                "BOU_Y" => $eventBouY,
                "BOU_WIDTH" => $eventBouWidth,
                "BOU_HEIGHT" => $eventBouHeight
            );

            $eventUid = $this->bp->addEvent($arrayData);

            //Flow
            if ($objectBpmnType == "bpmnGateway" && $eventType == "END") {
                //Gateway
                $flowX1 = $objectBouX + $objectBouWidth;
                $flowY1 = $objectBouY + (int)($objectBouHeight / 2);
                $flowX2 = $eventBouX;
                $flowY2 = $eventBouY + $eventBouHeight2;
            } else {
                //Activity
                $flowX1 = $objectBouX + $objectBouWidth2;
                $flowY1 = ($eventType == "START") ? $objectBouY - $eventBouHeight + $eventBouHeight2 : $objectBouY + $objectBouHeight;
                $flowX2 = $flowX1;
                $flowY2 = ($eventType == "START") ? $objectBouY : $objectBouY + $objectBouHeight + $eventBouHeight2 + $eventBouHeight12;
            }

            $arrayData = array(
                "FLO_TYPE" => "SEQUENCE",
                "FLO_ELEMENT_ORIGIN" => ($eventType == "START") ? $eventUid : $objectUid,
                "FLO_ELEMENT_ORIGIN_TYPE" => ($eventType == "START") ? "bpmnEvent" : $objectBpmnType,
                "FLO_ELEMENT_DEST" => ($eventType == "START") ? $objectUid : $eventUid,
                "FLO_ELEMENT_DEST_TYPE" => ($eventType == "START") ? $objectBpmnType : "bpmnEvent",
                "FLO_IS_INMEDIATE" => 1,
                "FLO_CONDITION" => $condition,
                "FLO_X1" => $flowX1,
                "FLO_Y1" => $flowY1,
                "FLO_X2" => $flowX2,
                "FLO_Y2" => $flowY2,
                "FLO_STATE" => json_encode(
                    array(
                        array("x" => $flowX1, "y" => $flowY1),
                        array("x" => $flowX1, "y" => $flowY2 - 5),
                        array("x" => $flowX2, "y" => $flowY2 - 5),
                        array("x" => $flowX2, "y" => $flowY2)
                    )
                )
            );

            $flowUid = $this->bp->addFlow($arrayData);

            //Return
            return $eventUid;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function addTask(array $arrayTaskData)
    {
        try {
            //Task
            $taskUid = parent::addTask($arrayTaskData);

            //BPMN
            //Activity
            $arrayActivityType = array(
                "NORMAL" => "TASK",
                "ADHOC" => "TASK",
                "SUBPROCESS" => "SUB_PROCESS"
            );

            $activityBouX = (int)($arrayTaskData["TAS_POSX"]);
            $activityBouY = (int)($arrayTaskData["TAS_POSY"]);
            $activityBouWidth = (int)($arrayTaskData["TAS_WIDTH"]);
            $activityBouHeight = (int)($arrayTaskData["TAS_HEIGHT"]);

            $arrayData = array(
                "ACT_UID" => $taskUid,
                "ACT_NAME" => $arrayTaskData["TAS_TITLE"],
                "ACT_TYPE" => $arrayActivityType[$arrayTaskData["TAS_TYPE"]],
                "BOU_X" => $activityBouX,
                "BOU_Y" => $activityBouY,
                "BOU_WIDTH" => $activityBouWidth,
                "BOU_HEIGHT" => $activityBouHeight
            );

            $activityUid = $this->bp->addActivity($arrayData);

            if ($arrayTaskData["TAS_START"] == "TRUE") {
                $eventUid = $this->startTaskEndProcessToBpmnEvent(
                    "bpmnActivity",
                    $activityUid,
                    $activityBouX,
                    $activityBouY,
                    $activityBouWidth,
                    $activityBouHeight,
                    "",
                    "START"
                );
            }

            //Return
            return $taskUid;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function routeToBpmnGateway(
        $objectBpmnType,
        $objectUid,
        $objectBouX,
        $objectBouY,
        $objectBouWidth,
        $objectBouHeight,
        $gatewayName,
        $gatewayType,
        $gatewayDirection
    )
    {
        try {
            $gatewayBouWidth = 45;
            $gatewayBouHeight = $gatewayBouWidth;

            $gatewayBouWidth2 = (int)($gatewayBouWidth / 2);
            $gatewayBouHeight2 = (int)($gatewayBouHeight / 2);

            //
            $objectBouWidth2 = (int)($objectBouWidth / 2);
            $objectBouHeight2 = (int)($objectBouHeight / 2);

            //Gateway
            $gatewayBouX = $objectBouX + $objectBouWidth2 - $gatewayBouWidth2;
            $gatewayBouY = ($gatewayDirection == "DIVERGING") ? $objectBouY + $objectBouHeight + $gatewayBouHeight2 : $objectBouY - $gatewayBouHeight - $gatewayBouHeight2;

            $arrayData = array(
                "GAT_NAME" => $gatewayName,
                "GAT_TYPE" => $gatewayType,
                "GAT_DIRECTION" => $gatewayDirection,
                "GAT_DEFAULT_FLOW" => "0",
                "BOU_X" => $gatewayBouX,
                "BOU_Y" => $gatewayBouY,
                "BOU_WIDTH" => $gatewayBouWidth,
                "BOU_HEIGHT" => $gatewayBouHeight
            );

            $gatewayUid = $this->bp->addGateway($arrayData);

            //Flow
            if ($gatewayDirection == "DIVERGING") {
                $flowX1 = $objectBouX + $objectBouWidth2;
                $flowY1 = $objectBouY + $objectBouHeight;
                $flowX2 = $flowX1;
                $flowY2 = $gatewayBouY;
            } else {
                $flowX1 = $objectBouX + $objectBouWidth2;
                $flowY1 = $gatewayBouY + $gatewayBouHeight;
                $flowX2 = $flowX1;
                $flowY2 = $objectBouY;
            }

            $arrayData = array(
                "FLO_TYPE" => "SEQUENCE",
                "FLO_ELEMENT_ORIGIN" => ($gatewayDirection == "DIVERGING") ? $objectUid : $gatewayUid,
                "FLO_ELEMENT_ORIGIN_TYPE" => ($gatewayDirection == "DIVERGING") ? $objectBpmnType : "bpmnGateway",
                "FLO_ELEMENT_DEST" => ($gatewayDirection == "DIVERGING") ? $gatewayUid : $objectUid,
                "FLO_ELEMENT_DEST_TYPE" => ($gatewayDirection == "DIVERGING") ? "bpmnGateway" : $objectBpmnType,
                "FLO_IS_INMEDIATE" => 1,
                "FLO_CONDITION" => "",
                "FLO_X1" => $flowX1,
                "FLO_Y1" => $flowY1,
                "FLO_X2" => $flowX2,
                "FLO_Y2" => $flowY2,
                "FLO_STATE" => json_encode(
                    array(
                        array("x" => $flowX1, "y" => $flowY1),
                        array("x" => $flowX1, "y" => $flowY2 - 5),
                        array("x" => $flowX2, "y" => $flowY2 - 5),
                        array("x" => $flowX2, "y" => $flowY2)
                    )
                )
            );

            $flowUid = $this->bp->addFlow($arrayData);

            //Return
            return $gatewayUid;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function routeToBpmnFlow(
        $objectOriginBpmnType,
        $objectOriginUid,
        $objectOriginBouX,
        $objectOriginBouY,
        $objectOriginBouWidth,
        $objectOriginBouHeight,
        $objectDestBpmnType,
        $objectDestUid,
        $objectDestBouX,
        $objectDestBouY,
        $objectDestBouWidth,
        $objectDestBouHeight,
        $condition = ""
    )
    {
        try {
            $objectOriginBouWidth2 = (int)($objectOriginBouWidth / 2);
            $objectDestBouWidth2 = (int)($objectDestBouWidth / 2);

            $flowX1 = $objectOriginBouX + $objectOriginBouWidth2;
            $flowY1 = $objectOriginBouY + $objectOriginBouHeight;
            $flowX2 = $objectDestBouX + $objectDestBouWidth2;
            $flowY2 = $objectDestBouY;

            //Flow
            $arrayData = array(
                "FLO_TYPE" => "SEQUENCE",
                "FLO_ELEMENT_ORIGIN" => $objectOriginUid,
                "FLO_ELEMENT_ORIGIN_TYPE" => $objectOriginBpmnType,
                "FLO_ELEMENT_DEST" => $objectDestUid,
                "FLO_ELEMENT_DEST_TYPE" => $objectDestBpmnType,
                "FLO_IS_INMEDIATE" => 1,
                "FLO_CONDITION" => $condition,
                "FLO_X1" => $flowX1,
                "FLO_Y1" => $flowY1,
                "FLO_X2" => $flowX2,
                "FLO_Y2" => $flowY2,
                "FLO_STATE" => json_encode(array())
            );

            $flowUid = $this->bp->addFlow($arrayData);

            //Return
            return $flowUid;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function addRouteSecJoin($taskUid, $nextTaskUid)
    {
        try {
            //Route
            $result = parent::addRoute($taskUid, $nextTaskUid, "SEC-JOIN");

            //BPMN
            $arrayTaskData = $this->getTask($taskUid);

            $activityUid = $arrayTaskData["TAS_UID"];
            $activityBouX = (int)($arrayTaskData["TAS_POSX"]);
            $activityBouY = (int)($arrayTaskData["TAS_POSY"]);
            $activityBouWidth = (int)($arrayTaskData["TAS_WIDTH"]);
            $activityBouHeight = (int)($arrayTaskData["TAS_HEIGHT"]);

            $arrayTaskData = $this->getTask($nextTaskUid);

            $nextActivityUid = $arrayTaskData["TAS_UID"];
            $nextActivityBouX = (int)($arrayTaskData["TAS_POSX"]);
            $nextActivityBouY = (int)($arrayTaskData["TAS_POSY"]);
            $nextActivityBouWidth = (int)($arrayTaskData["TAS_WIDTH"]);
            $nextActivityBouHeight = (int)($arrayTaskData["TAS_HEIGHT"]);

            $result = $this->bp->getGatewayByDirectionActivityAndFlow("CONVERGING", $nextActivityUid);

            if (!is_array($result)) {
                $criteria = new \Criteria("workflow");

                $criteria->addSelectColumn(\BpmnFlowPeer::FLO_ELEMENT_ORIGIN . " AS GAT_UID");

                $criteria->add(\BpmnFlowPeer::PRJ_UID, $this->bp->getUid(), \Criteria::EQUAL);
                $criteria->add(\BpmnFlowPeer::FLO_TYPE, "SEQUENCE", \Criteria::EQUAL);
                $criteria->add(\BpmnFlowPeer::FLO_ELEMENT_ORIGIN_TYPE, "bpmnGateway", \Criteria::EQUAL);
                $criteria->add(\BpmnFlowPeer::FLO_ELEMENT_DEST, $activityUid, \Criteria::EQUAL);
                $criteria->add(\BpmnFlowPeer::FLO_ELEMENT_DEST_TYPE, "bpmnActivity", \Criteria::EQUAL);

                $rsCriteria = \BpmnFlowPeer::doSelectRS($criteria);
                $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

                $gatewayParentUid = "";

                if ($rsCriteria->next()) {
                    $row = $rsCriteria->getRow();

                    $gatewayParentUid = $row["GAT_UID"];
                } else {
                    throw new \Exception(\G::LoadTranslation("ID_ROUTE_PARENT_DOES_NOT_EXIST_FOR_ROUTE_SECJOIN"));
                }

                $arrayGatewayData = $this->bp->getGateway2($gatewayParentUid);

                $gatewayParentType = $arrayGatewayData["GAT_TYPE"];

                $gatewayUid = $this->routeToBpmnGateway(
                    "bpmnActivity",
                    $nextActivityUid,
                    $nextActivityBouX,
                    $nextActivityBouY,
                    $nextActivityBouWidth,
                    $nextActivityBouHeight,
                    "",
                    $gatewayParentType,
                    "CONVERGING"
                );

                $arrayGatewayData = $this->bp->getGateway2($gatewayUid);
            } else {
                $arrayGatewayData = $result;
            }

            $gatewayUid = $arrayGatewayData["GAT_UID"];
            $gatewayType = $arrayGatewayData["GAT_TYPE"];
            $gatewayBouX = $arrayGatewayData["BOU_X"];
            $gatewayBouY = $arrayGatewayData["BOU_Y"];
            $gatewayBouWidth = $arrayGatewayData["BOU_WIDTH"];
            $gatewayBouHeight = $arrayGatewayData["BOU_HEIGHT"];

            $flowUid = $this->routeToBpmnFlow(
                "bpmnActivity",
                $activityUid,
                $activityBouX,
                $activityBouY,
                $activityBouWidth,
                $activityBouHeight,
                "bpmnGateway",
                $gatewayUid,
                $gatewayBouX,
                $gatewayBouY,
                $gatewayBouWidth,
                $gatewayBouHeight
            );

            //Return
            return $result;
        } catch (\Exception $e) {
            $this->removeRouteFromTo($taskUid, $nextTaskUid);

            throw $e;
        }
    }

    public function addRoute($taskUid, $nextTaskUid, $type, $condition = "", $default = 0)
    {
        try {
            //Verify data
            if ($type == "SEC-JOIN") {
                throw new \Exception(\G::LoadTranslation("ID_ROUTE_IS_SECJOIN"));
            }

            //Route
            $result = parent::addRoute($taskUid, $nextTaskUid, $type, $condition);

            //BPMN
            $arrayBpmnGatewayType = array(
                "EVALUATE" => "EXCLUSIVE",
                "SELECT" => "COMPLEX",
                "PARALLEL" => "PARALLEL",
                "PARALLEL-BY-EVALUATION" => "INCLUSIVE"
            );

            $arrayTaskData = $this->getTask($taskUid);

            $activityUid = $arrayTaskData["TAS_UID"];
            $activityBouX = (int)($arrayTaskData["TAS_POSX"]);
            $activityBouY = (int)($arrayTaskData["TAS_POSY"]);
            $activityBouWidth = (int)($arrayTaskData["TAS_WIDTH"]);
            $activityBouHeight = (int)($arrayTaskData["TAS_HEIGHT"]);

            switch ($type) {
                case "EVALUATE":
                case "SELECT":
                case "PARALLEL":
                case "PARALLEL-BY-EVALUATION":
                    $result = $this->bp->getGatewayByDirectionActivityAndFlow("DIVERGING", $activityUid);

                    if (!is_array($result)) {
                        $gatewayUid = $this->routeToBpmnGateway(
                            "bpmnActivity",
                            $activityUid,
                            $activityBouX,
                            $activityBouY,
                            $activityBouWidth,
                            $activityBouHeight,
                            "",
                            $arrayBpmnGatewayType[$type],
                            "DIVERGING"
                        );

                        $arrayGatewayData = $this->bp->getGateway2($gatewayUid);
                    } else {
                        $arrayGatewayData = $result;
                    }

                    $gatewayUid = $arrayGatewayData["GAT_UID"];
                    $gatewayType = $arrayGatewayData["GAT_TYPE"];
                    $gatewayBouX = $arrayGatewayData["BOU_X"];
                    $gatewayBouY = $arrayGatewayData["BOU_Y"];
                    $gatewayBouWidth = $arrayGatewayData["BOU_WIDTH"];
                    $gatewayBouHeight = $arrayGatewayData["BOU_HEIGHT"];

                    if ($nextTaskUid != "-1") {
                        $arrayTaskData = $this->getTask($nextTaskUid);

                        $flowUid = $this->routeToBpmnFlow(
                            "bpmnGateway",
                            $gatewayUid,
                            $gatewayBouX,
                            $gatewayBouY,
                            $gatewayBouWidth,
                            $gatewayBouHeight,
                            "bpmnActivity",
                            $arrayTaskData["TAS_UID"],
                            (int)($arrayTaskData["TAS_POSX"]),
                            (int)($arrayTaskData["TAS_POSY"]),
                            (int)($arrayTaskData["TAS_WIDTH"]),
                            (int)($arrayTaskData["TAS_HEIGHT"]),
                            $condition
                        );
                    } else {
                        $eventUid = $this->startTaskEndProcessToBpmnEvent(
                            "bpmnGateway",
                            $gatewayUid,
                            $gatewayBouX,
                            $gatewayBouY,
                            $gatewayBouWidth,
                            $gatewayBouHeight,
                            "",
                            "END",
                            $condition
                        );
                    }
                    break;
                case "SEQUENTIAL":
                    if ($nextTaskUid != "-1") {
                        $arrayTaskData = $this->getTask($nextTaskUid);

                        $flowUid = $this->routeToBpmnFlow(
                            "bpmnActivity",
                            $activityUid,
                            $activityBouX,
                            $activityBouY,
                            $activityBouWidth,
                            $activityBouHeight,
                            "bpmnActivity",
                            $arrayTaskData["TAS_UID"],
                            (int)($arrayTaskData["TAS_POSX"]),
                            (int)($arrayTaskData["TAS_POSY"]),
                            (int)($arrayTaskData["TAS_WIDTH"]),
                            (int)($arrayTaskData["TAS_HEIGHT"])
                        );
                    } else {
                        $eventUid = $this->startTaskEndProcessToBpmnEvent(
                            "bpmnActivity",
                            $activityUid,
                            $activityBouX,
                            $activityBouY,
                            $activityBouWidth,
                            $activityBouHeight,
                            "",
                            "END"
                        );
                    }
                    break;
            }

            //Return
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function addLine($position, $direction = "HORIZONTAL")
    {
        try {
            //Line
            $swiUid = parent::addLine($position, $direction);

            //BPMN
            //Artifact
            $arrayData = array(
                "ART_UID" => $swiUid,
                "ART_TYPE" => ($direction == "HORIZONTAL") ? "HORIZONTAL_LINE" : "VERTICAL_LINE",
                "ART_NAME" => "",
                "BOU_X" => ($direction == "HORIZONTAL") ? -6666 : $position,
                "BOU_Y" => ($direction == "HORIZONTAL") ? $position : -6666,
                "BOU_WIDTH" => 0,
                "BOU_HEIGHT" => 0
            );

            $artifactUid = $this->bp->addArtifact($arrayData);

            //Return
            return $swiUid;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function addText($text, $x, $y)
    {
        try {
            //Line
            $swiUid = parent::addText($text, $x, $y);

            //BPMN
            //Artifact
            $arrayData = array(
                "ART_UID" => $swiUid,
                "ART_TYPE" => "TEXT_ANNOTATION",
                "ART_NAME" => $text,
                "BOU_X" => $x,
                "BOU_Y" => $y,
                "BOU_WIDTH" => 100,
                "BOU_HEIGHT" => 30
            );

            $artifactUid = $this->bp->addArtifact($arrayData);

            //Return
            return $swiUid;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function generateBpmn($processUid, $processUidFieldNameForException, $userUid = "")
    {
        $bpmnProjectUid = "";

        try {
            //Verify data
            $obj = \ProcessPeer::retrieveByPK($processUid);

            if (is_null($obj)) {
                throw new \Exception(\G::LoadTranslation("ID_PROCESS_DOES_NOT_EXIST", array($processUidFieldNameForException, $processUid)));
            }

            //Verify data
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\BpmnProjectPeer::PRJ_UID);
            $criteria->add(\BpmnProjectPeer::PRJ_UID, $processUid, \Criteria::EQUAL);

            $rsCriteria = \BpmnProjectPeer::doSelectRS($criteria);

            if ($rsCriteria->next()) {
                throw new \Exception(\G::LoadTranslation("ID_PROJECT_IS_BPMN", array($processUidFieldNameForException, $processUid)));
            }

            //Set data
            $processUidBk = $processUid;

            list($arrayWorkflowData, $arrayWorkflowFile) = $this->getData($processUid); //Get workflow data

            $arrayWorkflowData["process"] = $arrayWorkflowData["process"][0];

            $arrayWorkflowData["groupwfs"] = array();

            //Create WorkflowBpmn
            $arrayUid = array();
            $arrayUid2 = array();

            //Process
            $arrayProcessData = $arrayWorkflowData["process"];

            unset(
                $arrayProcessData["PRO_UID"],
                $arrayProcessData["PRO_UPDATE_DATE"]
            );

            $arrayProcessData["PRO_PARENT"] = $processUidBk;
            $arrayProcessData["PRO_TITLE"] = $arrayProcessData["PRO_TITLE"] . " - New version - " . date("M d, H:i:s");
            $arrayProcessData["PRO_CREATE_USER"] = ($userUid != "") ? $userUid : "00000000000000000000000000000001";

            $this->create($arrayProcessData);

            $processUid = $this->getUid();

            $bpmnProjectUid = $processUid;

            //Task
            foreach ($arrayWorkflowData["tasks"] as $value) {
                $arrayTaskData = $value;

                $taskUidOld = $arrayTaskData["TAS_UID"];

                //Add
                unset($arrayTaskData["TAS_UID"]);

                $taskUid = $this->addTask($arrayTaskData);

                //Add new UID
                $arrayUid["task"][$taskUidOld] = $taskUid;

                $arrayUid2[] = array(
                    "old_uid" => $taskUidOld,
                    "new_uid" => $taskUid
                );
            }

            //$arrayWorkflowData["tasks"] = array();

            //Route
            $arrayRouteSecJoin = array();

            foreach ($arrayWorkflowData["routes"] as $value) {
                $arrayRouteData = $value;

                $arrayRouteData["TAS_UID"] = $arrayUid["task"][$arrayRouteData["TAS_UID"]];
                $arrayRouteData["ROU_NEXT_TASK"] = ($arrayRouteData["ROU_NEXT_TASK"] != "-1") ? $arrayUid["task"][$arrayRouteData["ROU_NEXT_TASK"]] : $arrayRouteData["ROU_NEXT_TASK"];

                if ($arrayRouteData["ROU_TYPE"] != "SEC-JOIN") {
                    //Add
                    $result = $this->addRoute($arrayRouteData["TAS_UID"], $arrayRouteData["ROU_NEXT_TASK"], $arrayRouteData["ROU_TYPE"], $arrayRouteData["ROU_CONDITION"]);
                } else {
                    $arrayRouteSecJoin[] = $arrayRouteData;
                }
            }

            $arrayWorkflowData["routes"] = array();

            //Route SEC-JOIN
            foreach ($arrayRouteSecJoin as $value) {
                $arrayRouteData = $value;

                $result = $this->addRouteSecJoin($arrayRouteData["TAS_UID"], $arrayRouteData["ROU_NEXT_TASK"]);
            }

            //Lane
            foreach ($arrayWorkflowData["lanes"] as $value) {
                $arrayLaneData = $value;

                $swiX = (int)($arrayLaneData["SWI_X"]);
                $swiY = (int)($arrayLaneData["SWI_Y"]);

                switch ($arrayLaneData["SWI_TYPE"]) {
                    case "TEXT":
                        $swiUid = $this->addText($arrayLaneData["SWI_TEXT"], $swiX, $swiY);
                        break;
                    case "LINE":
                        $direction = (($swiX == 0) ? "HORIZONTAL" : "VERTICAL");

                        $swiUid = $this->addLine(($direction == "HORIZONTAL") ? $swiY : $swiX, $direction);
                        break;
                }
            }

            $arrayWorkflowData["lanes"] = array();

            //Data
            $arrayUid2 = array_merge(
                array(
                    array(
                        "old_uid" => $processUidBk,
                        "new_uid" => $processUid
                    )
                ),
                $arrayUid2
            );

            list($arrayWorkflowData, $arrayWorkflowFile) = $this->updateDataUidByArrayUid($arrayWorkflowData, $arrayWorkflowFile, $arrayUid2);

            $arrayWorkflowData["tasks"] = array();

            $this->createDataByArrayData($arrayWorkflowData);
            $this->createDataFileByArrayFile($arrayWorkflowFile);

            //Return
            return $bpmnProjectUid;
        } catch (\Exception $e) {
            if ($bpmnProjectUid != "") {
                $this->remove();
            }

            throw $e;
        }
    }
}
