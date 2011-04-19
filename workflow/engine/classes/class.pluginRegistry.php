<?php
/**
 * class.pluginRegistry.php
 * @package workflow.engine.classes
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2011 Colosa Inc.
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
 */


/**
 * @package workflow.engine.classes
 */

class pluginDetail {
  var $sNamespace;
  var $sClassName;
  var $sFriendlyName = null;
  var $sDescription = null;
  var $sSetupPage = null;
  var $sFilename;
  var $sPluginFolder = '';
  var $sCompanyLogo = '';
  var $iVersion = 0;
  var $enabled = false;
  var $aWorkspaces = null;
  var $bPrivate = false;

  /**
   * This function is the constructor of the pluginDetail class
   * @param string $sNamespace
   * @param string $sClassName
   * @param string $sFilename
   * @param string $sFriendlyName
   * @param string $sPluginFolder
   * @param string $sDescription
   * @param string $sSetupPage
   * @param integer $iVersion
   * @return void
   */
  function __construct( $sNamespace, $sClassName, $sFilename, $sFriendlyName = '', $sPluginFolder ='', $sDescription ='', $sSetupPage ='', $iVersion = 0) {
    $this->sNamespace = $sNamespace;
    $this->sClassName = $sClassName;
    $this->sFriendlyName = $sFriendlyName;
    $this->sDescription  = $sDescription;
    $this->sSetupPage    = $sSetupPage;
    $this->iVersion      = $iVersion;
    $this->sFilename     = $sFilename;
    if ( $sPluginFolder == '')
    $this->sPluginFolder = $sNamespace;
    else
    $this->sPluginFolder = $sPluginFolder ;
  }
}

/**
 * @package workflow.engine.classes
 */

class PMPluginRegistry {
  private $_aPluginDetails = array();
  private $_aPlugins = array();
  private $_aMenus = array();
  private $_aFolders = array();
  private $_aTriggers = array();
  private $_aDashboards = array();
  private $_aReports = array();
  private $_aPmFunctions = array();
  private $_aRedirectLogin = array();
  private $_aSteps = array();
  private $_aDashboardPages = array();
  private $_aCSSStyleSheets = array();
  private $_aToolbarFiles = array();
  private $_aCaseSchedulerPlugin = array();
  private $_aTaskExtendedProperties = array();

  static private $instance = NULL;

  /**
   * This function is the constructor of the PMPluginRegistry class
   * param
   * @return void
   */
  private function __construct() {}

  /**
   * This function is instancing to this class
   * param
   * @return object
   */
  function &getSingleton() {
    if (self::$instance == NULL) {
      self::$instance = new PMPluginRegistry ();
    }
    return self::$instance;
  }

  /**
   * This function generates a storable representation of a value
   * param
   * @return void
   */
  function serializeInstance() {
    return serialize ( self::$instance);
  }

  /**
   * This function takes a single serialized variable and converts it back a code
   * @param string $serialized
   * @return void
   */
  function unSerializeInstance( $serialized ) {
    if (self::$instance == NULL) {
      self::$instance = new PMPluginRegistry ();
    }

    $instance = unserialize ( $serialized );
    self::$instance = $instance;
  }

  /**
   * Save the current instance to the plugin singleton
   *
   */
  function save() {
    file_put_contents  ( PATH_DATA_SITE . 'plugin.singleton', $this->serializeInstance() );
  }

  //delete this function, it was here, only for test and debug purposes
  function showArrays () {
    krumo ( $this->_aPluginDetails);
    krumo ( $this->_aPlugins);
    krumo ( $this->_aMenus);
    krumo ( $this->_aFolders);
    krumo ( $this->_aTriggers);
    krumo ( $this->_aDashboards);
    krumo ( $this->_aReports);
    krumo ( $this->_aPmFunctions);
    krumo ( $this->_aRedirectLogin);
    krumo ( $this->_aSteps);
  }

