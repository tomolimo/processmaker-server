<?php
/**
 * adminProxy.php
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

class adminProxy extends HttpProxyController
{    
  function uxUserUpdate($httpData)
  {
    require_once 'classes/model/Users.php';
    $data = (array) json_decode($httpData->users);

    $user = UsersPeer::retrieveByPK($data['USR_UID']);
    $user->setUsrUx($data['USR_UX']);
    $user->save();
    $row = $user->toArray(BasePeer::TYPE_FIELDNAME);

    $uxList = self::getUxTypesList();
    $row['USR_UX'] = $uxList[$user->getUsrUx()];

    return array('success' => true, 'message'=>'done', 'users'=>$row);
  }

  function uxGroupUpdate($httpData)
  {
    require_once 'classes/model/Groupwf.php';
    $data = (array) json_decode($httpData->groups);

    $group = GroupwfPeer::retrieveByPK($data['GRP_UID']);
    $group->setGrpUx($data['GRP_UX']);
    $group->save();

    $g = new Groupwf();
    //$row = $group->toArray(BasePeer::TYPE_FIELDNAME);
    $row = $g->Load($group->getGrpUid());
    $row['CON_VALUE'] = $row['GRP_TITLE'];

    $uxList = self::getUxTypesList();
    $row['GRP_UX'] = $uxList[$group->getGrpUx()];

    return array('success' => true, 'message'=>'done', 'groups'=>$row);
  }

  function getUxTypesList($type = 'assoc')
  {
    $list = array();
      
    if ($type == 'assoc') {
      $list = array(
        'NORMAL' => 'Normal',
        'SIMPLIFIED' => 'Simplified',
        'SWITCHABLE' => 'Switchable'/*,
        'SINGLE' => 'Single Application'*/
      );
    }
    else {
      $list = array(
        array('NORMAL', 'Normal'),
        array('SIMPLIFIED', 'Simplified'),
        array('SWITCHABLE', 'Switchable')/*,
        array('SINGLE', 'Single Application')*/
      );
    }

    return $list;
  }

  function calendarSave() 
  {    
    //{ $_POST['BUSINESS_DAY']
    $businessDayArray = G::json_decode($_POST['BUSINESS_DAY']);
    $businessDayFixArray = array();      
    for($i=0;$i<sizeof($businessDayArray);$i++) {
      $businessDayFixArray[$i+1]['CALENDAR_BUSINESS_DAY'] = $businessDayArray[$i]->CALENDAR_BUSINESS_DAY;
      $businessDayFixArray[$i+1]['CALENDAR_BUSINESS_START'] = $businessDayArray[$i]->CALENDAR_BUSINESS_START;
      $businessDayFixArray[$i+1]['CALENDAR_BUSINESS_END'] = $businessDayArray[$i]->CALENDAR_BUSINESS_END;
    }
    $_POST['BUSINESS_DAY'] = $businessDayFixArray;
    //}
    
    //{ $_POST['CALENDAR_WORK_DAYS']
    $calendarWorkDaysArray = G::json_decode($_POST['CALENDAR_WORK_DAYS']);
    $calendarWorkDaysFixArray = array();
    for($i=0;$i<sizeof($calendarWorkDaysArray);$i++) {
      $calendarWorkDaysFixArray[$i] = $calendarWorkDaysArray[$i]."";        
    }
    $_POST['CALENDAR_WORK_DAYS'] = $calendarWorkDaysFixArray;     
    //} 
    
    //{ $_POST['HOLIDAY']
    $holidayArray = G::json_decode($_POST['HOLIDAY']);
    $holidayFixArray = array();      
    for($i=0;$i<sizeof($holidayArray);$i++) {
      $holidayFixArray[$i+1]['CALENDAR_HOLIDAY_NAME'] = $holidayArray[$i]->CALENDAR_HOLIDAY_NAME;
      $holidayFixArray[$i+1]['CALENDAR_HOLIDAY_START'] = $holidayArray[$i]->CALENDAR_HOLIDAY_START;
      $holidayFixArray[$i+1]['CALENDAR_HOLIDAY_END'] = $holidayArray[$i]->CALENDAR_HOLIDAY_END; 
    }
    $_POST['HOLIDAY'] = $holidayFixArray;
    //}
    
    //[ CALENDAR_STATUS BUSINESS_DAY_STATUS HOLIDAY_STATUS            
    if($_POST['BUSINESS_DAY_STATUS']=="INACTIVE") {
      unset($_POST['BUSINESS_DAY_STATUS']);        
    }      
    if($_POST['HOLIDAY_STATUS']=="INACTIVE") {
      unset($_POST['HOLIDAY_STATUS']);        
    }      
    //]
    
    $form = $_POST;
    G::LoadClass('calendar');
    $calendarObj=new calendar();
    $calendarObj->saveCalendarInfo($form);
    
    echo "{success: true}";      
  }
  
  /**
   * getting the kind of the authentication source
   * @param object $params
   * @return array $data
   */
  function testingOption($params){
    
    $data['success'] = true; 
    $data['optionAuthS'] = $params->optionAuthS;
    return $data;
    
  }// end testingOption function
  
  /**
   * saving the authentication source data
   * @param object $params
   * @return array $data
   */
  function saveAuthSources($params){

    global $RBAC;
    if ($RBAC->userCanAccess('PM_SETUP_ADVANCE') != 1) {
      G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
	    G::header('location: ../login/login');
	    die;
    }
    $aCommonFields = array('AUTH_SOURCE_UID',
                           'AUTH_SOURCE_NAME',
                           'AUTH_SOURCE_PROVIDER',
                           'AUTH_SOURCE_SERVER_NAME',
                           'AUTH_SOURCE_PORT',
                           'AUTH_SOURCE_ENABLED_TLS',
                           'AUTH_ANONYMOUS',
                           'AUTH_SOURCE_SEARCH_USER',
                           'AUTH_SOURCE_PASSWORD',
                           'AUTH_SOURCE_VERSION',
                           'AUTH_SOURCE_BASE_DN',
                           'AUTH_SOURCE_OBJECT_CLASSES',
                           'AUTH_SOURCE_ATTRIBUTES');

    $aFields = $aData = array();

    unset($params->PHPSESSID);
    foreach ($params as $sField => $sValue) {
      if (in_array($sField, $aCommonFields)) {
        $aFields[$sField] = (($sField=='AUTH_SOURCE_ENABLED_TLS' || $sField=='AUTH_ANONYMOUS'))? ($sValue=='yes')?1:0 :$sValue;
      }
      else {
        $aData[$sField] = ($sValue=='Active Directory')?'ad':$sValue;
      }
    }
    $aFields['AUTH_SOURCE_DATA'] = $aData;
    if ($aFields['AUTH_SOURCE_UID'] == '') {
      $RBAC->createAuthSource($aFields);
    }
    else {
      $RBAC->updateAuthSource($aFields);
    }
    $data=array();
    $data['success'] = true; 
    return $data;
  }//end saveAuthSoruces function
}
