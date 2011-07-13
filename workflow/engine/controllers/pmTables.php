<?php
/**
 * pmTables controller
 * @author Erik Amaru Ortiz <erik@colosa.com, aortiz.erik@gmail.com>
 * @inherits Controller
 * @access public
 */

class pmTables extends Controller
{
  /**
   * @param boolean debug
   */
  public $debug = FALSE;
  
  /**
   * getting default list
   * @param string $httpData->PRO_UID (opional)
   */
  public function index($httpData)
  {
    global $RBAC;
    $RBAC->requirePermissions('PM_SETUP_ADVANCE');

    G::LoadClass('configuration');
    $c = new Configurations();
    $configPage = $c->getConfiguration('additionalTablesList', 'pageSize','',$_SESSION['USER_LOGGED']);
    $Config['pageSize'] = isset($configPage['pageSize']) ? $configPage['pageSize'] : 20;

    $this->includeExtJS('pmTables/list', $this->debug);
    $this->setView('pmTables/list');
    
    //assigning js variables
    $this->setJSVar('FORMATS',$c->getFormats());
    $this->setJSVar('CONFIG', $Config);
    $this->setJSVar('PRO_UID', isset($_GET['PRO_UID'])? $_GET['PRO_UID'] : false);

    $this->setJSVar('_PLUGIN_SIMPLEREPORTS', $this->_getSimpleReportPluginDef());

    if (isset($_SESSION['_cache_pmtables'])) {
      unset($_SESSION['_cache_pmtables']);
    }

    if (isset($_SESSION['ADD_TAB_UID'])) {
      unset($_SESSION['ADD_TAB_UID']);
    }

    //render content
    G::RenderPage('publish', 'extJs');
  }

  /**
   * edit pmtable
   * @param string $httpData->id
   */
  public function edit($httpData)
  {
    require_once PATH_CONTROLLERS . 'pmTablesProxy.php';
    $addTabUid = isset($httpData->id) ? $httpData->id : false;
    $table = false;
    $repTabPluginPermissions = false;
    $additionalTables = new AdditionalTables();

    if ($addTabUid !== false) { // if is a edit request
      require_once 'classes/model/AdditionalTables.php';      
      $tableFields = array();
      $fieldsList = array();
      
      $table = $additionalTables->load($addTabUid, true);
      $_SESSION['ADD_TAB_UID'] = $addTabUid;

      //list dynaform fields
      switch ($table['ADD_TAB_TYPE']) {
        case 'NORMAL':
        case 'GRID':
          $repTabPluginPermissions = $this->_getSimpleReportPluginDef();
          break;
      }
    }

    $jsFile = isset($httpData->tableType) && $httpData->tableType == 'report' ? 'editReport' : 'edit';
    
    $this->includeExtJS('pmTables/' . $jsFile, $this->debug);

    //fix for backware compatibility
    if ($table) {
      $table['DBS_UID'] = $table['DBS_UID'] == null || $table['DBS_UID'] == '' ? 'workflow': $table['DBS_UID'];
    }

    $this->setJSVar('ADD_TAB_UID', $addTabUid);
    $this->setJSVar('PRO_UID', isset($_GET['PRO_UID'])? $_GET['PRO_UID'] : false);
    $this->setJSVar('TABLE', $table);
    $this->setJSVar('_plugin_permissions', $repTabPluginPermissions);

    G::RenderPage('publish', 'extJs');
  }
  
  /**
   * show pmTable data list
   * @param string $httpData->id
   */
  function data($httpData) 
  {
    require_once 'classes/model/AdditionalTables.php';
    $additionalTables = new AdditionalTables();
    $tableDef = $additionalTables->load($httpData->id, true);
    
    $this->includeExtJS('pmTables/data', $this->debug);
    $this->setJSVar('tableDef', $tableDef);
    //g::pr($tableDef['FIELDS']);
    G::RenderPage('publish', 'extJs');
  } 

  function export($httpData)
  {
    $this->includeExtJS('pmTables/export', $this->debug);    //adding a javascript file .js
    $this->setView('pmTables/export'); //adding a html file  .html.

    $toSend = Array();
    $toSend['UID_LIST'] = $httpData->id;

    $this->setJSVar('EXPORT_TABLES', $toSend);
    G::RenderPage('publish', 'extJs');
  }
   

   /**
   * - protected functions (non-callable from controller outside) -
   */
  
  /**
   * Get simple report plugin definition
   * @param $type
   */ 
  protected function _getSimpleReportPluginDef()
  {
    global $G_TMP_MENU;
    $oMenu = new Menu();
    $oMenu->load('setup');
    $repTabPluginPermissions = false;

    foreach( $oMenu->Options as $i=>$option) {
      if ($oMenu->Types[$i] == 'private' && $oMenu->Id[$i] == 'PLUGIN_REPTAB_PERMISSIONS') {
        $repTabPluginPermissions = array();
        $repTabPluginPermissions['label'] = $oMenu->Labels[$i];
        $repTabPluginPermissions['fn'] = $oMenu->Options[$i];
        break;
      }
    }

    return $repTabPluginPermissions;
  }

}


