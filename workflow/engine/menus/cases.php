<?php
/**
 * cases.php
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
global $G_TMP_MENU;
$G_TMP_MENU->AddIdRawOption('CASES_START_PAGE',         'casesStartPage',             G::LoadTranslation('ID_CASES_START_PAGE'),   'pm.gif');
$G_TMP_MENU->AddIdRawOption('FOLDERS',           '',                           G::LoadTranslation('ID_CASES_MENU_FOLDERS'), '',                   '', 'blockHeader');
$G_TMP_MENU->AddIdRawOption('CASES_INBOX',       'casesListExtJs?action=todo',  G::LoadTranslation('ID_INBOX'),              'icon-cases-inbox.png' );
$G_TMP_MENU->AddIdRawOption('CASES_DRAFT',       'casesListExtJs?action=draft', G::LoadTranslation('ID_DRAFT'),              'mail-mark-task.png'   );
$G_TMP_MENU->AddIdRawOption('CASES_SENT',        'cases_List?l=sent',          G::LoadTranslation('ID_SENT'),               'icon-cases-outbox.png');
$G_TMP_MENU->AddIdRawOption('CASES_SELFSERVICE', 'cases_List?l=selfservice',   G::LoadTranslation('ID_UNASSIGNED'),         'rotate_cw.png'        );
$G_TMP_MENU->AddIdRawOption('CASES_PAUSED',      'cases_List?l=paused',        G::LoadTranslation('ID_PAUSED'),             'mail-queue.png'       );
$G_TMP_MENU->AddIdRawOption('CASES_COMPLETED',   'cases_List?l=completed',     G::LoadTranslation('ID_COMPLETED'),          'file-archiver.png'    );
$G_TMP_MENU->AddIdRawOption('CASES_CANCELLED',   'cases_List?l=cancelled',     G::LoadTranslation('ID_CANCELLED'),          'edit-clear-list.png'  );
$G_TMP_MENU->AddIdRawOption('CASES_FOLDERS',     '../appFolder/appFolderList', G::LoadTranslation('ID_FOLDERS'),            'folderV2.gif'         );


if($RBAC->userCanAccess('PM_ALLCASES') == 1) {
  //$G_TMP_MENU->AddIdRawOption('CASES_GRAL', 'cases_List?l=gral', G::LoadTranslation('ID_GENERAL'));
}

$G_TMP_MENU->AddIdRawOption('SEARCHS',              '',                     G::LoadTranslation('ID_CASES_MENU_SEARCH'), '', '', 'blockHeader');
$G_TMP_MENU->AddIdRawOption('CASES_ADVANCEDSEARCH', 'cases_advancedSearch', G::LoadTranslation('ID_ADVANCEDSEARCH'),    'system-search.png');


$G_TMP_MENU->AddIdRawOption('ADMIN', '', G::LoadTranslation('ID_CASES_MENU_ADMIN'), '', '', 'blockHeader');
if($RBAC->userCanAccess('PM_SUPERVISOR') == 1) {
  $G_TMP_MENU->AddIdRawOption('CASES_TO_REVISE', 'cases_List?l=to_revise', G::LoadTranslation('ID_TO_REVISE'), 'document-review.png');
  //  $G_TMP_MENU->AddIdRawOption('CASES_SCHEDULER', 'cases_Scheduler_List', G::LoadTranslation('ID_SCHEDULER_LIST'));
  //  $G_TMP_MENU->AddIdRawOption('CASES_SCHEDULER_LOG', 'cases_Scheduler_Log', G::LoadTranslation('ID_SCHEDULER_LOG'));
}

if ($RBAC->userCanAccess('PM_REASSIGNCASE') == 1) {
  $G_TMP_MENU->AddIdRawOption('CASES_TO_REASSIGN', 'cases_List?l=to_reassign', G::LoadTranslation('ID_TO_REASSIGN'), 'reassing.png');
}



