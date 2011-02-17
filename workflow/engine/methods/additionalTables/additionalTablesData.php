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

G::LoadClass('configuration');
$c = new Configurations();
$configPage = $c->getConfiguration('additionalTablesData', 'pageSize','',$_SESSION['USER_LOGGED']);
$Config['pageSize'] = isset($configPage['pageSize']) ? $configPage['pageSize'] : 20;

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

$table_uid = Array();
$table_uid['UID'] = $_GET['sUID'];
$table_uid['COUNTER'] = $c;
$table_uid['TABLE_NAME'] = $arrTable['ADD_TAB_NAME'];
$table_uid['PKF'] = $xPKF;


$oHeadPublisher->assign('TABLES', $table_uid);
$oHeadPublisher->assign('NAMES', $arrNames);
$oHeadPublisher->assign('VALUES', $arrDescrip);
$oHeadPublisher->assign('CONFIG', $Config);

G::RenderPage('publish', 'extJs');