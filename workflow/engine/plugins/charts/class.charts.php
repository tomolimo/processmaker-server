<?php
  require_once ( "classes/model/Application.php" );
  require_once ( "classes/model/AppDelegation.php" );
  require_once ( "classes/model/Process.php" );

  class chartsClass extends PMPlugin  {

    function __construct (  ) {
    }

    function readConfig () {
    	$fileConf = PATH_PLUGINS . 'charts' . PATH_SEP . 'config' . PATH_SEP . 'setup.conf';
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

    function getDatasetCasesByStatus ( ) {
      $dataSet = new XYDataSet();

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
   	   $label = $row['APP_STATUS'];
   	   $value = $row['CANT'];
   	   $dataSet->addPoint(new Point($label , (int)$value ) );
	     $rs->next();
	     $row = $rs->getRow();
     }
     return $dataSet;
    }
    
    //we are trying to obtain the process title thru the long way, using the process object.
    //there is a short way, if you use a more complex query joining Content Table.
    function getDatasetCasesByProcess ( ) {
      $dataSet = new XYDataSet();
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
   	    $label = $processObj->getProTitle();
   	    $value = $row['CANT'];
   	    $dataSet->addPoint(new Point($label , (int)$value) );
	      $rs->next();
	      $row = $rs->getRow();
      }
      return $dataSet;
    }
    
  }
