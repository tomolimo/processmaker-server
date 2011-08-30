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

      $oDataset = FieldsPeer::doSelectRS($oCriteria);
      $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

      $aRows = Array();
      while( $oDataset->next() ){
        $aRows[] = $oDataset->getRow();
      }

      return (sizeof($aRows) > 0)? $aRows: false;
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
      $caseFields = array_change_key_case(unserialize($row['APP_DATA']), CASE_UPPER);
      
      if ($type == 'GRID') {
        list($gridName, $gridUid)  = explode('-', $gridKey);
        $gridName = strtoupper($gridName);
        foreach ($caseFields[$gridName] as $i => $gridRow) {
          $gridRow = array_change_key_case($gridRow, CASE_UPPER);

          eval('$obj = new ' .$this->className. '();');
          $obj->fromArray($caseFields, BasePeer::TYPE_FIELDNAME);
          $obj->setAppUid($row['APP_UID']);
          $obj->setAppNumber($row['APP_NUMBER']);
          $obj->fromArray($gridRow, BasePeer::TYPE_FIELDNAME);
          $obj->setRow($i);
          $obj->save();
          eval('$obj = new ' .$this->className. '();');
        }
      } else {
        eval('$obj = new ' .$this->className. '();');
        $obj->fromArray($caseFields, BasePeer::TYPE_FIELDNAME);
        $obj->setAppUid($row['APP_UID']);
        $obj->setAppNumber($row['APP_NUMBER']);
        $obj->save();
        $obj = null;
      }
    }
  }

  /**
   * Populate Report Table
   */
  public function populateReportTable2($sTableName, $sConnection = 'rp', $sType = 'NORMAL', $aFields = array(), $sProcessUid = '', $sGrid = '')
  {

    require_once "classes/model/Application.php";
    
    $con = Propel::getConnection($sConnection);
    $stmt = $con->createStatement();
    if ($sType == 'GRID') {
      $aAux  = explode('-', $sGrid);
      $sGrid = $aAux[0];
    }

    try {

      $tableExists  = true;
      $sDataBase    = 'database_' . strtolower(DB_ADAPTER);
      if(G::LoadSystemExist($sDataBase)){
        G::LoadSystem($sDataBase);
        $oDataBase = new database();
        $sDataBase = $sConnection;
        if($sDataBase == 'rp')
          $sDataBase = DB_REPORT_NAME;
        if($sDataBase == 'workflow')
          $sDataBase = DB_NAME;
        $tableExists = $oDataBase->tableExists($sTableName, $sDataBase);
      }
      if($tableExists) {

        switch (DB_ADAPTER) {
          case 'mysql':
            //select cases for this Process, ordered by APP_NUMBER
            $oCriteria = new Criteria('workflow');
            $oCriteria->add(ApplicationPeer::PRO_UID, $sProcessUid);
            $oCriteria->addAscendingOrderByColumn(ApplicationPeer::APP_NUMBER);
            $oDataset = ApplicationPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
              $aData = unserialize($aRow['APP_DATA']);

              //delete previous record from this report table ( previous records in case this is a grid )
              $deleteSql = 'DELETE FROM `' . $sTableName . "` WHERE APP_UID = '" . $aRow['APP_UID'] . "'";
              $rsDel = $stmt->executeQuery( $deleteSql );
              if ($sType == 'NORMAL') {
                $sQuery  = 'INSERT INTO `' . $sTableName . '` (';
                $sQuery .= '`APP_UID`,`APP_NUMBER`';

                foreach ($aFields as $aField) {
                  if ($aField['FLD_NAME'] != 'APP_UID' && $aField['FLD_NAME'] != 'APP_NUMBER' && $aField['FLD_NAME'] != 'ROW')
                    $sQuery .= ',`' . $aField['FLD_NAME'] . '`';
                }

                $sQuery .= ") VALUES ('" . $aRow['APP_UID'] . "'," . (int)$aRow['APP_NUMBER'];
                foreach ($aFields as $aField) {
                  if ($aField['FLD_NAME'] == 'APP_UID' || $aField['FLD_NAME'] == 'APP_NUMBER' || $aField['FLD_NAME'] == 'ROW') continue;

                  switch ($aField['FLD_TYPE']) {
                    case 'INT':
                    case 'FLOAT':
                      $sQuery .= ',' . (isset($aData[$aField['FLD_DYN_NAME']]) ? (float)str_replace(',', '', $aData[$aField['FLD_DYN_NAME']]) : '0');
                      break;
                    case 'VARCHAR':
                    case 'TEXT':
                      if (!isset($aData[$aField['FLD_NAME']])) {
                        $aData[$aField['FLD_NAME']] = '';
                      }
                      $sQuery .= ",'" . (isset($aData[$aField['FLD_DYN_NAME']]) ? mysql_real_escape_string($aData[$aField['FLD_DYN_NAME']]) : '') . "'";
                      break;
                    case 'DATE':
                      $value = (isset($aData[$aField['FLD_DYN_NAME']]) && trim($aData[$aField['FLD_DYN_NAME']])) != '' ? "'" . $aData[$aField['FLD_DYN_NAME']] . "'" : 'NULL';
                      $sQuery .= "," . $value;
                      break;
                  }
                }
                $sQuery .= ')';

                $rs = $stmt->executeQuery( $sQuery );
              }
              else {
                if (isset($aData[$sGrid])) {
                  foreach ($aData[$sGrid] as $iRow => $aGridRow) {
                    $sQuery  = 'INSERT INTO `' . $sTableName . '` (';
                    $sQuery .= '`APP_UID`,`APP_NUMBER`,`ROW`';

                    foreach ($aFields as $aField) {
                      if ($aField['FLD_NAME'] != 'APP_UID' && $aField['FLD_NAME'] != 'APP_NUMBER' && $aField['FLD_NAME'] != 'ROW')
                      $sQuery .= ',`' . $aField['FLD_NAME'] . '`';
                    }

                    $sQuery .= ") VALUES ('" . $aRow['APP_UID'] . "'," . (int)$aRow['APP_NUMBER'] . ',' . $iRow;
                    foreach ($aFields as $aField) {
                      if ($aField['FLD_NAME'] != 'APP_UID' || $aField['FLD_NAME'] != 'APP_NUMBER' || $aField['FLD_NAME'] != 'ROW') continue;

                      switch ($aField['FLD_TYPE']) {
                        case 'INT':
                        case 'FLOAT':
                          $sQuery .= ',' . (isset($aGridRow[$aField['FLD_NAME']]) ? (float)str_replace(',', '', $aGridRow[$aField['FLD_NAME']]) : '0');
                          break;
                        case 'VARCHAR':
                        case 'TEXT':
                          if (!isset($aGridRow[$aField['FLD_NAME']])) {
                            $aGridRow[$aField['FLD_NAME']] = '';
                          }
                          $sQuery .= ",'" . (isset($aGridRow[$aField['FLD_NAME']]) ? mysql_real_escape_string($aGridRow[$aField['FLD_NAME']]) : '') . "'";
                          break;
                        case 'DATE':
                          $value = (isset($aGridRow[$aField['FLD_NAME']]) && trim($aGridRow[$aField['FLD_NAME']])) != '' ? "'" . $aGridRow[$aField['FLD_NAME']] . "'" : 'NULL';
                          $sQuery .= "," . $value;
                          break;
                      }
                    }
                    $sQuery .= ')';
                    $rs = $stmt->executeQuery( $sQuery );
                  }
                }
              }
              $oDataset->next();
            }
            break;

            /**
              * For SQLServer code
              */
            case 'mssql':
              $oCriteria = new Criteria('workflow');
              $oCriteria->add(ApplicationPeer::PRO_UID, $sProcessUid);
              $oCriteria->addAscendingOrderByColumn(ApplicationPeer::APP_NUMBER);
              $oDataset = ApplicationPeer::doSelectRS($oCriteria);
              $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
              $oDataset->next();
              while ($aRow = $oDataset->getRow()) {
                $aData = unserialize($aRow['APP_DATA']);
                mysql_query('DELETE FROM [' . $sTableName . "] WHERE APP_UID = '" . $aRow['APP_UID'] . "'");
                if ($sType == 'NORMAL') {
                  $sQuery  = 'INSERT INTO [' . $sTableName . '] (';
                  $sQuery .= '[APP_UID],[APP_NUMBER]';
                  foreach ($aFields as $aField) {
                    $sQuery .= ',[' . $aField['sFieldName'] . ']';
                  }
                  $sQuery .= ") VALUES ('" . $aRow['APP_UID'] . "'," . (int)$aRow['APP_NUMBER'];
                  foreach ($aFields as $aField) {
                    switch ($aField['sType']) {
                      case 'number':
                        $sQuery .= ',' . (isset($aData[$aField['sFieldName']]) ? (float)str_replace(',', '', $aData[$aField['sFieldName']]) : '0');
                      break;
                      case 'char':
                      case 'text':
                        if (!isset($aData[$aField['sFieldName']])) {
                          $aData[$aField['sFieldName']] = '';
                        }
                        $sQuery .= ",'" . (isset($aData[$aField['sFieldName']]) ? mysql_real_escape_string($aData[$aField['sFieldName']]) : '') . "'";
                      break;
                      case 'date':
                        $sQuery .= ",'" . (isset($aData[$aField['sFieldName']]) ? $aData[$aField['sFieldName']] : '') . "'";
                      break;
                    }
                  }
                  $sQuery .= ')';
                  $rs = $stmt->executeQuery( $sQuery );
                }
                else {
                  if (isset($aData[$sGrid])) {
                    foreach ($aData[$sGrid] as $iRow => $aGridRow) {
                      $sQuery  = 'INSERT INTO [' . $sTableName . '] (';
                      $sQuery .= '`APP_UID`,`APP_NUMBER`,`ROW`';
                      foreach ($aFields as $aField) {
                        $sQuery .= ',[' . $aField['sFieldName'] . ']';
                      }
                      $sQuery .= ") VALUES ('" . $aRow['APP_UID'] . "'," . (int)$aRow['APP_NUMBER'] . ',' . $iRow;
                      foreach ($aFields as $aField) {
                        switch ($aField['sType']) {
                          case 'number':
                            $sQuery .= ',' . (isset($aGridRow[$aField['sFieldName']]) ? (float)str_replace(',', '', $aGridRow[$aField['sFieldName']]) : '0');
                          break;
                          case 'char':
                          case 'text':
                            if (!isset($aGridRow[$aField['sFieldName']])) {
                              $aGridRow[$aField['sFieldName']] = '';
                            }
                            $sQuery .= ",'" . (isset($aGridRow[$aField['sFieldName']]) ? mysql_real_escape_string($aGridRow[$aField['sFieldName']]) : '') . "'";
                          break;
                          case 'date':
                            $sQuery .= ",'" . (isset($aGridRow[$aField['sFieldName']]) ? $aGridRow[$aField['sFieldName']] : '') . "'";
                          break;
                        }
                      }
                      $sQuery .= ')';
                      $rs = $stmt->executeQuery( $sQuery );
                    }
                  }
                }
                $oDataset->next();
              }
            break;

        }
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }



  public function updateReportTables($sProcessUid, $sApplicationUid, $iApplicationNumber, $aFields)
  {
  try {

    //get all Active Report Tables
    $oCriteria = new Criteria('workflow');
    $oCriteria->add(AdditionalTablesPeer::PRO_UID, $sProcessUid);
    //$oCriteria->add(AdditionalTablesPeer::REP_TAB_STATUS, 'ACTIVE');
    $oDataset = AdditionalTablesPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();
    $aVars = array();

    while ($aRow = $oDataset->getRow()) {
      //$aRow['REP_TAB_NAME'] = $this->sPrefix . $aRow['REP_TAB_NAME'];

      $con = Propel::getConnection($aRow['DBS_UID']);
      $stmt = $con->createStatement();
      switch (DB_ADAPTER) {

        case 'mysql':
          $aTableFields = $this->getTableVars($aRow['ADD_TAB_UID'], true);

          if ($aRow['ADD_TAB_TYPE'] == 'NORMAL') {
            $sqlExists = "SELECT * FROM `" . $aRow['ADD_TAB_NAME'] . "` WHERE APP_UID = '" . $sApplicationUid . "'";
            $rsExists  = $stmt->executeQuery( $sqlExists, ResultSet::FETCHMODE_ASSOC);
            $rsExists->next();
            $aRow2 = $rsExists->getRow();

            if ( is_array( $aRow2) ) {
              $sQuery  = 'UPDATE `' . $aRow['ADD_TAB_NAME'] . '` SET ';
              foreach ($aTableFields as $aField) {

                if ($aField['sFieldName'] == 'APP_UID' || $aField['sFieldName'] == 'APP_NUMBER' || $aField['sFieldName'] == 'ROW') continue;
                $sQuery .= '`' . $aField['sFieldName'] . '` = ';

                switch ($aField['sType']) {
                  case 'FLOAT':
                  case 'INT':
                    $sQuery .= (isset($aFields[$aField['sFieldDynName']]) ? (float)str_replace(',', '', $aFields[$aField['sFieldDynName']]) : '0') . ',';
                  break;
                  case 'VARCHAR':
                  case 'TEXT':
                    if (!isset($aFields[$aField['sFieldDynName']])) {
                      $aFields[$aField['sFieldDynName']] = '';
                    }
                    $sQuery .= "'" . (isset($aFields[$aField['sFieldDynName']]) ? mysql_real_escape_string($aFields[$aField['sFieldDynName']]) : '') . "',";
                  break;
                  case 'DATE':
                    $mysqlDate = (isset($aFields[$aField['sFieldDynName']]) ? $aFields[$aField['sFieldDynName']] : '') ;
                    if ($mysqlDate!='') {
                      $mysqlDate = str_replace('/', '-', $mysqlDate);
                      $mysqlDate = date( 'Y-m-d',  strtotime($mysqlDate) );
                    }
                    $sQuery .= "'" . $mysqlDate . "',";
                  break;
                }
              }
              $sQuery  = substr($sQuery, 0, -1);
              $sQuery .= " WHERE APP_UID = '" . $sApplicationUid . "'";
            }
            else {
              $sQuery  = 'INSERT INTO `' . $aRow['ADD_TAB_NAME'] . '` (';
              $sQuery .= '`APP_UID`,`APP_NUMBER`';
              foreach ($aTableFields as $aField) {
                if ($aField['sFieldName'] != 'APP_UID' && $aField['sFieldName'] != 'APP_NUMBER' && $aField['sFieldName'] != 'ROW')
                  $sQuery .= ',`' . $aField['sFieldName'] . '`';
              }
              $sQuery .= ") VALUES ('" . $sApplicationUid . "'," . (int)$iApplicationNumber;
              foreach ($aTableFields as $aField) {
                if ($aField['sFieldName'] == 'APP_UID' || $aField['sFieldName'] == 'APP_NUMBER' || $aField['sFieldName'] == 'ROW') continue;

                switch ($aField['sType']) {
                  case 'FLOAT':
                  case 'INT':
                    $sQuery .= ',' . (isset($aFields[$aField['sFieldDynName']]) ? (float)str_replace(',', '', $aFields[$aField['sFieldDynName']]) : '0');
                    break;
                  case 'VARCHAR':
                  case 'TEXT':
                    if (!isset($aFields[$aField['sFieldDynName']])) {
                      $aFields[$aField['sFieldDynName']] = '';
                    }
                    $sQuery .= ",'" . (isset($aFields[$aField['sFieldDynName']]) ? mysql_real_escape_string($aFields[$aField['sFieldDynName']]) : '') . "'";
                    break;
                  case 'DATE':
                    $mysqlDate = ( isset($aFields[$aField['sFieldDynName']]) ? $aFields[$aField['sFieldDynName']] : '' );
                    if ($mysqlDate!='') {
                      $mysqlDate = str_replace( '/', '-', $mysqlDate );
                      $mysqlDate = date( 'Y-m-d',  strtotime($mysqlDate) );
                    }
                    $sQuery .= ",'" . $mysqlDate  . "'";
                    break;
                }
              }
              $sQuery .= ')';
            }

            $rs = $stmt->executeQuery( $sQuery );
          }
          else {
            //remove old rows from database
            $sqlDelete = 'DELETE FROM `' . $aRow['ADD_TAB_NAME'] . "` WHERE APP_UID = '" . $sApplicationUid . "'";
            $rsDelete  = $stmt->executeQuery( $sqlDelete );

            $aAux = explode('-', $aRow['ADD_TAB_GRID']);
            if (isset($aFields[$aAux[0]])) {
              foreach ($aFields[$aAux[0]] as $iRow => $aGridRow) {
                $sQuery  = 'INSERT INTO `' . $aRow['ADD_TAB_NAME'] . '` (';
                $sQuery .= '`APP_UID`,`APP_NUMBER`,`ROW`';
                foreach ($aTableFields as $aField) {
                  if ($aField['sFieldName'] != 'APP_UID' && $aField['sFieldName'] != 'APP_NUMBER' && $aField['sFieldName'] != 'ROW')
                    $sQuery .= ',`' . $aField['sFieldName'] . '`';
                }
                $sQuery .= ") VALUES ('" . $sApplicationUid . "'," . (int)$iApplicationNumber . ',' . $iRow;
                foreach ($aTableFields as $aField) {
                  if ($aField['sFieldName'] == 'APP_UID' || $aField['sFieldName'] == 'APP_NUMBER' || $aField['sFieldName'] == 'ROW') continue;

                  switch ($aField['sType']) {
                    case 'FLOAT':
                    case 'INT':
                      $sQuery .= ',' . (isset($aGridRow[$aField['sFieldDynName']]) ? (float)str_replace(',', '', $aGridRow[$aField['sFieldDynName']]) : '0');
                      break;
                    case 'VARCHAR':
                    case 'TEXT':
                      if (!isset($aGridRow[$aField['sFieldDynName']])) {
                        $aGridRow[$aField['sFieldDynName']] = '';
                      }
                      $sQuery .= ",'" . (isset($aGridRow[$aField['sFieldDynName']]) ? mysql_real_escape_string($aGridRow[$aField['sFieldDynName']]) : '') . "'";
                      break;
                    case 'DATE':
                      $sQuery .= ",'" . (isset($aGridRow[$aField['sFieldDynName']]) ? $aGridRow[$aField['sFieldDynName']] : '') . "'";
                      break;
                  }
                }
                $sQuery .= ')';
                $rs =$stmt->executeQuery( $sQuery );
              }
            }
          }
          break;

         /**
          * For SQLServer code
          */
          case 'mssql':
            $aTableFields = $this->getTableVars($aRow['REP_TAB_UID'], true);
            if ($aRow['REP_TAB_TYPE'] == 'NORMAL') {
              $oDataset2 = mssql_query("SELECT * FROM [" . $aRow['REP_TAB_NAME'] . "] WHERE APP_UID = '" . $sApplicationUid . "'");
              if ($aRow2 = mssql_fetch_row($oDataset2)) {
                $sQuery  = 'UPDATE [' . $aRow['REP_TAB_NAME'] . '] SET ';
                foreach ($aTableFields as $aField) {
                  $sQuery .= '[' . $aField['sFieldName'] . '] = ';
                  switch ($aField['sType']) {
                    case 'number':
                      $sQuery .= (isset($aFields[$aField['sFieldName']]) ? (float)str_replace(',', '', $aFields[$aField['sFieldName']]) : '0') . ',';
                    break;
                    case 'char':
                    case 'text':
                      if (!isset($aFields[$aField['sFieldName']])) {
                        $aFields[$aField['sFieldName']] = '';
                      }
                      $sQuery .= "'" . (isset($aFields[$aField['sFieldName']]) ? mysql_real_escape_string($aFields[$aField['sFieldName']]) : '') . "',";
                    break;
                    case 'date':
                      $sQuery .= "'" . (isset($aFields[$aField['sFieldName']]) ? $aFields[$aField['sFieldName']] : '') . "',";
                    break;
                  }
                }
                $sQuery  = substr($sQuery, 0, -1);
                $sQuery .= " WHERE APP_UID = '" . $sApplicationUid . "'";
              }
              else {
                $sQuery  = 'INSERT INTO [' . $aRow['REP_TAB_NAME'] . '] (';
                $sQuery .= '[APP_UID],[APP_NUMBER]';
                foreach ($aTableFields as $aField) {
                  $sQuery .= ',[' . $aField['sFieldName'] . ']';
                }
                $sQuery .= ") VALUES ('" . $sApplicationUid . "'," . (int)$iApplicationNumber;
                foreach ($aTableFields as $aField) {
                  switch ($aField['sType']) {
                    case 'number':
                      $sQuery .= ',' . (isset($aFields[$aField['sFieldName']]) ? (float)str_replace(',', '', $aFields[$aField['sFieldName']]) : '0');
                    break;
                    case 'char':
                    case 'text':
                      if (!isset($aFields[$aField['sFieldName']])) {
                        $aFields[$aField['sFieldName']] = '';
                      }
                      $sQuery .= ",'" . (isset($aFields[$aField['sFieldName']]) ? mysql_real_escape_string($aFields[$aField['sFieldName']]) : '') . "'";
                    break;
                    case 'date':
                      $sQuery .= ",'" . (isset($aFields[$aField['sFieldName']]) ? $aFields[$aField['sFieldName']] : '') . "'";
                    break;
                  }
                }
                $sQuery .= ')';
              }
              $rs = $stmt->executeQuery( $sQuery );
            }
            else {
              mysql_query('DELETE FROM [' . $aRow['REP_TAB_NAME'] . "] WHERE APP_UID = '" . $sApplicationUid . "'");
              $aAux = explode('-', $aRow['REP_TAB_GRID']);
              if (isset($aFields[$aAux[0]])) {
                foreach ($aFields[$aAux[0]] as $iRow => $aGridRow) {
                  $sQuery  = 'INSERT INTO [' . $aRow['REP_TAB_NAME'] . '] (';
                  $sQuery .= '[APP_UID],[APP_NUMBER],[ROW]';
                  foreach ($aTableFields as $aField) {
                    $sQuery .= ',[' . $aField['sFieldName'] . ']';
                  }
                  $sQuery .= ") VALUES ('" . $sApplicationUid . "'," . (int)$iApplicationNumber . ',' . $iRow;
                  foreach ($aTableFields as $aField) {
                    switch ($aField['sType']) {
                      case 'number':
                        $sQuery .= ',' . (isset($aGridRow[$aField['sFieldName']]) ? (float)str_replace(',', '', $aGridRow[$aField['sFieldName']]) : '0');
                      break;
                      case 'char':
                      case 'text':
                        if (!isset($aGridRow[$aField['sFieldName']])) {
                          $aGridRow[$aField['sFieldName']] = '';
                        }
                        $sQuery .= ",'" . (isset($aGridRow[$aField['sFieldName']]) ? mysql_real_escape_string($aGridRow[$aField['sFieldName']]) : '') . "'";
                      break;
                      case 'date':
                        $sQuery .= ",'" . (isset($aGridRow[$aField['sFieldName']]) ? $aGridRow[$aField['sFieldName']] : '') . "'";
                      break;
                    }
                  }
                  $sQuery .= ')';
                  $rs =$stmt->executeQuery( $sQuery );
                }
              }
            }
          break;

      }
      $oDataset->next();
    }
  }
  catch (Exception $oError) {
    throw($oError);
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
