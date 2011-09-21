<?php
/**
 * App controller
 * @author Erik Amaru Ortiz <erik@colosa.com, aortiz.erik@gmail.com>
 * @inherits Controller
 * @access public
 */

class AppProxy extends HttpProxyController
{
  function requestOpenSummary($httpData)
  {
    global $RBAC;
    $this->success = true;
    $this->dynUid = '';

    switch ($RBAC->userCanAccess('PM_CASES')) {
      case -2:
        throw new Exception(G::LoadTranslation('ID_USER_HAVENT_RIGHTS_SYSTEM'));
      break;
      case -1:
        throw new Exception(G::LoadTranslation('ID_USER_HAVENT_RIGHTS_PAGE'));
      break;
    }

    G::LoadClass('case');
    $case = new Cases();

    if ($RBAC->userCanAccess('PM_ALLCASES') < 0 && $case->userParticipatedInCase($httpData->appUid, $_SESSION['USER_LOGGED']) == 0) {
      throw new Exception(G::LoadTranslation('ID_NO_PERMISSION_NO_PARTICIPATED'));
    }

    $applicationFields = $case->loadCase($httpData->appUid, $httpData->delIndex);
    $process = new Process();
    $processData = $process->load($applicationFields['PRO_UID']);
    
    if (isset($processData['PRO_DYNAFORMS']['PROCESS'])) {
      $this->dynUid = $processData['PRO_DYNAFORMS']['PROCESS'];
    }

    $_SESSION['_applicationFields'] = $applicationFields;
    $_SESSION['_processData'] = $processData;
  }

  function getSummary($httpData)
  {
    $labels = array();
    $form = new Form('cases/cases_Resume', PATH_XMLFORM, SYS_LANG);
    G::LoadClass('case');
    $case = new Cases();

    foreach($form->fields as $fieldName => $field) {
      $labels[$fieldName] = $field->label;
    }

    if (isset($_SESSION['_applicationFields']) && $_SESSION['_processData']) {
      $applicationFields = $_SESSION['_applicationFields'];
      unset($_SESSION['_applicationFields']);
      $processData       = $_SESSION['_processData'];
      unset($_SESSION['_processData']);
    }
    else {
      $applicationFields = $case->loadCase($httpData->appUid, $httpData->delIndex);
      $process = new Process();
      $processData = $process->load($applicationFields['PRO_UID']);
    }

    $data = array();
    $task = new Task();
    $taskData = $task->load($applicationFields['TAS_UID']);
    $currentUser = $applicationFields['CURRENT_USER'] != '' ? $applicationFields['CURRENT_USER'] : '[' . G::LoadTranslation('ID_UNASSIGNED') . ']';
    
    $data[] = array('label'=>$labels['PRO_TITLE'] ,      'value' => $processData['PRO_TITLE'],        'section'=>$labels['TITLE1']);
    $data[] = array('label'=>$labels['TITLE'] ,          'value' => $applicationFields['TITLE'],      'section'=>$labels['TITLE1']);
    $data[] = array('label'=>$labels['APP_NUMBER'] ,     'value' => $applicationFields['APP_NUMBER'], 'section'=>$labels['TITLE1']);
    $data[] = array('label'=>$labels['STATUS'] ,         'value' => $applicationFields['STATUS'],     'section'=>$labels['TITLE1']);
    $data[] = array('label'=>$labels['APP_UID'] ,        'value' => $applicationFields['APP_UID'],    'section'=>$labels['TITLE1']);
    $data[] = array('label'=>$labels['CREATOR'] ,        'value' => $applicationFields['CREATOR'],    'section'=>$labels['TITLE1']);
    $data[] = array('label'=>$labels['CREATE_DATE'] ,    'value' => $applicationFields['CREATE_DATE'],'section'=>$labels['TITLE1']);
    $data[] = array('label'=>$labels['UPDATE_DATE'] ,    'value' => $applicationFields['UPDATE_DATE'],'section'=>$labels['TITLE1']);
    
    $data[] = array('label'=>$labels['TAS_TITLE'] ,         'value' => $taskData['TAS_TITLE'],                 'section'=>$labels['TITLE2']);
    $data[] = array('label'=>$labels['CURRENT_USER'] ,      'value' => $currentUser,                           'section'=>$labels['TITLE2']);
    $data[] = array('label'=>$labels['DEL_DELEGATE_DATE'] , 'value' => $applicationFields['DEL_DELEGATE_DATE'],'section'=>$labels['TITLE2']);
    $data[] = array('label'=>$labels['DEL_INIT_DATE'] ,     'value' => $applicationFields['DEL_INIT_DATE'],    'section'=>$labels['TITLE2']);
    $data[] = array('label'=>$labels['DEL_TASK_DUE_DATE'] , 'value' => $applicationFields['DEL_TASK_DUE_DATE'],'section'=>$labels['TITLE2']);
    $data[] = array('label'=>$labels['DEL_FINISH_DATE'] ,   'value' => $applicationFields['DEL_FINISH_DATE'],  'section'=>$labels['TITLE2']);
    //$data[] = array('label'=>$labels['DYN_UID'] ,           'value' => $processData['PRO_DYNAFORMS']['PROCESS'];, 'section'=>$labels['DYN_UID']);

    return $data;
  }


}