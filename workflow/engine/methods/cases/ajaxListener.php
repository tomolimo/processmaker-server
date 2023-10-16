<?php

use ProcessMaker\BusinessModel\Cases as BmCases;
use ProcessMaker\BusinessModel\Cases\ChangeLog;
/*----------------------------------********---------------------------------*/
use ProcessMaker\Plugins\PluginRegistry;

if (isset($_REQUEST['glpi_data'])) {
    // we must update the $_SESSION variables
    $glpi_data = json_decode($_REQUEST['glpi_data'], true);
    $_SESSION['APPLICATION'] = $glpi_data['glpi_app_uid'];
    $_SESSION['PROCESS']     = $glpi_data['glpi_pro_uid'];
    $_SESSION['TASK']        = '';
}


if (!isset($_SESSION['USER_LOGGED'])) {
    $responseObject = new stdclass();
    $responseObject->error = G::LoadTranslation('ID_LOGIN_AGAIN');
    $responseObject->success = true;
    $responseObject->lostSession = true;
    print G::json_encode($responseObject);
    die();
}

$filter = new InputFilter();
$_REQUEST = $filter->xssFilterHard($_REQUEST);
$_POST = $filter->xssFilterHard($_POST);

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "verifySession") {
    if (!isset($_SESSION['USER_LOGGED'])) {
        $response = new stdclass();
        $response->message = G::LoadTranslation('ID_LOGIN_AGAIN');
        $response->lostSession = true;
        print G::json_encode($response);
        die();
    } else {
        //When the user has session we will to validate the permissions over other actions
        /** Action: Reassign from openCase */
        global $RBAC;
        $proUid = isset($_SESSION['PROCESS']) ? $_SESSION['PROCESS'] : '';
        $appUid = isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION'] : '';
        $tasUid = isset($_SESSION['TASK']) ? $_SESSION['TASK'] : '';

        $response = new stdclass();
        $response->reassigncase = false;
        $response->message = G::LoadTranslation('ID_NOT_ABLE_REASSIGN');

        $userAuthorization = [];
        if (!empty($proUid) && !empty($appUid)) {
            $cases = new BmCases();
            $userAuthorization = $cases->userAuthorization(
                $RBAC->aUserInfo['USER_INFO']['USR_UID'],
                $proUid,
                $appUid,
                ['PM_REASSIGNCASE', 'PM_REASSIGNCASE_SUPERVISOR'],
                ['REASSIGN_MY_CASES' => ''],
                true,
                $tasUid
            );

            if (
                $userAuthorization['rolesPermissions']['PM_REASSIGNCASE'] ||
                ($userAuthorization['rolesPermissions']['PM_REASSIGNCASE_SUPERVISOR'] && $userAuthorization['supervisor']) ||
                in_array($appUid, $userAuthorization['objectPermissions']['REASSIGN_MY_CASES'])
            ) {
                $response->reassigncase = true;
                $response->message = '';
            }
        }

        print G::json_encode($response);
        die();
    }
}

class Ajax
{
    public function getCaseMenu($params)
    {
        global $G_TMP_MENU;
        global $sStatus;
        $sStatus = $params['app_status'];
        $oCase = new Cases();
        $conf = new Configurations();
        $oMenu = new Menu();
        $oMenu->load('caseOptions');

        $menuOptions = array();
        foreach ($oMenu->Options as $i => $action) {
            $option = array('id' => $oMenu->Id[$i], 'label' => $oMenu->Labels[$i], 'action' => $action);

            switch ($option['id']) {
                case 'STEPS':
                    $option['options'] = array();
                    break;
                case 'ACTIONS':
                    $option['options'] = $this->getActionOptions();
                    break;
                case 'INFO':
                    $option['options'] = $this->getInformationOptions();
                    break;
            }
            $menuOptions[] = $option;
        }

        echo G::json_encode($menuOptions);
    }

    public function steps()
    {
        if (!isset($_SESSION['USER_LOGGED'])) {
            $response = new stdclass();
            $response->message = G::LoadTranslation('ID_LOGIN_AGAIN');
            $response->lostSession = true;
            print G::json_encode($response);
            die();
        }

        $applications = new Applications();

        $proUid = isset($_SESSION['PROCESS']) ? $_SESSION['PROCESS'] : '';
        $tasUid = isset($_SESSION['TASK']) ? $_SESSION['TASK'] : '';
        $appUid = isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION'] : '';
        $index = isset($_SESSION['INDEX']) ? $_SESSION['INDEX'] : '';
        $steps = $applications->getSteps($appUid, $index, $tasUid, $proUid);
        $list = array();

        foreach ($steps as $step) {
            $item['id'] = $item['idtodraw'] = $step['id'];
            $item['draggable'] = false;
            $item['leaf'] = true;
            $item['hrefTarget'] = 'casesSubFrame';
            $item['text'] = $step['title'];
            $item['url'] = '../' . $step['url'];
            $item["type"] = $step["type"];

            switch ($step['type']) {
                case 'DYNAFORM':
                    $item['iconCls'] = 'ss_sprite ss_application_form';
                    break;
                case 'OUTPUT_DOCUMENT':
                    $item['iconCls'] = 'ss_sprite ss_application_put';
                    break;
                case 'INPUT_DOCUMENT':
                    $item['iconCls'] = 'ss_sprite ss_application_get';
                    break;
                case 'EXTERNAL':
                    $item['iconCls'] = 'ss_sprite ss_application_view_detail';
                    break;
                default:
                    $item['iconCls'] = 'ICON_ASSIGN_TASK';
            }

            $list[] = $item;
        }

        echo G::json_encode($list);
    }

