<?php
/**
 * processmaker.php
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
 /*
   * Case Tracker Menú
   *
   * @author Everth S. Berrios Morales <everth@colosa.com>
   * 
   */
global $G_TMP_MENU;
global $RBAC;

$G_TMP_MENU->AddIdRawOption('MAP',      'tracker/tracker_ViewMap');
$G_TMP_MENU->AddIdRawOption('DYNADOC',  'tracker/tracker_DynaDocs');
$G_TMP_MENU->AddIdRawOption('HISTORY',  'tracker/tracker_History');
$G_TMP_MENU->AddIdRawOption('MESSAGES',  'tracker/tracker_Messages');

$G_TMP_MENU->Labels = array(  
  G::LoadTranslation('ID_MAP'),
  G::LoadTranslation('ID_DYNADOC'),
  G::LoadTranslation('ID_HISTORY'), 
  G::LoadTranslation('ID_HISTORY_MESSAGES')
);

if ( file_exists ( PATH_CORE . 'menus/plugin.php' ) ) {
	require_once ( PATH_CORE . 'menus/plugin.php' );
}

G::LoadClass('case');
	$oCase = new Cases();
	$per = $oCase->Permisos( $_SESSION['PROCESS']);
	
	$p = explode('-', $per);	

if ($p[0] != 1)
{
  $G_TMP_MENU->DisableOptionId('MAP');
}

if ($p[1] != 1)
{
  $G_TMP_MENU->DisableOptionId('DYNADOC');
}

if ($p[2] != 1)
{
  $G_TMP_MENU->DisableOptionId('HISTORY');
}

if ($p[3] != 1)
{
  $G_TMP_MENU->DisableOptionId('MESSAGES');
}


