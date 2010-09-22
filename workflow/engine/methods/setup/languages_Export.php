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
$sPOFile = PATH_CORE . 'content' . PATH_SEP . 'translations' . PATH_SEP . MAIN_POFILE . '.' . $_GET['LAN_ID'] . '.po';
$poFile = new i18n_PO($sPOFile);
$poFile->buildInit();

$language = new Language();

if( ! isset($_GET['LAN_ID']) )
  throw new Exception('Language Target ID was not set!');

$langRecord = $language->findById($_GET['LAN_ID']);

if( ! isset($langRecord['LAN_NAME']) )
  throw new Exception('Language Target ID doesn\'t exist!');
  
$sLanguage = $langRecord['LAN_NAME'];
$sCountry = 'United States';

if ($_GET['LAN_ID'] != 'en') {
  $iCountry = new IsoCountry();
  $iCountryRecord = $iCountry->findById(strtoupper($_GET['LAN_ID']));
  $sCountry = isset($iCountryRecord['IC_NAME']) ?  $iCountryRecord['IC_NAME']: '';
}

//setting headers
$poFile->addHeader('Project-Id-Version'        , PO_SYSTEM_VERSION);
$poFile->addHeader('POT-Creation-Date'         , '');
$poFile->addHeader('PO-Revision-Date'          , date('Y-m-d H:i+0100'));
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
$oCriteria = new Criteria('workflow');
$oCriteria->addSelectColumn(TranslationPeer::TRN_CATEGORY);
$oCriteria->addSelectColumn(TranslationPeer::TRN_ID);
$oCriteria->addSelectColumn(TranslationPeer::TRN_VALUE);
$oCriteria->add(TranslationPeer::TRN_LANG, 'en');
$oDataset = TranslationPeer::doSelectRS($oCriteria);
$oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
$oDataset->next();