  /**
   * Register the plugin in the singleton
   *
   * @param unknown_type $sClassName
   * @param unknown_type $sNamespace
   * @param unknown_type $sFilename
   */
  function registerPlugin( $sNamespace, $sFilename = null)
  {
    $sClassName = $sNamespace . 'plugin';
    if ( isset( $this->_aPluginDetails[$sNamespace] ) )
      return;
    //require_once ( $sFilename );
    $plugin = new $sClassName ($sNamespace, $sFilename);
    $detail = new pluginDetail (
    $sNamespace,
    $sClassName,
    $sFilename,
    $plugin->sFriendlyName,
    $plugin->sPluginFolder,
    $plugin->sDescription,
    $plugin->sSetupPage,
    $plugin->iVersion  );
    if ( isset ($plugin->aWorkspaces) )
    $detail->aWorkspaces = $plugin->aWorkspaces;
    if ( isset ($plugin->bPrivate) )
      $detail->bPrivate = $plugin->bPrivate;
    //if ( isset( $this->_aPluginDetails[$sNamespace] ) ){
    //  $detail->enabled=$this->_aPluginDetails[$sNamespace]->enabled;
    //}
    $this->_aPluginDetails[$sNamespace] = $detail;
  }

  /**
   * get the plugin details, by filename
   *
   * @param unknown_type $sFilename
   */
  function getPluginDetails( $sFilename ) {
    foreach ( $this->_aPluginDetails as $key => $row ) {
      if ( $sFilename == baseName ( $row->sFilename ) )
      return $row;
    }
    return NULL;
  }

  /**
   * Enable the plugin in the singleton
   *
   * @param unknown_type $sNamespace
   */
  function enablePlugin($sNamespace ) {
    foreach ( $this->_aPluginDetails as $namespace=>$detail ) {
      if ( $sNamespace == $namespace ) {
        $this->registerFolder($sNamespace, $sNamespace, $detail->sPluginFolder ); //register the default directory, later we can have more
        $this->_aPluginDetails[$sNamespace]->enabled = true;
        $oPlugin = new $detail->sClassName( $detail->sNamespace, $detail->sFilename );
        $this->_aPlugins[$detail->sNamespace] =& $oPlugin;
        if (method_exists($oPlugin, 'enable')) {
          $oPlugin->enable();
        }
        return true;
      }
    }
    throw new Exception("Unable to enable plugin '$sNamespace' (plugin not found)");
  }

  /**
   * disable the plugin in the singleton
   *
   * @param unknown_type $sNamespace
   */
  function disablePlugin($sNamespace )
  {
    $found = false;
    foreach ( $this->_aPluginDetails as $namespace=>$detail ) {
      if ( $sNamespace == $namespace ) {
        unset ($this->_aPluginDetails[$sNamespace]);
        $oPlugin = new $detail->sClassName( $detail->sNamespace, $detail->sFilename );
        $this->_aPlugins[$detail->sNamespace] =& $oPlugin;
        if (method_exists($oPlugin, 'disable')) {
          $oPlugin->disable();
        }
        $found = true;
      }
    }

    if (!$found)
      throw new Exception("Unable to disable plugin '$sNamespace' (plugin not found)");

    foreach ( $this->_aMenus as $key=>$detail ) {
      if ( $detail->sNamespace == $sNamespace )
      unset ( $this->_aMenus[ $key ] );
    }
    foreach ( $this->_aFolders as $key=>$detail ) {
      if ( $detail->sNamespace == $sNamespace )
      unset ( $this->_aFolders[ $key ] );
    }

    foreach ( $this->_aTriggers as $key=>$detail ) {
      if ( $detail->sNamespace == $sNamespace )
      unset ( $this->_aTriggers[ $key ] );
    }

    foreach ( $this->_aDashboards as $key=>$detail ) {
      if ( $detail == $sNamespace )
      unset ( $this->_aDashboards[ $key ] );
    }

    foreach ( $this->_aReports as $key=>$detail ) {
      if ( $detail == $sNamespace )
      unset ( $this->_aReports[ $key ] );
    }

    foreach ( $this->_aPmFunctions as $key=>$detail ) {
      if ( $detail == $sNamespace )
      unset ( $this->_aPmFunctions[ $key ] );
    }

    foreach ( $this->_aRedirectLogin as $key=>$detail ) {
      if ( $detail->sNamespace == $sNamespace )
      unset ( $this->_aRedirectLogin[ $key ] );
    }

    foreach ( $this->_aSteps as $key=>$detail ) {
      if ( $detail->sNamespace == $sNamespace )
      unset ( $this->_aSteps[ $key ] );
    }
    foreach ( $this->_aToolbarFiles as $key=>$detail ) {
      if ( $detail->sNamespace == $sNamespace )
      unset ( $this->_aToolbarFiles[ $key ] );
    }
    foreach ( $this->_aDashboardPages as $key=>$detail ) {
      if ( $detail->sNamespace == $sNamespace )
      unset ( $this->_aDashboardPages[ $key ] );
    }
    foreach ( $this->_aCSSStyleSheets as $key=>$detail ) {
      if ( $detail->sNamespace == $sNamespace )
      unset ( $this->_aCSSStyleSheets[ $key ] );
    }
    foreach ( $this->_aCaseSchedulerPlugin as $key=>$detail ) {
      if ( $detail->sNamespace == $sNamespace )
      unset ( $this->_aCaseSchedulerPlugin[ $key ] );
    }
  	foreach ( $this->_aTaskExtendedProperties as $key=>$detail ) {
      if ( $detail->sNamespace == $sNamespace )
      unset ( $this->_aTaskExtendedProperties[ $key ] );
    }
    
    
  }

