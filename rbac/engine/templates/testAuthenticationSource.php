<?php
/**
 * testAuthenticationSource.php
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
  G::LoadSystem('inputfilter');
  $filter = new InputFilter();
  $HTTP_SESSION_VARS = $filter->xssFilterHard($HTTP_SESSION_VARS);

  global $G_TABLE;
  global $G_CONTENT;
  global $HTTP_SESSION_VARS;

	$tpl = new TemplatePower( PATH_TPL . 'testAuthenticationSource.html' );
	$tpl->prepare();
  $tpl->assign('STYLE_CSS' , (defined('STYLE_CSS') ? STYLE_CSS : ''));
  $tpl->assign('title' , $G_TABLE->title );

  $curAuthSource = $HTTP_SESSION_VARS['CURRENT_AUTH_SOURCE'];
  $dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );

  //crear nueva authentication source
  G::LoadClassRBAC ('authentication');
  $obj = new authenticationSource;
  $obj->SetTo( $dbc );
  $res = $obj->testSource ( $curAuthSource );
//print "<textarea rows=50 cols=60>"; print_r ($res );
//print "</textarea >";

  foreach ( $res as $line ) {
    if ( stristr ($line, 'error' ) !== false ) $line = "<font color='Red'>" . $line . '</font>';
    if ( stristr ($line, 'sucess' ) !== false ) $line = "<font color='Green'>" . $line . '</font>';

	  $tpl->newBlock( "lines" );
    $tpl->assign( "text" , $line );
  }
  $tpl->gotoBlock( "_ROOT" );
	$tpl->printToScreen();
  //die;









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
            $oLdap->message = $filter->xssFilterHard($oLdap->message);
            print ( $oLdap->message);
            return $oResult;
        }
/*
        $aRow = array();
        foreach($oResult->entries() as $oEntry) {
            $aAttr = $oEntry->attributes();
            $aAttr['dn'] = $oEntry->dn();
            $aRow[] = $aAttr;
        }
*/
        print_r ($aRow);
        print "<hr>";
/*
//ahora pedir todos los datos
        //active directory
        $aAttributes = array ("cn", "samaccountname", "givenname", "sn", "userprincipalname", "telephonenumber");
        //ldap
        //$aAttributes = array ("cn", "uid", "givenname", "sn", "mail", "mobile");

        $sFilter = '(objectClass=*)';
        $aParams = array(
            'scope' => 'base',
            'attributes' => $aAttributes,
        );

        $userDn = "CN=Javier,OU=Ventas,DC=colosa,DC=net";
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
        print "<hr>";

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
	  $tpl->newBlock( "headers" );
	  $tpl->assign( "width" , 	( $col["Width"] > 0 ? " style=\"width:" . $col["Width"] . ";\"" : '' ) );

  $iCurRow = 0;
  foreach($oResult->entries() as $oEntry) {
    $aAttr = $oEntry->attributes();
    print_r ($aAttr);
    $aAttr['dn'] = $oEntry->dn();
    $class = ( ++$iCurRow % 2 == 0 ? "Row1" : "Row2" );

    $tpl->newBlock( "row" );
	  $tpl->assign( "class" , $class );
    for ( $ncount=0 ; $ncount < 1 ; $ncount++ )
    {
      $tpl->newBlock( "field" );
      $col = 1;
  	  $tpl->assign( "align" , ( $col <> '' ? " align=\"$col\"" : 'x' ) );
  	  $tpl->assign( "value" , $aAttr['dn'] );
    }
  }

  if( $curpage > 1 ) {
    $firstUrl = SYS_CURRENT_URI . '?order=' . $orcad . '&page=1';
    $prevpage = $curpage - 1;
    $prevUrl  = SYS_CURRENT_URI . '?order=' . $orcad . '&page=' . $prevpage;
    $tpl->assign( "firstUrl" , $firstUrl );
    $tpl->assign( "prevUrl" ,  $prevUrl );
    $first = "<a href=" . $firstUrl . ">&lt;&lt;</a>";
    $prev  = "<a href=" . $prevUrl . ">&lt;</a>";
  }
  else
  {
    $tpl->assign( "firstOff" , 1 );
    $tpl->assign( "prevOff" ,  1 );
  	$first = "&lt;&lt;";
  	$prev  = "&lt;";
  }


  $tpl->gotoBlock( "_ROOT" );
  if ( $totpages !=1 )  $tpl->newBlock( "paging" );
  $tpl->assign( "first" , $first );
  $tpl->assign( "prev" ,  $prev );

  $tpl->assign( "curPage" , '<b>' . G::LoadMessageXml('ID_PAGE') . ' ' . $curpage . ' ' . G::LoadMessageXml('ID_OF') . ' ' . $totpages . '&nbsp' . '</b>');

  if( $curpage < $totpages ) {
    $lastUrl = SYS_CURRENT_URI . '?order=' . $orcad . '&page=' . $totpages;
    $nextpage = $curpage + 1;
    $nextUrl  = SYS_CURRENT_URI . '?order=' . $orcad . '&page=' . $nextpage;
    $next = "<a href=" . $nextUrl . ">></a>";
    $last = "<a href=" . $lastUrl . ">>></a>";
  }
  else
  {
  	$next = ">";
  	$last = ">>";
  }
  $tpl->assign( "next" , $next );
  $tpl->assign( "last" , $last );
  $tpl->assign( "columnCount", 1 );

  if( $totpages > 1 )
  {
    // --------- codigo para colocar numeros de paginas ---------
    $ncount = 1;
    $inicio=$HTTP_SESSION_VARS['COMIENZO'];
    $fin=$HTTP_SESSION_VARS['FINAL'];
     // echo "Inicio:".$inicio." fin".$fin;

    for( $ncount = $inicio; $ncount <= $fin-1; $ncount++ )
    {
     if($ncount>=1 && $ncount<=$totpages)
     {
      if( $ncount <= $totpages ) $pagesEnum .=  " &nbsp ";
      $pagesEnum .= "<a href=\"" . SYS_CURRENT_URI . "?order=".$orcad."&page=" . $ncount . "\">";
      $pagesEnum .= $ncount;
      $pagesEnum .= "</a>";
     }
    }
    $pagesEnum .=" &nbsp ";
  $tpl->assign("pagesEnum", $pagesEnum);
  }

  if ( $lastrow == 0 ) {
    $tpl->gotoBlock( "_ROOT" );
    $tpl->newBlock(  'norecords' );
    $tpl->assign( "noRecordsFound", G::LoadMessageXml('ID_NO_RECORDS_FOUND') );
    $tpl->assign( "columnCount", 1 );
  }

  if (  $lastrow > 25 * 0.66 ) {
    $tpl->gotoBlock( "_ROOT" );
    $tpl->newBlock( "bottomFooter" );
    $tpl->assign( "columnCount", 1 + 1);
    $tpl->assign( "first" , $first );
    $tpl->assign( "prev" ,  $prev );
    $tpl->assign( "next" , $next );
    $tpl->assign( "last" , $last );
    $tpl->assign("pagesEnum", $pagesEnum);
  }
  $tpl->gotoBlock( "_ROOT" );
	$tpl->printToScreen();
  ?>