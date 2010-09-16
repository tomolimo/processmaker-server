<?php
/**
 * language_Ajax.php
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
try {

  G::LoadInclude('ajax');
  if (isset($_POST['form']))
  {
  	$_POST = $_POST['form'];
  }
  $_POST['function'] = get_ajax_value('function');
  switch ($_POST['function'])
  {
  	case 'languageSelect':
  	  //print_r($_POST['lang']);  	die;  
  	  require_once 'classes/model/Configuration.php';
		  $oConfiguration = new Configuration();
		  $sDelimiter     = DBAdapter::getStringDelimiter();
		  $oCriteria      = new Criteria('workflow');
		  $oCriteria->add(ConfigurationPeer::CFG_UID, 'Language');		  
		  $oCriteria->add(ConfigurationPeer::OBJ_UID, '');
		  //$oCriteria->add(ConfigurationPeer::CFG_VALUE, '');
  		$oCriteria->add(ConfigurationPeer::PRO_UID, '');
  		$oCriteria->add(ConfigurationPeer::USR_UID, '');
  		$oCriteria->add(ConfigurationPeer::APP_UID, '');

		  if(ConfigurationPeer::doCount($oCriteria)==0)
		   {
		   		$aData['CFG_UID']   = 'Language';
					$aData['OBJ_UID']   = ''; 
					$aData['CFG_VALUE'] = $_POST['lang'];
					$aData['PRO_UID']   = ''; 
					$aData['USR_UID']   = ''; 
					$aData['APP_UID']   = ''; 
		
		  		$oConfig = new Configuration();		  	  
		  	  $oConfig->create($aData);  		
		   }		 
		  else
		   {
		   	  $oCriteria1 = new Criteria('workflow');
		  		$oCriteria1->add(ConfigurationPeer::CFG_VALUE, $_POST['lang']);
		  		$oCriteria2 = new Criteria('workflow');
		  		$oCriteria2->add(ConfigurationPeer::CFG_UID, 'Language');
		  		BasePeer::doUpdate($oCriteria2, $oCriteria1, Propel::getConnection('workflow'));
		   } 
  	
  	break;    	  	
  }   
}
catch (Exception $oException) {
	die($oException->getMessage());
}
?>