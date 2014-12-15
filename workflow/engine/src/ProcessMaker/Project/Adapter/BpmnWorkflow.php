<?php
namespace ProcessMaker\Project\Adapter;

use ProcessMaker\Project;
use ProcessMaker\Util;

/**
 * Class BpmnWorkflow
 *
 * @package ProcessMaker\Project\Adapter
 * @author Erik Amaru Ortiz <aortiz.erik@gmail.com, erik@colosa.com>
 */
class BpmnWorkflow extends Project\Bpmn
{
    /**
     * @var \ProcessMaker\Project\Workflow
     */
    protected $wp;

    const BPMN_GATEWAY_COMPLEX = "COMPLEX";
    const BPMN_GATEWAY_PARALLEL = "PARALLEL";
    const BPMN_GATEWAY_INCLUSIVE = "INCLUSIVE";
    const BPMN_GATEWAY_EXCLUSIVE = "EXCLUSIVE";


    /**
     * OVERRIDES
     */

    public static function load($prjUid)
    {

        $parent = parent::load($prjUid);
        //return new BpmnWorkflow();

        $me = new BpmnWorkflow();

        $me->project = $parent->project;
        $me->prjUid = $parent->project->getPrjUid();
        $me->wp = Project\Workflow::load($me->prjUid);

        return $me;
    }

    public function create($data)
    {
        try {
            parent::create($data);
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf("Can't create Bpmn Project." . PHP_EOL . $e->getMessage()));
        }

