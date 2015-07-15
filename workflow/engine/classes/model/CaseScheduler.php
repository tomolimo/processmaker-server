<?php
/**
 * CaseScheduler.php
 *
 * @package workflow.engine.classes.model
 */

//require_once 'classes/model/om/BaseCaseScheduler.php';

//require_once 'classes/model/Process.php';
//require_once 'classes/model/Task.php';

/**
 * Skeleton subclass for representing a row from the 'CASE_SCHEDULER' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package workflow.engine.classes.model
 */
class CaseScheduler extends BaseCaseScheduler
{
    public function load ($SchUid)
    {
        try {
            $oRow = CaseSchedulerPeer::retrieveByPK( $SchUid );
            if (! is_null( $oRow )) {
                $aFields = $oRow->toArray( BasePeer::TYPE_FIELDNAME );
                $this->fromArray( $aFields, BasePeer::TYPE_FIELDNAME );
                $this->setNew( false );
                return $aFields;
            } else {
                throw (new Exception( "The row '" . $SchUid . "' in table CASE_SCHEDULER doesn't exist!" ));
            }
        } catch (Exception $oError) {
            throw ($oError);
        }
    }

    public function create ($aData)
    {
        $con = Propel::getConnection( CaseSchedulerPeer::DATABASE_NAME );
        try {
            $this->fromArray( $aData, BasePeer::TYPE_FIELDNAME );
            if ($this->validate()) {
                $result = $this->save();
            } else {
                $e = new Exception( "Failed Validation in class " . get_class( $this ) . "." );
                $e->aValidationFailures = $this->getValidationFailures();
                throw ($e);
            }
            $con->commit();

            //Add Audit Log
            $perform = $aData["SCH_OPTION"];

            switch ($aData['SCH_OPTION']) {
                case '1':
                    $perform = 'Daily';
                    break;
                case '2':
                    $perform = 'Weekly';
                    break;
                case '3':
                    $perform = 'Monthly';
                    break;
                case '4':
                    $perform = 'One time only';
                    break;
                case '5':
                    $perform = 'Every';
                    break;

            }
            G::auditLog("CreateCaseScheduler", "Scheduler Name: ".$aData['SCH_NAME'].", Task: ".$aData['TAS_UID'].", Perform this task: ".$perform.", Start Date: ".$aData['SCH_START_DATE'].", End Date: ".$aData['SCH_END_DATE'].",  Execution time  : ".$aData['SCH_START_TIME']);

            return $result;
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    public function update ($fields)
    {
        $con = Propel::getConnection( CaseSchedulerPeer::DATABASE_NAME );
        try {
            $con->begin();
            $this->load( $fields['SCH_UID'] );
            $this->fromArray( $fields, BasePeer::TYPE_FIELDNAME );
            if ($this->validate()) {
                $result = $this->save();
                $con->commit();

                if (isset($fields['SCH_OPTION'])) {
                    //Add Audit Log
                    switch ($fields['SCH_OPTION']){
                    case '1':
                        $perform = 'Daily';
                        break;
                    case '2':
                        $perform = 'Weekly';
                        break;
                    case '3':
                        $perform = 'Monthly';
                        break;
                    case '4':
                        $perform = 'One time only';
                        break;
                    case '5':
                        $perform = 'Every';
                        break;
                    }
                    G::auditLog("UpdateCaseScheduler", "Scheduler Name: ".$fields['SCH_NAME'].", Task: ".$fields['TAS_UID'].", Perform this task: ".$perform.", Start Date: ".$fields['SCH_START_DATE'].", End Date: ".$fields['SCH_END_DATE'].",  Execution time  : ".$fields['SCH_START_TIME']);
                }

                return $result;
            } else {
                $con->rollback();
                throw (new Exception( "Failed Validation in class " . get_class( $this ) . "." ));
            }
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    public function remove ($SchUid)
    {
        $con = Propel::getConnection( CaseSchedulerPeer::DATABASE_NAME );
        try {
            $oCaseScheduler = CaseSchedulerPeer::retrieveByPK( $SchUid );
            if (! is_null( $oCaseScheduler )) {
                $fields = $this->Load( $SchUid );
                $iResult = $oCaseScheduler->delete();
                $con->commit();
                //Add Audit Log
                G::auditLog("DeleteCaseScheduler", "Scheduler Name: ".$fields['SCH_NAME'].", Task: ".$fields['TAS_UID']);

                return $iResult;
            } else {
                throw (new Exception( 'This row doesn\'t exist!' ));
            }
        } catch (Exception $e) {
            $con->rollback();
            throw ($e);
        }
    }

    /*
    * change Status of any Process
    * @param string $sSchedulerUid
    * @return boolean
    */
    public function changeStatus ($sSchedulerUid = '')
    {
        $Fields = $this->Load( $sSchedulerUid );
        $Fields['SCH_LAST_STATE'] = $Fields['SCH_STATE'];
        if ($Fields['SCH_STATE'] == 'ACTIVE') {
            $Fields['SCH_STATE'] = 'INACTIVE';
        } else {
            $Fields['SCH_STATE'] = 'ACTIVE';
        }
        $this->Update( $Fields );
    }

    public function getAllCriteria ()
    {
        $c = new Criteria( 'workflow' );
        $c->clearSelectColumns();
        $c->addSelectColumn( CaseSchedulerPeer::SCH_UID );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_NAME );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_DEL_USER_NAME );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_DEL_USER_PASS );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_DEL_USER_UID );
        $c->addSelectColumn( CaseSchedulerPeer::PRO_UID );
        $c->addSelectColumn( CaseSchedulerPeer::TAS_UID );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_TIME_NEXT_RUN );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_LAST_RUN_TIME );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_STATE );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_LAST_STATE );
        $c->addSelectColumn( CaseSchedulerPeer::USR_UID );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_OPTION );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_START_TIME );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_START_DATE );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_DAYS_PERFORM_TASK );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_EVERY_DAYS );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_WEEK_DAYS );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_START_DAY );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_MONTHS );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_END_DATE );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_REPEAT_EVERY );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_REPEAT_UNTIL );
        $c->addSelectColumn( CaseSchedulerPeer::SCH_REPEAT_STOP_IF_RUNNING );
        $c->addSelectColumn( CaseSchedulerPeer::CASE_SH_PLUGIN_UID );

        return $c;
    }

    public function getAll ()
    {
        $oCriteria = $this->getAllCriteria();
        $oDataset = CaseSchedulerPeer::doSelectRS( $oCriteria );

        $oDataset->setFetchmode( ResultSet::FETCHMODE_ASSOC );
        $oDataset->next();
        $aRows = Array ();
        while ($aRow = $oDataset->getRow()) {
            $aRows[] = $aRow;
            $oDataset->next();
        }
        foreach ($aRows as $k => $aRow) {
            $oProcess = new Process();
            $aProcessRow = $oProcess->load( $aRow['PRO_UID'] );

            $oTask = new Task();
            $aTaskRow = $oTask->load( $aRow['TAS_UID'] );

            $aRows[$k] = array_merge( $aRow, $aProcessRow, $aTaskRow );
        }

        return $aRows;
    }

    /**
     * function getAllByProcess
     * Get All Scheduled Tasks for some process.
     *
     * @author gustavo cruz
     * @param $pro_uid process uid
     * @return $aRows a result set array
     */
    public function getAllByProcess ($pro_uid)
    {

        $oCriteria = $this->getAllCriteria();
        $oCriteria->add( CaseSchedulerPeer::PRO_UID, $pro_uid );
        $oDataset = CaseSchedulerPeer::doSelectRS( $oCriteria );

        $oDataset->setFetchmode( ResultSet::FETCHMODE_ASSOC );
        $oDataset->next();
        $aRows = Array ();
        while ($aRow = $oDataset->getRow()) {
            $aRows[] = $aRow;
            $oDataset->next();
        }
        foreach ($aRows as $k => $aRow) {
            $oProcess = new Process();
            $aProcessRow = $oProcess->load( $aRow['PRO_UID'] );
            $oTask = new Task();
            $aTaskRow = $oTask->load( $aRow['TAS_UID'] );
            $aRows[$k] = array_merge( $aRow, $aProcessRow, $aTaskRow );
        }
        return $aRows;
    }

    public function getProcessDescription ()
    {
        $c = new Criteria( 'workflow' );
        $c->clearSelectColumns();
        $c->addSelectColumn( ProcessPeer::PRO_UID );

        $oDataset = ProcessPeer::doSelectRS( $c );
        $oDataset->setFetchmode( ResultSet::FETCHMODE_ASSOC );
        $oDataset->next();
        $aRows = Array ();
        while ($aRow = $oDataset->getRow()) {
            $aRows[] = $aRow;
            $oDataset->next();
        }

        foreach ($aRows as $k => $aRow) {
            $oProcess = new Process();
            $aProcessRow = $oProcess->load( $aRow['PRO_UID'] );

            $aRows[$k] = array_merge( $aRow, array ('PRO_TITLE' => $aProcessRow['PRO_TITLE']
            ) );
        }
        return $aRows;

    }

    public function getTaskDescription ()
    {
        $c = new Criteria( 'workflow' );
        $c->clearSelectColumns();
        $c->addSelectColumn( TaskPeer::TAS_UID );

        $oDataset = TaskPeer::doSelectRS( $c );
        $oDataset->setFetchmode( ResultSet::FETCHMODE_ASSOC );
        $oDataset->next();
        $aRows = Array ();
        while ($aRow = $oDataset->getRow()) {
            $aRows[] = $aRow;
            $oDataset->next();
        }
        foreach ($aRows as $k => $aRow) {

            $oTask = new Task();
            $aTaskRow = $oTask->load( $aRow['TAS_UID'] );

            $aRows[$k] = array_merge( $aRow, array ('TAS_TITLE' => $aTaskRow['TAS_TITLE']
            ), array ('PRO_UID' => $aTaskRow['PRO_UID']
            ) );
        }
        return $aRows;
    }

    public function caseSchedulerCron ($date, &$log = array(), $cron = 0)
    {
        G::LoadClass( 'dates' );
        require_once ('classes/model/LogCasesScheduler.php');
        $oDates = new dates();
        $nTime = strtotime( $date );
        $dCurrentDate = date( 'Y-m-d', $nTime ) . ' 00:00:00';
        $dNextDay = date( 'Y-m-d', strtotime( "$dCurrentDate" ) ) . ' 23:59:59';
        $oCriteria = $this->getAllCriteria();
        $oCriteria->addAnd( CaseSchedulerPeer::SCH_STATE, 'INACTIVE', Criteria::NOT_EQUAL );
        $oCriteria->addAnd( CaseSchedulerPeer::SCH_STATE, 'PROCESSED', Criteria::NOT_EQUAL );
        $oCriteria->add( $oCriteria->getNewCriterion(CaseSchedulerPeer::SCH_TIME_NEXT_RUN, $dCurrentDate, Criteria::GREATER_EQUAL )->
                        addAnd( $oCriteria->getNewCriterion(  CaseSchedulerPeer::SCH_TIME_NEXT_RUN, $dNextDay, Criteria::LESS_EQUAL ) )->
                        addOr( $oCriteria->getNewCriterion( CaseSchedulerPeer::SCH_OPTION, '5', Criteria::GREATER_EQUAL ) )
                        );
        $oCriteria->add( CaseSchedulerPeer::SCH_END_DATE, null, Criteria::EQUAL );
        $oCriteria->addOr( CaseSchedulerPeer::SCH_END_DATE, $dCurrentDate, Criteria::GREATER_EQUAL );
        $oDataset = CaseSchedulerPeer::doSelectRS( $oCriteria );
        $oDataset->setFetchmode( ResultSet::FETCHMODE_ASSOC );

        $sValue = '';
        $sActualTime = '';
        $sDaysPerformTask = '';
        $sWeeks = '';
        $sStartDay = '';
        $sMonths = '';

        while ($oDataset->next()) {
            $aRow = $oDataset->getRow();

            if ($cron == 1) {
                $arrayCron = unserialize( trim( @file_get_contents( PATH_DATA . "cron" ) ) );
                $arrayCron["processcTimeStart"] = time();
                @file_put_contents( PATH_DATA . "cron", serialize( $arrayCron ) );
            }

            $sSchedulerUid = $aRow['SCH_UID'];
            $sOption = $aRow['SCH_OPTION'];
            switch ($sOption) {
                case '1':
                    $sDaysPerformTask = $aRow['SCH_DAYS_PERFORM_TASK'];
                    $aDaysPerformTask = explode( '|', $sDaysPerformTask );
                    $sValue = $aDaysPerformTask[0];
                    if ($sValue != 1) {
                        $sDaysPerformTask = $aDaysPerformTask[1];
                    }
                    break;
                case '2':
                    $sDaysPerformTask = $aRow['SCH_EVERY_DAYS'];
                    $sWeeks = $aRow['SCH_WEEK_DAYS'];
                    break;
                case '3':
                    $sStartDay = $aRow['SCH_START_DAY'];
                    $sMonths = $aRow['SCH_MONTHS'];
                    $aStartDay = explode( '|', $sStartDay );
                    $sValue = $aStartDay[0];
                    break;
                case '4':
                    $aRow['SCH_STATE'] = 'PROCESSED';
                    break;
                case '5':
                    break;
            }

            $sActualTime = $aRow['SCH_TIME_NEXT_RUN'];
            $sActualDataHour    = (int)(date("H", strtotime($aRow["SCH_TIME_NEXT_RUN"])));
            $sActualDataMinutes = (int)(date("i", strtotime($aRow["SCH_TIME_NEXT_RUN"])));
            $dActualSysHour     = (int)(date("H", $nTime));
            $dActualSysMinutes  = (int)(date("i", $nTime));
            $sActualDataTime = strtotime( $aRow['SCH_TIME_NEXT_RUN'] );
            $sActualSysTime = strtotime( $nTime );

            if ($sActualDataHour == $dActualSysHour && $sActualDataMinutes <= $dActualSysMinutes) {
            //if ($sActualDataHour == $dActualSysHour && $sActualDataMinutes == $dActualSysMinutes) {
                $port = "";

                if (isset($_SERVER["SERVER_PORT"])) {
                    $port = ($_SERVER["SERVER_PORT"] . "" != "80")? ":" . $_SERVER["SERVER_PORT"] : "";
                } else {
                    if (defined("SERVER_PORT")) {
                        $port = (SERVER_PORT . "" != "80")? ":" . SERVER_PORT : "";
                    }
                }

                $defaultEndpoint = "http://" . SERVER_NAME . $port . "/sys" . SYS_SYS . "/" . SYS_LANG . "/classic/services/wsdl2";

                println( " - Connecting webservice: $defaultEndpoint" );
                $user = $aRow["SCH_DEL_USER_NAME"];
                $pass = $aRow["SCH_DEL_USER_PASS"];
                $processId = $aRow["PRO_UID"];
                $taskId = $aRow["TAS_UID"];
                $client = new SoapClient( $defaultEndpoint );
                $params = array ('userid' => $user,'password' => Bootstrap::getPasswordHashType() . ':' . $pass);
                $result = $client->__SoapCall( 'login', array ($params) );
                eprint( " - Logging as user $user............." );
                if ($result->status_code == 0) {
                    eprintln( "OK+", 'green' );
                    $sessionId = $result->message;
                    $newCaseLog = new LogCasesScheduler();
                    $newRouteLog = new LogCasesScheduler();
                    $variables = Array ();
                    $params = array ('sessionId' => $sessionId,'processId' => $processId,'taskId' => $taskId,'variables' => $variables
                    );

                    $paramsLog = array ('PRO_UID' => $processId,'TAS_UID' => $taskId,'SCH_UID' => $sSchedulerUid,'USR_NAME' => $user,'RESULT' => '','EXEC_DATE' => date( 'Y-m-d' ),'EXEC_HOUR' => date( 'H:i:s' ),'WS_CREATE_CASE_STATUS' => '','WS_ROUTE_CASE_STATUS' => ''
                    );

                    //If this Job was was registered to be performed by a plugin
                    if ((isset( $aRow['CASE_SH_PLUGIN_UID'] )) && ($aRow['CASE_SH_PLUGIN_UID'] != "")) {
                        //Check if the plugin is active
                        $pluginParts = explode( "--", $aRow['CASE_SH_PLUGIN_UID'] );
                        if (count( $pluginParts ) == 2) {
                            //***************** Plugins **************************
                            G::LoadClass( 'plugin' );
                            //here we are loading all plugins registered
                            //the singleton has a list of enabled plugins
                            $sSerializedFile = PATH_DATA_SITE . 'plugin.singleton';
                            $oPluginRegistry = & PMPluginRegistry::getSingleton();
                            if (file_exists( $sSerializedFile )) {
                                $oPluginRegistry->unSerializeInstance( file_get_contents( $sSerializedFile ) );
                            }
                            $oPluginRegistry = & PMPluginRegistry::getSingleton();
                            $activePluginsForCaseScheduler = $oPluginRegistry->getCaseSchedulerPlugins();
                            foreach ($activePluginsForCaseScheduler as $key => $caseSchedulerPlugin) {
                                if ((isset( $caseSchedulerPlugin->sNamespace )) && ($caseSchedulerPlugin->sNamespace == $pluginParts[0]) && (isset( $caseSchedulerPlugin->sActionId )) && ($caseSchedulerPlugin->sActionId == $pluginParts[1])) {
                                    $caseSchedulerSelected = $caseSchedulerPlugin;
                                }
                            }
                        }
                    }

                    //If there is a trigger that is registered to do this then transfer control
                    if ((isset( $caseSchedulerSelected )) && (is_object( $caseSchedulerSelected ))) {
                        eprintln( " - Transfering control to a Plugin: " . $caseSchedulerSelected->sNamespace . "/" . $caseSchedulerSelected->sActionId, 'green' );
                        $oData['OBJ_SOAP'] = $client;
                        $oData['SCH_UID'] = $aRow['SCH_UID'];
                        $oData['params'] = $params;
                        $oData['sessionId'] = $sessionId;
                        $oData['userId'] = $user;
                        $paramsLogResultFromPlugin = $oPluginRegistry->executeMethod( $caseSchedulerSelected->sNamespace, $caseSchedulerSelected->sActionExecute, $oData );
                        $paramsLog['WS_CREATE_CASE_STATUS'] = $paramsLogResultFromPlugin['WS_CREATE_CASE_STATUS'];
                        $paramsLog['WS_ROUTE_CASE_STATUS'] = $paramsLogResultFromPlugin['WS_ROUTE_CASE_STATUS'];

                        $paramsLogResult = $paramsLogResultFromPlugin['paramsLogResult'];
                        $paramsRouteLogResult = $paramsLogResultFromPlugin['paramsRouteLogResult'];
                    } else {
                        eprint( " - Creating the new case............." );

                        $paramsAux = $params;
                        $paramsAux["executeTriggers"] = 1;

                        $oPluginRegistry = &PMPluginRegistry::getSingleton();
                        if ($oPluginRegistry->existsTrigger ( PM_SCHEDULER_CREATE_CASE_BEFORE )) {
                            $oPluginRegistry->executeTriggers(PM_SCHEDULER_CREATE_CASE_BEFORE, $paramsAux);
                        }

                        $result = $client->__SoapCall("NewCase", array($paramsAux));

                        if ($oPluginRegistry->existsTrigger ( PM_SCHEDULER_CREATE_CASE_AFTER )) {
                            $oPluginRegistry->executeTriggers(PM_SCHEDULER_CREATE_CASE_AFTER, $result);
                        }

                        if ($result->status_code == 0) {
                            eprintln( "OK+ CASE #{$result->caseNumber} was created!", 'green' );

                            $caseId = $result->caseId;
                            $caseNumber = $result->caseNumber;
                            $log[] = $caseNumber . ' was created!, ProcessID: ' . $aRow['PRO_UID'];
                            $paramsLog['WS_CREATE_CASE_STATUS'] = "Case " . $caseNumber . " " . strip_tags( $result->message );
                            $paramsLogResult = 'SUCCESS';
                            $params = array ('sessionId' => $sessionId,'caseId' => $caseId,'delIndex' => "1");
                            try {
                                $result = $client->__SoapCall( 'RouteCase', array ($params) );
                                eprint(" - Routing the case #$caseNumber..............");
                                if ($result->status_code == 0) {
                                    $paramsLog['WS_ROUTE_CASE_STATUS'] = strip_tags( $result->message );
                                    $retMsg = explode( "Debug", $paramsLog['WS_ROUTE_CASE_STATUS'] );
                                    $retMsg = $retMsg[0];
                                    eprintln( "OK+ $retMsg", 'green' );
                                    $paramsRouteLogResult = 'SUCCESS';
                                } else {
                                    $paramsLog['WS_ROUTE_CASE_STATUS'] = strip_tags( $result->message );
                                    eprintln( "FAILED-> {$paramsLog ['WS_ROUTE_CASE_STATUS']}", 'red' );
                                    $paramsRouteLogResult = 'FAILED';
                                }
                            } catch (Exception $oError) {
                                setExecutionResultMessage('    WITH ERRORS', 'error');
                                $paramsLog['WS_ROUTE_CASE_STATUS'] = strip_tags( $oError->getMessage());
                                eprintln("  '-".strip_tags($oError->getMessage()), 'red');
                                $paramsRouteLogResult = 'FAILED';
                            }
                        } else {
                            $paramsLog['WS_CREATE_CASE_STATUS'] = strip_tags( $result->message );
                            eprintln( "FAILED->{$paramsLog ['WS_CREATE_CASE_STATUS']}", 'red' );
                            $paramsLogResult = 'FAILED';
                        }
                    }
                } else {
                    //Invalid user or bad password
                    eprintln( $result->message, 'red' );
                }
                if ($paramsLogResult == 'SUCCESS' && $paramsRouteLogResult == 'SUCCESS') {
                    $paramsLog['RESULT'] = 'SUCCESS';
                } else {
                    $paramsLog['RESULT'] = 'FAILED';
                }

                $newCaseLog->saveLogParameters( $paramsLog );
                $newCaseLog->save();

                if ($sOption != '4' && $sOption != '5') {
                    $nSchLastRunTime = $sActualTime;
                    $dEstimatedDate = $this->updateNextRun( $sOption, $sValue, $sActualTime, $sDaysPerformTask, $sWeeks, $sStartDay, $sMonths );

                    if ($aRow['SCH_END_DATE'] != '') {
                        if (date( "Y-m-d", strtotime( $dEstimatedDate ) ) > date( "Y-m-d", strtotime( $aRow['SCH_END_DATE'] ) )) {
                            $Fields = $this->Load( $sSchedulerUid );
                            $Fields['SCH_LAST_STATE'] = $aRow['SCH_STATE'];
                            $Fields['SCH_STATE'] = 'PROCESSED';
                            $this->Update( $Fields );
                        }
                    }
                    $nSchTimeNextRun = $dEstimatedDate;
                    $this->updateDate( $sSchedulerUid, $nSchTimeNextRun, $nSchLastRunTime );
                } elseif ($sOption != '5') {
                    $Fields = $this->Load( $sSchedulerUid );
                    $Fields['SCH_LAST_STATE'] = $aRow['SCH_STATE'];
                    $Fields['SCH_LAST_RUN_TIME'] = $Fields['SCH_TIME_NEXT_RUN'];
                    $Fields['SCH_STATE'] = 'PROCESSED';
                    $this->Update( $Fields );
                } else {
                    $nSchLastRunTime = $sActualTime;
                    $Fields = $this->Load( $sSchedulerUid );
                    $Fields['SCH_LAST_RUN_TIME'] = $Fields['SCH_TIME_NEXT_RUN'];

                    //$nSchTimeNextRun = strtotime( $Fields['SCH_TIME_NEXT_RUN'] );
                    $nSchTimeNextRun = $nTime;
                    $nextRun = $Fields['SCH_REPEAT_EVERY'] * 60 * 60;
                    $nSchTimeNextRun += $nextRun;
                    $nSchTimeNextRun = date( "Y-m-d H:i", $nSchTimeNextRun );

                    $this->updateDate( $sSchedulerUid, $nSchTimeNextRun, $nSchLastRunTime );
                }
            }
        }
    }

    public function updateDate ($sSchedulerUid = '', $sSchTimeNextRun = '', $sSchLastRunTime = '')
    {
        $Fields = $this->Load( $sSchedulerUid );
        $Fields['SCH_TIME_NEXT_RUN'] = strtotime( $sSchTimeNextRun );
        $Fields['SCH_LAST_RUN_TIME'] = strtotime( $sSchLastRunTime );
        $this->Update( $Fields );
    }

    public function updateNextRun($sOption, $sValue = "", $sActualTime = "", $sDaysPerformTask = "", $sWeeks = "", $sStartDay = "", $sMonths = "", $currentDate = "", $flagNextRun = true)
    {
        $nActualDate = $currentDate . " " . $sActualTime;
        $dEstimatedDate = '';
        $sWeeks = trim($sWeeks, " |");

        switch ($sOption) {
            case '1':
                switch ($sValue) {
                    case '1':
                        $dEstimatedDate = date( 'Y-m-d  H:i:s', strtotime( "$nActualDate  +1 day" ) );
                        break;
                    case '2':
                        $nDayOfTheWeek = date( 'w', strtotime( $sActualTime ) );
                        $nDayOfTheWeek = ($nDayOfTheWeek == 0) ? 7 : $nDayOfTheWeek;

                        if ($nDayOfTheWeek >= 5) {
                            $dEstimatedDate = date( 'Y-m-d  H:i:s', strtotime( "$nActualDate  +3 day" ) );
                        } else {
                            $dEstimatedDate = date( 'Y-m-d  H:i:s', strtotime( "$nActualDate  +1 day" ) );
                        }
                        break;
                    case '3':
                        $dEstimatedDate = date( 'Y-m-d  H:i:s', strtotime( "$nActualDate + " . $sDaysPerformTask . " day" ) );
                        break;
                }
                break;
            case '2':
                if ($sWeeks != "") {
                    $aDaysWeek = array ('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');

                    $nDayOfTheWeek = (int)(date("w", strtotime($sActualTime)));
                    $nDayOfTheWeek = ($nDayOfTheWeek == 0)? 7 : $nDayOfTheWeek;

                    $arrayWeekdays = explode("|", $sWeeks);
                    $firstDay = (int)($arrayWeekdays[0]);

                    $flagFound = $nDayOfTheWeek < $firstDay || in_array($nDayOfTheWeek, $arrayWeekdays);

                    if ($flagFound) {
                        $typeStatement = "this";
                        $indexDay = (in_array($nDayOfTheWeek, $arrayWeekdays))? $nDayOfTheWeek : $firstDay;

                        if ($flagNextRun) {
                            $index = array_search($nDayOfTheWeek, $arrayWeekdays);

                            if ($index !== false && isset($arrayWeekdays[$index + 1])) {
                                $indexDay = $arrayWeekdays[$index + 1];
                            } else {
                                $typeStatement = "next";
                                $indexDay = $firstDay;
                            }
                        }

                        $indexDay--;

                        $dEstimatedDate = date("Y-m-d", strtotime($nActualDate . " " . $typeStatement . " " . $aDaysWeek[$indexDay])) . " " . date("H:i:s", strtotime($sActualTime));
                    } else {
                        $nEveryDays = $sDaysPerformTask;

                        $typeStatement = ($firstDay >= $nDayOfTheWeek || $nEveryDays == 1)? "next" : "last";
                        $indexDay = $firstDay - 1;

                        if ($nEveryDays == 1) {
                            $dEstimatedDate = date("Y-m-d", strtotime($nActualDate . " " . $typeStatement . " " . $aDaysWeek[$indexDay])) . " " . date("H:i:s", strtotime($sActualTime));
                        } else {
                            $nEveryDays = 1;
                            $nDataTmp = date( 'Y-m-d', strtotime( "$nActualDate + " . $nEveryDays . " Week" ) );
                            $dEstimatedDate = date("Y-m-d", strtotime($nDataTmp . " " . $typeStatement . " " . $aDaysWeek[$indexDay])) . " " . date("H:i:s", strtotime($sActualTime));
                        }
                    }
                }
                break;
            case '3':
                if (strlen( $sMonths ) > 0) {
                    // Must have at least one selected month
                    //  Calculamos para la siguiente ejecucion, acorde a lo seleccionado
                    $aStartDay = explode( '|', $sStartDay );
                    $nYear = date( "Y", strtotime( $sActualTime ) );
                    $nCurrentMonth = date( "m", strtotime( $sActualTime ) );
                    $nCurrentDay = date( "d", strtotime( $sActualTime ) );
                    $aMonths = explode( '|', $sMonths );

                    $nSW = 0;
                    $nNextMonth = 0;
                    foreach ($aMonths as $value) {
                        if ($value > $nCurrentMonth) {
                            $nNextMonth = $value - 1;
                            $nSW = 1;
                            break;
                        }
                    }

                    if ($nSW == 1) {
                        $nExecNextMonth = $nNextMonth;
                    } else {
                        $nExecNextMonth = $aMonths[0] - 1;
                        $nYear ++;
                    }

                    switch ($sValue) {
                        case '1':
                            $nExecNextMonth ++;
                            $nCurrentDay = $aStartDay[1];
                            $dEstimatedDate = date( 'Y-m-d', strtotime( "$nYear-$nExecNextMonth-$nCurrentDay" ) ) . ' ' . date( 'H:i:s', strtotime( $sActualTime ) );
                            break;
                        case '2':
                            $aMontsShort = array ('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
                            $aWeeksShort = array ('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
                            $sNumDayWeek = $aStartDay[1];
                            $sDayWeek = ($aStartDay[2] == 7 ? 0 : $aStartDay[2]);
                            switch ($sNumDayWeek) {
                                case '1':
                                    $sDaysWeekOpt = "+0";
                                    break;
                                case '2':
                                    $sDaysWeekOpt = "+1";
                                    break;
                                case '3':
                                    $sDaysWeekOpt = "+2";
                                    break;
                                case '4':
                                    $sDaysWeekOpt = "+3";
                                    break;
                                case '5':
                                    $sDaysWeekOpt = "-1";
                                    $nExecNextMonth ++;
                                    if ($nExecNextMonth >= 12) {
                                        $nExecNextMonth = 0;
                                        $nYear ++;
                                    }
                                    break;
                            }
                            $dEstimatedDate = date( 'Y-m-d', strtotime( $sDaysWeekOpt . ' week ' . $aWeeksShort[$sDayWeek - 1] . ' ' . $aMontsShort[$nExecNextMonth] . ' ' . $nYear ) ) . ' ' . date( 'H:i:s', strtotime( $sActualTime ) );
                            break;
                    }
                }
                break;
        }
        return $dEstimatedDate;
    }

    public function Exists ($sUid)
    {
        try {
            $oObj = CaseSchedulerPeer::retrieveByPk( $sUid );
            return (is_object( $oObj ) && get_class( $oObj ) == 'CaseScheduler');
        } catch (Exception $oError) {
            throw ($oError);
        }
    }
}

