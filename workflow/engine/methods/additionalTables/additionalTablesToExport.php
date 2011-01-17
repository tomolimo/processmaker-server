<?php
/**
 * additionalTablesToExport.php
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
$oHeadPublisher->addExtJsScript('additionalTables/additionalTablesExport', false);    //adding a javascript file .js
$oHeadPublisher->addContent('additionalTables/additionalTablesExport'); //adding a html file  .html.

$labels = G::getTranslations(Array('ID_EXPORT','ID_IMPORT','ID_EDIT','ID_DELETE', 'ID_DATA',
  'ID_NEW_ADD_TABLE','ID_DESCRIPTION','ID_NAME','ID_CONFIRM','ID_ADDITIONAL_TABLES','ID_SELECT_FIRST_PM_TABLE_ROW',
  'ID_CONFIRM_DELETE_PM_TABLE', 'ID_CANCEL','ID_CLOSE','ID_ACTION_EXPORT','ID_ACTION_IGNORE','ID_TITLE_EXPORT_RESULT','ID_TITLE_EXPORT_TOOL'));

$toSend = Array();
$toSend['UID_LIST'] = $_GET["sUID"];

$oHeadPublisher->assign('TRANSLATIONS', $labels);
$oHeadPublisher->assign('EXPORT_TABLES', $toSend);
G::RenderPage('publish', 'extJs');
?>