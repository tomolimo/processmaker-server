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
    $thisYear = date('Y');
    $lastYear = $thisYear -1;
    $thisMonth = date('M');
    $lastMonth = date('M', strtotime( "31 days ago") );
//print "$thisMonth $lastMonth"; die;
    
    $todayIni        = date('Y-m-d H:i:s', strtotime( "today 00:00:00"));
    $todayEnd        = date('Y-m-d H:i:s', strtotime( "today 23:59:59"));
    $yesterdayIni    = date('Y-m-d H:i:s', strtotime( "yesterday 00:00:00"));
    $yesterdayEnd    = date('Y-m-d H:i:s', strtotime( "yesterday 23:59:59"));
    $thisWeekIni     = date('Y-m-d H:i:s', strtotime( "monday 00:00:00"));
    $thisWeekEnd     = date('Y-m-d H:i:s', strtotime( "sunday 23:59:59"));
    $previousWeekIni = date('Y-m-d H:i:s', strtotime( "last monday 00:00:00"));
    $previousWeekEnd = date('Y-m-d H:i:s', strtotime( "last sunday 23:59:59"));

    $thisMonthIni    = date('Y-m-d H:i:s', strtotime( "$thisMonth 1st 00:00:00"));
    $thisMonthEnd    = date('Y-m-d H:i:s', strtotime( "$thisMonth last day 23:59:59"));

    $previousMonthIni = date('Y-m-d H:i:s', strtotime( "$lastMonth 1st 00:00:00"));
    $previousMonthEnd = date('Y-m-d H:i:s', strtotime( "$lastMonth last day 23:59:59"));

    $thisYearIni     = date('Y-m-d H:i:s', strtotime( "jan $thisYear 00:00:00"));
    $thisYearEnd     = date('Y-m-d H:i:s', strtotime( "Dec 31 $thisYear 23:59:59"));
    $previousYearIni = date('Y-m-d H:i:s', strtotime( "jan $lastYear 00:00:00"));
    $previousYearEnd = date('Y-m-d H:i:s', strtotime( "Dec 31 $lastYear 23:59:59"));

    switch ( $config['DAS_INS_CONTEXT_TIME'] ) { 
      case 'TODAY'            : $dateIni = $todayIni;        $dateEnd = $todayEnd;        break;
      case 'YESTERDAY'        : $dateIni = $yesterdayIni;    $dateEnd = $yesterdayEnd;    break;
      case 'THIS_WEEK'        : $dateIni = $thisWeekIni;     $dateEnd = $thisWeekEnd;     break;
      case 'PREVIOUS_WEEK'    : $dateIni = $previousWeekIni; $dateEnd = $previousWeekEnd; break;

      case 'THIS_MONTH'       : $dateIni = $todayIni; $dateEnd = $todayEnd;     break;
      case 'PREVIOUS_MONTH'   : $dateIni = $todayIni; $dateEnd = $todayEnd;     break;
      case 'THIS_QUARTER'     : $dateIni = $todayIni; $dateEnd = $todayEnd;     break;
      case 'PREVIOUS_QUARTER' : $dateIni = $todayIni;   $dateEnd = $todayEnd;     break;

      case 'THIS_YEAR'        : $dateIni = $thisYearIni; $dateEnd = $thisYearEnd;     break;
      case 'PREVIOUS_YEAR'    : $dateIni = $previousYearIni; $dateEnd = $previousYearEnd;     break;
    }

    $con = Propel::getConnection("workflow");
    $stmt = $con->createStatement();
    $sql = "select count(*) as CANT from APPLICATION where APP_STATUS in ( 'DRAFT', 'TO_DO' ) ";
    $sql .= "and APP_CREATE_DATE > '$dateIni' and APP_CREATE_DATE <= '$dateEnd' ";
    $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
    $rs->next();
    $row = $rs->getRow();
    $casesTodo = $row['CANT'];

    $stmt = $con->createStatement();
    $sql = "select count(*) as CANT from APPLICATION where APP_STATUS = 'COMPLETED' ";
    $sql .= "and APP_CREATE_DATE > '$dateIni' and APP_CREATE_DATE <= '$dateEnd' ";
    $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
    $rs->next();
    $row = $rs->getRow();
    $casesCompleted = $row['CANT'];
    if ( $casesCompleted + $casesTodo != 0 ) { 
      $this->value = $casesCompleted / ($casesCompleted + $casesTodo)*100;
    }
    else {
      $this->value = 0;
    }
    $this->open      = $casesCompleted;
    $this->completed = $casesCompleted + $casesTodo;
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
    return true;
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
    $g->open      = $this->open;
    $g->completed = $this->completed;
    $g->render();
  }

}