  /**
   * get status plugin in the singleton
   *
   * @param unknown_type $sNamespace
   */
  function getStatusPlugin($sNamespace ) {
    foreach ( $this->_aPluginDetails as $namespace=>$detail ) {
      if ( $sNamespace == $namespace )
        if ( $this->_aPluginDetails[$sNamespace]->enabled )
          return 'enabled';
        else
          return 'disabled';
    }
    return 0;
  }

  /**
   * Install a plugin archive. If pluginName is specified, the archive will
   * only be installed if it contains this plugin.
   *
   * @return bool true if enabled, false otherwise
   */
  function installPluginArchive($filename, $pluginName = NULL) {
    G::LoadThirdParty( 'pear/Archive','Tar');
    $tar = new Archive_Tar ($filename);
  
    $files = $tar->listContent();
    
    $plugins = array();
    foreach ($files as $f) {
      if (preg_match("/^([\w\.]*).ini$/", $f['filename'], $matches)) {
        $plugins[] = $matches[1];
      }
    }
    
    if (count($plugins) > 1)
      throw new Exception("Multiple plugins in one archive are not supported currently");

    if (isset($pluginName) && !in_array($pluginName, $plugins))
      throw new Exception("Plugin '$pluginName' not found in archive");

    $pluginName = $plugins[0];
    $pluginFile = "$pluginName.php";
    $oldPluginStatus = $this->getStatusPlugin($pluginFile);
    
    if ($pluginStatus != 0) {
      $oldDetails = $this->getPluginDetails($pluginFile);
      $oldVersion = $oldDetails->iVersion;
    } else {
      $oldDetails = NULL;
      $oldVersion = NULL;
    }

    $pluginIni = $tar->extractInString("$pluginName.ini");
    $pluginConfig = parse_ini_string($pluginIni);
    
    /*if (!empty($oClass->aDependences)) {
      foreach ($oClass->aDependences as $aDependence) {
        if (file_exists(PATH_PLUGINS . $aDependence['sClassName'] . '.php')) {
          require_once PATH_PLUGINS . $aDependence['sClassName'] . '.php';
          if (!$oPluginRegistry->getPluginDetails($aDependence['sClassName'] . '.php')) {
            throw new Exception('This plugin needs "' . $aDependence['sClassName'] . '" plugin');
          }
        }
        else {
          throw new Exception('This plugin needs "' . $aDependence['sClassName'] . '" plugin');
        }
      }
    }
    unset($oClass);
    if ($fVersionOld > $fVersionNew) {
      throw new Exception('A recent version of this plugin was already installed.');
    }*/
    $res = $tar->extract ( PATH_PLUGINS );

    if (!file_exists(PATH_PLUGINS . $pluginFile))
      throw ( new Exception( "File '$pluginFile' doesn't exist ") );

    require_once ( PATH_PLUGINS . $pluginFile );
    $details = $this->getPluginDetails( $pluginFile );

    $this->installPlugin($details->sNamespace);
    $this->setupPlugins();

    $this->enablePlugin($details->sNamespace);

    $this->save();
  }
  
