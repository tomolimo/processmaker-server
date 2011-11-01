<?php
/**
 * Dashboard controller
 * @inherits Controller
 * @access public
 */

class Dashboard extends Controller {

  // Class properties
  private $pmDashlet;

  // Class constructor
  public function __construct() {
    G::LoadClass('pmDashlet');
    $this->pmDashlet = new PMDashlet();
  }

  // Functions for the dashboards users module - Start

  public function index($httpData) {
    try {
      $this->setJSVar('dashletsInstances', $this->getDashletsInstancesForCurrentUser());
      $this->includeExtJS('dashboard/index');
      $this->includeExtJSLib('ux/portal');
      G::RenderPage('publish', 'extJs');
    }
    catch (Exception $error) {
      //ToDo: Display a error message
    }
  }

  public function renderDashletInstance($data) {
    try {
      if (!isset($data->DAS_INS_UID)) {
        $data->DAS_INS_UID = '';
      }
      if ($data->DAS_INS_UID == '') {
        throw new Exception('Parameter "DAS_INS_UID" is empty.');
      }
      $this->pmDashlet->setup($data->DAS_INS_UID);

      if (!isset($_REQUEST['w']) ) {
        $width = 300;
      }
      else {
        $width = $_REQUEST['w'];
      }
      $this->pmDashlet->render( $width);
    }
    catch (Exception $error) {
      //ToDo: Render a image with the error message
    }
  }

