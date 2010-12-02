<?php

/**
 * cases_Scheduler_Log.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2010 Colosa Inc.23
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 *
 */
if (($RBAC_Response=$RBAC->userCanAccess("PM_LOGIN"))!=1) return $RBAC_Response;
global $RBAC;

if (!class_exists('LogCasesSchedulerPeer')){
require ("classes/model/LogCasesSchedulerPeer.php");
}

$oLogCaseScheduler = new LogCasesScheduler();
$aRows = $oLogCaseScheduler->getAll();

$fieldNames = Array(
        'LOG_CASE_UID' => 'char',
	'PRO_UID' => 'char',
	'TAS_UID' => 'char',
	'USR_NAME' => 'char',
	'EXEC_DATE' => 'char',
	'EXEC_HOUR' => 'char',
	'RESULT' => 'char',
	'SCH_UID' => 'char',
	'WS_CREATE_CASE_STATUS' => 'char',
	'WS_ROUTE_CASE_STATUS' => 'char',
);

$aRows = array_merge(Array($fieldNames), $aRows);


$_DBArray['log_cases_scheduler']   = $aRows;
$_SESSION['_DBArray'] = $_DBArray;

$oCriteria = new Criteria('dbarray');
$oCriteria->setDBArrayTable('log_cases_scheduler');
//krumo($oCriteria);
//$G_MAIN_MENU            = 'processmaker';
//$G_SUB_MENU             = 'cases';
//
//$G_ID_MENU_SELECTED     = 'CASES';
//$G_ID_SUB_MENU_SELECTED = 'CASES_SCHEDULER_LOG';

$G_PUBLISH = new Publisher;
$G_PUBLISH->ROWS_PER_PAGE = 10;
$G_PUBLISH->AddContent('propeltable', 'paged-table', 'cases/cases_Scheduler_Log', $oCriteria);
$G_PUBLISH->oPropelTable->rowsPerPage = 10;
G::RenderPage('publishBlank', 'blank');

?>