  function uninstallPlugin($sNamespace) {
    $this->enablePlugin($sNamespace);
    $this->disablePlugin($sNamespace);
    $this->save();

    foreach ($this->_aPluginDetails as $namespace=>$detail) {
      if ( $sNamespace == $namespace ) {
        $pluginDir = PATH_PLUGINS . $detail->sPluginFolder;
        var_dump($pluginDir);
        var_dump($detail->sFilename);
        if (isset($detail->sPluginFolder) && !empty($detail->sPluginFolder) && file_exists($plugin_dir))
          G::rm_dir($plugin_dir);
        if (isset($detail->sFilename) && !empty($detail->sFilename) && file_exists($detail->sFilename))
          unlink(PATH_PLUGINS . $detail->sFilename);
      }
    }
  }
  
  /**
   * install the plugin
   *
   * @param unknown_type $sNamespace
   */
  function installPlugin($sNamespace ) {
    try {
      foreach ( $this->_aPluginDetails as $namespace=>$detail ) {
        if ( $sNamespace == $namespace ) {
          $oPlugin = new $detail->sClassName( $detail->sNamespace, $detail->sFilename );
          $this->_aPlugins[$detail->sNamespace] =& $oPlugin;
          $oPlugin->install();
        }
      }
    }
    catch ( Exception $e ) {
      global $G_PUBLISH;
      $aMessage['MESSAGE'] = $e->getMessage();
      $G_PUBLISH  = new Publisher;
      $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
      G::RenderPage( 'publish' );
      die;
    }

  }

  /**
   * Register a menu in the singleton
   *
   * @param unknown_type $sNamespace
   * @param unknown_type $sMenuId
   * @param unknown_type $sFilename
   */
  function registerMenu($sNamespace, $sMenuId, $sFilename ) {
    $found = false;
    foreach ( $this->_aMenus as $row=>$detail ) {
      if ( $sMenuId == $detail->sMenuId && $sNamespace == $detail->sNamespace )
      $found = true;
    }
    if ( !$found ) {
      $menuDetail = new menuDetail ($sNamespace, $sMenuId, $sFilename);
      $this->_aMenus[] = $menuDetail;
    }
  }

  /**
   * Register a dashboard class in the singleton
   *
   * @param unknown_type $sNamespace
   * @param unknown_type $sMenuId
   * @param unknown_type $sFilename
   */
  function registerDashboard($sNamespace ) {
    $found = false;
    foreach ( $this->_aDashboards as $row=>$detail ) {
      if ( $sNamespace == $detail )
      $found = true;
    }
    if ( !$found ) {
      $this->_aDashboards[] = $sNamespace;
    }
  }

  /**
   * Register a dashboard page for cases in the singleton
   *
   * @param unknown_type $sNamespace
   * @param unknown_type $sPage
   */
  function registerDashboardPage($sNamespace, $sPage, $sName, $sIcon ) {
    $found = false;
    foreach ( $this->_aDashboardPages as $row=>$detail ) {
      if ( $sPage == $detail->sPage && $sNamespace == $detail->sNamespace ){
        $detail->sName=$sName;
        $detail->sIcon=$sIcon;
        $found = true;
      }
    }
    if ( !$found ) {
      $dashboardPage = new dashboardPage ($sNamespace, $sPage, $sName, $sIcon);
      $this->_aDashboardPages[] = $dashboardPage;
    }
  }

  /**
   * return all dashboard pages
   *
   * @return array
   */
  function getDashboardPages() {
    return  $this->_aDashboardPages;
  }

  /**
   * Register a stylesheet in the singleton
   *
   * @param unknown_type $sNamespace
   * @param unknown_type $sPage
   */
  function registerCss($sNamespace, $sCssFile ) {
    $found = false;
    foreach ( $this->_aCSSStyleSheets as $row=>$detail ) {
      if ( $sCssFile == $detail->sCssFile && $sNamespace == $detail->sNamespace ){
        $detail->sCssFile=$sCssFile;
        $found = true;
      }
    }
    if ( !$found ) {
      $cssFile = new cssFile ($sNamespace, $sCssFile);
      $this->_aCSSStyleSheets[] = $cssFile;
    }
  }

  /**
   * return all dashboard pages
   *
   * @return array
   */
  function getRegisteredCss() {
    return  $this->_aCSSStyleSheets;
  }


  /**
   * Register a reports class in the singleton
   *
   * @param unknown_type $sNamespace
   * @param unknown_type $sMenuId
   * @param unknown_type $sFilename
   */
  function registerReport($sNamespace ) {
    $found = false;
    foreach ( $this->_aReports as $row=>$detail ) {
      if ( $sNamespace == $detail )
      $found = true;
    }
    if ( !$found ) {
      $this->_aReports[] = $sNamespace;
    }
  }

