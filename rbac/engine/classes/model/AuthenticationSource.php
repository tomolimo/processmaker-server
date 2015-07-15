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
  	    //fixing the problem where the BaseDN has spaces.  we are removing the spaces.
  	    $baseDn = explode(',', $aFields['AUTH_SOURCE_BASE_DN']);
  	    foreach ($baseDn as $key => $val ) $baseDn[$key] = trim($val);
  	    $aFields['AUTH_SOURCE_BASE_DN'] = implode (',', $baseDn);

        $this->fromArray($aFields, BasePeer::TYPE_FIELDNAME);
        $aFields['AUTH_SOURCE_DATA'] = ($aFields['AUTH_SOURCE_DATA'] != '' ? unserialize($aFields['AUTH_SOURCE_DATA']) : array());
  	    return $aFields;
      }
      else {
        throw(new Exception('This row doesn\'t exist!'));
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

        $authSourceServerName = isset($aData['AUTH_SOURCE_SERVER_NAME']) ? ' - Server Name: '.$aData['AUTH_SOURCE_SERVER_NAME'] : '';
        $authSourcePort = isset($aData['AUTH_SOURCE_PORT']) ? ' - Port: '.$aData['AUTH_SOURCE_PORT'] : '';
        $authSourceEnabledTLS = isset($aData['AUTH_SOURCE_ENABLED_TLS']) ? ' - TLS: '.$aData['AUTH_SOURCE_ENABLED_TLS'] : '';
        $authSourceVersion = isset($aData['AUTH_SOURCE_VERSION']) ? ' - Version: '.$aData['AUTH_SOURCE_VERSION'] : '';
        $authSourceBaseDn = isset($aData['AUTH_SOURCE_BASE_DN']) ? ' - BaseDN: '.$aData['AUTH_SOURCE_BASE_DN'] : '';
        $authAnonymous = $aData['AUTH_ANONYMOUS'] == 1? ' - Anonymous: YES' : ' - Anonymous: NO';
        $authSourceSearchUser = isset($aData['AUTH_SOURCE_SEARCH_USER']) ? ' - Search User: '. $aData['AUTH_SOURCE_SEARCH_USER'] : '';
        $authSourceLdapType = isset($aData['AUTH_SOURCE_DATA']['LDAP_TYPE']) ? ' - LDAP type: '.$aData['AUTH_SOURCE_DATA']['LDAP_TYPE'] : '';
        $authSourceIdentifier = isset($aData['AUTH_SOURCE_DATA']['AUTH_SOURCE_IDENTIFIER_FOR_USER']) ? ' - Identifier: '.$aData['AUTH_SOURCE_DATA']['AUTH_SOURCE_IDENTIFIER_FOR_USER'] : '';
        $authSourceFilter = isset($aData['AUTH_SOURCE_DATA']['AUTH_SOURCE_ADDITIONAL_FILTER']) ? ' - Aditional Filter: '.$aData['AUTH_SOURCE_DATA']['AUTH_SOURCE_ADDITIONAL_FILTER'] : '';

        G::auditLog("CreateAuthSource", "Authentication Source Name: ".$aData['AUTH_SOURCE_NAME']." - Authentication Source ID: (".$aData['AUTH_SOURCE_UID'].") ".$authSourceServerName.$authSourcePort.$authSourceEnabledTLS.$authSourceVersion.$authSourceBaseDn.$authAnonymous.$authSourceSearchUser.$authSourceLdapType.$authSourceIdentifier.$authSourceFilter);

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
    $authSourceServerName = isset($aData['AUTH_SOURCE_SERVER_NAME']) ? ' - Server Name: '.$aData['AUTH_SOURCE_SERVER_NAME'] : '';
    $authSourcePort = isset($aData['AUTH_SOURCE_PORT']) ? ' - Port: '.$aData['AUTH_SOURCE_PORT'] : '';
    $authSourceEnabledTLS = isset($aData['AUTH_SOURCE_ENABLED_TLS']) ? ' - TLS: '.$aData['AUTH_SOURCE_ENABLED_TLS'] : '';
    $authSourceVersion = isset($aData['AUTH_SOURCE_VERSION']) ? ' - Version: '.$aData['AUTH_SOURCE_VERSION'] : '';
    $authSourceBaseDn = isset($aData['AUTH_SOURCE_BASE_DN']) ? ' - BaseDN: '.$aData['AUTH_SOURCE_BASE_DN'] : '';
    $authAnonymous = $aData['AUTH_ANONYMOUS'] == 1? ' - Anonymous: YES' : ' - Anonymous: NO';
    $authSourceSearchUser = isset($aData['AUTH_SOURCE_SEARCH_USER']) ? ' - Search User: '. $aData['AUTH_SOURCE_SEARCH_USER'] : '';
    $authSourceLdapType = isset($aData['AUTH_SOURCE_DATA']['LDAP_TYPE']) ? ' - LDAP type: '.$aData['AUTH_SOURCE_DATA']['LDAP_TYPE'] : '';
    $authSourceIdentifier = isset($aData['AUTH_SOURCE_DATA']['AUTH_SOURCE_IDENTIFIER_FOR_USER']) ? ' - Identifier: '.$aData['AUTH_SOURCE_DATA']['AUTH_SOURCE_IDENTIFIER_FOR_USER'] : '';
    $authSourceFilter = isset($aData['AUTH_SOURCE_DATA']['AUTH_SOURCE_ADDITIONAL_FILTER']) ? ' - Aditional Filter: '.$aData['AUTH_SOURCE_DATA']['AUTH_SOURCE_ADDITIONAL_FILTER'] : '';

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
          G::auditLog("UpdateAuthSource", "Authentication Source Name: ".$aData['AUTH_SOURCE_NAME']." - Authentication Source ID: (".$aData['AUTH_SOURCE_UID'].") ".$authSourceServerName.$authSourcePort.$authSourceEnabledTLS.$authSourceVersion.$authSourceBaseDn.$authAnonymous.$authSourceSearchUser.$authSourceLdapType.$authSourceIdentifier.$authSourceFilter);
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
        throw(new Exception('This row doesn\'t exist!'));
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
      $authenticationSource = $this->load($sUID);

  	  if (!is_null($oAuthenticationSource)) {
  	  	$oConnection->begin();
        $iResult = $oAuthenticationSource->delete();
        $oConnection->commit();

        G::auditLog("DeleteAuthSource", "Authentication Source Name: ".$authenticationSource['AUTH_SOURCE_NAME']." Authentication Source ID: (".$sUID.") ");
        return $iResult;
      }
      else {
        throw(new Exception('This row doesn\'t exist!'));
      }
    }
    catch (Exception $oError) {
    	$oConnection->rollback();
      throw($oError);
    }
  }

  //Added By Enrique Ponce de Leon <enrique@colosa.com>
  //Gets Criteria to fill grid of authentication source
  function getAuthenticationSources($start,$limit,$filter=''){
  	$oCriteria = new Criteria('rbac');
  	$oCriteria->addSelectColumn('COUNT(*) AS CNT');
  	$oCriteria->add(AuthenticationSourcePeer::AUTH_SOURCE_UID,'',Criteria::NOT_EQUAL);
  	if ($filter!=''){
  		$oCriteria->add(AuthenticationSourcePeer::AUTH_SOURCE_NAME,'%'.$filter.'%',Criteria::LIKE);
  	}

    $oCriteria2 = new Criteria('rbac');
  	$oCriteria2->addSelectColumn('*');
  	$oCriteria2->add(AuthenticationSourcePeer::AUTH_SOURCE_UID,'',Criteria::NOT_EQUAL);
  	if ($filter!=''){
  		$oCriteria2->add(AuthenticationSourcePeer::AUTH_SOURCE_NAME,'%'.$filter.'%',Criteria::LIKE);
  	}
  	$oCriteria2->setLimit($limit);
  	$oCriteria2->setOffset($start);

  	$result = array();
  	$result['COUNTER'] = $oCriteria;
  	$result['LIST'] = $oCriteria2;
  	return $result;
  }

  function getAllAuthSourcesByUser(){
    $oCriteria = new Criteria('rbac');
    $oCriteria->addSelectColumn(RbacUsersPeer::USR_UID);
    $oCriteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_NAME);
    $oCriteria->addSelectColumn(AuthenticationSourcePeer::AUTH_SOURCE_PROVIDER);
    $oCriteria->add(RbacUsersPeer::USR_STATUS,0,Criteria::NOT_EQUAL);
    $oCriteria->addJoin(RbacUsersPeer::UID_AUTH_SOURCE, AuthenticationSourcePeer::AUTH_SOURCE_UID, Criteria::INNER_JOIN);

    $oDataset = RbacUsersPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

    $aAuth = array();
    while($oDataset->next()){
      $row = $oDataset->getRow();
      $aAuth[$row['USR_UID']] = $row['AUTH_SOURCE_NAME'].' ('.$row['AUTH_SOURCE_PROVIDER'].')';
    }
    return $aAuth;
  }

} // AuthenticationSource