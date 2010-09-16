<?php
/**
 * class.serverConfiguration.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.
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

/**
 * ServerConfiguration - serverConf class
 * @author Hugo Loza
 * @copyright 2010 COLOSA
 * @license GNU Affero General Public License
 */
class serverConf {
  private $_aProperties = array ();
  private $_aWSapces = array ();
  private $aWSinfo = array ();
  private $pluginsA = array ();
  private $errors = array ();
  private static $instance = NULL;
  private $haveSetupData = false;
  private $beatType = 'starting';
  private $ip;
  private $index = 0;
  private $os;
  private $webserver;
  private $host;
  private $php;
  private $mysql;
  private $pmVersion;
  private $pmProduct = 'PMCE';
  private $nextBeatDate;
  private $logins;

  private function __construct() {
    $this->filePath = PATH_DATA . 'srvConf.singleton';
  }

  /**
   * This function is instancing to this object
   * @param
   * @return object
   */
  function &getSingleton() {
    if (self::$instance == NULL) {
      self::$instance = new serverConf ( );
      if (file_exists ( self::$instance->filePath )){
        self::$instance->unSerializeInstance ( file_get_contents ( self::$instance->filePath ) );
      }
    }
    if (isset ( self::$instance->haveSetupData ) && ! self::$instance->checkIfHostNameHasChanged ()) {
      self::$instance->getSetupData ();
    }

    if (self::$instance->beatType == 'starting' || strtotime ( "now" ) > self::$instance->nextBeatDate)
      self::$instance->postHeartBeat ();
    return self::$instance;
  }

  /**
   * This function generates a storable representation of this obejct
   * @param
   * @return void
   */
  function serializeInstance() {
    return serialize ( self::$instance );
  }

  /**
   * This function takes a single serialized object and converts it back a object
   * @param string $serialized
   * @return void
   */
  function unSerializeInstance($serialized) {
    if (self::$instance == NULL) {
      self::$instance = new serverConf ( );
    }

    if($instance = @unserialize ( $serialized )){
    	self::$instance = $instance;
  	}
  }

  /**
   * This will save the object in the specified file (defined as a property of this class)
   * @param
   * @return void
   */

  function saveSingleton() {
    $size = file_put_contents ( $this->filePath, $this->serializeInstance () );
  }

  /**
   * With this is possible to save a property that will be saved in the properties
   * array of this class.
   * @param string $propertyName
   * @param string $propertyValue
   */
  function setProperty($propertyName, $propertyValue) {
    $this->_aProperties [$propertyName] = $propertyValue;
    $this->saveSingleton ();
  }

  /**
   * To unset a defined property. If it doesn't exist then it does nothing.
   * @param string $propertyName
   * @return void
   */
  function unsetProperty($propertyName) {
    if (isset ( $this->_aProperties [$propertyName] ))
      unset ( $this->_aProperties [$propertyName] );
    $this->saveSingleton ();
  }

  /**
   * Returns the value of a defined property. If it doesn't exist then returns null
   * @param string $propertyName
   * @return string/null
   */
  function getProperty($propertyName) {
    if (isset ( $this->_aProperties [$propertyName] )) {
      return $this->_aProperties [$propertyName];
    } else {
      return null;
    }
  }

  /**
   * Used to have a record of succesful logins to the system (total and by WS)
   * @param
   * @return void
   */
  function sucessfulLogin() {
    $this->logins ++;
    $this->workspaces [SYS_SYS] ['WSP_LOGINS'] ++;
    $this->saveSingleton ();
  }

  function setWsInfo($wsname,$info){
    $this->aWSinfo[$wsname]=$info;
  }

  /**
   * This will togle the status of a workspace (enabled,disabled)
   * @param string $wsName
   * @return void
   */
  function changeStatusWS($wsName) {

    if (isset ( $this->_aWSapces [$wsName] )) { //Enable WS
      unset ( $this->_aWSapces [$wsName] );
    } else {
      $this->_aWSapces [$wsName] = 'disabled';
    }
    $this->saveSingleton ();
  }

  /**
   * Return the status of a WS. If is disabled will return 1 otherwise 0
   * @param $wsname
   * @return boolean
   */
  function isWSDisabled($wsName) {
    return isset ( $this->_aWSapces [$wsName] );
  }

  /**
   * Check only if the server address or server namer has changed,
   * to send another beat in next minute.
   * @param
   * @return boolean
   */
  function checkIfHostNameHasChanged() {
    //removed the PM_VERSION control, because when an upgrade is done, the haveSetupData has to be changed.
    if ($this->ip != getenv ( 'SERVER_ADDR' ))
      return false;

    if ($this->host != getenv ( 'SERVER_NAME' ))
      return false;

    return $this->haveSetupData;
  }

