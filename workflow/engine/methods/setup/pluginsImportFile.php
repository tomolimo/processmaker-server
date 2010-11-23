<?
/**
 * processes_ImportFile.php
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

global $RBAC;
$access = $RBAC->userCanAccess('PM_SETUP_ADVANCE');
if( $access != 1 ){
  switch ($access)
  {
  	case -1:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	case -2:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	default:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;  	
  }
}  

 try {
  //load the variables
  G::LoadClass('plugin');
  if ( ! isset($_FILES['form']['error']['PLUGIN_FILENAME'] ) || $_FILES['form']['error']['PLUGIN_FILENAME'] == 1 ) {
    throw ( new Exception ("There was an error uploading the file, probably the file size if greater than upload_max_filesize parameter in php.ini, please check this parameter and try again." ) );
  }

  //save the file
  if ($_FILES['form']['error']['PLUGIN_FILENAME'] == 0) {
    $filename = $_FILES['form']['name']['PLUGIN_FILENAME'];
    $path     = PATH_DOCUMENT . 'input' . PATH_SEP ;
    $tempName = $_FILES['form']['tmp_name']['PLUGIN_FILENAME'];
    G::uploadFile($tempName, $path, $filename );
  }
  if ( ! $_FILES['form']['type']['PLUGIN_FILENAME'] == 'application/octet-stream')
    throw ( new Exception ( "the uploaded files are invalid, expected 'application/octect-stream mime type file (".  $_FILES['form']['type']['PLUGIN_FILENAME'] . ")" ));


  G::LoadThirdParty( 'pear/Archive','Tar');
  $tar = new Archive_Tar ( $path. $filename);
  $sFileName  = substr($filename,0,strrpos($filename, '.' ));
  $sClassName = substr($filename,0,strpos($filename, '-' ));

  $aFiles = $tar->listContent();
  $bMainFile = false;
  $bClassFile = false;
  foreach ( $aFiles as $key => $val ) {
    if ( $val['filename'] == $sClassName . '.php' ) $bMainFile = true;
    if ( $val['filename'] == $sClassName . PATH_SEP . 'class.' . $sClassName . '.php' ) $bClassFile = true;
  }

  $oPluginRegistry =& PMPluginRegistry::getSingleton();
  $pluginFile = $sClassName . '.php';

  if ( $bMainFile && $bClassFile ) {
    $sAux = $sClassName . 'Plugin';
    $fVersionOld = 0.0;
    if (file_exists(PATH_PLUGINS . $pluginFile)) {
      if (!class_exists($sAux) && !class_exists($sClassName . 'plugin')) {
        include PATH_PLUGINS . $pluginFile;
      }
      if (!class_exists($sAux)) {
        $sAux = $sClassName . 'plugin';
      }
      $oClass = new $sAux($sClassName);
      $fVersionOld = $oClass->iVersion;
      unset($oClass);
    }
    $res = $tar->extract ( $path );
    $sContent = file_get_contents($path . PATH_SEP . $pluginFile);
    $sContent = str_ireplace($sAux, $sAux . '_', $sContent);
    $sContent = str_ireplace('$oPluginRegistry =& PMPluginRegistry::getSingleton();', '', $sContent);
    $sContent = str_ireplace('$oPluginRegistry->registerPlugin(\'' . $sClassName . '\', __FILE__);', '', $sContent);
    //header('Content-Type: text/plain');var_dump($sClassName, $sContent);die;
    file_put_contents($path . PATH_SEP . $pluginFile, $sContent);
    $sAux = $sAux . '_';
    include $path . PATH_SEP . $pluginFile;
    $oClass = new $sAux($sClassName);
    $fVersionNew = $oClass->iVersion;
    if (!isset($oClass->iPMVersion)) {
      $oClass->iPMVersion = 0;
    }
    if ($oClass->iPMVersion > 0) {
      if (!defined('PM_VERSION')) {
        define('PM_VERSION', 0);
      }
      if (PM_VERSION > 0) {
        if ($oClass->iPMVersion > PM_VERSION) {
          //throw new Exception('This plugin needs version ' . $oClass->iPMVersion . ' or higher of ProcessMaker');
        }
      }
    }
    if (!isset($oClass->aDependences)) {
      $oClass->aDependences = null;
    }
    if (!empty($oClass->aDependences)) {
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
    }
    $res = $tar->extract ( PATH_PLUGINS );
  }
  else
    throw ( new Exception ( "The file $filename doesn't contain class: $sClassName ") ) ;

  if ( !file_exists ( PATH_PLUGINS . $sClassName . '.php' ) ) throw ( new Exception( "File '$pluginFile' doesn't exists ") );

  require_once ( PATH_PLUGINS . $pluginFile );
  $details = $oPluginRegistry->getPluginDetails( $pluginFile );

  $oPluginRegistry->installPlugin( $details->sNamespace);
  $oPluginRegistry->setupPlugins(); //get and setup enabled plugins
  $size = file_put_contents  ( PATH_DATA_SITE . 'plugin.singleton', $oPluginRegistry->serializeInstance() );

  //G::header ( 'Location: pluginsList');
  // this additional parameter pluginsList enables the load of the plugin list panel inside de main admin panel iframe
  print "
  <script>
  parent.window.location.href = 'main_init?action=pluginsList';
  </script>
  ";

}
catch ( Exception $e ){
  $G_PUBLISH = new Publisher;
	$aMessage['MESSAGE'] = $e->getMessage();
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
  G::RenderPage('publishBlank', 'blank');
}