    public function getInformationOptions()
    {
        $options = array();
        $options[] = array('text' => G::LoadTranslation('ID_PROCESS_MAP'), 'fn' => 'processMap');
        $options[] = array('text' => G::LoadTranslation('ID_PROCESS_INFORMATION'), 'fn' => 'processInformation');
        $options[] = array('text' => G::LoadTranslation('ID_TASK_INFORMATION'), 'fn' => 'taskInformation');
        $options[] = array('text' => G::LoadTranslation('ID_CASE_HISTORY'), 'fn' => 'caseHistory');
        $options[] = array('text' => G::LoadTranslation('ID_HISTORY_MESSAGE_CASE'), 'fn' => 'messageHistory');
        $options[] = array('text' => G::LoadTranslation('ID_DYNAFORMS'), 'fn' => 'dynaformHistory');
        $options[] = array('text' => G::LoadTranslation('ID_DYNAFORM_HISTORY'), 'fn' => 'changeLogHistory');
        $options[] = array('text' => G::LoadTranslation('ID_UPLOADED_DOCUMENTS'), 'fn' => 'uploadedDocuments');
        $options[] = array('text' => G::LoadTranslation('ID_GENERATED_DOCUMENTS'), 'fn' => 'generatedDocuments');

        return $options;
    }

    /**
     * Get the options menu from action
     *
     * @return array
    */
    public function getActionOptions()
    {
        $appUid = $_SESSION['APPLICATION'];
        $index = $_SESSION['INDEX'];
        $proUid = $_SESSION['PROCESS'];
        $tasUid = $_SESSION['TASK'];

        $c = new Criteria('workflow');
        $c->clearSelectColumns();
        $c->addSelectColumn(AppThreadPeer::APP_THREAD_PARENT);
        $c->add(AppThreadPeer::APP_UID, $appUid);
        $c->add(AppThreadPeer::APP_THREAD_STATUS, 'OPEN');
        $cant = AppThreadPeer::doCount($c);

        $oCase = new Cases();
        $aFields = $oCase->loadCase($appUid, $index);

        global $RBAC;

        $options = [];

        switch ($aFields['APP_STATUS']) {
            case 'DRAFT':
                if (!AppDelay::isPaused($appUid, $index)) {
                    $options[] = ['text' => G::LoadTranslation('ID_PAUSED_CASE'), 'fn' => 'setUnpauseCaseDate'];
                } else {
                    $options[] = ['text' => G::LoadTranslation('ID_UNPAUSE'), 'fn' => 'unpauseCase'];
                }

                $options[] = ['text' => G::LoadTranslation('ID_DELETE'), 'fn' => 'deleteCase'];

                if ($RBAC->userCanAccess('PM_REASSIGNCASE') == 1 || $RBAC->userCanAccess('PM_REASSIGNCASE_SUPERVISOR') == 1) {
                    if (!AppDelay::isPaused($appUid, $index)) {
                        $options[] = ['text' => G::LoadTranslation('ID_REASSIGN'), 'fn' => 'getUsersToReassign'];
                    }
                }
                break;
            case 'TO_DO':
                if (!AppDelay::isPaused($appUid, $index)) {
                    $options[] = ['text' => G::LoadTranslation('ID_PAUSED_CASE'), 'fn' => 'setUnpauseCaseDate'];
                    if ($cant == 1) {
                        if ($RBAC->userCanAccess('PM_CANCELCASE') == 1) {
                            $options[] = ['text' => G::LoadTranslation('ID_CANCEL'), 'fn' => 'cancelCase'];
                        } else {
                            $options[] = ['text' => G::LoadTranslation('ID_CANCEL'), 'fn' => 'cancelCase', 'hide' => 'hiden'];
                        }
                    }
                } else {
                    $options[] = ['text' => G::LoadTranslation('ID_UNPAUSE'), 'fn' => 'unpauseCase'];
                }
                $cases = new BmCases();
                $userAuthorization = $cases->userAuthorization(
                    $RBAC->aUserInfo['USER_INFO']['USR_UID'],
                    $proUid,
                    $appUid,
                    [],
                    ['REASSIGN_MY_CASES' => ''],
                    false,
                    $tasUid
                );
                if (
                    $RBAC->userCanAccess('PM_REASSIGNCASE') == 1
                    || $RBAC->userCanAccess('PM_REASSIGNCASE_SUPERVISOR') == 1
                    || in_array($appUid, $userAuthorization['objectPermissions']['REASSIGN_MY_CASES'])
                ) {
                    if (!AppDelay::isPaused($appUid, $index)) {
                        $options[] = ['text' => G::LoadTranslation('ID_REASSIGN'), 'fn' => 'getUsersToReassign'];
                    }
                }
                break;
            case 'CANCELLED':
                $options[] = ['text' => G::LoadTranslation('ID_REACTIVATE'), 'fn' => 'reactivateCase'];
                break;
        }

        if ($_SESSION["TASK"] != "" && $_SESSION["TASK"] != "-1") {
            $oTask = new Task();
            $tasksInParallel = explode('|', $_SESSION['TASK']);
            $tasksInParallel = array_filter($tasksInParallel, function ($value) {
                return !empty($value);
            });
            $nTasksInParallel = count($tasksInParallel);

            if ($nTasksInParallel > 1) {
                $aTask = $oTask->load($tasksInParallel[$nTasksInParallel - 1]);
            } else {
                $aTask = $oTask->load($_SESSION['TASK']);
            }

            if ($aTask['TAS_TYPE'] == 'ADHOC') {
                $options[] = ['text' => G::LoadTranslation('ID_ADHOC_ASSIGNMENT'), 'fn' => 'adhocAssignmentUsers'];
            }
        }

        return $options;
    }

