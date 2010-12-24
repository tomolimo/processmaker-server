<?php
/**
 * class.Installer_mssql.php
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
//
// It works with the table CONFIGURATION in a WF dataBase
//
// Copyright (C) 2007 COLOSA
//
// License: LGPL, see LICENSE
////////////////////////////////////////////////////

/**
 * Processmaker Installer_mssql
 * @package ProcessMaker
 * @author 
 * @copyright 2008 COLOSA
 */
class Installer
{
  public  $options = Array();
  public  $result  = Array();
  public  $error   = Array();
  public  $report  = Array();
  private $connection_database;
  
  public  $server   = '';
  public  $port     = '1433';
  public  $userName = '';
  public  $password = '';
  
  /**
   * construct of insert
   *
   * @param  string    $pPRO_UID  
   * @return void
   */  
  function __construct()
  {
  }
  
  function setServerData($psServer, $psPort, $psUserName, $psPassword )
  {
    $this->server   = $psServer;
    $this->port     = $psPort;
    $this->userName = $psUserName;
    $this->password = $psPassword;
  }
  
  /**
   * create_site
   *
   * @param  array    $config
   * @param  boolean  $confirmed
   * @return void
   */  
  public function create_site($config = Array(), $confirmed = false)
  {
    $this->options=G::array_concat(Array(
      'isset'=>false,
      'password'  =>G::generate_password(12),
      'path_data' =>@PATH_DATA,
      'path_compiled' =>@PATH_C,
      'name'=>$config['name'],
      'database'=>Array(),
      'admin'=>Array('username'=>'admin','password'=>'admin'),
      'advanced'=>Array(
        'ao_db_wf'=>'wf_'.$config['name'],
        'ao_db_rb'=>'rb_'.$config['name'],
        'ao_db_rp'=>'rp_'.$config['name'],
        'ao_db_drop'=>false
      )
    ),$config);
    $a = @explode(SYSTEM_HASH,G::decrypt(HASH_INSTALLATION,SYSTEM_HASH));
    $this->options['database'] = G::array_concat(Array(
      'username'=>@$a[1],
      'password'=>@$a[2],
      'hostname'=>@$a[0]
    ),$this->options['database']);
    return ($confirmed===true)?$this->make_site():$this->create_site_test();
  }
  
  /**
   * isset_site
   *
   * @param  string   $name  Default value "workflow"
   * @return string   file_exists(PATH_DATA."sites/".$name);
   */  
  public function isset_site($name = "workflow")
  {
    return file_exists(PATH_DATA."sites/".$name);
  }

  /**
   * create_site_test
   *
   * @return void 
   */  
  private function create_site_test()
  {
    $name = (preg_match('/^[\w]+$/i',trim($this->options['name'])))?true:false;
    $result=Array(
      'path_data' =>$this->is_dir_writable($this->options['path_data']),
      'path_compiled' =>$this->is_dir_writable($this->options['path_compiled']),
      'database'  =>$this->check_connection(),
      'access_level'=>$this->cc_status,
      'isset'=>($this->options['isset']==true)?$this->isset_site($this->options['name']):false,
      'microtime'=>microtime(),
      'workspace'=>$this->options['name'],
      'name'=>array(
        'status'=>$name,
        'message'=>($name)?'PASSED':'Workspace name invalid'
      ),
      'admin'=>array(
          'username'=>(preg_match('/^[\w@\.-]+$/i',trim($this->options['admin']['username'])))?true:false,
          'password'=>((trim($this->options['admin']['password'])=='')?false:true)
        )
    );
    $result['name']['message'] = ($result['isset'])?'Workspace already exists':$result['name']['message'];
    $result['name']['status']  = ($result['isset'])?false:$result['name']['status'];
    //g::pr($result);
    return Array(
        'created'=>G::var_compare(
        true,
        $result['path_data'],
        $result['database']['connection'],
        $result['name']['status'],
        $result['database']['version'],
        $result['database']['ao']['ao_db_wf']['status'],
        $result['database']['ao']['ao_db_rb']['status'],
        $result['database']['ao']['ao_db_rp']['status'],
        $result['admin']['username'],
        (($result['isset'])?false:true),
        $result['admin']['password']),
        'result'=>$result
    );
  }
  
