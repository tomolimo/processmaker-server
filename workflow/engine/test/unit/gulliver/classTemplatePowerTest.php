<?php
/**
 * classTemplatePowerTest.php
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
  if ( !defined ('PATH_THIRDPARTY') ) {
    require_once(  $_SERVER['PWD']. '/test/bootstrap/unit.php');
  }

  require_once( PATH_THIRDPARTY . 'lime/lime.php');
  define ( 'G_ENVIRONMENT', G_TEST_ENV);
  require_once( PATH_CORE . 'config' . PATH_SEP . 'environments.php');

  global $G_ENVIRONMENTS;
  if ( isset ( $G_ENVIRONMENTS ) ) {
    $dbfile = $G_ENVIRONMENTS[ G_TEST_ENV ][ 'dbfile'];
    if ( !file_exists ( $dbfile ) ) {
      printf("%s \n", pakeColor::colorize( "dbfile $dbfile doesn't exist for environment " . G_ENVIRONMENT  , 'ERROR'));
      exit (200);
    }
    else
     include ( $dbfile );
  }
  else
   exit (201);
	G::LoadThirdParty('pear/json','class.json');
	G::LoadThirdParty('smarty/libs','Smarty.class');
	G::LoadSystem ( 'xmlform');
	G::LoadSystem ( 'xmlDocument');
	G::LoadSystem ( 'form');
	G::LoadSystem ( 'templatePower');
	$t = new lime_test(16, new lime_output_color());
	$obj = new TemplatePowerParser( 'a', 'b'  );
	$method = array ( );
	$testItems = 0;
	$class_methods = get_class_methods('TemplatePowerParser');
	foreach ($class_methods as $method_name) {
	    $methods[ $testItems ] = $method_name;
	    $testItems++;
	}
	$t->diag('class TemplatePowerParser' );
	$t->is(  $testItems , 8,  "class TemplatePowerParser " . $testItems . " methods." );
	$t->isa_ok( $obj  , 'TemplatePowerParser',  'class TemplatePowerParser created');
	$t->can_ok( $obj,      '__prepare',   '__prepare()');
	$t->can_ok( $obj,      '__prepareTemplate',   '__prepareTemplate()');
	$t->can_ok( $obj,      '__parseTemplate',   '__parseTemplate()');
	$obj = new TemplatePower(  ); 
	$t->can_ok( $obj,      '__outputContent',   '__outputContent()');
	$t->can_ok( $obj,      '__printVars',   '__printVars()');
	$t->can_ok( $obj,      'prepare',   'prepare()');
	$t->can_ok( $obj,      'newBlock',   'newBlock()');
	$t->can_ok( $obj,      'assignGlobal',   'assignGlobal()');
	$t->can_ok( $obj,      'assign',   'assign()');
	$t->can_ok( $obj,      'gotoBlock',   'gotoBlock()');
	$t->can_ok( $obj,      'getVarValue',   'getVarValue()');
	$t->can_ok( $obj,      'printToScreen',   'printToScreen()');
	$t->can_ok( $obj,      'getOutputContent',   'getOutputContent()');
	$t->todo(  'review all pendings in this class');
