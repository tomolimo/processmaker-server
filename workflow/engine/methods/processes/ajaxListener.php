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
  * @date Jan 10th, 2010
  */

$action = $_REQUEST['action'];
unset($_REQUEST['action']);

$ajax = new Ajax();
$ajax->$action($_REQUEST);

class Ajax
{
  function categoriesList()
  {
    require_once "classes/model/ProcessCategory.php";
    
    $processCategory = new ProcessCategory;
    $defaultOption = Array();
    $defaultOption[] = Array('CATEGORY_UID'=>'<reset>', 'CATEGORY_NAME'=>G::LoadTranslation('ID_ALL'));
    $defaultOption[] = Array('CATEGORY_UID'=>'', 'CATEGORY_NAME'=>G::LoadTranslation('ID_PROCESS_NO_CATEGORY'));
    
    $response->rows = array_merge($defaultOption, $processCategory->getAll('array'));
    
    echo G::json_encode($response);
  }
  
  function processCategories()
  {
    require_once "classes/model/ProcessCategory.php";
    
    $processCategory = new ProcessCategory;
    $defaultOption = Array();
    $defaultOption[] = Array('CATEGORY_UID'=>'', 'CATEGORY_NAME'=>G::LoadTranslation('ID_PROCESS_NO_CATEGORY'));
    
    $response->rows = array_merge($defaultOption, $processCategory->getAll('array'));
    
    echo G::json_encode($response); 
  }
  
  function saveProcess()
  {
    try{
      require_once 'classes/model/Task.php';
      G::LoadClass('processMap');
      $oProcessMap = new ProcessMap();
      
      if( ! isset($_POST['PRO_UID']) ) {
      
        if( Process::existsByProTitle($_POST['PRO_TITLE']) ) {
          $result = array(
            'success' => false,  
            'msg' => 'Process Save Error',  
            'errors' => array(
              'PRO_TITLE' => G::LoadTranslation('ID_PROCESSTITLE_ALREADY_EXISTS', SYS_LANG, $_POST)
            )
          );
          print G::json_encode($result);
          exit(0);
        }
      
        $processData['USR_UID']         = $_SESSION['USER_LOGGED'];
        $processData['PRO_TITLE']       = $_POST['PRO_TITLE'];
        $processData['PRO_DESCRIPTION'] = $_POST['PRO_DESCRIPTION'];
        $processData['PRO_CATEGORY']    = $_POST['PRO_CATEGORY'];
        
        $sProUid = $oProcessMap->createProcess($processData);
        
        //call plugins
        $oData['PRO_UID']      = $sProUid;
        $oData['PRO_TEMPLATE'] = ( isset($_POST['PRO_TEMPLATE']) && $_POST['PRO_TEMPLATE'] != '' ) ? $_POST['form']['PRO_TEMPLATE'] : '';
        $oData['PROCESSMAP']   = $oProcessMap;
    
        $oPluginRegistry =& PMPluginRegistry::getSingleton();
        $oPluginRegistry->executeTriggers ( PM_NEW_PROCESS_SAVE , $oData );
        
      } else {
        //$oProcessMap->updateProcess($_POST['form']);
        $sProUid = $_POST['PRO_UID'];
      }
      
      //Save Calendar ID for this process
      if( isset($_POST['PRO_CALENDAR']) ){
        G::LoadClass("calendar");
        $calendarObj=new Calendar();
        $calendarObj->assignCalendarTo($sProUid, $_POST['PRO_CALENDAR'], 'PROCESS');
      }
      
      $result->success = true;
      $result->PRO_UID = $sProUid;
      $result->msg = G::LoadTranslation('ID_CREATE_PROCESS_SUCCESS');
    } catch(Exception $e){
      $result->success = false;
      $result->msg = $e->getMessage();
    }
    
    print G::json_encode($result);
  }
  
  function changeStatus()
  {
    $ids = explode(',', $_REQUEST['UIDS']);

    G::LoadClass('processes');
    $oProcess = new Processes();
    if( count($ids) > 0 ){
      foreach($ids as $id)
        $oProcess->changeStatus($id);
    }
  }
  
  function changeDebugMode()
  {
    $ids = explode(',', $_REQUEST['UIDS']);
    
    G::LoadClass('processes');
    $oProcess = new Processes();
    if( count($ids) > 0 ){
      foreach($ids as $id)
        $oProcess->changeDebugMode($id);
    }
  }