  private function getDashletsInstancesForCurrentUser() {
    try {
      if (!isset($_SESSION['USER_LOGGED'])) {
        throw new Exception('The session has expired.');
      }
      return $this->pmDashlet->getDashletsInstancesForUser($_SESSION['USER_LOGGED']);
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  // Functions for the dashboards users module - End

  // Functions for the dasboards administration module - Start

  public function dashletsList() {
    try {
      $this->includeExtJS('dashboard/dashletsList');
      $this->setView('dashboard/dashletsList');
      G::RenderPage('publish', 'extJs');
    }
    catch (Exception $error) {
      //ToDo: Display a error message
    }
  }

  public function getDashletsInstances($data) {
    $this->setResponseType('json');
    $result = new stdclass();
    $result->status = 'OK';
    try {
      if (!isset($data->start)) {
        $data->start = null;
      }
      if (!isset($data->limit)) {
        $data->limit = null;
      }
      $result->dashletsInstances = $this->pmDashlet->getDashletsInstances($data->start, $data->limit);
      $result->totalDashletsInstances = $this->pmDashlet->getDashletsInstancesQuantity();
    }
    catch (Exception $error) {
      $result->status = 'ERROR';
      $result->message = $error->getMessage();
    }
    return $result;
  }

  public function dashletInstanceForm($data) {
    try {
      $this->includeExtJS('dashboard/dashletInstanceForm', false);
      $this->setView('dashboard/dashletInstanceForm');
      if (!isset($data->DAS_INS_UID)) {
        $data->DAS_INS_UID = '';
      }
      if ($data->DAS_INS_UID != '') {
        $this->setJSVar('dashletInstance', $this->pmDashlet->getDashletInstance($data->DAS_INS_UID));
      }
      else {
        $this->setJSVar('dashletInstance', new stdclass());
      }
      G::RenderPage('publish', 'extJs');
      return null;
    }
    catch (Exception $error) {
      //ToDo: Display a error message
      error_log($error->getMessage());
    }
  }

  public function saveDashletInstance($data) {
    $this->setResponseType('json');
    $result = new stdclass();
    $result->status = 'OK';
    try {
      $this->pmDashlet->saveDashletInstance(get_object_vars($data));
    }
    catch (Exception $error) {
      $result->status = 'ERROR';
      $result->message = $error->getMessage();
    }
    return $result;
  }

  public function deleteDashletInstance($data) {
    $this->setResponseType('json');
    $result = new stdclass();
    $result->status = 'OK';
    try {
      if (!isset($data->DAS_INS_UID)) {
        $data->DAS_INS_UID = '';
      }
      if ($data->DAS_INS_UID == '') {
        throw new Exception('Parameter "DAS_INS_UID" is empty.');
      }
      $this->pmDashlet->deleteDashletInstance($data->DAS_INS_UID);
    }
    catch (Exception $error) {
      $result->status = 'ERROR';
      $result->message = $error->getMessage();
    }
    return $result;
  }

  public function getOwnersByType($type) {
    $this->setResponseType('json');
    $result = new stdclass();
    $result->status = 'OK';
    try {
      //ToDo: For the next release
    }
    catch (Exception $error) {
      $result->status = 'ERROR';
      $result->message = $error->getMessage();
    }
    return $result;
  }

  // Functions for the dasboards administration module - End

  public function ownerData($data)
  {  try {
       require_once ("classes/model/Content.php");

       require_once ("classes/model/Users.php");
       require_once ("classes/model/Department.php");

       G::LoadInclude("ajax");
  
       //$option = $_POST["option"];
       //$option = get_ajax_value("option");
  
       $type = $data->type;
                      
       switch ($type) {
         case "USER": //
                      break;
                        
         case "DEPARTMENT": $department = array();
                    
                            $oCriteria = new Criteria("workflow");
                            $del = DBAdapter::getStringDelimiter();

                            /*
                            SELECT
                              DISTINCT
                              DEPARTMENT.DEP_UID,
                              CONTENT.CON_VALUE
                            FROM
                              DEPARTMENT AS DEP
                              LEFT JOIN CONTENT ON (DEPARTMENT.DEP_UID = CONTENT.CON_ID AND CONTENT.CON_CATEGORY = 'DYN_TITLE' AND CONTENT.CON_LANG = 'en')
                            WHERE
                              DEPARTMENT.DEP_STATUS = 'ACTIVE'
                            ORDER BY CONTENT.CON_VALUE ASC
                            */

                            //SELECT
                            $oCriteria->setDistinct();
                            $oCriteria->addSelectColumn(DepartmentPeer::DEP_UID);
                            $oCriteria->addSelectColumn(ContentPeer::CON_VALUE);
                            //FROM
                            $aConditions   = array();
                            $aConditions[] = array(DepartmentPeer::DEP_UID, ContentPeer::CON_ID);
                            $aConditions[] = array(ContentPeer::CON_CATEGORY, $del . "DEPO_TITLE" . $del);
                            $aConditions[] = array(ContentPeer::CON_LANG, $del . "en" . $del);
                            $oCriteria->addJoinMC($aConditions, Criteria::LEFT_JOIN);
                            //WHERE
                            $oCriteria->add(DepartmentPeer::DEP_STATUS, "ACTIVE");
                            //ORDER BY X ASC
                            $oCriteria->addAscendingOrderByColumn(ContentPeer::CON_VALUE);
                            
                            $departmentNumRows = DepartmentPeer::doCount($oCriteria);
                    
                            $oDataset = DepartmentPeer::doSelectRS($oCriteria);
                            $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                            while ($oDataset->next()) {
                              $row = $oDataset->getRow();

                              $departmentUID = $row["DEP_UID"];
                              $depName = $row["CON_VALUE"];
                      
                              $department[] = array("TABLE_UID" => $departmentUID, "TABLE_NAME" => $depName);
                            }
                    
                            echo G::json_encode(array("success" => true, "resultTotal" => $departmentNumRows, "resultRoot" => $department));
                            break;
       }
     }
     catch (Exception $oException) {
       echo $oException->getMessage();
       exit(0);
     }
  }
  
  public function dashletData($data)
  {  try {
       require_once ("classes/model/Dashlet.php");

       G::LoadInclude("ajax");
  
       //$option = $_POST["option"];
       //$option = get_ajax_value("option");
       
       $dashlet = array();
                    
       $oCriteria = new Criteria("workflow");

       //SELECT
       //$oCriteria->setDistinct();
       $oCriteria->addSelectColumn(DashletPeer::DAS_UID);
       $oCriteria->addSelectColumn(DashletPeer::DAS_TITLE);
       //FROM
       //WHERE
       //ORDER BY X ASC
       $oCriteria->addAscendingOrderByColumn(DashletPeer::DAS_TITLE);
    
       //echo "<hr />" . $oCriteria->toString() . "<hr />";
    
       //query
       //doCount(Criteria $criteria, $distinct = false, $con = null)
       $dashletNumRows = DashletPeer::doCount($oCriteria);
       
       $oDataset = DashletPeer::doSelectRS($oCriteria);
       $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

       while ($oDataset->next()) {
         $row = $oDataset->getRow();

         $dashletUID   = $row["DAS_UID"];
         $dashTitle = $row["DAS_TITLE"];
         
         $dashlet[] = array("DAS_UID" => $dashletUID, "DAS_TITLE" => $dashTitle);
       }
       
       //echo "{users: " . G::json_encode($rows) . ", total_users: " . $totalRows . "}";
       //echo json_encode(array("success" => true, "resultTotal" => $dashletNumRows, "resultRoot" => $dashlet));
       echo G::json_encode(array("success" => true, "resultTotal" => $dashletNumRows, "resultRoot" => $dashlet));
     }
     catch (Exception $oException) {
       echo $oException->getMessage();
       exit(0);
     }
  }
}