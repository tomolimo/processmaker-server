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
    $additionalTables = new AdditionalTables();

    if ($addTabUid !== false) { // if is a edit request
      require_once 'classes/model/AdditionalTables.php';
      require_once 'classes/model/Fields.php';
      $tableFields = array();
      $fieldsList = array();
      
      $table = $additionalTables->load($addTabUid, true);

      // list the case fields
      foreach ($table['FIELDS'] as $i=>$field) {
        $table['FIELDS'][$i]['FLD_KEY']    = $field['FLD_KEY'] == '1' ? TRUE: FALSE;
        $table['FIELDS'][$i]['FLD_NULL']   = $field['FLD_NULL'] == '1' ? TRUE: FALSE;
        $table['FIELDS'][$i]['FLD_FILTER'] = $field['FLD_FILTER'] == '1' ? TRUE: FALSE;
        array_push($tableFields, $field['FLD_DYN_NAME']);
      }

      //list dynaform fields
      switch ($table['ADD_TAB_TYPE']) {
        case 'NORMAL':
          $fields = pmTablesProxy::_getDynafields($table['PRO_UID']);

          foreach ($fields as $field) {
            //select to not assigned fields for available grid
            if (!in_array($field['name'], $tableFields)) {
              $fieldsList[] = array(
                'FIELD_UID'  => $field['name'] . '-' . $field['type'],
                'FIELD_NAME' => $field['name']
              );
            }
          }
          $this->setJSVar('avFieldsList', $fieldsList);
          $repTabPluginPermissions = $this->_getSimpleReportPluginDef();
          $this->setJSVar('_plugin_permissions', $repTabPluginPermissions);
          break;
          
        case 'GRID':
          list($gridName, $gridId) = explode('-', $table['ADD_TAB_GRID']);
          // $G_FORM = new Form($table['PRO_UID'] . '/' . $gridId, PATH_DYNAFORM, SYS_LANG, false);
          // $gridFields = $G_FORM->getVars(false);
          $fieldsList = array();
          $gridFields = pmTablesProxy::_getGridDynafields($table['PRO_UID'], $gridId);
          foreach ($gridFields as $gfield) {
            if (!in_array($gfield['name'], $tableFields)) {
              $fieldsList[] = array(
                'FIELD_UID' => $gfield['name'] . '-' . $gfield['type'],
                'FIELD_NAME' => $gfield['name']
              );
            }
          }

          $this->setJSVar('avFieldsList', $fieldsList);
          $repTabPluginPermissions = $this->_getSimpleReportPluginDef();
          break;
          
        default:
          
          break;
      }
    }

    $jsFile = isset($httpData->tableType) && $httpData->tableType == 'report' ? 'editReport' : 'edit';
    
    $this->includeExtJS('pmTables/' . $jsFile, $this->debug);

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

