<?php
namespace ProcessMaker\Services\Api\Project;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Project\WebEntryEvent Api Controller
 *
 * @protected
 */
class WebEntryEvent extends Api
{
    private $webEntryEvent;

    /**
     * Constructor of the class
     *
     * return void
     */
    public function __construct()
    {
        try {
            $this->webEntryEvent = new \ProcessMaker\BusinessModel\WebEntryEvent();

            $this->webEntryEvent->setFormatFieldNameInUppercase(false);
            $this->webEntryEvent->setArrayFieldNameForException(array("processUid" => "prj_uid"));
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url GET /:prj_uid/web-entry-events
     *
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doGetWebEntryEvents($prj_uid)
    {
        try {
            $response = $this->webEntryEvent->getWebEntryEvents($prj_uid);

            return $response;
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url GET /:prj_uid/web-entry-event/:wee_uid
     *
     * @param string $prj_uid {@min 32}{@max 32}
     * @param string $wee_uid {@min 32}{@max 32}
     */
    public function doGetWebEntryEvent($prj_uid, $wee_uid)
    {
        try {
            $response = $this->webEntryEvent->getWebEntryEvent($wee_uid);

            return $response;
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url GET /:prj_uid/web-entry-event/event/:evn_uid
     *
     * @param string $prj_uid {@min 32}{@max 32}
     * @param string $evn_uid {@min 32}{@max 32}
     */
    public function doGetWebEntryEventEvent($prj_uid, $evn_uid)
    {
        try {
            $response = $this->webEntryEvent->getWebEntryEventByEvent($prj_uid, $evn_uid);

            return $response;
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url POST /:prj_uid/web-entry-event
     *
     * @param string $prj_uid      {@min 32}{@max 32}
     * @param array  $request_data
     *
     * @status 201
     */
    public function doPostWebEntryEvent($prj_uid, array $request_data)
    {
        try {
            $arrayData = $this->webEntryEvent->create($prj_uid, $this->getUserId(), $request_data);

            $response = $arrayData;

            return $response;
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url PUT /:prj_uid/web-entry-event/:wee_uid
     *
     * @param string $prj_uid      {@min 32}{@max 32}
     * @param string $wee_uid      {@min 32}{@max 32}
     * @param array  $request_data
     */
    public function doPutWebEntryEvent($prj_uid, $wee_uid, array $request_data)
    {
        try {
            $arrayData = $this->webEntryEvent->update($wee_uid, $this->getUserId(), $request_data);
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url DELETE /:prj_uid/web-entry-event/:wee_uid
     *
     * @param string $prj_uid {@min 32}{@max 32}
     * @param string $wee_uid {@min 32}{@max 32}
     */
    public function doDeleteWebEntryEvent($prj_uid, $wee_uid)
    {
        try {
            $this->webEntryEvent->delete($wee_uid);
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }
}

