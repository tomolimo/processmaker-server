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
    
    $con = Propel::getConnection("workflow");
    $stmt = $con->createStatement();
    $sql = "select count(*) as CANT from APPLICATION where APP_STATUS = 'TO_DO' ";
    $rs1 = $stmt->executeQuery($sql, ResultSet::FETCHMODE_NUM);
    $rs1->next();
    $row = $rs1->getRow();
    $casesTodo = $row['CANT'];

    $stmt = $con->createStatement();
    $sql = "select count(*) as CANT from APPLICATION where APP_STATUS = 'COMPLETED' ";
    $rs1 = $stmt->executeQuery($sql, ResultSet::FETCHMODE_NUM);
    $rs1->next();
    $row = $rs1->getRow();
    $casesCompleted = $row['CANT'];

    if ( $casesCompleted + $casesTodo != 0 ) { 
      $this->value = $casesTodo / ($casesCompleted + $casesTodo);
    }
    else {
      $this->value = 0;
    }
    return $row[0];
  }

  function render ($width = 300) {
    G::LoadClass('pmGauge');
    $g = new pmGauge();
    $g->w = $width;
    $g->value = $this->value;
    //$g->maxValue = $this->value + 5; //default 100 is ok,
    $g->render();
  }

}