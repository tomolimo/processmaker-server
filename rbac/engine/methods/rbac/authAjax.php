<?php
/**
 * authAjax.php
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
  G::Loadinclude ('ajax');
  $function   = get_ajax_value ( 'function' );
  $searchText = get_ajax_value ( 'text' );

  $userName   = get_ajax_value ( 'userName' );
  $authSource = get_ajax_value ( 'authSource' );
  $dn         = get_ajax_value ( 'dn' );
  $fullname   = get_ajax_value ( 'fullname' );
  $email      = get_ajax_value ( 'email' );
  $roles      = get_ajax_value ( 'roles' );

  switch ( $function ) {
    case 'searchText' :  searchText( $searchText );
         break;
    case 'validUser'  :  validUser( $searchText );
         break;
    case 'createUser'  :  createUser( $userName, $authSource, $dn, $fullname, $email, $roles );
         break;
  }

function createUser( $userName, $authSource, $dn, $fullname, $email, $roles ){
  global $DB_MODULE; // :(
  G::LoadClassRBAC ('user');
  $dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );
  $obj = new RBAC_User;
  $obj->SetTo( $dbc );

  //is ProcessMaker?
  $isPM = false;
  foreach ( $DB_MODULE as $index => $module ) {	if ( $module = 'ProcessMaker' ) $isPM = true; }
  if ( $isPM ) {
    $dbcPM = new DBConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME );
    $sesPM = new DBSession ( $dbcPM );
  }

  $res = $obj->UserNameRepetido( 0, $userName );
  if ( $res != 0 ) {
    $result = "-1";
  }
  else {
    $aux = explode( ' ', $fullname );
    $last = str_replace ( $aux[0] . ' ', '', $fullname );
    $uid = $obj->createUser ( $last, /*mid*/'', $aux[0], $email);
    $result = $obj->createUserName ($uid, $userName, 'LDAP');
    $obj->updateLDAP ( $uid, $authSource, $dn );
    $rol = explode ( ',', $roles );
    foreach ( $rol as  $key => $val ) {
      if ( $val != 0 )
        $obj->assignUserRole ( $uid, $val );
    }

    if ($isPM ) {
      $sql = "insert into USER (UID, USR_USER_NAME, USR_LASTNAME, USR_MIDNAME, USR_FIRSTNAME, USR_EMAIL) VALUES ( " .
      $uid . ", '" . $userName . "', '" . $last . "','', '". $aux[0] . "', '". $email. "' ) ";
     //$f =fopen ( '/shared/security.log', "a+" );fwrite ( $f, date("Y-m-d h:i:s") . "$isPM $sql \n" );  fclose ($f);
       $sesPM->Execute ( $sql );
    }
  }
  header("Content-Type: text/xml");
  print '<?xml version="1.0" encoding="UTF-8"?>';
  print '<data>';
  print "<value>$result</value>";
  print '</data>';
}

function validUser ( $userid) {
  global $RBAC;
  $res = $RBAC->UserNameRepetido( 0, $userid );

  header("Content-Type: text/xml");
  print '<?xml version="1.0" encoding="UTF-8"?>';
  print '<data>';
  print "<value>$res</value>";
  print '</data>';
/*
  if ( $res == 0 ) {
    print "<font color='green'>valid user</font>";
  }
  else {
    print "<font color='red'>user already used</font>";
  }
  */
}

