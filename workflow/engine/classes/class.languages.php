<?php
/**
 * class.languages.php
 * @package workflow.engine.ProcessMaker
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2011 Colosa Inc.
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
 * @package workflow.engine.ProcessMaker
 */
class languages {

  /*
  * Log 
  * @param text $text
  * @return void
  */
  function log ( $text ) 
  {
    $logDir = PATH_DATA . 'log';
    if (!file_exists($logDir))
      if (!mkdir($logDir))
        return;
    $logFile = "$logDir/query.log";
    $fp      = fopen ( $logFile, 'a+' );
    if ($fp !== false) {
      fwrite ( $fp, date("Y-m-d H:i:s") . " " . $text  . "\n" );
      fclose ( $fp );
    }
  }
  
  /*
  * Import a language file
  * 
  * @author Erik Amaru Ortiz <erik@colosa.com, aortiz.erik@gmail>
  * @param string $sLanguageFile
  * @param string $bXml
  * @return void 
  */
  public function importLanguage($sLanguageFile, $updateXml = true, $updateDB = true)
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

         foreach($POFile->translatorComments as $a=>$aux){   
          $aux = trim($aux);
          if ( $aux == 'TRANSLATION')
            $identifier = $aux;
          else {
            $var = explode('/',$aux);
            if ($var[0]=='LABEL')
              $context = $aux;
            if ($var[0]=='JAVASCRIPT')
              $context = $aux;
          }
          if (preg_match('/^([a-zA-Z_-]+)\/([a-zA-Z_-]+\.xml\?)/', $aux, $match)) 
            $identifier = $aux;
          else{
            if (preg_match('/^([a-zA-Z_-]+)\/([a-zA-Z_-]+\.xml$)/', $aux, $match)) 
            $context = $aux;            
          }
        }
        
        $reference  = $POFile->references[0]; 
 
        if( $identifier == 'TRANSLATION') {
          if ($updateDB) {
            list($category, $id) = explode('/', $context);
            $result = $oTranslation->addTranslation(
              $category,
              $id,
              $LOCALE,
              trim(str_replace(chr(10), '', stripslashes($rowTranslation['msgstr'])))
            );
            if( $result['codError'] == 0 )
              $countItemsSuccess++;
          }
        } else if( $updateXml ){
          $xmlForm = $context;
          //erik: expresion to prevent and hable correctly dropdown values like -1, -2 etc.
          preg_match('/^([\w_]+)\s-\s([\w_]+)\s*-*\s*([\w\W]*)$/', $reference, $match);
          
          if( ! file_exists(PATH_XMLFORM . $xmlForm) ) {
            continue;
          }
          
          G::LoadSystem('dynaformhandler');
          $dynaform = new dynaFormHandler(PATH_XMLFORM . $xmlForm);
          $fieldName = $match[2];

          $codes = explode('-', $reference);
          
          if( sizeof($codes) == 2 ) { //is a normal node
            $dynaform->addChilds($fieldName, Array($LOCALE=>$rowTranslation['msgstr']));
          } else if( sizeof($codes) > 2 ) { //is a node child for a language node
          	$name = $match[3] == "''" ? '' : $match[3];
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

      $trn = new Translation();
      $trn->generateFileTranslation($LOCALE);
      $trn->addTranslationEnvironment($LOCALE, $POHeaders, $countItemsSuccess);
      
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
