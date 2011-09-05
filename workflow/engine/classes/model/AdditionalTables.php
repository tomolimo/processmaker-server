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

  private $aDef = array(
    'mysql' => array(
      'TEXT' => 'TEXT',
      'CHAR'   => 'CHAR',
      'VARCHAR'   => 'VARCHAR',
      'INT'   => 'INT',
      'FLOAT' => 'FLOAT',
      'DATE'   => 'DATE'
    ),
    'pgsql' => array(
      'TEXT' => 'TEXT',
      'CHAR'   => 'CHAR',
      'VARCHAR'   => 'VARCHAR',
      'INT'   => 'INTEGER',
      'FLOAT' => 'REAL',
      'DATE'   => 'DATE'
    ),
    'mssql' => array(
      'TEXT' => 'TEXT',
      'CHAR'   => 'NCHAR',
      'VARCHAR'   => 'NVARCHAR',
      'INT'   => 'INTEGER',
      'FLOAT' => 'FLOAT',
      'DATE'   => 'CHAR (19)'
    )
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
        require_once 'classes/model/ShadowTable.php';
        if ($aAdditionalTables['ADD_TAB_SDW_AUTO_DELETE'] == 1) {
          $oCriteria = new Criteria('workflow');
          $oCriteria->add(ShadowTablePeer::ADD_TAB_UID, $sUID);
          ShadowTablePeer::doDelete($oCriteria);
        }
        else {
          $oShadowTable = new ShadowTable();
          $oShadowTable->create(array(
            'ADD_TAB_UID' => $sUID,
            'SHD_ACTION'  => 'DROP',
            'SHD_DETAILS' => '',
            'USR_UID'     => (isset($_SESSION['USER_LOGGED']) ? $_SESSION['USER_LOGGED'] : ''),
            'APP_UID'     => '',
            'SHD_DATE'    => date('Y-m-d H:i:s'))
          );
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

  function createTable($sTableName, $sConnection = '', $aFields = array()) {
    
    if ($sConnection == '' || $sConnection == 'wf') {
      $sConnection = 'workflow';
    }

    try {
      switch (DB_ADAPTER) {
        case 'mysql':

          // trying to get a connection, if it doesn't exist Propel::getConnection() throws an exception
          $con = Propel::getConnection($sConnection);
          $stmt = $con->createStatement();

          $sQuery = 'CREATE TABLE IF NOT EXISTS `' . $sTableName . '` (';
          $aPKs   = array();
          foreach ($aFields as $aField) {
            $aField['sFieldName'] = strtoupper($aField['sFieldName']);
            switch ($aField['sType']) {
              case 'VARCHAR':
                $sQuery .= '`' . $aField['sFieldName'] . '` ' . $aField['sType'] . '(' . $aField['iSize'] . ')' . " " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . " DEFAULT '',";
              break;
              case 'TEXT':
                $sQuery .= '`' . $aField['sFieldName'] . '` ' . $aField['sType'] . " " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . " ,"; // " DEFAULT '',";
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

          $rs = $stmt->executeQuery('DROP TABLE IF EXISTS `' . $sTableName . '`');
          $rs = $stmt->executeQuery($sQuery);
        break;

        case 'mysql2':
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
                $sQuery .= '`' . $aField['sFieldName'] . '` ' . $aField['sType'] . " " . ($aField['bNull'] ? 'NULL' : 'NOT NULL') . " ,"; // " DEFAULT '',";
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
            throw new Exception('Cannot create the table "' . $sTableName . '"! ' . mysql_error() . ' SQL: ' . $sQuery);
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
    $debug=false;

    if ($sConnection == '' || $sConnection == 'wf') {
      $sConnection = 'workflow';
    }
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
        
        if ($aNewField['FLD_KEY'] == 1 || $aNewField['FLD_KEY'] === 'on' || $aNewField['FLD_AUTO_INCREMENT'] === 'on') {
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

      if ($debug) {
        echo 'new';
        print_r($aNewFields);
        echo 'old';
        print_r($aOldFields);
        echo 'to add';
        print_r($aFieldsToAdd);
        echo 'keys';
        print_r($aKeys);
        echo 'to delete';
        print_r($aFieldsToDelete);
      }
      
      foreach ($aNewFields as $aNewField) {
        if (isset($aOldFields[$aNewField['FLD_UID']])) {
          $aOldField = $aOldFields[$aNewField['FLD_UID']];
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

      if ($debug) {
        echo 'to alter'; print_r($aFieldsToAlter);
      }

      G::LoadSystem('database_' . strtolower(DB_ADAPTER));
      $oDataBase = new database(DB_ADAPTER, DB_HOST, DB_USER, DB_PASS, DB_NAME);
      $oDataBase->iFetchType = MYSQL_NUM;
      
      //$oDataBase->executeQuery($oDataBase->generateDropPrimaryKeysSQL($sTableName));
      $con = Propel::getConnection($sConnection);
      $stmt = $con->createStatement();
     
      $sQuery = $oDataBase->generateDropPrimaryKeysSQL($sTableName);
      if ($debug) {
        echo 'sql drop pk';
        var_dump($sQuery);
      }
      try {
        $rs = $stmt->executeQuery($sQuery);
      } catch(PDOException $oException ) {
        throw $oException;
      }

      foreach ($aFieldsToDelete as $aFieldToDelete) {
        //$oDataBase->executeQuery($oDataBase->generateDropColumnSQL($sTableName, strtoupper($aFieldToDelete['FLD_NAME'])));
        $sQuery = $oDataBase->generateDropColumnSQL($sTableName, strtoupper($aFieldToDelete['FLD_NAME']));
        if ($debug) {
          echo 'sql drop field';
          var_dump($sQuery);
        }
        $rs = $stmt->executeQuery($sQuery);
      }

      foreach ($aFieldsToAdd as $aFieldToAdd) {
        switch ($aFieldToAdd['FLD_TYPE']) {
          case 'VARCHAR':
            $aData = array(
              'Type'    => 'VARCHAR(' . $aFieldToAdd['FLD_SIZE'] . ')',
              'Null'    => ($aFieldToAdd['FLD_NULL'] == 1 || $aFieldToAdd['FLD_NULL'] === 'on' ? 'YES' : ''),
              'Default' => ''
            );
          break;
          case 'TEXT':
            $aData = array(
              'Type'    => 'TEXT',
              'Null'    => ($aFieldToAdd['FLD_NULL'] == 1 || $aFieldToAdd['FLD_NULL'] === 'on' ? 'YES' : ''),
              'Default' => ''
            );
          break;
          case 'DATE':
            $aData = array(
              'Type'    => 'DATE', 'Null' => 'YES'
            );
              // 'Null'    => ($aFieldToAdd['FLD_NULL'] == 'on' ? 'YES' : ''),
              // 'Default' => 'NULL'); // '0000-00-00');
          break;
          case 'INT':
            $aData = array(
              'Type'    => 'INT(' . (int)$aFieldToAdd['FLD_SIZE'] . ')',
              'Null'    => ($aFieldToAdd['FLD_NULL'] == 1 || $aFieldToAdd['FLD_NULL'] === 'on' ? 'YES' : ''),
              'Default' => '0',
              'AI'      => ($aFieldToAdd['FLD_AUTO_INCREMENT'] == 1 || $aFieldToAdd['FLD_AUTO_INCREMENT'] === 'on' ? 1 : 0)
            );
          break;
          case 'FLOAT':
            $aData = array(
              'Type'    => 'FLOAT(' . (int)$aFieldToAdd['FLD_SIZE'] . ')',
              'Null'    => ($aFieldToAdd['FLD_NULL'] == 1 || $aFieldToAdd['FLD_NULL'] == 'on' ? 'YES' : ''),
              'Default' => '0'
            );
          break;
        }
               
        //$oDataBase->executeQuery($oDataBase->generateAddColumnSQL($sTableName, strtoupper($aFieldToAdd['FLD_NAME']), $aData));
        $sQuery = $oDataBase->generateAddColumnSQL($sTableName, strtoupper($aFieldToAdd['FLD_NAME']), $aData);
        if ($debug) {
          echo 'sql add';
          var_dump($sQuery);
        }
        $rs = $stmt->executeQuery($sQuery);
      }
      
      //$oDataBase->executeQuery($oDataBase->generateAddPrimaryKeysSQL($sTableName, $aKeys));
      $sQuery = $oDataBase->generateAddPrimaryKeysSQL($sTableName, $aKeys);
      if ($debug) {
        echo 'sql gen pk';
        var_dump($sQuery);
      }
      $rs = $stmt->executeQuery($sQuery);
      
      foreach ($aFieldsToAlter as $aFieldToAlter) {
        switch ($aFieldToAlter['FLD_TYPE']) {
          case 'VARCHAR':
            $aData = array(
              'Type'    => 'VARCHAR(' . $aFieldToAlter['FLD_SIZE'] . ')',
              'Null'    => ($aFieldToAlter['FLD_NULL'] == 'on' ? 'YES' : ''),
              'Default' => ''
            );
          break;
          case 'TEXT':
            $aData = array(
              'Type'    => 'TEXT',
              'Null'    => ($aFieldToAlter['FLD_NULL'] == 'on' ? 'YES' : ''),
              'Default' => ''
            );
          break;
          case 'DATE':
            $aData = array(
              'Type'    => 'DATE', 'Null' => 'YES'
            );
            //'Null'    => ($aFieldToAlter['FLD_NULL'] == 'on' ? 'YES' : ''),
            //'Default' => 'NULL'); // '0000-00-00');
          break;
          case 'INT':
            $aData = array(
              'Type'    => 'INT(' . (int)$aFieldToAlter['FLD_SIZE'] . ')',
              'Null'    => ($aFieldToAlter['FLD_NULL'] == 'on' ? 'YES' : ''),
              'Default' => '0',
              'AI'      => ($aFieldToAlter['FLD_AUTO_INCREMENT'] == 'on' ? 1 : 0)
            );
          break;
          case 'FLOAT':
            $aData = array(
              'Type'    => 'FLOAT(' . (int)$aFieldToAlter['FLD_SIZE'] . ')',
              'Null'    => ($aFieldToAlter['FLD_NULL'] == 'on' ? 'YES' : ''),
              'Default' => '0'
            );
          break;
        }
        //$oDataBase->executeQuery($oDataBase->generateChangeColumnSQL($sTableName, strtoupper($aFieldToAlter['FLD_NAME']), $aData, strtoupper($aFieldToAlter['FLD_NAME_OLD'])));

        $sQuery = $oDataBase->generateChangeColumnSQL($sTableName, strtoupper($aFieldToAlter['FLD_NAME']), $aData, strtoupper($aFieldToAlter['FLD_NAME_OLD']));
        if ($debug) {
          echo 'sql alter';
          var_dump($sQuery);
        }
        $rs = $stmt->executeQuery($sQuery);
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  function createPropelClasses($sTableName, $sClassName, $aFields, $sAddTabUid, $connection='workflow') {
    try {
      /*$aUID = array('FLD_NAME'           => 'PM_UNIQUE_ID',
                    'FLD_TYPE'           => 'INT',
                    'FLD_SIZE'           => '11',
                    'FLD_KEY'            => 'on',
                    'FLD_NULL'           => '',
                    'FLD_AUTO_INCREMENT' => 'on');
      array_unshift($aFields, $aUID);*/
      $aTypes = array(
        'VARCHAR' => 'string',
        'TEXT'    => 'string',
        'DATE'    => 'int',
        'INT'     => 'int',
        'FLOAT'   => 'double'
      );
      $aCreoleTypes = array(
        'VARCHAR' => 'VARCHAR',
        'TEXT'    => 'LONGVARCHAR',
        'DATE'    => 'TIMESTAMP',
        'INT'     => 'INTEGER',
        'FLOAT'   => 'DOUBLE'
      );
      if ($sClassName == '') {
        $sClassName = $this->getPHPName($sTableName);
      }

      $sPath = PATH_DB . SYS_SYS . PATH_SEP . 'classes' . PATH_SEP;
      if (!file_exists($sPath)) {
         G::mk_dir($sPath);
      }
      if (!file_exists($sPath . 'map')) {
        G::mk_dir($sPath . 'map');
      }
      if (!file_exists($sPath . 'om')) {
         G::mk_dir($sPath . 'om');
      }
      $aData = array();
      $aData['pathClasses']    = substr(PATH_DB, 0, -1);
      $aData['tableName']      = $sTableName;
      $aData['className']      = $sClassName;
      $aData['connection']     = $connection;
      $aData['GUID']           = $sAddTabUid;
      
      $aData['firstColumn']    = isset($aFields[0])? strtoupper($aFields[0]['FLD_NAME']) : strtoupper($aFields[1]['FLD_NAME']);
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
        $aColumn    = array(
          'name'        => $aField['FLD_NAME'],
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
        if ($aField['FLD_KEY'] == 1 || $aField['FLD_KEY'] === 'on') {
          $aPKs[] = $aColumn;
        }
        else {
          $aNotPKs[] = $aColumn;
        }
        if ($aField['FLD_AUTO_INCREMENT'] == 1 || $aField['FLD_AUTO_INCREMENT'] === 'on') {
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
      //deleting pm table
      $aData = $this->load($sUID);
      $this->remove($sUID);

      //deleting fields
      require_once 'classes/model/Fields.php';
      $oCriteria = new Criteria('workflow');
      $oCriteria->add(FieldsPeer::ADD_TAB_UID, $sUID);
      FieldsPeer::doDelete($oCriteria);

      //deleting table
      if ($aData['DBS_UID'] == 'wf' || $aData['DBS_UID'] == 'workflow' || $aData['DBS_UID'] == '' || $aData['DBS_UID'] == '0' || !$aData['DBS_UID']) {
        G::LoadSystem('database_' . strtolower(DB_ADAPTER));
        $oDataBase = new database(DB_ADAPTER, DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $oDataBase->iFetchType = MYSQL_NUM;
        $oDataBase->executeQuery($oDataBase->generateDropTableSQL($aData['ADD_TAB_NAME']));
      } else {
        $con = Propel::getConnection($aData['DBS_UID']);
        if (is_object($con)) {
          $stmt = $con->createStatement();
          $stmt->executeQuery('DROP TABLE '.$aData['ADD_TAB_NAME']);
        }
      }


      //deleting clases
      $sClassName = $this->getPHPName($aData['ADD_TAB_CLASS_NAME'] != '' ? $aData['ADD_TAB_CLASS_NAME'] : $aData['ADD_TAB_NAME']);
      $sPath = PATH_DB . SYS_SYS . PATH_SEP . 'classes' . PATH_SEP;
      
      @unlink($sPath . $sClassName . '.php');
      @unlink($sPath . $sClassName . 'Peer.php');
      @unlink($sPath . 'map' . PATH_SEP . $sClassName . 'MapBuilder.php');
      @unlink($sPath . 'om' . PATH_SEP . 'Base' . $sClassName . '.php');
      @unlink($sPath . 'om' . PATH_SEP . 'Base' . $sClassName . 'Peer.php');
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
   * Populate Report Table
   */
  public function populateReportTable($sTableName, $sConnection = 'rp', $sType = 'NORMAL', $aFields = array(), $sProcessUid = '', $sGrid = '')
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