    public function processMap()
    {
        global $G_PUBLISH;
        global $G_CONTENT;
        global $G_FORM;
        global $G_TABLE;
        global $RBAC;

        $oTemplatePower = new TemplatePower(PATH_TPL . 'processes/processes_Map.html');
        $oTemplatePower->prepare();
        $G_PUBLISH = new Publisher();
        $G_PUBLISH->AddContent('template', '', '', '', $oTemplatePower);
        $oHeadPublisher = headPublisher::getSingleton();

        //$oHeadPublisher->addScriptfile('/jscore/processmap/core/processmap.js');
        $oHeadPublisher->addScriptCode('
    var maximunX = ' . ProcessMap::getMaximunTaskX($_SESSION['PROCESS']) . ';
    window.onload = function(){
      var pb=leimnud.dom.capture("tag.body 0");
      Pm=new processmap();

      var params = "{\"uid\":\"' . $_SESSION['PROCESS'] . '\",\"mode\":false,\"ct\":false}";
      // maximun x and y position
      var xPos = 0;
      var yPos = 0;

      //obtaining the processmap object for the current process
      var oRPC = new leimnud.module.rpc.xmlhttp({
        url   : "../processes/processes_Ajax",
        async : false,
        method: "POST",
        args  : "action=load&data="+params
      });

      oRPC.make();
      var response = eval(\'(\' + oRPC.xmlhttp.responseText + \')\');

      for (var i in response){
        if (i==\'task\'){
          elements = response[i];
          for (var j in elements){
            if (elements[j].uid!=undefined){
              if (elements[j].position.x > xPos){
                xPos = elements[j].position.x;
              }
              if (elements[j].position.y > yPos){
                yPos = elements[j].position.y;
              }
            }
          }
        }
      }

      Pm.options = {
        target    : "pm_target",
        dataServer: "../processes/processes_Ajax",
        uid       : "' . $_SESSION['PROCESS'] . '",
        lang      : "' . SYS_LANG . '",
        theme     : "processmaker",
        size      : {w:xPos+800,h:yPos+150},
        images_dir: "/jscore/processmap/core/images/",
        rw        : false,
        hideMenu  : false
      }
      Pm.make();

      oLeyendsPanel = new leimnud.module.panel();
      oLeyendsPanel.options = {
        size  :{w:260,h:155},
        position:{x:((document.body.clientWidth * 95) / 100) - ((document.body.clientWidth * 95) / 100 - (((document.body.clientWidth * 95) / 100) - 260)),y:45,center:false},
        title :G_STRINGS.ID_COLOR_LEYENDS,
        theme :"processmaker",
        statusBar:false,
        control :{resize:false,roll:false,drag:true,close:false},
        fx  :{modal:false,opacity:false,blinkToFront:true,fadeIn:false,drag:false}
      };
      oLeyendsPanel.setStyle = {
        content:{overflow:"hidden"}
      };
      oLeyendsPanel.events = {
        remove: function() {delete(oLeyendsPanel);}.extend(this)
      };
      oLeyendsPanel.make();
      oLeyendsPanel.loader.show();
      var oRPC = new leimnud.module.rpc.xmlhttp({
        url : "cases_Ajax",
        args: "action=showLeyends"
      });
      oRPC.callback = function(rpc){
        oLeyendsPanel.loader.hide();
        var scs=rpc.xmlhttp.responseText.extractScript();
        oLeyendsPanel.addContent(rpc.xmlhttp.responseText);
      }.extend(this);
      oRPC.make();
    }');

        G::RenderPage('publish', 'blank');
    }

    public function getProcessInformation()
    {
        if (!isset($_SESSION['USER_LOGGED'])) {
            $response = new stdclass();
            $response->message = G::LoadTranslation('ID_LOGIN_AGAIN');
            $response->lostSession = true;
            print G::json_encode($response);
            die();
        }
        $process = new Process();
        $processData = $process->load($_SESSION['PROCESS']);
        require_once 'classes/model/Users.php';
        $user = new Users();
        try {
            $userData = $user->load($processData['PRO_CREATE_USER']);
            $processData['PRO_AUTHOR'] = $userData['USR_FIRSTNAME'] . ' ' . $userData['USR_LASTNAME'];
        } catch (Exception $oError) {
            $processData['PRO_AUTHOR'] = '(USER DELETED)';
        }

        $conf = new Configurations();
        $conf->getFormats();
        $processData['PRO_CREATE_DATE'] = $conf->getSystemDate($processData['PRO_CREATE_DATE']);
        print(G::json_encode($processData));
    }

    public function getTaskInformation()
    {
        if (!isset($_SESSION['USER_LOGGED'])) {
            $response = new stdclass();
            $response->message = G::LoadTranslation('ID_LOGIN_AGAIN');
            $response->lostSession = true;
            print G::json_encode($response);
            die();
        }

        $task = new Task();
        if ($_SESSION['TASK'] == '-1') {
            $_SESSION['TASK'] = $_SESSION['CURRENT_TASK'];
        }
        $taskData = $task->getDelegatedTaskData($_SESSION['TASK'], $_SESSION['APPLICATION'], $_SESSION['INDEX']);

        $taskData = \ProcessMaker\Util\DateTime::convertUtcToTimeZone($taskData);

        print(G::json_encode($taskData));
    }

    public function caseHistory()
    {
        global $G_PUBLISH;

        $oHeadPublisher = headPublisher::getSingleton();
        $conf = new Configurations();
        $oHeadPublisher->addExtJsScript('cases/caseHistory', true); //adding a javascript file .js
        $oHeadPublisher->addContent('cases/caseHistory'); //adding a html file  .html.
        $oHeadPublisher->assign("FORMATS", $conf->getFormats());
        $oHeadPublisher->assign('pageSize', $conf->getEnvSetting('casesListRowNumber'));
        G::RenderPage('publish', 'extJs');
    }

    public function messageHistory()
    {
        global $G_PUBLISH;

        $oHeadPublisher = headPublisher::getSingleton();
        $conf = new Configurations();
        $oHeadPublisher->addExtJsScript('cases/caseMessageHistory', true); //adding a javascript file .js
        $oHeadPublisher->addContent('cases/caseMessageHistory'); //adding a html file  .html.
        $oHeadPublisher->assign('pageSize', $conf->getEnvSetting('casesListRowNumber'));
        G::RenderPage('publish', 'extJs');
    }

    public function dynaformHistory()
    {
        global $G_PUBLISH;

        $oHeadPublisher = headPublisher::getSingleton();
        $conf = new Configurations();
        $oHeadPublisher->addExtJsScript('cases/caseHistoryDynaformPage', true); //adding a javascript file .js
        $oHeadPublisher->addContent('cases/caseHistoryDynaformPage'); //adding a html file  .html.
        $oHeadPublisher->assign('pageSize', $conf->getEnvSetting('casesListRowNumber'));
        G::RenderPage('publish', 'extJs');
    }

    public function changeLogHistory()
    {
        global $G_PUBLISH;

        $idHistory = sprintf(
            '%s_%s_%s',
            $_SESSION['PROCESS'],
            $_SESSION['APPLICATION'],
            $_SESSION['TASK']
        );

        $oHeadPublisher = headPublisher::getSingleton();
        $conf = new Configurations();
        $oHeadPublisher->addExtJsScript('cases/caseChangeLog', true); //adding a javascript file .js
        $oHeadPublisher->addContent('cases/caseChangeLog'); //adding a html file  .html.
        $oHeadPublisher->assign('ID_HISTORY', $idHistory);
        G::RenderPage('publish', 'extJs');
    }

    public function uploadedDocuments()
    {
        if (!isset($_SESSION['USER_LOGGED'])) {
            $response = new stdclass();
            $response->message = G::LoadTranslation('ID_LOGIN_AGAIN');
            $response->lostSession = true;
            print G::json_encode($response);
            die();
        }
        global $G_PUBLISH;

        $oHeadPublisher = headPublisher::getSingleton();
        $conf = new Configurations();
        $oHeadPublisher->addExtJsScript('cases/casesUploadedDocumentsPage', true); //adding a javascript file .js
        $oHeadPublisher->addContent('cases/casesUploadedDocumentsPage'); //adding a html file  .html.
        $oHeadPublisher->assign("FORMATS", $conf->getFormats());
        $oHeadPublisher->assign('pageSize', $conf->getEnvSetting('casesListRowNumber'));
        G::RenderPage('publish', 'extJs');
    }

    public function uploadedDocumentsSummary()
    {
        global $G_PUBLISH;

        $oHeadPublisher = headPublisher::getSingleton();
        $conf = new Configurations();
        $oHeadPublisher->addExtJsScript('cases/casesUploadedDocumentsPage', true); //adding a javascript file .js
        $oHeadPublisher->addContent('cases/casesUploadedDocumentsPage'); //adding a html file  .html.
        $oHeadPublisher->assign("FORMATS", $conf->getFormats());
        $oHeadPublisher->assign('pageSize', $conf->getEnvSetting('casesListRowNumber'));
        G::RenderPage('publish', 'extJs');
    }

    public function generatedDocuments()
    {
        global $G_PUBLISH;

        $oHeadPublisher = headPublisher::getSingleton();
        $conf = new Configurations();
        $oHeadPublisher->addExtJsScript('cases/casesGenerateDocumentPage', true); //adding a javascript file .js
        $oHeadPublisher->addContent('cases/casesGenerateDocumentPage'); //adding a html file  .html.
        $oHeadPublisher->assign("FORMATS", $conf->getFormats());
        $oHeadPublisher->assign('pageSize', $conf->getEnvSetting('casesListRowNumber'));
        G::RenderPage('publish', 'extJs');
    }

    public function generatedDocumentsSummary()
    {
        global $G_PUBLISH;

        $oHeadPublisher = headPublisher::getSingleton();
        $conf = new Configurations();
        $oHeadPublisher->addExtJsScript('cases/casesGenerateDocumentPage', true); //adding a javascript file .js
        $oHeadPublisher->addContent('cases/casesGenerateDocumentPage'); //adding a html file  .html.
        $oHeadPublisher->assign("FORMATS", $conf->getFormats());
        $oHeadPublisher->assign('pageSize', $conf->getEnvSetting('casesListRowNumber'));
        G::RenderPage('publish', 'extJs');
    }

    public function cancelCase()
    {
        $oCase = new Cases();
        $multiple = false;

        if (isset($_POST['APP_UID']) && isset($_POST['DEL_INDEX'])) {
            $APP_UID = $_POST['APP_UID'];
            $DEL_INDEX = $_POST['DEL_INDEX'];

            $appUids = explode(',', $APP_UID);
            $delIndexes = explode(',', $DEL_INDEX);
            if (count($appUids) > 1 && count($delIndexes) > 1) {
                $multiple = true;
            }
        } elseif (isset($_POST['sApplicationUID']) && isset($_POST['iIndex'])) {
            $APP_UID = $_POST['sApplicationUID'];
            $DEL_INDEX = $_POST['iIndex'];
        } else {
            $APP_UID = $_SESSION['APPLICATION'];
            $DEL_INDEX = $_SESSION['INDEX'];
        }

        // Save the note pause reason
        if ($_POST['NOTE_REASON'] != '') {
            require_once("classes/model/AppNotes.php");
            $appNotes = new AppNotes();
            $noteContent = addslashes($_POST['NOTE_REASON']);
            $appNotes->postNewNote($APP_UID, $_SESSION['USER_LOGGED'], $noteContent, $_POST['NOTIFY_PAUSE']);
        }
        // End save


        if ($multiple) {
            foreach ($appUids as $i => $appUid) {
                $oCase->cancelCase($appUid, $delIndexes[$i], $_SESSION['USER_LOGGED']);
            }
        } else {
            $oCase->cancelCase($APP_UID, $DEL_INDEX, $_SESSION['USER_LOGGED']);
        }
    }

    public function getUsersToReassign()
    {
        if (!isset($_SESSION['USER_LOGGED'])) {
            $response = new stdclass();
            $response->message = G::LoadTranslation('ID_LOGIN_AGAIN');
            $response->lostSession = true;
            print G::json_encode($response);
            die();
        }

        if (isset($_SESSION['TASK']) && $_SESSION['TASK'] != '-1') {
            $taskUid  = $_SESSION['TASK'];
        } else {
            $taskUid  = $_SESSION['CURRENT_TASK'];
        }
        $search   = $_POST['search'];
        $pageSize = $_POST['pageSize'];

        $sortField = (isset($_POST['sort']))?  $_POST['sort'] : '';
        $sortDir   = (isset($_POST['dir']))?   $_POST['dir'] : '';
        $start     = (isset($_POST['start']))? $_POST['start'] : 0;
        $limit     = (isset($_POST['limit']))? $_POST['limit'] : $pageSize;

        $response = [];

        try {
            $case = new BmCases();

            $result = $case->getUsersToReassign($_SESSION['USER_LOGGED'], $taskUid, ['filter' => $search], $sortField, $sortDir, $start, $limit);

            $response['status'] = 'OK';
            $response['success'] = true;
            $response['resultTotal'] = $result['total'];
            $response['resultRoot'] = $result['data'];
        } catch (Exception $e) {
            $response['status'] = 'ERROR';
            $response['message'] = $e->getMessage();
        }

        echo G::json_encode($response);
    }

    public function reassignCase()
    {
        $cases = new Cases();
        $user = new Users();
        $app = new Application();
        $result = new stdclass();
        $oAppDel = new AppDelegation();

        $TO_USR_UID = $_POST['USR_UID'];
        try {
            //Current users of OPEN DEL_INDEX thread
            if (isset($_SESSION['APPLICATION']) && isset($_SESSION['INDEX'])) {
                $aCurUser = $oAppDel->getCurrentUsers($_SESSION['APPLICATION'], $_SESSION['INDEX']);
            }
            $flagReassign = true;
            if (!empty($aCurUser)) {
                foreach ($aCurUser as $key => $value) {
                    if ($value === $TO_USR_UID) {
                        $flagReassign = false;
                    }
                }
            } else {
                //DEL_INDEX is CLOSED
                throw new Exception(G::LoadTranslation('ID_REASSIGNMENT_ERROR'));
            }

            //If the currentUser is diferent to nextUser, create the thread
            if ($flagReassign) {
                $cases->reassignCase($_SESSION['APPLICATION'], $_SESSION['INDEX'], $_SESSION['USER_LOGGED'], $TO_USR_UID);
            }

            $caseData = $app->load($_SESSION['APPLICATION']);
            $userData = $user->load($TO_USR_UID);

            $data['APP_NUMBER'] = $caseData['APP_NUMBER'];
            $data['USER'] = $userData['USR_LASTNAME'] . ' ' . $userData['USR_FIRSTNAME']; //TODO change with the farmated username from environment conf
            $result->status = 0;
            $result->msg = G::LoadTranslation('ID_REASSIGNMENT_SUCCESS', SYS_LANG, $data);

            // Save the note reassign reason
            if (isset($_POST['NOTE_REASON']) && $_POST['NOTE_REASON'] !== '') {
                require_once("classes/model/AppNotes.php");
                $appNotes = new AppNotes();
                $noteContent = addslashes($_POST['NOTE_REASON']);
                $notifyReassign = $_POST['NOTIFY_REASSIGN'] === 'true' ? true: false;
                $appNotes->postNewNote($_SESSION['APPLICATION'], $_SESSION['USER_LOGGED'], $noteContent, $notifyReassign);
            }
        } catch (Exception $e) {
            $result->status = 1;
            $result->msg = $e->getMessage();
        }

        print G::json_encode($result);
    }

    public function pauseCase()
    {
        $result = new stdclass();
        try {
            $unpauseDate = $_REQUEST['unpauseDate'] . ' '. $_REQUEST['unpauseTime'];
            $oCase = new Cases();
            if (isset($_POST['APP_UID']) && isset($_POST['DEL_INDEX'])) {
                $APP_UID = $_POST['APP_UID'];
                $DEL_INDEX = $_POST['DEL_INDEX'];
            } elseif (isset($_POST['sApplicationUID']) && isset($_POST['iIndex'])) {
                $APP_UID = $_POST['sApplicationUID'];
                $DEL_INDEX = $_POST['iIndex'];
            } else {
                $APP_UID = $_SESSION['APPLICATION'];
                $DEL_INDEX = $_SESSION['INDEX'];
            }

            // Save the note pause reason
            if ($_REQUEST['NOTE_REASON'] != '') {
                require_once("classes/model/AppNotes.php");
                $appNotes = new AppNotes();
                $noteContent = addslashes($_REQUEST['NOTE_REASON']);
                $appNotes->postNewNote($APP_UID, $_SESSION['USER_LOGGED'], $noteContent, $_REQUEST['NOTIFY_PAUSE']);
            }
            // End save


            $oCase->pauseCase($APP_UID, $DEL_INDEX, $_SESSION['USER_LOGGED'], $unpauseDate);
            $app = new Application();
            $caseData = $app->load($APP_UID);
            $data['APP_NUMBER'] = $caseData['APP_NUMBER'];
            $data['UNPAUSE_DATE'] = $unpauseDate;

            $result->success = true;
            $result->msg = G::LoadTranslation('ID_CASE_PAUSED_SUCCESSFULLY', SYS_LANG, $data);
        } catch (Exception $e) {
            $result->success = false;
            $result->msg = $e->getMessage();
        }
        echo G::json_encode($result);
    }

    public function unpauseCase()
    {
        $result = new stdclass();
        try {
            $applicationUID = (isset($_POST['APP_UID'])) ? $_POST['APP_UID'] : $_SESSION['APPLICATION'];
            $delIndex = (isset($_POST['DEL_INDEX'])) ? $_POST['DEL_INDEX'] : $_SESSION['INDEX'];
            $oCase = new Cases();
            $oCase->unpauseCase($applicationUID, $delIndex, $_SESSION['USER_LOGGED']);

            $app = new Application();
            $caseData = $app->load($applicationUID);
            $data['APP_NUMBER'] = $caseData['APP_NUMBER'];

            $result->success = true;
            $result->msg = G::LoadTranslation('ID_CASE_UNPAUSED_SUCCESSFULLY', SYS_LANG, $data);
        } catch (Exception $e) {
            $result->success = false;
            $result->msg = $e->getMessage();
        }

        print G::json_encode($result);
    }

    public function deleteCase()
    {
        $result = new stdclass();
        try {
            $applicationUID = (isset($_POST['APP_UID'])) ? $_POST['APP_UID'] : $_SESSION['APPLICATION'];
            $app = new Application();
            $caseData = $app->load($applicationUID);
            $data['APP_NUMBER'] = $caseData['APP_NUMBER'];

            $oCase = new Cases();
            $oCase->removeCase($applicationUID);

            $result->success = true;
            $result->msg = G::LoadTranslation('ID_CASE_DELETED_SUCCESSFULLY', SYS_LANG, $data);
        } catch (Exception $e) {
            $result->success = false;
            $result->msg = $e->getMessage();
        }
        print G::json_encode($result);
    }

    public function reactivateCase()
    {
        $result = new stdclass();
        try {
            $applicationUID = (isset($_POST['APP_UID'])) ? $_POST['APP_UID'] : $_SESSION['APPLICATION'];
            $delIndex = (isset($_POST['DEL_INDEX'])) ? $_POST['DEL_INDEX'] : $_SESSION['INDEX'];
            $app = new Application();
            $caseData = $app->load($applicationUID);
            $data['APP_NUMBER'] = $caseData['APP_NUMBER'];

            $oCase = new Cases();
            $oCase->reactivateCase($applicationUID, $delIndex, $_SESSION['USER_LOGGED']);

            $result->success = true;
            $result->msg = G::LoadTranslation('ID_CASE_REACTIVATED_SUCCESSFULLY', SYS_LANG, $data);
        } catch (Exception $e) {
            $result->success = false;
            $result->msg = $e->getMessage();
        }

        print G::json_encode($result);
    }

    public function changeLogAjax()
    {
        $idHistory = $_REQUEST["idHistory"];
        $idHistoryArray = explode("_", $idHistory);
        $proUid = $idHistoryArray[0];
        $appUid = $idHistoryArray[1];
        $tasUid = $idHistoryArray[2];

        /*----------------------------------********---------------------------------*/
            $start = isset($_REQUEST['start']) ? (int) $_REQUEST['start'] : 0;
            $limit = isset($_REQUEST['limit']) ? (int) $_REQUEST['limit'] : 15;
            $changeLog = new ChangeLog();
            echo G::json_encode($changeLog->getChangeLog($appUid, $proUid, $tasUid, $start, $limit));
        /*----------------------------------********---------------------------------*/
    }

    public function changeLogTab()
    {
        if (!isset($_SESSION['USER_LOGGED'])) {
            $response = new stdclass();
            $response->message = G::LoadTranslation('ID_LOGIN_AGAIN');
            $response->lostSession = true;
            print G::json_encode($response);
            die();
        }
        try {
            global $G_PUBLISH;
            require_once 'classes/model/AppHistory.php';

            //!dataInput
            $idHistory = $_REQUEST["idHistory"];
            //!dataInput
            //!dataSytem
            $idHistoryArray = explode("_", $idHistory);
            $_REQUEST["PRO_UID"] = $idHistoryArray[0];
            $_REQUEST["APP_UID"] = $idHistoryArray[1];
            $_REQUEST["TAS_UID"] = $idHistoryArray[2];
            $_REQUEST["DYN_UID"] = "";

            $result = new stdclass();

            $G_PUBLISH = new Publisher();
            $G_PUBLISH->AddContent('view', 'cases/cases_DynaformHistory'); ?>
            <link rel="stylesheet" type="text/css" href="/css/classic.css" />
            <style type="text/css">
                html {
                    color: black !important;
                }

                body {
                    color: black !important;
                }
            </style>
            <script language="javascript">
                function ajaxPostRequest(url, callback_function, id) {
                    var d = new Date();
                    var time = d.getTime();
                    url = url + '&nocachetime=' + time;
                    var return_xml = false;
                    var http_request = false;

                    if (window.XMLHttpRequest) {
                        // Mozilla, Safari,...
                        http_request = new XMLHttpRequest();
                        if (http_request.overrideMimeType) {
                            http_request.overrideMimeType('text/xml');
                        }
                    }
                    else if (window.ActiveXObject) {
                        // IE
                        try {
                            http_request = new ActiveXObject("Msxml2.XMLHTTP");
                        }
                        catch (e) {
                            try {
                                http_request = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            catch (e) {
                            }
                        }
                    }

                    if (!http_request) {
                        alert('This browser is not supported.');
                        return false;
                    }

                    http_request.onreadystatechange = function() {
                        if (http_request.readyState == 4) {
                            if (http_request.status == 200) {
                                if (return_xml) {
                                    eval(callback_function + '(http_request.responseXML)');
                                }
                                else {
                                    eval(callback_function + '(http_request.responseText, \'' + id + '\')');
                                }
                            }
                            else {
                                alert('Error found on request:(Code: ' + http_request.status + ')');
                            }
                        }
                    }
                    http_request.open('GET', url, true);
                    http_request.send(null);
                }

                function toggleTable(tablename) {
                    table = document.getElementById(tablename);

                    if (table.style.display == '') {
                        table.style.display = 'none';
                    } else {
                        table.style.display = '';
                    }
                }

                function noesFuncion(idIframe) {
                    window.parent.tabIframeWidthFix2(idIframe);
                }

                function onResizeIframe(idIframe) {
                    window.onresize = noesFuncion(idIframe);
                }

                function showDynaformHistoryGetNomDynaform_RSP(response, id) {
                    //!showDynaformHistoryGlobal
                    showDynaformHistoryGlobal.idDin = showDynaformHistoryGlobal.idDin;
                    showDynaformHistoryGlobal.idHistory = showDynaformHistoryGlobal.idHistory;
                    showDynaformHistoryGlobal.dynDate = showDynaformHistoryGlobal.dynDate;

                    //!dataSystem
                    var idDin = showDynaformHistoryGlobal.idDin;
                    var idHistory = showDynaformHistoryGlobal.idHistory;
                    var dynDate = showDynaformHistoryGlobal.dynDate;

                    //!windowParent
                    window.parent.historyGridListChangeLogGlobal.viewIdDin = idDin;
                    window.parent.historyGridListChangeLogGlobal.viewIdHistory = idHistory;
                    window.parent.historyGridListChangeLogGlobal.viewDynaformName = response;
                    window.parent.historyGridListChangeLogGlobal.dynDate = dynDate;

                    window.parent.Actions.tabFrame('dynaformViewFromHistory');
                }

                showDynaformHistoryGlobal = {};
                showDynaformHistoryGlobal.idDin = "";
                showDynaformHistoryGlobal.idHistory = "";
                showDynaformHistoryGlobal.dynDate = "";

                function showDynaformHistory(idDin, idHistory, dynDate) {
                    //!showDynaformHistoryGlobal
                    showDynaformHistoryGlobal.idDin = showDynaformHistoryGlobal.idDin;
                    showDynaformHistoryGlobal.idHistory = showDynaformHistoryGlobal.idHistory;
                    showDynaformHistoryGlobal.dynDate = showDynaformHistoryGlobal.dynDate;

                    //!dataSystem
                    showDynaformHistoryGlobal.idDin = idDin;
                    showDynaformHistoryGlobal.idHistory = idHistory;
                    showDynaformHistoryGlobal.dynDate = dynDate;

                    var url = "caseHistory_Ajax.php?actionAjax=showDynaformHistoryGetNomDynaform_JXP&idDin=" + idDin + "&dynDate=" + dynDate;
                    ajaxPostRequest(url, 'showDynaformHistoryGetNomDynaform_RSP');
                }
            </script>
            <?php
            G::RenderPage('publish', 'raw');

            $result->success = true;
            $result->msg = G::LoadTranslation('ID_CASE_REACTIVATED_SUCCESSFULLY', SYS_LANG, "success");
        } catch (Exception $e) {
            $result->success = false;
            $result->msg = $e->getMessage();
        }
    }

    public function dynaformViewFromHistory()
    {
        ?>
        <link rel="stylesheet" type="text/css" href="/css/<?php echo SYS_SKIN; ?>.css" />

        <script type="text/javascript">
            //!Code that simulated reload library javascript maborak
            var leimnud = {};
            leimnud.exec = "";
            leimnud.fix = {};
            leimnud.fix.memoryLeak = "";
            leimnud.browser = {};
            leimnud.browser.isIphone = "";
            leimnud.iphone = {};
            leimnud.iphone.make = function() {
            };
            function ajax_function(ajax_server, funcion, parameters, method)
            {
            }
            //!
        </script>

        <?php
        global $G_PUBLISH;

        $_POST["HISTORY_ID"] = $_REQUEST["HISTORY_ID"];
        $_POST["DYN_UID"] = $_REQUEST["DYN_UID"];

        $G_PUBLISH = new Publisher();
        $FieldsHistory = $_SESSION["HISTORY_DATA"];
        $Fields["APP_DATA"] = $FieldsHistory[$_POST["HISTORY_ID"]]; //isset($FieldsHistory[$_POST["HISTORY_ID"]])? $FieldsHistory[$_POST["HISTORY_ID"]] : "";
        $Fields["APP_DATA"]["__DYNAFORM_OPTIONS"]["PREVIOUS_STEP_LABEL"] = "";
        $Fields["APP_DATA"]["__DYNAFORM_OPTIONS"]["NEXT_STEP_LABEL"] = "";
        $Fields["APP_DATA"]["__DYNAFORM_OPTIONS"]["NEXT_STEP"] = "#";
        $Fields["APP_DATA"]["__DYNAFORM_OPTIONS"]["NEXT_ACTION"] = "return false;";

        $FieldsPmDynaform = $Fields;
        $FieldsPmDynaform["PRO_UID"] = $_SESSION['PROCESS'];
        $FieldsPmDynaform["CURRENT_DYNAFORM"] = $_REQUEST['DYN_UID'];
        $a = new PmDynaform($FieldsPmDynaform);
        if ($a->isResponsive()) {
            $a->printView();
        } else {
            $G_PUBLISH->AddContent("dynaform", "xmlform", $_SESSION["PROCESS"] . "/" . $_POST["DYN_UID"], "", $Fields["APP_DATA"], "", "", "view");
        } ?>

        <script type="text/javascript">

        <?php
        global $G_FORM; ?>

            function loadForm_<?php echo $G_FORM->id; ?>(parametro1) {
            }
        </script>

        <?php
        G::RenderPage("publish", "raw"); ?>

        <style type="text/css">
            html {
                color: black !important;
            }

            body {
                color: black !important;
            }
        </style>

        <script type="text/javascript">

        <?php
        global $G_FORM; ?>

            function loadForm_<?php echo $G_FORM->id; ?>(parametro1) {
            }
        </script>

        <?php
    }
}

$pluginRegistry = PluginRegistry::loadSingleton();
if ($pluginRegistry->existsTrigger(PM_GET_CASES_AJAX_LISTENER)) {
    $ajax = $pluginRegistry->executeTriggers(PM_GET_CASES_AJAX_LISTENER, null);
} else {
    $ajax = new Ajax();
}

if (!($ajax instanceof Ajax)) {
    $ajax = new Ajax();
}


$action = $_REQUEST['action'];

unset($_REQUEST['action']);

$ajax->$action($_REQUEST);