  /**
   * This method will update all the initial values for this class related to the server
   * @param
   * @return void
   */
  function getSetupData() {
    self::$instance->haveSetupData = true;

    if (! defined ( 'PM_VERSION' )) {
      if (file_exists ( PATH_METHODS . 'login/version-pmos.php' )) {
        require_once (PATH_METHODS . 'login/version-pmos.php');
      } else {
        define ( 'PM_VERSION', 'Development Version' );
      }
    }

    $this->ip = getenv ( 'SERVER_ADDR' );

    $this->os = '';
    if (file_exists ( '/etc/redhat-release' )) {
      $fnewsize = filesize ( '/etc/redhat-release' );
      $fp = fopen ( '/etc/redhat-release', 'r' );
      $this->os = trim ( fread ( $fp, $fnewsize ) );
      fclose ( $fp );
    }
    $this->os .= " (" . PHP_OS . ")";
    $this->pmVersion = PM_VERSION;
    $this->webserver = getenv ( 'SERVER_SOFTWARE' );
    $this->host = getenv ( 'SERVER_NAME' );
    $this->php = phpversion ();
    $this->workspaces = $this->getWSList ();
    $this->plugins = $this->getPluginsList ();
    $this->nextBeatDate = strtotime ( "+1 min" );
    $this->saveSingleton ();
  }

  /**
   * Will return a list of all WS in this system and their related information.
   * @uses getWorkspaceInfo
   * @param
   * @return array
   */
  function getWSList() {
    $dir = PATH_DB;
    $wsArray = array ();
    if (file_exists ( $dir )) {
      if ($handle = opendir ( $dir )) {
        while ( false !== ($file = readdir ( $handle )) ) {
          if (($file != ".") && ($file != "..")) {
            if (file_exists ( PATH_DB . $file . '/db.php' )) { //print $file."/db.php <hr>";
              $statusl = ($this->isWSDisabled ( $file )) ? 'DISABLED' : 'ENABLED';
              //$wsInfo = $this->getWorkspaceInfo ( $file );
              if(isset($this->aWSinfo[$file])){
                $wsInfo = $this->aWSinfo[$file];
              }else{
                $wsInfo ['num_processes'] = "not gathered yet";
                $wsInfo ['num_cases'] = "not gathered yet";;
                $wsInfo ['num_users'] = "not gathered yet";
              }
              $wsArray [$file] = array ('WSP_ID' => $file, 'WSP_NAME' => $file, 'WSP_STATUS' => $statusl, 'WSP_PROCESS_COUNT' => $wsInfo ['num_processes'], 'WSP_CASES_COUNT' => $wsInfo ['num_cases'], 'WSP_USERS_COUNT' => isset($wsInfo ['num_users'])?$wsInfo ['num_users']:"" );
              if (isset ( $this->workspaces [$file] ['WSP_LOGINS'] ))
                $wsArray [$file] ['WSP_LOGINS'] = $this->workspaces [$file] ['WSP_LOGINS'];

            }
          }
        }
        closedir ( $handle );
      }
    }
    return $wsArray;

  }
  /**
   * Will return all the information of a WS.
   * - Status
   * - # of cases
   * - # of processes
   * - # of users
   * @param string $wsName
   * @return array
   */
  function getWorkspaceInfo($wsName) {
    $aResult = Array ('num_processes' => '0', 'num_cases' => '0' );
    if (file_exists ( PATH_DB . $wsName . PATH_SEP . 'db.php' )) {

      $sContent = file_get_contents ( PATH_DB . $wsName . PATH_SEP . 'db.php' );

      $sContent = str_replace ( '<?php', '', $sContent );
      $sContent = str_replace ( '<?', '', $sContent );
      $sContent = str_replace ( '?>', '', $sContent );
      $sContent = str_replace ( 'define', '', $sContent );
      $sContent = str_replace ( "('", "$", $sContent );
      $sContent = str_replace ( "',", '=', $sContent );
      $sContent = str_replace ( ");", ';', $sContent );

      @eval ( $sContent );

      if (! (isset ( $DB_ADAPTER ) && isset ( $DB_USER ) && isset ( $DB_PASS ) && isset ( $DB_HOST ) && isset ( $DB_NAME ))) {
        return false;
      }

      $dsn = $DB_ADAPTER . '://' . $DB_USER . ':' . $DB_PASS . '@' . $DB_HOST . '/' . $DB_NAME;
      $dsnRbac = $DB_ADAPTER . '://' . $DB_RBAC_USER . ':' . $DB_RBAC_PASS . '@' . $DB_RBAC_HOST . '/' . $DB_RBAC_NAME;
      $dsnRp = $DB_ADAPTER . '://' . $DB_REPORT_USER . ':' . $DB_REPORT_PASS . '@' . $DB_REPORT_HOST . '/' . $DB_REPORT_NAME;

      $link = @mysql_connect ( $DB_HOST, $DB_USER, $DB_PASS );

      if ($link) {
        @mysql_select_db ( $DB_NAME );
        $result = @mysql_query ( "SELECT COUNT(*) AS NUM FROM PROCESS WHERE PRO_STATUS='ACTIVE'", $link );
        if ($result) {
          $a = @mysql_fetch_array ( $result );

          if (isset ( $a ['NUM'] )) {
            $aResult ['num_processes'] = $a ['NUM'];
          }
        }
        $result = @mysql_query ( "SELECT COUNT(APP_UID) AS NUM FROM APPLICATION WHERE APP_STATUS<>'COMPLETED'", $link );
        if ($result) {
          $a = @mysql_fetch_array ( $result );

          if (isset ( $a ['NUM'] )) {
            $aResult ['num_cases'] = $a ['NUM'];
          }
        }

        $result = @mysql_query ( "SELECT COUNT(USR_UID) AS NUM FROM USERS WHERE USR_STATUS NOT IN('DELETED','DISABLED')", $link );
        $aResult ['num_users'] = 'undefined';
        if ($result) {
          $a = @mysql_fetch_array ( $result );

          if (isset ( $a ['NUM'] )) {
            $aResult ['num_users'] = $a ['NUM'];
          }
        }
        mysql_close ( $link );
      }
    }
    return $aResult;
  }

