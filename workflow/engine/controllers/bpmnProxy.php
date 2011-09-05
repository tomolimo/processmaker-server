<?php
/**
 * BpmnProxy Controller
 * @author Erik Amaru Ortiz <erik@colosa.com, aortiz.erik@gmail.com>
 */

class BpmnProxy extends HttpProxyController
{
  function openProcess($httpData)
  {
    G::LoadClass('xpdl');
    $processUID     = $httpData->PRO_UID;
    $oProcess       = new Xpdl();
    $fields         = $oProcess->serializeProcess($processUID);
    $oData          = unserialize($fields);
    $arrayTasks     = array();
    $countTasks     = 0;
    $countSubProcess= 0;
    $arrayEvents    = array();
    $arraySubProcess= array();
    $count          = 0;
    $countRoutes    = 0;
    $arrayRoutes    = array();
    $process        = array();
    $process        = $oProcess->createProcessPM($oData->process);
    foreach($oData->tasks as $id => $value) {
      if($value['TAS_TYPE'] == 'SUBPROCESS'){
        $arraySubProcess[$countSubProcess]['0'] = $value['TAS_UID'];
        $arraySubProcess[$countSubProcess]['1'] = $value['TAS_TITLE'];
        $arraySubProcess[$countSubProcess]['2'] = $value['TAS_POSX'];
        $arraySubProcess[$countSubProcess]['3'] = $value['TAS_POSY'];
        $countSubProcess = $countSubProcess + 1;
      } else {
        $arrayTasks[$countTasks]['0'] = $value['TAS_UID'];
        $arrayTasks[$countTasks]['1'] = $value['TAS_TITLE'];
        $arrayTasks[$countTasks]['2'] = $value['TAS_POSX'];
        $arrayTasks[$countTasks]['3'] = $value['TAS_POSY'];
        $arrayTasks[$countTasks]['4'] = $value['TAS_WIDTH'];
        $arrayTasks[$countTasks]['5'] = $value['TAS_HEIGHT'];
        $arrayTasks[$countTasks]['6'] = $value['TAS_BOUNDARY'];
        if($value['TAS_START'] == 'TRUE') {
          $arrayEvents[$count]['0']      = G::generateUniqueID();
          if($value['TAS_EVN_UID'] == '') {
            $arrayEvents[$count]['1']      = 'bpmnEventEmptyStart';
          } else {
            foreach($oData->event as $eventid => $val){
              if($val['EVN_UID'] == $value['TAS_EVN_UID']) {
                $arrayEvents[$count]['0'] = $val['EVN_UID'];
                $arrayEvents[$count]['1'] = $val['EVN_TYPE'];
                break;
              }
            }
          }
          $arrayEvents[$count]['2']      = $value['TAS_POSX']+68;
          $arrayEvents[$count]['3']      = $value['TAS_POSY']-50;
          $arrayEvents[$count]['4']      = $value['TAS_UID'];
          $arrayRoutes[$countRoutes]['0']= G::generateUniqueID();
          $arrayRoutes[$countRoutes]['1']= $arrayEvents[$count]['0'];
          $arrayRoutes[$countRoutes]['2']= $value['TAS_UID'];
          $arrayRoutes[$countRoutes]['3']= '1';
          $arrayRoutes[$countRoutes]['4']= '2';
          $count                         = $count+ 1;
          $countRoutes                   = $countRoutes+ 1;
        }
        $countTasks = $countTasks + 1;
      }
    }
    $lanes  = $oProcess->createLanesNewPM($oData->lanes);
    $fields = $oProcess->createTransitionsPM($oData->tasks,$oData->routes,$arrayEvents,$count,$arrayRoutes,$countRoutes);

    //Get Standalone Events and routes
    $countEvent = count($fields['EVENTS']);
    $countRoutes = count($fields['TRANSITION']);
    foreach($oData->event as $id => $value) {
      if($value['TAS_UID'] == '' && $value['EVN_TAS_UID_FROM'] != '' && $value['EVN_TAS_UID_TO'] != ''){  //Check for Intermediate Events
        $evn_uid = $value['EVN_UID'];
        $idTaskFrom = $value['EVN_TAS_UID_FROM'];
        $idTaskTo = $value['EVN_TAS_UID_TO'];

        $fields['EVENTS'][$countEvent]['0'] = $value['EVN_UID'];
        $fields['EVENTS'][$countEvent]['1'] = $value['EVN_TYPE'];
        $fields['EVENTS'][$countEvent]['2'] = $value['EVN_POSX'];
        $fields['EVENTS'][$countEvent]['3'] = $value['EVN_POSY'];
        $fields['EVENTS'][$countEvent]['4'] = $value['TAS_UID'];
        $countEvent               = $countEvent + 1;

        $fields['TRANSITION'][$countRoutes]['0']= G::generateUniqueID();
        $fields['TRANSITION'][$countRoutes]['1']= $idTaskFrom;
        $fields['TRANSITION'][$countRoutes]['2']= $evn_uid;
        $fields['TRANSITION'][$countRoutes]['3']= '1';
        $fields['TRANSITION'][$countRoutes]['4']= '2';
        $countRoutes              = $countRoutes + 1;

        $fields['TRANSITION'][$countRoutes]['0']= G::generateUniqueID();
        $fields['TRANSITION'][$countRoutes]['1']= $evn_uid;
        $fields['TRANSITION'][$countRoutes]['2']= $idTaskTo;
        $fields['TRANSITION'][$countRoutes]['3']= '2';
        $fields['TRANSITION'][$countRoutes]['4']= '1';
        $countRoutes              = $countRoutes + 1;
      }
      else if($value['TAS_UID'] == '' && $value['EVN_TAS_UID_TO'] != '' && ! preg_match("/Start/", $value['EVN_TYPE'])){
        $evn_uid = $value['EVN_UID'];
        $idTask = $value['EVN_TAS_UID_TO'];

        $fields['EVENTS'][$countEvent]['0'] = $value['EVN_UID'];
        $fields['EVENTS'][$countEvent]['1'] = $value['EVN_TYPE'];
        $fields['EVENTS'][$countEvent]['2'] = $value['EVN_POSX'];
        $fields['EVENTS'][$countEvent]['3'] = $value['EVN_POSY'];
        $fields['EVENTS'][$countEvent]['4'] = $value['TAS_UID'];
        $countEvent               = $countEvent + 1;

        $fields['TRANSITION'][$countRoutes]['0']= G::generateUniqueID();
        $fields['TRANSITION'][$countRoutes]['1']= $evn_uid;
        $fields['TRANSITION'][$countRoutes]['2']= $idTask;
        $fields['TRANSITION'][$countRoutes]['3']= '2';
        $fields['TRANSITION'][$countRoutes]['4']= '1';
        $countRoutes              = $countRoutes + 1;
      }
      else if($value['TAS_UID'] == '' && $value['EVN_TAS_UID_FROM'] != '' && ! preg_match("/End/", $value['EVN_TYPE'])){  //Check for Intermediate Events
        $evn_uid = $value['EVN_UID'];
        $idTask = $value['EVN_TAS_UID_FROM'];

        $fields['EVENTS'][$countEvent]['0'] = $value['EVN_UID'];
        $fields['EVENTS'][$countEvent]['1'] = $value['EVN_TYPE'];
        $fields['EVENTS'][$countEvent]['2'] = $value['EVN_POSX'];
        $fields['EVENTS'][$countEvent]['3'] = $value['EVN_POSY'];
        $fields['EVENTS'][$countEvent]['4'] = $value['TAS_UID'];
        $countEvent               = $countEvent + 1;

        $fields['TRANSITION'][$countRoutes]['0']= G::generateUniqueID();
        $fields['TRANSITION'][$countRoutes]['1']= $idTask;
        $fields['TRANSITION'][$countRoutes]['2']= $evn_uid;
        $fields['TRANSITION'][$countRoutes]['3']= '1';
        $fields['TRANSITION'][$countRoutes]['4']= '2';
        $countRoutes              = $countRoutes + 1;
      }
      else if($value['TAS_UID'] == '' && $value['EVN_TAS_UID_FROM'] == '' && $value['EVN_TAS_UID_TO'] == ''){
        $fields['EVENTS'][$countEvent]['0'] = $value['EVN_UID'];
        $fields['EVENTS'][$countEvent]['1'] = $value['EVN_TYPE'];
        $fields['EVENTS'][$countEvent]['2'] = $value['EVN_POSX'];
        $fields['EVENTS'][$countEvent]['3'] = $value['EVN_POSY'];
        $fields['EVENTS'][$countEvent]['4'] = $value['TAS_UID'];
        $countEvent               = $countEvent + 1;
      }
    }

    //Get all the standalone Gateway
    $countGateway = count($fields['GATEWAYS']);
    $countTransitions = count($fields['TRANSITION']);

    foreach($oData->gateways as $id => $value) {
      if($value['GAT_NEXT_TASK'] != '' && $value['TAS_UID'] != '' && $value['GAT_TYPE'] != ''){
        $fields['GATEWAYS'][$countGateway]['0']   = $value['GAT_UID'];
        $fields['GATEWAYS'][$countGateway]['1']   = $value['GAT_TYPE'];
        $fields['GATEWAYS'][$countGateway]['2']   = $value['GAT_X'];
        $fields['GATEWAYS'][$countGateway]['3']   = $value['GAT_Y'];
        $fields['GATEWAYS'][$countGateway]['4']   = $value['TAS_UID'];
        $fields['GATEWAYS'][$countGateway]['5']   = $value['TAS_UID'];
        $countGateway+=1;

        $fields['TRANSITION'][$countTransitions]['0'] = G::generateUniqueID();
        $fields['TRANSITION'][$countTransitions]['1'] = $value['TAS_UID'];
        $fields['TRANSITION'][$countTransitions]['2'] = $value['GAT_UID'];
        $fields['TRANSITION'][$countTransitions]['3'] = '1';
        $fields['TRANSITION'][$countTransitions]['4'] = '2';
        $countTransitions += 1;

        $fields['TRANSITION'][$countTransitions]['0'] = G::generateUniqueID();
        $fields['TRANSITION'][$countTransitions]['1'] = $value['GAT_UID'];
        $fields['TRANSITION'][$countTransitions]['2'] = $value['GAT_NEXT_TASK'];
        $fields['TRANSITION'][$countTransitions]['3'] = '2';
        $fields['TRANSITION'][$countTransitions]['4'] = '1';
        $countTransitions += 1;
      }
      //creating gateway and route from gateway to task i.e if target task is not NULL
      else if($value['GAT_NEXT_TASK'] != '' && $value['TAS_UID'] == ''){
        $fields['GATEWAYS'][$countGateway]['0']   = $value['GAT_UID'];
        $fields['GATEWAYS'][$countGateway]['1']   = $value['GAT_TYPE'];
        $fields['GATEWAYS'][$countGateway]['2']   = $value['GAT_X'];
        $fields['GATEWAYS'][$countGateway]['3']   = $value['GAT_Y'];
        $fields['GATEWAYS'][$countGateway]['4']   = $value['TAS_UID'];
        $fields['GATEWAYS'][$countGateway]['5']   = $value['GAT_NEXT_TASK'];

        $fields['TRANSITION'][$countTransitions]['0'] = G::generateUniqueID();
        $fields['TRANSITION'][$countTransitions]['1'] = $value['GAT_UID'];
        $fields['TRANSITION'][$countTransitions]['2'] = $value['GAT_NEXT_TASK'];
        $fields['TRANSITION'][$countTransitions]['3'] = '2';
        $fields['TRANSITION'][$countTransitions]['4'] = '1';
        $countGateway+=1;
        $countTransitions += 1;
      }
      //creating gateway and route from task to gateway i.e if source task is not NULL
      else if($value['GAT_NEXT_TASK'] == '' && $value['TAS_UID'] != ''){
        $fields['GATEWAYS'][$countGateway]['0']   = $value['GAT_UID'];
        $fields['GATEWAYS'][$countGateway]['1']   = $value['GAT_TYPE'];
        $fields['GATEWAYS'][$countGateway]['2']   = $value['GAT_X'];
        $fields['GATEWAYS'][$countGateway]['3']   = $value['GAT_Y'];
        $fields['GATEWAYS'][$countGateway]['4']   = $value['TAS_UID'];
        $fields['GATEWAYS'][$countGateway]['5']   = $value['GAT_NEXT_TASK'];

        $fields['TRANSITION'][$countTransitions]['0'] = G::generateUniqueID();
        $fields['TRANSITION'][$countTransitions]['1'] = $value['TAS_UID'];
        $fields['TRANSITION'][$countTransitions]['2'] = $value['GAT_UID'];
        $fields['TRANSITION'][$countTransitions]['3'] = '1';
        $fields['TRANSITION'][$countTransitions]['4'] = '2';
        $countGateway+=1;
        $countTransitions += 1;
      }
      else if($value['GAT_NEXT_TASK'] == '' && $value['TAS_UID'] == ''){
       $fields['GATEWAYS'][$countGateway]['0']   = $value['GAT_UID'];
       $fields['GATEWAYS'][$countGateway]['1']   = $value['GAT_TYPE'];
       $fields['GATEWAYS'][$countGateway]['2']   = $value['GAT_X'];
       $fields['GATEWAYS'][$countGateway]['3']   = $value['GAT_Y'];
       $countGateway += 1;
      }
    }

    //Create Annotation route
    foreach($oData->lanes as $id => $value)
    {
       if($value['SWI_NEXT_UID'] != '') {
         $fields['TRANSITION'][$countTransitions]['0'] = G::generateUniqueID();
         $fields['TRANSITION'][$countTransitions]['1'] = $value['SWI_NEXT_UID'];
         $fields['TRANSITION'][$countTransitions]['2'] = $value['SWI_UID'];
         $fields['TRANSITION'][$countTransitions]['3'] = '1';
         $fields['TRANSITION'][$countTransitions]['4'] = '2';
         $countTransitions += 1;
       }
    }

    //$subProcess         = $oProcess->createSubProcessesPM($oData->subProcess);
    $arrayEvents        = $fields['EVENTS'];
    $arrayGateways      = $fields['GATEWAYS'];
    $arrayRoutes        = $fields['TRANSITION'];

    $data['tasks'] = $arrayTasks;
    $data['subprocess'] = $arraySubProcess;
    $data['routes'] = $arrayRoutes;
    $data['events'] = $arrayEvents;
    $data['gateways'] = $arrayGateways;
    $data['process'] = $process;
    $data['annotations'] = $lanes;

    return $data;
  }
}









