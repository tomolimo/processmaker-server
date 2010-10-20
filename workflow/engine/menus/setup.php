<?php
/**
 * setup.php
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
global $G_TMP_MENU;
global $RBAC;
  
//settings options
$G_TMP_MENU->AddIdRawOption('LOGO', 'uplogo', G::LoadTranslation('ID_LOGO'), 'icon-pmlogo.png', '', 'settings');
$G_TMP_MENU->AddIdRawOption('EMAILS','emails', G::LoadTranslation('ID_EMAIL'), 'icon-email-settings.png', '', 'settings');
$G_TMP_MENU->AddIdRawOption('CALENDAR', 'calendarList', G::LoadTranslation('ID_CALENDAR'), 'icon-calendar.png', '', 'settings' );
if ($RBAC->userCanAccess('PM_SETUP_ADVANCE') == 1)  
  $G_TMP_MENU->AddIdRawOption('CASES_LIST_SETUP', '../cases/casesListSetup', G::LoadTranslation('ID_CASES_LIST_SETUP'), "",'', 'settings');
$G_TMP_MENU->AddIdRawOption('PROCESS_CATEGORY', '../processCategory/processCategoryList', G::LoadTranslation('ID_PROCESS_CATEGORY'), "rules.png",'', 'settings');  
if ($RBAC->userCanAccess('PM_SETUP_ADVANCE') == 1)
  $G_TMP_MENU->AddIdRawOption('LANGUAGES', 'languages',   G::LoadTranslation('ID_LANGUAGES'), 'icon-language.png', '', 'settings');  
$G_TMP_MENU->AddIdRawOption('SKINS', 'skinsList', G::LoadTranslation('ID_SKINS'), 'icon-skins.png', '', 'settings');
$G_TMP_MENU->AddIdRawOption('HEARTBEAT', 'processHeartBeatConfig', G::LoadTranslation('ID_HEARTBEAT_CONFIG'), "heartBeat.jpg",'', 'settings');
$G_TMP_MENU->AddIdRawOption('ENVIRONMENT_SETTINGS', 'environmentSettings', G::LoadTranslation('ID_ENVIRONMENT_SETTINGS'), "",'', 'settings');
if ($RBAC->userCanAccess('PM_SETUP_ADVANCE') == 1)
  $G_TMP_MENU->AddIdRawOption('APPCACHEVIEW_SETUP', '../setup/appCacheViewConf', G::LoadTranslation('ID_APPCACHE_SETUP'), "",'', 'settings');
$G_TMP_MENU->AddIdRawOption('CLEAR_CACHE', 'clearCompiled', G::LoadTranslation('ID_CLEAR_CACHE'), 'icon-rebuild-clean.png', "", 'settings' );
if ($RBAC->userCanAccess('PM_SETUP_ADVANCE') == 1)
  $G_TMP_MENU->AddIdRawOption('UPGRADE', 'upgrade',     G::LoadTranslation('ID_UPGRADE'), 'icon-system-upgrade.png', '', 'settings');

//settings options
if ($RBAC->userCanAccess('PM_SETUP') == 1)
  $G_TMP_MENU->AddIdRawOption('ADDITIONAL_TABLES', '../additionalTables/additionalTablesList', G::LoadTranslation('ID_ADDITIONAL_TABLES'), 'icon-tables.png','', 'tools');
if ($RBAC->userCanAccess('PM_SETUP_ADVANCE') == 1)
  $G_TMP_MENU->AddIdRawOption('PLUGINS',   'pluginsList', 'Plugins', 'icon-plugins.png', '', 'tools');
$G_TMP_MENU->AddIdRawOption('WEBSERVICES', 'webServices', G::LoadTranslation('ID_WEB_SERVICES'), 'icon-webservices.png', '', 'tools');
$G_TMP_MENU->AddIdRawOption('LOG_CASE_SCHEDULER', '../cases/cases_Scheduler_Log', G::LoadTranslation('ID_LOG_CASE_SCHEDULER'), "icon-logs-list.png",'', 'tools');


