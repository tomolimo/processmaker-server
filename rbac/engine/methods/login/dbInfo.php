<?php
/**
 * dbInfo.php
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
function lookup($target)
{
  global $ntarget;
  $msg = $target . ' => ';
  //if( eregi('[a-zA-Z]', $target) )
  if( preg_match('[a-zA-Z]', $target) ) //Made compatible to PHP 5.3
    $ntarget = gethostbyname($target);
  else
    $ntarget = gethostbyaddr($target);
  $msg .= $ntarget;
  return($msg);
}

  $G_MAIN_MENU = 'rbac.login';
  $G_MENU_SELECTED = 1;
  
  if (file_exists(PATH_METHODS . 'login/version-rbac.php'))
  {
    include('version-rbac.php');
  }
  else {
    define('RBAC_VERSION', 'Development Version');
  }
  
  $dbc = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );
  $ses = new DBSession ($dbc);
  $dset = $ses->execute ('SELECT VERSION() AS VERSION ');
  $row  = $dset->Read();
  
  if (getenv('HTTP_CLIENT_IP')) {
    $ip = getenv('HTTP_CLIENT_IP');
  }
  elseif(getenv('HTTP_X_FORWARDED_FOR')) {
    $ip = getenv('HTTP_X_FORWARDED_FOR');
  } else {
    $ip = getenv('REMOTE_ADDR');
  }
  
    if ( file_exists ( '/etc/redhat-release' ) ) {
      $fnewsize = filesize( '/etc/redhat-release'  );
      $fp = fopen( '/etc/redhat-release' , 'r' );
      $redhat = fread( $fp, $fnewsize );
      fclose( $fp );
    }
  
  $Fields = $dbc->db->dsn;
  $Fields['SYSTEM']          = $redhat;
  $Fields['MYSQL']           = $row['VERSION'];
  $Fields['PHP']             = phpversion();
  $Fields['FLUID']           = RBAC_VERSION;
  $Fields['IP']              = lookup ($ip);
  $Fields['ENVIRONMENT']     = SYS_SYS;
  $Fields['SERVER_SOFTWARE'] = getenv('SERVER_SOFTWARE');
  $Fields['SERVER_NAME']     = getenv('SERVER_NAME');
  $Fields['SERVER_PROTOCOL'] = getenv('SERVER_PROTOCOL');
  $Fields['SERVER_PORT']     = getenv('SERVER_PORT');
  $Fields['REMOTE_HOST']     = getenv('REMOTE_HOST');
  $Fields['SERVER_ADDR']     = getenv('SERVER_ADDR');
  $Fields['HTTP_USER_AGENT'] = getenv('HTTP_USER_AGENT');
  $Fields['a'] = $dbc;
  $G_PUBLISH = new Publisher;
  $G_PUBLISH->SetTo($dbc);
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'rbac/dbInfo', '', $Fields, 'appNew2');
  G::RenderPage( 'publish');
?>