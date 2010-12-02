<?php
/**
 * pluginsRemove.php
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
/*
global $RBAC;
switch ($RBAC->userCanAccess('PM_SETUP_ADVANCE'))
{
	case -2:
	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
	  G::header('location: ../login/login');
	  die;
	break;
	case -1:
	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
	  G::header('location: ../login/login');
	  die;
	break;
}*/

/**
 * function rmdir_recursive
 * @author gustavo cruz gustavo-at-colosa-dot-com
 * @param $dir directory to be erased
 * @desc Fork of the rmdir native command this one also erase
 *       the content of the directory recursively.
 */
function rmdir_recursive($dir) {
     $files = scandir($dir);
     array_shift($files);    // remove '.' from array
     array_shift($files);    // remove '..' from array

     foreach ($files as $file) {
          $file = $dir . '/' . $file;
          if (is_dir($file)) {
             rmdir_recursive($file);
//             rmdir($file);
          } else {
             unlink($file);
          }
     }
     //rmdir($dir);
}

G::LoadClass('plugin');
$oPluginRegistry =& PMPluginRegistry::getSingleton();

$oDir = PATH_PLUGINS.trim($_POST['pluginUid']);
$oFile = PATH_PLUGINS.$_POST['pluginUid'].".php";
$pluginFile = $_POST['pluginUid'].".php";
//G::pr($pluginFile);
//G::pr($oFile);
if ($handle = opendir( PATH_PLUGINS )) {
   while ( false !== ($file = readdir($handle))) {
       if ( strpos($file, '.php',1) ) {
         if ( $file == $pluginFile ) {
           require_once ( PATH_PLUGINS . $pluginFile );
           $details = $oPluginRegistry->getPluginDetails( $pluginFile );
           $oPluginRegistry->enablePlugin( $details->sNamespace);
           $oPluginRegistry->disablePlugin( $details->sNamespace );
           $size = file_put_contents  ( PATH_DATA_SITE . 'plugin.singleton', $oPluginRegistry->serializeInstance() );
         }
       }
     }
   closedir($handle);
}

//$details = $oPluginRegistry->getPluginDetails( $oFile );
//G::pr($details);
//$oPluginRegistry->disablePlugin( $details->sNamespace );
//$size = file_put_contents ( PATH_DATA_SITE . 'plugin.singleton', $oPluginRegistry->serializeInstance() );
if ($oDir!=""&&$oFile!=""){
    if (is_dir($oDir)) {
        rmdir_recursive($oDir);
    }
    if (file_exists($oFile)){
        unlink($oFile);
    }
}

echo $_POST['pluginUid']." ".str_replace("\r\n","<br>",G::LoadTranslation('ID_MSG_REMOVE_PLUGIN_SUCCESS'));

