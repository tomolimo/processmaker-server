<?
/**
 * extJs.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.
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

//  G::LoadSystem('templatePower');


  G::LoadClass('serverConfiguration');
  $oServerConf =& serverConf::getSingleton();

  $oHeadPublisher =& headPublisher::getSingleton();
 
  $extSkin=$oServerConf->getProperty("extSkin");
  if(isset($extSkin[SYS_SKIN])){
    $oHeadPublisher->setExtSkin( $extSkin[SYS_SKIN]); 
  }
  
  if( $oHeadPublisher->extJsInit === true){
    $header = $oHeadPublisher->getExtJsVariablesScript();
    $styles = $oHeadPublisher->getExtJsStylesheets();
    $body   = $oHeadPublisher->getExtJsScripts();
    
    $templateFile = 'extJsInitLoad.html';
  } else {
    $header = $oHeadPublisher->includeExtJs();
    $styles = '';
    $body   = $oHeadPublisher->renderExtJs();
    
    $templateFile = 'extJs.html';
  }
  $template = new TemplatePower( PATH_SKINS . $templateFile );
  $template->prepare();
  $template->assign( 'header', $header );
  $template->assign( 'styles', $styles );
  $template->assign( 'bodyTemplate', $body);
  $content = $template->getOutputContent();  
  
  print $content;
  