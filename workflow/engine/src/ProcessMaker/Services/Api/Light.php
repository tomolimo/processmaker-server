<?php

namespace ProcessMaker\Services\Api;

use \G;
use \ProcessMaker\Project\Adapter;
use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 *
 * Process Api Controller
 *
 * @protected
 */
class Light extends Api
{
    /**
     * Get list counters
     * @return array
     *
     * @copyright Colosa - Bolivia
     *
     * @url GET /counters
     */
    public function countersCases ()
    {
        try {
            $userId   = $this->getUserId();
            $lists    = new \ProcessMaker\BusinessModel\Lists();
            $response = $lists->getCounters($userId);
            $result   = $this->parserCountersCases($response);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $result;
    }

    public function parserCountersCases ($data)
    {
        $structure = array(
            "CASES_INBOX" => "toDo",
            "CASES_DRAFT" => "draft",
            "CASES_CANCELLED" => "cancelled",
            "CASES_SENT" => "participated",
            "CASES_PAUSED" => "paused",
            "CASES_COMPLETED" => "completed",
            "CASES_SELFSERVICE" => "unassigned",
        );
        $response = array();
        foreach ($data as $counterList) {
            if(isset($structure[$counterList['item']])){
                $name = $structure[$counterList['item']];
                $response[$name] = $counterList['count'];
            }
        }
        return $response;
    }
    /**
     * Get list process start
     * @return array
     *
     * @copyright Colosa - Bolivia
     *
     * @url GET /start-case
     */
    public function getProcessListStartCase ()
    {
        try {
            $oMobile   = new \ProcessMaker\BusinessModel\Light();
            $startCase = $oMobile->getProcessListStartCase($this->getUserId());
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $startCase;
    }

    /**
     * Get list Case To Do
     *
     * @copyright Colosa - Bolivia
     *
     * @url GET /todo
     */
    public function doGetCasesListToDo(
        $start = 0,
        $limit = 10,
        $sort = 'APP_CACHE_VIEW.APP_NUMBER',
        $dir = 'DESC',
        $cat_uid = '',
        $pro_uid = '',
        $search = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();
            $dataList['action'] = 'todo';
            $dataList['paged']  = true;
            $dataList['start'] = $start;
            $dataList['limit'] = $limit;
            $dataList['sort']  = $sort;
            $dataList['dir']   = $dir;
            $dataList['category'] = $cat_uid;
            $dataList['process']  = $pro_uid;
            $dataList['search']   = $search;

            $oCases   = new \ProcessMaker\BusinessModel\Cases();
            $response = $oCases->getList($dataList);
            $result   = $this->parserDataTodo($response['data']);
            return $result;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    public function parserDataTodo ($data)
    {
        $structure = array(
            //'app_uid' => 'mongoId',
            'app_uid'           => 'caseId',
            'app_title'         => 'caseTitle',
            'app_number'        => 'caseNumber',
            'app_update_date'   => 'date',
            'del_task_due_date' => 'dueDate',
            //'' => 'status'
            'user' => array(
                'usrcr_usr_uid'       => 'userId',
                'usrcr_usr_firstname' => 'firstName',
                'usrcr_usr_lastname'  => 'lastName',
                'usrcr_usr_username'  => 'fullName',
            ),
            'prevUser' => array(
                'previous_usr_uid'       => 'userId',
                'previous_usr_firstname' => 'firstName',
                'previous_usr_lastname'  => 'lastName',
                'previous_usr_username'  => 'fullName',
            ),
            'process' => array(
                'pro_uid'       => 'processId',
                'app_pro_title' => 'name'
            ),
            'task' => array(
                'tas_uid'       => 'taskId',
                'app_tas_title' => 'name'
            ),
            'inp_doc_uid' => 'documentUid' //Esta opcion es temporal
        );

        $response = $this->replaceFields($data, $structure);
        return $response;
    }

    /**
     * Get list Cases Participated
     *
     * @copyright Colosa - Bolivia
     *
     * @url GET /participated
     */
    public function doGetCasesListParticipated(
        $start = 0,
        $limit = 10,
        $sort = 'APP_CACHE_VIEW.APP_NUMBER',
        $dir = 'DESC',
        $cat_uid = '',
        $pro_uid = '',
        $search = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();
            $dataList['action'] = 'sent';
            $dataList['paged']  = true;
            $dataList['start'] = $start;
            $dataList['limit'] = $limit;
            $dataList['sort']  = $sort;
            $dataList['dir']   = $dir;
            $dataList['category'] = $cat_uid;
            $dataList['process']  = $pro_uid;
            $dataList['search']   = $search;
            $oCases = new \ProcessMaker\BusinessModel\Cases();
            $response = $oCases->getList($dataList);
            $result = $this->parserDataParticipated($response['data']);
            return $result;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    public function parserDataParticipated ($data)
    {
        $structure = array(
            //'app_uid' => 'mongoId',
            'app_uid'           => 'caseId',
            'app_title'         => 'caseTitle',
            'app_number'        => 'caseNumber',
            'app_update_date'   => 'date',
            'del_task_due_date' => 'dueDate',
            'currentUser' => array(
                'usrcr_usr_uid'       => 'userId',
                'usrcr_usr_firstname' => 'firstName',
                'usrcr_usr_lastname'  => 'lastName',
                'usrcr_usr_username'  => 'fullName',
            ),
            'prevUser' => array(
                'previous_usr_uid'       => 'userId',
                'previous_usr_firstname' => 'firstName',
                'previous_usr_lastname'  => 'lastName',
                'previous_usr_username'  => 'fullName',
            ),
            'process' => array(
                'pro_uid'       => 'processId',
                'app_pro_title' => 'name'
            ),
            'task' => array(
                'tas_uid'       => 'taskId',
                'app_tas_title' => 'name'
            )
        );

        $response = $this->replaceFields($data, $structure);
        return $response;
    }

    /**
     * Get list Cases Paused
     *
     * @copyright Colosa - Bolivia
     *
     * @url GET /paused
     */
    public function doGetCasesListPaused(
        $start = 0,
        $limit = 10,
        $sort = 'APP_CACHE_VIEW.APP_NUMBER',
        $dir = 'DESC',
        $cat_uid = '',
        $pro_uid = '',
        $search = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();
            $dataList['action'] = 'paused';
            $dataList['paged']  = true;

            $dataList['start']    = $start;
            $dataList['limit']    = $limit;
            $dataList['sort']     = $sort;
            $dataList['dir']      = $dir;
            $dataList['category'] = $cat_uid;
            $dataList['process']  = $pro_uid;
            $dataList['search']   = $search;
            $oCases = new \ProcessMaker\BusinessModel\Cases();
            $response = $oCases->getList($dataList);
            $result = $this->parserDataParticipated($response['data']);
            return $result;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    public function parserDataPaused ($data)
    {
        $structure = array(
            //'app_uid' => 'mongoId',
            'app_uid'           => 'caseId',
            'app_title'         => 'caseTitle',
            'app_number'        => 'caseNumber',
            'app_update_date'   => 'date',
            'del_task_due_date' => 'dueDate',
            'currentUser' => array(
                'usrcr_usr_uid'       => 'userId',
                'usrcr_usr_firstname' => 'firstName',
                'usrcr_usr_lastname'  => 'lastName',
                'usrcr_usr_username'  => 'fullName',
            ),
            'prevUser' => array(
                'previous_usr_uid'       => 'userId',
                'previous_usr_firstname' => 'firstName',
                'previous_usr_lastname'  => 'lastName',
                'previous_usr_username'  => 'fullName',
            ),
            'process' => array(
                'pro_uid'       => 'processId',
                'app_pro_title' => 'name'
            ),
            'task' => array(
                'tas_uid'       => 'taskId',
                'app_tas_title' => 'name'
            )
        );

        $response = $this->replaceFields($data, $structure);
        return $response;
    }

    /**
     * Get list Cases Unassigned
     *
     * @copyright Colosa - Bolivia
     *
     * @url GET /unassigned
     */
    public function doGetCasesListUnassigned(
        $start = 0,
        $limit = 0,
        $sort = 'APP_CACHE_VIEW.APP_NUMBER',
        $dir = 'DESC',
        $cat_uid = '',
        $pro_uid = '',
        $search = ''
    ) {
        try {
            $dataList['userId'] = $this->getUserId();
            $dataList['action'] = 'unassigned';
            $dataList['paged']  = false;

            $dataList['start']    = $start;
            $dataList['limit']    = $limit;
            $dataList['sort']     = $sort;
            $dataList['dir']      = $dir;
            $dataList['category'] = $cat_uid;
            $dataList['process']  = $pro_uid;
            $dataList['search']   = $search;
            $oCases   = new \ProcessMaker\BusinessModel\Cases();
            $response = $oCases->getList($dataList);
            $result   = $this->parserDataUnassigned($response);
            return $result;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    public function parserDataUnassigned ($data)
    {
        $structure = array(
            //'app_uid' => 'mongoId',
            'app_uid'           => 'caseId',
            'app_title'         => 'caseTitle',
            'app_number'        => 'caseNumber',
            'app_update_date'   => 'date',
            'del_task_due_date' => 'dueDate',
            'currentUser' => array(
                'usrcr_usr_uid'       => 'userId',
                'usrcr_usr_firstname' => 'firstName',
                'usrcr_usr_lastname'  => 'lastName',
                'usrcr_usr_username'  => 'fullName',
            ),
            'prevUser' => array(
                'previous_usr_uid'       => 'userId',
                'previous_usr_firstname' => 'firstName',
                'previous_usr_lastname'  => 'lastName',
                'previous_usr_username'  => 'fullName',
            ),
            'process' => array(
                'pro_uid'       => 'processId',
                'app_pro_title' => 'name'
            ),
            'task' => array(
                'tas_uid'       => 'taskId',
                'app_tas_title' => 'name'
            )
        );

        $response = $this->replaceFields($data, $structure);
        return $response;
    }

    public function replaceFields ($data, $structure)
    {
        $response = array();
        foreach ($data as $field => $d) {
            if (is_array($d)) {
                $newData = array();
                foreach ($d as $field => $value) {
                    if (array_key_exists($field, $structure)) {
                        $newName           = $structure[$field];
                        $newData[$newName] = is_null($value) ? "":$value;
                    } else {
                        foreach ($structure as $name => $str) {
                            if (is_array($str) && array_key_exists($field, $str)) {
                                $newName                  = $str[$field];
                                $newData[$name][$newName] = is_null($value) ? "":$value;
                            }
                        }
                    }
                }
                if (count($newData) > 0)
                    $response[] = $newData;
            } else {
                if (array_key_exists($field, $structure)) {
                    $newName           = $structure[$field];
                    $response[$newName] = is_null($d) ? "":$d;
                } else {
                    foreach ($structure as $name => $str) {
                        if (is_array($str) && array_key_exists($field, $str)) {
                            $newName                  = $str[$field];
                            $response[$name][$newName] = is_null($d) ? "":$d;
                        }
                    }
                }
            }

        }
        return $response;
    }

    /**
     * Get list History case
     *
     * @copyright Colosa - Bolivia
     *
     * @url GET /history/:app_uid
     *
     * @param string $app_uid {@min 32}{@max 32}
     */
    public function doGetCasesListHistory($app_uid)
    {
        try {
            $oMobile = new \ProcessMaker\BusinessModel\Light();
            $response         = $oMobile->getCasesListHistory($app_uid);
            $response['flow'] = $this->parserDataHistory($response['flow']);
            $r             = new \stdclass();
            $r->data       = $response;
            $r->totalCount = count($response['flow']);
            return $r;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    public function parserDataHistory ($data)
    {
        $structure = array(
            //'' => 'caseId',
            //'' => 'caseTitle',
            //'' => 'processName',
            //'' => 'ownerFullName',
            //'flow' => array(
                'TAS_TITLE'         => 'taskName',
                //'' => 'userId',
                'USR_NAME'          => 'userFullName',
                'APP_TYPE'          => 'flowStatus', // is null default Router in FE
                'DEL_DELEGATE_DATE' => 'dueDate',
            //)
        );

        $response = $this->replaceFields($data, $structure);
        return $response;
    }

    /**
     *
     * @url GET /project/:prj_uid/dynaforms
     *
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doGetDynaForms($prj_uid)
    {
        try {
            $process = new \ProcessMaker\BusinessModel\Process();
            $process->setFormatFieldNameInUppercase(false);
            $process->setArrayFieldNameForException(array("processUid" => "prj_uid"));

            $response = $process->getDynaForms($prj_uid);
            $result   = $this->parserDataDynaForm($response);
            foreach ($result as $k => $form) {
                $result[$k]['formContent'] = (isset($form['formContent']) && $form['formContent'] != null)?json_decode($form['formContent']):"";
                $result[$k]['index']       = $k;
            }
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $result;
    }

    /**
     * @url GET /project/:prj_uid/activity/:act_uid/steps
     *
     * @param string $act_uid {@min 32}{@max 32}
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doGetActivitySteps($act_uid, $prj_uid)
    {
        try {
            $task = new \ProcessMaker\BusinessModel\Task();
            $task->setFormatFieldNameInUppercase(false);
            $task->setArrayParamException(array("taskUid" => "act_uid", "stepUid" => "step_uid"));

            $activitySteps = $task->getSteps($act_uid);

            $dynaForm = new \ProcessMaker\BusinessModel\DynaForm();
            $dynaForm->setFormatFieldNameInUppercase(false);
            $oMobile = new \ProcessMaker\BusinessModel\Light();
            $step = new \ProcessMaker\Services\Api\Project\Activity\Step();
            $response = array();
            for ($i = 0; $i < count($activitySteps); $i++) {
                if ($activitySteps[$i]['step_type_obj'] == "DYNAFORM") {
                    $dataForm = $dynaForm->getDynaForm($activitySteps[$i]['step_uid_obj']);
                    $result   = $this->parserDataDynaForm($dataForm);
                    $result['formContent'] = (isset($result['formContent']) && $result['formContent'] != null)?json_decode($result['formContent']):"";
                    $result['index']       = $i;
                    $result['stepId']      = $activitySteps[$i]["step_uid"];
                    $trigger = $oMobile->statusTriggers($step->doGetActivityStepTriggers($activitySteps[$i]["step_uid"], $act_uid, $prj_uid));
                    $result["triggers"]    = $trigger;
                    $response[] = $result;
                }
            }
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $response;
    }

    /**
     * Execute Trigger case
     *
     * @param string $prj_uid  {@min 1}{@max 32}
     * @param string $act_uid  {@min 1}{@max 32}
     * @param string $cas_uid  {@min 1}{@max 32}
     * @param string $step_uid {@min 32}{@max 32}
     * @param string $type     {@choice before,after}
     *
     * @copyright Colosa - Bolivia
     *
     * @url POST /process/:prj_uid/task/:act_uid/case/:cas_uid/step/:step_uid/execute-trigger/:type
     */
    public function doPutExecuteTriggerCase($prj_uid, $act_uid, $cas_uid, $step_uid, $type)
    {
        try {
            $userUid = $this->getUserId();
            $step = new \ProcessMaker\Services\Api\Project\Activity\Step();
            $triggers= $step->doGetActivityStepTriggers($step_uid, $act_uid, $prj_uid);

            $step = new \ProcessMaker\BusinessModel\Step();
            $step->setFormatFieldNameInUppercase(false);
            $step->setArrayParamException(array("stepUid" => "step_uid", "taskUid" => "act_uid", "processUid" => "prj_uid"));

            $cases = new \ProcessMaker\BusinessModel\Cases();
            foreach($triggers as $trigger){
                if (strtolower($trigger['st_type']) == $type) {
                    $cases->putExecuteTriggerCase($cas_uid, $trigger['tri_uid'], $userUid);
                }
            }
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url GET /project/dynaform/:dyn_uid
     *
     * @param string $dyn_uid {@min 32}{@max 32}
     */
    public function doGetDynaForm($dyn_uid)
    {
        try {
            $dynaForm = new \ProcessMaker\BusinessModel\DynaForm();
            $dynaForm->setFormatFieldNameInUppercase(false);

            $response = $dynaForm->getDynaForm($dyn_uid);
            $result   = $this->parserDataDynaForm($response);
            $result['formContent'] = (isset($result['formContent']) && $result['formContent'] != null)?json_decode($result['formContent']):"";
            return $result;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    /**
     * @url POST /project/dynaforms
     *
     */
    public function doGetDynaFormsId($request_data)
    {
        try {
            $dynaForm = new \ProcessMaker\BusinessModel\DynaForm();
            $dynaForm->setFormatFieldNameInUppercase(false);
            $return = array();
            foreach ($request_data['formId'] as $dyn_uid) {
                $response = $dynaForm->getDynaForm($dyn_uid);
                $result   = $this->parserDataDynaForm($response);
                $result['formContent'] = (isset($result['formContent']) && $result['formContent'] != null)?json_decode($result['formContent']):"";
                $return[] = $result;
            }
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $return;
    }

    public function parserDataDynaForm ($data)
    {
        $structure = array(
            'dyn_uid'         => 'formId',
            'dyn_title'       => 'formTitle',
            'dyn_description' => 'formDescription',
            //'dyn_type'        => 'formType',
            'dyn_content'     => 'formContent'
        );

        $response = $this->replaceFields($data, $structure);
        return $response;
    }

    /**
     * @url POST /process/:pro_uid/task/:task_uid/start-case
     *
     * @param string $pro_uid {@min 32}{@max 32}
     * @param string $task_uid {@min 32}{@max 32}
     */
    public function postStartCase($pro_uid, $task_uid)
    {
        try {
            $oMobile = new \ProcessMaker\BusinessModel\Light();
            $result  = $oMobile->startCase($this->getUserId(), $pro_uid, $task_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $result;
    }

    /**
     * Route Case
     * @url PUT /cases/:app_uid/route-case
     *
     * @param string $app_uid {@min 32}{@max 32}
     * @param string $del_index {@from body}
     */
    public function doPutRouteCase($app_uid, $del_index = null)
    {
        try {
            $oMobile  = new \ProcessMaker\BusinessModel\Light();
            $response = $oMobile->updateRouteCase($app_uid, $this->getUserId(), $del_index);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $response;
    }

    /**
     * @url GET /user/data
     */
    public function doGetUserData()
    {
        try {
            $userUid  = $this->getUserId();
            $oMobile  = new \ProcessMaker\BusinessModel\Light();
            $response = $oMobile->getUserData($userUid);
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url POST /users/data
     */
    public function doGetUsersData($request_data)
    {
        try {
            $response = array();
            $oMobile  = new \ProcessMaker\BusinessModel\Light();
            foreach ($request_data['user']['ids'] as $userUid) {
                $response[] = $oMobile->getUserData($userUid);
            }
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url POST /case/:app_uid/upload/location
     *
     * @param string $app_uid      { @min 32}{@max 32}
     * @param float $latitude      {@min -90}{@max 90}
     * @param float $longitude     {@min -180}{@max 180}
     */
    public function postInputDocumentLocation($app_uid, $latitude, $longitude)
    {
        try {
            $userUid       = $this->getUserId();
            $oMobile       = new \ProcessMaker\BusinessModel\Light();

            $url           = "http://maps.googleapis.com/maps/api/staticmap?center=".$latitude.','.$longitude."&format=jpg&size=600x600&zoom=15&markers=color:blue%7Clabel:S%7C".$latitude.','.$longitude;
            $imageLocation = imagecreatefromjpeg($url);
            $tmpfname = tempnam("php://temp","pmm");
            imagejpeg($imageLocation, $tmpfname);

            $_FILES["form"]["type"] = "image/jpeg";
            $_FILES["form"]["name"] = 'Location.jpg';
            $_FILES["form"]["tmp_name"] = $tmpfname;
            $_FILES["form"]["error"] = 0;
            $sizes = getimagesize($tmpfname);
            $_FILES["form"]["size"] = ($sizes['0'] * $sizes['1']);

            $request_data = array(array('name' => $_FILES["form"]["name"]));
            $file = $oMobile->postUidUploadFiles($userUid, $app_uid, $request_data);

            $strPathName = PATH_DOCUMENT . G::getPathFromUID($app_uid) . PATH_SEP;
            $strFileName = $file[0]['appDocUid'] . "_" . $file[0]['docVersion'] . ".jpg";
            if (! is_dir( $strPathName )) {
                G::verifyPath( $strPathName, true );
            }
            copy($tmpfname, $strPathName . $strFileName);
            unlink($tmpfname);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $file;
    }

    /**
     * @url POST /case/:app_uid/download64
     *
     * @param string $app_uid         {@min 32}{@max 32}
     */
    public function postDownloadFile($app_uid, $request_data)
    {
        try {
            $oMobile = new \ProcessMaker\BusinessModel\Light();
            $files = $oMobile->downloadFile($app_uid, $request_data);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $files;
    }

    /**
     * @url POST /logout
     *
     * @param $access
     * @param $refresh
     * @return mixed
     */
    public function postLogout($access, $refresh)
    {
        try {
            $oMobile = new \ProcessMaker\BusinessModel\Light();
            $files = $oMobile->logout($access, $refresh);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $files;
    }

    /**
     * @url GET /:type/case/:app_uid
     *
     * @param $access
     * @param $refresh
     * @return mixed
     */
    public function getInformation($type, $app_uid)
    {
        try {
            $userUid       = $this->getUserId();
            $oMobile = new \ProcessMaker\BusinessModel\Light();
            $response = $oMobile->getInformation($userUid, $type, $app_uid);
            $response = $this->parserGetInformation($response);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $response;
    }

    public function parserGetInformation ($data)
    {
        $structure = array(
            'case' => array(
                'PRO_TITLE'   => 'processTitle',
                'APP_TITLE'   => 'caseTitle',
                'APP_NUMBER'  => 'caseNumber',
                'APP_STATUS'  => 'caseStatus',
                'APP_UID'     => 'caseId',
                'CREATOR'     => 'caseCreator',
                'CREATE_DATE' => 'caseCreateDate',
                'UPDATE_DATE' => 'caseUpdateData',
                'DESCRIPTION' => 'caseDescription'
            ),
            'task' => array(
                'TAS_TITLE'         => 'taskTitle',
                'CURRENT_USER'      => 'currentUser',
                'DEL_DELEGATE_DATE' => 'delDelegateDate',
                'DEL_INIT_DATE'     => 'delInitDate',
                'DEL_TASK_DUE_DATE' => 'delDueDate',
                'DEL_FINISH_DATE'   => 'delFinishDate'
            )
        );

        $response = $this->replaceFields($data, $structure);
        return $response;
    }

    /**
     * @url POST /case/:app_uid/upload
     *
     * @param $access
     * @param $refresh
     * @return mixed
     */
    public function uidUploadFiles($app_uid, $request_data)
    {
        try {
            $userUid = $this->getUserId();
            $oMobile = new \ProcessMaker\BusinessModel\Light();
            $filesUids = $oMobile->postUidUploadFiles($userUid, $app_uid, $request_data);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $filesUids;
    }

    /**
     * @url POST /case/:app_uid/upload/:app_doc_uid
     *
     * @param $access
     * @param $refresh
     * @return mixed
     */
    public function documentUploadFiles($app_uid, $app_doc_uid, $request_data)
    {
        try {
            $userUid = $this->getUserId();
            $oMobile = new \ProcessMaker\BusinessModel\Light();
            $response = $oMobile->documentUploadFiles($userUid, $app_uid, $app_doc_uid, $request_data);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $response;
    }

    /**
     * @url POST /case/:app_uid/claim
     *
     * @param $app_uid
     * @return mixed
     */
    public function claimCaseUser($app_uid)
    {
        try {
            $userUid = $this->getUserId();
            $oMobile = new \ProcessMaker\BusinessModel\Light();
            $response = $oMobile->claimCaseUser($userUid, $app_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $response;
    }

    /**
     * Get Case Notes
     *
     * @param string $app_uid {@min 1}{@max 32}
     * @param string $start {@from path}
     * @param string $limit {@from path}
     * @param string $sort {@from path}
     * @param string $dir {@from path}
     * @param string $usr_uid {@from path}
     * @param string $date_from {@from path}
     * @param string $date_to {@from path}
     * @param string $search {@from path}
     * @return array
     *
     * @copyright Colosa - Bolivia
     *
     * @url GET /case/:app_uid/notes
     */
    public function doGetCaseNotes(
        $app_uid,
        $start = 0,
        $limit = 25,
        $sort = 'APP_CACHE_VIEW.APP_NUMBER',
        $dir = 'DESC',
        $usr_uid = '',
        $date_from = '',
        $date_to = '',
        $search = ''
    ) {
        try {
            $dataList['start'] = $start;
            $dataList['limit'] = $limit;
            $dataList['sort'] = $sort;
            $dataList['dir'] = $dir;
            $dataList['user'] = $usr_uid;
            $dataList['dateFrom'] = $date_from;
            $dataList['dateTo'] = $date_to;
            $dataList['search'] = $search;

            $appNotes = new \AppNotes();
            $response = $appNotes->getNotesList( $app_uid, '', $start, $limit );
            $response  = $this->parserDataNotes($response['array']['notes']);

            return $response;
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
    }

    public function parserDataNotes ($data)
    {
        $structure = array(
            'APP_UID'          => 'caseId',
            'notes' => array(
                'NOTE_DATE'    => 'date',
                'NOTE_CONTENT' => 'content'
            ),
            'user' => array(
                'USR_UID'       => 'userId',
                'USR_USERNAME'  => 'name',
                'USR_FIRSTNAME' => 'firstName',
                'USR_LASTNAME'  => 'lastName',
                'USR_EMAIL'     => 'email'
            )
        );

        $response = $this->replaceFields($data, $structure);
        return $response;
    }

    /**
     * Post Case Notes
     *
     * @param string $app_uid {@min 1}{@max 32}
     * @param string $noteContent {@min 1}{@max 500}
     * @param int $sendMail {@choice 1,0}
     *
     * @copyright Colosa - Bolivia
     *
     * @url POST /case/:app_uid/note
     */
    public function doPostCaseNote($app_uid, $noteContent, $sendMail = 0)
    {
        try {
            $usr_uid = $this->getUserId();
            $cases = new \ProcessMaker\BusinessModel\Cases();
            $sendMail = ($sendMail == 0) ? false : true;
            $cases->saveCaseNote($app_uid, $usr_uid, $noteContent, $sendMail);
            $result = array("status" => 'ok');
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $result;
    }

    /**
     * GET list category
     *
     * @return array
     * @throws RestException
     *
     * @url GET /category
     */
    public function getCategoryList()
    {
        try {
            $oLight = new \ProcessMaker\BusinessModel\Light();
            $category = $oLight->getCategoryList();
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $category;
    }

    /**
     * GET list process
     *
     * @return array
     * @throws RestException
     *
     * @param string $action {@min 1}{@max 32}
     * @param string $cat_uid {@max 32}{@from path}
     *
     * @url GET /process/:action
     */
    public function getProcessList ($action, $cat_uid = null)
    {
        try {
            $usr_uid = $this->getUserId();
            $oLight = new \ProcessMaker\BusinessModel\Light();
            $process = $oLight->getProcessList($action, $cat_uid, $usr_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $process;
    }

    /**
     * GET list process
     *
     * @return array
     * @throws RestException
     *
     * @param string $task_uid {@min 1}{@max 32}
     *
     * @url GET /userstoreassign/:task_uid
     */
    public function getUsersToReassign ($task_uid)
    {
        try {
            $usr_uid = $this->getUserId();
            $oLight = new \ProcessMaker\BusinessModel\Light();
            $process = $oLight->getUsersToReassign($usr_uid, $task_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $process;
    }

    /**
     * @return \stdclass
     * @throws RestException
     *
     * @param string $app_uid {@min 1}{@max 32}
     * @param string $to_usr_uid {@min 1}{@max 32}
     *
     * @url POST /reassign/:app_uid/user/:to_usr_uid
     */
    public function reassignCase ($app_uid, $to_usr_uid)
    {
        try {
            $usr_uid = $this->getUserId();
            $oLight = new \ProcessMaker\BusinessModel\Light();
            $process = $oLight->reassignCase($usr_uid, $app_uid, $to_usr_uid);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $process;
    }

    /**
     * Paused Case
     *
     * @return \stdclass
     * @throws RestException
     *
     * @param string $app_uid {@min 1}{@max 32}
     *
     * @url POST /cases/:app_uid/pause
     */
    public function pauseCase ($app_uid, $request_data)
    {
        try {
            $usr_uid = $this->getUserId();
            $oLight = new \ProcessMaker\BusinessModel\Light();
            $process = $oLight->pauseCase($usr_uid, $app_uid, $request_data);
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $process;
    }

    /**
     * Unpaused Case
     *
     * @return \stdclass
     * @throws RestException
     *
     * @param string $app_uid {@min 1}{@max 32}
     *
     * @url POST /cases/:app_uid/unpause
     */
    public function unpauseCase ($app_uid)
    {
        $result = array();
        try {
            $usr_uid = $this->getUserId();
            $cases = new \ProcessMaker\BusinessModel\Cases();
            $cases->putUnpauseCase($app_uid, $usr_uid);
            $result["status"] = "ok";
        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }
        return $result;
    }

    /**
     * Cancel Case
     *
     * @param string $cas_uid {@min 1}{@max 32}
     *
     * @copyright Colosa - Bolivia
     *
     * @url POST /cases/:app_uid/cancel
     */
    public function doPutCancelCase($app_uid)
    {
        $response = array("status" => "false");
        try {
            $userUid = $this->getUserId();
            $cases = new \ProcessMaker\BusinessModel\Cases();
            $cases->putCancelCase($app_uid, $userUid);
            $response["status"] = "ok";
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }

    /**
     * @url GET /project/:prj_uid/case/:app_uid
     *
     * @param string $prj_uid {@min 32}{@max 32}
     */
    public function doGetProcessMapImage($prj_uid, $app_uid)
    {
        $return = array();
        try {
            $oPMap = new \ProcessMaker\BusinessModel\ProcessMap();
            $schema = Adapter\BpmnWorkflow::getStruct($prj_uid);

            $case = new \ProcessMaker\BusinessModel\Cases();
            $case->setFormatFieldNameInUppercase(false);
            $schemaStatus = $case->getTasks($app_uid);

            $file = $oPMap->get_image($schema, $schemaStatus);
            ob_start();
            imagepng($file);
            $image  = ob_get_clean();
            $return["map"] = base64_encode($image);

        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $return;
    }

    /**
     * Get configuration ProcessMaker
     *
     * @return array
     *
     * @url GET /config
     */
    public function getConfiguration()
    {
        try {
            $oMobile = new \ProcessMaker\BusinessModel\Light();
            $response = $oMobile->getConfiguration();
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
        return $response;
    }
}
