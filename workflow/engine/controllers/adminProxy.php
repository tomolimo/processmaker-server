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
        'SWITCHABLE' => 'Switchable',
        'SINGLE' => 'Single Application'
      );
    }
    else {
      $list = array(
        array('NORMAL', 'Normal'),
        array('SIMPLIFIED', 'Simplified'),
        array('SWITCHABLE', 'Switchable'),
        array('SINGLE', 'Single Application')
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
  
 /**
  * for Test email configuration
  * @autor Alvaro  <alvaro@colosa.com>
  */
  public function testConnection($params) {
    
    G::LoadClass('net');
    G::LoadThirdParty('phpmailer', 'class.smtp');
    
    $step = $_POST['step'];  
    $server = $_POST['server'];
    $user = $_POST['user'];    
    $passwd = $_POST['passwd'];
    $passwdHide = $_POST['passwdHide'];
    
    if (trim($passwdHide) != '') {
      $passwd = $passwdHide;
      $passwdHide = '';
    }
    
    $passwdDec = G::decrypt($passwd,'EMAILENCRYPT');
    
    if (strpos( $passwdDec, 'hash:' ) !== false) {
      list($hash, $pass) = explode(":", $passwdDec);   
      $_POST['passwd'] = $pass;
    }
    
    $port = $_POST['port'];
    $auth_required = $_POST['req_auth'];
    $UseSecureCon = $_POST['UseSecureCon'];
    $SendaTestMail = $_POST['SendaTestMail'];
    $Mailto = $_POST['eMailto'];
    $SMTPSecure  = $_POST['UseSecureCon'];

    $Server = new NET($server);     
    $smtp = new SMTP;
    
    $timeout = 10;
    $hostinfo = array();
    $srv=$_POST['server'];
    
    switch ($step) {
      case 1:
        $this->success = $Server->getErrno() == 0;
        $this->msg = $this->result ? 'success' : $Server->error;
        break;
      
      case 2:
        $Server->scannPort($port);
        
        $this->success = $Server->getErrno() == 0; //'Successfull'.$smtp->status;
        $this->msg = $this->result ? '' : $Server->error;
        break;
      
      case 3:   //try to connect to host    
        if (preg_match('/^(.+):([0-9]+)$/', $srv, $hostinfo)) {
          $server = $hostinfo[1];
          $port = $hostinfo[2];
        }
        else { 
          $host = $srv;
        }
        
        $tls = (strtoupper($SMTPSecure) == 'tls');
        $ssl = (strtoupper($SMTPSecure) == 'ssl');    
        
        $this->success = $smtp->Connect(($ssl ? 'ssl://':'').$server, $port, $timeout);
        $this->msg = $this->result ? '' : $Server->error;
        
        break;
      
      case 4:  //try login to host        
        if($auth_required == 'true') {
          try {	          
            if (preg_match('/^(.+):([0-9]+)$/', $srv, $hostinfo)) {
              $server = $hostinfo[1];
              $port = $hostinfo[2];
            }
            else {
              $server = $srv;	          
            }           
            if (strtoupper($UseSecureCon)=='TLS') {
              $tls = 'tls';
            }
            
            if (strtoupper($UseSecureCon)=='SSL') {
              $tls = 'ssl';
            }
            
            $tls = (strtoupper($UseSecureCon) == 'tls');
            $ssl = (strtoupper($UseSecureCon) == 'ssl');
            $server = $_POST['server'];
            
            if(strtoupper($UseSecureCon) == 'SSL') {
              $resp = $smtp->Connect(('ssl://').$server, $port, $timeout);
            }
            else {
              $resp = $smtp->Connect($server, $port, $timeout);
            }
             
            if ($resp) {
              $hello = $_SERVER['SERVER_NAME'];
              $smtp->Hello($hello);
              
              if (strtoupper($UseSecureCon) == 'TLS') {                
                $smtp->Hello($hello);
              }
           
              if( $smtp->Authenticate($user, $passwd) ) { 
                $this->success = true;                 
              }
              else {
                $this->success = false;
                $this->msg = $smtp->error['error'];
              }
            }
            else {
              $this->success = false;
              $this->msg = $smtp->error['error'];
            }
          }
          catch (Exception $e) {
            $this->success = false;
            $this->msg = $e->getMessage(); 
          }
        }
        else {
          $this->success = true;
          $this->msg = 'No authentication required!';
        }
        break;
      
      case 5:   //send a test mail    
        if($SendaTestMail == 'true') {
          try {             
            $_POST['FROM_NAME'] = 'Process Maker O.S. [Test mail]';
            $_POST['FROM_EMAIL'] = $user;
            $_POST['MESS_ENGINE'] = 'PHPMAILER';
            $_POST['MESS_SERVER'] = $server;
            $_POST['MESS_PORT']   = $port;
            $_POST['MESS_ACCOUNT'] = $user;
            $_POST['MESS_PASSWORD'] = $passwd;
            $_POST['TO'] = $Mailto;
            
            if($auth_required == 'true') { 
              $_POST['SMTPAuth'] = true;
            }
            else {
              $_POST['SMTPAuth'] = false;                      
            }
            
            if ($_POST['UseSecureCon'] == 'ssl') {
              $_POST['MESS_SERVER'] = 'ssl://'.$_POST['MESS_SERVER'];
            }
            
            $resp = $this->sendTestMail();            
            if ($resp->status == '1') {
              $this->success=true;           
            }
            else {
              $this->success=false;
              $this->msg=$smtp->error['error'];         
            }
          }
          catch (Exception $e) {
            $this->success = false;
            $this->msg = $e->getMessage();           
          }          
        }
        else {
          $this->success=true;
          $this->msg='jump this step';         
        }
        break;
    }   
  }
  
 /**
  * for send email configuration
  * @autor Alvaro  <alvaro@colosa.com>
  */
  public function sendTestMail() {
    
    global $G_PUBLISH;
    G::LoadClass("system");
    G::LoadClass('spool');
        
    $sFrom    = ($_POST['FROM_NAME'] != '' ? $_POST['FROM_NAME'] . ' ' : '') . '<' . $_POST['FROM_EMAIL'] . '>';
    $sSubject = G::LoadTranslation('ID_MESS_TEST_SUBJECT');
    $msg      = G::LoadTranslation('ID_MESS_TEST_BODY');
    
    switch ($_POST['MESS_ENGINE']) {
      case 'MAIL':
        $engine = G::LoadTranslation('ID_MESS_ENGINE_TYPE_1');
        break;
      
      case 'PHPMAILER':
        $engine = G::LoadTranslation('ID_MESS_ENGINE_TYPE_2');
        break;
      
      case 'OPENMAIL':
        $engine = G::LoadTranslation('ID_MESS_ENGINE_TYPE_3');
        break;
    }
    
    $sBodyPre  = new TemplatePower(PATH_TPL . 'admin' . PATH_SEP . 'email.tpl');    
    $sBodyPre->prepare();
    $sBodyPre->assign('server', $_SERVER['SERVER_NAME']);
    $sBodyPre->assign('date', date('H:i:s'));
    $sBodyPre->assign('ver', System::getVersion());
    $sBodyPre->assign('engine', $engine);
    $sBodyPre->assign('msg', $msg);
    $sBody = $sBodyPre->getOutputContent();
    
    $oSpool = new spoolRun();
    $oSpool->setConfig(
      array(
        'MESS_ENGINE'   => $_POST['MESS_ENGINE'],
        'MESS_SERVER'   => $_POST['MESS_SERVER'],
        'MESS_PORT'     => $_POST['MESS_PORT'],
        'MESS_ACCOUNT'  => $_POST['MESS_ACCOUNT'],
        'MESS_PASSWORD' => $_POST['MESS_PASSWORD'],
        'SMTPAuth'      => $_POST['SMTPAuth'],
        'SMTPSecure'    => isset($_POST['SMTPSecure'])?$_POST['SMTPSecure']:'none'
      )
    );
    
    $oSpool->create(
      array(
        'msg_uid'          => '',
        'app_uid'          => '',
        'del_index'        => 0,
        'app_msg_type'     => 'TEST',
        'app_msg_subject'  => $sSubject,
        'app_msg_from'     => $sFrom,
        'app_msg_to'       => $_POST['TO'],
        'app_msg_body'     => $sBody,
        'app_msg_cc'       => '',
        'app_msg_bcc'      => '',
        'app_msg_attach'   => '',
        'app_msg_template' => '',
        'app_msg_status'   => 'pending',
        'app_msg_attach'=>'' // Added By Ankit
      )
    );
    
    $oSpool->sendMail();	
    $G_PUBLISH = new Publisher();
    
    if ($oSpool->status == 'sent') {
      $o->status = true;
      $o->success = true;
      $o->msg = G::LoadTranslation('ID_MAIL_TEST_SUCCESS');
    }
    else {
      $o->status = false;
      $o->success = false;
      $o->msg = $oSpool->error;
    }
    return $o; 
  }
  
 /**
  * getting Save email configuration
  * @autor Alvaro  <alvaro@colosa.com>
  */
  public function saveConfiguration() {
    
    require_once 'classes/model/Configuration.php';
    
    try {            
      $oConfiguration = new Configuration();
      $aFields['MESS_PASSWORD']  = $_POST['passwd'];
      
      if ($_POST['passwdHide'] != '') {
        $aFields['MESS_PASSWORD'] = $_POST['passwdHide'];
      }
      
      $aFields['MESS_PASSWORD_HIDDEN'] = '';
      $aPasswd = G::decrypt($aFields['MESS_PASSWORD'],'EMAILENCRYPT');
      
      if ((strpos( $aPasswd, 'hash:') !== true) && ($aFields['MESS_PASSWORD'] != '')) {   // for plain text
        $aFields['MESS_PASSWORD'] = 'hash:'.$aFields['MESS_PASSWORD'];
        $aFields['MESS_PASSWORD'] = G::encrypt($aFields['MESS_PASSWORD'],'EMAILENCRYPT');    
      }
      
      $aFields['MESS_ENABLED']             = isset($_POST['EnableEmailNotifications']) ? $_POST['EnableEmailNotifications'] : '';
      $aFields['MESS_ENGINE']              = $_POST['EmailEngine'];
      $aFields['MESS_SERVER']              = trim($_POST['server']);
      $aFields['MESS_RAUTH']               = isset($_POST['req_auth']) ? $_POST['req_auth'] : '';
      $aFields['MESS_PORT']                = $_POST['port'];
      $aFields['MESS_ACCOUNT']             = $_POST['from'];
      $aFields['MESS_BACKGROUND']          = '';//isset($_POST['background']) ? $_POST['background'] : '';
      $aFields['MESS_EXECUTE_EVERY']       = '';//$_POST['form']['MESS_EXECUTE_EVERY'];
      $aFields['MESS_SEND_MAX']            = '';//$_POST['form']['MESS_SEND_MAX'];
      $aFields['SMTPSecure']               = $_POST['UseSecureCon'];
      $aFields['MAIL_TO']                  = $_POST['eMailto'];
      $aFields['MESS_TRY_SEND_INMEDIATLY'] = $_POST['SendaTestMail'];//isset($_POST['form']['MESS_TRY_SEND_INMEDIATLY']) ? $_POST['form']['MESS_TRY_SEND_INMEDIATLY'] : '';
      $CfgUid='Emails';
      $ObjUid='';
      $ProUid='';
      $UsrUid='';
      $AppUid='';
      
      if($oConfiguration->exists($CfgUid, $ObjUid, $ProUid, $UsrUid, $AppUid)) {
        $oConfiguration->update(
          array(
            'CFG_UID'   => 'Emails',
            'OBJ_UID'   => '',
            'CFG_VALUE' => serialize($aFields),
            'PRO_UID'   => '',
            'USR_UID'   => '',
            'APP_UID'   => ''
          )
        );
        $this->success='true';
        $this->msg='Saved';  
      }
      else {
        $oConfiguration->create(
          array(
            'CFG_UID'   => 'Emails',
            'OBJ_UID'   => '',
            'CFG_VALUE' => serialize($aFields),
            'PRO_UID'   => '',
            'USR_UID'   => '',
            'APP_UID'   => ''
          )
        );
        $this->success='true'; 
        $this->msg='Saved'; 
      }
    }
    catch (Exception $e) {      
      $this->success= false;
      $this->msg = $e->getMessage();     
    }
  }

 /**
  * loadFields for email configuration
  * @autor Alvaro  <alvaro@colosa.com>
  */  
  public function loadFields() {
    
    G::loadClass('configuration');
    
    $oConfiguration = new Configurations();      
    $oConfiguration->loadConfig($x, 'Emails','','','','');
    $fields = $oConfiguration->aConfig;
    $this->success = (count($fields) > 0);    
    $passwd = $fields['MESS_PASSWORD'];
    $passwdDec = G::decrypt($passwd,'EMAILENCRYPT');
    if (strpos( $passwdDec, 'hash:' ) !== false) {
      list($hash, $pass) = explode(":", $passwdDec);   
      $fields['MESS_PASSWORD'] = $pass;
    }   
    $this->data = $fields;
  }
}
