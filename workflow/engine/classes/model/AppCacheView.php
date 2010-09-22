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
   * loads the configuration fields from the database based in an action parameter
   * then assemble the Criteria object with these data.
   * @param  String $action
   * @return Criteria object $Criteria
   */
  function addPMFieldsToCriteria($action) {
    $caseColumns = array();
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
    $confCasesList = $conf->loadObject('casesList',$action,'','','');
    
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
      foreach($confCasesList['second']['data'] as $fieldData){
        $fieldName = 'APP_CACHE_VIEW.'.$fieldData['name'];
        $oCriteria->addSelectColumn (  $fieldName );
      }
      //add the default and hidden DEL_INIT_DATE
      $oCriteria->addSelectColumn ( 'APP_CACHE_VIEW.DEL_INIT_DATE' );
      return $oCriteria;
    }
  }
  
} // AppCacheView
