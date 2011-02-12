<?php
G::LoadClass('processMap');
$oProcessMap = new processMap(new DBConnection);
//$_GET['sid'] gets STEP_UID and sTYPE(i.e BEFORE and AFTER) in format STEP_UID-sTYPE
if(isset($_GET['stepid'])){
  $aStepTypeId = explode('|',$_GET['stepid']);
  $_SESSION['stepUID'] = $_GET['stepid'];
  //$aStepTypeId = explode('-','2517180104cd42c25cc39e4071099227-BEFORE');
  $sStep       = $aStepTypeId[0];
  $sType       = $aStepTypeId[1];
}

//$_GET['sid'] gets STEP_UID and sTYPE(i.e BEFORE and AFTER) in format STEP_UID-sTYPE
if(isset($_GET['stepid'])){
  $aStepTypeId = explode('|',$_GET['stepid']);
  $_SESSION['stepUID'] = $_GET['stepid'];
  //$aStepTypeId = explode('-','2517180104cd42c25cc39e4071099227-BEFORE');
  $sStep       = $aStepTypeId[0];
  $sType       = $aStepTypeId[1];
}

$start = isset($_POST['start'])? $_POST['start']: 0;
$limit = isset($_POST['limit'])? $_POST['limit']: '';

switch($_GET['action'])
{
   case 'getDynaformList':
        $rows = $oProcessMap->getExtDynaformsList($start, $limit, $_GET['pid']);
        $result['totalCount'] = $oProcessMap->getAllDynaformCount();
        array_shift($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
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
        $result['totalCount'] = count($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'getAdditionalTables':
        $rows = $oProcessMap->getExtAdditionalTablesList();
        $result['totalCount'] = count($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'getInputDocumentList':
        $rows = $oProcessMap->getExtInputDocumentsCriteria($start, $limit,$_GET['pid']);
        $result['totalCount'] = $oProcessMap->getAllInputDocumentCount();
        array_shift($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'editInputDocument':
        require_once 'classes/model/InputDocument.php';
        $oInputDocument = new InputDocument();
        $rows = $oInputDocument->load($_GET['INP_DOC_UID']);
        $tmpData = json_encode( $rows ) ;
        $tmpData = str_replace("\\/","/",'{success:true,data:'.$tmpData.'}'); // unescape the slashes

        $result = $tmpData;
        echo $result;
        break;

   case 'getOutputDocument':
        $rows = $oProcessMap->getExtOutputDocumentsCriteria($start, $limit,$_GET['pid']);
        $result['totalCount'] = $oProcessMap->getAllOutputDocumentCount();
        array_shift($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'editObjectPermission':
        $rows = $oProcessMap->editExtObjectPermission($_GET['pid'],$_GET['op_uid']);
        //array_shift($rows);
        $tmpData = json_encode( $rows ) ;
        $tmpData = str_replace("\\/","/",'{success:true,data:'.$tmpData.'}'); // unescape the slashes

        $result = $tmpData;
        echo $result;
        break;

   case 'editOutputDocument':
        require_once 'classes/model/OutputDocument.php';
        $oOutputDocument = new OutputDocument();
        $rows = $oOutputDocument->load($_GET['tid']);
        $tmpData = json_encode( $rows ) ;
        $tmpData = str_replace("\\/","/",'{success:true,data:'.$tmpData.'}'); // unescape the slashes

        $result = $tmpData;
        echo $result;
        break;

   case 'getReportTables':
        $rows        = $oProcessMap->getExtReportTables($start, $limit,$_GET['pid']);
        $result['totalCount'] = $oProcessMap->getAllReportTableCount();
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'editReportTables':
        require_once 'classes/model/ReportTable.php';
        $oReportTable = new ReportTable();
        $rows = $oReportTable->load($_GET['REP_TAB_UID']);
        $tmpData = json_encode( $rows ) ;
        $tmpData = str_replace("\\/","/",'{success:true,data:'.$tmpData.'}'); // unescape the slashes

        $result = $tmpData;
        echo $result;
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
        $result['totalCount'] = count($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'getDatabaseConnectionList':
        $rows = $oProcessMap->getExtCriteriaDBSList($start, $limit,$_GET['pid']);
        $result['totalCount'] = $oProcessMap->getAllDbSourceCount();
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'editDatabaseConnection':
        require_once 'classes/model/DbSource.php';
        $o = new DbSource();
        $rows = $o->load($_GET['dbs_uid'],$_GET['pid']);
        $tmpData = json_encode( $rows ) ;
        $tmpData = str_replace("\\/","/",'{success:true,data:'.$tmpData.'}'); // unescape the slashes

        $result = $tmpData;
        echo $result;
        break;

   case 'process_Supervisors':
        $rows = $oProcessMap->listExtProcessesSupervisors($start, $limit, $_GET['pid']);
        $result['totalCount'] = $oProcessMap->getAllProcessSupervisorsCount();
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;
            
   case 'availableProcessesSupervisors':
        $rows = $oProcessMap->listExtNoProcessesUser($_GET['pid']);
        $result['totalCount'] = count($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'supervisorDynaforms':
        $rows = $oProcessMap->getExtSupervisorDynaformsList($start, $limit,$_GET['pid']);
        $result['totalCount'] = $oProcessMap->getAllSupervisorDynaformsCount();
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;
   case 'availableSupervisorDynaforms':
        $rows = $oProcessMap->getExtAvailableSupervisorDynaformsList($_GET['pid']);
        $result['totalCount'] = count($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;
   case 'supervisorInputDoc':
        $rows = $oProcessMap->getExtSupervisorInputsList($start, $limit,$_GET['pid']);
        $result['totalCount'] = $oProcessMap->getAllSupervisorInputsCount();
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;
   case 'availableSupervisorInputDoc':
        $rows = $oProcessMap->getExtAvailableSupervisorInputsList($_GET['pid']);
        $result['totalCount'] = count($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'getAssignedCaseTrackerObjects':
        $rows = $oProcessMap->getExtCaseTrackerObjectsCriteria($start, $limit, $_GET['pid']);
        $result['totalCount'] = $oProcessMap->getAllCaseTrackerObjectCount();
        array_shift($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'getAvailableCaseTrackerObjects':
        $rows = $oProcessMap->getAvailableExtCaseTrackerObjects($_GET['pid']);
        array_shift($rows);
        $result['totalCount'] = count($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'getAvailableSteps':
        $rows = $oProcessMap->getExtAvailableBBCriteria($_GET['pid'], $_GET['tid']);
        array_shift($rows);
        $result['totalCount'] = count($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'getAssignedSteps':
        $rows = $oProcessMap->getExtStepsCriteria($start, $limit,$_GET['tid']);
        $result['totalCount'] = $oProcessMap->getAllStepCount();
        array_shift($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'getAssignedUsersList':
        $rows        = $oProcessMap->usersExtList($start, $limit, $_GET['pid'], $_GET['tid']);
        $result['totalCount'] = $oProcessMap->getAllTaskUserCount();
        array_shift($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'getAvailableUsersList':
        $rows = $oProcessMap->getAvailableExtUsersCriteria($_GET['tid']);
        $result['totalCount'] = count($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'getAvailableStepTriggers':
        $aStepTypeId = explode('|',$_SESSION['stepUID']);
        $sStep       = $aStepTypeId[0];
        $sType       = $aStepTypeId[1];
        //Getting available Steps Criteria that have been not selected for a particular task
        $rows        = $oProcessMap->getExtAvailableStepTriggersCriteria($_GET['pid'], $sStep, $_GET['tid'], $sType);
        $result['totalCount'] = count($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'getAssignedStepTriggers':
        $rows        = $oProcessMap->getExtStepTriggersCriteria($start, $limit, $sStep, $_GET['tid'], $sType);
        $result['totalCount'] = $oProcessMap->getAllStepTriggerCount();
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'availableUsers':
        $rows = $oProcessMap->getExtAvailableUsersList($_GET['tid']);
        array_shift($rows);
        $result['totalCount'] = count($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'assignedUsers':
        $rows        = $oProcessMap->getExtusersadhoc($start, $limit,$_GET['pid'], $_GET['tid']);
        $result['totalCount'] = $oProcessMap->getAllTaskUserCount();
        array_shift($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
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
        $tmpData = json_encode( $rows ) ;
        $tmpData = str_replace("\\/","/",'{success:true,data:'.$tmpData.'}'); // unescape the slashes

        $result = $tmpData;
        echo $result;
        break;

   case 'getObjectPermission':
        $rows = $oProcessMap->getExtObjectsPermissions($start, $limit,$_GET['pid']);
        $result['totalCount'] = $oProcessMap->getAllObjectPermissionCount();
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'getObjectPermissionType':
        $rows = $oProcessMap->newExtObjectPermission($_GET['pid'],$_GET['objectType']);
        array_shift($rows);
        $result['totalCount'] = count($rows);
        $result['data'] = $rows;
        print json_encode( $result ) ;
        break;

   case 'process_Edit':
  	$rows = $oProcessMap->editProcessNew($_GET['pid']);
        $tmpData = json_encode( $rows ) ;
        $tmpData = str_replace("\\/","/",'{success:true,data:'.$tmpData.'}'); // unescape the slashes

        $result = $tmpData;
        echo $result;
        break;
}
  
   //$result['data'] = $rows;
   //print json_encode( $result ) ;
    /*$tmpData = json_encode( $rows ) ;
    $tmpData = str_replace("\\/","/",'{success:true,data:'.$tmpData.'}'); // unescape the slashes

    $result = $tmpData;
    echo $result;*/
?>