  /**
   * make_site
   *
   * @return array  $test
   */  
  private function make_site()
  {
    $test = $this->create_site_test();
    if($test['created']===true)
    {
      $local = Array('localhost','127.0.0.1');
      $this->wf_site_name = $wf = $this->options['advanced']['ao_db_wf'];
      $this->rbac_site_name   = $rb = $this->options['advanced']['ao_db_rb'];
      $this->report_site_name = $rp = $this->options['advanced']['ao_db_rp'];

      $schema = "schema.sql";
      $values = "insert.sql";   

      if($this->options['advanced']['ao_db_drop']===true) {
        /* Create databases & users  */
        $q  = "IF NOT EXISTS (SELECT * FROM sys.databases WHERE name='" . $wf . "' ) DROP DATABASE " . $wf;
        $ac = @mssql_query($q,$this->connection_database);
        $this->log($q.": => ".((!$ac)? "Error drop table " . $wf : "OK")."\n");

        $q  = "IF NOT EXISTS (SELECT * FROM sys.databases WHERE name='" . $rb . "' ) DROP DATABASE " . $rb;
        $ac = @mssql_query($q,$this->connection_database);
        $this->log($q.": => ".((!$ac)? "Error drop table " . $rb : "OK")."\n");

        $q  = "IF NOT EXISTS (SELECT * FROM sys.databases WHERE name='" . $rp . "' ) DROP DATABASE " . $rp;
        $ac = @mssql_query($q,$this->connection_database);
        $this->log($q.": => ".((!$ac)? "Error drop table " . $rp : "OK")."\n");
      }

      $q  = "IF NOT EXISTS (SELECT * FROM sys.databases WHERE name='" . $wf . "' ) CREATE DATABASE ".$wf." ";
      $ac = @mssql_query($q,$this->connection_database);
      $this->log($q.": => ".((!$ac)? "Error creating table" .$wf  : "OK")."\n");

      $q  = "IF NOT EXISTS (SELECT * FROM sys.databases WHERE name='" . $rb . "' ) CREATE DATABASE ".$rb." ";
      $ac = @mssql_query($q,$this->connection_database);
      $this->log($q.": => ".((!$ac)? "Error creating table" .$rb : "OK")."\n");

      /* report DB begin */

      $q  = "IF NOT EXISTS (SELECT * FROM sys.databases WHERE name='" . $rp . "' ) CREATE DATABASE ".$rp." ";
      $ac = @mssql_query($q,$this->connection_database);
      $this->log($q.": => ".((!$ac)? "Error creating table" .$rp : "OK")."\n");

      /* report DB end */

      //$priv_wf = "GRANT ALL PRIVILEGES ON `".$wf.".* TO ".$wf."@`".$this->options['database']['hostname']."` IDENTIFIED BY '".$this->options['password']."' WITH GRANT OPTION";
      if($this->cc_status==1) {
      	
        $this->setGrantPrivileges($wf, $this->options['password']);
        $this->setGrantPrivileges($rb, $this->options['password']);
        $this->setGrantPrivileges($rp, $this->options['password']);
        
      }
      /* Dump schema workflow && data  */

      $this->log("Dump schema workflow/rbac && data\n====================================\n");
      $myPortA = explode(":",$this->options['database']['hostname']);
      if(count($myPortA)<2) {
        if(strlen(trim($this->port))>0 )
          $myPortA[1] = $this->port;
        else 
          $myPortA[1] = "1433";
      }
      $myPort = $myPortA[1];
      $this->options['database']['hostname'] = $myPortA[0];

      $this->log("Mssql port: ".$myPort."\n");

      mssql_select_db($wf,$this->connection_database);
      $pws = PATH_WORKFLOW_MSSQL_DATA.$schema;
      $qws = $this->query_sql_file(PATH_WORKFLOW_MSSQL_DATA.$schema,$this->connection_database);
      $this->log($qws);
      $qwv = $this->query_sql_file(PATH_WORKFLOW_MSSQL_DATA.$values,$this->connection_database);
      $this->log($qwv);

      /* Dump schema rbac && data  */
      $pws = PATH_RBAC_MSSQL_DATA.$schema;
      mssql_select_db($rb,$this->connection_database);
      $qrs = $this->query_sql_file(PATH_RBAC_MSSQL_DATA.$schema,$this->connection_database);
      $this->log($qrs);
      $qrv = $this->query_sql_file(PATH_RBAC_MSSQL_DATA.$values,$this->connection_database);
      $this->log($qrv);
     
      $path_site  = $this->options['path_data']."/sites/".$this->options['name']."/";
      $db_file    = $path_site."db.php";
      @mkdir($path_site,0777,true);
      @mkdir($path_site."files/",0777,true);
      @mkdir($path_site."mailTemplates/",0777,true);
      @mkdir($path_site."public/",0777,true);
      @mkdir($path_site."reports/",0777,true);
      @mkdir($path_site."xmlForms",0777,true);

      // IP referencial 
      $this->options['database']['hostname'] = $this->server;

      $db_text = "<?php\n" .
                 "// Processmaker configuration\n" .
                 "define ('DB_ADAPTER', 'mssql' );\n" .
                 "define ('DB_HOST', '" . $this->options['database']['hostname'] . ":".$myPort."' );\n" .
                 "define ('DB_NAME', '" . $wf. "' );\n" .
                 "define ('DB_USER', '" . (($this->cc_status==1)?$wf:$this->options['database']['username']). "' );\n" .
                 "define ('DB_PASS', '" . (($this->cc_status==1)?$this->options['password']:$this->options['database']['password']). "' );\n" .
                 "define ('DB_RBAC_HOST', '". $this->options['database']['hostname'] .":".$myPort."' );\n" .
                 "define ('DB_RBAC_NAME', '". $rb . "' );\n" .
                 "define ('DB_RBAC_USER', '".(($this->cc_status==1)?$rb:$this->options['database']['username']) . "' );\n" .
                 "define ('DB_RBAC_PASS', '". (($this->cc_status==1)?$this->options['password']:$this->options['database']['password']) . "' );\n" .
                 "define ('DB_REPORT_HOST', '". $this->options['database']['hostname'] .":".$myPort."' );\n" .
                 "define ('DB_REPORT_NAME', '". $rp . "' );\n" .
                 "define ('DB_REPORT_USER', '".(($this->cc_status==1)?$rp:$this->options['database']['username']) . "' );\n" .
                 "define ('DB_REPORT_PASS', '". (($this->cc_status==1)?$this->options['password']:$this->options['database']['password']) . "' );\n" .
                 "?>";
      $fp =  @fopen($db_file, "w");
      $this->log("Creating: ".$db_file."  => ".((!$fp)?$fp:"OK")."\n");
      $ff =  @fputs( $fp, $db_text, strlen($db_text));
      $this->log("Write: ".$db_file."  => ".((!$ff)?$ff:"OK")."\n");

      fclose( $fp );     
     
      $this->set_admin();
    }
    return $test;
  }

