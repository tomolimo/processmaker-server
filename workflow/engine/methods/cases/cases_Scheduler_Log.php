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

$G_PUBLISH = new Publisher;

$oHeadPublisher =& headPublisher::getSingleton();

//$oHeadPublisher->usingExtJs('ux/Ext.ux.fileUploadField');
$oHeadPublisher->addExtJsScript('cases/casesSchedulerLog', false);    //adding a javascript file .js
$oHeadPublisher->addContent('cases/casesSchedulerLog'); //adding a html file  .html.

$labels = G::getTranslations(Array('ID_DATE_LABEL','ID_TIME_LABEL','ID_USER','ID_RESULT', 'ID_CREATED_CASE_STATUS',
  'ID_ROUTED_CASE_STATUS','ID_CLOSE','ID_LOG_CASE_SCHEDULER','ID_VIEW','ID_TITLE_LOG_DETAIL'));

$oHeadPublisher->assign('TRANSLATIONS', $labels);
G::RenderPage('publish', 'extJs');

?>
