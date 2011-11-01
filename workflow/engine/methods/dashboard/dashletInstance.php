<?php
try {
  require_once ("classes/model/Content.php");

  require_once ("classes/model/Dashlet.php");
  require_once ("classes/model/Users.php");
  require_once ("classes/model/Department.php");

  G::LoadInclude("ajax");
  
  //$option = $_POST["option"];
  $option = get_ajax_value("option");
  
  switch ($option) {
    case "OWNERTYPE": $type = get_ajax_value("type");
                      
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
                      
                      break;

    case "DASHLST": $dashlet = array();
                    
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
                    break;
  }
}
catch (Exception $oException) {
  echo $oException->getMessage();
  exit(0);
}
?>