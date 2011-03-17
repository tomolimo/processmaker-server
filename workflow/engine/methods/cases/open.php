<?php
/**
 * open.php Open Case main processor
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
 
 /**
  * @author Erik Amaru Ortiz <erik@colosa.com>
  * @date Jan 3th, 2010
  */

  $oHeadPublisher =& headPublisher::getSingleton(); 
  $oHeadPublisher->usingExtJs('ux/miframe');
  $oHeadPublisher->addExtJsScript('cases/open', false);
  //
  $uri = '';
  foreach($_GET as $k=>$v)
    $uri .= ($uri == '')? "$k=$v": "&$k=$v";

  G::LoadClass("configuration");
  G::LoadClass("case");
  $oCase = new Cases();
  $appNum = '';
  if( isset($_GET['APP_UID']) && isset($_GET['DEL_INDEX'])) {
    $case = $oCase->loadCase($_GET['APP_UID'], $_GET['DEL_INDEX']);
    $appNum = $case['APP_TITLE'];
  }
  $conf = new Configurations;

  if (!isset($_GET['to_revise'])){
    $script = 'cases_Open?';
  } else {
    $script = 'cases_OpenToRevise?';
    $delIndex = $_GET['DEL_INDEX'];
    $appUid   = $_GET['APP_UID'];
    $oHeadPublisher->assign( 'treeToReviseTitle',   G::loadtranslation('ID_STEP_LIST')); //translations
//  $oHeadPublisher->assign( 'TRANSLATIONS',   $TRANSLATIONS); //translations
    $casesPanelUrl = 'casesToReviseTreeContent?APP_UID='.$appUid.'&DEL_INDEX='.$delIndex;
    $oHeadPublisher->assign( 'casesPanelUrl',   $casesPanelUrl); //translations
    echo "<div id='toReviseTree'></div>";
  }

  $oHeadPublisher->assign('uri', $script . $uri);
  $oHeadPublisher->assign('_APP_NUM', $appNum);
  $oHeadPublisher->assign('_ENV_CURRENT_DATE', $conf->getSystemDate(date('Y-m-d')));
  G::RenderPage('publish', 'extJs');


