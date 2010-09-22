<?php

require_once 'classes/model/om/BaseAppCacheView.php';


/**
 * Skeleton subclass for representing a row from the 'APP_CACHE_VIEW' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
 
require_once 'classes/model/AppDelay.php';
 
class AppCacheView extends BaseAppCacheView {

  function getAllCounters ( $aTypes, $userUid, $processSummary = false ) {
    $aResult = Array();
    foreach($aTypes as $type){
      $aResult[$type] = $this->getListCounters($type, $userUid, $processSummary);
    }
    return $aResult;
  }

  function getListCounters ( $type, $userUid, $processSummary ) {
  	switch ( $type ) {
  		case 'to_do' : 
  	    $Criteria = $this->getToDoCountCriteria( $userUid );
  	    break;
  		case 'draft' : 
  	    $Criteria = $this->getDraftCountCriteria( $userUid );
  	    break;
  		case 'sent' : 
  	    $Criteria = $this->getSentCountCriteria( $userUid );
        return AppCacheViewPeer::doCount($Criteria, true);
  	    break;
  		case 'selfservice' : 
  	    $Criteria = $this->getUnassignedCountCriteria( $userUid );
  	    break;
  		case 'paused' : 
  	    $Criteria = $this->getPausedCountCriteria( $userUid );
  	    break;
  		case 'completed' : 
  	    $Criteria = $this->getCompletedCountCriteria( $userUid );
  	    break;
  		case 'cancelled' : 
  	    $Criteria = $this->getCancelledCountCriteria( $userUid );
  	    break;
  	  default :
  	    return $type;
    }
    return AppCacheViewPeer::doCount($Criteria);
  }
  
  /**
   * gets the todo cases list criteria
   * param $userUid the current userUid
   * param $doCount if true this will return the criteria for count cases only
   * @return Criteria object $Criteria
   */
  function getToDo ( $userUid, $doCount ) {
    // adding configuration fields from the configuration options
    // and forming the criteria object
    if ( $doCount ) {
      $Criteria  = new Criteria('workflow');
    }
    else {
      $Criteria = $this->addPMFieldsToCriteria('todo');
    }
    $Criteria->add (AppCacheViewPeer::APP_STATUS, "TO_DO" , CRITERIA::EQUAL );
    $Criteria->add (AppCacheViewPeer::USR_UID, $userUid);

    $Criteria->add (AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL);
    $Criteria->add (AppCacheViewPeer::APP_THREAD_STATUS, 'OPEN');
    $Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');
    return $Criteria;
  }

  /**
   * gets the todo cases list criteria for count
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getToDoCountCriteria ($userUid) {
  	return $this->getToDo($userUid, true);
  }
  
  /**
   * gets the todo cases list criteria for list
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getToDoListCriteria ($userUid) {
  	return $this->getToDo($userUid, false);
  }

  /**
   * gets the DRAFT cases list criteria
   * param $userUid the current userUid
   * param $doCount if true this will return the criteria for count cases only
   * @return Criteria object $Criteria
   */
  function getDraft ( $userUid, $doCount ) {
    // adding configuration fields from the configuration options
    // and forming the criteria object
    if ( $doCount ) {
      $Criteria  = new Criteria('workflow');
    }
    else {
      $Criteria = $this->addPMFieldsToCriteria('draft');
    }
    $Criteria->add (AppCacheViewPeer::APP_STATUS, "DRAFT" , CRITERIA::EQUAL );
    $Criteria->add (AppCacheViewPeer::USR_UID, $userUid);

    //$Criteria->add (AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL);
    $Criteria->add (AppCacheViewPeer::APP_THREAD_STATUS, 'OPEN');
    $Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');
    return $Criteria;
  }
  
    /**
   * gets the DRAFT cases list criteria for count
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getDraftCountCriteria ($userUid) {
  	return $this->getDraft($userUid, true);
  }
  
   /**
   * gets the DRAFT cases list criteria for list
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getDraftListCriteria ($userUid) {
  	return $this->getDraft($userUid, false);
  }

    /**
   * gets the SENT cases list criteria
   * param $userUid the current userUid
   * param $doCount if true this will return the criteria for count cases only
   * @return Criteria object $Criteria
   */
  function getSent ( $userUid, $doCount ) {
    // adding configuration fields from the configuration options
    // and forming the criteria object
    if ( $doCount ) {
      $Criteria  = new Criteria('workflow');
    }
    else {
      $Criteria = $this->addPMFieldsToCriteria('sent');
    }
    $Criteria->add (AppCacheViewPeer::APP_STATUS, "DRAFT" , CRITERIA::EQUAL );
    $Criteria->add (AppCacheViewPeer::USR_UID, $userUid);

    //$Criteria->add (AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL);
    $Criteria->add (AppCacheViewPeer::APP_THREAD_STATUS, 'OPEN');
    $Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');
    return $Criteria;
  }
  
    /**
   * gets the SENT cases list criteria for count
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getSentCountCriteria ($userUid) {
    $Criteria  = new Criteria('workflow');

    //$Criteria->add (AppCacheViewPeer::APP_STATUS, "DRAFT" , CRITERIA::EQUAL );
    $Criteria->add (AppCacheViewPeer::USR_UID, $userUid);

    return $Criteria;
  }
  
   /**
   * gets the SENT cases list criteria for list
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getSentListCriteria ($userUid) {
    $Criteria = $this->addPMFieldsToCriteria('sent');
    $Criteria->addAsColumn( 'DEL_INDEX', 'MAX(' . AppCacheViewPeer::DEL_INDEX . ')' );
    
    $Criteria->add (AppCacheViewPeer::USR_UID, $userUid);

    $Criteria->addGroupByColumn(AppCacheViewPeer::APP_UID);
    //$Criteria->addGroupByColumn(AppCacheViewPeer::APP_);
    return $Criteria;  	
  }


    /**
   * gets the UNASSIGNED cases list criteria
   * param $userUid the current userUid
   * param $doCount if true this will return the criteria for count cases only
   * @return Criteria object $Criteria
   */
  function getUnassigned ( $userUid, $doCount ) {
    //get the valid selfservice tasks for this user
    if (!class_exists('Cases')){
      G::loadClass('case');
    }

    $oCase = new Cases();
    $tasks = $oCase->getSelfServiceTasks( $userUid ); 
    $aTasks = array();
    foreach ( $tasks as $key => $val ) {
      if ( strlen(trim($val['uid'])) > 10 ) $aTasks[] = $val['uid'];
    }
    
    // adding configuration fields from the configuration options
    // and forming the criteria object
    if ( $doCount ) {
      $Criteria  = new Criteria('workflow');
    }
    else {
      $Criteria = $this->addPMFieldsToCriteria('unassigned');
    }
    $Criteria->add (AppCacheViewPeer::APP_STATUS, "TODO" , CRITERIA::EQUAL );

    //$Criteria->add (AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL);
    //$Criteria->add (AppCacheViewPeer::APP_THREAD_STATUS, 'OPEN');
    //$Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');
    
    $Criteria->add(AppDelegationPeer::USR_UID, '');
    $Criteria->add(AppDelegationPeer::TAS_UID, $aTasks , Criteria::IN );    
    
    return $Criteria;
  }
  
    /**
   * gets the UNASSIGNED cases list criteria for count
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getUnassignedCountCriteria ($userUid) {
  	return $this->getUnassigned($userUid, true);
  }
  
   /**
   * gets the UNASSIGNED cases list criteria for list
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getUnassignedListCriteria ($userUid) {
  	return $this->getUnassigned($userUid, false);
  }

    /**
   * gets the PAUSED cases list criteria
   * param $userUid the current userUid
   * param $doCount if true this will return the criteria for count cases only
   * @return Criteria object $Criteria
   */
  function getPaused ( $userUid, $doCount ) {
    // adding configuration fields from the configuration options
    // and forming the criteria object
    if ( $doCount ) {
      $Criteria  = new Criteria('workflow');
    }
    else {
      $Criteria = $this->addPMFieldsToCriteria('paused');
    }
    $Criteria->add (AppCacheViewPeer::USR_UID, $userUid);

    //join with APP_DELAY table using APP_UID and DEL_INDEX    
    $appDelayConds[] = array(AppCacheViewPeer::APP_UID, AppDelayPeer::APP_UID);
    $appDelayConds[] = array(AppCacheViewPeer::DEL_INDEX, AppDelayPeer::APP_DEL_INDEX);
    $Criteria->addJoinMC($appDelayConds, Criteria::LEFT_JOIN);
    
    $Criteria->add($Criteria->getNewCriterion(AppDelayPeer::APP_DISABLE_ACTION_USER, null, Criteria::ISNULL)->addOr($Criteria->getNewCriterion(AppDelayPeer::APP_DISABLE_ACTION_USER, 0)));

    $Criteria->add(AppDelayPeer::APP_DELAY_UID, null, Criteria::ISNOTNULL);
    $Criteria->add(AppDelayPeer::APP_TYPE, 'PAUSE');
    return $Criteria;
  }
  
    /**
   * gets the PAUSED cases list criteria for count
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getPausedCountCriteria ($userUid) {
  	return $this->getPaused($userUid, true);
  }
  
   /**
   * gets the PAUSED cases list criteria for list
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getPausedListCriteria ($userUid) {
  	return $this->getPaused($userUid, false);
  }


    /**
   * gets the COMPLETED cases list criteria
   * param $userUid the current userUid
   * param $doCount if true this will return the criteria for count cases only
   * @return Criteria object $Criteria
   */
  function getCompleted ( $userUid, $doCount ) {
    // adding configuration fields from the configuration options
    // and forming the criteria object
    if ( $doCount ) {
      $Criteria  = new Criteria('workflow');
    }
    else {
      $Criteria = $this->addPMFieldsToCriteria('completed');
    }
    $Criteria->add (AppCacheViewPeer::APP_STATUS, "COMPLETED" , CRITERIA::EQUAL );
    $Criteria->add (AppCacheViewPeer::USR_UID, $userUid);

    //$Criteria->add (AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL);
    $Criteria->add (AppCacheViewPeer::APP_THREAD_STATUS, 'OPEN');
    $Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');


    //$c->add(AppDelegationPeer::DEL_PREVIOUS, '0', Criteria::NOT_EQUAL);
    $Criteria->addGroupByColumn(ApplicationPeer::APP_UID);
   
    return $Criteria;
  }
  
    /**
   * gets the COMPLETED cases list criteria for count
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getCompletedCountCriteria ($userUid) {
  	return $this->getCompleted($userUid, true);
  }
  
   /**
   * gets the COMPLETED cases list criteria for list
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getCompletedListCriteria ($userUid) {
  	return $this->getCompleted($userUid, false);
  }


    /**
   * gets the CANCELLED cases list criteria
   * param $userUid the current userUid
   * param $doCount if true this will return the criteria for count cases only
   * @return Criteria object $Criteria
   */
  function getCancelled ( $userUid, $doCount ) {
    // adding configuration fields from the configuration options
    // and forming the criteria object
    if ( $doCount ) {
      $Criteria  = new Criteria('workflow');
    }
    else {
      $Criteria = $this->addPMFieldsToCriteria('cancelled');
    }
    $Criteria->add (AppCacheViewPeer::APP_STATUS, "CANCELLED" , CRITERIA::EQUAL );
    $Criteria->add (AppCacheViewPeer::USR_UID, $userUid);
    $Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'CLOSED');
    
    return $Criteria;
  }
  
    /**
   * gets the CANCELLED cases list criteria for count
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getCancelledCountCriteria ($userUid) {
  	return $this->getCancelled($userUid, true);
  }
  
   /**
   * gets the CANCELLED cases list criteria for list
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getCancelledListCriteria ($userUid) {
  	return $this->getCancelled($userUid, false);
  }

  /**
   * loads the configuration fields from the database based in an action parameter
   * then assemble the Criteria object with these data.
   * @param  String $action
   * @return Criteria object $Criteria
   */
  function addPMFieldsToCriteria($action) {
    $caseColumns = array();
    if (!class_exists('AdditionalTables')){
      require_once ( "classes/model/AdditionalTables.php" );
    }
    $caseReaderFields = array();
    $oCriteria  = new Criteria('workflow');
    $oCriteria->clearSelectColumns ( );
    // default configuration fields array
    $defaultFields = array (
                  'APP_UID',
                  'DEL_INDEX',
                  'APP_NUMBER',
                  'APP_STATUS',
                  'USR_UID',
                  'PREVIOUS_USR_UID',
                  'TAS_UID',
                  'PRO_UID',
                  'DEL_DELEGATE_DATE',
                  'DEL_INIT_DATE',
                  'DEL_TASK_DUE_DATE',
                  'DEL_FINISH_DATE',
                  'DEL_THREAD_STATUS',
                  'APP_THREAD_STATUS',
                  'APP_TITLE',
                  'APP_PRO_TITLE',
                  'APP_TAS_TITLE',
                  'APP_CURRENT_USER',
                  'APP_DEL_PREVIOUS_USER',
                  'DEL_PRIORITY',
                  'DEL_DURATION',
                  'DEL_QUEUE_DURATION',
                  'DEL_DELAY_DURATION',
                  'DEL_STARTED',
                  'DEL_FINISHED',
                  'DEL_DELAYED',
                  'APP_CREATE_DATE',
                  'APP_FINISH_DATE',
                  'APP_UPDATE_DATE',
                  'APP_OVERDUE_PERCENTAGE'
                );
  
    $conf = new Configurations();
    try {
      $confCasesList = $conf->loadObject('casesList',$action,'','','');
    }   catch (Exception $e){
      $confCasesList = array();
    }
    //if there is PMTABLE for this case list:
    if ( count($confCasesList)>1 && isset($confCasesList['PMTable']) && trim($confCasesList['PMTable'])!='') {
    // getting the table name

      $oAdditionalTables = AdditionalTablesPeer::retrieveByPK($confCasesList['PMTable']);
      $tableName = $oAdditionalTables->getAddTabName();
  
      foreach($confCasesList['second']['data'] as $fieldData){
        if (!in_array($fieldData['name'],$defaultFields)){
          $fieldName = $tableName.'.'.$fieldData['name'];
          $oCriteria->addSelectColumn (  $fieldName );
        } 
        else {
          $fieldName = 'APP_CACHE_VIEW.'.$fieldData['name'];
          $oCriteria->addSelectColumn (  $fieldName );
        }
      }
  
      //add the default and hidden DEL_INIT_DATE
      $oCriteria->addSelectColumn ( 'APP_CACHE_VIEW.DEL_INIT_DATE' );
      //Add the JOIN
      $oCriteria->addJoin(AppCacheViewPeer::APP_UID, $tableName.'.APP_UID', Criteria::LEFT_JOIN);
      return $oCriteria;
    } 
    //else this list do not have a PM Table,
    else {
      if (is_array($confCasesList)){
        foreach($confCasesList['second']['data'] as $fieldData){
          $fieldName = 'APP_CACHE_VIEW.'.$fieldData['name'];
          $oCriteria->addSelectColumn (  $fieldName );
        }
      }
      //add the default and hidden DEL_INIT_DATE
      $oCriteria->addSelectColumn ( 'APP_CACHE_VIEW.DEL_INIT_DATE' );
      return $oCriteria;
    }
  }
  
} // AppCacheView
