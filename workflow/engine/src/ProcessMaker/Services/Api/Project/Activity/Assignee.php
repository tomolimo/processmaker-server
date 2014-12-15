<?php
namespace ProcessMaker\Services\Api\Project\Activity;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Project\Activity\Assignee Api Controller
 *
 * @protected
 */
class Assignee extends Api
{
    /**
     * @url GET /:prjUid/activity/:actUid/assignee
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $actUid {@min 32} {@max 32}
     * @param string $filter
     * @param int    $start
     * @param int    $limit
     * @param string $type
     *
     */
    public function doGetActivityAssignees($prjUid, $actUid, $filter = '', $start = null, $limit = null, $type = '')
    {
        $response = array();
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $arrayData = $task->getTaskAssignees($prjUid, $actUid, $filter, $start, $limit, $type);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url GET /:prjUid/activity/:actUid/available-assignee
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $actUid {@min 32} {@max 32}
     * @param string $filter
     * @param int    $start
     * @param int    $limit
     * @param string $type
     *
     */
    public function doGetActivityAvailableAssignee($prjUid, $actUid, $filter = '', $start = null, $limit = null, $type = '')
    {
        $response = array();
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $arrayData = $task->getTaskAvailableAssignee($prjUid, $actUid, $filter, $start, $limit, $type);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url GET /:prjUid/activity/:actUid/assignee/:aasUid
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $actUid {@min 32} {@max 32}
     * @param string $aasUid {@min 32} {@max 32}
     *
     */
    public function doGetActivityAssignee($prjUid, $actUid, $aasUid)
    {
        $response = array();
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $objectData = $task->getTaskAssignee($prjUid, $actUid, $aasUid);
            //Response
            $response = $objectData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url POST /:prjUid/activity/:actUid/assignee
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $actUid {@min 32} {@max 32}
     * @param string $aas_uid {@min 32} {@max 32}
     * @param string $aas_type {@choice user,group}
     *
     * @status 201
     */
    public function doPostActivityAssignee($prjUid, $actUid, $aas_uid, $aas_type)
    {
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $task->addTaskAssignee($prjUid, $actUid, $aas_uid, $aas_type);
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url DELETE /:prjUid/activity/:actUid/assignee/:aasUid
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $actUid {@min 32} {@max 32}
     * @param string $aasUid {@min 32} {@max 32}
     *
     */
    public function doDeleteActivityAssignee($prjUid, $actUid, $aasUid)
    {
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $task->removeTaskAssignee($prjUid, $actUid, $aasUid);
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url GET /:prjUid/activity/:actUid/adhoc-assignee
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $actUid {@min 32} {@max 32}
     * @param string $filter
     * @param int    $start
     * @param int    $limit
     * @param string $type
     *
     */
    public function doGetActivityAdhocAssignees($prjUid, $actUid, $filter = '', $start = null, $limit = null, $type = '')
    {
        $response = array();
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $arrayData = $task->getTaskAdhocAssignees($prjUid, $actUid, $filter, $start, $limit, $type);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url GET /:prjUid/activity/:actUid/adhoc-available-assignee
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $actUid {@min 32} {@max 32}
     * @param string $filter
     * @param int    $start
     * @param int    $limit
     * @param string $type
     *
     */
    public function doGetActivityAvailableAdhocAssignee($prjUid, $actUid, $filter = '', $start = null, $limit = null, $type = '')
    {
        $response = array();
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $arrayData = $task->getTaskAvailableAdhocAssignee($prjUid, $actUid, $filter, $start, $limit, $type);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url GET /:prjUid/activity/:actUid/adhoc-assignee/:aasUid
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $actUid {@min 32} {@max 32}
     * @param string $assUid {@min 32} {@max 32}
     *
     */
    public function doGetActivityAdhocAssignee($prjUid, $actUid, $aasUid)
    {
        $response = array();
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $objectData = $task->getTaskAdhocAssignee($prjUid, $actUid, $aasUid);
            //Response
            $response = $objectData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url POST /:prjUid/activity/:actUid/adhoc-assignee
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $actUid {@min 32} {@max 32}
     * @param string $ada_uid {@min 32} {@max 32}
     * @param string $ada_type {@choice user,group}
     *
     * @status 201
     */
    public function doPostActivityAdhocAssignee($prjUid, $actUid, $ada_uid, $ada_type)
    {
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $task->addTaskAdhocAssignee($prjUid, $actUid, $ada_uid, $ada_type);
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url DELETE /:prjUid/activity/:actUid/adhoc-assignee/:adaUid
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $actUid {@min 32} {@max 32}
     * @param string $adaUid {@min 32} {@max 32}
     *
     */
    public function doDeleteActivityAdhocAssignee($prjUid, $actUid, $adaUid)
    {
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $task->removeTaskAdhocAssignee($prjUid, $actUid, $adaUid);
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url GET /:prjUid/activity/:actUid/assignee/all
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $actUid {@min 32} {@max 32}
     * @param string $filter
     * @param int    $start
     * @param int    $limit
     * @param string $type
     *
     */
    public function doGetActivityAssigneesAll($prjUid, $actUid, $filter = '', $start = null, $limit = null, $type = '')
    {
        $response = array();
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $arrayData = $task->getTaskAssigneesAll($prjUid, $actUid, $filter, $start, $limit, $type);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url GET /:prjUid/activity/:actUid/adhoc-assignee/all
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $actUid {@min 32} {@max 32}
     * @param string $filter
     * @param int    $start
     * @param int    $limit
     * @param string $type
     *
     */
    public function doGetActivityAdhocAssigneesAll($prjUid, $actUid, $filter = '', $start = null, $limit = null, $type = '')
    {
        $response = array();
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $arrayData = $task->getTaskAdhocAssigneesAll($prjUid, $actUid, $filter, $start, $limit, $type);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }
}

