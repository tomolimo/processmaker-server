<?php
namespace ProcessMaker\Services\Api\Project;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Project\TimerEvent Api Controller
 *
 * @protected
 */
class TimerEvent extends Api
{
    private $timerEvent;

    /**
     * Constructor of the class
     *
     * return void
     */
    public function __construct()
    {
        try {
            $this->timerEvent = new \ProcessMaker\BusinessModel\TimerEvent();

            $this->timerEvent->setFormatFieldNameInUppercase(false);
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url GET /:prj_uid/timer-events
     *
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doGetTimerEvents($prj_uid)
    {
        try {
            $response = $this->timerEvent->getTimerEvents($prj_uid);

            return $response;
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url GET /:prj_uid/timer-event/:tmrevn_uid
     *
     * @param string $prj_uid    {@min 32}{@max 32}
     * @param string $tmrevn_uid {@min 32}{@max 32}
     */
    public function doGetTimerEvent($prj_uid, $tmrevn_uid)
    {
        try {
            $response = $this->timerEvent->getTimerEvent($tmrevn_uid);

            return $response;
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url GET /:prj_uid/timer-event/event/:evn_uid
     *
     * @param string $prj_uid {@min 32}{@max 32}
     * @param string $evn_uid {@min 32}{@max 32}
     */
    public function doGetTimerEventEvent($prj_uid, $evn_uid)
    {
        try {
            $response = $this->timerEvent->getTimerEventByEvent($prj_uid, $evn_uid);

            return $response;
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url POST /:prj_uid/timer-event
     *
     * @param string $prj_uid      {@min 32}{@max 32}
     * @param array  $request_data
     *
     * @status 201
     */
    public function doPostTimerEvent($prj_uid, array $request_data)
    {
        try {
            $arrayData = $this->timerEvent->create($prj_uid, $request_data);

            $response = $arrayData;

            return $response;
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url PUT /:prj_uid/timer-event/:tmrevn_uid
     *
     * @param string $prj_uid      {@min 32}{@max 32}
     * @param string $tmrevn_uid   {@min 32}{@max 32}
     * @param array  $request_data
     */
    public function doPutTimerEvent($prj_uid, $tmrevn_uid, array $request_data)
    {
        try {
            $arrayData = $this->timerEvent->update($tmrevn_uid, $request_data);
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url DELETE /:prj_uid/timer-event/:tmrevn_uid
     *
     * @param string $prj_uid    {@min 32}{@max 32}
     * @param string $tmrevn_uid {@min 32}{@max 32}
     */
    public function doDeleteTimerEvent($prj_uid, $tmrevn_uid)
    {
        try {
            $this->timerEvent->delete($tmrevn_uid);
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }
}