  /**
   * Register a PmFunction class in the singleton
   *
   * @param unknown_type $sNamespace
   * @param unknown_type $sMenuId
   * @param unknown_type $sFilename
   */
  function registerPmFunction($sNamespace ) {
    $found = false;
    foreach ( $this->_aPmFunctions as $row=>$detail ) {
      if ( $sNamespace == $detail )
      $found = true;
    }
    if ( !$found ) {
      $this->_aPmFunctions[] = $sNamespace;
    }
  }

  /**
   * Register a redirectLogin class in the singleton
   *
   * @param unknown_type $sNamespace
   * @param unknown_type $sRole
   * @param unknown_type $sPath
   */
  function registerRedirectLogin($sNamespace, $sRole, $sPathMethod ) {
    $found = false;
    foreach ( $this->_aRedirectLogin as $row=>$detail ) {
      if (( $sNamespace == $detail->sNamespace )&&( $sRole == $detail->sRoleCode )) //Filters based on Workspace and Role Code
      $found = true;
    }
    if ( !$found ) {
      $this->_aRedirectLogin[] = new redirectDetail ( $sNamespace, $sRole, $sPathMethod);
    }
  }

  /**
   * Register a folder for methods
   *
   * @param unknown_type $sFolderName
   */
  function registerFolder($sNamespace, $sFolderId, $sFolderName ) {
    $found = false;
    foreach ( $this->_aFolders as $row=>$detail )
    if ( $sFolderId == $detail->sFolderId && $sNamespace == $detail->sNamespace )
    $found = true;

    if ( !$found ) {
      $this->_aFolders[] = new folderDetail ( $sNamespace, $sFolderId, $sFolderName);
    }
  }

  /**
   * Register a step for process
   *
   * @param unknown_type $sFolderName
   */
  function registerStep($sNamespace, $sStepId, $sStepName, $sStepTitle, $setupStepPage = '') {
    $found = false;
    foreach ( $this->_aSteps as $row=>$detail )
    if ( $sStepId == $detail->sStepId && $sNamespace == $detail->sNamespace )
    $found = true;

    if ( !$found ) {
      $this->_aSteps[] = new stepDetail ( $sNamespace, $sStepId, $sStepName, $sStepTitle, $setupStepPage);
    }
  }

  /**
   * return true if the $sFolderName is registered in the singleton
   *
   * @param unknown_type $sFolderName
   */
  function isRegisteredFolder( $sFolderName ) {
    foreach ( $this->_aFolders as $row => $folder ) {
      if ( $sFolderName  == $folder->sFolderName && is_dir ( PATH_PLUGINS . $folder->sFolderName )){
        return true;
      }elseif( $sFolderName  == $folder->sFolderName && is_dir ( PATH_PLUGINS .$folder->sNamespace.PATH_SEP. $folder->sFolderName )){
        return $folder->sNamespace;
      }
    }
    return false;
  }

  /**
   * return all menus related to a menuId
   *
   * @param unknown_type $menuId
   */
  function getMenus( $menuId ) {
    foreach ( $this->_aMenus as $row=>$detail ) {
      if ( $menuId == $detail->sMenuId && file_exists ( $detail->sFilename ) ) {
        include ( $detail->sFilename );
      }
    }
  }

  /**
   * return all dashboards registered
   * @return array
   */
  function getDashboards( ) {
    return $this->_aDashboards;
    $dash = array ();
    foreach ( $this->_aDashboards as $row=>$detail ) {
      $sClassName = str_replace ( 'plugin', 'class', $this->_aPluginDetails[ $detail ]->sClassName);
      $dash[] = $sClassName;
    }
    return $dash;
  }

  /**
   * this function returns all reports registered
   * @return array
   */
  function getReports( ) {
    return $this->_aReports;
    $report = array ();
    foreach ( $this->_aReports as $row=>$detail ) {
      $sClassName = str_replace ( 'plugin', 'class', $this->_aPluginDetails[ $detail ]->sClassName);
      $report[] = $sClassName;
    }
    return $report;
  }

  /**
   * This function returns all dashboards registered
   * @ array
   */
  function getPmFunctions( ) {
    return $this->_aPmFunctions;
    $dash = array ();
    foreach ( $this->_aPmFunctions as $row=>$detail ) {
      $sClassName = str_replace ( 'plugin', 'class', $this->_aPluginDetails[ $detail ]->sClassName);
      $dash[] = $sClassName;
    }
    return $dash;
  }

