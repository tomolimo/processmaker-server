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
  if(isset($_POST['form'])) {
    $_POST = $_POST['form'];
  }
  $_POST['function'] = get_ajax_value('function');
  switch($_POST['function']) {
    case 'savePredetermined':
      G::loadClass('configuration');
      
      if( ! isset($_POST['lang']) ) 
        echo 'The lang id was not set!';
        
      $configuration = new Configurations;
      $configuration->aConfig = Array('LAN_ID' => $_POST['lang']);
      $configuration->saveConfig('LANGUAGE_ENVIRONMENT', '');
      
      //verifying if the config was stored correctly.
      $oConf = new Configurations;
      $oConf->loadConfig($x, 'LANGUAGE_ENVIRONMENT', '');
      $meta = $oConf->aConfig;
      
      if( isset($meta['LAN_ID']) && $meta['LAN_ID'] == $_POST['lang'] ){
        echo 'The Setting was saved successfully!';
      } else {
        echo 'Some error occured while the setting was being save, try later please.';
      }
      break;
    
    case 'languagesList':
      require_once 'classes/model/Language.php';
      require_once 'classes/model/IsoCountry.php';
      G::loadClass('configuration');
      
      $isoCountry = new isoCountry();
      $lang = new Language();
      
      
      $languagesList = $lang->getActiveLanguages();

      $response = new stdClass();
      //verifying if the config was stored correctly.
      $langConf = new Configurations;
      $langConf->loadConfig($x, 'LANGUAGE_ENVIRONMENT', '');
      $langEnv = $langConf->aConfig;
      //print_r($langEnv);
      foreach( $languagesList as $i=>$lang ) {
        $oConf = new Configurations; 
        $oConf->loadConfig($x, 'LANGUAGE_META', $lang['LAN_ID']);
        $meta = $oConf->aConfig;
        //print_r($meta);
        if( $lang['LAN_ID'] == 'en' )
          $langId = 'us';
        else 
          $langId = $lang['LAN_ID'];
          
        $isoCountryRecord = $isoCountry->findById(strtoupper($langId));
        $countryName = isset($isoCountryRecord['IC_NAME'])? $isoCountryRecord['IC_NAME']: 'Unknow';
        
        $languagesList[$i]['COUNTRY_NAME'] = $countryName;
        $languagesList[$i]['OBS']          = ''; //($lang['LAN_ID'] == 'es')? 'Need Update': '';
        
        if( count($meta) > 0 ) {
          $languagesList[$i]['DATE']         = $meta['import-date'];
          $languagesList[$i]['REV_DATE']     = $meta['headers']['PO-Revision-Date'];
          $languagesList[$i]['VERSION']      = $meta['headers']['Project-Id-Version'];
        } else {
          $languagesList[$i]['DATE']         = '';
          $languagesList[$i]['REV_DATE']     = '';
          $languagesList[$i]['VERSION']      = '';
        }
        
        $languagesList[$i]['DEFAULT'] = (isset($langEnv['LAN_ID']) && $langEnv['LAN_ID']==$lang['LAN_ID']) ? '1' : '0';
      }
      $response->data = $languagesList;
      
      print(G::json_encode($response));
    break;
  }
} catch ( Exception $oException ) {
  die($oException->getMessage());
}
?>