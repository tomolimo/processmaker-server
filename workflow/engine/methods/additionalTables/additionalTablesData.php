<?php
/**
 * additionalTablesData.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
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

global $RBAC;
$RBAC->requirePermissions('PM_SETUP_ADVANCE');
$G_PUBLISH = new Publisher;

$oHeadPublisher =& headPublisher::getSingleton();

require_once 'classes/model/AdditionalTables.php';
$oAdditionalTables = new AdditionalTables();
$oAdditionalTables->createXmlList($_GET['sUID']);
$arrTable = $oAdditionalTables->load($_GET['sUID'],true);
$fields = $arrTable['FIELDS'];

//print_r($arrTable);
//die;

$arrNames = Array();
$arrDescrip = Array();
$c = 0;
$xPKF = "";
foreach ($fields as $field){
	$c++;
	$arrNames[] = $field['FLD_NAME'];
	$arrDescrip[] = $field['FLD_DESCRIPTION'];
	if ($field['FLD_KEY']=='1'){
		$xPKF = $field['FLD_NAME'];
	}
}


//$oHeadPublisher->usingExtJs('ux/Ext.ux.fileUploadField');
$oHeadPublisher->addExtJsScript('additionalTables/additionalTablesData', false);    //adding a javascript file .js
$oHeadPublisher->addContent('additionalTables/additionalTablesData'); //adding a html file  .html.

$labels = G::getTranslations(Array('ID_EXPORT','ID_IMPORT','ID_EDIT','ID_DELETE', 'ID_DATA',
  'ID_NEW_ADD_TABLE','ID_DESCRIPTION','ID_NAME','ID_CONFIRM','ID_ADDITIONAL_TABLES','ID_SELECT_FIRST_PM_TABLE_ROW',
  'ID_CONFIRM_DELETE_PM_TABLE','ID_ADD_ROW','ID_BACK','ID_SELECT_FIRST_ROW','ID_MSG_CONFIRM_DELETE_ROW'));

$table_uid = Array();
$table_uid['UID'] = $_GET['sUID'];
$table_uid['COUNTER'] = $c;
$table_uid['TABLE_NAME'] = $arrTable['ADD_TAB_NAME'];
$table_uid['PKF'] = $xPKF;


$oHeadPublisher->assign('TRANSLATIONS', $labels);
$oHeadPublisher->assign('TABLES', $table_uid);
$oHeadPublisher->assign('NAMES', $arrNames);
$oHeadPublisher->assign('VALUES', $arrDescrip);

G::RenderPage('publish', 'extJs');
//global $RBAC;
//if ($RBAC->userCanAccess('PM_SETUP') != 1) {
//  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
//	G::header('location: ../login/login');
//	die;
//}
//
//require_once 'classes/model/AdditionalTables.php';
//$oAdditionalTables = new AdditionalTables();
//$oAdditionalTables->createXmlList($_GET['sUID']);
//
//$G_MAIN_MENU            = 'processmaker';
////$G_SUB_MENU             = 'setup';
//$G_ID_MENU_SELECTED     = 'SETUP';
////$G_ID_SUB_MENU_SELECTED = 'ADDITIONAL_TABLES';
//
//$ocaux = $oAdditionalTables->getDataCriteria($_GET['sUID']);
//
////var_dump($ocaux);
//
//$rs = AdditionalTablesPeer::DoSelectRs ($ocaux);
//$rs->setFetchmode (ResultSet::FETCHMODE_ASSOC);
//
//$fieldN = Array('DUMMY'=>'char');
//
//$rows = Array();
//while($rs->next()){
//	$rows[] = $rs->getRow();
//}
//
//$rows = array_merge(Array($fieldN), $rows);
//
//print_r($rows);
////die();
//
//global $_DBArray;
//$_DBArray['virtual_pmtable']   = $rows;
//$_SESSION['_DBArray'] = $_DBArray;
//G::LoadClass('ArrayPeer');
//$oCriteria = new Criteria('dbarray');
//$oCriteria->setDBArrayTable('virtual_pmtable');
//
//
//$G_PUBLISH = new Publisher;
////$G_PUBLISH->AddContent('xmlform', 'xmlform', 'additionalTables/additionalTablesTitle', '', $oAdditionalTables->load($_GET['sUID']));
//
//$G_PUBLISH->AddContent('propeltable', 'paged-table', 'xmlLists/' . $_GET['sUID'], $oCriteria, array('ADD_TAB_UID' => $_GET['sUID']), '', '', '', PATH_DYNAFORM);
//G::RenderPage('publishBlank', 'blank');