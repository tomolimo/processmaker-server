<?php
/**
 * pmTablesProxy
 * @author Erik Amaru Ortiz <erik@colosa.com, aortiz.erik@gmail.com>
 * @inherits HttpProxyController
 * @access public
 */

require_once 'classes/model/AdditionalTables.php';

class pmTablesProxy extends HttpProxyController
{

  /**
   * get pmtables list
   * @param string $httpData->start
   * @param string $httpData->limit
   * @param string $httpData->textFilter
   */
  public function getList($httpData)
  {
    G::LoadClass('configuration');
    G::LoadClass('processMap');
    $configurations = new Configurations();
    $processMap = new processMap();
    
    // setting parameters
    $config     = $configurations->getConfiguration('additionalTablesList', 'pageSize','',$_SESSION['USER_LOGGED']);
    $env        = $configurations->getConfiguration('ENVIRONMENT_SETTINGS', '');
    $limit_size = isset($config->pageSize) ? $config['pageSize'] : 20;
    $start      = isset($httpData->start) ? $httpData->start : 0;
    $limit      = isset($httpData->limit) ? $httpData->limit : $limit_size;
    $filter     = isset($httpData->textFilter) ? $httpData->textFilter : '';
    $pro_uid    = isset($httpData->pro_uid) ? $httpData->pro_uid : null;

    if ($pro_uid !== null) {
      $process = $pro_uid == '' ? array('not_equal'=>$pro_uid) : array('equal'=>$pro_uid);
      $addTables = AdditionalTables::getAll($start, $limit, $filter, $process);
      
      $c = $processMap->getReportTablesCriteria($pro_uid);
      $oDataset = RoutePeer::doSelectRS($c);
      $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
      $reportTablesOldList = array();
      while($oDataset->next()) {
        $reportTablesOldList[] = $oDataset->getRow();
      }
      $addTables['count'] += count($reportTablesOldList);
      
      foreach ($reportTablesOldList as $i => $oldRepTab) {
        $addTables['rows'][] = array(
          'ADD_TAB_UID' => $oldRepTab['REP_TAB_UID'],
          'PRO_UID' => $oldRepTab['PRO_UID'],
          'ADD_TAB_DESCRIPTION' => $oldRepTab['REP_TAB_TITLE'],
          'ADD_TAB_NAME' => $oldRepTab['REP_TAB_NAME'],
          'ADD_TAB_TYPE' => $oldRepTab['REP_TAB_TYPE'],
          'TYPE' => 'CLASSIC' 
        );
      }
    } 
    else {
      $addTables = AdditionalTables::getAll($start, $limit, $filter);
    }

    return $addTables;
  }

  /**
   * get processesList   
   */
  public function getProcessList()
  {
    require_once 'classes/model/Process.php';
    
    $process = new Process();
    return $process->getAll();
  }

  /**
   * get database connection list
   */
  public function getDbConnectionsList()
  {
    G::LoadClass ( 'dbConnections');
    $proUid = $_POST['PRO_UID'];
    $dbConn = new DbConnections();
    $dbConnections = $dbConn->getConnectionsProUid($proUid);
    $defaultConnections = array (
      array('DBS_UID'=>'workflow', 'DBS_NAME'=>'Workflow'),
      array('DBS_UID'=>'rp', 'DBS_NAME'=>'REPORT')
    );

    $dbConnections = array_merge($defaultConnections, $dbConnections);

    return $dbConnections;
  }

  /**
   * get dynaform fields
   * @param string $httpData->PRO_UID
   * @param string $httpData->TYPE
   * @param string $httpData->GRID_UID
   */
  public function getDynafields($httpData)
  {
    G::LoadClass('reportTables');

    $aFields['FIELDS'] = array();
    $aFields['PRO_UID'] = $httpData->PRO_UID;

    if(isset($httpData->TYPE) && $httpData->TYPE == 'GRID') {
      $aProcessGridFields = Array();
      if (isset($httpData->GRID_UID)) {
        global $G_FORM;
        list($gridName, $gridId) = explode('-', $httpData->GRID_UID);

        $gridFields = $this->_getGridDynafields($httpData->PRO_UID, $gridId);

        foreach ($gridFields as $gfield) {
          $aProcessGridFields[] = array(
            'FIELD_UID' => $gfield['name'] . '-' . $gfield['type'],
            'FIELD_NAME' => $gfield['name']
          );
        }
      } else {
        $gridFields = $this->_getGridFields($aFields['PRO_UID']);

        foreach ($gridFields as $gfield) {
          $aProcessGridFields[]  = array(
            'FIELD_UID'  => $gfield['name'] . '-' . $gfield['xmlform'],
            'FIELD_NAME' => $gfield['name']
          );
        }
      }
      $resultList['processFields'] = $aProcessGridFields;

    } else {
      $aProcessFields = Array();
      $dynFields = $this->_getDynafields($aFields['PRO_UID']);

      foreach ($dynFields as $dfield) {
        $aProcessFields[]  = array(
          'FIELD_UID'  => $dfield['name'] . '-' . $dfield['type'],
          'FIELD_NAME' => $dfield['name']
        );
      }
      $resultList['processFields'] = $aProcessFields;
    }

    return $resultList;
  }