        try {
            $wpData = array();
            $wpData["PRO_UID"] = $this->getUid();

            if (array_key_exists("PRJ_NAME", $data)) {
                $wpData["PRO_TITLE"] = $data["PRJ_NAME"];
            }
            if (array_key_exists("PRJ_DESCRIPTION", $data)) {
                $wpData["PRO_DESCRIPTION"] = $data["PRJ_DESCRIPTION"];
            }
            if (array_key_exists("PRJ_AUTHOR", $data)) {
                $wpData["PRO_CREATE_USER"] = $data["PRJ_AUTHOR"];
            }

            $this->wp = new Project\Workflow();
            $this->wp->create($wpData);

        } catch (\Exception $e) {
            $prjUid = $this->getUid();
            //$this->remove();
            $bpmnProject = Project\Bpmn::load($prjUid);
            $bpmnProject->remove();

            throw new \RuntimeException(sprintf(
                "Can't create Bpmn Project with prj_uid: %s, workflow creation fails." . PHP_EOL . $e->getMessage()
                , $prjUid
            ));
        }
    }

    public function update($data)
    {
        parent::update($data);

        $arrayData = array();

        if (isset($data["PRJ_UID"])) {
            $arrayData["PRO_UID"] = $data["PRJ_UID"];
        }

        if (isset($data["PRJ_NAME"])) {
            $arrayData["PRO_TITLE"] = $data["PRJ_NAME"];
        }

        if (isset($data["PRJ_DESCRIPTION"])) {
            $arrayData["PRO_DESCRIPTION"] = $data["PRJ_DESCRIPTION"];
        }

        if (isset($data["PRJ_STATUS"])) {
            $arrayData["PRO_STATUS"] = $data["PRJ_STATUS"];
        }

        $this->wp->update($arrayData);
    }

    public static function getList($start = null, $limit = null, $filter = "", $changeCaseTo = CASE_UPPER)
    {
        $bpmnProjects = parent::getList($start, $limit, $filter);
        $workflowProjects = Project\Workflow::getList($start, $limit, $filter);
        $bpmnProjectsUid = array();
        $bpmnProjectsList = array();
        $list = array();

        foreach ($bpmnProjects as $bpmnProject) {
            $bpmnProjectsList[$bpmnProject["PRJ_UID"]] = $bpmnProject;
        }

        $bpmnProjectsUid = array_keys($bpmnProjectsList);

        foreach ($workflowProjects as $workflowProject) {
            $data["PRJ_UID"] = $workflowProject["PRO_UID"];
            $data["PRJ_NAME"] = $workflowProject["PRO_TITLE"];
            $data["PRJ_DESCRIPTION"] = $workflowProject["PRO_DESCRIPTION"];
            $data["PRJ_CATEGORY"] = $workflowProject["PRO_CATEGORY"];

            if (in_array($workflowProject["PRO_UID"], $bpmnProjectsUid)) {
                $data["PRJ_TYPE"] = "bpmn";
                $data["PRJ_CREATE_DATE"] = $bpmnProjectsList[$workflowProject["PRO_UID"]]["PRJ_CREATE_DATE"];
                $data["PRJ_UPDATE_DATE"] = $bpmnProjectsList[$workflowProject["PRO_UID"]]["PRJ_UPDATE_DATE"];
            } else {
                $data["PRJ_TYPE"] = "classic";
                $data["PRJ_CREATE_DATE"] = $workflowProject["PRO_CREATE_DATE"];
                $data["PRJ_UPDATE_DATE"] = $workflowProject["PRO_UPDATE_DATE"];
            }

            if ($changeCaseTo != CASE_UPPER) {
                $data = array_change_key_case($data, $changeCaseTo);
            }

            $list[] = $data;
        }

        return $list;
    }

    public function addActivity($data)
    {
        $taskData = array();

        $actUid = parent::addActivity($data);
        $taskData["TAS_UID"] = $actUid;

        if (array_key_exists("ACT_NAME", $data)) {
            $taskData["TAS_TITLE"] = $data["ACT_NAME"];
        }
        if (array_key_exists("ACT_NAME", $data)) {
            $taskData["TAS_POSX"] = $data["BOU_X"];
        }
        if (array_key_exists("ACT_NAME", $data)) {
            $taskData["TAS_POSY"] = $data["BOU_Y"];
        }
        if (array_key_exists("ACT_TYPE", $data)) {
            if ($data["ACT_TYPE"] == "SUB_PROCESS") {
                $taskData["TAS_TYPE"] = "SUBPROCESS";
            } else {
                $taskData["TAS_TYPE"] = "NORMAL";
            }
        }

        $this->wp->addTask($taskData);

        return $actUid;
    }

    public function updateActivity($actUid, $data)
    {
        parent::updateActivity($actUid, $data);

        $taskData = array();

        if (array_key_exists("ACT_NAME", $data)) {
            $taskData["TAS_TITLE"] = $data["ACT_NAME"];
        }
        if (array_key_exists("ACT_NAME", $data)) {
            $taskData["TAS_POSX"] = $data["BOU_X"];
        }
        if (array_key_exists("ACT_NAME", $data)) {
            $taskData["TAS_POSY"] = $data["BOU_Y"];
        }

        $this->wp->updateTask($actUid, $taskData);
    }

    public function removeActivity($actUid)
    {
        parent::removeActivity($actUid);
        $this->wp->removeTask($actUid);

    }

    public function removeGateway($gatUid)
    {
//        $gatewayData = $this->getGateway($gatUid);
//        $flowsDest = \BpmnFlow::findAllBy(\BpmnFlowPeer::FLO_ELEMENT_DEST, $gatUid);

//        foreach ($flowsDest as $flowDest) {
//            switch ($flowDest->getFloElementOriginType()) {
//                case "bpmnActivity":
//                    $actUid = $flowDest->getFloElementOrigin();
//                    $flowsOrigin = \BpmnFlow::findAllBy(\BpmnFlowPeer::FLO_ELEMENT_ORIGIN, $gatUid);
//
//                    foreach ($flowsOrigin as $flowOrigin) {
//                        switch ($flowOrigin->getFloElementDestType()) {
//                            case "bpmnActivity":
//                                $toActUid = $flowOrigin->getFloElementDest();
//                                $this->wp->removeRouteFromTo($actUid, $toActUid);
//                                break;
//                        }
//                    }
//                    break;
//            }
//        }

        parent::removeGateway($gatUid);
    }

    public function addFlow($data)
    {
        $floUid = parent::addFlow($data);

        // to add start event->activity  as initial or end task
        switch ($data["FLO_ELEMENT_ORIGIN_TYPE"]) {
            case "bpmnEvent":
                switch ($data["FLO_ELEMENT_DEST_TYPE"]) {
                    case "bpmnActivity":
                        $event = \BpmnEventPeer::retrieveByPK($data["FLO_ELEMENT_ORIGIN"]);

                        // setting as start task
                        if ($event && $event->getEvnType() == "START") {
                            $this->wp->setStartTask($data["FLO_ELEMENT_DEST"]);
                        }

                        $this->updateEventStartObjects($data["FLO_ELEMENT_ORIGIN"], $data["FLO_ELEMENT_DEST"]);
                        break;
                }
                break;
            case "bpmnActivity":
                switch ($data["FLO_ELEMENT_DEST_TYPE"]) {
                    case "bpmnEvent":
                        $actUid = $data["FLO_ELEMENT_ORIGIN"];
                        $evnUid = $data["FLO_ELEMENT_DEST"];
                        $event = \BpmnEventPeer::retrieveByPK($evnUid);

                        // setting as end task
                        if ($event && $event->getEvnType() == "END") {
                            $this->wp->setEndTask($actUid);
                        }
                        break;
                }
                break;
        }

        return $floUid;
    }

    public function updateFlow($floUid, $data, $flows)
    {
        $flowBefore = \BpmnFlowPeer::retrieveByPK($floUid);

        parent::updateFlow($floUid, $data);

        $flowCurrent = \BpmnFlowPeer::retrieveByPK($floUid);

        //Verify case: Event1(start) -> Activity1 -----Update-to----> Event1(start) -> Activity2
        if ($flowBefore->getFloElementOriginType() == "bpmnEvent" && $flowBefore->getFloElementDestType() == "bpmnActivity" &&
            $flowCurrent->getFloElementOriginType() == "bpmnEvent" && $flowCurrent->getFloElementDestType() == "bpmnActivity" &&
            $flowBefore->getFloElementOrigin() == $flowCurrent->getFloElementOrigin() &&
            $flowBefore->getFloElementDest() != $flowCurrent->getFloElementDest()
        ) {
            $event = \BpmnEventPeer::retrieveByPK($flowBefore->getFloElementOrigin());

            if (!is_null($event) && $event->getEvnType() == "START") {
                //Remove as start task
                $this->wp->setStartTask($flowBefore->getFloElementDest(), false);

                //Setting as start task
                $this->wp->setStartTask($flowCurrent->getFloElementDest());

                $this->updateEventStartObjects($flowCurrent->getFloElementOrigin(), $flowCurrent->getFloElementDest());
            }
        }

        //Verify case: Activity1 -> Event1(end) -----Update-to----> Activity2 -> Event1(end)
        if ($flowBefore->getFloElementOriginType() == "bpmnActivity" && $flowBefore->getFloElementDestType() == "bpmnEvent" &&
            $flowCurrent->getFloElementOriginType() == "bpmnActivity" && $flowCurrent->getFloElementDestType() == "bpmnEvent" &&
            $flowBefore->getFloElementOrigin() != $flowCurrent->getFloElementOrigin() &&
            $flowBefore->getFloElementDest() == $flowCurrent->getFloElementDest()
        ) {
            $event = \BpmnEventPeer::retrieveByPK($flowBefore->getFloElementDest());

            if (!is_null($event) && $event->getEvnType() == "END") {
                //Remove as end task
                $this->wp->setEndTask($flowBefore->getFloElementOrigin(), false);

                //Setting as end task
                $this->wp->setEndTask($flowCurrent->getFloElementOrigin());
            }
        }

        //Verify case: Activity1 -> Event1(end) -----Update-to----> Activity1 -> Activity2
        if ($flowBefore->getFloElementOriginType() == "bpmnActivity" && $flowBefore->getFloElementDestType() == "bpmnEvent" &&
            $flowCurrent->getFloElementOriginType() == "bpmnActivity" && $flowCurrent->getFloElementDestType() == "bpmnActivity" &&
            $flowBefore->getFloElementOrigin() == $flowCurrent->getFloElementOrigin()
        ) {
            $event = \BpmnEventPeer::retrieveByPK($flowBefore->getFloElementDest());

            if (!is_null($event) && $event->getEvnType() == "END") {
                //Remove as end task
                $this->wp->setEndTask($flowBefore->getFloElementOrigin(), false);
            }
        }

        //Verify case: Activity1 -> Activity2 -----Update-to----> Activity1 -> Activity3
        if ($flowBefore->getFloElementOriginType() == "bpmnActivity" && $flowBefore->getFloElementDestType() == "bpmnActivity" &&
            $flowCurrent->getFloElementOriginType() == "bpmnActivity" && $flowCurrent->getFloElementDestType() == "bpmnActivity" &&
            $flowBefore->getFloElementOrigin() == $flowCurrent->getFloElementOrigin() &&
            $flowBefore->getFloElementDest() != $flowCurrent->getFloElementDest()
        ) {
            $this->wp->removeRouteFromTo($flowBefore->getFloElementOrigin(), $flowBefore->getFloElementDest());
        }
    }

    public function removeFlow($floUid)
    {
        $flow = \BpmnFlowPeer::retrieveByPK($floUid);
        parent::removeFlow($floUid);

        // verify case: event(start) -> activity
        // => find the corresponding task and unset it as start task
        if ($flow->getFloElementOriginType() == "bpmnEvent" &&
            $flow->getFloElementDestType() == "bpmnActivity"
        ) {
            $event = \BpmnEventPeer::retrieveByPK($flow->getFloElementOrigin());

            if (! is_null($event) && $event->getEvnType() == "START") {
                $activity = \BpmnActivityPeer::retrieveByPK($flow->getFloElementDest());
                if (! is_null($activity)) {
                    $this->wp->setStartTask($activity->getActUid(), false);
                }
            }

            $this->updateEventStartObjects($flow->getFloElementOrigin(), "");
        } elseif ($flow->getFloElementOriginType() == "bpmnActivity" &&
            $flow->getFloElementDestType() == "bpmnEvent") {
            // verify case: activity -> event(end)
            // => find the corresponding task and unset it as start task
            $event = \BpmnEventPeer::retrieveByPK($flow->getFloElementDest());

            if (! is_null($event) && $event->getEvnType() == "END") {
                $activity = \BpmnActivityPeer::retrieveByPK($flow->getFloElementOrigin());

                if (! is_null($activity)) {
                    $this->wp->setEndTask($activity->getActUid(), false);
                }
            }
        } else {
            switch ($flow->getFloElementOriginType()) {
                case "bpmnActivity":
                    switch ($flow->getFloElementDestType()) {
                        // activity->activity
                        case "bpmnActivity":
                            $this->wp->removeRouteFromTo($flow->getFloElementOrigin(), $flow->getFloElementDest());
                            break;
                    }
                    break;
            }
        }

        // TODO Complete for other routes, activity->activity, activity->gateway and viceversa
    }

    public function addEvent($data)
    {
        if (! array_key_exists("EVN_TYPE", $data)) {
            throw new \RuntimeException("Required param \"EVN_TYPE\" is missing.");
        }

        $eventUid = parent::addEvent($data);
        $event = \BpmnEventPeer::retrieveByPK($eventUid);

        // create case scheduler
        if ($event && $event->getEvnMarker() == "TIMER" && $event->getEvnType() == "START") {
            $this->wp->addCaseScheduler($eventUid);
        }

        // create web entry
        if ($event && $event->getEvnMarker() == "MESSAGE" && $event->getEvnType() == "START") {
            $this->wp->addWebEntry($eventUid);
        }

        return $eventUid;
    }

    public function removeEvent($data)
    {

        $event = \BpmnEventPeer::retrieveByPK($data);

        // delete case scheduler
        if ($event && $event->getEvnMarker() == "TIMER" && $event->getEvnType() == "START") {
            $this->wp->removeCaseScheduler($data);
        }

        // delete web entry
        if ($event && $event->getEvnMarker() == "MESSAGE" && $event->getEvnType() == "START") {
            $this->wp->removeWebEntry($data);
        }

        parent::removeEvent($data);
    }

    public function updateEventStartObjects($eventUid, $taskUid)
    {
        $event = \BpmnEventPeer::retrieveByPK($eventUid);

        //Update case scheduler
        if (!is_null($event) && $event->getEvnType() == "START" && $event->getEvnMarker() == "TIMER") {
            $this->wp->updateCaseScheduler($eventUid, array("TAS_UID" => $taskUid));
        }

        //Update web entry
        if (!is_null($event) && $event->getEvnType() == "START" && $event->getEvnMarker() == "MESSAGE") {
            $this->wp->updateWebEntry($eventUid, array("TAS_UID" => $taskUid));
        }
    }

    public function mapBpmnFlowsToWorkflowRoutes()
    {
        $activities = $this->getActivities();

        foreach ($activities as $activity) {

            $flows = \BpmnFlow::findAllBy(array(
                \BpmnFlowPeer::FLO_ELEMENT_ORIGIN => $activity["ACT_UID"],
                \BpmnFlowPeer::FLO_ELEMENT_ORIGIN_TYPE => "bpmnActivity"
            ));

            //
            foreach ($flows as $flow) {
                switch ($flow->getFloElementDestType()) {
                    case "bpmnActivity":
                        // (activity -> activity)
                        $this->wp->addRoute($activity["ACT_UID"], $flow->getFloElementDest(), "SEQUENTIAL");
                        break;

                    case "bpmnGateway":
                        // (activity -> gateway)
                        // we must find the related flows: gateway -> <object>
                        $gatUid = $flow->getFloElementDest();
                        $gatewayFlows = \BpmnFlow::findAllBy(array(
                            \BpmnFlowPeer::FLO_ELEMENT_ORIGIN => $gatUid,
                            \BpmnFlowPeer::FLO_ELEMENT_ORIGIN_TYPE => "bpmnGateway"
                        ));

                        if ($gatewayFlows > 0) {
                            $this->wp->resetTaskRoutes($activity["ACT_UID"]);
                        }

                        foreach ($gatewayFlows as $gatewayFlow) {
                            $gatewayFlow = $gatewayFlow->toArray();

                            switch ($gatewayFlow['FLO_ELEMENT_DEST_TYPE']) {
                                case 'bpmnEvent':
                                case 'bpmnActivity':
                                    // (gateway -> activity)
                                    $gateway = \BpmnGateway::findOneBy(\BpmnGatewayPeer::GAT_UID, $gatUid)->toArray();
                                    switch ($gateway["GAT_TYPE"]) {
                                        //case 'SELECTION':
                                        case self::BPMN_GATEWAY_COMPLEX:
                                            $routeType = "SELECT";
                                            break;
                                        //case 'EVALUATION':
                                        case self::BPMN_GATEWAY_EXCLUSIVE:
                                            $routeType = "EVALUATE";
                                            break;
                                        //case 'PARALLEL':
                                        case self::BPMN_GATEWAY_PARALLEL:
                                            if ($gateway["GAT_DIRECTION"] == "DIVERGING") {
                                                $routeType = "PARALLEL";
                                            } elseif ($gateway["GAT_DIRECTION"] == "CONVERGING") {
                                                $routeType = "SEC-JOIN";
                                            } else {
                                                throw new \LogicException(sprintf(
                                                    "Invalid Gateway direction, accepted values: [%s|%s], given: %s.",
                                                    "DIVERGING", "CONVERGING", $gateway["GAT_DIRECTION"]
                                                ));
                                            }
                                            break;
                                        //case 'PARALLEL_EVALUATION':
                                        case self::BPMN_GATEWAY_INCLUSIVE:
                                            if ($gateway["GAT_DIRECTION"] == "DIVERGING") {
                                                $routeType = "PARALLEL-BY-EVALUATION";
                                            } elseif ($gateway["GAT_DIRECTION"] == "CONVERGING") {
                                                $routeType = "SEC-JOIN";
                                            } else {
                                                throw new \LogicException(sprintf(
                                                    "Invalid Gateway direction, accepted values: [%s|%s], given: %s.",
                                                    "DIVERGING", "CONVERGING", $gateway["GAT_DIRECTION"]
                                                ));
                                            }
                                            break;
                                        default:
                                            throw new \LogicException(sprintf("Unsupported Gateway type: %s", $gateway['GAT_TYPE']));
                                    }
                                    $condition = array_key_exists('FLO_CONDITION', $gatewayFlow) ? $gatewayFlow["FLO_CONDITION"] : '';

                                    if ($gatewayFlow['FLO_ELEMENT_DEST_TYPE'] == 'bpmnEvent') {
                                        $event = \BpmnEventPeer::retrieveByPK($gatewayFlow['FLO_ELEMENT_DEST']);
                                        if ($event->getEvnType() == "END") {
                                            $this->wp->addRoute($activity["ACT_UID"], -1, $routeType, $condition);
                                        }
                                    } else {
                                        $this->wp->addRoute($activity["ACT_UID"], $gatewayFlow['FLO_ELEMENT_DEST'], $routeType, $condition);
                                    }
                                    break;
                                default:
                                    // for processmaker is only allowed flows between "gateway -> activity"
                                    // any another flow is considered invalid
                                    throw new \LogicException(sprintf(
                                        "For ProcessMaker is only allowed flows between \"gateway -> activity\" " . PHP_EOL .
                                        "Given: bpmnGateway -> " . $gatewayFlow['FLO_ELEMENT_DEST_TYPE']
                                    ));
                            }
                        }
                        break;
                }
            }
        }
    }

    public function remove()
    {
        parent::remove();
        $this->wp->remove();
    }

    public static function createFromStruct(array $projectData, $generateUid = true)
    {
        $projectData["prj_name"] = trim($projectData["prj_name"]);
        if ($projectData["prj_name"] == '') {
            throw new \Exception("`prj_name` is required but it is empty.");
        }
        if (\Process::existsByProTitle($projectData["prj_name"])) {
            throw new \Exception("Project with name: {$projectData["prj_name"]}, already exists.");
        }
        $activities = $projectData['diagrams']['0']['activities'];
        foreach ($activities as $value) {
            if (empty($value['act_name'])) {
                throw new \Exception("For activity: {$value['act_uid']} `act_name` is required but missing.");
            }
            if (empty($value['act_type'])) {
                throw new \Exception("For activity: {$value['act_uid']} `act_type` is required but missing.");
            }
        }
        $events = $projectData['diagrams']['0']['events'];
        foreach ($events as $value) {
            if (empty($value['evn_type'])) {
                throw new \Exception("For event: {$value['evn_uid']} `evn_type` is required but missing.");
            }
            if (empty($value['evn_marker'])) {
                throw new \Exception("For event: {$value['evn_uid']} `evn_marker` is required but missing.");
            }
        }
        $bwp = new self;
        $result = array();
        $data = array();

        if ($generateUid) {
            $result[0]["old_uid"] = isset($projectData["prj_uid"]) ? $projectData["prj_uid"] : "";
            $projectData["prj_uid"] = Util\Common::generateUID();
            $result[0]["new_uid"] = $projectData["prj_uid"];
            $result[0]["object"] = "project";
        }

        $data["PRJ_UID"] = $projectData["prj_uid"];
        $data["PRJ_AUTHOR"] = $projectData["prj_author"];

        $bwp->create($data);

        $diagramData = $processData = array();

        if (array_key_exists("diagrams", $projectData) && is_array($projectData["diagrams"]) && count($projectData["diagrams"]) > 0) {
            $diagramData = array_change_key_case($projectData["diagrams"][0], CASE_UPPER);

            if ($generateUid) {
                $result[1]["old_uid"] = $diagramData["DIA_UID"];
                $diagramData["DIA_UID"] = Util\Common::generateUID();
                $result[1]["new_uid"] = $diagramData["DIA_UID"];
                $result[1]["object"] = "diagram";
            }
        }

        $bwp->addDiagram($diagramData);

        if (array_key_exists("process", $projectData) && is_array($projectData["process"])) {
            $processData = array_change_key_case($projectData["process"], CASE_UPPER);
            if ($generateUid) {
                $result[2]["old_uid"] = $processData["PRO_UID"];
                $processData["PRO_UID"] = Util\Common::generateUID();
                $result[2]["new_uid"] = $processData["PRO_UID"];
                $result[2]["object"] = "process";
            }
        }

        $bwp->addProcess($processData);

        $mappedUid = array_merge($result, self::updateFromStruct($bwp->prjUid, $projectData, $generateUid, true));

        return $generateUid ? $mappedUid : $bwp->getUid();
    }

    /**
     * Compose and return a Project struct
     *
     * Example struct return:
     *  array(
     *    "prj_uid" => "25111170353317e324d6e23073851309",
     *    "prj_name" => "example project",
     *    "prj_description" => "project desc.",
     *    ...
     *    "diagrams" => array(
     *      array(
     *        "dia_uid" => "94208559153317e325f1c24068030751",
     *        "dia_name" => "Example Diagram",
     *        ...
     *        "activities" => array(...),
     *        "events" => array(...),
     *        "gateways" => array(...),
     *        "flows" => array(...),
     *        "artifacts" => array(...),
     *        "laneset" => array(...),
     *        "lanes" => array(...)
     *      )
     *    )
     *  )
     *
     * @param $prjUid
     * @return array
     */
    public static function getStruct($prjUid)
    {
        $bwp = BpmnWorkflow::load($prjUid);

        $project = array_change_key_case($bwp->getProject(), CASE_LOWER);
        $diagram = $bwp->getDiagram();
        $process = $bwp->getProcess();
        $diagram["pro_uid"] = $process["PRO_UID"];

        $configList = array("changeCaseTo" => CASE_LOWER);

        if (! is_null($diagram)) {
            $diagram = array_change_key_case($diagram, CASE_LOWER);
            $diagram["activities"] = $bwp->getActivities($configList);
            $diagram["events"] = $bwp->getEvents($configList);
            $diagram["gateways"] = $bwp->getGateways($configList);
            $diagram["flows"] = $bwp->getFlows($configList);
            $diagram["artifacts"] = $bwp->getArtifacts($configList);
            $diagram["laneset"] = $bwp->getLanesets($configList);
            $diagram["lanes"] = $bwp->getLanes($configList);
            $diagram["data"] = $bwp->getDataCollection($configList);
            $diagram["participants"] = $bwp->getParticipants($configList);
            $project["diagrams"][] = $diagram;
        }

        return $project;
    }

    /**
     * Update project from a struct defined.
     *
     * This function make add, update or delete of elements of a project
     * Actions is based on a diff from project save in Db and the given structure as param, by the following criteria.
     *
     * 1. Elements that are on the struct, but they are not in the Db will be created on Db
     * 2. Elements that are on the struct and they are found in db, will be compared, if they have been modified then will be updated on Db
     * 3. Elements found in Db but they are not present on the struct will be considered deleted, so they will be deleted from Db.
     *
     * Example Struct:
     *  array(
     *    "prj_uid" => "25111170353317e324d6e23073851309",
     *    "prj_name" => "example project",
     *    "prj_description" => "project desc.",
     *    ...
     *    "diagrams" => array(
     *      array(
     *        "dia_uid" => "94208559153317e325f1c24068030751",
     *        "dia_name" => "Example Diagram",
     *        ...
     *        "activities" => array(...),
     *        "events" => array(...),
     *        "gateways" => array(...),
     *        "flows" => array(...),
     *        "artifacts" => array(...),
     *        "laneset" => array(...),
     *        "lanes" => array(...)
     *      )
     *    )
     *  )
     *
     * Notes:
     *   1. All elements keys are in lowercase
     *   2. the "diagrams" element is an array of arrays
     *
     * @param $prjUid
     * @param $projectData
     * @return array
     */
    public static function updateFromStruct($prjUid, $projectData, $generateUid = true, $forceInsert = false)
    {
        $diagram = isset($projectData["diagrams"]) && isset($projectData["diagrams"][0]) ? $projectData["diagrams"][0] : array();
        $diagram["activities"] = isset($diagram["activities"])? $diagram["activities"]: array();
        $diagram["artifacts"]  = isset($diagram["artifacts"])? $diagram["artifacts"]: array();
        $diagram["gateways"] = isset($diagram["gateways"])? $diagram["gateways"]: array();
        $diagram["events"] = isset($diagram["events"])? $diagram["events"]: array();
        $diagram["data"] = isset($diagram["data"])? $diagram["data"]: array();
        $diagram["participants"] = isset($diagram["participants"])? $diagram["participants"]: array();
        $result = array();

        $projectData['prj_uid'] = $prjUid;
        $bwp = BpmnWorkflow::load($prjUid);
        $projectRecord = array_change_key_case($projectData, CASE_UPPER);
        $bwp->update($projectRecord);

        /*
         * Diagram's Activities Handling
         */
        $whiteList = array();
        foreach ($diagram["activities"] as $i => $activityData) {
            $activityData = array_change_key_case($activityData, CASE_UPPER);
            unset($activityData["_EXTENDED"], $activityData["BOU_ELEMENT_ID"]);
            $activityData = Util\ArrayUtil::boolToIntValues($activityData);

            $activity = $bwp->getActivity($activityData["ACT_UID"]);

            if ($forceInsert || is_null($activity)) {
                if ($generateUid) {
                    //Activity
                    unset($activityData["BOU_UID"]);

                    $uidOld = $activityData["ACT_UID"];
                    $activityData["ACT_UID"] = Util\Common::generateUID();

                    $result[] = array(
                        "object"  => "activity",
                        "old_uid" => $uidOld,
                        "new_uid" => $activityData["ACT_UID"]
                    );
                }

                $bwp->addActivity($activityData);
            } elseif (! $bwp->isEquals($activity, $activityData)) {
                $bwp->updateActivity($activityData["ACT_UID"], $activityData);
            } else {
                Util\Logger::log("Update Activity ({$activityData["ACT_UID"]}) Skipped - No changes required");
            }

            $diagram["activities"][$i] = $activityData;
            $whiteList[] = $activityData["ACT_UID"];
        }

        $activities = $bwp->getActivities();

        // looking for removed elements
        foreach ($activities as $activityData) {
            if (! in_array($activityData["ACT_UID"], $whiteList)) {
                $bwp->removeActivity($activityData["ACT_UID"]);
            }
        }

        /*
         * Diagram's Artifacts Handling
         */
        $whiteList = array();
        foreach ($diagram["artifacts"] as $i => $artifactData) {
            $artifactData = array_change_key_case($artifactData, CASE_UPPER);
            unset($artifactData["_EXTENDED"]);

            $artifact = $bwp->getArtifact($artifactData["ART_UID"]);

            if ($forceInsert || is_null($artifact)) {
                if ($generateUid) {
                    //Artifact
                    unset($artifactData["BOU_UID"]);

                    $uidOld = $artifactData["ART_UID"];
                    $artifactData["ART_UID"] = Util\Common::generateUID();

                    $result[] = array(
                        "object"  => "artifact",
                        "old_uid" => $uidOld,
                        "new_uid" => $artifactData["ART_UID"]
                    );
                }

                $bwp->addArtifact($artifactData);
            } elseif (! $bwp->isEquals($artifact, $artifactData)) {
                $bwp->updateArtifact($artifactData["ART_UID"], $artifactData);
            } else {
                Util\Logger::log("Update Artifact ({$artifactData["ART_UID"]}) Skipped - No changes required");
            }

            $diagram["artifacts"][$i] = $artifactData;
            $whiteList[] = $artifactData["ART_UID"];
        }

        $artifacts = $bwp->getArtifacts();
        // looking for removed elements
        foreach ($artifacts as $artifactData) {
            if (! in_array($artifactData["ART_UID"], $whiteList)) {
                $bwp->removeArtifact($artifactData["ART_UID"]);
            }
        }

        /*
         * Diagram's Gateways Handling
         */
        $arrayUidGatewayParallel = array();
        $flagGatewayParallel = false;

        $whiteList = array();

        foreach ($diagram["gateways"] as $i => $gatewayData) {
            $gatewayData = array_change_key_case($gatewayData, CASE_UPPER);
            unset($gatewayData["_EXTENDED"]);

            $flagAddOrUpdate = false;

            $gateway = $bwp->getGateway($gatewayData["GAT_UID"]);

            if ($forceInsert || is_null($gateway)) {
                if ($generateUid) {
                    //Gateway
                    unset($gatewayData["BOU_UID"]);

                    $uidOld = $gatewayData["GAT_UID"];
                    $gatewayData["GAT_UID"] = Util\Common::generateUID();

                    $result[] = array(
                        "object"  => "gateway",
                        "old_uid" => $uidOld,
                        "new_uid" => $gatewayData["GAT_UID"]
                    );
                }

                $bwp->addGateway($gatewayData);

                $flagAddOrUpdate = true;
            } elseif (! $bwp->isEquals($gateway, $gatewayData)) {
                $bwp->updateGateway($gatewayData["GAT_UID"], $gatewayData);

                $flagAddOrUpdate = true;
            } else {
                Util\Logger::log("Update Gateway ({$gatewayData["GAT_UID"]}) Skipped - No changes required");
            }

            if ($flagAddOrUpdate) {
                $arrayGatewayData = $bwp->getGateway($gatewayData["GAT_UID"]);

                if ($arrayGatewayData["GAT_TYPE"] == "PARALLEL") {
                    $arrayUidGatewayParallel[] = $gatewayData["GAT_UID"];
                    $flagGatewayParallel = true;
                }
            }

            $diagram["gateways"][$i] = $gatewayData;
            $whiteList[] = $gatewayData["GAT_UID"];
        }

        $gateways = $bwp->getGateways();

        // looking for removed elements
        foreach ($gateways as $gatewayData) {
            if (! in_array($gatewayData["GAT_UID"], $whiteList)) {
                $bwp->removeGateway($gatewayData["GAT_UID"]);
            }
        }

        /*
         * Diagram's Events Handling
         */
        $whiteList = array();
        foreach ($diagram["events"] as $i => $eventData) {
            $eventData = array_change_key_case($eventData, CASE_UPPER);
            unset($eventData["_EXTENDED"]);
            if (array_key_exists("EVN_CANCEL_ACTIVITY", $eventData)) {
                $eventData["EVN_CANCEL_ACTIVITY"] = $eventData["EVN_CANCEL_ACTIVITY"] ? 1 : 0;
            }
            if (array_key_exists("EVN_WAIT_FOR_COMPLETION", $eventData)) {
                $eventData["EVN_WAIT_FOR_COMPLETION"] = $eventData["EVN_WAIT_FOR_COMPLETION"] ? 1 : 0;
            }

            $event = $bwp->getEvent($eventData["EVN_UID"]);

            if ($forceInsert || is_null($event)) {
                if ($generateUid) {
                    //Event
                    unset($eventData["BOU_UID"]);

                    $uidOld = $eventData["EVN_UID"];
                    $eventData["EVN_UID"] = Util\Common::generateUID();

                    $result[] = array(
                        "object"  => "event",
                        "old_uid" => $uidOld,
                        "new_uid" => $eventData["EVN_UID"]
                    );
                }

                $bwp->addEvent($eventData);
            } elseif (! $bwp->isEquals($event, $eventData)) {
                $bwp->updateEvent($eventData["EVN_UID"], $eventData);
            } else {
                Util\Logger::log("Update Event ({$eventData["EVN_UID"]}) Skipped - No changes required");
            }

            $diagram["events"][$i] = $eventData;
            $whiteList[] = $eventData["EVN_UID"];
        }

        $events = $bwp->getEvents();

        // looking for removed elements
        foreach ($events as $eventData) {
            if (! in_array($eventData["EVN_UID"], $whiteList)) {
                // If it is not in the white list, then remove them
                $bwp->removeEvent($eventData["EVN_UID"]);
            }
        }

        /*
         * Diagram's Data Handling
         */
        $whiteList = array();
        foreach ($diagram["data"] as $i => $dataObjectData) {
            $dataObjectData = array_change_key_case($dataObjectData, CASE_UPPER);
            unset($dataObjectData["_EXTENDED"]);

            $dataObject = $bwp->getData($dataObjectData["DAT_UID"]);

            if ($forceInsert || is_null($dataObject)) {
                if ($generateUid) {
                    //Data
                    unset($dataObjectData["BOU_UID"]);

                    $uidOld = $dataObjectData["DAT_UID"];
                    $dataObjectData["DAT_UID"] = Util\Common::generateUID();

                    $result[] = array(
                        "object"  => "data",
                        "old_uid" => $uidOld,
                        "new_uid" => $dataObjectData["DAT_UID"]
                    );
                }

                $bwp->addData($dataObjectData);
            } elseif (! $bwp->isEquals($dataObject, $dataObjectData)) {
                $bwp->updateData($dataObjectData["DAT_UID"], $dataObjectData);
            } else {
                Util\Logger::log("Update Data ({$dataObjectData["DAT_UID"]}) Skipped - No changes required");
            }

            $diagram["data"][$i] = $dataObjectData;
            $whiteList[] = $dataObjectData["DAT_UID"];
        }

        $dataCollection = $bwp->getDataCollection();

        // looking for removed elements
        foreach ($dataCollection as $dataObjectData) {
            if (! in_array($dataObjectData["DAT_UID"], $whiteList)) {
                // If it is not in the white list, then remove them
                $bwp->removeData($dataObjectData["DAT_UID"]);
            }
        }

        ////
        /*
         * Diagram's Participant Handling
         */
        $whiteList = array();
        foreach ($diagram["participants"] as $i => $participantData) {
            $participantData = array_change_key_case($participantData, CASE_UPPER);
            unset($participantData["_EXTENDED"]);

            $dataObject = $bwp->getParticipant($participantData["PAR_UID"]);

            if ($forceInsert || is_null($dataObject)) {
                if ($generateUid) {
                    //Participant
                    unset($participantData["BOU_UID"]);

                    $uidOld = $participantData["PAR_UID"];
                    $participantData["PAR_UID"] = Util\Common::generateUID();

                    $result[] = array(
                        "object"  => "participant",
                        "old_uid" => $uidOld,
                        "new_uid" => $participantData["PAR_UID"]
                    );
                }

                $bwp->addParticipant($participantData);
            } elseif (! $bwp->isEquals($dataObject, $participantData)) {
                $bwp->updateParticipant($participantData["PAR_UID"], $participantData);
            } else {
                Util\Logger::log("Update Participant ({$participantData["PAR_UID"]}) Skipped - No changes required");
            }

            $diagram["participants"][$i] = $participantData;
            $whiteList[] = $participantData["PAR_UID"];
        }

        $dataCollection = $bwp->getParticipants();

        // looking for removed elements
        foreach ($dataCollection as $participantData) {
            if (! in_array($participantData["PAR_UID"], $whiteList)) {
                // If it is not in the white list, then remove them
                $bwp->removeParticipant($participantData["PAR_UID"]);
            }
        }

        /*
         * Diagram's Flows Handling
         */
        $whiteList = array();

        foreach ($diagram["flows"] as $i => $flowData) {
            $flowData = array_change_key_case($flowData, CASE_UPPER);

            // if it is a new flow record
            if ($forceInsert || ($generateUid && !\BpmnFlow::exists($flowData["FLO_UID"]))) {
                $oldFloUid = $flowData["FLO_UID"];
                $flowData["FLO_UID"] = Util\Common::generateUID();
                $result[] = array("object" => "flow", "new_uid" => $flowData["FLO_UID"], "old_uid" => $oldFloUid);

                $mappedUid = self::mapUid($flowData["FLO_ELEMENT_ORIGIN"], $result);
                if ($mappedUid !== false) {
                    $flowData["FLO_ELEMENT_ORIGIN"] = $mappedUid;
                }

                $mappedUid = self::mapUid($flowData["FLO_ELEMENT_DEST"], $result);
                if ($mappedUid !== false) {
                    $flowData["FLO_ELEMENT_DEST"] = $mappedUid;
                }
            }

            //Update UIDs
            foreach ($result as $value) {
                if ($flowData["FLO_ELEMENT_ORIGIN"] == $value["old_uid"]) {
                    $flowData["FLO_ELEMENT_ORIGIN"] = $value["new_uid"];
                }

                if ($flowData["FLO_ELEMENT_DEST"] == $value["old_uid"]) {
                    $flowData["FLO_ELEMENT_DEST"] = $value["new_uid"];
                }
            }

            //Update condition
            if ($flagGatewayParallel && $flowData["FLO_ELEMENT_ORIGIN_TYPE"] == "bpmnGateway" && in_array($flowData["FLO_ELEMENT_ORIGIN"], $arrayUidGatewayParallel)) {
                $flowData["FLO_CONDITION"] = "";
            }

            $diagram["flows"][$i] = $flowData;
            $whiteList[] = $flowData["FLO_UID"];
        }

        foreach ($diagram["flows"] as $flowData) {
            $flow = $bwp->getFlow($flowData["FLO_UID"]);
            if ($forceInsert || is_null($flow)) {
                $bwp->addFlow($flowData);
            } elseif (! $bwp->isEquals($flow, $flowData)) {
                $bwp->updateFlow($flowData["FLO_UID"], $flowData, $diagram["flows"]);
            } else {
                Util\Logger::log("Update Flow ({$flowData["FLO_UID"]}) Skipped - No changes required");
            }
        }

        $flows = $bwp->getFlows();

        // looking for removed elements
        foreach ($flows as $flowData) {
            if (! in_array($flowData["FLO_UID"], $whiteList)) {
                $bwp->removeFlow($flowData["FLO_UID"]);
            }
        }

        $bwp->mapBpmnFlowsToWorkflowRoutes();

        return $result;
    }

    protected static function mapUid($oldUid, $list)
    {
        foreach ($list as $item) {
            if ($item["old_uid"] == $oldUid) {
                return $item["new_uid"];
            }
        }

        return false;
    }

    public function setDisabled($value = true)
    {
        parent::setDisabled($value);
        $this->wp->setDisabled($value);
    }
}