  /**
   * set Grant Privileges for SQLServer
   *
   * @param  string    $psUser
   * @param  string    $psPassword  
   * @return void
   */ 
  function setGrantPrivileges($psUser, $psPassword )
  {
      $sCreateUser = "sp_addlogin '" . $psUser . "', '" . $psPassword . "';";
      $ac = @mssql_query($sCreateUser, $this->connection_database);
      $this->log($sCreateUser.": => ".((!$ac)? "Error when adding user" :"OK")."\n");
      
      /* mssql_select_db($psUser); */
      $sGrantDB = "sp_grantdbaccess '" . $psUser . "', '" . $psUser . "';";
      $ac = @mssql_query($sGrantDB, $this->connection_database);
      $this->log($sGrantDB.": => ".((!$ac)? "Failed to set permissions to the database" :"OK")."\n");

			///-- EXEC master..sp_addsrvrolemember @loginame = N'rp_sample03', @rolename = N'sysadmin'
      $sAddRoleSysAdmin = "EXEC master..sp_addsrvrolemember @loginame = N'" . $psUser . "', @rolename = N'sysadmin';";
      $ac = @mssql_query($sAddRoleSysAdmin , $this->connection_database);
      $this->log($sAddRoleSysAdmin.": => ".((!$ac)? "Failed to set permissions" :"OK")."\n");
      
      return true;
  }
  