  /**
   * Will list the plugins of the system
   * @param
   * @retun array
   */
  function getPluginsList() {
    return $this->pluginsA;
  }

  /***
   * Register a PLugin
   */
  function addPlugin($workspace,$info){
    $this->pluginsA[$workspace]=$info;
  }

  function getDBVersion(){
    $sMySQLVersion = '?????';
    if (defined ( "DB_HOST" )) {
      G::LoadClass ( 'net' );
      G::LoadClass ( 'dbConnections' );
      $dbNetView = new NET ( DB_HOST );
      $dbNetView->loginDbServer ( DB_USER, DB_PASS );

      $dbConns = new dbConnections ( '' );
      $availdb = '';
      foreach ( $dbConns->getDbServicesAvailables () as $key => $val ) {
        if ($availdb != '')
          $availdb .= ', ';
        $availdb .= $val ['name'];
      }

      try {
        $sMySQLVersion = $dbNetView->getDbServerVersion ( 'mysql' );
      }
      catch ( Exception $oException ) {
        $sMySQLVersion = '?????';
      }
    }
    return $sMySQLVersion;
  }

  /**
   * This will send a beat with the stats information
   * @param
   * @return void
   */
  function postHeartBeat() {
    return false;
    /*
    $this->index = intval ( $this->index ) + 1;
    $heartBeatUrl = 'http://heartbeat.processmaker.com/syspmLicenseSrv/en/green/services/beat';

    //Update sensitive data
    $this->workspaces = $this->getWSList ();
    $this->plugins = $this->getPluginsList ();

    $params = array ();
    $params ['ip'] = $this->ip;
    $params ['index'] = $this->index;
    $params ['beatType'] = $this->beatType;
    $params ['date'] = date ( 'Y-m-d H:i:s' );
    $params ['host'] = $this->host;
    $params ['os'] = $this->os;
    $params ['webserver'] = $this->webserver;
    $params ['php'] = $this->php;
    $params ['pmVersion'] = $this->pmVersion;
    $params ['pmProduct'] = $this->pmProduct;
    $params ['logins'] = $this->logins;
    $params ['workspaces'] = serialize ( $this->workspaces );
    $params ['plugins'] = serialize ( $this->plugins );
    $params ['dbVersion'] = $this->getDBVersion();
    $params ['errors'] = serialize( $this->errors );
    if($licInfo=$this->getProperty('LICENSE_INFO')){
      $params ['license'] = serialize ( $licInfo );
    }

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $heartBeatUrl );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_HEADER, true );
    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, false );
    curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
    //To avoid SSL error
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );

    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params );    

    //To avoid timeouts
    curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
    curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );

    $response = curl_exec ( $ch );
    $headers = curl_getinfo ( $ch );
    $header = substr ( $response, 0, $headers ['header_size'] );
    $content = substr ( $response, $headers ['header_size'] );
    curl_close ( $ch );

    if ($headers ['http_code'] == 200) {
      $this->beatType = 'beat';
      $this->resetLogins ();
      $this->nextBeatDate = strtotime ( "+7 day" ); //next beat in 7 days
      //Reset Errors
      $this->errors=array();
    } else {
    	//Catch the error
    	$this->errors[]=curl_getinfo($curl_session);
      $this->nextBeatDate = strtotime ( "+1 day" ); //retry in 30 mins
    }

    $this->saveSingleton ();
    */
  }
  /**
   * Will reset all the logins' count
   * @param
   * @return void
   */
  private function resetLogins() {
    $this->logins = 0;
    foreach ( $this->workspaces as $wsName => $wsinfo ) {
      $this->workspaces [$wsName] ['WSP_LOGINS'] = 0;
    }
  }

}