  /**
   * This function returns all steps registered
   *
   * @return string
   */
  function getSteps( ) {
    return $this->_aSteps;
  }

  /**
   * This function returns all redirect registered
   * @return string
   */
  function getRedirectLogins( ) {
    return $this->_aRedirectLogin;
  }

  /**
   * execute all triggers related to a triggerId
   *
   * @param unknown_type $menuId
   * @return object
   */
  function executeTriggers( $triggerId, $oData )
  {
    foreach ( $this->_aTriggers as $row=>$detail ) {
      if ( $triggerId == $detail->sTriggerId  ) {

        //review all folders registered for this namespace
        $found = false;
        $classFile = '';

        foreach ( $this->_aFolders as $row=>$folder ) {
          $fname = PATH_PLUGINS . $folder->sFolderName . PATH_SEP . 'class.' . $folder->sFolderName  .'.php';
          if ( $detail->sNamespace == $folder->sNamespace && file_exists ( $fname ) ) {
            $found = true;
            $classFile = $fname;
          }
        }
        if ( $found ) {
          require_once ( $classFile );
          $sClassName = substr($this->_aPluginDetails[ $detail->sNamespace ]->sClassName,0,1) . str_replace ( 'plugin', 'class', substr($this->_aPluginDetails[ $detail->sNamespace ]->sClassName,1));
          $obj = new $sClassName( );
          $methodName = $detail->sTriggerName;
          $response = $obj->{$methodName}( $oData );
          if (PEAR::isError($response) ) {
            print $response->getMessage();  return;
          }
          return $response;
        }
        else
        print "error in call method " . $detail->sTriggerName;
      }
    }
  }

  /**
   * verify if exists triggers related to a triggerId
   *
   * @param unknown_type $triggerId
   */
  function existsTrigger( $triggerId) {
    $found = false;
    foreach ( $this->_aTriggers as $row=>$detail ) {
      if ( $triggerId == $detail->sTriggerId  ) {

        //review all folders registered for this namespace
        foreach ( $this->_aFolders as $row=>$folder ) {
          $fname = PATH_PLUGINS . $folder->sFolderName . PATH_SEP . 'class.' . $folder->sFolderName  .'.php';
          if ( $detail->sNamespace == $folder->sNamespace && file_exists ( $fname ) ) {
            $found = true;
          }
        }
      }
    }
    return $found;
  }

  /**
   * Return info related to a triggerId
   *
   * @param unknown_type $triggerId
   * @return object
   */
  function getTriggerInfo( $triggerId) {
    $found = null;
    foreach ( $this->_aTriggers as $row=>$detail ) {
      if ( $triggerId == $detail->sTriggerId  ) {

        //review all folders registered for this namespace
        foreach ( $this->_aFolders as $row=>$folder ) {
          $fname = PATH_PLUGINS . $folder->sFolderName . PATH_SEP . 'class.' . $folder->sFolderName  .'.php';
          if ( $detail->sNamespace == $folder->sNamespace && file_exists ( $fname ) ) {
            $found = $detail;
          }
        }
      }
    }
    return $found;
  }

  /**
   * Register a trigger in the Singleton
   *
   * @param unknown_type $sTriggerId
   * @param unknown_type $sMethodFunction
   * @return void
   */
  function registerTrigger($sNamespace, $sTriggerId, $sTriggerName ) {
    $found = false;
    foreach ( $this->_aTriggers as $row=>$detail ) {
      if ( $sTriggerId == $detail->sTriggerId && $sNamespace == $detail->sNamespace )
      $found = true;
    }
    if ( !$found ) {
      $triggerDetail = new triggerDetail ($sNamespace, $sTriggerId, $sTriggerName);
      $this->_aTriggers[] = $triggerDetail;
    }
  }

  /**
   * get plugin
   *
   * @param unknown_type $sNamespace
   * @return void
   */
  function &getPlugin($sNamespace) {
    if (array_key_exists($sNamespace, $this->_aPlugins)) {
      return $this->_aPlugins[$sNamespace];
    }
    /*
     $aDetails = KTUtil::arrayGet($this->_aPluginDetails, $sNamespace);
     if (empty($aDetails)) {
     return null;
     }
     $sFilename = $aDetails[2];
     if (!empty($sFilename)) {
     require_once($sFilename);
     }
     $sClassName = $aDetails[0];
     $oPlugin =& new $sClassName($sFilename);
     $this->_aPlugins[$sNamespace] =& $oPlugin;
     return $oPlugin;
     */
  }