  /**
   * save pm table
   */
  public function save()
  {
    require_once 'classes/model/AdditionalTables.php';
    require_once 'classes/model/Fields.php';
    try {
      $data = $_POST;
      $data['PRO_UID'] = trim($data['PRO_UID']);
      $data['columns'] = G::json_decode($_POST['columns']); //decofing data columns
      $isReportTable = $data['PRO_UID'] != '' ? true : false;

      // Reserved Words
      $aReservedWords = array(
        'ALTER', 'CLOSE', 'COMMIT', 'CREATE', 'DECLARE',
        'DELETE', 'DROP', 'FETCH', 'FUNCTION', 'GRANT',
        'INDEX', 'INSERT', 'OPEN', 'REVOKE', 'ROLLBACK',
        'SELECT', 'SYNONYM', 'TABLE', 'UPDATE', 'VIEW',
        'APP_UID', 'ROW'
      );

      $oAdditionalTables = new AdditionalTables();
      $oFields = new Fields();

      // verify if exists.
      $aNameTable = $oAdditionalTables->loadByName($data['REP_TAB_NAME']);

      $repTabClassName = $oAdditionalTables->getPHPName($data['REP_TAB_NAME']);

      $repTabData = array(
        'ADD_TAB_UID'         => $data['REP_TAB_UID'],
        'ADD_TAB_NAME'        => $data['REP_TAB_NAME'],
        'ADD_TAB_CLASS_NAME'  => $repTabClassName,
        'ADD_TAB_DESCRIPTION' => $data['REP_TAB_DSC'],
        'ADD_TAB_PLG_UID'     => '',
        'DBS_UID'             => $data['REP_TAB_CONNECTION'],
        'PRO_UID'             => $data['PRO_UID'],
        'ADD_TAB_TYPE'        => $data['REP_TAB_TYPE'],
        'ADD_TAB_GRID'        => $data['REP_TAB_GRID']
      );

      $columns = $data['columns'];
       
      if ($data['REP_TAB_UID'] == '') { //new report table

        if ($isReportTable) { //setting default columns
          $defaultColumns = $this->_getReportTableDefaultColumns($data['REP_TAB_TYPE']);
          $columns = array_merge($defaultColumns, $columns);
        }

        /** validations **/
        if(is_array($aNameTable)) {
          throw new Exception('The table "' . $data['REP_TAB_NAME'] . '" already exits.');
        }

        if (in_array(strtoupper($data['REP_TAB_NAME']), $aReservedWords) ) {
          throw new Exception('Could not create the table with the name "' . $data['REP_TAB_NAME'] . '" because it is a reserved word.');
        }
        //create record
        $addTabUid = $oAdditionalTables->create($repTabData);

      } else { //editing report table
        $addTabUid = $data['REP_TAB_UID'];
        //loading old data before update
        $addTabBeforeData = $oAdditionalTables->load($addTabUid, true);
        //updating record
        $oAdditionalTables->update($repTabData);

        //removing old data fields references
        $oCriteria = new Criteria('workflow');
        $oCriteria->add(FieldsPeer::ADD_TAB_UID, $data['REP_TAB_UID']);
        //$oCriteria->add(FieldsPeer::FLD_NAME, 'APP_UID', Criteria::NOT_EQUAL);
        //$oCriteria->add(FieldsPeer::FLD_NAME, 'ROW', Criteria::NOT_EQUAL);
        FieldsPeer::doDelete($oCriteria);

        //getting old fieldnames
        $oldFields = array();
        foreach ($addTabBeforeData['FIELDS'] as $field) {
          $oldFields[$field['FLD_UID']] = $field;
        }
      }

      $aFields    = array();
      $fieldsList = array();
      $editFieldsList = array();

      foreach ($columns as $i => $column) {
        //new feature, to reorder the columns
        if (isset($oldFields[$column->uid])) { // the the field alreaday exists
          if ($oldFields[$column->uid]['FLD_INDEX'] != $i) { // if its index has changed
            $column->uid = ''; //set as new field,
          }
        }

        $field = array(
          'FLD_UID'               => $column->uid,
          'FLD_INDEX'             => $i,
          'ADD_TAB_UID'           => $addTabUid,
          'FLD_NAME'              => $column->field_name,
          'FLD_DESCRIPTION'       => $column->field_label,
          'FLD_TYPE'              => $column->field_type,
          'FLD_SIZE'              => $column->field_size,
          'FLD_NULL'              => (isset($column->field_null) && $column->field_null ? 1 : 0),
          'FLD_AUTO_INCREMENT'    => 0,
          'FLD_KEY'               => (isset($column->field_key) && $column->field_key ? 1 : 0),
          'FLD_FOREIGN_KEY'       => 0,
          'FLD_FOREIGN_KEY_TABLE' => '',
          'FLD_DYN_NAME'          => $column->field_dyn,
          'FLD_DYN_UID'           => $column->field_uid,
          'FLD_FILTER'            => (isset($column->field_filter) && $column->field_filter ? 1 : 0)
        );

        $fieldUid = $oFields->create($field);
        $fieldsList[] = $field;

        if($data['REP_TAB_UID'] == '') { //new
          $aFields[] = array(
            'sType'       => $column->field_type,
            'iSize'       => $column->field_size,
            'sFieldName'  => $column->field_name,
            'bNull'       => (isset($column->field_null) ? $column->field_null : 1),
            'bAI'         => 0,
            'bPrimaryKey' => (isset($column->field_key) ? $column->field_key : 0)
          );
        } else { //editing
          $field['FLD_UID'] = $fieldUid;
          $aFields[$fieldUid] = $field;
        }
      }
      if ($data['REP_TAB_UID'] == '') { //create a new report table
        $oAdditionalTables->createTable($data['REP_TAB_NAME'], $data['REP_TAB_CONNECTION'], $aFields);
      } else { //editing
        //print_R($aFields);
        $oAdditionalTables->updateTable($data['REP_TAB_NAME'], $data['REP_TAB_CONNECTION'], $aFields, $oldFields);
      }

      $oAdditionalTables->createPropelClasses($data['REP_TAB_NAME'], '', $fieldsList, $addTabUid, $data['REP_TAB_CONNECTION']);

      if ($isReportTable) {
        $oAdditionalTables->populateReportTable($data['REP_TAB_NAME'], $data['REP_TAB_CONNECTION'], $data['REP_TAB_TYPE'], $fieldsList, $data['PRO_UID'], $data['REP_TAB_GRID']);
      }

      $result->success = true;
    } catch (Exception $e) {
      $result->success = false;
      $result->msg = $e->getMessage();
      $result->trace = $e->getTraceAsString();
    }

    return $result;
  }
  