  /**
   * set_admin
   *
   * @return void
   */  
  public function set_admin()
  {
    mssql_select_db($this->wf_site_name,$this->connection_database);
    $q  = 'UPDATE USERS SET USR_USERNAME = \''.mysql_escape_string($this->options['admin']['username']).'\', `USR_PASSWORD` = \''.md5($this->options['admin']['password']).'\' WHERE `USR_UID` = \'00000000000000000000000000000001\' LIMIT 1';
    $ac = @mssql_query($q,$this->connection_database);
    $this->log("Set workflow USERNAME:PASSWORD => ".((!$ac)? "mssql_error()":"OK")."\n");

    mssql_select_db($this->rbac_site_name,$this->connection_database);
    $q  = 'UPDATE USERS SET USR_USERNAME = \''.mysql_escape_string($this->options['admin']['username']).'\', `USR_PASSWORD` = \''.md5($this->options['admin']['password']).'\' WHERE `USR_UID` = \'00000000000000000000000000000001\' LIMIT 1';
    $ac = @mssql_query($q,$this->connection_database);
    $this->log("Set rbac USERNAME:PASSWORD => ".((!$ac)? "mssql_error()" : "OK")."\n");
  }

  /**
   * query_sql_file
   *
   * @param   string  $file
   * @param   string  $connection
   * @return  array   $report
   */  
  public function query_sql_file($file, $connection)
  {
    $report = array(
      'SQL_FILE' => $file,
      'errors'   => array(),
      'querys'   => 0
    );    
    
    #<--
    if( !is_file($file) ) {
      $report['errors']="Error reading SQL";
      return $report;
    }
    $content = file_get_contents($file);
    $ret     = explode(';', $content);
    #-->
    
    $report['querys'] = count($ret);
    ///-- @mssql_query("SET NAMES 'utf8';");
    
    foreach($ret as $qr) {
      $re = @mssql_query($qr, $connection);
      if(!$re) {
        $report['errors'][] = "Query error "; ///-- . mysql_error();
      }
    }
    return $report;
  }  

  /**
   * check_path
   *
   * @return  void
   * @todo  Empty function
   */  
  private function check_path()
  {

  }

  /**
   * function find_root_path
   *
   * @param   string  $path
   * @return  string  $path
   */  
  private function find_root_path($path)
  {
    $i = 0; //prevent loop inifinity
    while(!is_dir($path) && ($path = dirname($path)) && ((strlen($path)>1) && $i<10)) {
      $i++;
    }
    return $path;
  }
  
  /**
   * file_permisions
   *
   * @param   string  $file
   * @param   integer $def default value 777
   * @return  integer $def
   */  
  public function file_permisions($file, $def = 777)
  {
    if ( PHP_OS == 'WINNT' )
      return $def;
    else
      return (int)substr(sprintf('%o',@fileperms($file)),-4);
  }

  /**
   * is_dir_writable
   *
   * @param   string  $dir  default value empty
   * @return  string  $path
   */  
  public function is_dir_writable($dir = '')
  {
    if (PHP_OS=='WINNT') {
        $dir = $this->find_root_path($dir);
        return file_exists ( $dir );
    }
    else {
      $dir = $this->find_root_path($dir);
        return (is_writable($dir) && ($this->file_permisions($dir)==777 || $this->file_permisions($dir)==755));
    }
  }

  /**
   * getDirectoryFiles
   *
   * @param   string  $dir  default value empty
   * @return  string  $path
   */  
  public function getDirectoryFiles($dir,$extension){
    $filesArray=array();
    if (file_exists($dir)) {
      if ($handle = opendir($dir)) {
          while (false !== ($file = readdir($handle))) {
              $fileParts=explode(".",$file);
              if($fileParts[count($fileParts)-1]==$extension) {
                $filesArray[]=$file;
              }
          }
          closedir($handle);
      }
    }
    return $filesArray;
  }

  /**
   * check_db_empty
   *
   * @param   string   $dbName  
   * @return  boolean  true or false
   */  
  public function check_db_empty($dbName)
  {
    $a = @mssql_select_db($dbName,$this->connection_database);
    if(!$a) {
      return true;
    }
    $q = @mssql_query('SHOW TABLES',$this->connection_database);
    return (@mssql_num_rows($q)>0)?false:true;
  }
  
