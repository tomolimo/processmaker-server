<?php
/**
 * languages_Export.php
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


require_once 'classes/model/IsoCountry.php';
require_once 'classes/model/Language.php';
require_once 'classes/model/Translation.php';

//G::LoadThirdParty('pear', 'Benchmark/Timer'); 
G::LoadSystem('i18n_po');

//echo G::getMemoryUsage();
//$timer = new Benchmark_Timer();
//$timer->start();

//creating the .po file

if( ! isset($_GET['LOCALE']) )
  throw new Exception('Language Target ID was not set!');
  
$sPOFile = PATH_CORE . 'content' . PATH_SEP . 'translations' . PATH_SEP . MAIN_POFILE . '.' . $_GET['LOCALE'] . '.po';
$poFile = new i18n_PO($sPOFile);
$poFile->buildInit();

$language = new Language();

$locale       = $_GET['LOCALE'];
$_TARGET_LANG = $_GET['LOCALE'];
$_BASE_LANG   = 'en';

if( strpos($locale, Translation::$localeSeparator) !== false ) {
  list($LAN_ID, $IC_UID) = explode(Translation::$localeSeparator, $_GET['LOCALE']);
  $iCountry = new IsoCountry();
  $iCountryRecord = $iCountry->findById($IC_UID);

  if( ! isset($iCountryRecord['IC_UID']) )
    throw new Exception("Country Target ID '{$_GET['LAN_ID']}' doesn't exist!");

  $sCountry = $iCountryRecord['IC_NAME'];
} else {
  $LAN_ID = $locale;
  $sCountry = $IC_UID = '';
}

$langRecord = $language->findById($LAN_ID);

if( ! isset($langRecord['LAN_NAME']) )
  throw new Exception("Language Target ID \"{$LAN_ID}\" doesn't exist!");
  
$sLanguage = $langRecord['LAN_NAME'];

//setting headers
$poFile->addHeader('Project-Id-Version'        , '1.8');
$poFile->addHeader('POT-Creation-Date'         , '');
$poFile->addHeader('PO-Revision-Date'          , date('Y-m-d H:i:s'));
$poFile->addHeader('Last-Translator'           , '');
$poFile->addHeader('Language-Team'             , 'Colosa Developers Team <developers@colosa.com>');
$poFile->addHeader('MIME-Version'              , '1.0');
$poFile->addHeader('Content-Type'              , 'text/plain; charset=utf-8');
$poFile->addHeader('Content-Transfer_Encoding' , '8bit');
$poFile->addHeader('X-Poedit-Language'         , ucwords($sLanguage));
$poFile->addHeader('X-Poedit-Country'          , ucwords($sCountry));
$poFile->addHeader('X-Poedit-SourceCharset'    , 'utf-8');
$poFile->addHeader('Content-Transfer-Encoding' , '8bit');

//$timer->setMarker('end making po headers');
//export translation

$aLabels = array();
$aMsgids = array();

// selecting of all translations records of base language 'en' in the PM TRANSLATIONS table
$oCriteria = new Criteria('workflow');
$oCriteria->addSelectColumn(TranslationPeer::TRN_CATEGORY);
$oCriteria->addSelectColumn(TranslationPeer::TRN_ID);
$oCriteria->addSelectColumn(TranslationPeer::TRN_VALUE);
$oCriteria->add(TranslationPeer::TRN_LANG, 'en');
$oDataset = TranslationPeer::doSelectRS($oCriteria);
$oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
$oDataset->next();

while ($aRow1 = $oDataset->getRow()) {
  if( $LAN_ID != 'en' ){
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(TranslationPeer::TRN_VALUE);
    $oCriteria->add(TranslationPeer::TRN_CATEGORY, $aRow1['TRN_CATEGORY']);
    $oCriteria->add(TranslationPeer::TRN_ID, $aRow1['TRN_ID']);
    $oCriteria->add(TranslationPeer::TRN_LANG, $_GET['LOCALE']);
    $oDataset2 = TranslationPeer::doSelectRS($oCriteria);
    $oDataset2->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset2->next();
    $aRow2 = $oDataset2->getRow();
  } else {
    $aRow2 = $aRow1;
  }
    $aRow1['TRN_CATEGORY'] = trim($aRow1['TRN_CATEGORY']);

    # Validation
    # implemented to validate that the TRN_CATEGORY field into TRANSLALTION table is valid
    # By Erik A. Ortiz <erik@colosa.com> on Thu Feb 4, 2010
    preg_match("/^[0-9a-zA-Z_-]+/", $aRow1['TRN_CATEGORY'], $sTestResult);
    
    if( $sTestResult[0] === $aRow1['TRN_CATEGORY']){ #the regular expr. evaluated ()$sTestResult) for $aRow1['TRN_CATEGORY'] must be the same
        $msgid = trim($aRow1['TRN_VALUE']);
        
        if ( isset($aMsgids[$msgid]) ) 
          $msgid = '[' . $aRow1['TRN_CATEGORY'] . '/' . $aRow1['TRN_ID'] . '] ' . $msgid;
        
        $poFile->addTranslatorComment('TRANSLATION');
        $poFile->addTranslatorComment($aRow1['TRN_CATEGORY'] . '/' . $aRow1['TRN_ID']);
        $poFile->addReference($aRow1['TRN_CATEGORY'] . '/' . $aRow1['TRN_ID']);
        
        $msgstr = ($aRow2 && $aRow2['TRN_VALUE'] != '' ) ?  $aRow2['TRN_VALUE'] : $aRow1['TRN_VALUE'];
        $poFile->addTranslation(stripslashes($msgid), stripslashes($msgstr));
        $aMsgids[$msgid] = true;
    } else { #Autocleaning, delete the inconsistant data 
        $oTranslation = new Translation;
        $oTranslation->remove($aRow1['TRN_CATEGORY'], $aRow1['TRN_ID'], $_GET['LAN_ID']);
    }
    $oDataset->next();
}

//$timer->setMarker('end making 1th .po from db');

//now find labels in xmlforms
/************/
$aExceptionFields = array('', 'javascript', 'hidden', 'phpvariable', 'private', 'toolbar', 'xmlmenu', 'toolbutton', 'cellmark', 'grid');

