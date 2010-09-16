<?
/**
 * blank.php
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

  G::verifyPath ( PATH_SMARTY_C,     true );
  G::verifyPath ( PATH_SMARTY_CACHE, true );

  // put full path to Smarty.class.php
  require_once(PATH_THIRDPARTY . 'smarty/libs/Smarty.class.php');


$smarty = new Smarty();

$smarty->template_dir = PATH_SKINS;
$smarty->compile_dir  = PATH_SMARTY_C;
$smarty->cache_dir    = PATH_SMARTY_CACHE;
$smarty->config_dir   = PATH_THIRDPARTY . 'smarty/configs';

$oHeadPublisher =& headPublisher::getSingleton();
if (isset($oHeadPublisher)) $header = $oHeadPublisher->printHeader();
$smarty->assign('username', (isset($_SESSION['USR_USERNAME']) ? '(' . $_SESSION['USR_USERNAME'] . ' ' . G::LoadTranslation('ID_IN') . ' ' . SYS_SYS . ')' : '') );
$smarty->assign('header', $header );
//$smarty->assign('tpl_menu', PATH_TEMPLATE . 'menu.html' );
//$smarty->assign('tpl_submenu', PATH_TEMPLATE . 'submenu.html' );
$smarty->display('blank.html');
?>