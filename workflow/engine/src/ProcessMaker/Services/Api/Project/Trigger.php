<?php
namespace ProcessMaker\Services\Api\Project;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Project\Trigger Api Controller
 *
 * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
 * @copyright Colosa - Bolivia
 *
 * @protected
 */
class Trigger extends Api
{
    /**
     * @param string $projectUid {@min 1} {@max 32}
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     * @return array
     *
     * @url GET /:projectUid/triggers
     */
    public function doGetTriggers($projectUid)
    {
        try {
            $trigger = new \ProcessMaker\BusinessModel\Trigger();
            $response = $trigger->getTriggers($projectUid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $projectUid {@min 1} {@max 32}
     * @param string $triggerUid {@min 1} {@max 32}
     * @return array
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url GET /:projectUid/trigger/:triggerUid
     */
    public function doGetTrigger($projectUid, $triggerUid)
    {
        try {
            $trigger = new \ProcessMaker\BusinessModel\Trigger();
            $response = $trigger->getDataTrigger($triggerUid);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $projectUid {@min 1} {@max 32}
     * @param array $request_data
     * @param string $tri_title {@from body} {@min 1}
     * @param string $tri_description {@from body}
     * @param string $tri_type {@from body}
     * @param string $tri_webbot {@from body}
     * @param string $tri_param {@from body}
     * @return array
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url POST /:projectUid/trigger
     * @status 201
     */
    public function doPostTrigger($projectUid, $request_data, $tri_title, $tri_description = '', $tri_type = 'SCRIPT', $tri_webbot = '', $tri_param = '')
    {
        try {
            $trigger = new \ProcessMaker\BusinessModel\Trigger();
            $response = $trigger->saveTrigger($projectUid, $request_data, true);
            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $projectUid {@min 1} {@max 32}
     * @param string $triggerUid {@min 1} {@max 32}
     * @param array $request_data
     * @param string $tri_title {@from body}
     * @param string $tri_description {@from body}
     * @param string $tri_type {@from body}
     * @param string $tri_webbot {@from body}
     * @param string $tri_param {@from body}
     * @return void
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url PUT /:projectUid/trigger/:triggerUid
     */
    public function doPutTrigger($projectUid, $triggerUid, $request_data, $tri_title = '', $tri_description = '', $tri_type = 'SCRIPT', $tri_webbot = '', $tri_param = '')
    {
        try {
            $request_data['tri_uid'] = $triggerUid;
            $trigger = new \ProcessMaker\BusinessModel\Trigger();
            $trigger->saveTrigger($projectUid, $request_data, false, $triggerUid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @param string $projectUid {@min 1} {@max 32}
     * @param string $triggerUid {@min 1} {@max 32}
     * @return void
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @url DELETE /:projectUid/trigger/:triggerUid
     */
    public function doDeleteTrigger($projectUid, $triggerUid)
    {
        try {
            $trigger = new \ProcessMaker\BusinessModel\Trigger();
            $response = $trigger->deleteTrigger($triggerUid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
}

