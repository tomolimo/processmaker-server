<?php
/**
 * openProcess.php
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
 
  G::LoadClass('xpdl');
  $processUID     = $_REQUEST['PRO_UID'];
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
  foreach($oData->tasks as $id => $value)
   {
     if($value['TAS_TYPE'] == 'SUBPROCESS')
     {
       $arraySubProcess[$countSubProcess]['0'] = $value['TAS_UID'];
       $arraySubProcess[$countSubProcess]['1'] = $value['TAS_TITLE'];
       $arraySubProcess[$countSubProcess]['2'] = $value['TAS_POSX'];
       $arraySubProcess[$countSubProcess]['3'] = $value['TAS_POSY'];
       $countSubProcess = $countSubProcess + 1;
     }
     else
     {
       $arrayTasks[$countTasks]['0'] = $value['TAS_UID'];
       $arrayTasks[$countTasks]['1'] = $value['TAS_TITLE'];
       $arrayTasks[$countTasks]['2'] = $value['TAS_POSX'];
       $arrayTasks[$countTasks]['3'] = $value['TAS_POSY'];
       $arrayTasks[$countTasks]['4'] = $value['TAS_WIDTH'];
       $arrayTasks[$countTasks]['5'] = $value['TAS_HEIGHT'];
       $arrayTasks[$countTasks]['6'] = $value['TAS_BOUNDARY'];
       if($value['TAS_START'] == 'TRUE'){
         $arrayEvents[$count]['0']      = G::generateUniqueID();
         if($value['TAS_EVN_UID'] == '')
           {
             $arrayEvents[$count]['1']      = 'bpmnEventEmptyStart';
           }
         else
         {
             foreach($oData->event as $eventid => $val){
                 if($val['EVN_UID'] == $value['TAS_EVN_UID'])
                 {
                     $arrayEvents[$count]['0'] = $val['EVN_UID'];
                     $arrayEvents[$count]['1'] = $val['EVN_TYPE'];
                     break;
                 }
             }
        }
         $arrayEvents[$count]['2']      = $value['TAS_POSX']+68;
         $arrayEvents[$count]['3']      = $value['TAS_POSY']-50;
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
   //for Events
   foreach($oData->event as $id => $value)
   {
       if($value['TAS_UID'] == '' && $value['EVN_TAS_UID_FROM'] == '' && $value['EVN_TAS_UID_TO'] == ''){
         $arrayEvents[$count]['0'] = $value['EVN_UID'];
         $arrayEvents[$count]['1'] = $value['EVN_TYPE'];
         $arrayEvents[$count]['2'] = $value['EVN_POSX'];
         $arrayEvents[$count]['3'] = $value['EVN_POSY'];
         $count                    = $count+ 1;
       }
   }
   $lanes              = $oProcess->createLanesNewPM($oData->lanes);
   $fields             = $oProcess->createTransitionsPM($oData->tasks,$oData->routes,$arrayEvents,$count,$arrayRoutes,$countRoutes);
   //$subProcess         = $oProcess->createSubProcessesPM($oData->subProcess);
   $arrayEvents        = $fields['EVENTS'];
   $arrayGateways      = $fields['GATEWAYS'];
   $arrayRoutes        = $fields['TRANSITION'];
   $result['TASKS']    = $arrayTasks;
   $result['ROUTES']   = $arrayRoutes;
   $result['EVENTS']   = $arrayEvents;
   $result['GATEWAYS'] = $arrayGateways;
   $aTasks      = json_encode($arrayTasks);
   $aSubProcess = json_encode($arraySubProcess);
   $aRoutes     = json_encode($arrayRoutes);
   $aEvents     = json_encode($arrayEvents);
   $aGateways   = json_encode($arrayGateways);
   $aProcess    = json_encode($process);
   $aLanes      = json_encode($lanes);
   echo "tasks:$aTasks|gateways:$aGateways|events:$aEvents|annotations:$aLanes|process:$aProcess|subprocess:$aSubProcess|routes:$aRoutes";
?>