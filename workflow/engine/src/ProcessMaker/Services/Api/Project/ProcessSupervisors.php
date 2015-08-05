<?php
namespace ProcessMaker\Services\Api\Project;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Project\ProcessSupervisors Api Controller
 *
 * @protected
 */
class ProcessSupervisors extends Api
{
    /**
     * @param string $prjUid {@min 32} {@max 32}
     *
     * @url GET /:prjUid/process-supervisors
     */
    public function doGetProcessSupervisors($prjUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $arrayData = $supervisor->getProcessSupervisors($prjUid);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $puUid {@min 32} {@max 32}
     *
     * @url GET /:prjUid/process-supervisor/:puUid
     */
    public function doGetProcessSupervisor($prjUid, $puUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $objectData = $supervisor->getProcessSupervisor($prjUid, $puUid);
            //Response
            $response = $objectData;
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $obj_type
     *
     * @url GET /:prjUid/available-process-supervisors
     */
    public function doGetAvailableSupervisors($prjUid, $obj_type = '')
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $arrayData = $supervisor->getAvailableProcessSupervisors($obj_type, $prjUid);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }


    /**
     * @param string $prjUid {@min 32} {@max 32}
     *
     * @url GET /:prjUid/process-supervisor/dynaforms
     */
    public function doGetProcessSupervisorDynaforms($prjUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $arrayData = $supervisor->getProcessSupervisorDynaforms($prjUid);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @param string $prjUid {@min 32} {@max 32}
     *
     * @url GET /:prjUid/process-supervisor/assignmentsteps
     */
    public function doGetProcessSupervisorAssignmentsteps($prjUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $arrayData = $supervisor->getProcessSupervisorDynaformsInputsDocuments($prjUid);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $pudUid {@min 32} {@max 32}
     *
     * @url GET /:prjUid/process-supervisor/dynaform/:pudUid
     */
    public function doGetProcessSupervisorDynaform($prjUid, $pudUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $objectData = $supervisor->getProcessSupervisorDynaform($prjUid, $pudUid);
            //Response
            $response = $objectData;
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @param string $prjUid {@min 32} {@max 32}
     *
     * @url GET /:prjUid/process-supervisor/available-dynaforms
     */
    public function doGetAvailableProcessSupervisorDynaform($prjUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $arrayData = $supervisor->getAvailableProcessSupervisorDynaform($prjUid);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @param string $prjUid {@min 32} {@max 32}
     *
     * @url GET /:prjUid/process-supervisor/available-assignmentsteps
     */
    public function doGetAvailableProcessSupervisorAssignmentstep($prjUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $arrayData = $supervisor->getAvailableProcessSupervisorDynaformInputDocument($prjUid);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @param string $prjUid {@min 32} {@max 32}
     *
     * @url GET /:prjUid/process-supervisor/input-documents
     */
    public function doGetProcessSupervisorInputDocuments($prjUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $arrayData = $supervisor->getProcessSupervisorInputDocuments($prjUid);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $puiUid {@min 32} {@max 32}
     *
     * @url GET /:prjUid/process-supervisor/input-document/:puiUid
     */
    public function doGetProcessSupervisorInputDocument($prjUid, $puiUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $objectData = $supervisor->getProcessSupervisorInputDocument($prjUid, $puiUid);
            //Response
            $response = $objectData;
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @param string $prjUid {@min 32} {@max 32}
     *
     * @url GET /:prjUid/process-supervisor/available-input-documents
     */
    public function doGetAvailableProcessSupervisorInputDocument($prjUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $arrayData = $supervisor->getAvailableProcessSupervisorInputDocument($prjUid);
            //Response
            $response = $arrayData;
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url POST /:prjUid/process-supervisor
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $usr_uid {@min 32} {@max 32}
     * @param string $pu_type {@choice SUPERVISOR,GROUP_SUPERVISOR}
     *
     * @status 201
     */
    public function doPostProcessSupervisor($prjUid, $usr_uid, $pu_type)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $objectData = $supervisor->addProcessSupervisor($prjUid, $usr_uid, $pu_type);
            //Response
            $response = $objectData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url POST /:prjUid/process-supervisor/dynaform
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $dyn_uid {@min 32} {@max 32}
     * @param int $pud_position
     *
     * @status 201
     */
    public function doPostProcessSupervisorDynaform($prjUid, $dyn_uid, $pud_position = null)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $objectData = $supervisor->addProcessSupervisorDynaform($prjUid, $dyn_uid, $pud_position);
            //Response
            $response = $objectData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url POST /:prjUid/process-supervisor/input-document
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $inp_doc_uid {@min 32} {@max 32}
     * @param int $pui_position
     *
     * @status 201
     */
    public function doPostProcessSupervisorInputDocument($prjUid, $inp_doc_uid, $pui_position = null)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $objectData = $supervisor->addProcessSupervisorInputDocument($prjUid, $inp_doc_uid, $pui_position);
            //Response
            $response = $objectData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url DELETE /:prjUid/process-supervisor/:puUid
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $puUid {@min 32} {@max 32}
     *
     */
    public function doDeleteSupervisor($prjUid, $puUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $supervisor->removeProcessSupervisor($prjUid, $puUid);
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url DELETE /:prjUid/process-supervisor/dynaform/:pudUid
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $pudUid {@min 32} {@max 32}
     *
     */
    public function doDeleteDynaformSupervisor($prjUid, $pudUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $supervisor->removeDynaformSupervisor($prjUid, $pudUid);
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url DELETE /:prjUid/process-supervisor/input-document/:puiUid
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $puiUid {@min 32} {@max 32}
     *
     */
    public function doDeleteInputDocumentSupervisor($prjUid, $puiUid)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $supervisor->removeInputDocumentSupervisor($prjUid, $puiUid);
        } catch (\Exception $e) {
            //response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url PUT /:prjUid/process-supervisor/dynaform/:pud_uid
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $pud_uid {@min 32} {@max 32}
     * @param int $pud_position
     *
     * @status 201
     */
    public function doPutProcessSupervisorDynaform($prjUid, $pud_uid, $pud_position = null)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $objectData = $supervisor->updateProcessSupervisorDynaform($prjUid, $pud_uid, $pud_position);
            //Response
            $response = $objectData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url PUT /:prjUid/process-supervisor/input-document/:pui_uid
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $pui_uid {@min 32} {@max 32}
     * @param int $pui_position
     *
     * @status 201
     */
    public function doPutProcessSupervisorInputDocument($prjUid, $pui_uid, $pui_position = null)
    {
        try {
            $supervisor = new \ProcessMaker\BusinessModel\ProcessSupervisor();
            $objectData = $supervisor->updateProcessSupervisorInputDocument($prjUid, $pui_uid, $pui_position);
            //Response
            $response = $objectData;
        } catch (\Exception $e) {
            //Response
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }
}

