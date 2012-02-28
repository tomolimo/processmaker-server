<?php
/**
 * replacementLogo.php
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
try {//ini_set('display_errors','1');
  global $RBAC;
  switch ($RBAC->userCanAccess('PM_LOGIN'))
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
  }
  function changeNamelogo($snameLogo)
  {
    // The ereg_replace function has been DEPRECATED as of PHP 5.3.0.
    // $snameLogo = ereg_replace("[áàâãª]","a",$snameLogo);
    // $snameLogo = ereg_replace("[ÁÀÂÃ]","A",$snameLogo);
    // $snameLogo = ereg_replace("[ÍÌÎ]","I",$snameLogo);
    // $snameLogo = ereg_replace("[íìî]","i",$snameLogo);
    // $snameLogo = ereg_replace("[éèê]","e",$snameLogo);
    // $snameLogo = ereg_replace("[ÉÈÊ]","E",$snameLogo);
    // $snameLogo = ereg_replace("[óòôõº]","o",$snameLogo);
    // $snameLogo = ereg_replace("[ÓÒÔÕ]","O",$snameLogo);
    // $snameLogo = ereg_replace("[úùû]","u",$snameLogo);
    // $snameLogo = ereg_replace("[ÚÙÛ]","U",$snameLogo);
    $snameLogo = preg_replace("/[áàâãª]/", "a", $snameLogo);
    $snameLogo = preg_replace("/[ÁÀÂÃ]/",  "A", $snameLogo);
    $snameLogo = preg_replace("/[ÍÌÎ]/",   "I", $snameLogo);
    $snameLogo = preg_replace("/[íìî]/",   "i", $snameLogo);
    $snameLogo = preg_replace("/[éèê]/",   "e", $snameLogo);
    $snameLogo = preg_replace("/[ÉÈÊ]/",   "E", $snameLogo);
    $snameLogo = preg_replace("/[óòôõº]/", "o", $snameLogo);
    $snameLogo = preg_replace("/[ÓÒÔÕ]/",  "O", $snameLogo);
    $snameLogo = preg_replace("/[úùû]/",   "u", $snameLogo);
    $snameLogo = preg_replace("/[ÚÙÛ]/",   "U", $snameLogo);
    $snameLogo = str_replace("ç","c",$snameLogo);
    $snameLogo = str_replace("Ç","C",$snameLogo);
    $snameLogo = str_replace("[ñ]","n",$snameLogo);
    $snameLogo = str_replace("[Ñ]","N",$snameLogo);
    return ($snameLogo);
  }

  $sfunction =$_GET['function'];
  switch($sfunction){
   	case 'replacementLogo':
   	  $snameLogo=urldecode($_GET['NAMELOGO']);
         $snameLogo=trim($snameLogo);
         $snameLogo=changeNamelogo($snameLogo);
   	  G::loadClass('configuration');
   	  $oConf = new Configurations;
   	  $aConf = Array(
   	  	'WORKSPACE_LOGO_NAME' => SYS_SYS,
   	  	'DEFAULT_LOGO_NAME'   => $snameLogo
   	  );
   	  	
   	  $oConf->aConfig = $aConf;
   	  $oConf->saveConfig('USER_LOGO_REPLACEMENT', '', '','');
   	  
   	  G::SendTemporalMessage('ID_REPLACED_LOGO', 'tmp-info', 'labels');
   	  //header('location: uplogo.php');
   	  //G::header('location: uplogo');
    break;
    case 'restoreLogo':
    $snameLogo=$_GET['NAMELOGO'];
   	  G::loadClass('configuration');
   	  $oConf = new Configurations;
   	  $aConf = Array(
   	  	'WORKSPACE_LOGO_NAME' => '',
   	  	'DEFAULT_LOGO_NAME'   => '' 
   	  );
   	  	
   	  $oConf->aConfig = $aConf;
   	  $oConf->saveConfig('USER_LOGO_REPLACEMENT', '', '','');
   	  
   	  
   	  G::SendTemporalMessage('ID_REPLACED_LOGO', 'tmp-info', 'labels');
    break;             

   }
  
}
catch (Exception $oException) {
	die($oException->getMessage());
}
?>