//find all xml files into PATH_XMLFORM
$aXMLForms        = glob(PATH_XMLFORM . '*/*.xml');
//from a sublevel to
$aXMLForms2       = glob(PATH_XMLFORM . '*/*/*.xml');
$aXMLForms = array_merge($aXMLForms, $aXMLForms2);

$aEnglishLabel    = array();
$aOptions         = array();
$nodesNames    = Array();

G::loadSystem('dynaformhandler');

foreach ($aXMLForms as $xmlFormPath) {
  $xmlFormFile = str_replace( chr(92), '/', $xmlFormPath);
  $xmlFormFile = str_replace( PATH_XMLFORM, '', $xmlFormPath);
  //$oForm = new Form( $sXmlForm, '', 'en');
  //echo $xmlFormPath;
  //echo '<br>';
  //echo '<br>';
  $dynaForm = new dynaFormHandler($xmlFormPath);
  
  $dynaNodes = $dynaForm->getFields();

  //get all fields of each xmlform
  foreach ($dynaNodes as $oNode) {
    
    $sNodeName = $oNode->nodeName;
    //$arrayNode = $dynaForm->getArray($oNode, Array('type', $_BASE_LANG, $_BASE_LANG)); 
    $arrayNode = $dynaForm->getArray($oNode);
    //if has not native language translation
    if( ! isset($arrayNode[$_BASE_LANG]) || ! isset($arrayNode['type']) || ( isset($arrayNode['type']) && in_array($arrayNode['type'], $aExceptionFields)) ){ 
      continue; //just continue with the next node
    }
    
    
    // Getting the Base Origin Text
    if( ! is_array($arrayNode[$_BASE_LANG]) )
      $originNodeText = trim($arrayNode[$_BASE_LANG]);
    else {
      $langNode = $arrayNode[$_BASE_LANG][0];
      $originNodeText = getTextValue($langNode);
    }
    
    /*if( strpos($xmlFormPath, 'patterns/patterns_GridParallelByEvaluationType.xml') !== false){
      g::pr($arrayNode);
      g::dump(getTextValue($langNode));
    }*/
   
    // Getting the Base Target Text
    if( isset($arrayNode[$_TARGET_LANG]) ) {
      if( ! is_array($arrayNode[$_TARGET_LANG]) )
        $targetNodeText = trim($arrayNode[$_TARGET_LANG]);
      else {
        $langNode = $arrayNode[$_TARGET_LANG][0];
        $targetNodeText = getTextValue($langNode);
      }
    } else {
      $targetNodeText = $originNodeText;
    }
    
    $nodeName = $arrayNode['__nodeName__'];
    $nodeType = $arrayNode['type'];
    /*echo 'NODENAME:'. $nodeName . '<br>';
    echo 'ORIGIN text:'. $originNodeText.'<br>';
    echo 'TARGET text:'. $targetNodeText.'<br>';
    echo 'NODE TYPE'. $arrayNode['type'].'<br>';
    */
    $msgid = $originNodeText;
    
    // if the nodeName already exists in the po file, we need to create other msgid
    if( isset($aMsgids[$msgid]) )
      $msgid = '[' . $xmlFormFile . '?' . $nodeName . '] ' . $originNodeText;
    
    $poFile->addTranslatorComment($xmlFormFile . '?' . $nodeName);
    $poFile->addTranslatorComment($xmlFormFile);
    $poFile->addReference($nodeType . ' - ' . $nodeName);
    $poFile->addTranslation($msgid, stripslashes($targetNodeText));
    
    /*if( strpos($xmlFormPath, 'patterns/patterns_GridParallelByEvaluationType.xml') !== false){
      echo 'msgstr: ' .$msgid, stripslashes($targetNodeText);
      echo '<br>';
    }*/
    
    
    $aMsgids[$msgid] = true;
    
    //if it is a dropdown field
    if( isset($arrayNode[$_BASE_LANG]) && isset($arrayNode[$_BASE_LANG][0]) && isset($arrayNode[$_BASE_LANG][0]['option']) ){
      
      $originOptionNode = $arrayNode[$_BASE_LANG][0]['option']; //get the options
      
      $targetOptionExists = false;
      if( isset($arrayNode[$_TARGET_LANG]) && isset($arrayNode[$_TARGET_LANG][0]) && isset($arrayNode[$_TARGET_LANG][0]['option']) ) {
        $targetOptionNode = $arrayNode[$_TARGET_LANG][0]['option'];
        $targetOptionExists = true;
      } 
      
      if ( ! is_array($originOptionNode) ){
        if( is_string($originOptionNode) ){
          $poFile->addTranslatorComment($xmlFormFile . '?' . $nodeName . '-'. $originOptionNode);
          $poFile->addTranslatorComment($xmlFormFile);
          $poFile->addReference($nodeType . ' - ' . $nodeName . ' - ' . $originOptionNode);
          $poFile->addTranslation($msgid, stripslashes($originOptionNode));
        }
      } else {
        foreach( $originOptionNode as $optionNode ) {
          $optionName = $optionNode['name'];
          $originOptionValue = getTextValue($optionNode);
          
          if( $targetOptionExists ){

            $targetOptionValue = getMatchDropdownOptionValue($optionName, $targetOptionNode);
            if( $targetOptionValue === false ){
              $targetOptionValue = $originOptionValue;
            }
          } else {
            $targetOptionValue = $originOptionValue;
          }
            
          $targetOptionValue = ($targetOptionValue != '') ? $targetOptionValue : "''"; 
          $optionName = ($optionName != '') ? $optionName : "''";
          
          $msgid = '[' . $xmlFormFile . '?' . $nodeName  . '-' . $optionName . ']';
          /*g::dump($xmlFormFile . '?' . $nodeName . '-'. $originOptionValue);
          g::dump($xmlFormFile);
          g::dump($nodeType . ' - ' . $nodeName . ' - ' . $originOptionValue);
          g::dump($msgid);
          g::dump($targetOptionValue);*/
          
          $poFile->addTranslatorComment($xmlFormFile . '?' . $nodeName . '-'. $optionName);
          $poFile->addTranslatorComment($xmlFormFile);
          $poFile->addReference($nodeType . ' - ' . $nodeName . ' - ' . $optionName);
          $poFile->addTranslation($msgid, stripslashes($targetOptionValue));
        }
      }
    }
	} //end foreach
	
}
//die;
//g::pr($aEnglishLabel); die;
/*******************/
//
//$timer->setMarker('end xml files processed');
//$profiling = $timer->getProfiling();
//$timer->stop(); $timer->display(); 
//echo G::getMemoryUsage();
//die;
//g::pr($profiling);

G::streamFile($sPOFile, true);

function getTextValue($arrayNode){
  return isset($arrayNode['#text']) ? $arrayNode['#text']: (isset($arrayNode['#cdata-section']) ? $arrayNode['#cdata-section']: '');
}

function getMatchDropdownOptionValue($name, $options){
  foreach($options as $option){
    //echo $name .'=='. $option['name'];
    //echo '----------------------------<br>';
    if($name == $option['name']){
      //echo '[[[[['.getTextValue($option).']]]]]';
      return getTextValue($option);
    }
  }
  return false;
}