  /**
   * check_db
   *
   * @param   string  $dbName  
   * @return  Array   Array('status' => true or false,'message' => string)
   */  
  public function check_db($dbName)
  {
    if(!$this->connection_database) {
      return Array('status' => false, 'message' => 'Unable to establish connection');
    }
    else  {
      if(! @mssql_select_db($dbName,$this->connection_database) && $this->cc_status!=1) {
        return Array('status' => false, 'message' => 'Unable to establish connection (2)');
      }
      else {      
        
        if($this->options['advanced']['ao_db_drop']===true || $this->check_db_empty($dbName)) {
          return Array('status' => true,  'message' => 'PASSED');
        }
        else {
          return Array('status' => false, 'message' => 'Database is not empty');
        }
      }
    }
  }
  
  /**
   * check_connection
   *
   * @return  Array   $rt
   */  
  private function check_connection()
  {
    if(!function_exists("mssql_connect")) {
      $this->cc_status = 0;
      $rt = Array(
        'connection' => false,
        'grant'      => 0,
        'version'    => false,
        'message'    => "php-mssql is Not Installed",
        'ao'=>Array(
          'ao_db_wf' => false,
          'ao_db_rb' => false,
          'ao_db_rp' => false
        )
      );
    }
    else {
      /*
      g::pr(@explode(SYSTEM_HASH,G::decrypt(HASH_INSTALLATION,SYSTEM_HASH)) );
      krumo("Adaptador " . DB_ADAPTER);
      krumo("Host " . DB_HOST);
      */
      ///-- $this->connection_database = @mssql_connect($this->options['database']['hostname'],$this->options['database']['username'],$this->options['database']['password']);
      $this->connection_database = @mssql_connect($this->server, $this->userName, $this->password);
      $rt = Array(
          'version' => false,
          'ao'=>Array(
              'ao_db_wf' => false,
              'ao_db_rb' => false,
              'ao_db_rp' => false
            )
        );
      if(!$this->connection_database) {
        $this->cc_status  = 0;
        $rt['connection'] = false;
        $rt['grant']      = 0;
        $rt['message']    = "Mssql error: Connection failed. "; ///-- .mysql_error();
      }
      else {
        /*
        preg_match('@[0-9]+\.[0-9]+\.[0-9]+@',mysql_get_server_info($this->connection_database),$version);
        $rt['version']    = version_compare(@$version[0],"4.1.0",">=");
        $rt['connection'] = true;
        */
        $rt['version'] = true; 
        $rt['connection'] = true;
        $dbNameTest = "PROCESSMAKERTESTDC";
        $db = @mssql_query("CREATE DATABASE ".$dbNameTest,$this->connection_database);
        if(!$db) {
          $this->cc_status = 3;
          $rt['grant']     = 3;
          //$rt['message'] = "Db GRANTS error:  ".mysql_error();
          $rt['message']   = "Successful connection";
        }
        else {

          //@mysql_drop_db("processmaker_testGA");
          $usrTest = "wfrbtest";
///--          $chkG    = "GRANT ALL PRIVILEGES ON ".$dbNameTest.".* TO ".$usrTest."@'%' IDENTIFIED BY 'sample' WITH GRANT OPTION";
///--          $ch      = @mssql_query($chkG,$this->connection_database);
          $ch      = $this->setGrantPrivileges($usrTest, 'sample' );
          if(!$ch) {
            $this->cc_status = 2;
            $rt['grant']     = 2;
            //$rt['message'] = "USER PRIVILEGES ERROR";
            $rt['message']   = "Successful connection";
          }
          else {
            $this->cc_status = 1;
            @mssql_query("DROP USER ".$usrTest."@'%'",$this->connection_database);
            $rt['grant']   = 1;
            $rt['message'] = "Successful connection";
          }
          @mssql_query("DROP DATABASE ".$dbNameTest,$this->connection_database);

        }
//        var_dump($wf,$rb,$rp);
      }
    }
    $rt['ao']['ao_db_wf'] = $this->check_db($this->options['advanced']['ao_db_wf']);
    $rt['ao']['ao_db_rb'] = $this->check_db($this->options['advanced']['ao_db_rb']);
    $rt['ao']['ao_db_rp'] = $this->check_db($this->options['advanced']['ao_db_rp']);
    
    return $rt;
  }
  
  /**
   * log
   *
   * @param   string $text
   * @return  void
   */  
  public function log($text)
  {
    array_push($this->report,$text);
  }
}
?>
