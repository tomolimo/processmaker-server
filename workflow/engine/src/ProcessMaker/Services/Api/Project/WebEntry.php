<?php
namespace ProcessMaker\Services\Api\Project;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Project\WebEntry Api Controller
 *
 * @protected
 */
class WebEntry extends Api
{
    private $webEntry;

    /**
     * Constructor of the class
     *
     * return void
     */
    public function __construct()
    {
        try {
            $this->webEntry = new \ProcessMaker\BusinessModel\WebEntry();

            $this->webEntry->setFormatFieldNameInUppercase(false);
            $this->webEntry->setArrayFieldNameForException(array("processUid" => "prj_uid"));
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url GET /:prj_uid/web-entries
     *
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doGetWebEntries($prj_uid)
    {
        try {
            $response = $this->webEntry->getWebEntries($prj_uid);

            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url GET /:prj_uid/web-entry/:we_uid
     *
     * @param string $prj_uid {@min 32}{@max 32}
     * @param string $we_uid  {@min 32}{@max 32}
     */
    public function doGetWebEntry($prj_uid, $we_uid)
    {
        try {
            $response = $this->webEntry->getWebEntry($we_uid);

            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url POST /:prj_uid/web-entry
     *
     * @param string $prj_uid      {@min 32}{@max 32}
     * @param array  $request_data
     *
     * @status 201
     */
    public function doPostWebEntry($prj_uid, array $request_data)
    {
        try {
            $arrayData = $this->webEntry->create($prj_uid, $this->getUserId(), $request_data);

            $response = $arrayData;

            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url PUT /:prj_uid/web-entry/:we_uid
     *
     * @param string $prj_uid      {@min 32}{@max 32}
     * @param string $we_uid       {@min 32}{@max 32}
     * @param array  $request_data
     */
    public function doPutWebEntry($prj_uid, $we_uid, array $request_data)
    {
        try {
            $arrayData = $this->webEntry->update($we_uid, $this->getUserId(), $request_data);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url DELETE /:prj_uid/web-entry/:we_uid
     *
     * @param string $prj_uid {@min 32}{@max 32}
     * @param string $we_uid  {@min 32}{@max 32}
     */
    public function doDeleteWebEntry($prj_uid, $we_uid)
    {
        try {
            $this->webEntry->delete($we_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
}

