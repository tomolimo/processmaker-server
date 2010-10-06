<?php
/**
 * class.plugin.php
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

require_once ( 'class.pluginRegistry.php');

define ( 'G_PLUGIN_CLASS',     1 );

define ( 'PM_CREATE_CASE',       1001 );
define ( 'PM_UPLOAD_DOCUMENT',   1002 );
define ( 'PM_CASE_DOCUMENT_LIST',1003 );
define ( 'PM_BROWSE_CASE',       1004 );
define ( 'PM_NEW_PROCESS_LIST',  1005 );
define ( 'PM_NEW_PROCESS_SAVE',  1006 );
define ( 'PM_NEW_DYNAFORM_LIST', 1007 );
define ( 'PM_NEW_DYNAFORM_SAVE', 1008 );
define ( 'PM_EXTERNAL_STEP',     1009 );
define ( 'PM_CASE_DOCUMENT_LIST_ARR', 1010 );
define ( 'PM_LOGIN', 1011 );
define ( 'PM_UPLOAD_DOCUMENT_BEFORE', 1012 );


class menuDetail {
  var $sNamespace;
  var $sMenuId;
  var $sFilename;
  /**
  * This function is the constructor of the menuDetail class
  * @param string $sNamespace
  * @param string $sMenuId
  * @param string $sFilename
  * @return void
  */
  function __construct( $sNamespace, $sMenuId, $sFilename ) {
    $this->sNamespace = $sNamespace;
    $this->sMenuId    = $sMenuId;
    $this->sFilename  = $sFilename;
  }
 }
class toolbarDetail {
  var $sNamespace;
  var $sToolbarId;
  var $sFilename;
  /**
  * This function is the constructor of the menuDetail class
  * @param string $sNamespace
  * @param string $sMenuId
  * @param string $sFilename
  * @return void
  */
  function __construct( $sNamespace, $sToolbarId, $sFilename ) {
    $this->sNamespace = $sNamespace;
    $this->sToolbarId    = $sToolbarId;
    $this->sFilename  = $sFilename;
  }
 }
 class dashboardPage {
  var $sNamespace;
  var $sPage;
  var $sName;  
  var $sIcon;    
  /**
  * This function is the constructor of the dashboardPage class
  * @param string $sNamespace
  * @param string $sPage  
  * @return void
  */
  function __construct( $sNamespace, $sPage, $sName, $sIcon ) {
    $this->sNamespace = $sNamespace;
    $this->sPage    = $sPage;    
    $this->sName    = $sName;
    $this->sIcon    = $sIcon;
  }
 }

class cssFile {
  var $sNamespace;
  var $sCssFile;
  /**
  * This function is the constructor of the dashboardPage class
  * @param string $sNamespace
  * @param string $sPage  
  * @return void
  */
  function __construct( $sNamespace, $sCssFile) {
    $this->sNamespace = $sNamespace;
    $this->sCssFile    = $sCssFile;        
  }
 }
 
class triggerDetail {
  var $sNamespace;
  var $sTriggerId;
  var $sTriggerName;

  /**
  * This function is the constructor of the triggerDetail class
  * @param string $sNamespace
  * @param string $sTriggerId
  * @param string $sTriggerName
  * @return void
  */
  function __construct( $sNamespace, $sTriggerId, $sTriggerName ) {
    $this->sNamespace   = $sNamespace;
    $this->sTriggerId   = $sTriggerId;
    $this->sTriggerName = $sTriggerName;
  }
}

class folderDetail {
  var $sNamespace;
  var $sFolderId;
  var $sFolderName;

  /**
  * This function is the constructor of the folderDetail class
  * @param string $sNamespace
  * @param string $sFolderId
  * @param string $sFolderName
  * @return void
  */
  function __construct( $sNamespace, $sFolderId, $sFolderName ) {
    $this->sNamespace  = $sNamespace;
    $this->sFolderId   = $sFolderId;
    $this->sFolderName = $sFolderName;
   }
}

class stepDetail {
  var $sNamespace;
  var $sStepId;
  var $sStepName;
  var $sStepTitle;
  var $sSetupStepPage;

  /**
  * This function is the constructor of the stepDetail class
  * @param string $sNamespace
  * @param string $sStepId
  * @param string $sStepName
  * @param string $sStepTitle
  * @param string $sSetupStepPage
  * @return void
  */
  function __construct( $sNamespace, $sStepId, $sStepName, $sStepTitle, $sSetupStepPage ) {
    $this->sNamespace     = $sNamespace;
    $this->sStepId        = $sStepId;
    $this->sStepName      = $sStepName;
    $this->sStepTitle     = $sStepTitle;
    $this->sSetupStepPage = $sSetupStepPage;
   }
}

