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
      /*G::loadClass('configuration');
      
      if( ! isset($_POST['lang']) ) 
        echo 'The lang id was not set!';
        
      $configuration = new Configurations;
      $configuration->aConfig = Array('LAN_ID' => $_POST['lang']);
      $configuration->saveConfig('LANGUAGE_ENVIRONMENT', '');
      
      //verifying if the config was stored correctly.
      $oConf = new Configurations;
      $oConf->loadConfig($x, 'LANGUAGE_ENVIRONMENT', '');
      $meta = $oConf->aConfig;
      */
      require_once "classes/model/Translation.php";
      $tranlationsList = Translation::getTranslationEnvironments();
      g::pr($tranlationsList); die;
      if( isset($meta['LAN_ID']) && $meta['LAN_ID'] == $_POST['lang'] ){
        echo 'The Setting was saved successfully!';
      } else {
        echo 'Some error occured while the setting was being save, try later please.';
      }
      break;
    
    case 'languagesList':
      require_once 'classes/model/Language.php';
      require_once 'classes/model/IsoCountry.php';
      require_once 'classes/model/Translation.php';
      G::loadClass('configuration');
      
      $isoCountry  = new isoCountry();
      //$lang        = new Language();
      $translation = new Translation();
      
      //$languagesList = $lang->getActiveLanguages();
      
      $response = new stdClass();
      //verifying if the config was stored correctly.
      //$langConf = new Configurations;
      //$langConf->loadConfig($x, 'LANGUAGE_ENVIRONMENT', '');
      //$langEnv = $langConf->aConfig;
      //print_r($langEnv);

      $translationsEnvList = $translation->getTranslationEnvironments();
      $i = 0;
      foreach( $translationsEnvList as $locale=>$translation) {
      
        $COUNTRY_ID = $translation['IC_UID'];
        $isoCountryRecord = $isoCountry->findById(strtoupper($COUNTRY_ID));
        
        $languagesList[$i]['LAN_ID']       = $translation['LAN_ID'];
        $languagesList[$i]['LOCALE']       = $translation['LOCALE'];
        $languagesList[$i]['LAN_FLAG']     = strtolower($isoCountryRecord['IC_UID']);
        $languagesList[$i]['NUM_RECORDS']  = $translation['NUM_RECORDS'];
        $languagesList[$i]['DATE']         = $translation['DATE'];
        $languagesList[$i]['LAN_NAME']     = $translation['HEADERS']['X-Poedit-Language'];
        $languagesList[$i]['COUNTRY_NAME'] = $translation['HEADERS']['X-Poedit-Country'];
        $languagesList[$i]['TRANSLATOR']   = htmlentities($translation['HEADERS']['Last-Translator']);
        $languagesList[$i]['REV_DATE']     = $translation['HEADERS']['PO-Revision-Date'];
        $languagesList[$i]['VERSION']      = $translation['HEADERS']['Project-Id-Version'];
        
        
        //$languagesList[$i]['DEFAULT'] = (isset($langEnv['LAN_ID']) && $langEnv['LAN_ID']==$translation['LAN_ID']) ? '1' : '0';
        $i++;
      }
      $translation = new Translation();
      
      $response->data = $languagesList;
      
      print(G::json_encode($response));
    break;

    case 'xml':
      echo $s = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
