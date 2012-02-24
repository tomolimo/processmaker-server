<?php
/**
 * cases/ajaxListener.php Ajax Listener for Cases rpc requests
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 *
 */
 
 /**
  * @author Erik Amaru Ortiz <erik@colosa.com>
  * @date Jan 3th, 2010
  */

require_once 'classes/model/Application.php';
require_once 'classes/model/Users.php';
require_once 'classes/model/AppThread.php';
require_once 'classes/model/AppDelay.php';
require_once 'classes/model/Process.php';
require_once 'classes/model/Task.php';
G::LoadClass('case');

$action = $_REQUEST['action'];
unset($_REQUEST['action']);
$ajax = new Ajax();
$ajax->$action($_REQUEST);

class Ajax
{
  function getCaseMenu($params)
  {
  
    G::LoadClass("configuration");
    G::LoadClass("case");
    global $G_TMP_MENU;
    global $sStatus;
    $sStatus = $params['app_status'];
    $oCase = new Cases();
    $conf = new Configurations;
    $oMenu = new Menu();
    $oMenu->load('caseOptions');
    
    $menuOptions = Array();
    foreach( $oMenu->Options as $i => $action ) {
      $option = Array(
        'id'     => $oMenu->Id[$i],
        'label'  => $oMenu->Labels[$i],
        'action' => $action       
      );
      
      switch($option['id']) {
        case 'STEPS':
          $option['options'] = Array();
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

  
  function steps()
  {
    G::LoadClass('applications');
    $applications = new Applications();

    $proUid = isset($_SESSION['PROCESS'])     ? $_SESSION['PROCESS']     : '';
    $tasUid = isset($_SESSION['TASK'])        ? $_SESSION['TASK']        : '';
    $appUid = isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION'] : '';
    $index  = isset($_SESSION['INDEX'])       ? $_SESSION['INDEX']       : '';
    $steps  = $applications->getSteps($appUid, $index, $tasUid, $proUid);
    $list   = array();
    
    foreach ($steps as $step) {
      $item['id']         = $item['idtodraw'] = $step['id'];
      $item['draggable']  = false;
      $item['leaf']       = true;
      $item['hrefTarget'] = 'casesSubFrame';
      $item['text']       = $step['title'];
      $item['url']        = '../' . $step['url'];

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

  function getInformationOptions()
  {
    $options = Array();
    $options[] = Array('text' => G::LoadTranslation('ID_PROCESS_MAP'), 'fn'=>'processMap');
    $options[] = Array('text' => G::LoadTranslation('ID_PROCESS_INFORMATION'), 'fn'=>'processInformation');
    $options[] = Array('text' => G::LoadTranslation('ID_TASK_INFORMATION'), 'fn'=>'taskInformation');
    $options[] = Array('text' => G::LoadTranslation('ID_CASE_HISTORY'), 'fn'=>'caseHistory');
    $options[] = Array('text' => G::LoadTranslation('ID_HISTORY_MESSAGE_CASE'), 'fn'=>'messageHistory');
    $options[] = Array('text' => G::LoadTranslation('ID_DYNAFORMS'), 'fn'=>'dynaformHistory');
    $options[] = Array('text' => G::LoadTranslation('ID_UPLOADED_DOCUMENTS'), 'fn'=>'uploadedDocuments');
    $options[] = Array('text' => G::LoadTranslation('ID_GENERATED_DOCUMENTS'), 'fn'=>'generatedDocuments');

    return $options;
  }

  function getActionOptions()
  {
    $APP_UID = $_SESSION['APPLICATION'];

    $c = new Criteria('workflow');
    $c->clearSelectColumns();
    $c->addSelectColumn( AppThreadPeer::APP_THREAD_PARENT );
    $c->add(AppThreadPeer::APP_UID, $APP_UID );
    $c->add(AppThreadPeer::APP_THREAD_STATUS , 'OPEN' );
    $cant = AppThreadPeer::doCount($c);

    $oCase = new Cases();
    $aFields = $oCase->loadCase( $_SESSION['APPLICATION'], $_SESSION['INDEX'] );

    GLOBAL $RBAC;

    $options = Array();

    switch($aFields['APP_STATUS'])
    {
      case 'DRAFT':
          if( ! AppDelay::isPaused($_SESSION['APPLICATION'], $_SESSION['INDEX']) ) {
            $options[] = Array('text'=>G::LoadTranslation('ID_PAUSED_CASE'), 'fn'=>'setUnpauseCaseDate');
          } else {
            $options[] = Array('text'=>G::LoadTranslation('ID_UNPAUSE'), 'fn'=>'unpauseCase');
          }

          $options[] = Array('text'=>G::LoadTranslation('ID_DELETE'), 'fn'=>'deleteCase');

          if( $RBAC->userCanAccess('PM_REASSIGNCASE')==1 ) {
            $options[] = Array('text'=>G::LoadTranslation('ID_REASSIGN'), 'fn'=>'getUsersToReassign');
          }
      break;

      case 'TO_DO':
        if( ! AppDelay::isPaused($_SESSION['APPLICATION'], $_SESSION['INDEX']) ) {
          $options[] = Array('text'=>G::LoadTranslation('ID_PAUSED_CASE'), 'fn'=>'setUnpauseCaseDate');
          if ($cant == 1) {                        
            if($RBAC->userCanAccess('PM_CANCELCASE')==1) 
              $options[] = Array('text'=>G::LoadTranslation('ID_CANCEL'), 'fn'=>'cancelCase');
            else  
              $options[] = Array('text'=>G::LoadTranslation('ID_CANCEL'), 'fn'=>'cancelCase', 'hide'=>'hiden');            	            	            
          }
        } 
        else {
          $options[] = Array('text'=>G::LoadTranslation('ID_UNPAUSE'), 'fn'=>'unpauseCase');
        }
        if($RBAC->userCanAccess('PM_REASSIGNCASE')==1) {
          $options[] = Array('text'=>G::LoadTranslation('ID_REASSIGN'), 'fn'=>'getUsersToReassign');
        }
      break;

      case 'CANCELLED':
        $options[] = Array('text'=>G::LoadTranslation('ID_REACTIVATE'), 'fn'=>'reactivateCase');
      break;
    }
    
    if( $_SESSION['TASK'] != '-1' ) {
      $oTask = new Task();
      $aTask = $oTask->load($_SESSION['TASK']);
      if ($aTask['TAS_TYPE'] == 'ADHOC') {
        $options[] = Array('text'=>G::LoadTranslation('ID_ADHOC_ASSIGNMENT'), 'fn'=>'adhocAssignmentUsers');
      }
    }
    return $options;
  }

  function processMap()
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
    $oHeadPublisher = & headPublisher::getSingleton();


    //$oHeadPublisher->addScriptfile('/jscore/processmap/core/processmap.js');
    $oHeadPublisher->addScriptCode('
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
        size  :{w:160,h:140},
        position:{x:((document.body.clientWidth * 95) / 100) - ((document.body.clientWidth * 95) / 100 - (((document.body.clientWidth * 95) / 100) - 160)),y:45,center:false},
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

  function getProcessInformation()
  {
    $process = new Process();
    $processData = $process->load($_SESSION['PROCESS']);
    require_once 'classes/model/Users.php';
    $user = new Users();
    try {
      $userData = $user->load($processData['PRO_CREATE_USER']);
      $processData['PRO_AUTHOR'] = $userData['USR_FIRSTNAME'] . ' ' . $userData['USR_LASTNAME'];
    } catch ( Exception $oError ) {
      $processData['PRO_AUTHOR'] = '(USER DELETED)';
    }

    $processData['PRO_CREATE_DATE'] = date('F j, Y', strtotime($processData['PRO_CREATE_DATE']));

    print(G::json_encode($processData));
  }
  
  function getTaskInformation()
  {
    $task = new Task();
    if($_SESSION['TASK']=='-1')
      $_SESSION['TASK'] = $_SESSION['CURRENT_TASK'];
    $taskData = $task->getDelegatedTaskData($_SESSION['TASK'], $_SESSION['APPLICATION'], $_SESSION['INDEX']);
    
    print(G::json_encode($taskData));
  }
  
  function caseHistory()
  {
    global $G_PUBLISH;
		$c = Cases::getTransferHistoryCriteria($_SESSION['APPLICATION']);
		$G_PUBLISH = new Publisher();
		$G_PUBLISH->AddContent('propeltable', 'paged-table', 'cases/cases_TransferHistory', $c, array());
		G::RenderPage('publish', 'blank');
  }
  
  function messageHistory()
  {
    global $G_PUBLISH;
    $oCase = new Cases();
    $G_PUBLISH = new Publisher();
    $G_PUBLISH->AddContent('propeltable', 'paged-table', 'cases/cases_Messages', $oCase->getHistoryMessagesTracker($_SESSION['APPLICATION']));
    G::RenderPage('publish', 'blank'); 
  }
  
  function dynaformHistory()
  {
    global $G_PUBLISH;
    $oCase = new Cases();
    $G_PUBLISH = new Publisher();
    $G_PUBLISH->AddContent('propeltable', 'paged-table', 'cases/cases_AllDynaformsList', $oCase->getallDynaformsCriteria($_SESSION['PROCESS'], $_SESSION['APPLICATION'], $_SESSION['TASK'], $_SESSION['USER_LOGGED']));
    G::RenderPage('publish', 'blank');
  }
  
  function uploadedDocuments()
  {
    global $G_PUBLISH;    
    $oCase = new Cases();
    $G_PUBLISH = new Publisher();
    $G_PUBLISH->AddContent('propeltable', 'paged-table', 'cases/cases_AllInputdocsList', $oCase->getAllUploadedDocumentsCriteria($_SESSION['PROCESS'], $_SESSION['APPLICATION'], $_SESSION['TASK'], $_SESSION['USER_LOGGED']));
    G::RenderPage('publish', 'blank');
  }
  
  function uploadedDocumentsSummary()
  {
    global $G_PUBLISH;    
    $oCase = new Cases();
    $G_PUBLISH = new Publisher();
    $G_PUBLISH->AddContent('propeltable', 'paged-table', 'cases/cases_AllInputdocsList_Summary', $oCase->getAllUploadedDocumentsCriteria($_SESSION['PROCESS'], $_SESSION['APPLICATION'], $_SESSION['TASK'], $_SESSION['USER_LOGGED']));
    G::RenderPage('publish', 'blank');
  }
  
  function generatedDocuments()
  {
    global $G_PUBLISH;
    $oCase = new Cases();
    $G_PUBLISH = new Publisher();
    $G_PUBLISH->AddContent('propeltable', 'paged-table', 'cases/cases_AllOutputdocsList', $oCase->getAllGeneratedDocumentsCriteria($_SESSION['PROCESS'], $_SESSION['APPLICATION'], $_SESSION['TASK'], $_SESSION['USER_LOGGED']));
    G::RenderPage('publish', 'blank');
  }
    function generatedDocumentsSummary()
  {
    global $G_PUBLISH;
    $oCase = new Cases();
    $G_PUBLISH = new Publisher();
    $G_PUBLISH->AddContent('propeltable', 'paged-table', 'cases/cases_AllOutputdocsList_Summary', $oCase->getAllGeneratedDocumentsCriteria($_SESSION['PROCESS'], $_SESSION['APPLICATION'], $_SESSION['TASK'], $_SESSION['USER_LOGGED']));
    G::RenderPage('publish', 'blank');
  }
  
  function cancelCase()
  {
    $oCase = new Cases();
    $multiple = false;

    if( isset($_POST['APP_UID']) && isset($_POST['DEL_INDEX']) ) {
      $APP_UID   = $_POST['APP_UID'];
      $DEL_INDEX = $_POST['DEL_INDEX'];

      $appUids    = explode(',', $APP_UID);
      $delIndexes = explode(',', $DEL_INDEX);
      if( count($appUids) > 1 && count($delIndexes) > 1 )
        $multiple = true;
    } else if( isset($_POST['sApplicationUID']) && isset($_POST['iIndex']) ){
      $APP_UID   = $_POST['sApplicationUID'];
      $DEL_INDEX = $_POST['iIndex'];
    } else {
      $APP_UID   = $_SESSION['APPLICATION'];
      $DEL_INDEX = $_SESSION['INDEX'];
    }

    if( $multiple ) {
      foreach($appUids as $i=>$appUid)
        $oCase->cancelCase($appUid, $delIndexes[$i], $_SESSION['USER_LOGGED']);
    } else
      $oCase->cancelCase($APP_UID, $DEL_INDEX, $_SESSION['USER_LOGGED']);
  }
  
  function getUsersToReassign()
  {
    $case = new Cases();
    $result->data = $case->getUsersToReassign($_SESSION['TASK'], $_SESSION['USER_LOGGED']);
    
    print G::json_encode($result);
    
  }
  
  function reassignCase()
  {
    $cases = new Cases();
    $user = new Users();
    $app = new Application();
    
    $TO_USR_UID = $_POST['USR_UID'];
    try{
      $cases->reassignCase($_SESSION['APPLICATION'], $_SESSION['INDEX'], $_SESSION['USER_LOGGED'], $TO_USR_UID);
      $caseData = $app->load($_SESSION['APPLICATION']);
      $userData = $user->load($TO_USR_UID);
      //print_r($caseData);
      $data['APP_NUMBER'] = $caseData['APP_NUMBER'];
      $data['USER'] = $userData['USR_LASTNAME'].' '. $userData['USR_FIRSTNAME']; //TODO change with the farmated username from environment conf
      $result->status = 0;
      $result->msg = G::LoadTranslation('ID_REASSIGNMENT_SUCCESS', SYS_LANG, $data);
    } catch(Exception $e){
      $result->status = 1;
      $result->msg = $e->getMessage();
    }
    
    print G::json_encode($result);
  }
  
  
  function pauseCase()
  {
    try{
      $unpauseDate = $_REQUEST['unpauseDate'];
      $oCase = new Cases();
      if( isset($_POST['APP_UID']) && isset($_POST['DEL_INDEX']) ) {
        $APP_UID   = $_POST['APP_UID'];
        $DEL_INDEX = $_POST['DEL_INDEX'];
      } else if( isset($_POST['sApplicationUID']) && isset($_POST['iIndex']) ){
        $APP_UID   = $_POST['sApplicationUID'];
        $DEL_INDEX = $_POST['iIndex'];
      } else {
        $APP_UID   = $_SESSION['APPLICATION'];
        $DEL_INDEX = $_SESSION['INDEX'];
      }
      
      $oCase->pauseCase($APP_UID, $DEL_INDEX, $_SESSION['USER_LOGGED'], $unpauseDate);
      $app = new Application();
      $caseData = $app->load($APP_UID);
      $data['APP_NUMBER'] = $caseData['APP_NUMBER'];
      $data['UNPAUSE_DATE'] = $unpauseDate;
      
      $result->success = true;
      $result->msg = G::LoadTranslation('ID_CASE_PAUSED_SUCCESSFULLY', SYS_LANG, $data);
    } catch(Exception $e) {
      $result->success = false;
      $result->msg = $e->getMessage();
    }
    echo G::json_encode($result);
  }
  
  function unpauseCase()
  {
    try{
      $applicationUID = (isset($_POST['APP_UID'])) ? $_POST['APP_UID'] : $_SESSION['APPLICATION'];
      $delIndex = (isset($_POST['DEL_INDEX'])) ? $_POST['DEL_INDEX'] : $_SESSION['INDEX'];
      $oCase = new Cases();
      $oCase->unpauseCase($applicationUID, $delIndex, $_SESSION['USER_LOGGED']);
      
      $app = new Application();
      $caseData = $app->load($applicationUID);
      $data['APP_NUMBER'] = $caseData['APP_NUMBER'];
      
      $result->success = true;
      $result->msg = G::LoadTranslation('ID_CASE_UNPAUSED_SUCCESSFULLY', SYS_LANG, $data);
    } catch(Exception $e) {
      $result->success = false;
      $result->msg = $e->getMessage();
    }
    
    print G::json_encode($result);
  }

  function deleteCase()
  {
    try{
      $applicationUID = (isset($_POST['APP_UID'])) ? $_POST['APP_UID'] : $_SESSION['APPLICATION'];
      $app = new Application();
      $caseData = $app->load($applicationUID);
      $data['APP_NUMBER'] = $caseData['APP_NUMBER'];
      
      $oCase = new Cases();
      $oCase->removeCase($applicationUID);
      
      $result->success = true;
      $result->msg = G::LoadTranslation('ID_CASE_DELETED_SUCCESSFULLY', SYS_LANG, $data);
    } catch(Exception $e) {
      $result->success = false;
      $result->msg = $e->getMessage();
    }
    
    print G::json_encode($result);
  }
  
  function reactivateCase()
  {
    try{
      $applicationUID = (isset($_POST['APP_UID'])) ? $_POST['APP_UID'] : $_SESSION['APPLICATION'];
      $delIndex = (isset($_POST['DEL_INDEX'])) ? $_POST['DEL_INDEX'] : $_SESSION['INDEX'];
      $app = new Application();
      $caseData = $app->load($applicationUID);
      $data['APP_NUMBER'] = $caseData['APP_NUMBER'];
      
      $oCase = new Cases();
      $oCase->reactivateCase($applicationUID, $delIndex, $_SESSION['USER_LOGGED']);
      
      $result->success = true;
      $result->msg = G::LoadTranslation('ID_CASE_REACTIVATED_SUCCESSFULLY', SYS_LANG, $data);
    } catch(Exception $e) {
      $result->success = false;
      $result->msg = $e->getMessage();
    }
    
    print G::json_encode($result);
  }
  
}
