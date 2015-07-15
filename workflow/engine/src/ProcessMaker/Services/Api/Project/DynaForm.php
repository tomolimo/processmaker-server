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
    
    /**
     * @url GET /:prj_uid/dynaform/:dyn_uid/download-language/:lang
     *
     * @param string $dyn_uid {@min 32}{@max 32}
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doGetDynaFormLanguage($dyn_uid, $prj_uid, $lang)
    {
        try {
            \G::LoadClass('pmDynaform');
            $pmDynaform = new \pmDynaform();
            return $pmDynaform->downloadLanguage($dyn_uid, $lang);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url POST /:prj_uid/dynaform/:dyn_uid/upload-language
     *
     * @param string $dyn_uid {@min 32}{@max 32}
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doPostDynaFormLanguage($dyn_uid, $prj_uid)
    {
        try {
            \G::LoadClass('pmDynaform');
            $pmDynaform = new \pmDynaform();
            $pmDynaform->uploadLanguage($dyn_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url POST /:prj_uid/dynaform/:dyn_uid/delete-language/:lang
     *
     * @param string $dyn_uid {@min 32}{@max 32}
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doDeleteDynaFormLanguage($dyn_uid, $prj_uid, $lang)
    {
        try {
            \G::LoadClass('pmDynaform');
            $pmDynaform = new \pmDynaform();
            $pmDynaform->deleteLanguage($dyn_uid, $lang);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url GET /:prj_uid/dynaform/:dyn_uid/list-language
     *
     * @param string $dyn_uid {@min 32}{@max 32}
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doGetListDynaFormLanguage($dyn_uid, $prj_uid)
    {
        try {
            \G::LoadClass('pmDynaform');
            $pmDynaform = new \pmDynaform();
            return $pmDynaform->listLanguage($dyn_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

}
