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
  private $aDef = array('mysql' => array('TEXT' => 'TEXT',
                                         'CHAR'   => 'CHAR',
                                         'VARCHAR'   => 'VARCHAR',
                                         'INT'   => 'INT',
                                         'FLOAT' => 'FLOAT',
                                         'DATE'   => 'DATE'),
                      'pgsql' => array('TEXT' => 'TEXT',
                                       'CHAR'   => 'CHAR',
                                       'VARCHAR'   => 'VARCHAR',
                                       'INT'   => 'INTEGER',
                                       'FLOAT' => 'REAL',
                                       'DATE'   => 'DATE'),
                      'mssql' => array('TEXT' => 'TEXT',
                                       'CHAR'   => 'NCHAR',
                                       'VARCHAR'   => 'NVARCHAR',
                                       'INT'   => 'INTEGER',
                                       'FLOAT' => 'FLOAT',
                                       'DATE'   => 'CHAR (19)') 
                      );
  /**
   * Function load
   * access public
   */
  public function load($sUID, $bFields = false) {
    try {
      $oAdditionalTables = AdditionalTablesPeer::retrieveByPK($sUID);
      if (!is_null($oAdditionalTables)) {
        $aFields = $oAdditionalTables->toArray(BasePeer::TYPE_FIELDNAME);
        if ($bFields) {
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
          $oCriteria->add(FieldsPeer::ADD_TAB_UID, $sUID);
          $oCriteria->addAscendingOrderByColumn(FieldsPeer::FLD_INDEX);
          $oDataset = FieldsPeer::doSelectRS($oCriteria);
          $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
          $oDataset->next();
          $aFields['FIELDS'] = array();
          $i = 1;
          while ($aRow = $oDataset->getRow()) {
            $aFields['FIELDS'][$i] = $aRow;
            $oDataset->next();
            $i++;
          }
        }
        $this->fromArray($aFields, BasePeer::TYPE_FIELDNAME);
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

  function create($aData, $aFields = array()) {
    if (!isset($aData['ADD_TAB_UID'])) {
      $aData['ADD_TAB_UID'] = G::generateUniqueID();
    }
    else {
      if ($aData['ADD_TAB_UID'] == '') {
        $aData['ADD_TAB_UID'] = G::generateUniqueID();
      }
    }
    $oConnection = Propel::getConnection(AdditionalTablesPeer::DATABASE_NAME);
    try {
      $oAdditionalTables = new AdditionalTables();
      $oAdditionalTables->fromArray($aData, BasePeer::TYPE_FIELDNAME);
      if ($oAdditionalTables->validate()) {
        $oConnection->begin();
        $iResult = $oAdditionalTables->save();
        $oConnection->commit();
        require_once 'classes/model/ShadowTable.php';
        $oShadowTable = new ShadowTable();
        $oShadowTable->create(array('ADD_TAB_UID' => $aData['ADD_TAB_UID'],
                                    'SHD_ACTION'  => 'CREATE',
                                    'SHD_DETAILS' => serialize($aFields),
                                    'USR_UID'     => (isset($_SESSION['USER_LOGGED']) ? $_SESSION['USER_LOGGED'] : ''),
                                    'APP_UID'     => '',
                                    'SHD_DATE'    => date('Y-m-d H:i:s')));
        return $aData['ADD_TAB_UID'];
      }
      else {
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
          require_once 'classes/model/ShadowTable.php';
          $oShadowTable = new ShadowTable();
          $oShadowTable->create(array('ADD_TAB_UID' => $aData['ADD_TAB_UID'],
                                      'SHD_ACTION'  => 'ALTER',
                                      'SHD_DETAILS' => serialize($aFields),
                                      'USR_UID'     => (isset($_SESSION['USER_LOGGED']) ? $_SESSION['USER_LOGGED'] : ''),
                                      'APP_UID'     => '',
                                      'SHD_DATE'    => date('Y-m-d H:i:s')));
          return $iResult;
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
        require_once 'classes/model/ShadowTable.php';
        if ($aAdditionalTables['ADD_TAB_SDW_AUTO_DELETE'] == 1) {
          $oCriteria = new Criteria('workflow');
          $oCriteria->add(ShadowTablePeer::ADD_TAB_UID, $sUID);
          ShadowTablePeer::doDelete($oCriteria);
        }
        else {
          $oShadowTable = new ShadowTable();
          $oShadowTable->create(array('ADD_TAB_UID' => $sUID,
                                      'SHD_ACTION'  => 'DROP',
                                      'SHD_DETAILS' => '',
                                      'USR_UID'     => (isset($_SESSION['USER_LOGGED']) ? $_SESSION['USER_LOGGED'] : ''),
                                      'APP_UID'     => '',
                                      'SHD_DATE'    => date('Y-m-d H:i:s')));
        }
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

  function createTable($sTableName, $sConnection = 'wf', $aFields = array()) {
    $sTableName = $sTableName;
    if ($sConnection == '') {
      $sConnection = 'wf';
    }
    $sDBName = 'DB' . ($sConnection != 'wf' ? '_' . strtoupper($sConnection) : '') . '_NAME';
    $sDBHost = 'DB' . ($sConnection != 'wf' ? '_' . strtoupper($sConnection) : '') . '_HOST';
    $sDBUser = 'DB' . ($sConnection != 'wf' ? '_' . strtoupper($sConnection) : '') . '_USER';
    $sDBPass = 'DB' . ($sConnection != 'wf' ? '_' . strtoupper($sConnection) : '') . '_PASS';
    try {
      switch (DB_ADAPTER) {
        case 'mysql':
          eval('$oConnection = @mysql_connect(' . $sDBHost . ', ' . $sDBUser . ', ' . $sDBPass . ');');
          if (!$oConnection) {
            throw new Exception('Cannot connect to the server!');
          }
          eval("if (!@mysql_select_db($sDBName)) {
            throw new Exception('Cannot connect to the database ' . $sDBName . '!');
          }");
          $sQuery = 'CREATE TABLE IF NOT EXISTS `' . $sTableName . '` (';
          $aPKs   = array();
          foreach ($aFields as $aField) {
          	$aField['sFieldName'] = strtoupper($aField['sFieldName']);
            switch ($aField['sType']) {
              case 'VARCHAR':
                $sQuery .= '`' . $aField['sFieldName'] . '` ' . $aField['sType'] . '(' . $aField['iSize'] . ')' . " " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . " DEFAULT '',";
              break;
              case 'TEXT':
                $sQuery .= '`' . $aField['sFieldName'] . '` ' . $aField['sType'] . " " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . " DEFAULT '',";
              break;
              case 'DATE':
                $sQuery .= '`' . $aField['sFieldName'] . '` ' . $aField['sType'] . " " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . " ,"; // " DEFAULT '0000-00-00',";
              break;
              case 'INT':
                $sQuery .= '`' . $aField['sFieldName'] . '` ' . $aField['sType'] . '(' . $aField['iSize'] . ')' . " " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . ' ' . ($aField['bAI'] ? 'AUTO_INCREMENT' : "DEFAULT '0'") . ',';
                if ($aField['bAI']) {
                  if (!in_array('`' . $aField['sFieldName'] . '`', $aPKs)) {
                    $aPKs[] = '`' . $aField['sFieldName'] . '`';
                  }
                }
              break;
              case 'FLOAT':
                $sQuery .= '`' . $aField['sFieldName'] . '` ' . $aField['sType'] . '(' . $aField['iSize'] . ')' . " " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . " DEFAULT '0',";
              break;
            }
            if ($aField['bPrimaryKey'] == 1) {
              if (!in_array('`' . $aField['sFieldName'] . '`', $aPKs)) {
                $aPKs[] = '`' . $aField['sFieldName'] . '`';
              }
            }
          }
          $sQuery  = substr($sQuery, 0, -1);
          if (!empty($aPKs)) {
            $sQuery .= ',PRIMARY KEY (' . implode(',', $aPKs) . ')';
          }
          $sQuery .= ') DEFAULT CHARSET=utf8;';
          if (!@mysql_query($sQuery)) {
            throw new Exception('Cannot create the table "' . $sTableName . '"!');
          }
        break;
        case 'mssql':
          $sDBAdapter = DB_ADAPTER;
          $sDBUser    = DB_USER;
          $sDBPass    = DB_PASS;
          $sDBHost    = DB_HOST; // substr(DB_HOST, 0, strpos(DB_HOST,':')); 
          $sDBName    = DB_NAME;
          
          $sDBHost = substr($sDBHost, 0, strpos($sDBHost,':'));
          
          $dsn        = $sDBAdapter . '://' . $sDBUser . ':' . $sDBPass . '@' . $sDBHost . '/' . $sDBName;
          
          
          $db =& DB::Connect( $dsn);
          if (PEAR::isError($db)) { die($db->getMessage()); }         
          
          $sQuery = 'CREATE TABLE ' . $sTableName . ' (';
          $aPKs   = array();
          foreach ($aFields as $aField) {
            switch ($aField['sType']) {
              case 'VARCHAR':
                $sQuery .= ' ' . $aField['sFieldName'] . ' ' . $aField['sType'] . '(' . $aField['iSize'] . ')' . " " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . " DEFAULT '',";
              break;
              case 'TEXT':
                $sQuery .= ' ' . $aField['sFieldName'] . ' ' . $aField['sType'] . ' ,' ; ///-- " " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . " DEFAULT '',";
              break;
              case 'DATE':
                //  In cases of incompatibility, use char(19)
                $sQuery .=  $aField['sFieldName'] . " char(19) " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . " DEFAULT '0000-00-00',";
              break;
              case 'INT':
                $sQuery .=  $aField['sFieldName'] . ' ' . $aField['sType'] . " " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . ' ' . ($aField['bAI'] ? 'AUTO_INCREMENT' : "DEFAULT '0'") . ',';
                if ($aField['bAI']) {
                  if (!in_array(' ' . $aField['sFieldName'] . ' ', $aPKs)) {
                    $aPKs[] = ' ' . $aField['sFieldName'] . ' ';
                  }
                }
              break;
              case 'FLOAT':
                $sQuery .= ' ' . $aField['sFieldName'] . '  ' . $aField['sType'] . " " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . " DEFAULT '0',";
              break;
            }
            if ($aField['bPrimaryKey'] == 1) {
              if (!in_array(' ' . $aField['sFieldName'] . ' ', $aPKs)) {
                $aPKs[] = ' ' . $aField['sFieldName'] . ' ';
              }
            }
          }
          $sQuery  = substr($sQuery, 0, -1);
          if (!empty($aPKs)) {
            $sQuery .= ',PRIMARY KEY (' . implode(',', $aPKs) . ')';
          }
          $sQuery .= ') ';
          $res = @$db->query($sQuery);
          if (!$res) {
            throw new Exception('Cannot create the table "' . $sTableName . '"!');
          }
          
        break;
     
      
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  function updateTable($sTableName, $sConnection = 'wf', $aNewFields = array(), $aOldFields = array()) {
    try {
      //$aKeys           = array('PM_UNIQUE_ID');
      $aKeys           = array();
      $aFieldsToAdd    = array();
      $aFieldsToDelete = array();
      $aFieldsToAlter  = array();
      foreach ($aNewFields as $aNewField) {
      	$aNewField['FLD_NAME'] = strtoupper($aNewField['FLD_NAME']);
        if (!isset($aOldFields[$aNewField['FLD_UID']])) {
          $aFieldsToAdd[] = $aNewField;
        }
        if (($aNewField['FLD_KEY'] == 'on') || ($aNewField['FLD_AUTO_INCREMENT'] == 'on')) {
          if (!in_array($aNewField['FLD_NAME'], $aKeys)) {
            $aKeys[] = $aNewField['FLD_NAME'];
          }
        }
      }
      foreach ($aOldFields as $aOldField) {
      	$aOldField['FLD_NAME'] = strtoupper($aOldField['FLD_NAME']);
        if (!isset($aNewFields[$aOldField['FLD_UID']])) {
          $aFieldsToDelete[] = $aOldField;
        }
      }
      foreach ($aNewFields as $aNewField) {
        if (isset($aOldFields[$aNewField['FLD_UID']])) {
          $bEqual = true;
          if (trim($aNewField['FLD_NAME']) != trim($aOldField['FLD_NAME'])) {
            $bEqual = false;
          }
          if (trim($aNewField['FLD_TYPE']) != trim($aOldField['FLD_TYPE'])) {
            $bEqual = false;
          }
          if (trim($aNewField['FLD_SIZE']) != trim($aOldField['FLD_SIZE'])) {
            $bEqual = false;
          }
          if (trim($aNewField['FLD_NULL']) != trim($aOldField['FLD_NULL'])) {
            $bEqual = false;
          }
          if (trim($aNewField['FLD_AUTO_INCREMENT']) != trim($aOldField['FLD_AUTO_INCREMENT'])) {
            $bEqual = false;
          }
          if (trim($aNewField['FLD_KEY']) != trim($aOldField['FLD_KEY'])) {
            $bEqual = false;
          }
          if (!$bEqual) {
            $aNewField['FLD_NAME_OLD'] = $aOldFields[$aNewField['FLD_UID']]['FLD_NAME'];
            $aFieldsToAlter[] = $aNewField;
          }
        }
      }
      G::LoadSystem('database_' . strtolower(DB_ADAPTER));
      $oDataBase = new database(DB_ADAPTER, DB_HOST, DB_USER, DB_PASS, DB_NAME);
      $oDataBase->iFetchType = MYSQL_NUM;
      $oDataBase->executeQuery($oDataBase->generateDropPrimaryKeysSQL($sTableName));
      foreach ($aFieldsToAdd as $aFieldToAdd) {
        switch ($aFieldToAdd['FLD_TYPE']) {
          case 'VARCHAR':
            $aData = array('Type'    => 'VARCHAR(' . $aFieldToAdd['FLD_SIZE'] . ')',
                           'Null'    => ($aFieldToAdd['FLD_NULL'] == 'on' ? 'YES' : ''),
                           'Default' => '');
          break;
          case 'TEXT':
            $aData = array('Type'    => 'TEXT',
                           'Null'    => ($aFieldToAdd['FLD_NULL'] == 'on' ? 'YES' : ''),
                           'Default' => '');
          break;
          case 'DATE':
            $aData = array('Type'    => 'DATE', 'Null' => 'YES');
                          // 'Null'    => ($aFieldToAdd['FLD_NULL'] == 'on' ? 'YES' : ''),
                          // 'Default' => 'NULL'); // '0000-00-00');
          break;
          case 'INT':
            $aData = array('Type'    => 'INT(' . (int)$aFieldToAdd['FLD_SIZE'] . ')',
                           'Null'    => ($aFieldToAdd['FLD_NULL'] == 'on' ? 'YES' : ''),
                           'Default' => '0',
                           'AI'      => ($aFieldToAdd['FLD_AUTO_INCREMENT'] == 'on' ? 1 : 0));
          break;
          case 'FLOAT':
            $aData = array('Type'    => 'FLOAT(' . (int)$aFieldToAdd['FLD_SIZE'] . ')',
                           'Null'    => ($aFieldToAdd['FLD_NULL'] == 'on' ? 'YES' : ''),
                           'Default' => '0');
          break;
        }
        //echo $oDataBase->generateAddColumnSQL($sTableName, $aFieldToAdd['FLD_NAME'], $aData);
        $oDataBase->executeQuery($oDataBase->generateAddColumnSQL($sTableName, strtoupper($aFieldToAdd['FLD_NAME']), $aData));
      }
      foreach ($aFieldsToDelete as $aFieldToDelete) {
        $oDataBase->executeQuery($oDataBase->generateDropColumnSQL($sTableName, strtoupper($aFieldToDelete['FLD_NAME'])));
      }
      //die;
      $oDataBase->executeQuery($oDataBase->generateAddPrimaryKeysSQL($sTableName, $aKeys));
      foreach ($aFieldsToAlter as $aFieldToAlter) {
        switch ($aFieldToAlter['FLD_TYPE']) {
          case 'VARCHAR':
            $aData = array('Type'    => 'VARCHAR(' . $aFieldToAlter['FLD_SIZE'] . ')',
                           'Null'    => ($aFieldToAlter['FLD_NULL'] == 'on' ? 'YES' : ''),
                           'Default' => '');
          break;
          case 'TEXT':
            $aData = array('Type'    => 'TEXT',
                           'Null'    => ($aFieldToAlter['FLD_NULL'] == 'on' ? 'YES' : ''),
                           'Default' => '');
          break;
          case 'DATE':
            $aData = array('Type'    => 'DATE', 'Null' => 'YES');
//                           'Null'    => ($aFieldToAlter['FLD_NULL'] == 'on' ? 'YES' : ''),
//                           'Default' => 'NULL'); // '0000-00-00');
          break;
          case 'INT':
            $aData = array('Type'    => 'INT(' . (int)$aFieldToAlter['FLD_SIZE'] . ')',
                           'Null'    => ($aFieldToAlter['FLD_NULL'] == 'on' ? 'YES' : ''),
                           'Default' => '0',
                           'AI'      => ($aFieldToAlter['FLD_AUTO_INCREMENT'] == 'on' ? 1 : 0));
          break;
          case 'FLOAT':
            $aData = array('Type'    => 'FLOAT(' . (int)$aFieldToAlter['FLD_SIZE'] . ')',
                           'Null'    => ($aFieldToAlter['FLD_NULL'] == 'on' ? 'YES' : ''),
                           'Default' => '0');
          break;
        }
        $oDataBase->executeQuery($oDataBase->generateChangeColumnSQL($sTableName, strtoupper($aFieldToAlter['FLD_NAME']), $aData, strtoupper($aFieldToAlter['FLD_NAME_OLD'])));
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  function createPropelClasses($sTableName, $sClassName, $aFields, $sAddTabUid) {
    try {
      /*$aUID = array('FLD_NAME'           => 'PM_UNIQUE_ID',
                    'FLD_TYPE'           => 'INT',
                    'FLD_SIZE'           => '11',
                    'FLD_KEY'            => 'on',
                    'FLD_NULL'           => '',
                    'FLD_AUTO_INCREMENT' => 'on');
      array_unshift($aFields, $aUID);*/
      $aTypes = array('VARCHAR' => 'string',
                      'TEXT'    => 'string',
                      'DATE'    => 'int',
                      'INT'     => 'int',
                      'FLOAT'   => 'double');
      $aCreoleTypes = array('VARCHAR' => 'VARCHAR',
                            'TEXT'    => 'LONGVARCHAR',
                            'DATE'    => 'TIMESTAMP',
                            'INT'     => 'INTEGER',
                            'FLOAT'   => 'DOUBLE');
      if ($sClassName == '') {
        $sClassName = $this->getPHPName($sTableName);
      }

      $sPath = PATH_DB . SYS_SYS . PATH_SEP . 'classes' . PATH_SEP;
      if (!file_exists($sPath)) {
        G::mk_dir($sPath);
        G::mk_dir($sPath . 'map');
        G::mk_dir($sPath . 'om');
      }
      $aData = array();
      $aData['pathClasses']    = substr(PATH_DB, 0, -1);
      $aData['tableName']      = $sTableName;
      $aData['className']      = $sClassName;
      $aData['GUID']           = $sAddTabUid;
      $aData['firstColumn']    = strtoupper($aFields[1]['FLD_NAME']);
      $aData['totalColumns']   = count($aFields);
      $aData['useIdGenerator'] = 'false';
      $oTP1  = new TemplatePower(PATH_TPL . 'additionalTables' . PATH_SEP . 'Table.tpl');
      $oTP1->prepare();
      $oTP1->assignGlobal($aData);
      file_put_contents($sPath . $sClassName . '.php', $oTP1->getOutputContent());
      $oTP2  = new TemplatePower(PATH_TPL . 'additionalTables' . PATH_SEP . 'TablePeer.tpl');
      $oTP2->prepare();
      $oTP2->assignGlobal($aData);
      file_put_contents($sPath . $sClassName . 'Peer.php', $oTP2->getOutputContent());
      $aColumns = array();
      $aPKs     = array();
      $aNotPKs  = array();
      $i        = 0;
      foreach($aFields as $iKey => $aField) {
      	$aField['FLD_NAME'] = strtoupper($aField['FLD_NAME']); 
      	if ($aField['FLD_TYPE']=='DATE') $aField['FLD_NULL'] = '';
        $aColumn    = array('name'        => $aField['FLD_NAME'],
                            'phpName'     => $this->getPHPName($aField['FLD_NAME']),
                            'type'        => $aTypes[$aField['FLD_TYPE']],
                            'creoleType'  => $aCreoleTypes[$aField['FLD_TYPE']],
                            'notNull'     => ($aField['FLD_NULL'] == 'on' ? 'true' : 'false'),
                            'size'        => (($aField['FLD_TYPE'] == 'VARCHAR') || ($aField['FLD_TYPE'] == 'INT') || ($aField['FLD_TYPE'] == 'FLOAT') ? $aField['FLD_SIZE'] : 'null'),
                            'var'         => strtolower($aField['FLD_NAME']),
                            'attribute'   => (($aField['FLD_TYPE'] == 'VARCHAR') || ($aField['FLD_TYPE'] == 'TEXT') || ($aField['FLD_TYPE'] == 'DATE') ? '$' . strtolower($aField['FLD_NAME']) . " = ''" : '$' . strtolower($aField['FLD_NAME']) . ' = 0'),
                            'index'       => $i,
                            );
        if ($aField['FLD_TYPE'] == 'DATE') {
          $aColumn['getFunction'] = '/**
   * Get the [optionally formatted] [' . $aColumn['var'] . '] column value.
   *
   * @param      string $format The date/time format string (either date()-style or strftime()-style).
   *              If format is NULL, then the integer unix timestamp will be returned.
   * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
   * @throws     PropelException - if unable to convert the date/time to timestamp.
   */
  public function get' . $aColumn['phpName'] . '($format = "Y-m-d")
  {

    if ($this->' . $aColumn['var'] . ' === null || $this->' . $aColumn['var'] . ' === "") {
      return null;
    } elseif (!is_int($this->' . $aColumn['var'] . ')) {
      // a non-timestamp value was set externally, so we convert it
      if (($this->' . $aColumn['var'] . ' == "0000-00-00 00:00:00") || ($this->' . $aColumn['var'] . ' == "0000-00-00") || !$this->' . $aColumn['var'] . ') {
        $ts = "0";
      }
      else {
        $ts = strtotime($this->' . $aColumn['var'] . ');
      }
      if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
        throw new PropelException("Unable to parse value of [' . $aColumn['var'] . '] as date/time value: " . var_export($this->' . $aColumn['var'] . ', true));
      }
    } else {
      $ts = $this->' . $aColumn['var'] . ';
    }
    if ($format === null) {
      return $ts;
    } elseif (strpos($format, "%") !== false) {
      return strftime($format, $ts);
    } else {
      return date($format, $ts);
    }
  }';
        }
        else {
          $aColumn['getFunction'] = '/**
   * Get the [' . $aColumn['var'] . '] column value.
   *
   * @return     string
   */
  public function get' . $aColumn['phpName'] . '()
  {

    return $this->' . $aColumn['var'] . ';
  }';
        }
        switch ($aField['FLD_TYPE']) {
          case 'VARCHAR':
          case 'TEXT':
            $aColumn['setFunction'] = '// Since the native PHP type for this column is string,
    // we will cast the input to a string (if it is not).
    if ($v !== null && !is_string($v)) {
      $v = (string) $v;
    }

    if ($this->' . $aColumn['var'] . ' !== $v) {
      $this->' . $aColumn['var'] . ' = $v;
      $this->modifiedColumns[] = ' . $aData['className'] . 'Peer::' . $aColumn['name'] . ';
    }';
          break;
          case 'DATE':
            $aColumn['setFunction'] = 'if ($v !== null && !is_int($v)) {
      // if($v == \'\')
      //   $ts = null;
      // else
        $ts = strtotime($v);
      if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
        //throw new PropelException("Unable to parse date/time value for [' . $aColumn['var'] . '] from input: " . var_export($v, true));
      }
    } else {
      $ts = $v;
    }
    if ($this->' . $aColumn['var'] . ' !== $ts) {
      $this->' . $aColumn['var'] . ' = $ts;
      $this->modifiedColumns[] = ' . $aData['className'] . 'Peer::' . $aColumn['name'] . ';
    }';
          break;
          case 'INT':
            $aColumn['setFunction'] = '// Since the native PHP type for this column is integer,
    // we will cast the input value to an int (if it is not).
    if ($v !== null && !is_int($v) && is_numeric($v)) {
      $v = (int) $v;
    }

    if ($this->' . $aColumn['var'] . ' !== $v || $v === 1) {
      $this->' . $aColumn['var'] . ' = $v;
      $this->modifiedColumns[] = ' . $aData['className'] . 'Peer::' . $aColumn['name'] . ';
    }';
          break;
          case 'FLOAT':
            $aColumn['setFunction'] = 'if ($this->' . $aColumn['var'] . ' !== $v || $v === 0) {
      $this->' . $aColumn['var'] . ' = $v;
      $this->modifiedColumns[] = ' . $aData['className'] . 'Peer::' . $aColumn['name'] . ';
    }';
          break;
        }
        $aColumns[] = $aColumn;
        if ($aField['FLD_KEY'] == 'on') {
          $aPKs[] = $aColumn;
        }
        else {
          $aNotPKs[] = $aColumn;
        }
        if ($aField['FLD_AUTO_INCREMENT'] == 'on') {
          $aData['useIdGenerator'] = 'true';
        }
        $i++;
      }
      $oTP3  = new TemplatePower(PATH_TPL . 'additionalTables' . PATH_SEP . 'map' . PATH_SEP . 'TableMapBuilder.tpl');
      $oTP3->prepare();
      $oTP3->assignGlobal($aData);
      foreach ($aPKs as $iIndex => $aColumn) {
        $oTP3->newBlock('primaryKeys');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP3->assign($sKey, $aColumn[$sKey]);
        }
      }
      $oTP3->gotoBlock('_ROOT');
      foreach ($aNotPKs as $iIndex => $aColumn) {
        $oTP3->newBlock('columnsWhitoutKeys');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP3->assign($sKey, $aColumn[$sKey]);
        }
      }
      file_put_contents($sPath . PATH_SEP . 'map' . PATH_SEP . $sClassName . 'MapBuilder.php', $oTP3->getOutputContent());
      $oTP4  = new TemplatePower(PATH_TPL . 'additionalTables' . PATH_SEP . 'om' . PATH_SEP . 'BaseTable.tpl');
      $oTP4->prepare();
      switch (count($aPKs)) {
        case 0:
          $aData['getPrimaryKeyFunction'] = 'return null;';
          $aData['setPrimaryKeyFunction'] = '';
        break;
        case 1:
          $aData['getPrimaryKeyFunction'] = 'return $this->get' . $aPKs[0]['phpName'] . '();';
          $aData['setPrimaryKeyFunction'] = '$this->set' . $aPKs[0]['phpName'] . '($key);';
        break;
        default:
          $aData['getPrimaryKeyFunction'] = '$pks = array();' . "\n";
          $aData['setPrimaryKeyFunction'] = '';
          foreach ($aPKs as $iIndex => $aColumn) {
            $aData['getPrimaryKeyFunction'] .= '$pks[' . $iIndex . '] = $this->get' . $aColumn['phpName'] . '();' . "\n";
            $aData['setPrimaryKeyFunction'] .= '$this->set' . $aColumn['phpName'] . '($keys[' . $iIndex . ']);' . "\n";
          }
          $aData['getPrimaryKeyFunction'] .= 'return $pks;' . "\n";
        break;
      }
      $oTP4->assignGlobal($aData);
      foreach ($aColumns as $iIndex => $aColumn) {
        $oTP4->newBlock('allColumns1');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP4->assign($sKey, $aColumn[$sKey]);
        }
        $oTP4->newBlock('allColumns2');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP4->assign($sKey, $aColumn[$sKey]);
        }
        $oTP4->newBlock('allColumns3');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP4->assign($sKey, $aColumn[$sKey]);
        }
        $oTP4->newBlock('allColumns4');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP4->assign($sKey, $aColumn[$sKey]);
        }
        $oTP4->newBlock('allColumns5');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP4->assign($sKey, $aColumn[$sKey]);
        }
        $oTP4->newBlock('allColumns6');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP4->assign($sKey, $aColumn[$sKey]);
        }
        $oTP4->newBlock('allColumns7');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP4->assign($sKey, $aColumn[$sKey]);
        }
        $oTP4->newBlock('allColumns8');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP4->assign($sKey, $aColumn[$sKey]);
        }
        $oTP4->newBlock('allColumns9');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP4->assign($sKey, $aColumn[$sKey]);
        }
      }
      $oTP4->gotoBlock('_ROOT');
      foreach ($aPKs as $iIndex => $aColumn) {
        $oTP4->newBlock('primaryKeys1');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP4->assign($sKey, $aColumn[$sKey]);
        }
      }
      $oTP4->gotoBlock('_ROOT');
      foreach ($aPKs as $iIndex => $aColumn) {
        $oTP4->newBlock('primaryKeys2');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP4->assign($sKey, $aColumn[$sKey]);
        }
      }
      $oTP4->gotoBlock('_ROOT');
      foreach ($aNotPKs as $iIndex => $aColumn) {
        $oTP4->newBlock('columnsWhitoutKeys');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP4->assign($sKey, $aColumn[$sKey]);
        }
      }
      file_put_contents($sPath . PATH_SEP . 'om' . PATH_SEP . 'Base' . $sClassName . '.php', $oTP4->getOutputContent());
      $oTP5  = new TemplatePower(PATH_TPL . 'additionalTables' . PATH_SEP . 'om' . PATH_SEP . 'BaseTablePeer.tpl');
      $oTP5->prepare();
      $sKeys = '';
      foreach ($aPKs as $iIndex => $aColumn) {
        $sKeys .= '$' . $aColumn['var'] . ', ';
      }
      $sKeys = substr($sKeys, 0, -2);
      //$sKeys = '$pm_unique_id';
      if ($sKeys != '') {
        $aData['sKeys'] = $sKeys;
      }
      else {
        $aData['sKeys'] = '$DUMMY';
      }
      $oTP5->assignGlobal($aData);
      foreach ($aColumns as $iIndex => $aColumn) {
        $oTP5->newBlock('allColumns1');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP5->assign($sKey, $aColumn[$sKey]);
        }
        $oTP5->newBlock('allColumns2');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP5->assign($sKey, $aColumn[$sKey]);
        }
        $oTP5->newBlock('allColumns3');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP5->assign($sKey, $aColumn[$sKey]);
        }
        $oTP5->newBlock('allColumns4');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP5->assign($sKey, $aColumn[$sKey]);
        }
        $oTP5->newBlock('allColumns5');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP5->assign($sKey, $aColumn[$sKey]);
        }
        $oTP5->newBlock('allColumns6');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP5->assign($sKey, $aColumn[$sKey]);
        }
        $oTP5->newBlock('allColumns7');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP5->assign($sKey, $aColumn[$sKey]);
        }
        $oTP5->newBlock('allColumns8');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP5->assign($sKey, $aColumn[$sKey]);
        }
        $oTP5->newBlock('allColumns9');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP5->assign($sKey, $aColumn[$sKey]);
        }
        $oTP5->newBlock('allColumns10');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP5->assign($sKey, $aColumn[$sKey]);
        }
      }
      $oTP5->gotoBlock('_ROOT');
      foreach ($aPKs as $iIndex => $aColumn) {
        $oTP5->newBlock('primaryKeys1');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP5->assign($sKey, $aColumn[$sKey]);
        }
      }
      foreach ($aPKs as $iIndex => $aColumn) {
        $oTP5->newBlock('primaryKeys2');
        $aKeys = array_keys($aColumn);
        foreach ($aKeys as $sKey) {
          $oTP5->assign($sKey, $aColumn[$sKey]);
        }
      }
      file_put_contents($sPath . PATH_SEP . 'om' . PATH_SEP . 'Base' . $sClassName . 'Peer.php', $oTP5->getOutputContent());
    }
    catch (Exception $oError) {
      throw($oError);
    }
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

  function deleteAll($sUID) {
    try {
      $aData = $this->load($sUID);
      $this->remove($sUID);
      require_once 'classes/model/Fields.php';
      $oCriteria = new Criteria('workflow');
      $oCriteria->add(FieldsPeer::ADD_TAB_UID, $sUID);
      FieldsPeer::doDelete($oCriteria);
      G::LoadSystem('database_' . strtolower(DB_ADAPTER));
      $oDataBase = new database(DB_ADAPTER, DB_HOST, DB_USER, DB_PASS, DB_NAME);
      $oDataBase->iFetchType = MYSQL_NUM;
      $oDataBase->executeQuery($oDataBase->generateDropTableSQL($aData['ADD_TAB_NAME']));
      $sClassName = $this->getPHPName($aData['ADD_TAB_CLASS_NAME'] != '' ? $aData['ADD_TAB_CLASS_NAME'] : $aData['ADD_TAB_NAME']);
      $sPath = PATH_DB . SYS_SYS . PATH_SEP . 'classes' . PATH_SEP;
      @unlink($sPath . $sClassName . '.php');
      @unlink($sPath . $sClassName . 'Peer.php');
      @unlink($sPath . PATH_SEP . 'map' . PATH_SEP . $sClassName . 'MapBuilder.php');
      @unlink($sPath . PATH_SEP . 'om' . PATH_SEP . 'Base' . $sClassName . '.php');
      @unlink($sPath . PATH_SEP . 'om' . PATH_SEP . 'Base' . $sClassName . 'Peer.php');
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  function createXmlList($sUID) {
    try {
      $aData = $this->load($sUID, true);
      $sPath = PATH_DYNAFORM . 'xmlLists' . PATH_SEP;
      if (!file_exists($sPath)) {
        G::mk_dir($sPath);
      }
      file_put_contents($sPath . 'additionalTablesDataOptions.xml', '<?xml version="1.0" encoding="UTF-8"?>
<dynaForm type="xmlmenu">

<ADD_TAB_UID type="private" showInTable="0" />

<MNU_ADD type="link" link="additionalTablesDataNew?sUID=@#ADD_TAB_UID" colAlign="left" colWidth="100">
  <en>' . G::LoadTranslation("ID_NEW") . '</en>
</MNU_ADD>

<MNU_IMPORT_DATA type="link" link="additionalTablesDataImportForm?sUID=@#ADD_TAB_UID" colAlign="left" colWidth="100">
  <en>' . G::LoadTranslation("ID_IMPORT") . '</en>
</MNU_IMPORT_DATA>

<PAGED_TABLE_ID type="private" />

<JS type="javascript" replaceTags="1">
<![CDATA[
var additionalTablesDataDelete = function(sUID, sKeys) {
  new leimnud.module.app.confirm().make({
    label:"' . G::LoadTranslation('ID_CONFIRM_DELETE_ELEMENT') . '",
    action:function() {
      //ajax_function(@G::encryptlink("additionalTablesDataDelete"), "", "sUID=" + sUID + "&sPMUID=" + sPMUID, "POST");
      //@#PAGED_TABLE_ID.refresh();
      window.location = "additionalTablesDataDelete?" + "sUID=" + sUID + "&" + sKeys;
    }.extend(this)
  });
};
]]>
</JS>
</dynaForm>');
      $sKeys = '';
      $sXml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
      $sXml .= '<dynaForm width="100%" menu="xmlLists/additionalTablesDataOptions">' . "\n".
            '<xtitle type="title">
             <en><![CDATA[<center><b>' . G::LoadTranslation("ID_TABLE") . ': '.$aData['ADD_TAB_NAME'].'</b></center>]]></en>
             </xtitle>';
      
      //$sXml .= '<PM_UNIQUE_ID type="private" showInTable="0" />' . "\n";
      foreach ($aData['FIELDS'] as $aField) {
        
        $fZise = $aField['FLD_SIZE'] > (1024/sizeof($aData['FIELDS'])) ? $aField['FLD_SIZE']: 1024/sizeof($aData['FIELDS']);
        
        
        $sXml .= '<' . $aField['FLD_NAME'] . ' type="text" colWidth="'.$fZise.'px" titleAlign="left" align="left">' . "\n";
        $sXml .= '<' . SYS_LANG . '>' . ($aField['FLD_DESCRIPTION'] != '' ? $aField['FLD_DESCRIPTION'] : $aField['FLD_NAME']) . '</' . SYS_LANG . '>' . "\n";
        $sXml .= '</' . $aField['FLD_NAME'] . '>' . "\n";
        if ($aField['FLD_KEY'] == 1) {
          $sKeys .= $aField['FLD_NAME'] . '=@#' . $aField['FLD_NAME'] . '&amp;';
        }
      }
      $sKeys = substr($sKeys, 0, -5);
      $sXml .= '<EDIT type="link" colWidth="40" value="@G::LoadTranslation(ID_EDIT)" link="additionalTablesDataEdit?sUID=@#ADD_TAB_UID&amp;' . $sKeys . '" onclick=""/>' . "\n";
      $sXml .= '<DELETE type="link" colWidth="40" value="@G::LoadTranslation(ID_DELETE)" link="#" onclick="additionalTablesDataDelete(@QADD_TAB_UID, \'' . $sKeys . '\');return false;"/>' . "\n";
      $sXml .= '</dynaForm>';
      file_put_contents($sPath . $sUID . '.xml', $sXml);
    }
    catch (Exception $oError) {
      throw($oError);
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
      $oCriteria = new Criteria('workflow');
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


  function createXmlEdit($sUID, $bEnableKeys) {
    try {
      $aData = $this->load($sUID, true);
      $sPath = PATH_DYNAFORM . 'xmlLists' . PATH_SEP;
      $sXml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
      $sXml .= '<dynaForm name="xmlLists/' . $sUID . 'Edit" type="xmlform" width="700" mode="">';
      //$sXml .= '<PM_UNIQUE_ID type="hidden" />';
      $sXml .= '<TITLE type="title" label="' . $aData['ADD_TAB_NAME'] . '" />';
      foreach ($aData['FIELDS'] as $aField) {
        switch ($aField['FLD_TYPE']) {
          case 'VARCHAR':
            if( intVal($aField['FLD_SIZE'])  <= 100){
              $vCharType = 'text';
              $vCharAtt = 'size="' . $aField['FLD_SIZE'] . '" maxlength="' . $aField['FLD_SIZE'] . '" validate="Any"'; 
            } else {
              $vCharType = 'textarea';
              $vCharAtt = 'rows="3" cols="90"';
            }
            $sXml .= '<' . $aField['FLD_NAME'] . ' type="'.$vCharType.'"  '. $vCharAtt .' required="' . (($aField['FLD_KEY'] == 1) && ($aField['FLD_AUTO_INCREMENT'] == 0) || ($aField['FLD_NULL'] == 0) ? '1' : '0') . '" readonly="0" mode="' . ($bEnableKeys ? 'edit' : ($aField['FLD_KEY'] == 1 ? 'view' : 'edit')) . '"><' . SYS_LANG . '>' . ($aField['FLD_DESCRIPTION'] != '' ? $aField['FLD_DESCRIPTION'] : $aField['FLD_NAME']) . '</' . SYS_LANG . '></' . $aField['FLD_NAME'] . '>';
          break;
          case 'TEXT':
            $sXml .= '<' . $aField['FLD_NAME'] . ' type="textarea" required="' . (($aField['FLD_KEY'] == 1) && ($aField['FLD_AUTO_INCREMENT'] == 0) || ($aField['FLD_NULL'] == 0) ? '1' : '0') . '" readonly="0" rows="8" cols="90" mode="' . ($bEnableKeys ? 'edit' : ($aField['FLD_KEY'] == 1 ? 'view' : 'edit')) . '"><' . SYS_LANG . '>' . ($aField['FLD_DESCRIPTION'] != '' ? $aField['FLD_DESCRIPTION'] : $aField['FLD_NAME']) . '</' . SYS_LANG . '></' . $aField['FLD_NAME'] . '>';
          break;
          case 'DATE':
            $sXml .= '<' . $aField['FLD_NAME'] . ' type="date" beforedate="-15y" afterdate="15y" mask="Y-m-d" required="' . ((($aField['FLD_KEY'] == 1) && ($aField['FLD_AUTO_INCREMENT'] == 0) || ($aField['FLD_NULL'] == 0) ) ? '1' : '0') . '" readonly="0" size="15" mode="' . ($bEnableKeys ? 'edit' : ($aField['FLD_KEY'] == 1 ? 'view' : 'edit')) . '"><' . SYS_LANG . '>' . ($aField['FLD_DESCRIPTION'] != '' ? $aField['FLD_DESCRIPTION'] : $aField['FLD_NAME']) . '</' . SYS_LANG . '></' . $aField['FLD_NAME'] . '>';
          break;
          case 'INT':
            $sXml .= '<' . $aField['FLD_NAME'] . ' type="text" maxlength="' . $aField['FLD_SIZE'] . '" validate="Int" required="' . (($aField['FLD_KEY'] == 1) && ($aField['FLD_AUTO_INCREMENT'] == 0) || ($aField['FLD_NULL'] == 0) ? '1' : '0') . '" readonly="0" size="' .($aField['FLD_SIZE']<=100?$aField['FLD_SIZE']:100). '" mode="' . ($bEnableKeys ? 'edit' : ($aField['FLD_KEY'] == 1 ? 'view' : 'edit')) . '"><' . SYS_LANG . '>' . ($aField['FLD_DESCRIPTION'] != '' ? $aField['FLD_DESCRIPTION'] : $aField['FLD_NAME']) . '</' . SYS_LANG . '></' . $aField['FLD_NAME'] . '>';
          break;
          case 'FLOAT':
            $sXml .= '<' . $aField['FLD_NAME'] . ' type="text" maxlength="' . $aField['FLD_SIZE'] . '" validate="Real" required="' . (($aField['FLD_KEY'] == 1) && ($aField['FLD_AUTO_INCREMENT'] == 0) || ($aField['FLD_NULL'] == 0) ? '1' : '0') . '" readonly="0" size="' . ($aField['FLD_SIZE']<=100?$aField['FLD_SIZE']:100) . '" mode="' . ($bEnableKeys ? 'edit' : ($aField['FLD_KEY'] == 1 ? 'view' : 'edit')) . '"><' . SYS_LANG . '>' . ($aField['FLD_DESCRIPTION'] != '' ? $aField['FLD_DESCRIPTION'] : $aField['FLD_NAME']) . '</' . SYS_LANG . '></' . $aField['FLD_NAME'] . '>';
          break;
        }
      }
      $sXml .= '<btnSave type="submit"><' . SYS_LANG . '>' . G::LoadTranslation('ID_SAVE_CHANGES') . '</' . SYS_LANG . '></btnSave>';
      $sXml .= '<btnBack type="button" onclick="history.back()"><' . SYS_LANG . '>' . G::LoadTranslation('ID_CANCEL') . '</' . SYS_LANG . '></btnBack>';
      $sXml .= '</dynaForm>';
// g::pr($aField);
// g::pr($sXml); die;
      file_put_contents($sPath . $sUID . 'Edit.xml', $sXml);
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  function saveDataInTable($sUID, $aFields) {
    try {
      $aData = $this->load($sUID, true);
      $sPath = PATH_DB . SYS_SYS . PATH_SEP . 'classes' . PATH_SEP;
      $sClassName = ($aData['ADD_TAB_CLASS_NAME'] != '' ? $aData['ADD_TAB_CLASS_NAME'] : $this->getPHPName($aData['ADD_TAB_NAME']));
      $oConnection = Propel::getConnection(FieldsPeer::DATABASE_NAME);
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
      eval('$oClass = ' . $sClassName . 'Peer::retrieveByPK(' . $sKeys . ');');
      if (is_null($oClass)) {
        $oClass = new $sClassName;
        foreach ($aFields as $sKey => $sValue) {
          eval('$oClass->set' . $this->getPHPName($sKey) . '($aFields["' . $sKey . '"]);');
        }
        if ($oClass->validate()) {
          $oConnection->begin();
          $iResult = $oClass->save();
          $oConnection->commit();
        }
        return true;
      }
      else {
        return false;
      }
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
} // AdditionalTables
