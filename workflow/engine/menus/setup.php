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
  
  
  $G_TMP_MENU->AddIdRawOption('CASES_LIST_SETUP', '../cases/casesListSetup', G::LoadTranslation('ID_CASES_LIST_SETUP'), "",'', 'setting');
  $G_TMP_MENU->AddIdRawOption('APPCACHEVIEW_SETUP', '../setup/appCacheViewConf', G::LoadTranslation('ID_APPCACHE_SETUP'), "",'', 'setting');
  
  if ($RBAC->userCanAccess('PM_SETUP') == 1) {
    $G_TMP_MENU->AddIdRawOption('ADDITIONAL_TABLES', '../additionalTables/additionalTablesList', G::LoadTranslation('ID_ADDITIONAL_TABLES'), 'icon-tables.png','', 'tool');
  }
  if ($RBAC->userCanAccess('PM_SETUP_ADVANCE') == 1) {
    $G_TMP_MENU->AddIdRawOption('LANGUAGES', 'languages',   G::LoadTranslation('ID_LANGUAGES'), 'icon-language.png', '', 'setting');
    $G_TMP_MENU->AddIdRawOption('PLUGINS',   'pluginsList', 'Plugins', 'icon-plugins.png', '', 'setting');
    $G_TMP_MENU->AddIdRawOption('UPGRADE',   'upgrade',     G::LoadTranslation('ID_UPGRADE'), 'icon-system-upgrade.png', '', 'setting');
  }
  $G_TMP_MENU->AddIdRawOption('EMAILS',      'emails',      G::LoadTranslation('ID_EMAIL'), 'icon-email-settings.png', '', 'setting');
  $G_TMP_MENU->AddIdRawOption('WEBSERVICES', 'webServices', G::LoadTranslation('ID_WEB_SERVICES'), 'icon-webservices.png', '', 'tool');
  $G_TMP_MENU->AddIdRawOption('SKINS',       'skinsList', G::LoadTranslation('ID_SKINS'), 'icon-skins.png', '', 'setting');
  
  $G_TMP_MENU->AddIdRawOption('LOGO',        'uplogo', G::LoadTranslation('ID_LOGO'), 'icon-pmlogo.png', '', 'setting');

  $G_TMP_MENU->AddIdRawOption('CLEAR_CACHE', '', G::LoadTranslation('ID_CLEAR_CACHE'), 'icon-rebuild-clean.png', "msgBox('".G::LoadTranslation('ID_CLEAR_CACHE_CONFIRM1')."', 'confirm', function(){ location.href='clearCompiled';})", '' );

  $G_TMP_MENU->AddIdRawOption('CALENDAR',        'calendarList', G::LoadTranslation('ID_CALENDAR'), 'icon-calendar.png', '', 'setting' );
  $G_TMP_MENU->AddIdRawOption('LOG_CASE_SCHEDULER', '../cases/cases_Scheduler_Log', G::LoadTranslation('ID_LOG_CASE_SCHEDULER'), "icon-logs-list.png",'', 'tool');
  
  $G_TMP_MENU->AddIdRawOption('PROCESS_CATEGORY', '../processCategory/processCategoryList', G::LoadTranslation('ID_PROCESS_CATEGORY'), "rules.png",'', 'setting');    
  
  /*$G_TMP_MENU->AddIdRawOption('WORKSPACE',          'workspaceList', G::LoadTranslation('ID_WORKSPACES') );*/
  //$G_TMP_MENU->AddIdRawOption('SELFSERVICE',    'selfService', G::LoadTranslation('ID_SELF_SERVICE') );
  //$G_TMP_MENU->AddIdRawOption('TRANSLATION', 'tools/translations', 'Translations');
  //$G_TMP_MENU->AddIdRawOption('UPDATE_ALL',  'tools/updateTranslation', 'Update');


