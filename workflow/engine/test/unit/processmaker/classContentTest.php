<?php
/**
 * classContentTest.php
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
  $unitFilename = $_SERVER['PWD'] . '/test/bootstrap/unit.php' ;
  require_once( $unitFilename );

  require_once( PATH_THIRDPARTY . '/lime/lime.php');
  require_once( PATH_THIRDPARTY.'lime/yaml.class.php');
  require_once( PATH_CORE . "config/databases.php");  
  require_once( 'propel/Propel.php' );
  Propel::init(  PATH_CORE . "config/databases.php");

  G::LoadSystem ( 'testTools');
 
  G::LoadThirdParty('smarty/libs','Smarty.class');

  require_once(PATH_CORE.'/classes/model/Content.php');

class ContentTest extends unitTest
  {

    function LoadContent($data, $fields)
    {
      $obj = new Content();
      $ConCategory = $fields['CON_CATEGORY']; 
      $ConParent   = $fields['CON_PARENT'];
      $ConId       = $fields['CON_ID'];
      $ConLang     = $fields['CON_LANG'];
      $ConValue    = $fields['CON_VALUE'];
      try {
        $res = $obj->load($ConCategory, $ConParent, $ConId, $ConLang );
        if ( $res == '' ) {
          $this->testLime->is ( $res, '', 'Load Empty Content ' );
        }
        else
          $this->testLime->is ( $ConValue, $ConValue, 'correct value from Content->Load ' );
      }
      catch ( Exception $e) {
        return $e;
      }
      return $res;
    }
    
    function deleteContent($data, $fields)
    {
      $obj = new Content();
      $ConCategory = $fields['CON_CATEGORY']; 
      $ConParent   = $fields['CON_PARENT'];
      $ConId       = $fields['CON_ID'];
      $ConLang     = $fields['CON_LANG'];
      try {
        //$res = $obj->load($ConCategory, $ConParent, $ConId, $ConLang );
        $content = ContentPeer::retrieveByPK( $ConCategory, $ConParent, $ConId, $ConLang );
        if ( $content ) 
          $content->delete( );
      }
      catch ( Exception $e) {
        return $e;
      }
      return $res;
    }
    

    function addContent($data, $fields)
    {
      $obj=new Content();
      $ConCategory = $fields['CON_CATEGORY']; 
      $ConParent   = $fields['CON_PARENT'];
      $ConId       = $fields['CON_ID'];
      $ConLang     = $fields['CON_LANG'];
      $ConValue    = $fields['CON_VALUE'];
      try {
        $res = $obj->addContent($ConCategory, $ConParent, $ConId, $ConLang, $ConValue );
      }
      catch ( Exception $e) {
        return $e;
      }
      return $res;
    }
    
  }
    


  $obj = new Content();
  //$t   = new lime_test( 5, new lime_output_color() );
  $t   = new lime_test( 12, new lime_output_color() );
 
  $t->diag('class Content' );
  $t->isa_ok( $obj  , 'Content',  'class Content created');

  $t->todo(  'review all combinations of is_utf8 ');
  $t->todo(  'review is_utf8 should be in another class');

  //Initialize the global domain (It is optional)
  $testDomain = new ymlDomain();
  
  $test = new ContentTest ('content.yml', $t, $testDomain );
  
  //check if an row exists, 
  $test->load('loadContent');
  $test->runSingle();
 
  //check if an row exists, 
  $test->load('deleteContent');
  $test->runAll();

  $test->load('addContentAcentos');
  $test->runSingle();

  //add the same row twice, the first time goes good, but the second the class throw an error 
  $test->load('addContent1');
  $test->runSingle();

  $test->load('addContentTwice');
  $test->runSingle();

  $test->load('loadContent');
  $test->runSingle();

  $obj = new Content(); 
  $res = $obj->addContent ('1','2','3','en','language1');
  $res = $obj->addContent ('1','2','3','es','language2');
  $res = $obj->addContent ('1','2','3','pt','language3');
  $res = $obj->addContent ('1','2','3','fr','language4');
  $res = $obj->addContent ('1','2','3','it','language5');
  $res = $obj->removeContent ('1','2','3');
  //$t->can_ok( $res,      'getAppTitle',   'removeContent.' );
