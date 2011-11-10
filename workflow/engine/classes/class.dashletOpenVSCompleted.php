<?php

require_once 'interfaces/dashletInterface.php';

class dashletOpenVSCompleted implements DashletInterface {

  function setup($config) {
    $thisYear = date('Y');
    $lastYear = $thisYear -1;
    $thisMonth = date('M');
    $lastMonth = date('M', strtotime( "31 days ago") );

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

  function getAdditionalFields() {
    $additionalFields = array();
    $greenFrom = new stdclass();
    $greenFrom->xtype = 'textfield';
    $greenFrom->name = 'DAS_RED_FROM';
    $greenFrom->fieldLabel = 'Red Starts In';
    $greenFrom->width = 50;
    $additionalFields[] = $greenFrom;
    $greenFrom = new stdclass();
    $greenFrom->xtype = 'textfield';
    $greenFrom->name = 'DAS_RED_TO';
    $greenFrom->fieldLabel = 'Red Ends In';
    $greenFrom->width = 50;
    $additionalFields[] = $greenFrom;
    $greenFrom = new stdclass();
    $greenFrom->xtype = 'textfield';
    $greenFrom->name = 'DAS_YELLOW_FROM';
    $greenFrom->fieldLabel = 'Yellow Starts In';
    $greenFrom->width = 50;
    $additionalFields[] = $greenFrom;
    $greenFrom = new stdclass();
    $greenFrom->xtype = 'textfield';
    $greenFrom->name = 'DAS_YELLOW_TO';
    $greenFrom->fieldLabel = 'Yellow Ends In';
    $greenFrom->width = 50;
    $additionalFields[] = $greenFrom;
    $greenFrom = new stdclass();
    $greenFrom->xtype = 'textfield';
    $greenFrom->name = 'DAS_GREEN_FROM';
    $greenFrom->fieldLabel = 'Green Starts In';
    $greenFrom->width = 50;
    $additionalFields[] = $greenFrom;
    $greenFrom = new stdclass();
    $greenFrom->xtype = 'textfield';
    $greenFrom->name = 'DAS_GREEN_TO';
    $greenFrom->fieldLabel = 'Green Ends In';
    $greenFrom->width = 50;
    $additionalFields[] = $greenFrom;
    return $additionalFields;
  }

  function render ($width = 300) {
    G::LoadClass('pmGauge');
    $g = new pmGauge();
    $g->w = $width;
    $g->value = $this->value;

    $g->greenFrom  = 50;
    $g->greenTo    = 100;
    $g->yellowFrom = 30;
    $g->yellowTo   = 50;
    $g->redFrom    = 0;
    $g->redTo      = 30;

    $g->centerLabel = $this->centerLabel;
    $g->open        = $this->open;
    $g->completed   = $this->completed;
    $g->render();
  }

}