  /**
   * set company logo
   *
   * @param unknown_type $sNamespace
   * @param unknown_type $filename
   * @return void
   */
  function setCompanyLogo( $sNamespace, $filename ) {
    $found = false;
    foreach ( $this->_aPluginDetails as $row=>$detail ) {
      if ( $sNamespace == $detail->sNamespace )
      $this->_aPluginDetails[ $sNamespace ]->sCompanyLogo = $filename;
    }
  }

  /**
   * get company logo
   *
   * @param unknown_type $default
   * @return void
   */
  function getCompanyLogo( $default ) {
    $sCompanyLogo = $default;
    foreach ( $this->_aPluginDetails as $row=>$detail ) {
      if ( trim($detail->sCompanyLogo) != '' )
      $sCompanyLogo = $detail->sCompanyLogo;
    }
    return $sCompanyLogo;
  }

  /**
   * get setup Plugins
   *
   * @param unknown_type $default
   * @return void
   */
  function setupPlugins() {
    try {
      $iPlugins = 0;
      G::LoadClass ( 'serverConfiguration' );
      $oServerConf = & serverConf::getSingleton ();
      $oServerConf->addPlugin ( SYS_SYS,$this->_aPluginDetails );
      foreach ( $this->_aPluginDetails as $namespace=>$detail ) {
        if ( isset($detail->enabled ) && $detail->enabled ) {
          if ( !empty( $detail->sFilename) && file_exists ($detail->sFilename) ) {
            if (strpos($detail->sFilename, PATH_SEP) !== false) {
              $aux = explode ( PATH_SEP, $detail->sFilename );
            }
            else {
              $aux = explode ( chr(92), $detail->sFilename );
            }
            $sFilename = PATH_PLUGINS . $aux[ count($aux) -1];
            if (! file_exists($sFilename) ) continue;
            require_once( $sFilename);
            $oPlugin = new $detail->sClassName( $detail->sNamespace, $detail->sFilename );
            $this->_aPlugins[$detail->sNamespace] =& $oPlugin;

            $iPlugins++;
            //print ( "$iPlugins $namespace <br>");
            $oPlugin->setup();
          }
        }
      }
      $this->eevalidate();
      return $iPlugins;
    }
    catch ( Exception $e ) {
      global $G_PUBLISH;
      $aMessage['MESSAGE'] = $e->getMessage();
      $G_PUBLISH  = new Publisher;
      $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
      G::RenderPage( 'publish' );
      die;
    }

  }

  /**
   * this function execute a Method
   *
   * @param string $sNamespace
   * @param string $methodName
   * @param object $oData
   * @return object
   */
  function executeMethod( $sNamespace, $methodName, $oData ) {
    $response = null;
    try {
      $details = $this->_aPluginDetails[$sNamespace];
      $pluginFolder = $details->sPluginFolder;
      $className    = $details->sClassName;
      $classFile = PATH_PLUGINS . $pluginFolder . PATH_SEP . 'class.' . $pluginFolder .'.php';
      if ( file_exists ( $classFile ) ) {
        require_once ( $classFile );
        $sClassName=substr_replace($className,"class",-6,6);
        //$sClassName = str_replace ( 'plugin', 'class', $className );
        $obj = new $sClassName( );
        if ( !in_array ( $methodName, get_class_methods ($obj) ) ) {
          throw ( new Exception ( "The method '$methodName' doesn't exist in class '$sClassName' ") );
        }
        $obj->sNamespace    = $details->sNamespace;
        $obj->sClassName    = $details->sClassName;
        $obj->sFilename     = $details->sFilename;
        $obj->iVersion      = $details->iVersion;
        $obj->sFriendlyName = $details->sFriendlyName;
        $obj->sPluginFolder = $details->sPluginFolder;
        $response = $obj->{$methodName}( $oData );
      }
      return $response;
    }
    catch ( Exception $e ) {
      throw ($e);
    }
  }

