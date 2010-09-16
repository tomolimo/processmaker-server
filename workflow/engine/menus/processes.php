<?php
/**
 * processes.php
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
$G_TMP_MENU_ALIGN = "left";

$G_TMP_MENU->AddIdRawOption('DYNAFORMS',    '', G::LoadTranslation('ID_DYNAFORMS'),"/images/dynaforms.gif",'Pm.data.render.buildingBlocks.injector(\'dynaforms\'); return false;');
$G_TMP_MENU->AddIdRawOption('INPUTDOCS',    '', G::LoadTranslation('ID_REQUEST_DOCUMENTS'),"/images/inputdocument.gif",'Pm.data.render.buildingBlocks.injector(\'inputs\'); return false;');
$G_TMP_MENU->AddIdRawOption('OUTPUTDOCS',   '', G::LoadTranslation('ID_OUTPUT_DOCUMENTS'),"/images/outputdocument.gif",'Pm.data.render.buildingBlocks.injector(\'outputs\'); return false;');
$G_TMP_MENU->AddIdRawOption('TRIGGERS',     '', G::LoadTranslation('ID_TRIGGERS'),"/images/trigger.gif",'Pm.data.render.buildingBlocks.injector(\'triggers\'); return false;');

//$G_TMP_MENU->AddIdRawOption('MESSAGES',     '', G::LoadTranslation('ID_MESSAGES'),"/images/mail.gif",'Pm.data.render.buildingBlocks.injector(\'messages\'); return false;');
G::LoadClass('reportTables');
if (ReportTables::tableExist()) {
  $G_TMP_MENU->AddIdRawOption('REPORT_TABLES', '', G::LoadTranslation('ID_REPORT_TABLES'),"/images/report_tables.gif",'Pm.data.render.buildingBlocks.injector(\'reportTables\'); return false;');
}
$G_TMP_MENU->AddIdRawOption('DB_CONNECTIONS', '', G::LoadTranslation('ID_DB_CONNECTIONS'),"/images/iconoenlace.png",'showDbConnectionsList(Pm.options.uid); return false;');
$G_TMP_MENU->AddIdRawOption('CASE_SCHEDULER', '', G::LoadTranslation('ID_CASE_SCHEDULER'), "/images/scheduler.png", 'showCaseSchedulerList(Pm.options.uid); return false;');


?>