function searchText ( $searchText) {
  global $_SESSION;
  global $RBAC;
  $curAuthSource = $_SESSION['CURRENT_AUTH_SOURCE'];
  $dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );

	$tpl = new TemplatePower( PATH_TPL . 'authListUsers.html' );
	$tpl->prepare();
	
  $tpl->assign( "STYLE_CSS" , ( defined ('STYLE_CSS') ? STYLE_CSS : 'simple' ) );
  $tpl->assign( "title" , 'Search Results' );

  //authentication source
  G::LoadClassRBAC ('authentication');
  $obj = new authenticationSource;
  $obj->SetTo( $dbc );
  $data = $obj->searchUsers ( $curAuthSource , $searchText);
  if ( $data['codError'] == 0 && isset ( $data['rows'] ) && !is_array( $data['rows'] ) ) {
	  $tpl->newBlock( "empty-rows" );
    $tpl->assign( "title" , 'Search Results' );
    $tpl->assign( "empty-rows-text" , 'no records found' );
  	$tpl->printToScreen();
  	die;
  }
  if ( $data['codError'] != 0 ) {
	  $tpl->newBlock( "error" );
    $tpl->assign( "title" , 'Log Error' );
    $tpl->assign( "text" , "<font color='Red'>" . $data['rows']  . "</font>" ) ;
  	$tpl->printToScreen();
    die;
  }

  //build the options to create...an user, list the applications and their roles..
  $ses = new DBSession ( $dbc );
  $ses2 = new DBSession ( $dbc );
  $dset = $ses->execute ( "SELECT * from APPLICATION where APP_CODE != 'RBAC' " );
  $appRow = $dset->Read();
  $checkboxs = "<br><input type='checkbox' @@disabled name='C-@@Y' id='C-@@Y' onclick='emptyDropdowns( \"@@X\");' ><input type='hidden' name='H-@@Y' id='H-@@Y' value='@@X' ><input type='hidden' name='E-@@Y' id='E-@@Y' value='' >";
  $inputs    = "<div id='D-@@Y'>@@validuser</div><input type='text'  name='T-@@Y' id='T-@@Y' value=\"@@Z\" onchange='verifyUserName ( this, \"@@Y\" );' size='14' maxlength='16'>&nbsp;";
  $options = "<table cellpadding='0' cellspacing='0'><tr>" ;
  $options .= "";
  while ( is_array ( $appRow ) ) {
    $appid = $appRow['UID'];
    $selectId = 'S-@@Y-'. $appid;
    $dset2 = $ses2->execute ( 'SELECT * from ROLE where ROL_APPLICATION = ' . $appid );
    $rolRow = $dset2->Read();
    $selectEnabled = false;
    if ( is_array ( $rolRow ) ) {
      $options .= '<td>' . $appRow['APP_CODE'] . '<br>';
      $options .= "<select name='$selectId' id='$selectId' @@disabled onchange='flipCheckbox(  \"@@Y\");' >\n";
      $options .= "<option value='0'>select a Role</option>";
      $selectEnabled = true;
    }
    while ( is_array ( $rolRow ) ) {
      $options .= "<option value='" . $rolRow['UID'] . "'>" . $rolRow['ROL_CODE'] . "</option>";
      $rolRow = $dset2->Read();
    }
    if ( $selectEnabled ) {
      $options .= "</select>\n<td>";
    }

    $appRow = $dset->Read();
  }
  $options .= '</tr></table>';

  $i = 0;
  if ( isset ( $data['rows'] ) ) 
  foreach ( $data['rows'] as $row ) {

    $tpl->newBlock( "users" );
    if ( ++$i % 2 == 0 )
      $tpl->assign( "class" , 'Row2' );
    else
      $tpl->assign( "class" , 'Row1' );
    $tpl->assign( "index" , $i );
    $tpl->assign( "dn" , $row['dn'] );
    $tpl->assign( "name" ,     $row['attr']['givenName'] );
    $tpl->assign( "lastname" , $row['attr']['sn'] );
    $tpl->assign( "fullname" , $row['attr']['cn'] );
    $tpl->assign( "email" ,    $row['attr']['mail'] );
    $tpl->assign( "uid" ,      $row['attr']['uid'] );

    //verificar uid
    $userid = $row['attr']['uid'];
    $res = $RBAC->UserNameRepetido( 0, $userid );


    $input    = str_replace ( '@@Z', $userid,  str_replace ( '@@X', $row['dn'], str_replace ( '@@Y', $i, $inputs) ) );
    $input    = str_replace ( '@@validuser', ( $res == 0 ? "<font color='green'>valid user</font>" : "<font color='red'>user already used</font>"), $input );
    $checkbox = str_replace ( '@@X', $row['dn'], str_replace ( '@@Y', $i, $checkboxs) );
    $checkbox = str_replace ( '@@disabled', ( $res <> 0 ? 'disabled' : ''), $checkbox );
    $option   = str_replace ( '@@X', $row['dn'], str_replace ( '@@Y', $i, $options) );
//    $option   = str_replace ( '@@disabled', ( $res <> 0 ? 'disabled' : ''), $option );
    $tpl->assign( "checkbox" , $checkbox );
    $tpl->assign( "input" ,    $input );
    $tpl->assign( "options" ,  $option );
  }
  $tpl->gotoBlock( "_ROOT" );
  $tpl->newBlock( "start-users" );
  $tpl->assign( "dummy" ,    'dummy' );

  $tpl->gotoBlock( "_ROOT" );
  $tpl->newBlock( "end-users" );
  $tpl->assign( "dummy" ,    'dummy' );

	$tpl->printToScreen();

}


?>