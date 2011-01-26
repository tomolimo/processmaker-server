<?php
/**
 * Translation.php
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

require_once 'classes/model/om/BaseTranslation.php';


/**
 * Skeleton subclass for representing a row from the 'TRANSLATION' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    workflow.classes.model
 */
class Translation extends BaseTranslation {

  public static $meta;
  public static $localeSeparator = '-';

  private $envFilePath;

  function __construct(){
    $this->envFilePath = PATH_DATA . "META-INF" . PATH_SEP . "translations.env";
  }

  function getAllCriteria(){
    
    //SELECT * from TRANSLATION WHERE TRN_LANG = 'en' order by TRN_CATEGORY, TRN_ID 
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(TranslationPeer::TRN_ID);
    $oCriteria->addSelectColumn(TranslationPeer::TRN_CATEGORY);
    $oCriteria->addSelectColumn(TranslationPeer::TRN_LANG);
    $oCriteria->addSelectColumn(TranslationPeer::TRN_VALUE);
    //$c->add(TranslationPeer::TRN_LANG, 'en');

    return $oCriteria;
  }



  /* Load strings from a Database .
   * @author Fernando Ontiveros <fernando@colosa.com>
   * @parameter $languageId   (es|en|...).
  */

  function generateFileTranslation ($languageId = '') {
    $translation = Array();
    $translationJS = Array();
    
    if ($languageId === '')
      $languageId = defined('SYS_LANG') ? SYS_LANG : 'en';

    $c = new Criteria();
    $c->add(TranslationPeer::TRN_LANG,  $languageId );
    $c->addAscendingOrderByColumn ( 'TRN_CATEGORY' );
    $c->addAscendingOrderByColumn ( 'TRN_ID' );
    $tranlations = TranslationPeer::doSelect($c);

    $cacheFile   = PATH_LANGUAGECONT . "translation." . $languageId;
    $cacheFileJS = PATH_CORE . 'js' . PATH_SEP . 'labels' . PATH_SEP . $languageId.".js";

    foreach ( $tranlations as $key => $row ) {
      if ( $row->getTrnCategory() === 'LABEL' ) {
        $translation[ $row->getTrnId() ] = $row->getTrnValue();
      }
      if ( $row->getTrnCategory() === 'JAVASCRIPT') {
        $translationJS[ $row->getTrnId() ] = $row->getTrnValue();
      }
    }
	
    try {
      
      if( ! is_dir(dirname($cacheFile)) ) 
        G::mk_dir(dirname($cacheFile));

      if (!file_exists($cacheFile) || !file_exists($cacheFileJS)){
          $error = 'translation file does not exist';
          throw new Exception($error);
      }
      $f = fopen( $cacheFile , 'w+');
      fwrite( $f , "<?php\n" );
      fwrite( $f , '$translation =' . 'unserialize(\'' . addcslashes( serialize ( $translation ), '\\\'' ) . "');\n");
      fwrite( $f , "?>" );
      fclose( $f );

      $json=new Services_JSON();

      $f = fopen( $cacheFileJS , 'w');
      fwrite( $f , "var G_STRINGS =". $json->encode( $translationJS ) . ";\n");
      fclose( $f );

      $res['cacheFile'] = $cacheFile;
      $res['cacheFileJS'] = $cacheFileJS;
      $res['rows']   = count (  $translation );
      $res['rowsJS'] = count (  $translationJS );
      return $res;    
    } catch( Exception $e ) {
      //echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

  }

  /**
   * returns an array with
   * codError      0 - no error, < 0 error
   * rowsAffected  0,1 the number of rows affected
   * message       message error.
   */
  function addTranslation ( $category, $id, $languageId, $value  ) {
    //if exists the row in the database propel will update it, otherwise will insert.
    $tr = TranslationPeer::retrieveByPK( $category, $id, $languageId );
    if ( ! ( is_object ( $tr ) &&  get_class ($tr) == 'Translation' ) ) {
      $tr = new Translation();
    }
    $tr->setTrnCategory( $category );
    $tr->setTrnId( $id );
    $tr->setTrnLang( $languageId);
    $tr->setTrnValue( $value );

    if ($tr->validate() ) {
      // we save it, since we get no validation errors, or do whatever else you like.
      $res = $tr->save();
    }
    else {
      // Something went wrong. We can now get the validationFailures and handle them.
      $msg = '';
      $validationFailuresArray = $tr->getValidationFailures();
      foreach($validationFailuresArray as $objValidationFailure) {
        $msg .= $objValidationFailure->getMessage() . "<br/>";
      }
      return array ( 'codError' => -100, 'rowsAffected' => 0, 'message' => $msg );
    }
    return array ( 'codError' => 0, 'rowsAffected' => $res, 'message' => '');
    //to do: uniform  coderror structures for all classes
  }

  function remove($sCategory, $sId, $sLang) {
    $oTranslation = TranslationPeer::retrieveByPK($sCategory, $sId, $sLang);
    if ( ( is_object ( $oTranslation ) &&  get_class ($oTranslation) == 'Translation' ) ) {
      $oTranslation->delete();
    }
  }

  function addTranslationEnvironment($locale, $headers, $numRecords)
  {
    $filePath = $this->envFilePath;
    $environments = Array();
    
    if( file_exists($filePath) ) {
      $environments = unserialize(file_get_contents($filePath));
    }
    
    $environment['LOCALE']  = $locale;
    $environment['HEADERS'] = $headers;
    $environment['DATE']    = date('Y-m-d H:i:s');
    $environment['NUM_RECORDS'] = $numRecords;
    $environment['LANGUAGE'] = $headers['X-Poedit-Language'];
    $environment['COUNTRY']  = $headers['X-Poedit-Country'];

    if( strpos($locale, self::$localeSeparator) !== false ) {
      list($environment['LAN_ID'], $environment['IC_UID']) = explode(self::$localeSeparator, strtoupper($locale));
      $environments[$environment['LAN_ID']][$environment['IC_UID']] = $environment;
    } else {
      $environment['LAN_ID'] = strtoupper($locale);
      $environment['IC_UID'] = '';
      $environments[$locale]['__INTERNATIONAL__'] = $environment;
    }
    
    
    file_put_contents($filePath, serialize($environments));
  }
  
  function removeTranslationEnvironment($locale)
  {
    $filePath = $this->envFilePath;
    if( strpos($locale, self::$localeSeparator) !== false ) {
      list($LAN_ID, $IC_UID) = explode('-', strtoupper($locale));
    } else {
      $LAN_ID = $locale;
      $IC_UID = '__INTERNATIONAL__';
    }

    if( file_exists($filePath) ) {
      $environments = unserialize(file_get_contents($filePath));
      if( ! isset($environments[$LAN_ID][$IC_UID]) )
        return NULL;
      
      unset($environments[$LAN_ID][$IC_UID]);
      file_put_contents($filePath, serialize($environments));
    }
  }

  function getTranslationEnvironments(){
    $filePath = $this->envFilePath;
    $envs = Array();
    
    if( ! file_exists($filePath) ) {
      //the transaltions table file doesn't exist, then build it

      if( ! is_dir(dirname($this->envFilePath)) )
        G::mk_dir(dirname($this->envFilePath));
      
      $translationsPath = PATH_CORE . "content" . PATH_SEP . 'translations' . PATH_SEP;
      $basePOFile = $translationsPath . 'english' . PATH_SEP . 'processmaker.en.po';
      
      $params = self::getInfoFromPOFile($basePOFile);
      $this->addTranslationEnvironment($params['LOCALE'], $params['HEADERS'], $params['COUNT']);
      
      //getting more lanuguage translations
      $files = glob($translationsPath . "*.po");
      foreach( $files as $file ){
        $params = self::getInfoFromPOFile($file);
        $this->addTranslationEnvironment($params['LOCALE'], $params['HEADERS'], $params['COUNT']);
      }
    }
    $envs = unserialize(file_get_contents($filePath));

    $environments = Array();
    foreach($envs as $LAN_ID => $rec1 ){
      foreach($rec1 as $IC_UID => $rec2 ){
        $environments[] = $rec2;
      }
    }
    
    return $environments;

    /*G::LoadSystem('dbMaintenance');
      $o = new DataBaseMaintenance('localhost', 'root', 'atopml2005');
      $o->connect('wf_os');
      $r = $o->query('select * from ISO_COUNTRY');
      foreach($r as $i=>$v){
        $r[$i]['IC_NAME'] = utf8_encode($r[$i]['IC_NAME']);
        unset($r[$i]['IC_SORT_ORDER']);
      }
      $r1 = $o->query('select * from LANGUAGE');
      $r2 = Array();
      foreach($r1 as $i=>$v){
        $r2[$i]['LAN_NAME'] = utf8_encode($r1[$i]['LAN_NAME']);
        $r2[$i]['LAN_ID'] = utf8_encode($r1[$i]['LAN_ID']);
      }
      $s = Array('ISO_COUNTRY'=>$r, 'LANGUAGE'=>$r2);
      file_put_contents($translationsPath . 'pmos-translations.meta', serialize($s));
     */
  }

  function getInfoFromPOFile($file){
    G::loadClass('i18n_po');
    $POFile = new i18n_PO($file);
    $POFile->readInit();
    $POHeaders = $POFile->getHeaders();

    if( $POHeaders['X-Poedit-Country'] != '.' ) {
      $country  = self::getTranslationMetaByCountryName($POHeaders['X-Poedit-Country']);
    } else {
      $country = '.';
    }
    $language = self::getTranslationMetaByLanguageName($POHeaders['X-Poedit-Language']);

    if( $language !== false ) {
      if( $country !== false ) {
        if( $country != '.') {
          $LOCALE = $language['LAN_ID'] . '-' . $country['IC_UID'];
        } else if( $country == '.' ) {
          //this a trsnlation file with a language international, no country name was set
          $LOCALE = $language['LAN_ID'];
        } else
          throw new Exception('PO File Error: "'.$file.'" has a invalid country definition!');
      } else
        throw new Exception('PO File Error: "'.$file.'" has a invalid country definition!');
    } else
      throw new Exception('PO File Error: "'.$file.'" has a invalid language definition!');

    $countItems = 0;
    try {
      while( $rowTranslation = $POFile->getTranslation() ) {
        $countItems++;
      }
    } catch(Exception $e) {
      $countItems = '-';
    }

    return Array('LOCALE'=>$LOCALE, 'HEADERS'=>$POHeaders , 'COUNT'=>$countItems);
  }

  function getTranslationEnvironment($locale){
    $filePath = $this->envFilePath;
    $environments = Array();

    if( ! file_exists($filePath) ) {
      throw new Exception("The file $filePath doesn't exist");
    }

    $environments = unserialize(file_get_contents($filePath));
    if( strpos($locale, self::$localeSeparator) !== false ) {
      list($LAN_ID, $IC_UID) = explode(self::localeSeparator, strtoupper($locale));
    } else {
      $LAN_ID = $locale;
      $IC_UID = '__INTERNATIONAL__';
    }

    if( isset($environments[$LAN_ID][$IC_UID]) )
      return $environments[$LAN_ID][$IC_UID];
    else 
      return false;
  }
  
  function saveTranslationEnvironment($locale, $data){
    $filePath = $this->envFilePath;
    $environments = Array();

    if( ! file_exists($filePath) ) {
      throw new Exception("The file $filePath doesn't exist");
    }

    $environments = unserialize(file_get_contents($filePath));
    if( strpos($locale, self::$localeSeparator) !== false ) {
      list($LAN_ID, $IC_UID) = explode(self::localeSeparator, strtoupper($locale));
    } else {
      $LAN_ID = $locale;
      $IC_UID = '__INTERNATIONAL__';
    }

    $environments[$LAN_ID][$IC_UID] = $data;
    file_put_contents($filePath, serialize($environments));
  }

  function getTranslationMeta(){
    $translationsPath = PATH_CORE . "content" . PATH_SEP . 'translations' . PATH_SEP;
    $translationsTable = unserialize(file_get_contents($translationsPath . 'pmos-translations.meta'));
    return $translationsTable;
  }

  function getTranslationMetaByCountryName($IC_NAME){
    $translationsTable = self::getTranslationMeta();

    foreach ($translationsTable['ISO_COUNTRY'] as $row) {
      if( $row['IC_NAME'] == $IC_NAME )
        return $row;
    }
    return false;
  }

  function getTranslationMetaByLanguageName($LAN_NAME){
    $translationsTable = self::getTranslationMeta();

    foreach ($translationsTable['LANGUAGE'] as $row) {
      if( $row['LAN_NAME'] == $LAN_NAME )
        return $row;
    }
    return false;
  }
} // Translation










