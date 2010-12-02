<?php
/**
 * class.{className}.php
 *  
 */

  class {className}Class extends PMPlugin  {

    function __construct (  ) {
      set_include_path(
        PATH_PLUGINS . '{className}' . PATH_SEPARATOR .
        get_include_path()
      );
    }

    function setup()
    {
    }

<!-- START BLOCK : dashboard -->
    //here we are defining the available charts, the dashboard setup will call this function to know the charts
    function getAvailableCharts(  ) {
    	return array (
    	         '{className}Chart',
    	         );
    }

    function getChart( $chartName ) {
      $prePath = '/sys' . SYS_SYS . '/' . SYS_LANG . '/blank/';
    	$obj = new StdClass();
    	switch ($chartName) {
    	  case '{className}Chart':
    	    $obj->title = '{className} Chart - Per Forum';
    	  break;
    	}
      $obj->height = 220;
      $obj->open->image = $prePath . '{className}/drawChart?chart=' . $chartName . "&u=";
    	return $obj;
    }

   /* definition of all charts */
   /* that definition comes in two parts :
   /* 1.  the getXX () function to get the data from the databases
   /* 2.  the XX () function to draw the graph
   */

   /** chart  {className} ***/
    function get{className}Chart ( ) {
      $dataSet = array();
      $past1months = mktime(0, 0, 0, date("m") -1 , date("d"), date("Y"));

      $con = Propel::getConnection('workflow');
      $sql = "select  CON_VALUE, COUNT(*) AS CANT  from APPLICATION LEFT JOIN CONTENT ON ( CON_CATEGORY = 'PRO_TITLE' AND CON_ID = PRO_UID  AND CON_LANG = 'en' ) GROUP BY CON_VALUE" ;
      $stmt = $con->createStatement();
      $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
      $rs->next();
      $row = $rs->getRow();
      while ( is_array ( $row ) ) {
      	if ( strlen ( trim ($row['CON_VALUE']) ) > 0 )  {
  	      $label[] = $row['CON_VALUE'];
   	      $data[]  = $row['CANT'];
   	    }
 	      $rs->next();
	      $row = $rs->getRow();
      }
      $dataSet['data']  = $data;
      $dataSet['label'] = $label;

      $max = 1;
      foreach ( $dataSet['data'] as $k => $val ) if ( $val > $max ) $max = $val;
      $aux = intval($max / 6 ) * 6 + 6;
      $dataSet['max']  = $aux;

      return $dataSet;
    }

    function {className}Chart( ) {
      G::LoadThirdParty("libchart/classes", "libchart" );
      $chart = new VerticalBarChart(430, 220);
      $dataSet = $this->get{className}Chart();
	    $dataPostSet = new XYDataSet();
	    $dataTopicSet = new XYDataSet();
	    foreach ( $dataSet['label'] as $key => $label ) {
	    	$dataPostSet->addPoint(new Point( $label, $dataSet['data'][$key] )) ;
	    }

	    $chart->setDataSet($dataPostSet);
      $chart->setTitle( " Posts by Week " );
      $chart->render();
    }

    
<!-- END BLOCK : dashboard --> 

<!-- START BLOCK : report -->

    //here we are defining the available charts, the dashboard setup will call this function to know the charts
    function getAvailableReports(  ) {
    	return array (
    	  array ( 'uid'=>'{className}Report_1', 'title'=>'{className} Test Report (users)' ),
    	  //array ( 'uid'=>'{className}Report_2', 'title'=>'{className} Test Report (groups)' )
    	);
    }    
    
    function getReport( $reportName ) {
    	$obj = new StdClass();
    	switch ( $reportName ) {
    	  case '{className}Report_1':
    	    $obj->title = '{className} Test Report (users)';
    	  break;
    	  case '{className}Report_2':
    	    $obj->title = '{className} Test Report (users)';
    	  break;
    	  default:
    	    $obj->title = 'default ....';
    	  break;
    	}
    	return $obj;
    }

    function {className}Report_1() {
    	global $G_PUBLISH;
      require_once 'classes/model/Users.php';
      $sDelimiter = DBAdapter::getStringDelimiter();
      $aUsers   = array();
    	$aUsers[] = array('USR_UID'       => 'char',
    	                  'USR_NAME'      => 'char',
    	                  'USR_FIRSTNAME' => 'char',
                        'USR_LASTNAME'  => 'char',
                        'USR_EMAIL'     => 'char',
                        'USR_ROLE'      => 'char',);
                          
      $con = Propel::getConnection('workflow');
      $sql = 'SELECT USR_UID,USR_USERNAME,USR_FIRSTNAME,USR_LASTNAME,USR_EMAIL,USR_ROLE FROM USERS';
      $stmt = $con->createStatement();
      $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
      $rs->next();
      $row = $rs->getRow();
      while ( is_array ( $row ) ) {
      	$aUsers[] = array('USR_UID'       => $row['USR_UID'],
    	                  'USR_NAME'      => $row['USR_USERNAME'],
    	                  'USR_FIRSTNAME' => $row['USR_FIRSTNAME'],
                        'USR_LASTNAME'  => $row['USR_LASTNAME'],
                        'USR_EMAIL'     => $row['USR_EMAIL'],
                        'USR_ROLE'      => $row['USR_ROLE']);
 	      $rs->next();
	      $row = $rs->getRow();
      }

      global $_DBArray;
      $_DBArray['users']  = $aUsers;
      $_SESSION['_DBArray'] = $_DBArray;
      
      G::LoadClass('ArrayPeer');
      $oCriteria = new Criteria('dbarray');
      $oCriteria->setDBArrayTable('users');
      $oCriteria->addDescendingOrderByColumn('USR_USERNAME');
      $G_PUBLISH = new Publisher;
      $G_PUBLISH->AddContent('propeltable', 'paged-table', '{className}/report', $oCriteria);
      G::RenderPage('publish');
    	return 1;
    }
    
<!-- END BLOCK : report --> 

  }