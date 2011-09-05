<?php
/**
 * databases.php
 *  
 * 
 */

  global $G_ENVIRONMENTS;
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

  switch (DB_ADAPTER) {
  	case 'mysql':
  	  $dsn     .= '?encoding=utf8';
  	  $dsnRbac .= '?encoding=utf8';
  	break;
  	case 'mssql':
  	  //$dsn     .= '?sendStringAsUnicode=false';
  	  //$dsnRbac .= '?sendStringAsUnicode=false';
  	break;
  	default:
  	break;
  }

  $pro ['datasources']['workflow']['connection'] = $dsn;
  $pro ['datasources']['workflow']['adapter'] = DB_ADAPTER;

  $pro ['datasources']['rbac']['connection'] = $dsnRbac;
  $pro ['datasources']['rbac']['adapter'] = DB_ADAPTER;

  $pro ['datasources']['dbarray']['connection'] = 'dbarray://user:pass@localhost/pm_os';
  $pro ['datasources']['dbarray']['adapter']    = 'dbarray';

  return $pro;
?>
