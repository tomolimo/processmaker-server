<?php
/**
 * dbInfo.php
 *  
 * {projectName}
 * 
 */
function lookup($target)
{
  global $ntarget;
  $msg = $target . ' => ';
  //if( eregi('[a-zA-Z]', $target) )
  if( preg_match('[a-zA-Z]', $target)) //Made compatible to PHP 5.3
    $ntarget = gethostbyname($target);
  else
    $ntarget = gethostbyaddr($target);
  $msg .= $ntarget;
  return($msg);
}

	function getDbServicesAvailables()
	{ 
		$servicesAvailables = Array();
		
		$dbServices = Array(
			'mysql' => Array(
                'id'        => 'mysql', 
                'command'   => 'mysql_connect',
                'name'      => 'MySql'
            ),
			'pgsql' => Array(
                 'id'       => 'pgsql',
                 'command'  => 'pg_connect',
                 'name'     => 'PostgreSQL'
            ),
			'mssql' => Array(
                  'id'      => 'mssql',
                  'command' => 'mssql_connect',
                  'name'    => 'Microsoft SQL Server'),
			'oracle'=> Array(
                'id'        => 'oracle',
                'command'   => 'oci_connect',
                'name'      => 'Oracle'
            ),
			'informix'=> Array(
                'id'        => 'informix',
                'command'   => 'ifx_connect',
                'name'      => 'Informix'
            ),
			'sqlite' => Array(
                'id'        => 'sqlite',
                'command'   => 'sqlite_open',
                'name'      => 'SQLite'
            )
		);
			
		foreach($dbServices as $service) {
			if(@function_exists($service['command'])){
				$servicesAvailables[] = $service;
			}
		}
		return $servicesAvailables;
	}

  function getDbServerVersion($driver)  {
    try{
      switch($driver)
      {
        case 'mysql':
            if($link = @mysql_connect( DB_HOST, DB_USER, DB_PASS)){
                $v = @mysql_get_server_info();
            } else {
                throw new Exception(@mysql_error($link));
            }
            break;
      }
      return (isset($v))?$v:'none';
    } catch (Exception $e){
        return ($e->getMessage());
    }
  }

  if (file_exists(PATH_METHODS . 'login/version-{projectName}.php'))
  {
    include('version-{projectName}.php');
  }
  else {
    define('PRG_VERSION', 'Development Version');
  }

  if (getenv('HTTP_CLIENT_IP')) {
    $ip = getenv('HTTP_CLIENT_IP');
  }
  elseif(getenv('HTTP_X_FORWARDED_FOR')) {
    $ip = getenv('HTTP_X_FORWARDED_FOR');
  } else {
    $ip = getenv('REMOTE_ADDR');
  }

    $redhat = '';
    if ( file_exists ( '/etc/redhat-release' ) ) {
      $fnewsize = filesize( '/etc/redhat-release'  );
      $fp = fopen( '/etc/redhat-release' , 'r' );
      $redhat = fread( $fp, $fnewsize );
      fclose( $fp );
    }
    
    $redhat .= " (" . PHP_OS . ")";

  //$dbNetView = new NET(DB_HOST);
  //$dbNetView->loginDbServer(DB_USER, DB_PASS);
  
  $availdb = '';
  foreach ( getDbServicesAvailables()  as $key => $val ) {
    if ( $availdb != '' ) $availdb .= ', ';
    $availdb .= $val['name'];
  }

  $Fields['SYSTEM']          = $redhat;
  $Fields['DATABASE']        = 'MySql (Version ' . getDbServerVersion('mysql') .')';
  $Fields['DATABASE_SERVER'] = DB_HOST;
  $Fields['DATABASE_NAME']   = DB_NAME;
  $Fields['PHP']             = phpversion();
  $Fields['FLUID']           = PRG_VERSION;
  $Fields['IP']              = lookup ($ip);
  $Fields['ENVIRONMENT']     = SYS_SYS;
  $Fields['SERVER_SOFTWARE'] = getenv('SERVER_SOFTWARE');
  $Fields['SERVER_NAME']     = getenv('SERVER_NAME');
  $Fields['AVAILABLE_DB']    = $availdb;
  $Fields['SERVER_PROTOCOL'] = getenv('SERVER_PROTOCOL');
  $Fields['SERVER_PORT']     = getenv('SERVER_PORT');
  $Fields['REMOTE_HOST']     = getenv('REMOTE_HOST');
  $Fields['SERVER_ADDR']     = getenv('SERVER_ADDR');
  $Fields['HTTP_USER_AGENT'] = getenv('HTTP_USER_AGENT');

  $G_PUBLISH = new Publisher;
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/dbInfo', '', $Fields);
  G::RenderPage('publish', 'raw');
