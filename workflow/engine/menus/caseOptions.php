<?php
/**
 * caseOptions.php
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
global $sStatus;

if ((($sStatus == 'DRAFT') || ($sStatus == 'TO_DO')) && ($_SESSION['TASK'] != -1)) {
  if (isset($_SESSION['bNoShowSteps'])) {
    unset($_SESSION['bNoShowSteps']);
  } else {
    $G_TMP_MENU->AddIdOption('STEPS' , G::LoadTranslation('ID_STEPS')      , 'javascript:showSteps();'      , 'absolute');
    $G_TMP_MENU->AddIdOption('INFO'  , G::LoadTranslation('ID_INFORMATION'), 'javascript:showInformation();', 'absolute');
  }
  $G_TMP_MENU->AddIdOption('ACTIONS' , G::LoadTranslation('ID_ACTIONS')    , 'javascript:showActions();'    , 'absolute');
} else {
  $G_TMP_MENU->AddIdOption('INFO'  , G::LoadTranslation('ID_INFORMATION'), 'javascript:showInformation();', 'absolute');
}
$G_TMP_MENU->AddIdOption('NOTES'  , G::LoadTranslation('ID_NOTES'), 'javascript:showNotes();', 'absolute');










