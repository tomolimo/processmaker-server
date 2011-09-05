<?php
/**
 * databases.php
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

  global $G_ENVIRONMENTS;
  if(defined("G_ENVIRONMENT")){ //If we don't have G_ENVIRONMENT defined the only enable dbArray
//var_dump($G_ENVIRONMENTS[G_ENVIRONMENT]);die;
  if ( isset ( $G_ENVIRONMENTS ) ) {
    $dbfile = $G_ENVIRONMENTS[ G_ENVIRONMENT ][ 'dbfile'];
    if ( !file_exists ( $dbfile ) ) {
      printf("%s \n", pakeColor::colorize( "dbfile $dbfile doesn't exist for environment " . G_ENVIRONMENT  , 'ERROR'));
      die();
    }
    require_once ( $dbfile );
  }
  else {
    //when this file is called from sysGeneric, the $G_ENVIRONMENTS DOES NOT EXIST, BUT DB_HOST is defined
    if ( !defined ( 'DB_HOST' ) ) {
      printf("%s \n", pakeColor::colorize( "dbfile $dbfile doesn't exist for environment " . G_ENVIRONMENT  , 'ERROR'));
      die();
    }

  }
    //to do: enable for other databases
  $dbType = DB_ADAPTER;

  $dsn     = DB_ADAPTER . '://' .  DB_USER . ':' . DB_PASS . '@' . DB_HOST . '/' . DB_NAME;

  //to do: enable a mechanism to select RBAC Database
  $dsnRbac = DB_ADAPTER . '://' .  DB_RBAC_USER . ':' . DB_RBAC_PASS . '@' . DB_RBAC_HOST . '/' . DB_RBAC_NAME;

  //to do: enable a mechanism to select report Database
  $dsnReport = DB_ADAPTER . '://' .  DB_REPORT_USER . ':' . DB_REPORT_PASS . '@' . DB_REPORT_HOST . '/' . DB_REPORT_NAME;

  switch (DB_ADAPTER) {
  	case 'mysql':
  	  $dsn       .= '?encoding=utf8';
  	  $dsnRbac   .= '?encoding=utf8';
  	  $dsnReport .= '?encoding=utf8';
  	break;
  	case 'mssql':
  	  //$dsn       .= '?sendStringAsUnicode=false';
  	  //$dsnRbac   .= '?sendStringAsUnicode=false';
  	  //$dsnReport .= '?sendStringAsUnicode=false';
  	break;
  	default:
  	break;
  }

  $pro ['datasources']['workflow']['connection'] = $dsn;
  $pro ['datasources']['workflow']['adapter'] = DB_ADAPTER;

  $pro ['datasources']['rbac']['connection'] = $dsnRbac;
  $pro ['datasources']['rbac']['adapter'] = DB_ADAPTER;

  $pro ['datasources']['rp']['connection'] = $dsnReport;
  $pro ['datasources']['rp']['adapter'] = DB_ADAPTER;
}
  $pro ['datasources']['dbarray']['connection'] = 'dbarray://user:pass@localhost/pm_os';
  $pro ['datasources']['dbarray']['adapter']    = 'dbarray';

  return $pro;
?>
