<?php
/**
 * users_New.php
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
try {
  global $RBAC;
  switch ($RBAC->userCanAccess('PM_FACTORY'))
  {
  	case -2:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	case -1:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
    case -3:
      G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
      G::header('location: ../login/login');
      die;
    break;
  	
  }
  
  $aFields['MESSAGE0']   = str_replace("\r\n","<br>",G::LoadTranslation('ID_USER_REGISTERED')) . '!';
  $aFields['MESSAGE1']   = str_replace("\r\n","<br>",G::LoadTranslation('ID_MSG_ERROR_USR_USERNAME'));
  $aFields['MESSAGE2']   = str_replace("\r\n","<br>",G::LoadTranslation('ID_MSG_ERROR_DUE_DATE'));
  $aFields['MESSAGE3']   = str_replace("\r\n","<br>",G::LoadTranslation('ID_NEW_PASS_SAME_OLD_PASS'));
  $aFields['MESSAGE4']   = str_replace("\r\n","<br>",G::LoadTranslation('ID_MSG_ERROR_USR_FIRSTNAME'));
  $aFields['MESSAGE5']   = str_replace("\r\n","<br>",G::LoadTranslation('ID_MSG_ERROR_USR_LASTNAME'));
    // the default role variable sets the value that will be showed as the default for the role field.
  $aFields['DEFAULT_ROLE']   = 'PROCESSMAKER_OPERATOR';
  $aFields['START_DATE'] = date('Y-m-d');
  $aFields['END_DATE']   = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y') + 5));
  $aFields['USR_DUE_DATE']= date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y') + 1));
  
  //Load Calendar options and falue for this user
  G::LoadClass ( 'calendar' );
  $calendar = new Calendar ( );
  $calendarObj = $calendar->getCalendarList ( true, true );
  global $_DBArray;
  $_DBArray ['availableCalendars'] = $calendarObj ['array'];
  $_SESSION ['_DBArray'] = $_DBArray;

//  $G_MAIN_MENU           = 'processmaker';
//  $G_ID_MENU_SELECTED    = 'USERS';

  $G_MAIN_MENU            = 'processmaker';
  $G_SUB_MENU             = 'users';
  $G_ID_MENU_SELECTED     = 'USERS';
  $G_ID_SUB_MENU_SELECTED = 'USERS';

/////////////////////////////
//SELECT USR_UID, CONCAT(USR_LASTNAME, " ", USR_FIRSTNAME) FROM USERS WHERE USR_STATUS = 1 ORDER BY USR_LASTNAME
  require_once 'classes/model/Users.php';
	$oCriteria=new Criteria();
  $oCriteria->addSelectColumn(UsersPeer::USR_UID);
  $oCriteria->addSelectColumn(UsersPeer::USR_USERNAME);
  $oCriteria->addSelectColumn(UsersPeer::USR_FIRSTNAME);
  $oCriteria->addSelectColumn(UsersPeer::USR_LASTNAME);
  $oCriteria->addSelectColumn(UsersPeer::USR_EMAIL);
  $oCriteria->add(UsersPeer::USR_STATUS,'ACTIVE');
  //$oCriteria->add(UsersPeer::USR_UID,$_GET['USR_UID'], Criteria::NOT_EQUAL);
  $oDataset=UsersPeer::doSelectRS($oCriteria);
  $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
  ///////////////////////
  G::loadClass('configuration');
  $oConf = new Configurations;
  $oConf->loadConfig($obj, 'ENVIRONMENT_SETTINGS','');

  $defaultOption = isset($oConf->aConfig['format'])? $oConf->aConfig['format']: '';
  
  $aUserInfo = array();
  $aUserInfo[] = array('USR_UID' => 'char','USER_FULLNAME' => 'char');
  while( $oDataset->next()){
  $aRow1 = $oDataset->getRow();
  
  $infoUser = G::getFormatUserList($defaultOption,$aRow1);
  $aUserInfo[]=array(
          'USR_UID'       => $aRow1['USR_UID'],
          'USER_FULLNAME' => $infoUser
         );
  }
  //print_r($aUserInfo);
  global $_DBArray;
  $_DBArray['aUserInfo']  = $aUserInfo;
  $_SESSION['_DBArray'] = $_DBArray;
/////////////////////////////

  
  $G_PUBLISH             = new Publisher;
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'users/users_New.xml', '', $aFields, 'users_Save');
  G::RenderPage('publish','blank');
}
catch (Exception $oException) {
	die($oException->getMessage());
}
?>