class redirectDetail {
  var $sNamespace;
  var $sRoleCode;
  var $sPathMethod;

  /**
  * This function is the constructor of the redirectDetail class
  * @param string $sNamespace
  * @param string $sRoleCode
  * @param string $sPathMethod
  * @return void
  */
  function __construct( $sNamespace, $sRoleCode, $sPathMethod ) {
    $this->sNamespace  = $sNamespace;
    $this->sRoleCode   = $sRoleCode;
    $this->sPathMethod = $sPathMethod;
   }
}

class folderData {
  var $sProcessUid;
  var $sProcessTitle;
  var $sApplicationUid;
  var $sApplicationTitle;
  var $sUserUid;
  var $sUserLogin;
  var $sUserFullName;

  /**
  * This function is the constructor of the folderData class
  * @param string $sProcessUid
  * @param string $sProcessTitle
  * @param string $sApplicationUid
  * @param string $sApplicationTitle
  * @param string $sUserUid
  * @param string $sUserLogin
  * @param string $sUserFullName
  * @return void
  */
  function __construct( $sProcessUid, $sProcessTitle, $sApplicationUid, $sApplicationTitle, $sUserUid, $sUserLogin = '', $sUserFullName ='') {
    $this->sProcessUid       = $sProcessUid;
    $this->sProcessTitle     = $sProcessTitle;
    $this->sApplicationUid   = $sApplicationUid;
    $this->sApplicationTitle = $sApplicationTitle;
    $this->sUserUid          = $sUserUid;
    $this->sUserLogin        = $sUserLogin;
    $this->sUserFullName     = $sUserFullName;
   }
}

class uploadDocumentData {
  var $sApplicationUid;
  var $sUserUid;
  var $sFilename;
  var $sFileTitle;
  var $sDocumentUid;
  var $bUseOutputFolder;
  var $iVersion;

  /**
  * This function is the constructor of the uploadDocumentData class
  * @param string $sApplicationUid
  * @param string $sUserUid
  * @param string $sFilename
  * @param string $sFileTitle
  * @param string $sDocumentUid
  * @param integer $iVersion
  * @return void
  */
  function __construct( $sApplicationUid, $sUserUid, $sFilename, $sFileTitle, $sDocumentUid, $iVersion = 1 ) {
    $this->sApplicationUid = $sApplicationUid;
    $this->sUserUid        = $sUserUid;
    $this->sFilename       = $sFilename;
    $this->sFileTitle      = $sFileTitle;
    $this->sDocumentUid    = $sDocumentUid;
    $this->bUseOutputFolder = false;
    $this->iVersion    = $iVersion;
   }
}
class loginInfo {
  var $lName;
  var $lPassword;
  var $lSession;

  /**
  * This function is the constructor of the loginInfo class
  * @param string $lName
  * @param string $lPassword
  * @param string $lSession
  * @return void
  */
  function __construct( $lName, $lPassword, $lSession ) {
    $this->lName = $lName;
    $this->lPassword    = $lPassword;
    $this->lSession  = $lSession;
  }
}

class PMPlugin {
  var $sNamespace;
  var $sClassName;
  var $sFilename = null;
  var $iVersion = 0;
  var $sFriendlyName = null;
  var $sPluginFolder = '';
  var $aWorkspaces = null;
  var $bPrivate = false;

  /**
  * This function sets values to the plugin
  * @param string $sNamespace
  * @param string $sFilename
  * @return void
  */
  function PMPlugin($sNamespace, $sFilename = null) {
    $this->sNamespace    = $sNamespace;
    $this->sClassName    = $sNamespace . 'Plugin';
    $this->sPluginFolder = $sNamespace;
    $this->sFilename     = $sFilename;
  }

  /**
  * With this function we can register the MENU
  * @param string $menuId
  * @param string $menuFilename
  * @return void
  */
  function registerMenu( $menuId, $menuFilename ) {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $sMenuFilename   = PATH_PLUGINS . $this->sPluginFolder . PATH_SEP . $menuFilename;
    $oPluginRegistry->registerMenu ( $this->sNamespace, $menuId, $sMenuFilename);
  }

 /**
  * With this function we can register the dashboard
  * @param
  * @return void
  */
  function registerDashboard( ) {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $oPluginRegistry->registerDashboard ( $this->sNamespace);
  }
  

  /**
  * With this function we can register the report
  * @param
  * @return void
  */
  function registerReport( ) {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $oPluginRegistry->registerReport ( $this->sNamespace);
  }

  /**
  * With this function we can register the pm's function
  * @param
  * @return void
  */
  function registerPmFunction( ) {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $oPluginRegistry->registerPmFunction ( $this->sNamespace);
  }

