<?php
/**
 * additionalTablesList.php
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

//$oHeadPublisher->usingExtJs('ux/Ext.ux.fileUploadField');
$oHeadPublisher->addExtJsScript('additionalTables/additionalTablesList', false);    //adding a javascript file .js
$oHeadPublisher->addContent('additionalTables/additionalTablesList'); //adding a html file  .html.

$labels = G::getTranslations(Array('ID_EXPORT','ID_IMPORT','ID_EDIT','ID_DELETE', 'ID_DATA',
  'ID_NEW_ADD_TABLE','ID_DESCRIPTION','ID_NAME','ID_CONFIRM','ID_ADDITIONAL_TABLES','ID_SELECT_FIRST_PM_TABLE_ROW',
  'ID_CONFIRM_DELETE_PM_TABLE'));

$oHeadPublisher->assign('TRANSLATIONS', $labels);
G::RenderPage('publish', 'extJs');

/*global $RBAC;
if ($RBAC->userCanAccess('PM_SETUP') != 1) {
  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
	G::header('location: ../login/login');
	die;
}

require_once 'classes/model/AdditionalTables.php';
$oCriteria = new Criteria('workflow');
$oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_UID);
$oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_NAME);
$oCriteria->addSelectColumn(AdditionalTablesPeer::ADD_TAB_DESCRIPTION);
$oCriteria->add(AdditionalTablesPeer::ADD_TAB_UID, '', Criteria::NOT_EQUAL);

$G_MAIN_MENU            = 'processmaker';
//$G_SUB_MENU             = 'admin';
$G_ID_MENU_SELECTED     = 'SETUP';
//$G_ID_SUB_MENU_SELECTED = 'ADMIN';

$G_PUBLISH = new Publisher;
$G_PUBLISH->AddContent('propeltable', 'paged-table', 'additionalTables/additionalTablesList', $oCriteria, '', '');
G::RenderPage('publishBlank', 'blank');*/