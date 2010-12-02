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

  $RBAC->requirePermissions('PM_SETUP');

  $oHeadPublisher =& headPublisher::getSingleton();

  global $G_TMP_MENU;
  $oMenu = new Menu();
  $oMenu->load('setup');
  $items = Array();

  $menuTypes = array_unique($oMenu->Types);
  foreach($menuTypes as $i=>$v){
    if( $v == 'admToolsContent'){
      unset($menuTypes[$i]);
      break;
    }
  }
  //sort($menuTypes);

  $tabItems = Array();
  $i=0;
  foreach( $menuTypes as $menuType ){
    $tabItems[$i]->id    = $menuType;
    $LABEL_TRANSLATION = G::LoadTranslation("ID_".strtoupper($menuType));
    
    if( substr($LABEL_TRANSLATION,0,2) !== '**' ){
      $title = $LABEL_TRANSLATION;
    } else {
      $title = str_replace('_', ' ', ucwords($menuType));
    }
    $tabItems[$i]->title = $title;
    $i++;
  }

  $oHeadPublisher->addExtJsScript('setup/main', false);    //adding a javascript file .js
  $oHeadPublisher->addContent('setup/main'); //adding a html file  .html.
  $oHeadPublisher->assign('tabItems', $tabItems);

  G::RenderPage('publish', 'extJs');
  // this patch enables the load of the plugin list panel inside de main admin panel iframe
  if (isset($_GET['action'])&&$_GET['action']=='pluginsList'){
    print "
    <script>
    document.getElementById('setup-frame').src = 'pluginsList';
    </script>
    ";
  }