  /**
   * delete pm table
   * @param string $httpData->rows
   */
  public function delete($httpData) 
  {
    G::LoadClass('reportTables');
    $rows = G::json_decode($httpData->rows);
    $rp = new reportTables();
    $at = new AdditionalTables();
    
    try {
      foreach ($rows as $row ) {
        if($row->type == 'CLASSIC') {
          $rp->deleteReportTable($row->id);
        } else {
          $at->deleteAll($row->id);
        }
      }
      $result->success = true;
    } catch(Exception $e) {
      $result->success = false;
      $result->msg = $e->getMessage();
    }
    
    return $result;
  }

  /**
   * get pm tables data
   * @param string $httpData->id
   * @param string $httpData->start
   * @param string $httpData->limit
   */
  public function dataView($httpData)
  {
    require_once 'classes/model/AdditionalTables.php';

    G::LoadClass('configuration');
    $co = new Configurations();
    $config = $co->getConfiguration('additionalTablesData', 'pageSize','',$_SESSION['USER_LOGGED']);
    $limit_size = isset($config['pageSize']) ? $config['pageSize'] : 20;
    $start   = isset($httpData->start)  ? $httpData->start : 0;
    $limit   = isset($httpData->limit)  ? $httpData->limit : $limit_size; 

    $oAdditionalTables = new AdditionalTables();
    $table = $oAdditionalTables->load($httpData->id, true);
    $result = $oAdditionalTables->getAllData($httpData->id, $start, $limit);
    
    $keys = array();
    foreach ($table['FIELDS'] as $field) {
      if ($field['FLD_KEY'] == '1') {
        $keys[] = $field['FLD_NAME'];
      }
    }

    foreach ($result['rows'] as $i => $row) {
      $indexes = array();
      foreach ($keys as $key) {
        $indexes[] = $row[$key];
      }

      $result['rows'][$i]['__index__'] = implode('-', $indexes);
    }

    return $result;
  }

