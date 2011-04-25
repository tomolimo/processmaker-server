<?php
/**
 * testAuthenticationUser.php
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

  $strPass = $_POST['form'][PASS];

  if ( $strPass == '' ) 
    return;

  $userId   = $_SESSION['CURRENT_USER'];

	$tpl = new TemplatePower( PATH_TPL . 'testAuthenticationSource.html' );
	$tpl->prepare();
  $tpl->assign( "STYLE_CSS" , STYLE_CSS );
  $tpl->assign( "title" , $G_TABLE->title );

  $curAuthSource = $HTTP_SESSION_VARS['CURRENT_AUTH_SOURCE'];
  $dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );

  //Class user
  G::LoadClassRBAC ("user");
  $user = new RBAC_User;
  $user->SetTo ($dbc);
  $user->Load ($userId);

  //crear nueva authentication source
  G::LoadClassRBAC ('authentication');
  $obj = new authenticationSource;
  $obj->SetTo( $dbc );
  $res = $obj->verifyPassword ( $userId, $user->Fields['USR_LDAP_DN'] , $strPass,  $user->Fields['USR_LDAP_SOURCE'] );

//print "<textarea rows=10 cols=60>"; print_r ($obj->vlog );
//print "</textarea >"; 

  foreach ( $obj->vlog as $line ) {
    if ( stristr ($line, 'error' ) !== false ) $line = "<font color='Red'>" . $line . '</font>';
    if ( stristr ($line, 'sucess' ) !== false ) $line = "<font color='Green'>" . $line . '</font>';

	  $tpl->newBlock( "lines" );
    $tpl->assign( "text" , $line );
  }
  $tpl->gotoBlock( "_ROOT" );
	$tpl->printToScreen();

?>