<?php
/**
 * authentication.php
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


  if (!isset($_POST['form']) ) {
    G::SendTemporalMessage ('ID_USER_HAVENT_RIGHTS_SYSTEM', "error");
    G::header  ("location: login.html");die;
  }


try {
  $frm = $_POST['form'];
  $usr = '';
  $pwd = '';
  if (isset($frm['USR_USERNAME'])) {
    $usr = strtolower(trim($frm['USR_USERNAME']));
    $pwd = trim($frm['USR_PASSWORD']);
  }
  $uid = $RBAC->VerifyLogin($usr , $pwd);
  $sPwd = 'currentPwd';
  switch ($uid) {
    //The user not exists
    case -1:
      G::SendTemporalMessage ('ID_USER_NOT_REGISTERED', "warning");
      break;
    //The password is incorrect
    case -2:
      G::SendTemporalMessage ('ID_WRONG_PASS', "warning");
      if(isset($_SESSION['__AUTH_ERROR__'])){
      	G::SendMessageText($_SESSION['__AUTH_ERROR__'], "warning");
      	unset($_SESSION['__AUTH_ERROR__']);
      }
      break;
    //The user is inactive
    case -3:
      require_once 'classes/model/Users.php';
      $user = new Users;
      $aUser = $user->loadByUsernameInArray($usr);
      switch($aUser['USR_STATUS']){
        case 'VACATION': 
          G::SendTemporalMessage ('ID_USER_ONVACATION', "warning");
          break;
        CASE 'INACTIVE': 
          G::SendTemporalMessage ('ID_USER_INACTIVE', "warning");
          break;
      }
      break;
    //The Due date is finished
    case -4:
      G::SendTemporalMessage ('ID_USER_INACTIVE_BY_DATE', "warning");
      break;
    case -5:
      G::SendTemporalMessage ('ID_AUTHENTICATION_SOURCE_INVALID', "warning");
      break;
  }
  $$sPwd= $pwd;
  
  //to avoid empty string in user field.  This will avoid a weird message "this row doesnt exists"
  if ( !isset($uid) ) {
    $uid = -1;
    G::SendTemporalMessage ('ID_USER_NOT_REGISTERED', "warning");
  }

  if ( !isset($uid) || $uid < 0 ) {
    if(isset($_SESSION['FAILED_LOGINS']))
      $_SESSION['FAILED_LOGINS']++;
    if (!defined('PPP_FAILED_LOGINS')) {
      define('PPP_FAILED_LOGINS', 0);
    }
    if (PPP_FAILED_LOGINS > 0) {
      if ($_SESSION['FAILED_LOGINS'] >= PPP_FAILED_LOGINS) {
        $oConnection = Propel::getConnection('rbac');
        $oStatement  = $oConnection->prepareStatement("SELECT USR_UID FROM USERS WHERE USR_USERNAME = '" . $usr . "'");
        $oDataset    = $oStatement->executeQuery();
        if ($oDataset->next()) {
          $sUserUID = $oDataset->getString('USR_UID');
          $oConnection = Propel::getConnection('rbac');
          $oStatement  = $oConnection->prepareStatement("UPDATE USERS SET USR_STATUS = 0 WHERE USR_UID = '" . $sUserUID . "'");
          $oStatement->executeQuery();
          $oConnection = Propel::getConnection('workflow');
          $oStatement  = $oConnection->prepareStatement("UPDATE USERS SET USR_STATUS = 'INACTIVE' WHERE USR_UID = '" . $sUserUID . "'");
          $oStatement->executeQuery();
          unset($_SESSION['FAILED_LOGINS']);
          G::SendMessageText(G::LoadTranslation('ID_ACCOUNT') . ' "' . $usr . '" ' . G::LoadTranslation('ID_ACCOUNT_DISABLED_CONTACT_ADMIN'), 'warning');
        }
        else {
          //Nothing
        }
      }
    }
    G::header  ("location: login.html");
    die;
  }
  if(!isset( $_SESSION['WORKSPACE'] ) ) $_SESSION['WORKSPACE'] = SYS_SYS;

  //Execute the SSO Script from plugin
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    if ( $oPluginRegistry->existsTrigger ( PM_LOGIN ) ) {
            $lSession="";
            $loginInfo = new loginInfo ($usr, $pwd, $lSession  );          
            $oPluginRegistry->executeTriggers ( PM_LOGIN , $loginInfo );
    }

  $_SESSION['USER_LOGGED']  = $uid;
  $_SESSION['USR_USERNAME'] = $usr;
  $aUser = $RBAC->userObj->load($_SESSION['USER_LOGGED']);
  $RBAC->loadUserRolePermission('PROCESSMAKER', $_SESSION['USER_LOGGED']);
  
  $rol = $RBAC->rolesObj->load($RBAC->aUserInfo['PROCESSMAKER']['ROLE']['ROL_UID']);
  $_SESSION['USR_FULLNAME'] = $aUser['USR_FIRSTNAME'] . ' ' . $aUser['USR_LASTNAME']; 
  $_SESSION['USR_ROLENAME'] = $rol['ROL_NAME']; 
  
  unset($_SESSION['FAILED_LOGINS']);

  // increment logins in heartbeat
  G::LoadClass('serverConfiguration');
  $oServerConf =& serverConf::getSingleton();
  $oServerConf->sucessfulLogin();

  // Asign the uid of user to userloggedobj
  $RBAC->loadUserRolePermission($RBAC->sSystem, $uid);
  $res = $RBAC->userCanAccess('PM_LOGIN');
  if ($res != 1 ) {
    if ($res == -2)
      G::SendTemporalMessage ('ID_USER_HAVENT_RIGHTS_SYSTEM', "error");
    else
      G::SendTemporalMessage ('ID_USER_HAVENT_RIGHTS_PAGE', "error");
    G::header  ("location: login.html");
    die;
  }

  if (isset($frm['USER_LANG'])) {
    if ($frm['USER_LANG'] != '') {
      $lang = $frm['USER_LANG'];
    }
  }
  else {
    if (defined('SYS_LANG')) {
      $lang = SYS_LANG;
    }
    else {
      $lang = 'en';
    }
  }

  /**log by Everth**/
  require_once 'classes/model/LoginLog.php';
  $weblog=new LoginLog();
  $aLog['LOG_UID']            = G::generateUniqueID();
  $aLog['LOG_STATUS']         = 'ACTIVE';
  $aLog['LOG_IP']             = $_SERVER['REMOTE_ADDR'];
  $aLog['LOG_SID']            = session_id();
  $aLog['LOG_INIT_DATE']      = date('Y-m-d H:i:s');
  //$aLog['LOG_END_DATE']       = '0000-00-00 00:00:00';
  $aLog['LOG_CLIENT_HOSTNAME']= $_SERVER['HTTP_HOST'];
  $aLog['USR_UID']            = $_SESSION['USER_LOGGED'];
  $weblog->create($aLog);
  /**end log**/

  /* Check password using policy - Start */
  require_once 'classes/model/UsersProperties.php';
  $oUserProperty = new UsersProperties();
  $aUserProperty = $oUserProperty->loadOrCreateIfNotExists($_SESSION['USER_LOGGED'], array('USR_PASSWORD_HISTORY' => serialize(array(md5($currentPwd)))));
  $aErrors       = $oUserProperty->validatePassword($_POST['form']['USR_PASSWORD'], $aUserProperty['USR_LAST_UPDATE_DATE'], $aUserProperty['USR_LOGGED_NEXT_TIME']);
  if (!empty($aErrors)) {
    if (!defined('NO_DISPLAY_USERNAME')) {
      define('NO_DISPLAY_USERNAME', 1);
    }
    $aFields = array();
    $aFields['DESCRIPTION']  = '<span style="font-weight:normal;">';
    $aFields['DESCRIPTION'] .= G::LoadTranslation('ID_POLICY_ALERT').':<br /><br />';
    foreach ($aErrors as $sError)  {
      switch ($sError) {
        case 'ID_PPP_MINIMUN_LENGTH':
          $aFields['DESCRIPTION'] .= ' - ' . G::LoadTranslation($sError).': ' . PPP_MINIMUN_LENGTH . '<br />';
          $aFields[substr($sError, 3)] = PPP_MINIMUN_LENGTH;
        break;
        case 'ID_PPP_MAXIMUN_LENGTH':
          $aFields['DESCRIPTION'] .= ' - ' . G::LoadTranslation($sError).': ' . PPP_MAXIMUN_LENGTH . '<br />';
          $aFields[substr($sError, 3)] = PPP_MAXIMUN_LENGTH;
        break;
        case 'ID_PPP_EXPIRATION_IN':
          $aFields['DESCRIPTION'] .= ' - ' . G::LoadTranslation($sError).' ' . PPP_EXPIRATION_IN . ' ' . G::LoadTranslation('ID_DAYS') . '<br />';
          $aFields[substr($sError, 3)] = PPP_EXPIRATION_IN;
        break;
        default:
          $aFields['DESCRIPTION'] .= ' - ' . G::LoadTranslation($sError).'<br />';
          $aFields[substr($sError, 3)] = 1;
        break;
      }
    }
    $aFields['DESCRIPTION'] .= '<br />' . G::LoadTranslation('ID_PLEASE_CHANGE_PASSWORD_POLICY') . '<br /><br /></span>';
    $G_PUBLISH = new Publisher;
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/changePassword', '', $aFields, 'changePassword');
    G::RenderPage('publish');
    die;
  }
  /* Check password using policy - End */
  if ( isset($_POST['form']['URL']) && $_POST['form']['URL'] != '') {
    $sLocation = $_POST['form']['URL'];
  }
  else {
    $sLocation = $oUserProperty->redirectTo($_SESSION['USER_LOGGED'], $lang);
  }
  G::header('Location: ' . $sLocation);
  die;

}
catch ( Exception $e ) {
  $aMessage['MESSAGE'] = $e->getMessage();
  $G_PUBLISH = new Publisher;
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
  G::RenderPage( 'publish' );
  die;
}