<catalog>
  <plant>
    <common>Bloodroot</common>
    <botanical>Sanguinaria canadensis</botanical>
    <zone>4</zone>
    <light>Mostly Shady</light>
    <price>2.44</price>
    <availability>03/15/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Columbine</common>
    <botanical>Aquilegia canadensis</botanical>
    <zone>3</zone>
    <light>Mostly Shady</light>
    <price>9.37</price>
    <availability>03/06/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Marsh Marigold</common>
    <botanical>Caltha palustris</botanical>
    <zone>4</zone>
    <light>Mostly Sunny</light>
    <price>6.81</price>
    <availability>05/17/2006</availability>
    <indoor>false</indoor>
  </plant>
  <plant>
    <common>Cowslip</common>
    <botanical>Caltha palustris</botanical>
    <zone>4</zone>
    <light>Mostly Shady</light>
    <price>9.90</price>
    <availability>03/06/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Dutchman's-Breeches</common>
    <botanical>Dicentra cucullaria</botanical>
    <zone>3</zone>
    <light>Mostly Shady</light>
    <price>6.44</price>
    <availability>01/20/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Ginger, Wild</common>
    <botanical>Asarum canadense</botanical>
    <zone>3</zone>
    <light>Mostly Shady</light>
    <price>9.03</price>
    <availability>04/18/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Hepatica</common>
    <botanical>Hepatica americana</botanical>
    <zone>4</zone>
    <light>Mostly Shady</light>
    <price>4.45</price>
    <availability>01/26/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Liverleaf</common>
    <botanical>Hepatica americana</botanical>
    <zone>4</zone>
    <light>Mostly Shady</light>
    <price>3.99</price>
    <availability>01/02/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Jack-In-The-Pulpit</common>
    <botanical>Arisaema triphyllum</botanical>
    <zone>4</zone>
    <light>Mostly Shady</light>
    <price>3.23</price>
    <availability>02/01/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Mayapple</common>
    <botanical>Podophyllum peltatum</botanical>
    <zone>3</zone>
    <light>Mostly Shady</light>
    <price>2.98</price>
    <availability>06/05/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Phlox, Woodland</common>
    <botanical>Phlox divaricata</botanical>
    <zone>3</zone>
    <light>Sun or Shade</light>
    <price>2.80</price>
    <availability>01/22/2006</availability>
    <indoor>false</indoor>
  </plant>
  <plant>
    <common>Phlox, Blue</common>
    <botanical>Phlox divaricata</botanical>
    <zone>3</zone>
    <light>Sun or Shade</light>
    <price>5.59</price>
    <availability>02/16/2006</availability>
    <indoor>false</indoor>
  </plant>
  <plant>
    <common>Spring-Beauty</common>
    <botanical>Claytonia Virginica</botanical>
    <zone>7</zone>
    <light>Mostly Shady</light>
    <price>6.59</price>
    <availability>02/01/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Trillium</common>
    <botanical>Trillium grandiflorum</botanical>
    <zone>5</zone>
    <light>Sun or Shade</light>
    <price>3.90</price>
    <availability>04/29/2006</availability>
    <indoor>false</indoor>
  </plant>
  <plant>
    <common>Wake Robin</common>
    <botanical>Trillium grandiflorum</botanical>
    <zone>5</zone>
    <light>Sun or Shade</light>
    <price>3.20</price>
    <availability>02/21/2006</availability>
    <indoor>false</indoor>
  </plant>
  <plant>
    <common>Violet, Dog-Tooth</common>
    <botanical>Erythronium americanum</botanical>
    <zone>4</zone>
    <light>Shade</light>
    <price>9.04</price>
    <availability>02/01/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Trout Lily</common>
    <botanical>Erythronium americanum</botanical>
    <zone>4</zone>
    <light>Shade</light>
    <price>6.94</price>
    <availability>03/24/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Adder's-Tongue</common>
    <botanical>Erythronium americanum</botanical>
    <zone>4</zone>
    <light>Shade</light>
    <price>9.58</price>
    <availability>04/13/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Anemone</common>
    <botanical>Anemone blanda</botanical>
    <zone>6</zone>
    <light>Mostly Shady</light>
    <price>8.86</price>
    <availability>12/26/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Grecian Windflower</common>
    <botanical>Anemone blanda</botanical>
    <zone>6</zone>
    <light>Mostly Shady</light>
    <price>9.16</price>
    <availability>07/10/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Bee Balm</common>
    <botanical>Monarda didyma</botanical>
    <zone>4</zone>
    <light>Shade</light>
    <price>4.59</price>
    <availability>05/03/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Bergamot</common>
    <botanical>Monarda didyma</botanical>
    <zone>4</zone>
    <light>Shade</light>
    <price>7.16</price>
    <availability>04/27/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Black-Eyed Susan</common>
    <botanical>Rudbeckia hirta</botanical>
    <zone>Annual</zone>
    <light>Sunny</light>
    <price>9.80</price>
    <availability>06/18/2006</availability>
    <indoor>false</indoor>
  </plant>
  <plant>
    <common>Buttercup</common>
    <botanical>Ranunculus</botanical>
    <zone>4</zone>
    <light>Shade</light>
    <price>2.57</price>
    <availability>06/10/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Crowfoot</common>
    <botanical>Ranunculus</botanical>
    <zone>4</zone>
    <light>Shade</light>
    <price>9.34</price>
    <availability>04/03/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Butterfly Weed</common>
    <botanical>Asclepias tuberosa</botanical>
    <zone>Annual</zone>
    <light>Sunny</light>
    <price>2.78</price>
    <availability>06/30/2006</availability>
    <indoor>false</indoor>
  </plant>
  <plant>
    <common>Cinquefoil</common>
    <botanical>Potentilla</botanical>
    <zone>Annual</zone>
    <light>Shade</light>
    <price>7.06</price>
    <availability>05/25/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Primrose</common>
    <botanical>Oenothera</botanical>
    <zone>3 - 5</zone>
    <light>Sunny</light>
    <price>6.56</price>
    <availability>01/30/2006</availability>
    <indoor>false</indoor>
  </plant>
  <plant>
    <common>Gentian</common>
    <botanical>Gentiana</botanical>
    <zone>4</zone>
    <light>Sun or Shade</light>
    <price>7.81</price>
    <availability>05/18/2006</availability>
    <indoor>false</indoor>
  </plant>
  <plant>
    <common>Blue Gentian</common>
    <botanical>Gentiana</botanical>
    <zone>4</zone>
    <light>Sun or Shade</light>
    <price>8.56</price>
    <availability>05/02/2006</availability>
    <indoor>false</indoor>
  </plant>
  <plant>
    <common>Jacob's Ladder</common>
    <botanical>Polemonium caeruleum</botanical>
    <zone>Annual</zone>
    <light>Shade</light>
    <price>9.26</price>
    <availability>02/21/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Greek Valerian</common>
    <botanical>Polemonium caeruleum</botanical>
    <zone>Annual</zone>
    <light>Shade</light>
    <price>4.36</price>
    <availability>07/14/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>California Poppy</common>
    <botanical>Eschscholzia californica</botanical>
    <zone>Annual</zone>
    <light>Sunny</light>
    <price>7.89</price>
    <availability>03/27/2006</availability>
    <indoor>false</indoor>
  </plant>
  <plant>
    <common>Shooting Star</common>
    <botanical>Dodecatheon</botanical>
    <zone>Annual</zone>
    <light>Mostly Shady</light>
    <price>8.60</price>
    <availability>05/13/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Snakeroot</common>
    <botanical>Cimicifuga</botanical>
    <zone>Annual</zone>
    <light>Shade</light>
    <price>5.63</price>
    <availability>07/11/2006</availability>
    <indoor>true</indoor>
  </plant>
  <plant>
    <common>Cardinal Flower</common>
    <botanical>Lobelia cardinalis</botanical>
    <zone>2</zone>
    <light>Shade</light>
    <price>3.02</price>
    <availability>02/22/2006</availability>
    <indoor>true</indoor>
  </plant>
</catalog>";
      
      break;
  }
} catch ( Exception $oException ) {
  die($oException->getMessage());
}
?>