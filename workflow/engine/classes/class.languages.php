<?php
/**
 * class.languages.php
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

require_once 'classes/model/Content.php';
require_once 'classes/model/Language.php';
require_once 'classes/model/IsoCountry.php';
require_once 'classes/model/Translation.php';
G::LoadClass('xmlDb');

/**
 * languages - Languages class
 * @package ProcessMaker
 */
class languages {

  /*
  * Log 
  * @param text $text
  * @return void
  */
  function log ( $text ) 
  {
    $logFile = PATH_DATA . 'log' . PATH_SEP . 'query.log';
    $fp      = fopen ( $logFile, 'a+' );
    fwrite ( $fp, date("Y-m-d H:i:s") . " " . $text  . "\n" );
    fclose ( $fp );
  }
  
  /*
  * Import a language file
  * @param string $sLanguageFile
  * @param string $bXml
  * @return void 
  */
  public function importLanguage2($sLanguageFile, $bXml = true)
  {
    try {
      $this->log ( $sLanguageFile );
      $oFile = fopen($sLanguageFile, 'r');
      $bFind = false;
      while (!$bFind && ($sLine = fgets($oFile))) {
        if (strpos($sLine, '"X-Poedit-Language:') !== false) {
          $aAux = explode(':', $sLine);
          $sAux = trim(str_replace('\n"', '', $aAux[1]));
        }
        if (strpos($sLine, '#') !== false) {
          $bFind = true;
        }
      }
      $oCriteria = new Criteria('workflow');
      $oCriteria->addSelectColumn(LanguagePeer::LAN_ID);
      $oCriteria->add(LanguagePeer::LAN_NAME, $sAux, Criteria::LIKE);
      $oDataset = LanguagePeer::doSelectRS($oCriteria);
      $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
      $oDataset->next();
      if ($aRow = $oDataset->getRow()) {
        $sLanguageID = $aRow['LAN_ID'];
      }
      else {
        throw new Exception('The .po file have a invalid language!');
      }
      if (!$bFind) {
        throw new Exception('The .po file have a bad format!');
      }
      $oTranslation = new Translation();
      $sAux = '';
      while ($sLine = fgets($oFile)) {
        if (strpos($sLine, '.xml') === false) {
          $aAux = explode('/', str_replace('# ', '', $sLine));
          if (!($sLine = fgets($oFile))) {
            throw new Exception('The .po file have a bad format!');
          }
          if (!($sLine = fgets($oFile))) {
            throw new Exception('The .po file have a bad format!');
          }
          if (!($sLine = fgets($oFile))) {
            throw new Exception('The .po file have a bad format!');
          }
          $oTranslation->addTranslation($aAux[0], trim(str_replace(chr(10), '', $aAux[1])), $sLanguageID, substr(trim(str_replace(chr(10), '', $sLine)), 8, -1));
          if (!($sLine = fgets($oFile))) {
            throw new Exception('The .po file have a bad format!');
          }
          $sLine = fgets($oFile);
        }
        else {
          $sXmlForm = trim(str_replace('# ', '', $sLine));
          if (!($sLine = fgets($oFile))) {
            throw new Exception('The .po file have a bad format!');
          }
          $aAux       = explode(' - ', $sLine);
          $sFieldName = trim(str_replace(chr(10), '', $aAux[1]));
          if (!($sLine = fgets($oFile))) {
            throw new Exception('The .po file have a bad format!');
          }
          if (!($sLine = fgets($oFile))) {
            throw new Exception('The .po file have a bad format!');
          }
          if (file_exists(PATH_XMLFORM . $sXmlForm) && $bXml) {
            if ($sAux == '') {
              $sAux        = $sXmlForm;
              $oConnection = new DBConnection(PATH_XMLFORM . $sXmlForm, '', '', '', 'myxml');
              $oSession    = new DBSession($oConnection);
            }
            if ($sAux == $sXmlForm) {
              if (count($aAux) == 2) {
                $oDataset = $oSession->Execute('SELECT * FROM dynaForm WHERE XMLNODE_NAME = "' . $sFieldName . '"');
                if ($oDataset->count() > 0) {
                  $oDataset2 = $oSession->Execute('SELECT * FROM dynaForm.' . $sFieldName . ' WHERE XMLNODE_NAME = "' . $sLanguageID . '"');
                  if ($oDataset2->count() == 0) {
                    $oSession->Execute('INSERT INTO dynaForm.' . $sFieldName . ' (XMLNODE_NAME) VALUES ("' . $sLanguageID . '")');
                  }
                  $oSession->Execute('UPDATE dynaForm.' . $sFieldName . ' SET XMLNODE_VALUE = "' . str_replace("'", "\'", str_replace('"', '""', stripslashes(substr(trim(str_replace(chr(10), '', $sLine)), 8, -1)))) . '" WHERE XMLNODE_NAME = "' . $sLanguageID . '"');
                }
                else {
                  $oSession->Execute('INSERT INTO dynaForm (XMLNODE_NAME, XMLNODE_VALUE) VALUES ("' . $sFieldName . '", "")');
                  $oSession->Execute('INSERT INTO dynaForm.' . $sFieldName . ' (XMLNODE_NAME, XMLNODE_VALUE) VALUES ("' . $sLanguageID . '", "' . str_replace("'", "\'", str_replace('"', '""', stripslashes(substr(trim(str_replace(chr(10), '', $sLine)), 8, -1)))) . '")');
                }
                $bDelete = true;
              }
              if (count($aAux) == 3) {
                if ($bDelete) {
                  $oDataset = $oSession->Execute('SELECT * FROM dynaForm WHERE XMLNODE_NAME = "' . $sFieldName . '"');
                  if ($oDataset->count() > 0) {
                    $oDataset2 = $oSession->Execute('SELECT * FROM dynaForm.' . $sFieldName . ' WHERE XMLNODE_NAME = "' . $sLanguageID . '"');
                    if ($oDataset2->count() == 0) {
                      $oSession->Execute('INSERT INTO dynaForm.' . $sFieldName . ' (XMLNODE_NAME) VALUES ("' . $sLanguageID . '")');
                    }
                    $oDataset = $oSession->Execute('SELECT * FROM dynaForm.' . $sFieldName . ' WHERE XMLNODE_NAME = "' . $sLanguageID . '"');
                    if ($oDataset->count() > 0) {
                      $oSession->Execute('DELETE FROM dynaForm.' . $sFieldName . '.' . $sLanguageID . ' WHERE 1');
                    }
                    else {
                      $oSession->Execute('INSERT INTO dynaForm.' . $sFieldName . ' (XMLNODE_NAME, XMLNODE_VALUE) VALUES ("' . $sLanguageID . '", "")');
                    }
                  }
                  $bDelete = false;
                }
                $oSession->Execute('INSERT INTO dynaForm.' . $sFieldName . '.' . $sLanguageID . ' (XMLNODE_NAME,XMLNODE_VALUE,name) VALUES ("option","' . str_replace("'", "\'", str_replace('"', '""', stripslashes(substr(trim(str_replace(chr(10), '', $sLine)), 8, -1)))) . '","' . trim(str_replace(chr(10), '', $aAux[2])) . '")');
              }
            }
            else {
              $oConnection = new DBConnection(PATH_XMLFORM . $sXmlForm, '', '', '', 'myxml');
              $oSession = new DBSession($oConnection);
              if (count($aAux) == 2) {
                $oDataset = $oSession->Execute('SELECT * FROM dynaForm WHERE XMLNODE_NAME = "' . $sFieldName . '"');
                if ($oDataset->count() > 0) {
                  $oDataset2 = $oSession->Execute('SELECT * FROM dynaForm.' . $sFieldName . ' WHERE XMLNODE_NAME = "' . $sLanguageID . '"');
                  if ($oDataset2->count() == 0) {
                    $oSession->Execute('INSERT INTO dynaForm.' . $sFieldName . ' (XMLNODE_NAME) VALUES ("' . $sLanguageID . '")');
                  }
                  $oSession->Execute('UPDATE dynaForm.' . $sFieldName . ' SET XMLNODE_VALUE = "' . str_replace("'", "\'", str_replace('"', '""', stripslashes(substr(trim(str_replace(chr(10), '', $sLine)), 8, -1)))) . '" WHERE XMLNODE_NAME = "' . $sLanguageID . '"');
                }
                else {
                  $oSession->Execute('INSERT INTO dynaForm (XMLNODE_NAME, XMLNODE_VALUE) VALUES ("' . $sFieldName . '", "")');
                  $oSession->Execute('INSERT INTO dynaForm.' . $sFieldName . ' (XMLNODE_NAME, XMLNODE_VALUE) VALUES ("' . $sLanguageID . '", "' . str_replace("'", "\'", str_replace('"', '""', stripslashes(substr(trim(str_replace(chr(10), '', $sLine)), 8, -1)))) . '")');
                }
                $bDelete = true;
              }
              if (count($aAux) == 3) {
                if ($bDelete) {
                  $oDataset = $oSession->Execute('SELECT * FROM dynaForm WHERE XMLNODE_NAME = "' . $sFieldName . '"');
                  if ($oDataset->count() > 0) {
                    $oDataset2 = $oSession->Execute('SELECT * FROM dynaForm.' . $sFieldName . ' WHERE XMLNODE_NAME = "' . $sLanguageID . '"');
                    if ($oDataset2->count() == 0) {
                      $oSession->Execute('INSERT INTO dynaForm.' . $sFieldName . ' (XMLNODE_NAME) VALUES ("' . $sLanguageID . '")');
                    }
                    $oDataset = $oSession->Execute('SELECT * FROM dynaForm.' . $sFieldName . ' WHERE XMLNODE_NAME = "' . $sLanguageID . '"');
                    if ($oDataset->count() > 0) {
                      $oSession->Execute('DELETE FROM dynaForm.' . $sFieldName . '.' . $sLanguageID . ' WHERE 1');
                    }
                    else {
                      $oSession->Execute('INSERT INTO dynaForm.' . $sFieldName . ' (XMLNODE_NAME, XMLNODE_VALUE) VALUES ("' . $sLanguageID . '", "")');
                    }
                  }
                  $bDelete = false;
                }
                $oSession->Execute('INSERT INTO dynaForm.' . $sFieldName . '.' . $sLanguageID . ' (XMLNODE_NAME,XMLNODE_VALUE,name) VALUES ("option","' . str_replace("'", "\'", str_replace('"', '""', stripslashes(substr(trim(str_replace(chr(10), '', $sLine)), 8, -1)))) . '","' . trim(str_replace(chr(10), '', $aAux[2])) . '")');
              }
              $sAux = $sXmlForm;
            }
          }
          if (!($sLine = fgets($oFile))) {
            throw new Exception('The .po file have a bad format!');
          }
          $sLine = fgets($oFile);
        }
      }
      fclose($oFile);
      
      $oLanguage = new Language();
      $oLanguage->update(array('LAN_ID' => $sLanguageID, 'LAN_ENABLED' => '1'));
      if ($bXml) {
        Translation::generateFileTranslation($sLanguageID);
      }
      $this->log ( "checking and updating CONTENT");
      $oCriteria = new Criteria('workflow');
      $oCriteria->addSelectColumn(ContentPeer::CON_CATEGORY);
      $oCriteria->addSelectColumn(ContentPeer::CON_ID);
      $oCriteria->addSelectColumn(ContentPeer::CON_VALUE);
      $oCriteria->add(ContentPeer::CON_LANG, 'en');
      $oCriteria->add(ContentPeer::CON_VALUE, '', Criteria::NOT_EQUAL );
      $oDataset = ContentPeer::doSelectRS($oCriteria);
      $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
      $oDataset->next();
      $oContent = new Content();
      while ($aRow = $oDataset->getRow()) {
        $oContent->load($aRow['CON_CATEGORY'], '', $aRow['CON_ID'], $sLanguageID);
        $oDataset->next();
      }
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }


  public function importLanguage($sLanguageFile, $updateXml = true)
  {
    try {
      G::LoadSystem('i18n_po');
      $POFile = new i18n_PO($sLanguageFile);
      $POFile->readInit();
      $POHeaders = $POFile->getHeaders();
      
      /*getting the PO Language definition*/
      $langName = $POHeaders['X-Poedit-Language'];
      //find the lang id
      $language = new Language();
      $langRecord = $language->findByLanName($langName);
      
      if( ! isset($langRecord['LAN_ID']) ) //if the language doesn't exist abort
        throw new Exception('The .po file has a invalid X-Poedit-Language definition!');
      
      $languageID = $langRecord['LAN_ID'];

      /*getting the PO Language definition*/
      $countryName = $POHeaders['X-Poedit-Country'];
      if( $countryName != '.' ) {
        $isoCountry = new IsoCountry();
        $countryRecord = $isoCountry->findByIcName($countryName);

        if( ! isset($countryRecord['IC_UID']) ) //if the language doesn't exist abort
          throw new Exception('The .po file has a invalid X-Poedit-Country definition!');

        $countryID = $countryRecord['IC_UID'];
        //define locale
        $LOCALE = "$languageID-$countryID";
      } else {
        $LOCALE = $languageID;
      }
      
      $oTranslation = new Translation();
      $countItems = 0;
      $countItemsSuccess = 0;
      
      while( $rowTranslation = $POFile->getTranslation() ) {
        $countItems++;
        
        if ( ! isset($POFile->translatorComments[0]) || ! isset($POFile->translatorComments[1]) || ! isset($POFile->references[0]) ) {
          throw new Exception('The .po file has not valid directives for Processmaker!');
        }
        
        $identifier = $POFile->translatorComments[0];
        $context    = $POFile->translatorComments[1];
        $reference  = $POFile->references[0];
        
        if( $identifier == 'TRANSLATION') {
          
          list($category, $id) = explode('/', $context);
          $result = $oTranslation->addTranslation(
            $category,
            $id,
            $LOCALE,
            trim(str_replace(chr(10), '', $rowTranslation['msgstr']))
          );
          if( $result['codError'] == 0 )
            $countItemsSuccess++;
        } else if( $updateXml ){
          $xmlForm = $context;
          $codes   = explode(' - ', $reference);
          $fieldName = trim($codes[1]);
          
          if( ! file_exists(PATH_XMLFORM . $xmlForm) ) {
            continue;
          }
          
          G::LoadSystem('dynaformhandler');
          $dynaform = new dynaFormHandler(PATH_XMLFORM . $xmlForm);
          
          if( sizeof($codes) == 2 ) { //is a normal node
            $dynaform->addChilds($fieldName, Array($LOCALE=>$rowTranslation['msgstr']));
          } else if( sizeof($codes) == 3 ) { //is a node child for a language node
            $name = trim($codes[2]);
            $childNode = Array(
              Array('name'=>'option', 'value'=>$rowTranslation['msgstr'], 'attributes'=>Array('name'=>$name))
            );

            $dynaform->addChilds($fieldName, Array($LOCALE=>NULL), $childNode);
          }
          $countItemsSuccess++;
        }
      }
     
      
      $oLanguage = new Language();
      $oLanguage->update(array('LAN_ID' => $languageID, 'LAN_ENABLED' => '1'));

      Translation::generateFileTranslation($LOCALE);
      Translation::addTranslationEnvironment($LOCALE, $POHeaders, $countItemsSuccess);
      
      $this->log( "checking and updating CONTENT");
      $content = new Content();
      $content->regenerateContent($languageID);
      
      //fill the results
      $results = new stdClass();
      $results->recordsCount        = $countItems;
      $results->recordsCountSuccess = $countItemsSuccess;
      $results->lang                = $languageID;
      $results->headers             = $POHeaders;
      
      return $results;
    }
    catch (Exception $oError) {
      throw($oError);
    }
  }
} // languages