<?php
namespace ProcessMaker\Services\Api\Project;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Project\DynaForm Api Controller
 *
 * @protected
 */
class DynaForm extends Api
{
    /**
     * @url GET /:prj_uid/dynaform/:dyn_uid
     *
     * @param string $dyn_uid {@min 32}{@max 32}
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doGetDynaForm($dyn_uid, $prj_uid)
    {
        try {
            $dynaForm = new \ProcessMaker\BusinessModel\DynaForm();
            $dynaForm->setFormatFieldNameInUppercase(false);

            $response = $dynaForm->getDynaForm($dyn_uid);

            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url POST /:prj_uid/dynaform
     *
     * @param string $prj_uid      {@min 32}{@max 32}
     * @param array  $request_data
     *
     * @status 201
     */
    public function doPostDynaForm($prj_uid, $request_data)
    {
        try {
            $dynaForm = new \ProcessMaker\BusinessModel\DynaForm();
            $dynaForm->setFormatFieldNameInUppercase(false);
            $dynaForm->setArrayFieldNameForException(array("processUid" => "prj_uid"));

            $arrayData = $dynaForm->executeCreate($prj_uid, $request_data);

            $response = $arrayData;

            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url PUT /:prj_uid/dynaform/:dyn_uid
     *
     * @param string $dyn_uid      {@min 32}{@max 32}
     * @param string $prj_uid      {@min 32}{@max 32}
     * @param array  $request_data
     */
    public function doPutDynaForm($dyn_uid, $prj_uid, $request_data)
    {
        try {
            $dynaForm = new \ProcessMaker\BusinessModel\DynaForm();
            $dynaForm->setFormatFieldNameInUppercase(false);

            $arrayData = $dynaForm->update($dyn_uid, $request_data);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url DELETE /:prj_uid/dynaform/:dyn_uid
     *
     * @param string $dyn_uid {@min 32}{@max 32}
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doDeleteDynaForm($dyn_uid, $prj_uid)
    {
        try {
            $dynaForm = new \ProcessMaker\BusinessModel\DynaForm();
            $dynaForm->setFormatFieldNameInUppercase(false);

            $dynaForm->delete($dyn_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url GET /:prj_uid/dynaform/:dyn_uid/fields
     *
     * @param string $dyn_uid {@min 32}{@max 32}
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doGetDynaFormFields($dyn_uid, $prj_uid)
    {
        try {
            $dynaForm = new \ProcessMaker\BusinessModel\DynaForm();
            $dynaForm->setFormatFieldNameInUppercase(false);

            $response = $dynaForm->getDynaFormFields($prj_uid, $dyn_uid);

            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }
}

