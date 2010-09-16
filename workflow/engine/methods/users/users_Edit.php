<?php
/**
 * users_Edit.php
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
  }
  // deprecated the class XmlForm_Field_Image is currently part of the class.xmlform.php package
  // the use of the external xmlfield_Image is highly discouraged
  if (!class_exists('XmlForm_Field_Image')){
    G::LoadClass('xmlfield_Image');
  }
  require_once 'classes/model/Users.php';
  require_once 'classes/model/Department.php';
  
  $_SESSION['CURRENT_USER'] = $_GET['USR_UID'];
  $oUser                    = new Users();
  $aFields                  = $oUser->load($_GET['USR_UID']);
  $aFields['USR_PASSWORD']  = '********';
  $aFields['MESSAGE0']   = str_replace("\r\n","<br>",G::LoadTranslation('ID_USER_REGISTERED')) . '!';
  $aFields['MESSAGE1']   = str_replace("\r\n","<br>",G::LoadTranslation('ID_MSG_ERROR_USR_USERNAME'));
  $aFields['MESSAGE2']   = str_replace("\r\n","<br>",G::LoadTranslation('ID_MSG_ERROR_DUE_DATE'));
  $aFields['MESSAGE3']   = str_replace("\r\n","<br>",G::LoadTranslation('ID_NEW_PASS_SAME_OLD_PASS'));
  $aFields['MESSAGE4']   = str_replace("\r\n","<br>",G::LoadTranslation('ID_MSG_ERROR_USR_FIRSTNAME'));
  $aFields['MESSAGE5']   = str_replace("\r\n","<br>",G::LoadTranslation('ID_MSG_ERROR_USR_LASTNAME'));
  $aFields['START_DATE']    = date('Y-m-d');
  $aFields['END_DATE']      = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y') + 5));
  
//  $G_MAIN_MENU              = 'processmaker';
//  $G_ID_MENU_SELECTED       = 'USERS';
  $G_MAIN_MENU            = 'processmaker';
  $G_SUB_MENU             = 'users';
  $G_ID_MENU_SELECTED     = 'USERS';
  $G_ID_SUB_MENU_SELECTED = 'USERS';

  $G_PUBLISH                = new Publisher;
  
  //getting the user and department
  $oDepInfo = new Department();
  $oUser = UsersPeer::retrieveByPk( $aFields['USR_REPORTS_TO'] );
  if ( get_class ($oUser) == 'Users' ) {
    $userFields = $oUser->toArray(BasePeer::TYPE_FIELDNAME);
    $aFields['USR_REPORTS_TO'] = $userFields['USR_FIRSTNAME'] . ' ' . $userFields['USR_LASTNAME'];
    try {
      $depFields =  $oDepInfo->load($userFields['DEP_UID'] );
      $aFields['USR_REPORTS_TO'] .=  " (" . $depFields['DEPO_TITLE'] . ")";
    }
    catch( Exception $e ) {
    }
  }
  else{
    $aFields['USR_REPORTS_TO'] = ' ';
  }

  try {
    $depFields =  $oDepInfo->load($aFields['DEP_UID']);
    $aFields['USR_DEPARTMENT'] = $depFields['DEPO_TITLE'];
  }
  catch( Exception $e ) {
    $oUser = UsersPeer::retrieveByPk( $_GET['USR_UID'] );
    $oUser->setDepUid( '' );
    $oUser->save();
  }
  
  //Load Calendar options and falue for this user
  G::LoadClass ( 'calendar' );
  $calendar = new Calendar ( );
  $calendarObj = $calendar->getCalendarList ( true, true );
  global $_DBArray;
  $_DBArray ['availableCalendars'] = $calendarObj ['array'];
  $_SESSION ['_DBArray'] = $_DBArray;
  $calendarInfo = $calendar->getCalendarFor ( $_GET['USR_UID'], $_GET['USR_UID'], $_GET['USR_UID'] );
  //If the function returns a DEFAULT calendar it means that this object doesn't have assigned any calendar
  $aFields ['USR_CALENDAR'] = $calendarInfo ['CALENDAR_APPLIED']!='DEFAULT'? $calendarInfo ['CALENDAR_UID']:"";
	$aFields['RANDOM']        = rand();
  //always show this form  users_EditRT.xml.
	$G_PUBLISH->AddContent('xmlform', 'xmlform', 'users/users_EditRT.xml', '', $aFields, 'users_Save?USR_UID=' . $_SESSION['CURRENT_USER']);
  //if (isset($aFields['DEP_UID'])  && isset($aFields['USR_REPORTS_TO']) &&  $aFields['DEP_UID']!='' && $aFields['USR_REPORTS_TO']!='')
  //	$G_PUBLISH->AddContent('xmlform', 'xmlform', 'users/users_EditRT.xml', '', $aFields, 'users_Save?USR_UID=' . $_SESSION['CURRENT_USER']);
  //else
  //	$G_PUBLISH->AddContent('xmlform', 'xmlform', 'users/users_Edit.xml', '', $aFields, 'users_Save?USR_UID=' . $_SESSION['CURRENT_USER']);
  
  G::RenderPage('publish');
}
catch (Exception $oException) {
	die($oException->getMessage());
}
?>