  function getUsers($params)
  {
    require_once 'classes/model/Users.php';
    G::LoadClass('configuration');
    $conf = new Configurations;
    
    $search = isset($params['search']) ? $params['search']: null;    
    $users = Users::getAll($params['start'], $params['limit'], $search);
    
    foreach($users->data as $i=>$user){
      $users->data[$i]['USER'] = $conf->getEnvSetting(
        'format',
        Array(
          'userName'=>$user['USR_USERNAME'],
          'firstName'=>$user['USR_FIRSTNAME'],
          'lastName'=>$user['USR_LASTNAME']
        )
      );
    }
    print G::json_encode($users);
  }

  function getGroups($params)
  {
    require_once 'classes/model/Groupwf.php';
    $search = isset($params['search']) ? $params['search']: null;
    $groups = Groupwf::getAll($params['start'], $params['limit'], $search);

    print G::json_encode($groups);
  }

  function assignUsersTask($param)
  {
    try{
      require_once 'classes/model/TaskUser.php';      
      $oTaskUser = new TaskUser();
      $UIDS = explode(',', $param['UIDS']);
      $TU_TYPE = 1;

      foreach( $UIDS as $UID ) {
        if ($_POST['TU_RELATION'] == 1 )
          $oTaskUser->create(array('TAS_UID' => $param['TAS_UID'], 'USR_UID' => $UID, 'TU_TYPE' => $TU_TYPE, 'TU_RELATION' => 1));
        else
          $oTaskUser->create(array('TAS_UID' => $param['TAS_UID'], 'USR_UID' => $UID, 'TU_TYPE' => $TU_TYPE, 'TU_RELATION' => 2));
      }

      $result->success = true;
      $result->msg = '';
    } catch(Exception $e){
      $result->success = false;
      $result->msg = $e->getMessage();
    }

    print G::json_encode($result);
  }

  function removeUsersTask($param)
  {
    try{
      require_once 'classes/model/TaskUser.php';
      $oTaskUser = new TaskUser();
      $USR_UIDS = explode(',', $param['USR_UID']);
      $TU_RELATIONS = explode(',', $param['TU_RELATION']);
      $TU_TYPE = 1;

      foreach($USR_UIDS as $i=>$USR_UID) {
        if ($TU_RELATIONS[$i] == 1 ){
          
          $oTaskUser->remove($param['TAS_UID'], $USR_UID, $TU_TYPE, 1);
          
        } else {
          $oTaskUser->remove($param['TAS_UID'], $USR_UID, $TU_TYPE, 2);
        }
      }

      $result->success = true;
      $result->msg = '';
    } catch(Exception $e){
      $result->success = false;
      $result->msg = "{$TU_RELATIONS[$i]} -  {$param['TAS_UID']}, {$USR_UIDS[$i]}, $TU_TYPE, 1  --> " . $e->getMessage();
    }

    print G::json_encode($result);
  }

  function getUsersTask($param)
  {
    require_once 'classes/model/TaskUser.php';
    G::LoadClass('configuration');
    $usersTaskList = Array();
    $task = new TaskUser;
    $conf = new Configurations;
    $TU_TYPE = 1;
    
    $usersTask = $task->getUsersTask($param['TAS_UID'], $TU_TYPE);
    
    foreach($usersTask->data as $userTask) {
      $usersTaskListItem['TAS_UID'] = $userTask['TAS_UID'];
      if( $userTask['TU_RELATION'] == 1 )
        $usersTaskListItem['NAME'] = $conf->getEnvSetting(
          'format',
          Array(
            'userName'=>$userTask['USR_USERNAME'],
            'firstName'=>$userTask['USR_FIRSTNAME'],
            'lastName'=>$userTask['USR_LASTNAME']
          )
        );
      else
        $usersTaskListItem['NAME'] = $userTask['GRP_TITLE'];

      $usersTaskListItem['TU_RELATION'] = $userTask['TU_RELATION'];
      $usersTaskListItem['USR_UID']     = $userTask['USR_UID'];

      $usersTaskList[] = $usersTaskListItem;
    }
    
    $result->data = $usersTaskList;
    $result->totalCount = $usersTask->totalCount;

    print G::json_encode($result);
  }

  function getProcessDetail($param){
    require_once 'classes/model/Process.php';
    
    $PRO_UID = $param['PRO_UID'];

    G::loadClass('tasks');
    $tasks = new Tasks();
    $process = ProcessPeer::retrieveByPk($PRO_UID);
    
    $tasksList = $tasks->getAllTasks($PRO_UID);

    
    $rootNode->id = $process->getProUid();
    $rootNode->text = $process->getProTitle();
    $rootNode->leaf = false;
    $rootNode->expanded =true;
    foreach($tasksList as $task) {
      $node = new stdClass;
      $node->id = $task['TAS_UID'];
      $node->text = $task['TAS_TITLE'];
      $node->leaf = true;
      $rootNode->children[] = $node;
    }

    $treeDetail[] = $rootNode;
    print G::json_encode($treeDetail);
  }
}


