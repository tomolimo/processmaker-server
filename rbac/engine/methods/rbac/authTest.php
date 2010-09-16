<?php
/**
 * authTest.php
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
$G_MAIN_MENU         = 'rbac';
$G_SUB_MENU          = 'rbac.authSource';
$G_BACK_PAGE         = 'rbac/authenticationList.html';
$G_MENU_SELECTED     = 2;
$G_SUB_MENU_SELECTED = 1;

$appid = isset($_GET[0])?$_GET[0]:'';//$URI_VARS[0];
if ($appid == '' && $_SESSION['CURRENT_AUTH_SOURCE'] != '')
{
  $appid = $_SESSION['CURRENT_AUTH_SOURCE'];
}

$_SESSION['CURRENT_AUTH_SOURCE'] = $appid;
$dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );

G::LoadClassRBAC('authentication');
$obj = new authenticationSource;
$obj->SetTo($dbc);
$obj->Load($appid);

$G_PUBLISH = new Publisher;
$G_PUBLISH->SetTo ($dbc);
$G_PUBLISH->AddContent('view', 'testAuthenticationSource');
G::RenderPage( 'publish');

/*
        require_once('Net/LDAP.php');
        $rootDn = 'OU=Ventas,DC=colosa,DC=net';
        $config = array(
            'dn' => 'scout@colosa.net',
            'password' => 'Colosa1',
            'host' => '192.168.0.50',
            'base' => $rootDn,
            'options' => array('LDAP_OPT_REFERRALS' => 0),
            'tls' => false,
            'port'=> 389
        );

        $oLdap =& Net_LDAP::connect($config);
        if (PEAR::isError($oLdap)) {
            print ( $oLdap->message);
            return $oLdap;
        }


        $sFilter = '(&(|(objectClass=user)(objectClass=inetOrgPerson)(objectClass=posixAccount))(|(cn=*a*)(mail=*a*)(sAMAccountName=*a*)))';
        $aParams = array(
            'scope' => 'sub',
            'attributes' => array('cn', 'dn', 'samaccountname'),
        );

        $oResult = $oLdap->search($rootDn, $sFilter, $aParams);
        if (PEAR::isError($oResult)) {
        print ( $oLdap->message);
            return $oResult;
        }
        $aRet = array();
        foreach($oResult->entries() as $oEntry) {
            $aAttr = $oEntry->attributes();
            $aAttr['dn'] = $oEntry->dn();
            $aRet[] = $aAttr;
        }

        print_r ($aRet);
        print '<hr>';

//ahora pedir todos los datos
        //active directory
        $aAttributes = array ('cn', 'samaccountname', 'givenname', 'sn', 'userprincipalname', 'telephonenumber');
        //ldap
        //$aAttributes = array ('cn', 'uid', 'givenname', 'sn', 'mail', 'mobile');

        $sFilter = '(objectClass=*)';
        $aParams = array(
            'scope' => 'base',
            'attributes' => $aAttributes,
        );

        $userDn = 'CN=Javier,OU=Ventas,DC=colosa,DC=net';
        $oResult = $oLdap->search($userDn, $sFilter, $aParams);
        if (PEAR::isError($oResult)) {
            print ( $oLdap->message);
            return $oResult;
        }
        $aRet = array();
        foreach($oResult->entries() as $oEntry) {
            $aAttr = $oEntry->attributes();
            $aAttr['dn'] = $oEntry->dn();
            $aRet[] = $aAttr;
        }
        print_r ($aRet);
        print '<hr>';

        $oLdap =& Net_LDAP::connect($config);
        if (PEAR::isError($oLdap)) {
            print ( $oLdap->message);
            return $oLdap;
        }
       $res = $oLdap->reBind('scout@colosa.net', 'Colosa1');

        if (PEAR::isError($res)) {
            print ( $res->message);
            return $res;
        }
        if ($res === true) {
            print 'ok';
        }
*/
?>