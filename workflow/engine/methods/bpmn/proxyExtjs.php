<?php
G::LoadClass('processMap');
$oProcessMap = new processMap(new DBConnection);

switch($_GET['action'])
  {
   case 'getDynaformList':
            $rows        = $oProcessMap->getExtDynaformsList($_GET['pid']);
            array_shift($rows);
            break;

   case 'getPMTableDynaform':
            $oAdditionalTables = new AdditionalTables();
            $aData = $oAdditionalTables->load($_GET['tabId'], true);
            $addTabName = $aData['ADD_TAB_NAME'];
            foreach ($aData['FIELDS'] as $iRow => $aRow) {
                if ($aRow['FLD_KEY'] == 1) {
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

   case 'editOutputDocument':
            require_once 'classes/model/OutputDocument.php';
            $oOutputDocument = new OutputDocument();
            $rows = $oOutputDocument->load($_GET['tid']);
            break;

   case 'getReportTables':
           $rows        = $oProcessMap->getExtReportTables($_GET['pid']);
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
            break;

   case 'getAvailableCaseTrackerObjects':
            $rows = $oProcessMap->getAvailableExtCaseTrackerObjects($_GET['tid']);
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

  


            
  }
  $result['totalCount'] = count($rows);
   $result['data'] = $rows;
   print json_encode( $result ) ;

 
      ?>