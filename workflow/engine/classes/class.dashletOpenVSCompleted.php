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
    $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
    $rs->next();
    $row = $rs->getRow();
    $casesTodo = $row['CANT'];

    $stmt = $con->createStatement();
    $sql = "select count(*) as CANT from APPLICATION where APP_STATUS = 'COMPLETED' ";
    $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
    $rs->next();
    $row = $rs->getRow();
    $casesCompleted = $row['CANT'];
    if ( $casesCompleted + $casesTodo != 0 ) { 
      $this->value = $casesTodo / ($casesCompleted + $casesTodo)*100;
    }
    else {
      $this->value = 0;
    }
    switch ( $config['DAS_INS_CONTEXT_TIME'] ) { 
      case 'TODAY'            : $this->centerLabel = 'Today';            break;
      case 'YESTERDAY'        : $this->centerLabel = 'Yesterday';        break;
      case 'THIS_WEEK'        : $this->centerLabel = 'This week';        break;
      case 'PREVIOUS_WEEK'    : $this->centerLabel = 'Previous week';    break;
      case 'THIS_MONTH'       : $this->centerLabel = 'This month';       break;
      case 'PREVIOUS_MONTH'   : $this->centerLabel = 'Previous month';   break;
      case 'THIS_QUARTER'     : $this->centerLabel = 'This quarter';     break;
      case 'PREVIOUS_QUARTER' : $this->centerLabel = 'Previous quarter'; break;
      case 'THIS_YEAR'        : $this->centerLabel = 'This year';        break;
      case 'PREVIOUS_YEAR'    : $this->centerLabel = 'Previous year';    break;
      default : $this->centerLabel = '';break;
    }
    return $row[0];
  }

  function render ($width = 300) {
    G::LoadClass('pmGauge');
    $g = new pmGauge();
    $g->w = $width;
    $g->value = $this->value;
    $g->maxValue   = 100;
    $g->greenFrom  = 90;
    $g->greenTo    = 100;
    $g->yellowFrom = 70;
    $g->yellowTo   = 90;
    $g->redFrom    = 100;
    $g->redTo      = 100;
    $g->centerLabel = $this->centerLabel;
    $g->render();
  }

}