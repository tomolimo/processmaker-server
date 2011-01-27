<?php
/**
 *  AuthenticationSource.php
 *  @package  rbac-classes-model
 * Skeleton subclass for representing a row from the 'AUTHENTICATION_SOURCE' table.
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 * 
 */

 /**
  * access public
  */
require_once 'classes/model/om/BaseAuthenticationSource.php';

 /**
  * @package  rbac-classes-model
  */

class AuthenticationSource extends BaseAuthenticationSource {
  function getAllAuthSources() {
    $oCriteria = new Criteria('rbac');
    $oCriteria->addSelectColumn('*');
    $oCriteria->add(AuthenticationSourcePeer::AUTH_SOURCE_UID, '', Criteria::NOT_EQUAL);
    return $oCriteria;
  }
  
  public function load($sUID) {
  	try {
  	  $oAuthenticationSource = AuthenticationSourcePeer::retrieveByPK($sUID);
  	  if (!is_null($oAuthenticationSource)) {
  	    $aFields = $oAuthenticationSource->toArray(BasePeer::TYPE_FIELDNAME);
        $this->fromArray($aFields, BasePeer::TYPE_FIELDNAME);
        $aFields['AUTH_SOURCE_DATA'] = ($aFields['AUTH_SOURCE_DATA'] != '' ? unserialize($aFields['AUTH_SOURCE_DATA']) : array());
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

  function create($aData) {
    if (!isset($aData['AUTH_SOURCE_UID'])) {
      $aData['AUTH_SOURCE_UID'] = G::generateUniqueID();
    }
    else {
      if ($aData['AUTH_SOURCE_UID'] == '') {
        $aData['AUTH_SOURCE_UID'] = G::generateUniqueID();
      }
    }
    $aData['AUTH_SOURCE_DATA'] = (is_array($aData['AUTH_SOURCE_DATA']) ? serialize($aData['AUTH_SOURCE_DATA']) : $aData['AUTH_SOURCE_DATA']);
    $oConnection = Propel::getConnection(AuthenticationSourcePeer::DATABASE_NAME);
  	try {
  	  $oAuthenticationSource = new AuthenticationSource();
  	  $oAuthenticationSource->fromArray($aData, BasePeer::TYPE_FIELDNAME);
  	  if ($oAuthenticationSource->validate()) {
        $oConnection->begin();
        $iResult = $oAuthenticationSource->save();
        $oConnection->commit();
        return $aData['AUTH_SOURCE_UID'];
  	  }
  	  else {
  	  	$sMessage = '';
  	    $aValidationFailures = $oAuthenticationSource->getValidationFailures();
  	    foreach($aValidationFailures as $oValidationFailure) {
          $sMessage .= $oValidationFailure->getMessage() . '<br />';
        }
        throw(new Exception('The registry cannot be created!<br />' . $sMessage));
  	  }
  	}
    catch (Exception $oError) {
      $oConnection->rollback();
    	throw($oError);
    }
  }

  function update($aData) {
    $aData['AUTH_SOURCE_DATA'] = (is_array($aData['AUTH_SOURCE_DATA']) ? serialize($aData['AUTH_SOURCE_DATA']) : $aData['AUTH_SOURCE_DATA']);
    $oConnection = Propel::getConnection(AuthenticationSourcePeer::DATABASE_NAME);
  	try {
  	  $oAuthenticationSource = AuthenticationSourcePeer::retrieveByPK($aData['AUTH_SOURCE_UID']);
  	  if (!is_null($oAuthenticationSource)) {
  	  	$oAuthenticationSource->fromArray($aData, BasePeer::TYPE_FIELDNAME);
  	    if ($oAuthenticationSource->validate()) {
  	    	$oConnection->begin();
          $iResult = $oAuthenticationSource->save();
          $oConnection->commit();
          return $iResult;
  	    }
  	    else {
  	    	$sMessage = '';
  	      $aValidationFailures = $oAuthenticationSource->getValidationFailures();
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

  /**
   * Function remove
   * access public
   */
  function remove($sUID) {
    $oConnection = Propel::getConnection(AuthenticationSourcePeer::DATABASE_NAME);
  	try {
  	  $oAuthenticationSource = AuthenticationSourcePeer::retrieveByPK($sUID);
  	  if (!is_null($oAuthenticationSource)) {
  	  	$oConnection->begin();
        $iResult = $oAuthenticationSource->delete();
        $oConnection->commit();
        return $iResult;
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
} // AuthenticationSource