<?
/**
 * wf5.php
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

  G::verifyPath ( '/tmp/smarty/c', true );
  G::verifyPath ( '/tmp/smarty/cache', true );
  // put full path to Smarty.class.php
  require_once(PATH_THIRDPARTY . 'smarty/libs/Smarty.class.php');


$smarty = new Smarty();

$smarty->template_dir = PATH_SKINS;
$smarty->compile_dir = '/tmp/smarty/c'; //'/web/www.domain.com/smarty/templates_c';
$smarty->cache_dir   = '/tmp/smarty/cache'; //web/www.domain.com/smarty/cache';
$smarty->config_dir = PATH_THIRDPARTY . 'smarty/configs';
$smarty->caching      = false;

$oHeadPublisher =& headPublisher::getSingleton();
if (isset($oHeadPublisher)) $header = $oHeadPublisher->printHeader();
$smarty->assign('header', $header );
$smarty->display('index.html');

?>