  /**
   * create pm tables record
   * @param string $httpData->id
   * @param string $httpData->start
   * @param string $httpData->limit
   */
  public function dataCreate($httpData)
  {
    require_once 'classes/model/AdditionalTables.php';
    $oAdditionalTables = new AdditionalTables();
    $table = $oAdditionalTables->load($httpData->id, true);
    $className = $table['ADD_TAB_CLASS_NAME'];
    $sClassPeerName = $className . 'Peer';
    
    $rows = G::json_decode($httpData->rows);
    if (is_array($rows)) {
      
    }
    else { //then is object 
      
    }
    print_R($row);
    //$sClassPeerName::retrieveByPk();
  }

  /**
   * update pm tables record
   * @param string $httpData->id
   * @param string $httpData->start
   * @param string $httpData->limit
   */
  public function dataUpdate($httpData)
  {
    require_once 'classes/model/AdditionalTables.php';
    $oAdditionalTables = new AdditionalTables();
    $table = $oAdditionalTables->load($httpData->id, true);
    $className = $table['ADD_TAB_CLASS_NAME'];
    $slassPeerName = $className . 'Peer';
    
    $rows = G::json_decode($httpData->rows);
    print_R($rows);
    if (is_array($rows)) {
      
    }
    else { //then is object 
      $keys = explode('-', $rows->__index__);
      foreach ($keys as $key) {
        $params .= is_numeric($key) ? $key : "'$key'";
      }

      $obj = null;
      var_dump('$obj = $classPeerName::retrieveByPk('.implode(',', $params).')');
      eval('$obj = $classPeerName::retrieveByPk('.implode(',', $params).')');
      var_dump($obj);
    }
    
    //$sClassPeerName::retrieveByPk();
  }


  /**
   * - protected functions (non callable from controller outside) -
   */

  /**
   * Get report table default columns
   * @param $type
   */
  protected function _getReportTableDefaultColumns($type='NORMAL')
  {
    $defaultColumns = array();
    $application = new stdClass(); //APPLICATION KEY
    $application->uid = '';
    $application->field_dyn   = '';
    $application->field_uid   = '';
    $application->field_name  = 'APP_UID';
    $application->field_label = 'APP_UID';
    $application->field_type  = 'VARCHAR';
    $application->field_size  = 32;
    $application->field_dyn   = '';
    $application->field_key   = 1;
    $application->field_null  = 0;
    $application->field_filter  = false;
    array_push($defaultColumns, $application);

    $application = new stdClass(); //APP_NUMBER
    $application->uid = '';
    $application->field_dyn   = '';
    $application->field_uid   = '';
    $application->field_name  = 'APP_NUMBER';
    $application->field_label = 'APP_NUMBER';
    $application->field_type  = 'INT';
    $application->field_size  = 11;
    $application->field_dyn   = '';
    $application->field_key   = 0;
    $application->field_null  = 0;
    $application->field_filter  = false;
    array_push($defaultColumns, $application);

    //if it is a grid report table
    if ($type == 'GRID') { //GRID INDEX
      $gridIndex = new stdClass();
      $gridIndex->uid = '';
      $gridIndex->field_dyn   = '';
      $gridIndex->field_uid   = '';
      $gridIndex->field_name  = 'ROW';
      $gridIndex->field_label = 'ROW';
      $gridIndex->field_type  = 'INT';
      $gridIndex->field_size  = '11';
      $gridIndex->field_dyn   = '';
      $gridIndex->field_key   = 1;
      $gridIndex->field_null  = 0;
      $gridIndex->field_filter = false;
      array_push($defaultColumns, $gridIndex);
    }

    return $defaultColumns;
  }

