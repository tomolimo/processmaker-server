<?php
/**
 * AdditionalTables.php
 * @package    workflow.engine.classes.model
 */
require_once 'classes/model/om/BaseAdditionalTables.php';


/**
 * Skeleton subclass for representing a row from the 'ADDITIONAL_TABLES' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 * <juliocesar@colosa.com, julces2000@gmail.com>
 *
 * @package    workflow.engine.classes.model
 */
class AdditionalTables extends BaseAdditionalTables {

  public $fields = array();
  public $primaryKeys = array();

  /**
   * Function load
   * access public
   */
  public function load($sUID, $bFields = false) {
    try {
      $oAdditionalTables = AdditionalTablesPeer::retrieveByPK($sUID);
      if (!is_null($oAdditionalTables)) {
        $aFields = $oAdditionalTables->toArray(BasePeer::TYPE_FIELDNAME);
        $this->fromArray($aFields, BasePeer::TYPE_FIELDNAME);

        if ($bFields) {
          $aFields['FIELDS'] = $this->getFields();
        }
        
        return $aFields;
      }
      else {
        throw(new Exception('This row doesn\'t exist!'));
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  public function getFields()
  {
    if (count($this->fields) > 0) {
      return $this->fields;
    }

    require_once 'classes/model/Fields.php';
    $oCriteria = new Criteria('workflow');

    $oCriteria->addSelectColumn(FieldsPeer::FLD_UID);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_INDEX);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_NAME);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_DESCRIPTION);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_TYPE);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_SIZE);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_NULL);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_AUTO_INCREMENT);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_KEY);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_FOREIGN_KEY);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_FOREIGN_KEY_TABLE);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_DYN_NAME);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_DYN_UID);
    $oCriteria->addSelectColumn(FieldsPeer::FLD_FILTER);
    $oCriteria->add(FieldsPeer::ADD_TAB_UID, $this->getAddTabUid());
    $oCriteria->addAscendingOrderByColumn(FieldsPeer::FLD_INDEX);

    $oDataset = FieldsPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

    while ($oDataset->next()) {
      $this->fields[] = $oDataset->getRow();
    }

    return $this->fields;
  }

  public function getPrimaryKeys()
  {
    $this->primaryKeys = array();
    foreach ($this->fields as $field) {
      if ($field['FLD_KEY'] == '1') {
        $this->primaryKeys[] = $field;
      }
    }
    return $this->primaryKeys;
  }

  public function loadByName($name) {
    try {
      $oCriteria = new Criteria('workflow');
      $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_UID);
      $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_NAME);
      $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_CLASS_NAME);
      $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_DESCRIPTION);
      $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_SDW_LOG_INSERT);
      $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_SDW_LOG_UPDATE);
      $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_SDW_LOG_DELETE);
      $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_SDW_LOG_SELECT);
      $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_SDW_MAX_LENGTH);
      $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_SDW_AUTO_DELETE);
      $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_PLG_UID);
      $oCriteria->addSelectColumn(AdditionalTablesPeer::DBS_UID);
      $oCriteria->add(AdditionalTablesPeer::ADD_TAB_NAME, $name, Criteria::LIKE);

      $oDataset = AdditionalTablesPeer::doSelectRS($oCriteria);
      $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

      $aRows = Array();
      while ($oDataset->next()) {
        $aRows[] = $oDataset->getRow();
      }

      return sizeof($aRows) > 0 ? $aRows : false;
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  /**
   * Create & Update function
   */
  function create($aData, $aFields = array())
  {
    if (!isset($aData['ADD_TAB_UID']) || (isset($aData['ADD_TAB_UID']) && $aData['ADD_TAB_UID'] == '')) {
      $aData['ADD_TAB_UID'] = G::generateUniqueID();
    }

    $oConnection = Propel::getConnection(AdditionalTablesPeer::DATABASE_NAME);

    try {
      $oAdditionalTables = new AdditionalTables();
      $oAdditionalTables->fromArray($aData, BasePeer::TYPE_FIELDNAME);

      if ($oAdditionalTables->validate()) {
        $oConnection->begin();
        $iResult = $oAdditionalTables->save();
        $oConnection->commit();
        /****DEPRECATED
        require_once 'classes/model/ShadowTable.php';
        $oShadowTable = new ShadowTable();
        $oShadowTable->create(array('ADD_TAB_UID' => $aData['ADD_TAB_UID'],
                                    'SHD_ACTION'  => 'CREATE',
                                    'SHD_DETAILS' => serialize($aFields),
                                    'USR_UID'     => (isset($_SESSION['USER_LOGGED']) ? $_SESSION['USER_LOGGED'] : ''),
                                    'APP_UID'     => '',
                                    'SHD_DATE'    => date('Y-m-d H:i:s')));
        */
        return $aData['ADD_TAB_UID'];
      } else {
        $sMessage = '';
        $aValidationFailures = $oAdditionalTables->getValidationFailures();
        foreach($aValidationFailures as $oValidationFailure) {
          $sMessage .= $oValidationFailure->getMessage() . '<br />';
        }
        throw(new Exception('The registry cannot be created!<br />' . $sMessage));
      }
    }
    catch (Exception $oError) {
      $oConnection->rollback();
      throw($oError);
    }
  }

  function update($aData, $aFields = array()) {
    $oConnection = Propel::getConnection(AdditionalTablesPeer::DATABASE_NAME);
    try {
      $oAdditionalTables = AdditionalTablesPeer::retrieveByPK($aData['ADD_TAB_UID']);
      if (!is_null($oAdditionalTables)) {
        $oAdditionalTables->fromArray($aData, BasePeer::TYPE_FIELDNAME);
        if ($oAdditionalTables->validate()) {
          $oConnection->begin();
          $iResult = $oAdditionalTables->save();
          $oConnection->commit();
          /*** DEPRECATED
          require_once 'classes/model/ShadowTable.php';
          $oShadowTable = new ShadowTable();
          $oShadowTable->create(array('ADD_TAB_UID' => $aData['ADD_TAB_UID'],
                                      'SHD_ACTION'  => 'ALTER',
                                      'SHD_DETAILS' => serialize($aFields),
                                      'USR_UID'     => (isset($_SESSION['USER_LOGGED']) ? $_SESSION['USER_LOGGED'] : ''),
                                      'APP_UID'     => '',
                                      'SHD_DATE'    => date('Y-m-d H:i:s')));
          return $iResult;*/
        }
        else {
          $sMessage = '';
          $aValidationFailures = $oAdditionalTables->getValidationFailures();
          foreach($aValidationFailures as $oValidationFailure) {
            $sMessage .= $oValidationFailure->getMessage() . '<br />';
          }
          throw(new Exception('The registry cannot be updated!<br />'.$sMessage));
        }
      }
      else {
        throw(new Exception('This row doesn\'t exist!'));
      }
    }
    catch (Exception $oError) {
      $oConnection->rollback();
      throw($oError);
    }
  }

  function remove($sUID) {
    $oConnection = Propel::getConnection(AdditionalTablesPeer::DATABASE_NAME);
    try {
      $oAdditionalTables = AdditionalTablesPeer::retrieveByPK($sUID);
      if (!is_null($oAdditionalTables)) {
        $aAdditionalTables = $oAdditionalTables->toArray(BasePeer::TYPE_FIELDNAME);
        $oConnection->begin();
        $iResult = $oAdditionalTables->delete();
        $oConnection->commit();
        
        return $iResult;
      }
      else {
        throw(new Exception('This row doesn\'t exist!'));
      }
    }
    catch (Exception $oError) {
      $oConnection->rollback();
      throw($oError);
    }
  }
  
  function deleteAll($id) 
  {
    //deleting pm table
    $additionalTable = AdditionalTables::load($id);
    AdditionalTables::remove($id);
    
    //deleting fields
    require_once 'classes/model/Fields.php';
    $criteria = new Criteria('workflow');
    $criteria->add(FieldsPeer::ADD_TAB_UID, $id);
    FieldsPeer::doDelete($criteria);
    
    //remove all related to pmTable
    G::loadClass('pmTable');
    $pmTable = new pmTable($additionalTable['ADD_TAB_NAME']);
    $pmTable->setDataSource($additionalTable['DBS_UID']);
    $pmTable->remove();
  }

  function getPHPName($sName) {
    $sName = trim($sName);
    $aAux  = explode('_', $sName);
    foreach ($aAux as $iKey => $sPart) {
      $aAux[$iKey] = ucwords(strtolower($sPart));
    }
    return implode('', $aAux);
  }

  function deleteMultiple($arrUID){
  	$arrUIDs = explode(",",$arrUID);
  	foreach ($arrUIDs as $UID){
  		$this->deleteAll($UID);
  	}
  }

  function getDataCriteria($sUID) {
    try {
      $aData = $this->load($sUID, true);
      $sPath = PATH_DB . SYS_SYS . PATH_SEP . 'classes' . PATH_SEP;
      $sClassName = ($aData['ADD_TAB_CLASS_NAME'] != '' ? $aData['ADD_TAB_CLASS_NAME'] : $this->getPHPName($aData['ADD_TAB_NAME']));

      if (file_exists ($sPath . $sClassName . '.php') ) {
        require_once $sPath . $sClassName . '.php';
      } else {
      	return null;
      }

      $sClassPeerName = $sClassName . 'Peer';
      $con = Propel::getConnection($aData['DBS_UID']);
      $oCriteria = new Criteria($aData['DBS_UID']);
      
      //eval('$oCriteria->addSelectColumn(' . $sClassPeerName . '::PM_UNIQUE_ID);');
      eval('$oCriteria->addSelectColumn("\'1\' AS DUMMY");');
      foreach ($aData['FIELDS'] as $aField) {
        eval('$oCriteria->addSelectColumn(' . $sClassPeerName . '::' . $aField['FLD_NAME'] . ');');
      }

      switch ($aField['FLD_TYPE']) {
        case 'VARCHAR':
        case 'TEXT':
        case 'DATE':
//          if($aField['FLD_NULL']!=1)
//            eval('$oCriteria->add(' . $sClassPeerName . '::' . $aField['FLD_NAME'] . ', \'(�_�_�)\', Criteria::NOT_EQUAL);');
        break;
        case 'INT';
        case 'FLOAT':
          eval('$oCriteria->add(' . $sClassPeerName . '::' . $aField['FLD_NAME'] . ', -99999999999, Criteria::NOT_EQUAL);');
        break;
      }
      //eval('$oCriteria->addAscendingOrderByColumn(' . $sClassPeerName . '::PM_UNIQUE_ID);');
      //echo $oCriteria->toString();
      return $oCriteria;
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  function getAllData($sUID, $start=NULL, $limit=NULL)
  {
    $aData = $this->load($sUID, true);
    $aData['DBS_UID'] = $aData['DBS_UID'] ? $aData['DBS_UID'] : 'workflow';
    $sPath = PATH_DB . SYS_SYS . PATH_SEP . 'classes' . PATH_SEP;
    $sClassName = ($aData['ADD_TAB_CLASS_NAME'] != '' ? $aData['ADD_TAB_CLASS_NAME'] : $this->getPHPName($aData['ADD_TAB_NAME']));

    if (file_exists ($sPath . $sClassName . '.php') ) {
      require_once $sPath . $sClassName . '.php';
    } else {
      return null;
    }

    $sClassPeerName = $sClassName . 'Peer';
    $con = Propel::getConnection($aData['DBS_UID']);
    $oCriteria = new Criteria($aData['DBS_UID']);
    
    //eval('$oCriteria->addSelectColumn("\'1\' AS DUMMY");');
    foreach ($aData['FIELDS'] as $aField) {
      eval('$oCriteria->addSelectColumn(' . $sClassPeerName . '::' . $aField['FLD_NAME'] . ');');
      if ($aField['FLD_KEY'] == '1') {
        eval('$oCriteria->addAscendingOrderByColumn(' . $sClassPeerName . '::' . $aField['FLD_NAME'] . ');');
      }
    }

    $oCriteriaCount = clone $oCriteria;
    //$count = $sClassPeerName::doCount($oCriteria);
    eval('$count = '.$sClassPeerName.'::doCount($oCriteria);');

    if (isset($limit)) {
      $oCriteria->setLimit($limit);
    }
    if (isset($start)) {
      $oCriteria->setOffset($start);
    }
    //$rs = $sClassPeerName::doSelectRS($oCriteria);
    eval('$rs = '.$sClassPeerName.'::doSelectRS($oCriteria);');
    $rs->setFetchmode (ResultSet::FETCHMODE_ASSOC);

    $rows = Array();
    while ($rs->next()) {
      $rows[] = $rs->getRow();
    }
    
    return array('rows' => $rows, 'count' => $count);
  }

  function checkClassNotExist($sUID) {
    try {
      $aData = $this->load($sUID, true);
      $sPath = PATH_DB . SYS_SYS . PATH_SEP . 'classes' . PATH_SEP;
      $sClassName = ($aData['ADD_TAB_CLASS_NAME'] != '' ? $aData['ADD_TAB_CLASS_NAME'] : $this->getPHPName($aData['ADD_TAB_NAME']));

      if (file_exists ($sPath . $sClassName . '.php') ) {
        return $sClassName;
      } else {
      	return '';
      }

    } catch (Exception $oError) {
      throw($oError);
    }
  }

  function saveDataInTable($sUID, $aFields) {
    try {
      $aData = $this->load($sUID, true);
      $sPath = PATH_DB . SYS_SYS . PATH_SEP . 'classes' . PATH_SEP;
      $sClassName = ($aData['ADD_TAB_CLASS_NAME'] != '' ? $aData['ADD_TAB_CLASS_NAME'] : $this->getPHPName($aData['ADD_TAB_NAME']));
      // $oConnection = Propel::getConnection(FieldsPeer::DATABASE_NAME);
      $oConnection = Propel::getConnection($aData['DBS_UID']);
      $stmt = $oConnection->createStatement();
      require_once $sPath . $sClassName . '.php';
      $sKeys = '';
      foreach ($aData['FIELDS'] as $aField) {
        if ($aField['FLD_KEY'] == 1) {
          $vValue = $aFields[$aField['FLD_NAME']];
          eval('$' . $aField['FLD_NAME'] . ' = $vValue;');
          $sKeys .= '$' . $aField['FLD_NAME'] . ',';
        }
      }
      $sKeys = substr($sKeys, 0, -1);
      $oClass = new $sClassName;
      foreach ($aFields as $sKey => $sValue) {
        if(!preg_match("/\(?\)/", $sKey))
          eval('$oClass->set' . $this->getPHPName($sKey) . '($aFields["' . $sKey . '"]);');
      }
      if ($oClass->validate()) {
        $iResult = $oClass->save();
      }
      return true;
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  function getDataTable($sUID, $aKeys) {
    try {
      $aData = $this->load($sUID, true);
      $sPath = PATH_DB . SYS_SYS . PATH_SEP . 'classes' . PATH_SEP;
      $sClassName = ($aData['ADD_TAB_CLASS_NAME'] != '' ? $aData['ADD_TAB_CLASS_NAME'] : $this->getPHPName($aData['ADD_TAB_NAME']));
      require_once $sPath . $sClassName . '.php';
      $sKeys = '';
      foreach ($aKeys as $sName => $vValue) {
        eval('$' . $sName . ' = $vValue;');
        $sKeys .= '$' . $sName . ',';
      }
      $sKeys = substr($sKeys, 0, -1);
      eval('$oClass = ' . $sClassName . 'Peer::retrieveByPK(' . $sKeys . ');');
      //eval('$oClass = ' . $sClassName . 'Peer::retrieveByPK($sPMUID);');
      if (!is_null($oClass)) {
        return $oClass->toArray(BasePeer::TYPE_FIELDNAME);
      }
      else {
        return false;
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  function updateDataInTable($sUID, $aFields) {
    try {
      //$sPMUID = $aFields['PM_UNIQUE_ID'];
      $aData  = $this->load($sUID, true);
      $sPath  = PATH_DB . SYS_SYS . PATH_SEP . 'classes' . PATH_SEP;
      $sClassName = ($aData['ADD_TAB_CLASS_NAME'] != '' ? $aData['ADD_TAB_CLASS_NAME'] : $this->getPHPName($aData['ADD_TAB_NAME']));
      $oConnection = Propel::getConnection(FieldsPeer::DATABASE_NAME);
      require_once $sPath . $sClassName . '.php';
      $sKeys = '';
      foreach ($aData['FIELDS'] as $aField) {//$sName => $vValue
        if ($aField['FLD_KEY'] == 1) {
          $vValue = $aFields[$aField['FLD_NAME']];
          eval('$' . $aField['FLD_NAME'] . ' = $vValue;');
          $sKeys .= '$' . $aField['FLD_NAME'] . ',';
        }
      }
      $sKeys = substr($sKeys, 0, -1);
      eval('$oClass = ' . $sClassName . 'Peer::retrieveByPK(' . $sKeys . ');');
      //eval('$oClass = ' . $sClassName . 'Peer::retrieveByPK($sPMUID);');
      if (!is_null($oClass)) {
        $oClass->fromArray($aFields, BasePeer::TYPE_FIELDNAME);
        if ($oClass->validate()) {
          $oConnection->begin();
          $iResult = $oClass->save();
          $oConnection->commit();
          return $iResult;
        }
      }
      else {
        $sMessage = '';
        if ($oClass) {
          $aValidationFailures = $oClass->getValidationFailures();
          foreach($aValidationFailures as $oValidationFailure) {
            $sMessage .= $oValidationFailure->getMessage() . '<br />';
          }
        }
        else {
          $sMessage = 'Error, row cannot updated';
        }
        throw(new Exception('The registry cannot be updated!<br />' . $sMessage));
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  function deleteDataInTable($sUID, $aKeys) {
    try {
      $aData  = $this->load($sUID, true);
      $sPath  = PATH_DB . SYS_SYS . PATH_SEP . 'classes' . PATH_SEP;
      $sClassName = ($aData['ADD_TAB_CLASS_NAME'] != '' ? $aData['ADD_TAB_CLASS_NAME'] : $this->getPHPName($aData['ADD_TAB_NAME']));
      $oConnection = Propel::getConnection(FieldsPeer::DATABASE_NAME);
      require_once $sPath . $sClassName . '.php';
      $sKeys = '';
      foreach ($aKeys as $sName => $vValue) {
        eval('$' . $sName . ' = $vValue;');
        $sKeys .= '$' . $sName . ',';
      }
      $sKeys = substr($sKeys, 0, -1);
      eval('$oClass = ' . $sClassName . 'Peer::retrieveByPK(' . $sKeys . ');');
      //eval('$oClass = ' . $sClassName . 'Peer::retrieveByPK($sPMUID);');
      if (!is_null($oClass)) {
        if ($oClass->validate()) {
          $oConnection->begin();
          $iResult = $oClass->delete();
          $oConnection->commit();
          return $iResult;
        }
      }
      else {
        $sMessage = '';
        $aValidationFailures = $oConnection-->getValidationFailures();
        foreach($aValidationFailures as $oValidationFailure) {
          $sMessage .= $oValidationFailure->getMessage() . '<br />';
        }
        throw(new Exception('The registry cannot be updated!<br />' . $sMessage));
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }


  /**
   * Populate the report table with all case data
   * @param string $sType
   * @param string $sProcessUid
   * @param string $sGrid
   * @return number
   */
  public function populateReportTable($tableName, $sConnection = 'rp', $type = 'NORMAL', $processUid = '', $gridKey = '')
  {
    require_once "classes/model/Application.php";

    $this->className = $this->getPHPName($tableName);
    $this->classPeerName = $this->className . 'Peer';

    if (!file_exists (PATH_WORKSPACE . 'classes/' . $this->className . '.php') ) {
      throw new Exception("ERROR: {$this->className} class file doesn't exit!");
    }

    require_once PATH_WORKSPACE . 'classes/' . $this->className . '.php';

    //select cases for this Process, ordered by APP_NUMBER
    $con = Propel::getConnection($sConnection);
    $stmt = $con->createStatement();
    $criteria = new Criteria('workflow');
    $criteria->add(ApplicationPeer::PRO_UID, $processUid);
    $criteria->addAscendingOrderByColumn(ApplicationPeer::APP_NUMBER);
    $dataset = ApplicationPeer::doSelectRS($criteria);
    $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

    while ($dataset->next()) {
      $row = $dataset->getRow();
      //remove old applications references
      $deleteSql = "DELETE FROM $tableName WHERE APP_UID = '".$row['APP_UID']."'";
      $rs = $stmt->executeQuery($deleteSql);
      // getting the case data
      $caseData = unserialize($row['APP_DATA']);
      
      if ($type == 'GRID') {
        list($gridName, $gridUid)  = explode('-', $gridKey);
        $gridData = isset($caseData[$gridName]) ? $caseData[$gridName] : array();
        
        foreach ($gridData as $i => $gridRow) {
          eval('$obj = new ' .$this->className. '();');
          $obj->fromArray($caseData, BasePeer::TYPE_FIELDNAME);
          $obj->setAppUid($row['APP_UID']);
          $obj->setAppNumber($row['APP_NUMBER']);
          $obj->fromArray(array_change_key_case($gridRow, CASE_UPPER), BasePeer::TYPE_FIELDNAME);
          $obj->setRow($i);
          $obj->save();
          eval('$obj = new ' .$this->className. '();');
        }
      } else {
        eval('$obj = new ' .$this->className. '();');
        $obj->fromArray(array_change_key_case($caseData, CASE_UPPER), BasePeer::TYPE_FIELDNAME);
        $obj->setAppUid($row['APP_UID']);
        $obj->setAppNumber($row['APP_NUMBER']);
        $obj->save();
        $obj = null;
      }
    }
  }

  /**
   * Update the report table with a determinated case data
   * @param string $proUid
   * @param string $appUid
   * @param string $appNumber
   * @param string $caseData  
   */
  public function updateReportTables($proUid, $appUid, $appNumber, $caseData)
  {
    G::loadClass('pmTable');
    //get all Active Report Tables
    $criteria = new Criteria('workflow');
    $criteria->add(AdditionalTablesPeer::PRO_UID, $proUid);
    $dataset = AdditionalTablesPeer::doSelectRS($criteria);
    $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    
    // accomplish all related  report tables for this process that contain case data for the target ($appUid) application
    while ($dataset->next()) {
      $row = $dataset->getRow();
      $className = $row['ADD_TAB_CLASS_NAME'];
      // verify if the report table class exists
      if (!file_exists (PATH_WORKSPACE . 'classes/' . $className . '.php') ) {
        continue;
      }
      // the class exists then load it.
      require_once PATH_WORKSPACE . 'classes/' . $className . '.php'; //
      
      // create a criteria object of report table class
      $c = new Criteria(pmTable::resolveDbSource($row['DBS_UID']));
      // select all related records with this $appUid
      eval('$c->add(' . $className . 'Peer::APP_UID, \'' . $appUid . '\');');
      eval('$records = ' . $className . 'Peer::doSelect($c);');

      switch ($row['ADD_TAB_TYPE']) { //switching by report table type
        case  'NORMAL':
          if (is_array($records) && count($records) > 0) { // if the record already exists on the report table 
            foreach ($records as $record) { //update all records
              $record->fromArray(array_change_key_case($caseData, CASE_UPPER), BasePeer::TYPE_FIELDNAME);
              if ($record->validate()) {
                $record->save();
              }
            }
          }
          else { // there are not any record for this application on the table, then create it
            eval('$obj = new ' . $className . '();');
            $obj->fromArray($caseData, BasePeer::TYPE_FIELDNAME);
            $obj->setAppUid($appUid);
            $obj->setAppNumber($appNumber);
            $obj->save();
          }
          break;
        
        case  'GRID':
          list($gridName, $gridUid)  = explode('-', $row['ADD_TAB_GRID']);
          $gridData = isset($caseData[$gridName]) ? $caseData[$gridName] : array();
          
          // delete old records
          if (is_array($records) && count($records) > 0) {
            foreach ($records as $record) {
              $record->delete();
            }
          }
          // save all grid rows on grid type report table
          foreach ($gridData as $i => $gridRow) {
            eval('$obj = new ' . $className . '();');
            $obj->fromArray(array_change_key_case($gridRow, CASE_UPPER), BasePeer::TYPE_FIELDNAME);
            $obj->setAppUid($appUid);
            $obj->setAppNumber($appNumber);
            $obj->setRow($i);
            $obj->save();
          }
          break;
      }
    }
  }

  public function getTableVars($uid, $bWhitType = false)
  {
    require_once 'classes/model/Fields.php';
    try {
      $oCriteria = new Criteria('workflow');
      $oCriteria->addSelectColumn(FieldsPeer::ADD_TAB_UID);
      $oCriteria->addSelectColumn(FieldsPeer::FLD_NAME);
      $oCriteria->addSelectColumn(FieldsPeer::FLD_TYPE);
      $oCriteria->addSelectColumn(FieldsPeer::FLD_DYN_NAME);
      $oCriteria->add(FieldsPeer::ADD_TAB_UID, $uid);
      $oDataset = ReportVarPeer::doSelectRS($oCriteria);
      $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
      $oDataset->next();
      $aVars = array();
      $aImportedVars = array();//This array will help to control if the variable already exist
      while ($aRow = $oDataset->getRow()) {
            if ($bWhitType) {
                if (!in_array($aRow['FLD_NAME'], $aImportedVars)) {
                  $aImportedVars[]=$aRow['FLD_NAME'];
                  $aVars[] = array('sFieldName' => $aRow['FLD_NAME'], 'sFieldDynName' => $aRow['FLD_DYN_NAME'], 'sType' => $aRow['FLD_TYPE']);
                }
            }else {
              $aVars[] = $aRow['FLD_NAME'];
            }
        $oDataset->next();
      }
      return $aVars;
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  public function getAll($start = 0, $limit = 20, $filter = '', $process = null)
  {
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_UID);
    $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_NAME);
    $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_DESCRIPTION);
    $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_TYPE);
    $oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_TAG);
    $oCriteria->addSelectColumn(AdditionalTablesPeer::PRO_UID);

    if (isset($process)) {
      foreach ($process as $key => $pro_uid) {
        if ($key == 'equal')
          $oCriteria->add(AdditionalTablesPeer::PRO_UID, $pro_uid, Criteria::EQUAL);
        else
          $oCriteria->add(AdditionalTablesPeer::PRO_UID, $pro_uid, Criteria::NOT_EQUAL);
      }
    }

    if ($filter != '' && is_string($filter)) {
      $oCriteria->add(
        $oCriteria->getNewCriterion(AdditionalTablesPeer::ADD_TAB_NAME, '%'.$filter.'%',Criteria::LIKE)->addOr(
        $oCriteria->getNewCriterion(AdditionalTablesPeer::ADD_TAB_DESCRIPTION, '%'.$filter.'%',Criteria::LIKE))
      );
    }

    $criteriaCount = clone $oCriteria;
    $count = AdditionalTablesPeer::doCount($criteriaCount);

    $oCriteria->setLimit($limit);
    $oCriteria->setOffset($start);

    $oDataset = AdditionalTablesPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

    $addTables = Array();
    $proUids   = Array();

    while( $oDataset->next() ) {
      $row = $oDataset->getRow();
      $row['PRO_TITLE'] = $row['PRO_DESCRIPTION'] = '';
      $addTables[] = $row;
      if ($row['PRO_UID'] != '') {
        $proUids[] = $row['PRO_UID'];
      }
    }

    //process details will have the info about the processes
    $procDetails = Array();

    if (count($proUids) > 0) {
      //now get the labels for all process, using an array of Uids,
      $c = new Criteria('workflow');
      //$c->add ( ContentPeer::CON_CATEGORY, 'PRO_TITLE', Criteria::EQUAL );
      $c->add(ContentPeer::CON_LANG, defined('SYS_LANG')? SYS_LANG: 'en', Criteria::EQUAL);
      $c->add(ContentPeer::CON_ID, $proUids, Criteria::IN);

      $dt = ContentPeer::doSelectRS ($c);
      $dt->setFetchmode(ResultSet::FETCHMODE_ASSOC);

      while ($dt->next()) {
        $row = $dt->getRow();
        $procDetails[$row['CON_ID']][$row['CON_CATEGORY']] = $row['CON_VALUE'];
      }

      foreach ($addTables as $i => $addTable) {
        if (isset($procDetails[$addTable['PRO_UID']]['PRO_TITLE']))
          $addTables[$i]['PRO_TITLE'] = $procDetails[$addTable['PRO_UID']]['PRO_TITLE'];

        if (isset($procDetails[$addTable['PRO_UID']]['PRO_DESCRIPTION']))
          $addTables[$i]['PRO_DESCRIPTION'] = $procDetails[$addTable['PRO_UID']]['PRO_DESCRIPTION'];
      }
    }

    // // fltering by proces title
    // if(isset($filter['process'])) {
    //    foreach ($addTables as $i => $addTable) {
    //     if (strpos($addTable['PRO_TITLE'], $filter['process']) === false)
    //       unset($addTables[$i]);
    //   }
    // }

    return array('rows'=>$addTables, 'count'=>$count);
  }
} // AdditionalTables
