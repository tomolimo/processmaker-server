<?php
G::LoadClass('processMap');
$oProcessMap = new processMap(new DBConnection);
//$_GET['sid'] gets STEP_UID and sTYPE(i.e BEFORE and AFTER) in format STEP_UID-sTYPE
if(isset($_GET['stepid']))
    {
      $aStepTypeId = explode('|',$_GET['stepid']);
      $_SESSION['stepUID'] = $_GET['stepid'];
      //$aStepTypeId = explode('-','2517180104cd42c25cc39e4071099227-BEFORE');
      $sStep       = $aStepTypeId[0];
      $sType       = $aStepTypeId[1];
    }

    //$_GET['sid'] gets STEP_UID and sTYPE(i.e BEFORE and AFTER) in format STEP_UID-sTYPE
    if(isset($_GET['stepid']))
    {
       $aStepTypeId = explode('|',$_GET['stepid']);
       $_SESSION['stepUID'] = $_GET['stepid'];
       //$aStepTypeId = explode('-','2517180104cd42c25cc39e4071099227-BEFORE');
       $sStep       = $aStepTypeId[0];
       $sType       = $aStepTypeId[1];
    }

switch($_GET['action'])
    {
       case 'getDynaformList':
                $rows = $oProcessMap->getExtDynaformsList($_GET['pid']);
                array_shift($rows);
                break;

       case 'getPMTableDynaform':
                $oAdditionalTables = new AdditionalTables();
                $aData = $oAdditionalTables->load($_GET['tabId'], true);
                $addTabName = $aData['ADD_TAB_NAME'];
                foreach ($aData['FIELDS'] as $iRow => $aRow)
                    {
                    if ($aRow['FLD_KEY'] == 1)
                        {
                        $rows[] = $aRow;
                        }
                    }
                break;

       case 'getAdditionalTables':
                $rows = $oProcessMap->getExtAdditionalTablesList();
                break;

       case 'getInputDocumentList':
                $rows = $oProcessMap->getExtInputDocumentsCriteria($_GET['pid']);
                array_shift($rows);
                break;

       case 'editInputDocument':
                require_once 'classes/model/InputDocument.php';
                $oInputDocument = new InputDocument();
                $rows = $oInputDocument->load($_GET['INP_DOC_UID']);
                break;

       case 'getOutputDocument':
                $rows = $oProcessMap->getExtOutputDocumentsCriteria($_GET['pid']);
                array_shift($rows);
                break;

      case 'editObjectPermission':
                $rows = $oProcessMap->editExtObjectPermission($_GET['op_uid'],$_GET['pid']);
                array_shift($rows);
                break;

       case 'editOutputDocument':
                require_once 'classes/model/OutputDocument.php';
                $oOutputDocument = new OutputDocument();
                $rows = $oOutputDocument->load($_GET['tid']);
                break;

       case 'getReportTables':
               $rows        = $oProcessMap->getExtReportTables($_GET['pid']);
               break;

      case 'editReportTables':
                require_once 'classes/model/ReportTable.php';
                $oReportTable = new ReportTable();
                $rows = $oReportTable->load($_GET['REP_TAB_UID']);
                break;

       case 'getReportTableType':
              if(isset($_GET['pid']) && $_GET['type'] == 'NORMAL')
                {
                  $aTheFields = array();
                  $aTheFields = getDynaformsVars($_GET['pid'], false);
                  foreach ($aTheFields as $aField)
                    {
                       $rows[]  = array('FIELD_UID'  => $aField['sName'] . '-' . $aField['sType'],
                      'FIELD_NAME' => $aField['sName']);
                    }
                }

              else if (isset($_GET['pid']) && $_GET['type'] == 'GRID')
                 {
                    $aTheFields = array();
                    $aTheFields = getGridsVars($_GET['pid']);
                    foreach ($aTheFields as $aField)
                        {
                           $rows[]  = array('FIELD_UID'  => $aField['sName'] . '-' . $aField['sXmlForm'],
                           'FIELD_NAME' => $aField['sName']);
                        }
                }
              break;

       case 'getDatabaseConnectionList':
                $rows = $oProcessMap->getExtCriteriaDBSList($_GET['pid']);
                break;

       case 'editDatabaseConnection':
                require_once 'classes/model/DbSource.php';
                $o = new DbSource();
                $rows = $o->load($_GET['tid']);
                break;

       case 'process_User':
                $rows = $oProcessMap->listExtProcessesUser($processUID);
                break;
       case 'availableProcessesUser':
                $rows = $oProcessMap->listExtNoProcessesUser($processUID);
                break;
       case 'supervisorDynaforms':
                $rows = $oProcessMap->getExtSupervisorDynaformsList($processUID);
                break;
       case 'availableSupervisorDynaforms':
                $rows = $oProcessMap->getExtAvailableSupervisorDynaformsList($processUID);
                break;
       case 'supervisorInputDoc':
                $rows = $oProcessMap->getExtSupervisorInputsList($processUID);
                break;
       case 'availableSupervisorInputDoc':
                $rows = $oProcessMap->getExtAvailableSupervisorInputsList($processUID);
                break;

       case 'getAssignedCaseTrackerObjects':
                $rows = $oProcessMap->getExtCaseTrackerObjectsCriteria($_GET['pid']);
                 array_shift($rows);
                break;

       case 'getAvailableCaseTrackerObjects':
                $rows = $oProcessMap->getAvailableExtCaseTrackerObjects($_GET['tid']);
                array_shift($rows);
                break;

       case 'getAvailableSteps':
                $rows = $oProcessMap->getExtAvailableBBCriteria($_GET['pid'], $_GET['tid']);
                array_shift($rows);
                break;

       case 'getAssignedSteps':
                $rows = $oProcessMap->getExtStepsCriteria($_GET['tid']);
                array_shift($rows);
                break;

       case 'getAssignedUsersList':
                $rows        = $oProcessMap->usersExtList($_GET['pid'], $_GET['tid']);
                array_shift($rows);
                break;

       case 'getAvailableUsersList':
                $rows = $oProcessMap->getAvailableExtUsersCriteria($_GET['tid']);
                break;

       case 'getAvailableStepTriggers':
               $aStepTypeId = explode('|',$_SESSION['stepUID']);
               $sStep       = $aStepTypeId[0];
               $sType       = $aStepTypeId[1];
               //Getting available Steps Criteria that have been not selected for a particular task
               $rows        = $oProcessMap->getExtAvailableStepTriggersCriteria($_GET['pid'], $sStep, $_GET['tid'], $sType);
               break;

       case 'getAssignedStepTriggers':
               $rows        = $oProcessMap->getExtStepTriggersCriteria($sStep, $_GET['tid'], $sType);
               break;

       case 'availableUsers':
               $rows = $oProcessMap->getExtAvailableUsersList($_GET['tid']);
               array_shift($rows);
               break;

       case 'assignedUsers':
               $rows        = $oProcessMap->getExtusersadhoc($_GET['pid'], $_GET['tid']);
               array_shift($rows);
               break;

      case 'getTaskPropertiesList':
               require_once 'classes/model/Task.php';
               $oTask = new Task();
               $rows = $oTask->load($_GET['tid']);
                 while (list($key, $value) = each($rows)) {
                    if ($value == 'TRUE')
                        $rows[$key] = true;
                    else if($value == 'FALSE')
                        $rows[$key] = false;

                    if($key == 'TAS_TYPE_DAY' && $value == '1')
                        $rows[$key] = 'Work Days';
                    else if($key == 'TAS_TYPE_DAY' && $value == '2')
                        $rows[$key] = 'Calendar Days';

                    if($key == 'TAS_TYPE' && $value == 'NORMAL')
                         $rows[$key] = false;
                    else if($key == 'TAS_TYPE' && $value == 'ADHOC')
                           $rows[$key] = true;



                    
                }

    }
   //$result['totalCount'] = count($rows);
   //$result['data'] = $rows;
   //print json_encode( $result ) ;
    $tmpData = json_encode( $rows ) ;
    $tmpData = str_replace("\\/","/",'{success:true,data:'.$tmpData.'}'); // unescape the slashes

    $result = $tmpData;
    echo $result;
 ?>
