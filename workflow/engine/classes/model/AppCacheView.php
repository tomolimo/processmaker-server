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
 
require_once 'classes/model/Application.php';
require_once 'classes/model/AppDelay.php';
require_once 'classes/model/AdditionalTables.php';
 
class AppCacheView extends BaseAppCacheView {
	var $confCasesList;

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
  		case 'to_revise' : 
  	    $Criteria = $this->getToReviseCountCriteria( $userUid );
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
    $Criteria = $this->addPMFieldsToCriteria('sent');

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

  function getSentListProcessCriteria ($userUid) {
    $Criteria = $this->addPMFieldsToCriteria('sent');
    $Criteria->add (AppCacheViewPeer::USR_UID, $userUid);
    return $Criteria;  	
  }

  /*
  * get user's SelfService tasks
  * @param string $sUIDUser
  * @return $rows
  */
  function getSelfServiceTasks($userUid = '')
  {
    $rows[] = array();
    $tasks  = array();

    //check starting task assigned directly to this user
    $c = new Criteria();
    $c->clearSelectColumns();
    $c->addSelectColumn(TaskPeer::TAS_UID);
    $c->addSelectColumn(TaskPeer::PRO_UID);
    $c->addJoin(TaskPeer::PRO_UID, ProcessPeer::PRO_UID, Criteria::LEFT_JOIN);
    $c->addJoin(TaskPeer::TAS_UID, TaskUserPeer::TAS_UID, Criteria::LEFT_JOIN);
    $c->add(ProcessPeer::PRO_STATUS, 'ACTIVE');
    $c->add(TaskPeer::TAS_ASSIGN_TYPE, 'SELF_SERVICE');
    $c->add(TaskUserPeer::USR_UID, $userUid);

    $rs = TaskPeer::doSelectRS($c);
    $rs->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $rs->next();
    $row = $rs->getRow();

    while (is_array($row)) {
        $tasks[] = $row['TAS_UID'];
        $rs->next();
        $row = $rs->getRow();
    }

    //check groups assigned to SelfService task
    G::LoadClass('groups');
    $group = new Groups();
    $aGroups = $group->getActiveGroupsForAnUser($userUid);

    $c = new Criteria();
    $c->clearSelectColumns();
    $c->addSelectColumn(TaskPeer::TAS_UID);
    $c->addSelectColumn(TaskPeer::PRO_UID);
    $c->addJoin(TaskPeer::PRO_UID, ProcessPeer::PRO_UID, Criteria::LEFT_JOIN);
    $c->addJoin(TaskPeer::TAS_UID, TaskUserPeer::TAS_UID, Criteria::LEFT_JOIN);
    $c->add(ProcessPeer::PRO_STATUS, 'ACTIVE');
    $c->add(TaskPeer::TAS_ASSIGN_TYPE, 'SELF_SERVICE');
    $c->add(TaskUserPeer::USR_UID, $aGroups, Criteria::IN);

    $rs = TaskPeer::doSelectRS($c);
    $rs->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $rs->next();
    $row = $rs->getRow();

    while (is_array($row)) {
        $tasks[] = $row['TAS_UID'];
        $rs->next();
        $row = $rs->getRow();
    }

    return $tasks;
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
    $tasks = $this->getSelfServiceTasks( $userUid ); 
    // adding configuration fields from the configuration options
    // and forming the criteria object
    if ( $doCount ) {
      $Criteria  = new Criteria('workflow');
    }
    else {
      $Criteria = $this->addPMFieldsToCriteria('unassigned');
    }
  //  $Criteria->add (AppCacheViewPeer::APP_STATUS, "TO_DO"   );

    $Criteria->add (AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL);
//    $Criteria->add (AppCacheViewPeer::APP_THREAD_STATUS, 'OPEN');
//    $Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');
    
    $Criteria->add(AppCacheViewPeer::USR_UID, '');
    $Criteria->add(AppCacheViewPeer::TAS_UID, $tasks , Criteria::IN );    

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
   * gets the TO_REVISE cases list criteria
   * param $userUid the current userUid
   * param $doCount if true this will return the criteria for count cases only
   * @return Criteria object $Criteria
   */
  function getToRevise ( $userUid, $doCount ) {
    // adding configuration fields from the configuration options
    // and forming the criteria object
    $oCriteria  = new Criteria('workflow');
    $oCriteria->add(ProcessUserPeer::USR_UID, $userUid );
    $oCriteria->add(ProcessUserPeer::PU_TYPE, 'SUPERVISOR');
    $oDataset = ProcessUserPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();
    $aProcesses = array();
    while ($aRow = $oDataset->getRow()) {
        $aProcesses[] = $aRow['PRO_UID'];
        $oDataset->next();
    }

    if ( $doCount ) {
      $c  = new Criteria('workflow');
    }
    else {
      $c = $this->addPMFieldsToCriteria('todo');
    }
    $c->add(AppCacheViewPeer::PRO_UID,           $aProcesses, Criteria::IN);
    $c->add(AppCacheViewPeer::APP_STATUS,        'TO_DO');
    $c->add(AppCacheViewPeer::DEL_FINISH_DATE,   null,  Criteria::ISNULL);
    $c->add(AppCacheViewPeer::APP_THREAD_STATUS, 'OPEN');
    $c->add(AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');

    return $c;
  }
  
    /**
   * gets the ToRevise cases list criteria for count
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getToReviseCountCriteria ($userUid) {
  	return $this->getToRevise($userUid, true);
  }
  
   /**
   * gets the PAUSED cases list criteria for list
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getToReviseListCriteria ($userUid) {
  	return $this->getToRevise($userUid, false);
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
   * gets the ADVANCED SEARCH cases list criteria for count
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getSearchCountCriteria () {
    //$Criteria  = new Criteria('workflow'); this sent a outer and cross join :P :P
    $Criteria = $this->addPMFieldsToCriteria('sent');
    return $Criteria;
  	//return $this->getSearchCriteria( true);
  }
  
  function getSearchAllCount ( ) {
    $CriteriaCount  = new Criteria('workflow');
    //$CriteriaCount->add (ApplicationPeer::USR_UID, '', CRITERIA::NOT_EQUAL );
    $totalCount = ApplicationPeer::doCount( $CriteriaCount);
    
    return $totalCount;
  }

   /**
   * gets the ADVANCED SEARCH cases list criteria for list
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getSearchListCriteria () {
    $Criteria = $this->addPMFieldsToCriteria('sent');
    $Criteria->addAsColumn( 'DEL_INDEX', 'MAX(' . AppCacheViewPeer::DEL_INDEX . ')' );
    
    //$Criteria->add (AppCacheViewPeer::USR_UID, $userUid);

    $Criteria->addGroupByColumn(AppCacheViewPeer::APP_UID);
    return $Criteria;
  	//return $this->getSearchCriteria(false);
  }
  
   /**
   * gets the ADVANCED SEARCH cases list criteria for STATUS
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */
  function getSearchStatusCriteria () {
    $Criteria  = new Criteria('workflow');
    return $Criteria;
  }
  
  
   /**
   * gets the SENT cases list criteria for list
   * param $userUid the current userUid
   * @return Criteria object $Criteria
   */

    /**
   * gets the cases list criteria using the advanced search
   * param $doCount if true this will return the criteria for count cases only
   * @return Criteria object $Criteria
   */
  function getSearchCriteria ( $doCount ) {
    // adding configuration fields from the configuration options
    // and forming the criteria object
    if ( $doCount ) {
      $Criteria  = new Criteria('workflow');
    }
    else {
      $Criteria = $this->addPMFieldsToCriteria('sent');
    }
    //$Criteria->add (AppCacheViewPeer::APP_STATUS, "TO_DO" , CRITERIA::EQUAL );
    $Criteria->add (AppCacheViewPeer::DEL_INDEX, 1);

    //$Criteria->add (AppCacheViewPeer::DEL_FINISH_DATE, null, Criteria::ISNULL);
    //$Criteria->add (AppCacheViewPeer::APP_THREAD_STATUS, 'OPEN');
    //$Criteria->add (AppCacheViewPeer::DEL_THREAD_STATUS, 'OPEN');
    return $Criteria;
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
                  'APP_OVERDUE_PERCENTAGE',
                  'APP_DELAY_UID',
                  'APP_THREAD_INDEX',
                  'APP_DEL_INDEX',
                  'APP_TYPE',
                  'APP_DELEGATION_USER',
                  'APP_ENABLE_ACTION_USER',
                  'APP_ENABLE_ACTION_DATE',
                  'APP_DISABLE_ACTION_USER',
                  'APP_DISABLE_ACTION_DATE',
                  'APP_AUTOMATIC_DISABLED_DATE'
                );
  
    //if there is PMTABLE for this case list:
    if ( count($this->confCasesList)>1 && isset($this->confCasesList['PMTable']) && trim($this->confCasesList['PMTable'])!='') {
    // getting the table name

      $oAdditionalTables = AdditionalTablesPeer::retrieveByPK($this->confCasesList['PMTable']);
      $tableName = $oAdditionalTables->getAddTabName();
  
      foreach($this->confCasesList['second']['data'] as $fieldData){
        if (!in_array($fieldData['name'],$defaultFields)){
          $fieldName = $tableName.'.'.$fieldData['name'];
          $oCriteria->addSelectColumn (  $fieldName );
        }
        else {
          switch ($fieldData['fieldType']){
            case 'case field':
              $configTable = 'APP_CACHE_VIEW';
            break;
            case 'delay field':
              $configTable = 'APP_DELAY';
            break;
            default:
              $configTable = 'APP_CACHE_VIEW';
            break;
          }
          $fieldName = $configTable.'.'.$fieldData['name'];
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
      if (is_array($this->confCasesList)){
        foreach($this->confCasesList['second']['data'] as $fieldData){
           switch ($fieldData['fieldType']){
            case 'case field':
              $configTable = 'APP_CACHE_VIEW';
            break;
            case 'delay field':
              $configTable = 'APP_DELAY';
            break;
            default:
              $configTable = 'APP_CACHE_VIEW';
            break;
          }
          $fieldName = $configTable.'.'.$fieldData['name'];
          $oCriteria->addSelectColumn (  $fieldName );
        }
      } else {
        //foreach($defaultFields as $field){
          $oCriteria->addSelectColumn('*');
        //}
      }
      //add the default and hidden DEL_INIT_DATE
      $oCriteria->addSelectColumn ( 'APP_CACHE_VIEW.DEL_INIT_DATE' );
      return $oCriteria;
    }
  }

  public function getDefaultFields (){
    return array (
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
                  'APP_OVERDUE_PERCENTAGE',
                  'APP_DELAY_UID',
                  'APP_THREAD_INDEX',
                  'APP_DEL_INDEX',
                  'APP_TYPE',
                  'APP_DELEGATION_USER',
                  'APP_ENABLE_ACTION_USER',
                  'APP_ENABLE_ACTION_DATE',
                  'APP_DISABLE_ACTION_USER',
                  'APP_DISABLE_ACTION_DATE',
                  'APP_AUTOMATIC_DISABLED_DATE'
                );
  }
} // AppCacheView