  /**
  * With this function we can set the company's logo
  * @param
  * @return void
  */
  function setCompanyLogo( $filename ) {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $oPluginRegistry->setCompanyLogo( $this->sNamespace, $filename);
  }

  /**
  * With this function we can register the pm's function
  * @param
  * @return void
  */
  function redirectLogin( $role, $pathMethod ) {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $oPluginRegistry->registerRedirectLogin( $this->sNamespace, $role, $pathMethod );
  }

  /**
   * Register a folder for methods
   *
   * @param unknown_type $sFolderName
   */
  function registerFolder($sFolderId, $sFolderName ) {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $oPluginRegistry->registerFolder( $this->sNamespace, $sFolderId, $sFolderName );
  }

  /**
  * With this function we can register the steps
  * @param
  * @return void
  */
  function registerStep($sStepId, $sStepName, $sStepTitle, $sSetupStepPage  = '') {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $oPluginRegistry->registerStep( $this->sNamespace, $sStepId, $sStepName, $sStepTitle, $sSetupStepPage );
  }

  /**
  * With this function we can register the triggers
  * @param string $sTriggerId
  * @param string $sTriggerName
  * @return void
  */
  function registerTrigger( $sTriggerId, $sTriggerName ) {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $oPluginRegistry->registerTrigger ( $this->sNamespace, $sTriggerId, $sTriggerName );
  }

  /**
  * With this function we can delete a file
  * @param string $sFilename
  * @param string $bAbsolutePath
  * @return void
  */
  function delete($sFilename, $bAbsolutePath = false) {
    if (!$bAbsolutePath) {
      $sFilename = PATH_PLUGINS . $this->sPluginFolder . PATH_SEP . $sFilename;
    }
    @unlink($sFilename);
  }

  /**
  * With this function we can copy a files
  * @param string $sSouce
  * @param string $sTarget
  * @param string $bSourceAbsolutePath
  * @param string $bTargetAbsolutePath
  * @return void
  */
  function copy($sSouce, $sTarget, $bSourceAbsolutePath = false, $bTargetAbsolutePath = false) {
    if (!$bSourceAbsolutePath) {
      $sSouce = PATH_PLUGINS . $this->sPluginFolder . PATH_SEP . $sSouce;
    }
    if (!$bTargetAbsolutePath) {
      $sTarget = PATH_PLUGINS . $this->sPluginFolder . PATH_SEP . $sTarget;
    }
    G::verifyPath(dirname($sTarget), true);
    @copy($sSouce, $sTarget);
  }

  /**
  * With this function we can rename a files
  * @param string $sSouce
  * @param string $sTarget
  * @param string $bSourceAbsolutePath
  * @param string $bTargetAbsolutePath
  * @return void
  */
  function rename($sSouce, $sTarget, $bSourceAbsolutePath = false, $bTargetAbsolutePath = false) {
    if (!$bSourceAbsolutePath) {
      $sSouce = PATH_PLUGINS . $this->sPluginFolder . PATH_SEP . $sSouce;
    }
    if (!$bTargetAbsolutePath) {
      $sTarget = PATH_PLUGINS . $this->sPluginFolder . PATH_SEP . $sTarget;
    }
    G::verifyPath(dirname($sTarget), true);
    @chmod(dirname($sTarget), 0777);
    @rename($sSouce, $sTarget);
  }

  /**
  * This function registers a page who is break
  * @param string $pageId
  * @param string $templateFilename
  * @return void
  */
  function registerBreakPageTemplate( $pageId, $templateFilename ) {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $sPageFilename = PATH_PLUGINS . $this->sPluginFolder . PATH_SEP . $templateFilename;
    $oPluginRegistry->registerBreakPageTemplate ( $this->sNamespace, $pageId, $sPageFilename);
  }
/**
  * With this function we can register a Dashboard Page for Cases Dashboard
  * @param string $sPage
  * @return void
  */
  function registerDashboardPage( $sPage, $sName, $sIcon="") {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $oPluginRegistry->registerDashboardPage ( $this->sNamespace, $sPage, $sName, $sIcon );
  }
  /**
  * With this function we can register a Dashboard Page for Cases Dashboard
  * @param string $sPage
  * @return void
  */
  function registerCss( $sCssFile) {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $oPluginRegistry->registerCss ( $this->sNamespace, $sCssFile );
  }
  /**
  * With this function we can register the toolbar file for dynaform editor
  * @param string $menuId
  * @param string $menuFilename
  * @return void
  */
  function registerToolbarFile( $sToolbarId, $filename ) {
    $oPluginRegistry =& PMPluginRegistry::getSingleton();
    $sFilename   = PATH_PLUGINS . $this->sPluginFolder . PATH_SEP . $filename;
    $oPluginRegistry->registerToolbarFile ( $this->sNamespace, $sToolbarId, $sFilename);
  }
}