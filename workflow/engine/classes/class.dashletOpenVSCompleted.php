<?php

require_once 'interfaces/dashletInterface.php';

class dashletOpenVSCompleted implements DashletInterface {

  function setup($config) {
/*
Array
(
    [DAS_UID] => 00000000000000000000000000000001
    [DAS_CLASS] => dashletOpenVSCompleted
    [DAS_TITLE] => Open Cases VS Complete Cases
    [DAS_DESCRIPTION] => Open Cases VS Complete Cases
    [DAS_VERSION] => 1.0
    [DAS_CREATE_DATE] => 2011-10-28 00:00:00
    [DAS_UPDATE_DATE] => 2011-10-28 00:00:00
    [DAS_STATUS] => 1
    [DAS_INS_UID] => 00000000000000000000000000000001
    [DAS_INS_TYPE] => OPEN_CASES
    [DAS_INS_CONTEXT_TIME] => MONTH
    [DAS_INS_START_DATE] => 
    [DAS_INS_END_DATE] => 
    [DAS_INS_OWNER_TYPE] => DEPARTMENT
    [DAS_INS_OWNER_UID] => 2502663244e6f5e1e3c2254024148892
    [DAS_INS_PROCESSES] => 
    [DAS_INS_TASKS] => 
    [DAS_INS_ADDITIONAL_PROPERTIES] => 
    [DAS_INS_CREATE_DATE] => 2011-10-28 00:00:00
    [DAS_INS_UPDATE_DATE] => 2011-10-28 00:00:00
    [DAS_INS_STATUS] => 1
)
*/
    
    //$this->w = $config['w'];
    //loadData
  }

  function render ($width = 300) {
    G::LoadClass('gauge');
    $g = new pmGauge();
    $g->w = $width;
    //others
    $g->render();
  }

}