  /**
   * this function gets Fields For Page  on Setup
   *
   * @param string $sNamespace
   * @return object
   */
  function getFieldsForPageSetup( $sNamespace ) {
    $oData = NULL;
    return $this->executeMethod ( $sNamespace, 'getFieldsForPageSetup', $oData);
  }

  /**
   * this function updates Fields For Page on Setup
   *
   * @param string $sNamespace
   * @return void
   */
  function updateFieldsForPageSetup( $sNamespace, $oData ) {
    if ( !isset ($this->_aPluginDetails[$sNamespace] ) ) {
      throw ( new Exception ( "The namespace '$sNamespace' doesn't exist in plugins folder." ) );
    };
    return $this->executeMethod ( $sNamespace, 'updateFieldsForPageSetup', $oData);
  }
  function eevalidate(){
    $fileL = PATH_DATA_SITE.'license.dat';
    $fileS = PATH_DATA.'license.dat';
    if((file_exists($fileL))||(file_exists($fileS))){//Found a License
      if(class_exists('pmLicenseManager')){
        $sSerializedFile = PATH_DATA_SITE . 'lmn.singleton';
        $pmLicenseManagerO =& pmLicenseManager::getSingleton();
        if ( file_exists ($sSerializedFile) ){
          $pmLicenseManagerO->unSerializeInstance( file_get_contents  ( $sSerializedFile ) );
        }
      }
    }
  }
  /**
   * Register a toolbar for dynaform editor in the singleton
   *
   * @param unknown_type $sNamespace
   * @param unknown_type $sToolbarId
   * @param unknown_type $sFilename
   */
  function registerToolbarFile($sNamespace, $sToolbarId, $sFilename ) {
    $found = false;
    foreach ( $this->_aToolbarFiles as $row=>$detail ) {
      if ( $sToolbarId == $detail->sToolbarId && $sNamespace == $detail->sNamespace )
      $found = true;
    }
    if ( !$found ) {
      $toolbarDetail = new toolbarDetail ($sNamespace, $sToolbarId, $sFilename);
      $this->_aToolbarFiles[] = $toolbarDetail;
    }
  }
  /**
   * return all toolbar files related to a sToolbarId
   *
   * @param unknown_type $sToolbarId (NORMAL, GRID)
   */
  function getToolbarOptions( $sToolbarId ) {
    foreach ( $this->_aToolbarFiles as $row=>$detail ) {
      if ( $sToolbarId == $detail->sToolbarId && file_exists ( $detail->sFilename ) ) {
        include ( $detail->sFilename );
      }
    }
  }
  /**
   * Register a Case Scheduler Plugin
   *
   */
  function registerCaseSchedulerPlugin($sNamespace, $sActionId, $sActionForm, $sActionSave, $sActionExecute, $sActionGetFields) {
    $found = false;
    foreach ( $this->_aCaseSchedulerPlugin as $row=>$detail )
    if ( $sActionId == $detail->sActionId && $sNamespace == $detail->sNamespace )
    $found = true;

    if ( !$found ) {
      $this->_aCaseSchedulerPlugin[] = new caseSchedulerPlugin ( $sNamespace, $sActionId, $sActionForm, $sActionSave, $sActionExecute, $sActionGetFields);
    }
  }
  /**
   * This function returns all Case Scheduler Plugins registered
   *
   * @return string
   */
  function getCaseSchedulerPlugins( ) {
    return $this->_aCaseSchedulerPlugin;
  }
  
   /**
   * Register a Task Extended property page in the singleton
   *
   * @param unknown_type $sNamespace
   * @param unknown_type $sPage
   */
  
  function registerTaskExtendedProperty($sNamespace, $sPage, $sName, $sIcon ) {
    $found = false;
    foreach ( $this->_aTaskExtendedProperties as $row=>$detail ) {
      if ( $sPage == $detail->sPage && $sNamespace == $detail->sNamespace ){
        $detail->sName=$sName;
        $detail->sIcon=$sIcon;
        $found = true;
      }
    }
    if ( !$found ) {
      $taskExtendedProperty = new taskExtendedProperty ($sNamespace, $sPage, $sName, $sIcon);
      $this->_aTaskExtendedProperties[] = $taskExtendedProperty;
    }
  }

  /**
   * return all dashboard pages
   *
   * @return array
   */
  function getTaskExtendedProperties() {
    return  $this->_aTaskExtendedProperties;
  }
}
