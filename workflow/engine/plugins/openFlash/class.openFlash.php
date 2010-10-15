<?php
  require_once ( "classes/model/Application.php" );
  require_once ( "classes/model/AppDelegation.php" );
  require_once ( "classes/model/Process.php" );

  class openFlashClass extends PMPlugin  {

    function __construct (  ) {
    }

    function readConfig () {
    	$fileConf = PATH_PLUGINS . 'openFlash' . PATH_SEP . 'config' . PATH_SEP . 'setup.conf';
    	if ( !file_exists( dirname($fileConf) ) ) 
    	  throw ( new Exception ("The directory " . dirname($fileConf) . " doesn't exist." ) );
    	  
    	if ( file_exists ( $fileConf ) && !is_writable( $fileConf ) ) 
    	  throw ( new Exception ("The file $fileConf doesn't exist or this file is not writable." ) );
    	
    	$content = file_get_contents ( $fileConf);
    	$fields = unserialize ($content);
    	return $fields;
    }
    
    function getFieldsForPageSetup () {
    	$fileConf = PATH_PLUGINS . $this->sPluginFolder . PATH_SEP . 'config' . PATH_SEP . 'setup.conf';
    	if ( !file_exists( dirname($fileConf) ) ) 
    	  throw ( new Exception ("The directory " . dirname($fileConf) . " doesn't exist." ) );
    	  
    	if ( file_exists ( $fileConf ) && !is_writable( $fileConf ) ) 
    	  throw ( new Exception ("The file $fileConf doesn't exist or this file is not writable." ) );
    	
    	if ( file_exists ( $fileConf ) ) {
    	  $content = file_get_contents ( $fileConf);
    	  $fields = unserialize ($content);
    	}
    	else
    	  $fields = array();
    	return $fields;
    }

    function updateFieldsForPageSetup ( $oData) {
    	$content = serialize ($oData['form']);
    	$fileConf = PATH_PLUGINS . $this->sPluginFolder . PATH_SEP . 'config' . PATH_SEP . 'setup.conf';
    	if ( !is_writable( dirname($fileConf) ) ) 
    	  throw ( new Exception ("The directory " . dirname($fileConf) . " doesn't exist or this directory is not writable." ) );
    	  
    	if ( file_exists ( $fileConf ) && !is_writable( $fileConf ) ) 
    	  throw ( new Exception ("The file $fileConf doesn't exist or this file is not writable." ) );
    	
    	file_put_contents ( $fileConf, $content);
    	return true;
    }

    function setup() {
    }

    function getChart( $chartName ) {
    	$this->readConfig();
      $prePath = '/sys' . SYS_SYS . '/' . SYS_LANG . '/blank/';
    	$obj = new StdClass();
      $obj->title = 'Standard ProcessMaker Reports';
      $obj->height = 220;
      $obj->open->url = $prePath . 'openFlash/chart?chart=' . $chartName . "&u=";
    	return $obj;
    }

    //here we are defining the available charts, the dashboard setup will call this function to know the charts
    function getAvailableCharts(  ) {
    	return array (
    	         'CasesByStatus', 
    	         'CasesByStatusPie', 
    	         'CasesByProcess', 
    	         'CasesByProcessPie' 
    	         );
    }

   /* definition of all charts */
   /* that definition comes in two parts : 
   /* 1.  the getXX () function to get the data from the databases
   /* 2.  the XX () function to draw the graph
   */
   
   /** chart  getCasesByStatus ***/
   /** to show the Cases grouped by Status*/
    function getCasesByStatus ( ) {
      $dataSet = array();

      $c = new Criteria('workflow');
      $c->clearSelectColumns();
      $c->addSelectColumn ( ApplicationPeer::APP_STATUS );
      $c->addSelectColumn ( 'COUNT(*) AS CANT') ;
      $c->addGroupByColumn(ApplicationPeer::APP_STATUS);
      $rs = ApplicationPeer::doSelectRS( $c );
      $rs->setFetchmode(ResultSet::FETCHMODE_ASSOC);
      $rs->next();
      $row = $rs->getRow();
      while ( is_array ( $row ) ) {
  	    $label[] = $row['APP_STATUS'];
   	    $data[]  = (int)$row['CANT'];
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

    function CasesByStatus( ) {
      $dataSet = $this->getCasesByStatus(); 
      $data = $dataSet['data']; 
      //$series2 = $dataSet['series2']; 
      $g = new graph();
      $g->title( ' Cases by Status ', '{font-size: 14px;}' );
      $bar_red = new bar_3d( 50, '#3334AD' );
      $bar_red->key( 'cases', 10 );
      $bar_red->data = $data;
      
      $g->data_sets[] = $bar_red;
      
      $g->set_x_axis_3d( 12 );
      $g->x_axis_colour( '#909090', '#ADB5C7' );
      $g->y_axis_colour( '#909090', '#ADB5C7' );      
      $g->set_x_labels( $dataSet['label'] );
      
      $g->set_y_max( $dataSet['max'] );
      $g->y_label_steps( 6 );
      //$g->set_y_legend( 'Posts', 12, '#736AFF' );
      echo $g->render();
    }

    function CasesByStatusPie ( ) {
    	$g->bg_colour = '#FFFFFF';
      $dataSet = $this->getCasesByStatus(); 
      $data = $dataSet['data']; 
      $g = new graph();
      $g->pie(80,'#505050','{font-size: 12px; color: #404040;');
      $g->pie_values( $data, $dataSet['label'] );
      $g->pie_slice_colours( array('#d01f3c','#356aa0','#C79810','#D54C78') );
      $g->set_tool_tip( '#val# #x_label#' );
      $g->title( 'Cases by Status', '{font-size:18px; color: #d01f3c}' );
      echo $g->render();
    }
    
    /** chart  CasesByProcess ***/
    /** to show the cases grouped by Process */
    function getCasesByProcess ( ) {
      $dataSet = array();
      $processObj = new Process;
 
      $c = new Criteria('workflow');
      $c->clearSelectColumns();
      $c->addSelectColumn ( ApplicationPeer::PRO_UID );
      $c->addSelectColumn ( 'COUNT(*) AS CANT') ;
	    //$c->addJoin( ProcessPeer::PRO_UID, ProcessPeer::PRO_UID, Criteria::LEFT_JOIN);
      $c->addGroupByColumn(ApplicationPeer::PRO_UID);
      $rs = ApplicationPeer::doSelectRS( $c );
      $rs->setFetchmode(ResultSet::FETCHMODE_ASSOC);
      $rs->next();
      $row = $rs->getRow();
      while ( is_array ( $row ) ) {
   	    $processObj->load ( $row['PRO_UID'] );
  	    $label[] = $processObj->getProTitle();
  	    $labelPie[] = substr ( $processObj->getProTitle(),0,16 );
   	    $data[]  = (int)$row['CANT'];
 	      $rs->next();
	      $row = $rs->getRow();
      }
      $dataSet['data']  = $data;
      $dataSet['label'] = $label;
      $dataSet['labelPie'] = $labelPie;

      $max = 1;
      foreach ( $dataSet['data'] as $k => $val ) if ( $val > $max ) $max = $val;
      $aux = intval($max / 6 ) * 6 + 6;
      $dataSet['max']  = $aux;
      return $dataSet;
    }

    function CasesByProcess( ) {
      $dataSet = $this->getCasesByProcess(); 
      $data = $dataSet['data']; 
      //$series2 = $dataSet['series2']; 
      $g = new graph();
      $g->title( ' Cases by Process ', '{font-size: 14px;}' );
      $bar_red = new bar_3d( 50, '#3334AD' );
      $bar_red->key( 'cases', 10 );
      $bar_red->data = $data;
      
      $g->data_sets[] = $bar_red;
      
      $g->set_x_axis_3d( 12 );
      $g->x_axis_colour( '#909090', '#ADB5C7' );
      $g->y_axis_colour( '#909090', '#ADB5C7' );      
      $g->set_x_labels( $dataSet['label'] );
      
      $g->set_y_max( $dataSet['max'] );
      $g->y_label_steps( 6 );
      echo $g->render();
    }

    function CasesByProcessPie ( ) {
    	$g->bg_colour = '#FFFFFF';
      $dataSet = $this->getCasesByProcess(); 
      $data = $dataSet['data']; 
      $g = new graph();
      $g->pie(80,'#505050','{font-size: 12px; color: #404040;');
      $g->pie_values( $data, $dataSet['labelPie'] );
      $g->pie_slice_colours( array('#d01f3c','#356aa0','#C79810','#D54C78') );
      $g->set_tool_tip( '#val# #x_label#' );
      $g->title( 'Cases by Process', '{font-size:16px; color: #d01f3c}' );
      echo $g->render();
    }
/*
    function getForumWeek ( ) {
      $databases = PATH_PLUGINS . "/openFlash/config/databases.php";
      Propel::init( $databases );

      $dataSet = array();
      $processObj = new Process;
      $past2months = mktime(0, 0, 0, date("m") -2 , date("d"), date("Y"));
 
      $con = Propel::getConnection('forum');
      $sql = "select week(FROM_UNIXTIME(post_time ))  as week  ,count(*) as cant from  phpbb_posts where post_time > $past2months group by week " ;
      $stmt = $con->createStatement();
      $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
      $rs->next();
      $row = $rs->getRow();
      while ( is_array ( $row ) ) {
  	    $label[] = date ( 'M d', mktime(0, 0, 0, 1 , $row['week']*7, date("Y")) );
   	    $data[]  = $row['cant'];
 	      $rs->next();
	      $row = $rs->getRow();
      }
      $dataSet['data']  = $data;
      $dataSet['label'] = $label;
      return $dataSet;
    }
    
    function ForumWeek ( ) {
      $dataSet = $this->getForumWeek(); 
      $data = $dataSet['data']; 
      $max = 1;
      foreach ( $dataSet['data'] as $k => $val ) if ( $val > $max ) $max = $val;
      $g = new graph();
      $g->title( ' Posts by Week ', '{font-size: 16px;}' );
      $bar_red = new bar_3d( 50, '#3334AD' );
      $bar_red->key( 'week', 10 );
      $bar_red->data = $data;
      
      $g->data_sets[] = $bar_red;
      
      $g->set_x_axis_3d( 12 );
      $g->x_axis_colour( '#909090', '#ADB5C7' );
      $g->y_axis_colour( '#909090', '#ADB5C7' );      
      $g->set_x_labels( $dataSet['label'] );
      
      $g->set_y_max( $max );
      $g->y_label_steps( 5 );
      $g->set_y_legend( 'Posts', 12, '#736AFF' );
      echo $g->render();
    }

    /** chart  PostByUser ***/
    /** to show the last 7 days grouped by user * /
    function getPostByUser ( ) {
      $databases = PATH_PLUGINS . "/openFlash/config/databases.php";
      Propel::init( $databases );

      $dataSet = array();
      $con = Propel::getConnection('forum');

      $past7days = mktime(0, 0, 0, date("m")  , date("d")-7, date("Y"));
      $sql = "select username, count(*) as cant from phpbb_posts left join phpbb_users on ( poster_id = user_id ) where post_time > $past7days group by username " ;
      $stmt = $con->createStatement();
      $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
      $rs->next();
      $row = $rs->getRow();
      while ( is_array ( $row ) ) {
  	    $label[] = $row['username'];
   	    $data[]  = $row['cant'];
 	      $rs->next();
	      $row = $rs->getRow();
      }
      $dataSet['data']  = $data;
      //$dataSet['data']  = $data;
      $dataSet['label'] = $label;
      return $dataSet;
    }

    function PostByUser ( ) {
      $dataSet = $this->getPostByUser(); 
      $data = $dataSet['data']; 
      $g = new graph();
      $g->title( ' Posts by User last week '. date("Y-m-d"), '{font-size: 16px;}' );
      $max = 1;
      foreach ( $dataSet['data'] as $k => $val ) if ( $val > $max ) $max = $val;
      $max = intval($max / 4 ) * 4 + 4;
      
      $bar_red = new bar_3d( 50, '#356aa0' );
      $bar_red->key( 'posts', 10 );
      $bar_red->data = $data;
      
      $bar_blue = new bar_3d( 75, '#D54C78' );
      $bar_blue->key( 'completed', 10 );
      $bar_blue->data = $data;
      
      $g->data_sets[] = $bar_red;
      //$g->data_sets[] = $bar_blue;      
      
      $g->set_x_axis_3d( 12 );
      $g->x_axis_colour( '#909090', '#ADB5C7' );
      $g->y_axis_colour( '#909090', '#ADB5C7' );      
      $g->set_x_labels( $dataSet['label'] );
      
      $g->set_y_max( $max );
      $g->y_label_steps( 4 );
      $g->set_y_legend( 'Processmaker', 12, '#736AFF' );
      echo $g->render();
    }
    
    /** chart  BugsByStatus ***/
    /** to show the bugs by status  resolved, open, closed * /
    function getBugsByStatus ( ) {
      $databases = PATH_PLUGINS . "/openFlash/config/databases.php";
      Propel::init( $databases );

      $dataSet = array();
      $con = Propel::getConnection('bugs');

      //open
      $sql = "SELECT count(*) as cant FROM mantis_bug_table where project_id = 31 and status in (20,30,40,50) " ;
      $stmt = $con->createStatement();
      $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
      $rs->next();
      $row = $rs->getRow();
  	  $label[] = 'open';
      $data[] = $row['cant'];

      //resolved
      $sql = "SELECT count(*) as cant FROM mantis_bug_table where project_id = 31 and status = 80" ;
      $stmt = $con->createStatement();
      $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
      $rs->next();
      $row = $rs->getRow();
  	  $label[] = 'resolved';
      $data[] = $row['cant'];

      //closed
      $sql = "SELECT count(*) as cant FROM mantis_bug_table where project_id = 31 and status = 90" ;
      $stmt = $con->createStatement();
      $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
      $rs->next();
      $row = $rs->getRow();
  	  $label[] = 'closed';
      $data[] = $row['cant'];

      $dataSet['data']  = $data;
      $dataSet['label'] = $label;
      return $dataSet;
    }

    function BugsByStatus ( ) {
      $dataSet = $this->getBugsByStatus(); 
      $data = $dataSet['data']; 
      $g = new graph();
      $g->title( ' Posts by User last week '. date("Y-m-d"), '{font-size: 16px;}' );
       
      $g->pie(70,'#505050','{font-size: 12px; color: #404040;');
      $g->pie_values( $data, $dataSet['label'] );
      $g->pie_slice_colours( array('#d01f3c','#356aa0','#C79810') );
      $g->set_tool_tip( '#val# #x_label#' );
      $g->title( 'Bugs by Status', '{font-size:18px; color: #d01f3c}' );
      echo $g->render();
    }

    /** chart  BugsOpenByUser ***/
    /** to show the bugs in OPEN status by user * /
    function getBugsOpenByUser ( ) {
      $databases = PATH_PLUGINS . "/openFlash/config/databases.php";
      Propel::init( $databases );

      $dataSet = array();
      $con = Propel::getConnection('bugs');

      $sql = "SELECT username, count(*) as cant FROM mantis_bug_table left join mantis_user_table on ( mantis_user_table.id = handler_id) where project_id = 31 and status in (20,30,40,50) group by username " ;
      $stmt = $con->createStatement();
      $rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
      $rs->next();
      $row = $rs->getRow();
      while ( is_array ( $row ) ) {
  	    $label[] = $row['username'];
   	    $data[]  = $row['cant'];
 	      $rs->next();
	      $row = $rs->getRow();
      }
      $dataSet['data']  = $data;
      $dataSet['label'] = $label;
      return $dataSet;
    }

    function BugsOpenByUser ( ) {
    	$g->bg_colour = '#EFFFEF';
      $dataSet = $this->getBugsOpenByUser(); 
      $data = $dataSet['data']; 
      $g = new graph();
      $g->pie(80,'#505050','{font-size: 12px; color: #404040;');
      $g->pie_values( $data, $dataSet['label'] );
      $g->pie_slice_colours( array('#d01f3c','#356aa0','#C79810','#D54C78') );
      $g->set_tool_tip( '#val# #x_label#' );
      $g->title( 'Open Bugs by User', '{font-size:18px; color: #d01f3c}' );
      echo $g->render();
    }
    
/*******/
    
    
    

    


  
}
