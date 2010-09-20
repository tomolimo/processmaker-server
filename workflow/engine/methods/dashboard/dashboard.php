<?php
/**
 * dashboard.php
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

if (($RBAC_Response=$RBAC->userCanAccess('PM_LOGIN'))!=1) return $RBAC_Response;

global $RBAC;

$access = $RBAC->userCanAccess('PM_USERS');
if( $access != 1 ){
  switch ($access)
  {
  	case -1:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	case -2:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	default:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;  	
  }
}  

try {
  $G_MAIN_MENU        = 'processmaker';
  $G_SUB_MENU         = 'dashboard';
  $G_ID_MENU_SELECTED = 'DASHBOARD';

  //Load dashboards class
  G::LoadClass('dashboards');
  $oDashboards = new Dashboards();

  //Show dashboards
  $G_PUBLISH   = new Publisher;
  $G_PUBLISH->AddContent('smarty', 'dashboard/frontend', '', '', array('ID_NEW' => G::LoadTranslation('ID_NEW')));
  $oHeadPublisher =& headPublisher::getSingleton();
  $oHeadPublisher->addScriptFile('/jscore/dashboard/core/dashboard.js');
  $oHeadPublisher->addInstanceModule('leimnud', 'dashboard');
  $oHeadPublisher->addScriptCode('leimnud.event.add(window,"load",function(){window.Da=new leimnud.module.dashboard();Da.make({target:$("dashboard"),data:' . $oDashboards->getDashboardsObject($_SESSION['USER_LOGGED']) . '});});');
  G::RenderPage('publish');
}
catch ( Exception $e ) {
  $aMessage = array();
  $aMessage['MESSAGE'] = $e->getMessage();
  $G_PUBLISH           = new Publisher;
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage);
  G::RenderPage('publish');
}