while ($aRow1 = $oDataset->getRow()) {
  if( $_GET['LAN_ID'] != 'en' ){
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(TranslationPeer::TRN_VALUE);
    $oCriteria->add(TranslationPeer::TRN_CATEGORY, $aRow1['TRN_CATEGORY']);
    $oCriteria->add(TranslationPeer::TRN_ID, $aRow1['TRN_ID']);
    $oCriteria->add(TranslationPeer::TRN_LANG, $_GET['LAN_ID']);
    $oDataset2 = TranslationPeer::doSelectRS($oCriteria);
    $oDataset2->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset2->next();
    $aRow2 = $oDataset2->getRow();
  } else 
    $aRow2 = $aRow1;
    
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
        $poFile->addTranslation($msgid, $msgstr);
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

foreach ($aXMLForms as $sXmlForm) {
  $sXmlForm = str_replace( chr(92), '/', $sXmlForm);
  $sXmlForm = str_replace( PATH_XMLFORM, '', $sXmlForm);
  $oForm = new Form( $sXmlForm, '', 'en');

  //get all fields of each xmlform
  foreach ($oForm->fields as $sNodeName => $oNode) {
    if (is_object($oNode)) {
      if (trim($oNode->label) != '') {
          //$aEnglishLabel[$oNode->name] = str_replace('"', '\"', stripslashes(ltrim(str_replace(chr(10), '', $oNode->label))));
        $aEnglishLabel[$oNode->name] = stripslashes(trim(str_replace(chr(10), '', $oNode->label)));
        $aOptions[$sXmlForm . '?' . $oNode->name] = $aEnglishLabel[$oNode->name];
      }
      if (isset($oNode->options)) {
        if (!empty($oNode->options)) {
          foreach ($oNode->options as $sKey => $sValue) {
            //$aEnglishLabel[$oNode->name . '-' . $sKey] = str_replace('"', '\"', stripslashes(ltrim(str_replace(chr(10), '', $sValue))));
            $aEnglishLabel[$oNode->name . '-' . $sKey] = stripslashes(ltrim(str_replace(chr(10), '', $sValue)));
            if (isset($aOptions[$sXmlForm . '?' . $oNode->name])) {
              if (!is_array($aOptions[$sXmlForm . '?' . $oNode->name])) {
                  $sAux = $aOptions[$sXmlForm . '?' . $oNode->name];
                  $aOptions[$sXmlForm . '?' . $oNode->name] = array();
              }
            }
            $aOptions[$sXmlForm . '?' . $oNode->name][$sKey] = $aEnglishLabel[$oNode->name . '-' . $sKey];
          }
        }
      }
    }
	} //end foreach
  unset($oForm->fields);
  unset($oForm->tree);
  unset($oForm);

  //now go to the fields array
  $oForm = new Form($sXmlForm, '', $_GET['LAN_ID']);
  $i = 1;
  $iNumberOfFields = count($oForm->fields);
  foreach ($oForm->fields as $sNodeName => $oNode) {
    if (is_object($oNode)) {
      if ( isset($aEnglishLabel[$oNode->name]) ) {
        $msgid = $aEnglishLabel[$oNode->name];
        //$oNode->label = str_replace('"', '\"', stripslashes(ltrim(str_replace(chr(10), '', $oNode->label))));
        $oNode->label = stripslashes(ltrim(str_replace(chr(10), '', $oNode->label)));
      } else 
        $msgid = '';
      
      if ( !in_array(strtolower($oNode->type), $aExceptionFields)) {
        if ((strpos($msgid, '@G::LoadTranslation') === false) && (strpos($oNode->label, '@G::LoadTranslation') === false)) {
          //if (in_array($msgid, $aMsgids)) {
          if ( isset($aMsgids[$msgid]) ) {
              $msgid = trim ( '[' . $sXmlForm . '?' . $oNode->name . '] ' . $oNode->label );
          }
          /*$aLabels[] = array(
              0 => '# ' . $sXmlForm . '?' . $sNodeName,
              1 => '# ' . $sXmlForm,
              2 => '#: ' . $oNode->type . ' - ' . $sNodeName,
              3 => 'msgid "' . $msgid . '"',
              4 => 'msgstr "' . trim($oNode->label) . '"'
          );*/
          
          $poFile->addTranslatorComment($sXmlForm . '?' . $sNodeName);
          $poFile->addTranslatorComment($sXmlForm);
          $poFile->addReference($oNode->type . ' - ' . $sNodeName);
          $poFile->addTranslation($msgid, trim($oNode->label));
          
          //$aMsgids[] = $msgid;
          $aMsgids[$msgid] = 1;
          
          if (isset($oNode->options)) {
            if (!empty($oNode->options)) {
              foreach ($oNode->options as $sKey => $sValue) {
                if ($sKey === '') {
                    $sKey = "''";
                }
                $msgid = '[' . $sXmlForm . '?' . $oNode->name  . '-' . $sKey . ']';
                $poFile->addTranslatorComment($sXmlForm . '?' . $sNodeName . '-'. $sKey);
                $poFile->addTranslatorComment($sXmlForm);
                $poFile->addReference($oNode->type . ' - ' . $sNodeName . ' - ' . $sKey);
                $poFile->addTranslation($msgid, $sValue);
                
                $aMsgids[$msgid] = true;
              }
            } else {
              if (isset($aOptions[$sXmlForm . '?' . $sNodeName])) {
                if (is_array($aOptions[$sXmlForm . '?' . $sNodeName])) {
                  foreach ($aOptions[$sXmlForm . '?' . $sNodeName] as $sKey => $sValue) {
                    if ($sKey === '')
                      $sKey = "''";
                      
                    $msgid = '[' . $sXmlForm . '?' . $oNode->name  . '-' . $sKey . ']';
                    $poFile->addTranslatorComment($sXmlForm . '?' . $sNodeName . '-'. $sKey);
                    $poFile->addTranslatorComment($sXmlForm);
                    $poFile->addReference($oNode->type . ' - ' . $sNodeName . ' - ' . $sKey);
                    $poFile->addTranslation($msgid, $sValue);
                    $aMsgids[$msgid] = true;
                  }
                }
              }
            }
          } else {
            if (isset($aOptions[$sXmlForm . '?' . $sNodeName])) {
              if (is_array($aOptions[$sXmlForm . '?' . $sNodeName])) {
                foreach ($aOptions[$sXmlForm . '?' . $sNodeName] as $sKey => $sValue) {
                  if ($sKey === '')
                      $sKey = "''";
                  
                  $msgid = '[' . $sXmlForm . '?' . $oNode->name  . '-' . $sKey . ']';
                  $poFile->addTranslatorComment($sXmlForm . '?' . $sNodeName . '-'. $sKey);
                  $poFile->addTranslatorComment($sXmlForm);
                  $poFile->addReference($oNode->type . ' - ' . $sNodeName . ' - ' . $sKey);
                  $poFile->addTranslation($msgid, $sValue);
                  $aMsgids[$msgid] = true;
                }
              }
            }
          }
        }
      }
    }
    $i++;
  }
  unset($oForm->fields);
  unset($oForm->tree);
  unset($oForm);
}
/*******************/
//
//$timer->setMarker('end xml files processed');
//$profiling = $timer->getProfiling();
//$timer->stop(); $timer->display(); 
//echo G::getMemoryUsage();
//die;
//g::pr($profiling);

G::streamFile($sPOFile, true);