  /**
   * Get all dynaform fields from a process (without grid fields)
   * @param $proUid
   * @param $type [values:xmlform/grid]
   */
  function _getDynafields($proUid, $type = 'xmlform')
  {
    require_once 'classes/model/Dynaform.php';
    $fields = array();
    $fieldsNames = array();
    
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(DynaformPeer::DYN_FILENAME);
    $oCriteria->add(DynaformPeer::PRO_UID, $proUid);
    $oCriteria->add(DynaformPeer::DYN_TYPE, $type);
    $oDataset = DynaformPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();
  
    $excludeFieldsList = array('title', 'subtitle', 'link', 'file', 'button', 'reset', 'submit',
                              'listbox', 'checkgroup', 'grid', 'javascript');
    
    $labelFieldsTypeList = array('dropdown', 'checkbox', 'radiogroup', 'yesno');
  
    while ($aRow = $oDataset->getRow()) {
      if (file_exists(PATH_DYNAFORM . PATH_SEP . $aRow['DYN_FILENAME'] . '.xml')) {
        $G_FORM  = new Form($aRow['DYN_FILENAME'], PATH_DYNAFORM, SYS_LANG);
        
        if ($G_FORM->type == 'xmlform' || $G_FORM->type == '') {
          foreach($G_FORM->fields as $fieldName => $fieldNode) {
            if (!in_array($fieldNode->type, $excludeFieldsList) && !in_array($fieldName, $fieldsNames)) {
              $fields[] = array('name' => $fieldName, 'type' => $fieldNode->type, 'label'=> $fieldNode->label);
              $fieldsNames[] = $fieldName;
              
              if (in_array($fieldNode->type, $labelFieldsTypeList) && !in_array($fieldName.'_label', $fieldsNames)) {
                $fields[] = array('name' => $fieldName . '_label', 'type' => $fieldNode->type, 'label'=>$fieldNode->label . '_label');
                $fieldsNames[] = $fieldName;
              }
            }
          }
        }
      }
      $oDataset->next();
    }
    
    return $fields;
  }

  /**
   * Get all dynaform grid fields from a process
   * @param $proUid
   * @param $gridId 
   */
  function _getGridDynafields($proUid, $gridId)
  {
    $fields = array();
    $fieldsNames = array();
    $excludeFieldsList = array('title', 'subtitle', 'link', 'file', 'button', 'reset', 'submit',
                              'listbox', 'checkgroup', 'grid', 'javascript');
    
    $labelFieldsTypeList = array('dropdown', 'checkbox', 'radiogroup', 'yesno');

    $G_FORM = new Form($proUid . '/' . $gridId, PATH_DYNAFORM, SYS_LANG, false);
    
    if ($G_FORM->type == 'grid') {
      foreach($G_FORM->fields as $fieldName => $fieldNode) {
        if (!in_array($fieldNode->type, $excludeFieldsList) && !in_array($fieldName, $fieldsNames)) {
          $fields[] = array('name' => $fieldName, 'type' => $fieldNode->type, 'label'=> $fieldNode->label);
          $fieldsNames[] = $fieldName;
          
          if (in_array($fieldNode->type, $labelFieldsTypeList) && !in_array($fieldName.'_label', $fieldsNames)) {
            $fields[] = array('name' => $fieldName . '_label', 'type' => $fieldNode->type, 'label'=>$fieldNode->label . '_label');
            $fieldsNames[] = $fieldName;
          }
        }
      }
    }
    
    return $fields;
  }
  
  /**
   * Get all dynaform fields inside all grids from a process
   * @param $proUid
   */
  function _getGridFields($proUid)
  {
    $aFields = array();
    $aFieldsNames = array();
    require_once 'classes/model/Dynaform.php';
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(DynaformPeer::DYN_FILENAME);
    $oCriteria->add(DynaformPeer::PRO_UID, $proUid);
    $oDataset = DynaformPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();
    while ($aRow = $oDataset->getRow()) {
      $G_FORM  = new Form($aRow['DYN_FILENAME'], PATH_DYNAFORM, SYS_LANG);
      if ($G_FORM->type == 'xmlform') {
        foreach($G_FORM->fields as $k => $v) {
          if ($v->type == 'grid') {
            if (!in_array($k, $aFieldsNames)) {
              $aFields[] = array('name' => $k, 'xmlform' => str_replace($proUid . '/', '', $v->xmlGrid));
              $aFieldsNames[] = $k;
            }
          }
        }
      }
      $oDataset->next();
    }
    return $aFields;
  }
}
 

