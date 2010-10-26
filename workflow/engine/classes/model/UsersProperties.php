<?php

require_once 'classes/model/om/BaseUsersProperties.php';


/**
 * Skeleton subclass for representing a row from the 'USERS_PROPERTIES' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class UsersProperties extends BaseUsersProperties {
  function UserPropertyExists($sUserUID) {
    try {
      $oUserProperty = UsersPropertiesPeer::retrieveByPk($sUserUID);
      if (get_class($oUserProperty) == 'UsersProperties') {
        return true;
      }
      else {
        return false;
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  public function load($sUserUID) {
    try {
      $oUserProperty = UsersPropertiesPeer::retrieveByPK($sUserUID);
      if (!is_null($oUserProperty)) {
        $aFields = $oUserProperty->toArray(BasePeer::TYPE_FIELDNAME);
        $this->fromArray($aFields, BasePeer::TYPE_FIELDNAME);
        return $aFields;
      }
      else {
        throw(new Exception('This row doesn\'t exists!'));
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }

  public function create($aData) {
    $oConnection = Propel::getConnection(UsersPropertiesPeer::DATABASE_NAME);
    try {
      $oUserProperty = new UsersProperties();
      $oUserProperty->fromArray($aData, BasePeer::TYPE_FIELDNAME);
      if ($oUserProperty->validate()) {
        $oConnection->begin();
        $iResult = $oUserProperty->save();
        $oConnection->commit();
        return true;
      }
      else {
        $sMessage = '';
        $aValidationFailures = $oUserProperty->getValidationFailures();
        foreach($aValidationFailures as $oValidationFailure) {
          $sMessage .= $oValidationFailure->getMessage() . '<br />';
        }
        throw(new Exception('The registry cannot be created!<br />'.$sMessage));
      }
    }
    catch (Exception $oError) {
      $oConnection->rollback();
      throw($oError);
    }
  }

  public function update($aData) {
    $oConnection = Propel::getConnection(UsersPropertiesPeer::DATABASE_NAME);
    try {
      $oUserProperty = UsersPropertiesPeer::retrieveByPK($aData['USR_UID']);
      if (!is_null($oUserProperty)) {
        $oUserProperty->fromArray($aData, BasePeer::TYPE_FIELDNAME);
        if ($oUserProperty->validate()) {
          $oConnection->begin();
          $iResult = $oUserProperty->save();
          $oConnection->commit();
          return $iResult;
        }
        else {
          $sMessage = '';
          $aValidationFailures = $oUserProperty->getValidationFailures();
          foreach($aValidationFailures as $oValidationFailure) {
            $sMessage .= $oValidationFailure->getMessage() . '<br />';
          }
          throw(new Exception('The registry cannot be updated!<br />'.$sMessage));
        }
      }
      else {
        throw(new Exception('This row doesn\'t exists!'));
      }
    }
    catch (Exception $oError) {
      $oConnection->rollback();
      throw($oError);
    }
  }

  public function loadOrCreateIfNotExists($sUserUID, $aUserProperty = array()) {
    if (!$this->UserPropertyExists($sUserUID)) {
      $aUserProperty['USR_UID'] = $sUserUID;
      if (!isset($aUserProperty['USR_LAST_UPDATE_DATE'])) {
        $aUserProperty['USR_LAST_UPDATE_DATE'] = date('Y-m-d H:i:s');
      }
      if (!isset($aUserProperty['USR_LOGGED_NEXT_TIME'])) {
        $aUserProperty['USR_LOGGED_NEXT_TIME'] = 0;
      }
      $this->create($aUserProperty);
    }
    else {
      $aUserProperty = $this->load($sUserUID);
    }
    return $aUserProperty;
  }

  public function validatePassword($sPassword, $sLastUpdate, $iChangePasswordNextTime) {
    if (!defined('PPP_MINIMUN_LENGTH')) {
      define('PPP_MINIMUN_LENGTH', 5);
    }
    if (!defined('PPP_MAXIMUN_LENGTH')) {
      define('PPP_MAXIMUN_LENGTH', 20);
    }
    if (!defined('PPP_NUMERICAL_CHARACTER_REQUIRED')) {
      define('PPP_NUMERICAL_CHARACTER_REQUIRED', 0);
    }
    if (!defined('PPP_UPPERCASE_CHARACTER_REQUIRED')) {
      define('PPP_UPPERCASE_CHARACTER_REQUIRED', 0);
    }
    if (!defined('PPP_SPECIAL_CHARACTER_REQUIRED')) {
      define('PPP_SPECIAL_CHARACTER_REQUIRED', 0);
    }
    if (!defined('PPP_EXPIRATION_IN')) {
      define('PPP_EXPIRATION_IN', 0);
    }
    if (!defined('PPP_CHANGE_PASSWORD_AFTER_NEXT_LOGIN')) {
      define('PPP_CHANGE_PASSWORD_AFTER_NEXT_LOGIN', 0);
    }
    if (function_exists('mb_strlen')) {
      $iLength = mb_strlen($sPassword);
    }
    else {
      $iLength = strlen($sPassword);
    }
    $aErrors = array();
    if ($iLength < PPP_MINIMUN_LENGTH) {
      $aErrors[] = 'ID_PPP_MINIMUN_LENGTH';
    }
    if ($iLength > PPP_MAXIMUN_LENGTH) {
      $aErrors[] = 'ID_PPP_MAXIMUN_LENGTH';
    }
    if (PPP_NUMERICAL_CHARACTER_REQUIRED == 1) {
      if (preg_match_all('/[0-9]/', $sPassword, $aMatch, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE) == 0) {
        $aErrors[] = 'ID_PPP_NUMERICAL_CHARACTER_REQUIRED';
      }
    }
    if (PPP_UPPERCASE_CHARACTER_REQUIRED == 1) {
      if (preg_match_all('/[A-Z]/', $sPassword, $aMatch, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE) == 0) {
        $aErrors[] = 'ID_PPP_UPPERCASE_CHARACTER_REQUIRED';
      }
    }
    if (PPP_SPECIAL_CHARACTER_REQUIRED == 1) {
      if (preg_match_all('/[��\\!|"@�#$~%�&�\/()=\'?��*+\-_.:,;]/', $sPassword, $aMatch, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE) == 0) {
        $aErrors[] = 'ID_PPP_SPECIAL_CHARACTER_REQUIRED';
      }
    }
    if (PPP_EXPIRATION_IN > 0) {
      G::LoadClass('dates');
      $oDates = new dates();
      $fDays  = $oDates->calculateDuration(date('Y-m-d H:i:s'), $sLastUpdate);
      if ($fDays > (PPP_EXPIRATION_IN*24)) {
        $aErrors[] = 'ID_PPP_EXPIRATION_IN';
      }
    }
    if (PPP_CHANGE_PASSWORD_AFTER_NEXT_LOGIN == 1) {
      if ($iChangePasswordNextTime == 1) {
        $aErrors[] = 'ID_PPP_CHANGE_PASSWORD_AFTER_NEXT_LOGIN';
      }
    }
    return $aErrors;
  }
  public function redirectTo($sUserUID, $sLanguage = 'en') {
    global $RBAC;
    //get the plugins, and check if there is redirectLogins
    //if yes, then redirect goes according his Role
    if ( class_exists('redirectDetail')) {
      //to do: complete the validation
      if(isset($RBAC->aUserInfo['PROCESSMAKER']['ROLE']['ROL_CODE']))
        $userRole = $RBAC->aUserInfo['PROCESSMAKER']['ROLE']['ROL_CODE'];
        
      $oPluginRegistry = &PMPluginRegistry::getSingleton();
      $aRedirectLogin = $oPluginRegistry->getRedirectLogins();
      if (isset($aRedirectLogin) && is_array($aRedirectLogin) ) {
        foreach ($aRedirectLogin as $key=>$detail) {
          if (isset($detail->sPathMethod) && $detail->sRoleCode == $userRole ) {
              return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . $detail->sPathMethod;
          }
        }
      }
    }
    //end plugin

    #New feature by Erik erik@colosa.com>
    #get user preferences for default redirect
    #verifying if it has any preferences on the configurations table
    G::loadClass('configuration');
    $oConf = new Configurations;
    $oConf->loadConfig($x, 'USER_PREFERENCES','','',$_SESSION['USER_LOGGED'],'');
    if( sizeof($oConf->aConfig) > 0) { #this user has a configuration record
    	
    	//these is for backward compatibility, because now, we dont have user and dashboard menu.
    	if ( $oConf->aConfig['DEFAULT_MENU'] == 'PM_USERS')     $oConf->aConfig['DEFAULT_MENU'] = 'PM_SETUP';
    	if ( $oConf->aConfig['DEFAULT_MENU'] == 'PM_DASHBOARD') $oConf->aConfig['DEFAULT_MENU'] = 'PM_SETUP';
    	
      switch($oConf->aConfig['DEFAULT_MENU']) {
        case 'PM_SETUP':
          if ($RBAC->userCanAccess('PM_SETUP') == 1) {
            return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . 'setup/main';
          }
          break;
        case 'PM_FACTORY':
          if ($RBAC->userCanAccess('PM_FACTORY') == 1) {
            return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . 'processes/main';
          }
          break;
        case 'PM_CASES':
          if ($RBAC->userCanAccess('PM_CASES') == 1) {
            return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . 'cases/main';
          }
          break;
        case 'PM_USERS':
          if ($RBAC->userCanAccess('PM_USERS') == 1) {
            return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . 'users/users_List';
          }
          break;
        case 'PM_DASHBOARD':
          if ($RBAC->userCanAccess('PM_DASHBOARD') == 1) {
            return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . 'dashboard/dashboard';
          }
          break;
      }
    }

    if ($RBAC->userCanAccess('PM_FACTORY') == 1) {
      return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . 'processes/main';
    }
    if ($RBAC->userCanAccess('PM_SETUP') == 1) {
      return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . 'setup/main';
    }
    if ($RBAC->userCanAccess('PM_CASES') == 1) {
      return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . 'cases/main';
    }
    if ($RBAC->userCanAccess('PM_USERS') == 1) {
      return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . 'users/users_List';
    }
    if ($RBAC->userCanAccess('PM_DASHBOARD') == 1) {
      return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . 'dashboard/dashboard';
    }
    if ($RBAC->userCanAccess('PM_REPORTS') == 1) {
      return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . 'reports/reportsList';
    }
    return '/sys' .  SYS_SYS . '/' . $sLanguage . '/' . SYS_SKIN . '/' . 'users/myInfo';
  }
} // UsersProperties
