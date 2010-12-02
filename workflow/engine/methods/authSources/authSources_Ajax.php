<?php
/**
 * authSources_Ajax.php
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
  if ($RBAC->userCanAccess('PM_SETUP_ADVANCE') != 1) {
    G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
    G::header('location: ../login/login');
    die;
  }

  switch ($_POST['action']) {
    case 'searchUsers':
      G::LoadThirdParty('pear/json','class.json');
      require_once 'classes/model/Users.php';
      $oJSON    = new Services_JSON();
      $i        = 0;
      $oUser    = new Users();
      $aAux     = $RBAC->searchUsers($_POST['sUID'], $_POST['sKeyword']);
      $aUsers   = array();
      // note added by gustavo cruz gustavo-at-colosa.com
      // changed the user data showed to accept FirstName and LastName variables
      $aUsers[] = array('Checkbox' => 'char',
                        'Username' => 'char',
                        'FullName' => 'char',
                        'FirstName' => 'char',
                        'LastName' => 'char',
                        'Email' => 'char',
                        'DistinguishedName' => 'char');
      foreach ($aAux as $aUser) {
        if (UsersPeer::doCount($oUser->loadByUsername($aUser['sUsername'])) == 0) {
          // add replace to change D'Souza to D*Souza by krlos
          $sCheckbox = '<input type="checkbox" name="aUsers[' . $i . ']" id="aUsers[' . $i . ']" value=\'' . str_replace( "\'","*", addslashes($oJSON->encode($aUser)) ) . '\' />';
          $i++;
        }
        else {
          $sCheckbox = G::LoadTranslation('ID_USER_REGISTERED') . ':<br />(' . $aUser['sUsername'] . ')';
        }
        // note added by gustavo cruz gustavo-at-colosa.com
        // assign the user data to the DBArray variable.
        $aUsers[] = array('Checkbox' => $sCheckbox,
                          'Username' => $aUser['sUsername'],
                          'FullName' => $aUser['sFullname'],
                          'FirstName' => $aUser['sFirstname'],
                          'LastName' => $aUser['sLastname'],
                          'Email' => $aUser['sEmail'],
                          'DistinguishedName' => $aUser['sDN']); 
      }
      global $_DBArray;
      $_DBArray['users']    = $aUsers;
      $_SESSION['_DBArray'] = $_DBArray;
      G::LoadClass('ArrayPeer');
      $oCriteria = new Criteria('dbarray');
      $oCriteria->setDBArrayTable('users');

      $aFields = $RBAC->getAuthSource($_POST['sUID']);

      global $G_PUBLISH;
      $G_PUBLISH = new Publisher();
      if ($aFields['AUTH_SOURCE_PROVIDER'] != 'ldap') {
        $G_PUBLISH->AddContent('propeltable', 'paged-table', 'authSources/ldapSearchResults', $oCriteria);
      }
      else {
        if (file_exists(PATH_XMLFORM . 'authSources/' . $aFields['AUTH_SOURCE_PROVIDER'] . 'Edit.xml')) {
          $G_PUBLISH->AddContent('propeltable', 'paged-table', 'authSources/' . $aFields['AUTH_SOURCE_PROVIDER'] . 'SearchResults', $oCriteria);
        }
        else {
          $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', array('MESSAGE' => 'File: ' . $aFields['AUTH_SOURCE_PROVIDER'] . 'SearchResults.xml' . ' not exists.'));
        }
      }
      G::RenderPage('publish', 'raw');
    break;
  }
}
catch ( Exception  $e ) {
	$fields = array('MESSAGE' => $e->getMessage() );
  global $G_PUBLISH;
  $G_PUBLISH = new Publisher();
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $fields );
  G::RenderPage('publish', 'blank');
}
