<?php
/**
 * main.php Cases List main processor
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

  $oHeadPublisher =& headPublisher::getSingleton();
  //$oHeadPublisher->setExtSkin( 'xtheme-gray');   
  $oHeadPublisher->usingExtJs('ux/XmlTreeLoader');
  
  $oHeadPublisher->addExtJsScript('cases/main', true );    //adding a javascript file .js
  $oHeadPublisher->addContent( 'cases/main'); //adding a html file  .html.

  G::RenderPage('publish', 'extJs');
  
die;
global $RBAC;
G::LoadClass('case');
G::LoadClass('configuration');

global $G_TMP_MENU;
$oMenu = new Menu();
$oMenu->load('cases');

G::LoadSystem('headPublisher');
$oHeadPublisher = & headPublisher::getSingleton();
$oHeadPublisher->addMaborakFile(PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'json/core/json.js');
$oHeadPublisher->addMaborakFile(PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'common/core/common.js');
$oHeadPublisher->addMaborakFile(PATH_GULLIVER_HOME . 'js' . PATH_SEP . 'common/core/effects.js');

//get the last case list viewed by the user.
$sUIDUserLogged = $_SESSION['USER_LOGGED'];
$conf = new Configurations();
if( ! isset($_GET['l']) ) {
  $confCasesList = $conf->loadObject('ProcessMaker', 'cases_List', '', $sUIDUserLogged, '');
  if( is_array($confCasesList) ) {
    $sTypeList = $confCasesList['sTypeList'];
  } else {
    $sTypeList = 'to_do';
  }
} else {
  $sTypeList = $_GET['l'];
  $confCasesList = array (
    
    'sTypeList' => $sTypeList 
  );
  $conf->saveObject($confCasesList, 'ProcessMaker', 'cases_List', '', $sUIDUserLogged, '');
}

$oCases = new Cases();
$aTypes = Array (
  'to_do', 
  'draft', 
  'cancelled', 
  'my_started', 
  'paused', 
  'sent' 
);
//  $aCount = $oCases->getAllConditionCasesCount($aTypes);
$aCount = array (
  'to_do' => 0, 
  'draft' => 0, 
  'sent' => 0, 
  'my_started' => 0, 
  'paused' => 0, 
  'cancelled' => 0 
);
$menum = Array ();
$_CASES_MENU_BLOCK = Array ();

foreach( $oMenu->Options as $i => $option ) {
  if( $oMenu->Types[$i] == 'blockHeader' ) {
    $CurrentBlockID = $oMenu->Id[$i];
    $menum[$CurrentBlockID]['blockTitle'] = $oMenu->Labels[$i];
  } 
  else {
    $menum[$CurrentBlockID]['blockItems'][$oMenu->Id[$i]] = Array (
      'label' => $oMenu->Labels[$i], 
      'link' => $oMenu->Options[$i], 
      'icon' => (isset($oMenu->Icons[$i]) && $oMenu->Icons[$i] != '') ? $oMenu->Icons[$i] : 'kcmdf.png' 
    );
    
   
    $notifier = "";
    if( isset($notifier) ) {
      $menum[$CurrentBlockID]['blockItems'][$oMenu->Id[$i]]['notifier'] = $notifier;
    }
  }
}

G::LoadSystem('templatePower');
$tpl = new TemplatePower(PATH_TPL . "cases/main_init.html");
$tpl->prepare();
$tpl->assign('SYS_SKIN', SYS_SKIN);
$tpl->assign('ICON_SIZE', 18);

if( isset($_SESSION['cases_url']) ) {
  $cases_url = $_SESSION['cases_url'] . "&content=inner";
  unset($_SESSION['cases_url']);
} 
else {
  $cases_url = "";
}

$tpl->assign('cases_url', $cases_url);

foreach( $menum as $menu => $aMenuBlock ) {
  //$tpl->( 'menu' );
  if( isset($aMenuBlock['blockItems']) && sizeof($aMenuBlock['blockItems']) > 0 ) {
    $tpl->newBlock('menu');
    $tpl->assign('blockTitle', $aMenuBlock['blockTitle']);
    
    foreach( $aMenuBlock['blockItems'] as $id => $aMenu ) {
      $tpl->newBlock('blockItem');
      $tpl->assign('id', $id);
      $tpl->assign('icon', isset($aMenu['icon']) ? $aMenu['icon'] : 'kcmdf.png');
      $tpl->assign('link', isset($aMenu['link']) ? $aMenu['link'] : '');
      $tpl->assign('label', $aMenu['label']);
      $tpl->assign('notifier', isset($aMenu['notifier']) ? $aMenu['notifier'] : '');
      
      $tpl->newBlock('blockItemSumary');
      $tpl->assign('sumary', 'something');
      $tpl->assign('id', $id);
    }
  }
}

$_SESSION['CASES_MAIN_LOADED'] = true;
$G_PUBLISH = new Publisher();
$G_PUBLISH->AddContent('template', '', '', '', $tpl);
G::RenderPage('publish', 'raw');






